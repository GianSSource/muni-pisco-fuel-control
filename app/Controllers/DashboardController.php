<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Fuel;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $fuelModel = new Fuel();

        $metrics = $fuelModel->getDashboardMetrics();
        $ultimasEntregas = $fuelModel->getLatestDeliveries(6);
        $dailyFuel = $fuelModel->getDailyDispatchedFuel(7);
        $monthlyFuel = $fuelModel->getMonthlyFuelDispatched();
        $yearlyFuel = $fuelModel->getYearlyFuelDispatched();
        $areaFuel = $fuelModel->getFuelDispatchedByArea();
        $typeFuel = $fuelModel->getFuelDispatchedByType();
        $stockByFuelType = $fuelModel->getStockByFuelType();


        $calendarEvents = $fuelModel->getDeliveryEvents();

        usort($calendarEvents, static fn($a, $b) => strcmp($b['date_time'] ?? $b['date'], $a['date_time'] ?? $a['date']));

        $uniquePlates = $fuelModel->getUniqueVehicles();
        $uniqueDrivers = $fuelModel->getUniqueDrivers();
        $areaRelations = $fuelModel->getAreaRelations();

        $catalog = new \App\Models\Catalog();
        $catalogAreas = $catalog->getAreas();

        $this->view('dashboard/index', [
            'title' => 'Dashboard General',
            'saldoDisponible' => $metrics['saldoDisponible'],
            'totalIngresado' => $metrics['totalIngresado'],
            'totalEntregado' => $metrics['totalEntregado'],
            'consumoDia' => $metrics['consumoDia'],
            'recibosMes' => $metrics['recibosMes'],
            'saldoTrend' => $metrics['saldoTrend'],
            'ingresadoTrend' => $metrics['ingresadoTrend'],
            'entregadoTrend' => $metrics['entregadoTrend'],
            'consumoDiaTrend' => $metrics['consumoDiaTrend'],
            'recibosMesTrend' => $metrics['recibosMesTrend'],
            'ultimasEntregas' => $ultimasEntregas,
            'dailyFuel' => $dailyFuel,
            'monthlyFuel' => $monthlyFuel,
            'yearlyFuel' => $yearlyFuel,
            'areaFuel' => $areaFuel,
            'typeFuel' => $typeFuel,
            'stockByFuelType' => $stockByFuelType,
            'calendarEvents' => $calendarEvents,
            'uniquePlates' => $uniquePlates,
            'uniqueDrivers' => $uniqueDrivers,
            'areaRelations' => $areaRelations,
            'catalogAreas' => $catalogAreas,
        ]);
    }
}