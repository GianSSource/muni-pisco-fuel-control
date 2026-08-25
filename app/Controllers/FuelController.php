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
        $drivers  = array_unique(array_filter(array_column($allDeliveries, 'driver_name')));
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
}
