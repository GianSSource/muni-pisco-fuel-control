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
        $tankLevels = $fuelModel->getTankLevels();

        $calendarEvents = $fuelModel->getDeliveryEvents();

        usort($calendarEvents, static fn($a, $b) => strcmp($a['date'], $b['date']));

        $this->view('dashboard/index', [
            'title' => 'Dashboard General',
            'saldoDisponible' => $metrics['saldoDisponible'],
            'totalIngresado' => $metrics['totalIngresado'],
            'totalEntregado' => $metrics['totalEntregado'],
            'consumoDia' => $metrics['consumoDia'],
            'recibosMes' => $metrics['recibosMes'],
            'ultimasEntregas' => $ultimasEntregas,
            'dailyFuel' => $dailyFuel,
            'monthlyFuel' => $monthlyFuel,
            'yearlyFuel' => $yearlyFuel,
            'areaFuel' => $areaFuel,
            'typeFuel' => $typeFuel,
            'stockByFuelType' => $stockByFuelType,
            'tankLevels' => $tankLevels,
            'calendarEvents' => $calendarEvents,
        ]);
    }
}