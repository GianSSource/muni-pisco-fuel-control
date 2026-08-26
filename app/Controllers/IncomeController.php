<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use PDO;
use Throwable;

final class IncomeController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $incomes = [];
        try {
            $db = Database::getConnection();
            $stmt = $db->query('SELECT * FROM fuel_incomes ORDER BY created_at DESC');
            $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {}

        $this->view('income/index', [
            'title' => 'Ingresos de Combustible',
            'incomes' => $incomes,
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function create(): void
    {
        $this->requireAdmin();

        $this->view('income/form', [
            'title' => 'Registrar Ingreso de Combustible',
            'csrfToken' => $this->csrfToken(),
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $supplier = trim((string) ($_POST['supplier'] ?? ''));
        $fuelType = trim((string) ($_POST['fuel_type'] ?? 'Diesel B5 S-50'));
        $quantity = (float) ($_POST['quantity'] ?? 0);
        $invoice = trim((string) ($_POST['invoice'] ?? ''));
        $notes = trim((string) ($_POST['notes'] ?? ''));

        if ($quantity <= 0) {
            header('Location: ' . BASE_URL . 'ingresos/nuevo?error=La+cantidad+debe+ser+mayor+a+0');
            exit;
        }

        try {
            $db = Database::getConnection();

            // Auto-crear tabla fuel_incomes si no existe
            $db->exec('CREATE TABLE IF NOT EXISTS fuel_incomes (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED NULL,
                supplier VARCHAR(160) NOT NULL,
                fuel_type VARCHAR(80) NOT NULL DEFAULT "Diesel B5 S-50",
                gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
                gallons_remaining DECIMAL(10,2) NOT NULL DEFAULT 0,
                unit_price DECIMAL(10,2) NOT NULL DEFAULT 0,
                receipt_number VARCHAR(80) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB');

            // Asegurar columna fuel_type si la tabla fue creada previamente sin ella
            try {
                $db->exec('ALTER TABLE fuel_incomes ADD COLUMN fuel_type VARCHAR(80) NOT NULL DEFAULT "Diesel B5 S-50"');
            } catch (Throwable $t) {}

            // Sincronizar saldo de lote activo
            $receiptNum = 'REC-ING-' . time();
            $userId = $_SESSION['user']['id'] ?? 1;

            $stmt = $db->prepare('INSERT INTO fuel_incomes (user_id, supplier, fuel_type, gallons, gallons_remaining, unit_price, receipt_number, created_at) VALUES (:user_id, :supplier, :fuel_type, :gallons, :gallons_remaining, 16.50, :receipt_number, NOW())');
            $stmt->execute([
                'user_id' => $userId,
                'supplier' => $supplier !== '' ? mb_substr($supplier, 0, 100) : 'Proveedor Municipal',
                'fuel_type' => $fuelType !== '' ? $fuelType : 'Diesel B5 S-50',
                'gallons' => $quantity,
                'gallons_remaining' => $quantity,
                'receipt_number' => $receiptNum,
            ]);

            header('Location: ' . BASE_URL . 'ingresos?success=Ingreso+de+' . $quantity . '+Gal+registrado+correctamente.+Stock+actualizado.');
            exit;
        } catch (Throwable $e) {
            header('Location: ' . BASE_URL . 'ingresos/nuevo?error=Error+al+registrar+ingreso:+' . urlencode($e->getMessage()));
            exit;
        }
    }
}
