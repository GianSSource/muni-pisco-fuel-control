<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Fuel;

final class CalendarController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $fuelModel = new Fuel();
        $fuelEvents = $fuelModel->getDeliveryEvents();

        usort($fuelEvents, static fn($a, $b) => strcmp($a['date'], $b['date']));

        $this->view('calendar/index', [
            'title' => 'Calendario de Entregas',
            'events' => $fuelEvents,
            'fuelEvents' => $fuelEvents,
        ]);
    }

    public function apiEvents(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json; charset=utf-8');

        $fuelEvents = (new Fuel())->getDeliveryEvents();
        usort($fuelEvents, static fn($a, $b) => strcmp($a['date'], $b['date']));

        echo json_encode(['status' => 'success', 'data' => $fuelEvents], JSON_UNESCAPED_UNICODE);
    }
}
