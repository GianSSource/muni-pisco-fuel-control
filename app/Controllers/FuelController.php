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

    /**
     * Muestra el formulario para crear un Nuevo Despacho (Botón Azul)
     */
    public function create(): void
    {
        $this->requireAuth();

        $fuelModel = new Fuel();

        // Calcular stock disponible actual para mostrarlo en el formulario de creación si se desea
        $totalIngresado = method_exists($fuelModel, 'getTotalIngresos') ? $fuelModel->getTotalIngresos() : 0;
        $totalEntregado = method_exists($fuelModel, 'getTotalDespachos') ? $fuelModel->getTotalDespachos() : 0;
        $saldoDisponible = $totalIngresado - $totalEntregado;

        $this->view('fuel/create', [
            'title' => 'Registrar Nuevo Despacho de Combustible',
            'saldoDisponible' => $saldoDisponible
        ]);
    }

    /**
     * Procesa y guarda el Nuevo Despacho restando del saldo disponible
     */
    public function store(): void
    {
        $this->requireAuth();

        $fuelModel = new Fuel();

        // Capturar los galones enviados desde el formulario del nuevo despacho
        $galonesDespacho = (float) ($_POST['gallons'] ?? 0);

        // Calcular stock actual disponible para evitar saldos negativos
        $totalIngresado = method_exists($fuelModel, 'getTotalIngresos') ? $fuelModel->getTotalIngresos() : 0;
        $totalEntregado = method_exists($fuelModel, 'getTotalDespachos') ? $fuelModel->getTotalDespachos() : 0;
        $saldoDisponible = $totalIngresado - $totalEntregado;

        // Validación: si se intenta despachar más de lo disponible en stock
        if ($galonesDespacho > $saldoDisponible) {
            $_SESSION['error'] = "No hay suficiente combustible disponible. Stock actual: {$saldoDisponible} Gal.";
            header('Location: ' . BASE_URL . 'proyectos/nuevo');
            exit;
        }

        // Guardar el despacho (asegúrate de que tu modelo tenga un método insertDelivery o insert)
        if (method_exists($fuelModel, 'insertDelivery')) {
            $fuelModel->insertDelivery($_POST);
        } else {
            // Fallback genérico si usas otro método en el modelo
            $fuelModel->save($_POST);
        }

        // Redirigir al dashboard para que se refleje la resta del saldo de inmediato
        header('Location: ' . BASE_URL . 'dashboard');
        exit;
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