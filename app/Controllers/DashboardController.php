<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Fuel;
use App\Models\Project;

final class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $fuelModel = new Fuel();
        $projectModel = new Project();

        $metrics = $fuelModel->getDashboardMetrics();
        $ultimasEntregas = $fuelModel->getLatestDeliveries(6);
        $dailyFuel = $fuelModel->getDailyDispatchedFuel(7);
        $monthlyFuel = $fuelModel->getMonthlyFuelDispatched();
        $yearlyFuel = $fuelModel->getYearlyFuelDispatched();
        $areaFuel = $fuelModel->getFuelDispatchedByArea();
        $typeFuel = $fuelModel->getFuelDispatchedByType();

        $fuelEvents = $fuelModel->getDeliveryEvents();
        $projectEvents = $projectModel->getDeliveryEvents();
        $calendarEvents = array_merge($fuelEvents, $projectEvents);

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
            'calendarEvents' => $calendarEvents,
            'clientsCount' => count((new Client())->all()),
            'projectsCount' => count((new Project())->all()),
        ]);
    }
}