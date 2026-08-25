<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Fuel;
use App\Models\Project;

final class CalendarController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $fuelModel = new Fuel();
        $projectModel = new Project();

        $fuelEvents = $fuelModel->getDeliveryEvents();
        $projectEvents = $projectModel->getDeliveryEvents();

        // Combinar y ordenar todos los eventos por fecha
        $allEvents = array_merge($fuelEvents, $projectEvents);
        usort($allEvents, static fn($a, $b) => strcmp($a['date'], $b['date']));

        $this->view('calendar/index', [
            'title' => 'Calendario de Entregas',
            'events' => $allEvents,
            'fuelEvents' => $fuelEvents,
            'projectEvents' => $projectEvents,
        ]);
    }

    public function apiEvents(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json; charset=utf-8');

        $fuelEvents = (new Fuel())->getDeliveryEvents();
        $projectEvents = (new Project())->getDeliveryEvents();

        $allEvents = array_merge($fuelEvents, $projectEvents);
        usort($allEvents, static fn($a, $b) => strcmp($a['date'], $b['date']));

        echo json_encode(['status' => 'success', 'data' => $allEvents], JSON_UNESCAPED_UNICODE);
    }
}
