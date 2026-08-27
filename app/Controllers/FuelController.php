<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Fuel;

final class FuelController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $fuelModel = new Fuel();

        $filters = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'vehicle' => trim((string) ($_GET['vehicle'] ?? '')),
            'driver' => trim((string) ($_GET['driver'] ?? '')),
            'destination' => trim((string) ($_GET['destination'] ?? '')),
            'date_from' => trim((string) ($_GET['date_from'] ?? '')),
            'date_to' => trim((string) ($_GET['date_to'] ?? '')),
            'min_gallons' => isset($_GET['min_gallons']) && $_GET['min_gallons'] !== '' ? (float) $_GET['min_gallons'] : null,
            'max_gallons' => isset($_GET['max_gallons']) && $_GET['max_gallons'] !== '' ? (float) $_GET['max_gallons'] : null,
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];

        $deliveries = $fuelModel->searchDeliveries($filters);

        // Obtener todos para llenar listas de autosugerencias/filtros
        $allDeliveries = $fuelModel->searchDeliveries([]);
        $vehicles = array_unique(array_filter(array_column($allDeliveries, 'vehicle_plate')));
        $drivers = array_unique(array_filter(array_column($allDeliveries, 'driver_name')));
        $destinations = array_unique(array_filter(array_column($allDeliveries, 'destination')));

        sort($vehicles);
        sort($drivers);
        sort($destinations);

        // Métricas de los resultados filtrados
        $totalGallons = 0;
        foreach ($deliveries as $d) {
            $totalGallons += (float) $d['gallons'];
        }
        $avgGallons = count($deliveries) > 0 ? $totalGallons / count($deliveries) : 0;

        $this->view('fuel/index', [
            'title' => 'Buscador de Despachos de Combustible',
            'deliveries' => $deliveries,
            'filters' => $filters,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'destinations' => $destinations,
            'totalGallons' => $totalGallons,
            'avgGallons' => $avgGallons,
            'count' => count($deliveries),
        ]);
    }

    public function searchApi(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json; charset=utf-8');

        $filters = [
            'q' => trim((string) ($_GET['q'] ?? '')),
            'vehicle' => trim((string) ($_GET['vehicle'] ?? '')),
            'driver' => trim((string) ($_GET['driver'] ?? '')),
            'destination' => trim((string) ($_GET['destination'] ?? '')),
            'date_from' => trim((string) ($_GET['date_from'] ?? '')),
            'date_to' => trim((string) ($_GET['date_to'] ?? '')),
            'min_gallons' => isset($_GET['min_gallons']) && $_GET['min_gallons'] !== '' ? (float) $_GET['min_gallons'] : null,
            'max_gallons' => isset($_GET['max_gallons']) && $_GET['max_gallons'] !== '' ? (float) $_GET['max_gallons'] : null,
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];

        $deliveries = (new Fuel())->searchDeliveries($filters);

        $totalGallons = 0;
        foreach ($deliveries as $d) {
            $totalGallons += (float) $d['gallons'];
        }

        echo json_encode([
            'status' => 'success',
            'count' => count($deliveries),
            'total_gallons' => number_format($totalGallons, 2),
            'data' => $deliveries
        ], JSON_UNESCAPED_UNICODE);
    }

    public function statsApi(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json; charset=utf-8');

        $view = trim((string) ($_GET['view'] ?? 'diario'));
        $stats = (new Fuel())->getFuelStats($view);

        $totalGallons = 0;
        foreach ($stats as $item) {
            $totalGallons += (float) ($item['total_gallons'] ?? 0);
        }

        echo json_encode([
            'status' => 'success',
            'view' => $view,
            'total_gallons' => round($totalGallons, 2),
            'data' => $stats
        ], JSON_UNESCAPED_UNICODE);
    }

    public function create(): void
    {
        $this->requireAuth();

        $incomes = [];
        try {
            $db = \App\Core\Database::getConnection();
            $stmt = $db->query('SELECT id, receipt_number, supplier, fuel_type, gallons_remaining FROM fuel_incomes WHERE gallons_remaining > 0 ORDER BY id DESC');
            $incomes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
        }

        $catalog = new \App\Models\Catalog();
        $fuelModel = new \App\Models\Fuel();
        $vehicles = $fuelModel->getUniqueVehicles();
        $drivers = $fuelModel->getUniqueDrivers();

        $this->view('fuel/create', [
            'title' => 'Registrar Vale de Despacho',
            'csrfToken' => $this->csrfToken(),
            'incomes' => $incomes,
            'areas' => $catalog->getAreas(),
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $incomeId = (int) ($_POST['fuel_income_id'] ?? 1);
        $plate = strtoupper(mb_substr(trim((string) ($_POST['vehicle_plate'] ?? '')), 0, 10));
        $driver = trim((string) ($_POST['driver_name'] ?? ''));
        $gallons = (float) ($_POST['gallons'] ?? 0);
        $destination = trim((string) ($_POST['destination'] ?? ''));
        $notes = trim((string) ($_POST['notes'] ?? ''));
        $fullDestination = $destination !== '' ? $destination : 'Despacho General';
        if ($notes !== '') {
            $fullDestination .= ' (Obs: ' . $notes . ')';
        }

        if ($plate === '' || $driver === '' || $gallons <= 0) {
            $this->redirect('despachos/nuevo?error=Complete+todos+los+campos+obligatorios');
        }

        try {
            $db = \App\Core\Database::getConnection();

            // Verificar o auto-crear lote si no existiera
            $incomeStmt = $db->prepare('SELECT id, fuel_type, gallons_remaining FROM fuel_incomes WHERE id = :id LIMIT 1');
            $incomeStmt->execute(['id' => $incomeId]);
            $income = $incomeStmt->fetch(\PDO::FETCH_ASSOC);

            if (!$income) {
                // Crear un lote inicial automático si no existe ningún lote previo
                $db->exec('INSERT INTO fuel_incomes (supplier, gallons, gallons_remaining, receipt_number) VALUES ("Ingreso Inicial Directo", 10000.00, 10000.00, "INC-AUTO-001")');
                $incomeId = (int) $db->lastInsertId();
                $income = ['id' => $incomeId, 'gallons_remaining' => 10000.00];
            }

            $availableStock = (float) ($income['gallons_remaining'] ?? 0);
            if ($availableStock < $gallons) {
                $errorMsg = 'Stock+insuficiente.+Intenta+despachar+' . number_format($gallons, 2) . '+Galones,+pero+el+lote+solo+cuenta+con+' . number_format($availableStock, 2) . '+Galones+disponibles.';
                $this->redirect('despachos/nuevo?error=' . $errorMsg);
            }

            // Generar correlativo de vale único
            $receiptNum = 'VAL-' . date('Y') . '-' . str_pad((string) rand(100, 9999), 4, '0', STR_PAD_LEFT);
            $userId = $_SESSION['user']['id'] ?? 1;

            // Inserta en fuel_deliveries
            $stmt = $db->prepare('INSERT INTO fuel_deliveries (fuel_income_id, user_id, receipt_number, vehicle_plate, driver_name, gallons, destination, status, created_at) VALUES (:income_id, :user_id, :receipt_number, :plate, :driver, :gallons, :destination, "Completado", NOW())');
            $stmt->execute([
                'income_id' => $incomeId,
                'user_id' => $userId,
                'receipt_number' => $receiptNum,
                'plate' => $plate,
                'driver' => $driver,
                'gallons' => $gallons,
                'destination' => $fullDestination,
            ]);

            // Descontar del saldo del lote si no lo manejó un trigger de BD
            $stmtUpdate = $db->prepare('UPDATE fuel_incomes SET gallons_remaining = GREATEST(0, gallons_remaining - :gallons) WHERE id = :id');
            $stmtUpdate->execute(['gallons' => $gallons, 'id' => $incomeId]);

            $newDeliveryId = (int) $db->lastInsertId();
            $this->redirect('despachos/imprimir?id=' . $newDeliveryId . '&auto=1');
        } catch (\Throwable $e) {
            $this->redirect('despachos/nuevo?error=Error+al+guardar+el+despacho:+' . urlencode($e->getMessage()));
        }
    }

    public function printReceipt(): void
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        $receiptNumber = trim((string) ($_GET['num'] ?? ''));

        $delivery = null;
        try {
            $db = \App\Core\Database::getConnection();
            if ($id > 0) {
                $stmt = $db->prepare('SELECT fd.*, COALESCE(fi.fuel_type, "Diesel B5 S-50") AS fuel_type FROM fuel_deliveries fd LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id WHERE fd.id = :id LIMIT 1');
                $stmt->execute(['id' => $id]);
                $delivery = $stmt->fetch(\PDO::FETCH_ASSOC);
            } elseif ($receiptNumber !== '') {
                $stmt = $db->prepare('SELECT fd.*, COALESCE(fi.fuel_type, "Diesel B5 S-50") AS fuel_type FROM fuel_deliveries fd LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id WHERE fd.receipt_number = :num LIMIT 1');
                $stmt->execute(['num' => $receiptNumber]);
                $delivery = $stmt->fetch(\PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $e) {
        }

        if (!$delivery) {
            $delivery = [
                'receipt_number' => 'VAL-' . date('Y') . '-0001',
                'vehicle_plate' => 'MUNI-01',
                'driver_name' => 'Conductor Municipal',
                'fuel_type' => 'Diesel B5 S-50',
                'gallons' => 10.00,
                'destination' => 'Despacho General',
                'status' => 'Completado',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }

        require __DIR__ . '/../Views/fuel/print_receipt.php';
    }
}
