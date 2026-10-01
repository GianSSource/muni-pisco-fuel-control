<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Fuel;
use App\Models\Catalog;

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
            'fuel_type' => trim((string) ($_GET['fuel_type'] ?? '')),
            'date_from' => trim((string) ($_GET['date_from'] ?? '')),
            'date_to' => trim((string) ($_GET['date_to'] ?? '')),
            'min_gallons' => isset($_GET['min_gallons']) && $_GET['min_gallons'] !== '' ? (float) $_GET['min_gallons'] : null,
            'max_gallons' => isset($_GET['max_gallons']) && $_GET['max_gallons'] !== '' ? (float) $_GET['max_gallons'] : null,
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = isset($_GET['limit']) && in_array((int) $_GET['limit'], [10, 25, 50]) ? (int) $_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $stats = $fuelModel->getDeliveriesStats($filters);
        $totalGallons = $stats['total_gallons'];
        $count = $stats['count'];

        $totalPages = $count > 0 ? (int) ceil($count / $limit) : 1;
        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $deliveries = $fuelModel->searchDeliveries($filters, $limit, $offset);

        // Valores únicos para los filtros: se obtienen con SELECT DISTINCT
        // directamente en BD (O(n) solo sobre la columna indexada), en vez de
        // cargar toda la tabla en memoria PHP para luego hacer array_unique.
        $vehicles = $fuelModel->getUniqueVehicles();
        $drivers = $fuelModel->getUniqueDrivers();
        $destinations = $fuelModel->getUniqueDestinations();

        // Tipos de combustible: se toman del catálogo (system_catalogs),
        // no de una lista fija, para que cualquier tipo agregado en
        // Catálogos (p.ej. GLP) aparezca aquí automáticamente.
        try {
            $fuelTypes = (new Catalog())->getFuelTypes();
        } catch (\Throwable $e) {
            $fuelTypes = [];
        }
        // Por si algún despacho quedó con un fuel_type que ya no está en
        // el catálogo activo, lo agregamos igual para no "esconderlo".
        $usedFuelTypes = $fuelModel->getUniqueFuelTypes();
        $fuelTypes = array_values(array_unique(array_merge($fuelTypes, $usedFuelTypes)));
        sort($fuelTypes);


        // Métricas de los resultados filtrados
        $avgGallons = $count > 0 ? $totalGallons / $count : 0;

        $this->view('fuel/index', [
            'title' => 'Buscador de Despachos de Combustible',
            'deliveries' => $deliveries,
            'filters' => $filters,
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'destinations' => $destinations,
            'fuelTypes' => $fuelTypes,
            'totalGallons' => $totalGallons,
            'avgGallons' => $avgGallons,
            'count' => $count,
            'page' => $page,
            'totalPages' => $totalPages,
            'limit' => $limit
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
            'fuel_type' => trim((string) ($_GET['fuel_type'] ?? '')),
            'date_from' => trim((string) ($_GET['date_from'] ?? '')),
            'date_to' => trim((string) ($_GET['date_to'] ?? '')),
            'min_gallons' => isset($_GET['min_gallons']) && $_GET['min_gallons'] !== '' ? (float) $_GET['min_gallons'] : null,
            'max_gallons' => isset($_GET['max_gallons']) && $_GET['max_gallons'] !== '' ? (float) $_GET['max_gallons'] : null,
            'status' => trim((string) ($_GET['status'] ?? '')),
        ];

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $limit = isset($_GET['limit']) && in_array((int) $_GET['limit'], [10, 25, 50]) ? (int) $_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        $fuelModel = new Fuel();
        $stats = $fuelModel->getDeliveriesStats($filters);

        $totalPages = $stats['count'] > 0 ? (int) ceil($stats['count'] / $limit) : 1;
        if ($page > $totalPages) {
            $page = $totalPages;
            $offset = ($page - 1) * $limit;
        }

        $deliveries = $fuelModel->searchDeliveries($filters, $limit, $offset);

        echo json_encode([
            'status' => 'success',
            'count' => $stats['count'],
            'total_gallons' => number_format($stats['total_gallons'], 2),
            'page' => $page,
            'totalPages' => $totalPages,
            'data' => $deliveries
        ], JSON_UNESCAPED_UNICODE);
    }

    public function statsApi(): void
    {
        $this->requireAuth();

        header('Content-Type: application/json; charset=utf-8');

        $allowedViews = ['diario', 'semanal', 'mensual', 'anual'];
        $view = trim((string) ($_GET['view'] ?? 'diario'));
        if (!in_array($view, $allowedViews, true)) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => "Parametro 'view' inválido. Valores permitidos: " . implode(', ', $allowedViews)
            ], JSON_UNESCAPED_UNICODE);
            return;
        }
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

        // Se agrupan los lotes por tipo de combustible para que el usuario
        // vea un solo campo por tipo (con el stock sumado), en vez de un
        // campo por cada lote individual. El lote real que se usará al
        // despachar lo decide el JS por FIFO (el más antiguo con stock
        // suficiente), por eso se ordena ASC y se envían también los lotes
        // de cada grupo en data-lots (JSON) para que el navegador elija.
        $fuelGroups = [];
        try {
            $db = \App\Core\Database::getConnection();
            $stmt = $db->query('SELECT id, receipt_number, supplier, fuel_type, gallons_remaining FROM fuel_incomes WHERE gallons_remaining > 0 ORDER BY id ASC');
            $incomes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($incomes as $inc) {
                $type = $inc['fuel_type'];
                if (!isset($fuelGroups[$type])) {
                    $fuelGroups[$type] = [
                        'fuel_type' => $type,
                        'total_stock' => 0.0,
                        'lots' => [],
                    ];
                }
                $fuelGroups[$type]['total_stock'] += (float) $inc['gallons_remaining'];
                $fuelGroups[$type]['lots'][] = [
                    'id' => (int) $inc['id'],
                    'receipt_number' => $inc['receipt_number'],
                    'supplier' => $inc['supplier'],
                    'stock' => (float) $inc['gallons_remaining'],
                ];
            }
            $fuelGroups = array_values($fuelGroups);
        } catch (\Throwable $e) {
            $fuelGroups = [];
        }

        $catalog = new \App\Models\Catalog();
        $fuelModel = new \App\Models\Fuel();
        $vehicles = (new \App\Models\Vehicle())->getActive();
        $drivers = $fuelModel->getUniqueDrivers();

        $this->view('fuel/create', [
            'title' => 'Registrar Vale de Despacho',
            'csrfToken' => $this->csrfToken(),
            'fuelGroups' => $fuelGroups,
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

        $fuelType = trim((string) ($_POST['fuel_type'] ?? ''));
        $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
        $driver = trim((string) ($_POST['driver_name'] ?? ''));
        $gallons = (float) ($_POST['gallons'] ?? 0);
        $destination = trim((string) ($_POST['destination'] ?? ''));
        $notes = trim((string) ($_POST['notes'] ?? ''));

        if ($fuelType === '' || $vehicleId <= 0 || $driver === '' || $gallons <= 0 || $gallons > 15000 || $destination === '') {
            $this->redirect('despachos/nuevo?error=Datos+invalidos+o+limite+de+15000+galones+excedido');
        }

        $vehicle = (new \App\Models\Vehicle())->findById($vehicleId);
        if (!$vehicle) {
            $this->redirect('despachos/nuevo?error=El+vehiculo+seleccionado+no+existe+o+esta+inactivo');
        }
        $plate = $vehicle['plate'];

        // requireAuth() ya garantiza sesión válida; si por algún motivo
        // faltara el id, NO se debe atribuir el registro a otro usuario
        // (antes caía por defecto al usuario #1). Se corta el flujo.
        $userId = $_SESSION['user']['id'] ?? null;
        if ($userId === null) {
            $this->redirect('login');
        }

        $fuelModel = new \App\Models\Fuel();

        try {
            $db = \App\Core\Database::getConnection();

            // IMPORTANTE: la tabla del contador se asegura ANTES de abrir
            // la transacción (CREATE TABLE es DDL y en MySQL haría un
            // COMMIT implícito si se ejecutara ya dentro de ella, dejando
            // el commit() final sin transacción que cerrar).
            \App\Core\Database::ensureSequenceTable($db);
            $db->beginTransaction();

            // 1. Calcular qué lote(s) usar. Lanza RuntimeException si el
            //    stock TOTAL de ese tipo no alcanza (nada se ha insertado
            //    todavía, así que no hay nada que revertir en ese caso).
            $plan = $fuelModel->planFifoAllocation($fuelType, $gallons);

            // 2. Genera el siguiente número de vale SECUENCIAL para el año
            //    en curso usando un contador atómico (ver
            //    Database::nextSequence) en vez de "leer el último y
            //    sumar 1", que fallaba en el primer vale de cada año.
            $year = date('Y');
            $nextSeq = \App\Core\Database::nextSequence($db, 'VAL-' . $year);
            $receiptNum = 'VAL-' . $year . '-' . str_pad((string) $nextSeq, 6, '0', STR_PAD_LEFT);

            // 3. Insertar el vale. fuel_income_id se deja como "lote
            //    principal" SOLO si el plan usó un único lote (para que
            //    reportes que todavía lean esa columna directamente sigan
            //    funcionando); si se repartió entre 2+, queda NULL.
            $mainIncomeId = count($plan) === 1 ? $plan[0]['income_id'] : null;

            $stmt = $db->prepare(
                'INSERT INTO fuel_deliveries
                    (fuel_income_id, fuel_type, user_id, vehicle_id, receipt_number, vehicle_plate,
                     driver_name, gallons, destination, notes, status, created_at)
                 VALUES
                    (:income_id, :fuel_type, :user_id, :vehicle_id, :receipt_number, :plate,
                     :driver, :gallons, :destination, :notes, "Completado", NOW())'
            );
            $stmt->execute([
                'income_id' => $mainIncomeId,
                'fuel_type' => $fuelType,
                'user_id' => $userId,
                'vehicle_id' => $vehicleId,
                'receipt_number' => $receiptNum,
                'plate' => $plate,
                'driver' => $driver,
                'gallons' => $gallons,
                'destination' => $destination,
                'notes' => $notes !== '' ? $notes : null,
            ]);
            $newDeliveryId = (int) $db->lastInsertId();

            // 4. Insertar UNA fila de asignación por cada lote del plan.
            //    Cada INSERT dispara trg_alloc_before_insert (valida saldo
            //    de ESE lote) y trg_alloc_after_insert (lo descuenta) antes
            //    de pasar a la siguiente fila del plan.
            $allocStmt = $db->prepare(
                'INSERT INTO fuel_delivery_lot_allocations (delivery_id, fuel_income_id, gallons_taken)
                 VALUES (:delivery_id, :income_id, :take)'
            );
            foreach ($plan as $lot) {
                $allocStmt->execute([
                    'delivery_id' => $newDeliveryId,
                    'income_id' => $lot['income_id'],
                    'take' => $lot['take'],
                ]);
            }

            $db->commit();

            // El descuento de gallons_remaining lo hacen los triggers de
            // fuel_delivery_lot_allocations (ver database.sql). No se
            // repite aquí para evitar restar el doble.
            $this->redirect('despachos/imprimir?id=' . $newDeliveryId . '&auto=1');
        } catch (\RuntimeException $e) {
            // Stock insuficiente en total: mensaje claro al usuario.
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            $this->redirect('despachos/nuevo?error=' . rawurlencode($e->getMessage()));
        } catch (\Throwable $e) {
            // Si la transacción quedó abierta (error durante el INSERT), se
            // revierte para no dejar la BD en estado inconsistente.
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log('Error al guardar despacho: ' . $e->getMessage());
            $this->redirect('despachos/nuevo?error=Ocurrio+un+error+al+guardar+el+despacho.+Intenta+nuevamente.');
        }
    }

    /**
     * Anula un vale ya emitido. Borra primero sus líneas de asignación de
     * lote -> cada DELETE dispara trg_alloc_after_delete y repone el saldo
     * lote por lote; luego marca el vale como Anulado.
     */
    public function anular(): void
    {
        // Anular un vale repone stock y altera el historial de despachos:
        // se reserva a admin/supervisor, no a cualquier operador autenticado.
        $this->requireSupervisor();
        $this->verifyCsrf();

        $deliveryId = (int) ($_POST['id'] ?? 0);
        if ($deliveryId <= 0) {
            $this->redirect('despachos?error=Vale+invalido');
        }

        try {
            $db = \App\Core\Database::getConnection();
            $db->beginTransaction();

            $db->prepare('DELETE FROM fuel_delivery_lot_allocations WHERE delivery_id = :id')
                ->execute(['id' => $deliveryId]);

            $db->prepare('UPDATE fuel_deliveries SET status = "Anulado" WHERE id = :id')
                ->execute(['id' => $deliveryId]);

            $db->commit();
            $this->redirect('despachos?msg=Vale+anulado+correctamente');
        } catch (\Throwable $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log('Error al anular vale: ' . $e->getMessage());
            $this->redirect('despachos?error=No+se+pudo+anular+el+vale');
        }
    }

    public function printReceipt(): void
    {
        $this->requireAuth();

        $id = (int) ($_GET['id'] ?? 0);
        $receiptNumber = trim((string) ($_GET['num'] ?? ''));

        $delivery = null;
        $lotAllocations = [];
        try {
            $db = \App\Core\Database::getConnection();
            $baseSql = 'SELECT fd.*,
                                COALESCE(fd.fuel_type, "Diesel B5 S-50") AS fuel_type,
                                COALESCE(v.unit_code, "-") AS vehicle_unit_code,
                                COALESCE(vt.name, "Vehículo Municipal") AS vehicle_type_name
                         FROM fuel_deliveries fd
                         LEFT JOIN vehicles v ON ((fd.vehicle_id IS NOT NULL AND fd.vehicle_id > 0 AND v.id = fd.vehicle_id) OR v.plate = fd.vehicle_plate OR UPPER(REPLACE(v.plate, "-", "")) = UPPER(REPLACE(fd.vehicle_plate, "-", "")))
                         LEFT JOIN system_catalogs vt ON vt.id = v.vehicle_type_id';
            if ($id > 0) {
                $stmt = $db->prepare($baseSql . ' WHERE fd.id = :id LIMIT 1');
                $stmt->execute(['id' => $id]);
                $delivery = $stmt->fetch(\PDO::FETCH_ASSOC);
            } elseif ($receiptNumber !== '') {
                $stmt = $db->prepare($baseSql . ' WHERE fd.receipt_number = :num LIMIT 1');
                $stmt->execute(['num' => $receiptNumber]);
                $delivery = $stmt->fetch(\PDO::FETCH_ASSOC);
            }

            if ($delivery) {
                $allocStmt = $db->prepare(
                    'SELECT a.gallons_taken, fi.receipt_number AS lot_receipt, fi.supplier
                       FROM fuel_delivery_lot_allocations a
                       JOIN fuel_incomes fi ON fi.id = a.fuel_income_id
                      WHERE a.delivery_id = :delivery_id
                      ORDER BY a.id ASC'
                );
                $allocStmt->execute(['delivery_id' => $delivery['id']]);
                $lotAllocations = $allocStmt->fetchAll(\PDO::FETCH_ASSOC);

                if (empty($lotAllocations) && !empty($delivery['fuel_income_id'])) {
                    $singleLotStmt = $db->prepare(
                        'SELECT :gallons AS gallons_taken, receipt_number AS lot_receipt, supplier
                           FROM fuel_incomes WHERE id = :id LIMIT 1'
                    );
                    $singleLotStmt->execute([
                        'gallons' => $delivery['gallons'] ?? 0,
                        'id' => $delivery['fuel_income_id']
                    ]);
                    $singleLot = $singleLotStmt->fetch(\PDO::FETCH_ASSOC);
                    if ($singleLot) {
                        $lotAllocations = [$singleLot];
                    }
                }
            }
        } catch (\Throwable $e) {
            error_log('Error al buscar vale de despacho: ' . $e->getMessage());
        }

        if (!$delivery) {
            http_response_code(404);
            echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">'
                . '<title>Vale no encontrado</title></head>'
                . '<body style="font-family: Arial, sans-serif; text-align:center; padding:60px; background:#f8fafc; color:#0f172a;">'
                . '<h1 style="color:#ef4444;">404</h1>'
                . '<h2>Vale de despacho no encontrado</h2>'
                . '<p style="color:#64748b;">El vale solicitado no existe o el identificador es incorrecto.</p>'
                . '<a href="' . BASE_URL . 'despachos" style="display:inline-block; margin-top:20px; padding:10px 20px; background:#2563eb; color:#fff; text-decoration:none; border-radius:6px;">Volver a Despachos</a>'
                . '</body></html>';
            return;
        }

        require __DIR__ . '/../Views/fuel/print_receipt.php';
    }
}