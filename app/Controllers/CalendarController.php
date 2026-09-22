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
        // Límite de 120 días / 300 eventos para no cargar todo el historial
        // de la BD en cada visita al calendario (Bug 20).
        $fuelEvents = $fuelModel->getDeliveryEvents(120, 300);

        usort($fuelEvents, static fn($a, $b) => strcmp($b['date_time'] ?? $b['date'], $a['date_time'] ?? $a['date']));

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

        $fuelEvents = (new Fuel())->getDeliveryEvents(120, 300);
        usort($fuelEvents, static fn($a, $b) => strcmp($b['date_time'] ?? $b['date'], $a['date_time'] ?? $a['date']));

        echo json_encode(['status' => 'success', 'data' => $fuelEvents], JSON_UNESCAPED_UNICODE);
    }
}
