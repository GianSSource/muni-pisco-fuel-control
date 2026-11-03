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
            $stmt = $db->query('SELECT fi.*, COALESCE(u.name, "Administrador") AS registered_by 
                                FROM fuel_incomes fi 
                                LEFT JOIN users u ON u.id = fi.user_id 
                                ORDER BY fi.created_at DESC');
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

        if ($quantity > 10000) {
            header('Location: ' . BASE_URL . 'ingresos/nuevo?error=La+cantidad+no+puede+superar+los+10,000+galones');
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

            $unit = \App\Models\Catalog::getFuelUnit($fuelType, true);
            $successMsg = 'Ingreso de ' . number_format((float) $quantity, 2) . ' ' . $unit . ' registrado correctamente. Stock actualizado.';
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