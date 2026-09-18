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
            (new \App\Models\Fuel())->recalculateStockBalances();

            $db = Database::getConnection();
            $stmt = $db->query('SELECT * FROM fuel_incomes ORDER BY created_at DESC');
            $incomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
        }

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

        $catalog = new \App\Models\Catalog();

        $this->view('income/form', [
            'title' => 'Registrar Ingreso de Combustible',
            'csrfToken' => $this->csrfToken(),
            'fuelTypes' => $catalog->getFuelTypes(),
            'suppliers' => $catalog->getSuppliers(),
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

        if ($quantity > 500000) {
            header('Location: ' . BASE_URL . 'ingresos/nuevo?error=La+cantidad+no+puede+superar+los+500,000+galones');
            exit;
        }

        // Validación contra catálogo: el proveedor y el tipo de combustible
        // deben existir como registro activo en system_catalogs. Evita que
        // se guarde un valor manipulado desde fuera del formulario (curl,
        // DevTools, etc).
        $catalogCheck = new \App\Models\Catalog();
        if (!$catalogCheck->existsInCategory('supplier', $supplier)) {
            header('Location: ' . BASE_URL . 'ingresos/nuevo?error=El+proveedor+seleccionado+no+es+valido.+Actualiza+la+pagina+e+intenta+de+nuevo');
            exit;
        }
        if (!$catalogCheck->existsInCategory('fuel_type', $fuelType)) {
            header('Location: ' . BASE_URL . 'ingresos/nuevo?error=El+tipo+de+combustible+seleccionado+no+es+valido.+Actualiza+la+pagina+e+intenta+de+nuevo');
            exit;
        }

        // requireAdmin() ya garantiza sesión válida; si por algún motivo
        // faltara el id, NO se debe atribuir el ingreso a otro usuario
        // (antes caía por defecto al usuario #1). Se corta el flujo.
        $userId = $_SESSION['user']['id'] ?? null;
        if ($userId === null) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        try {
            $db = Database::getConnection();

            // Auto-crear tabla fuel_incomes si no existe (ya sin unit_price:
            // el proyecto no maneja precio por galón)
            $db->exec('CREATE TABLE IF NOT EXISTS fuel_incomes (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED NULL,
                supplier VARCHAR(160) NOT NULL,
                fuel_type VARCHAR(80) NOT NULL DEFAULT "Diesel B5 S-50",
                gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
                gallons_remaining DECIMAL(10,2) NOT NULL DEFAULT 0,
                receipt_number VARCHAR(80) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB');

            // Si la tabla venía de una instalación previa con unit_price, se
            // elimina la columna (y su CHECK) para que no quede dato muerto.
            try {
                $db->exec('ALTER TABLE fuel_incomes DROP CONSTRAINT chk_incomes_price');
            } catch (Throwable $t) {
            }
            try {
                $db->exec('ALTER TABLE fuel_incomes DROP COLUMN unit_price');
            } catch (Throwable $t) {
            }

            // Asegurar columna fuel_type si la tabla fue creada previamente sin ella
            try {
                $db->exec('ALTER TABLE fuel_incomes ADD COLUMN fuel_type VARCHAR(80) NOT NULL DEFAULT "Diesel B5 S-50"');
            } catch (Throwable $t) {
            }

            // Asegurar columna invoice_number si la tabla fue creada previamente sin ella
            try {
                $db->exec('ALTER TABLE fuel_incomes ADD COLUMN invoice_number VARCHAR(40) NULL');
            } catch (Throwable $t) {
            }

            // Genera el siguiente número de recibo SECUENCIAL usando un
            // contador atómico (ver Database::nextSequence) en vez de
            // "leer el último y sumar 1", que fallaba en el primer
            // ingreso registrado en el sistema.
            // IMPORTANTE: la tabla del contador se asegura ANTES de abrir
            // la transacción (CREATE TABLE es DDL y en MySQL haría un
            // COMMIT implícito si se ejecutara ya dentro de ella).
            Database::ensureSequenceTable($db);
            $db->beginTransaction();

            $nextSeq = Database::nextSequence($db, 'REC-ING');
            $receiptNum = 'REC-ING-' . str_pad((string) $nextSeq, 6, '0', STR_PAD_LEFT);

            $stmt = $db->prepare('INSERT INTO fuel_incomes (user_id, supplier, fuel_type, gallons, gallons_remaining, receipt_number, invoice_number, notes, created_at) VALUES (:user_id, :supplier, :fuel_type, :gallons, :gallons_remaining, :receipt_number, :invoice_number, :notes, NOW())');
            $stmt->execute([
                'user_id' => $userId,
                'supplier' => $supplier !== '' ? mb_substr($supplier, 0, 100) : 'Proveedor Municipal',
                'fuel_type' => $fuelType !== '' ? $fuelType : 'Diesel B5 S-50',
                'gallons' => $quantity,
                'gallons_remaining' => $quantity,
                'receipt_number' => $receiptNum,
                'invoice_number' => $invoice !== '' ? $invoice : null,
                'notes' => $notes !== '' ? $notes : null,
            ]);

            $db->commit();

            $successMsg = 'Ingreso de ' . number_format((float) $quantity, 2) . ' Gal registrado correctamente. Stock actualizado.';
            header('Location: ' . BASE_URL . 'ingresos?success=' . urlencode($successMsg));
            exit;
        } catch (Throwable $e) {
            // Revertir transacción si quedó abierta
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log('Error al registrar ingreso: ' . $e->getMessage());
            header('Location: ' . BASE_URL . 'ingresos/nuevo?error=Ocurrio+un+error+al+registrar+el+ingreso.+Intenta+nuevamente.');
            exit;
        }
    }
}