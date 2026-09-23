<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

final class Fuel
{
    public function getDashboardMetrics(): array
    {
        try {
            $db = Database::getConnection();


            // 1. Total de combustible ingresado
            $totalIngresado = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_incomes');
                $totalIngresado = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (Throwable $t) {
                try {
                    $stmt = $db->query('SELECT COALESCE(SUM(cantidad), 0) AS total FROM ingresos_combustible');
                    $totalIngresado = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                } catch (\Throwable $t2) {
            error_log($t2->getMessage());
                }
            }

            // 2. Total entregado
            $totalEntregado = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_deliveries WHERE status <> "Anulado"');
                $totalEntregado = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (Throwable $t) {
                try {
                    $stmt = $db->query('SELECT COALESCE(SUM(cantidad_entregada), 0) AS total FROM recibos');
                    $totalEntregado = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                } catch (\Throwable $t2) {
            error_log($t2->getMessage());
                }
            }

            // 3. Saldo disponible
            $saldoDisponible = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons_remaining), 0) AS total FROM fuel_incomes');
                $saldoDisponible = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (Throwable $t) {
                try {
                    $stmt = $db->query('SELECT COALESCE(SUM(saldo_actual), 0) AS total FROM saldos_combustible');
                    $saldoDisponible = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                } catch (\Throwable $t2) {
            error_log($t2->getMessage());
                }
            }

            if ($saldoDisponible <= 0 && $totalIngresado > 0) {
                $saldoDisponible = max(0, $totalIngresado - $totalEntregado);
            }

            // 4. Consumo del día
            $consumoDia = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_deliveries WHERE DATE(created_at) = CURRENT_DATE() AND status <> "Anulado"');
                $consumoDia = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (Throwable $t) {
                try {
                    $stmt = $db->query('SELECT COALESCE(SUM(cantidad_entregada), 0) AS total FROM recibos WHERE DATE(fecha_hora) = CURRENT_DATE()');
                    $consumoDia = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                } catch (\Throwable $t2) {
            error_log($t2->getMessage());
                }
            }

            // 5. Cantidad de recibos del mes
            $recibosMes = 0;
            try {
                $stmt = $db->query('SELECT COUNT(*) AS total FROM fuel_deliveries WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())');
                $recibosMes = (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (Throwable $t) {
                try {
                    $stmt = $db->query('SELECT COUNT(*) AS total FROM recibos WHERE MONTH(fecha_hora) = MONTH(CURRENT_DATE()) AND YEAR(fecha_hora) = YEAR(CURRENT_DATE())');
                    $recibosMes = (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                } catch (\Throwable $t2) {
            error_log($t2->getMessage());
                }
            }

            // 6. Tendencias: ingresado y entregado del mes actual vs. mes anterior,
            // consumo de hoy vs. ayer, y recibos del mes actual vs. mes anterior.
            $ingresoMesActual = 0.0;
            $ingresoMesAnterior = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_incomes WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())');
                $ingresoMesActual = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_incomes WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)');
                $ingresoMesAnterior = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (\Throwable $t) {
            error_log($t->getMessage());
            }

            $entregaMesActual = 0.0;
            $entregaMesAnterior = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_deliveries WHERE status <> "Anulado" AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())');
                $entregaMesActual = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_deliveries WHERE status <> "Anulado" AND MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)');
                $entregaMesAnterior = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (\Throwable $t) {
            error_log($t->getMessage());
            }

            $consumoAyer = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_deliveries WHERE status <> "Anulado" AND DATE(created_at) = CURRENT_DATE() - INTERVAL 1 DAY');
                $consumoAyer = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (\Throwable $t) {
            error_log($t->getMessage());
            }

            $recibosMesAnterior = 0;
            try {
                $stmt = $db->query('SELECT COUNT(*) AS total FROM fuel_deliveries WHERE MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)');
                $recibosMesAnterior = (int) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (\Throwable $t) {
            error_log($t->getMessage());
            }

            // Saldo disponible al cierre del mes anterior, para poder comparar el
            // stock actual contra un punto de referencia real (no un acumulado total).
            $saldoInicioMes = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_incomes WHERE created_at < DATE_FORMAT(CURRENT_DATE(), "%Y-%m-01")');
                $ingresoHastaMesAnterior = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_deliveries WHERE status <> "Anulado" AND created_at < DATE_FORMAT(CURRENT_DATE(), "%Y-%m-01")');
                $entregaHastaMesAnterior = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

                $saldoInicioMes = max(0.0, $ingresoHastaMesAnterior - $entregaHastaMesAnterior);
            } catch (\Throwable $t) {
            error_log($t->getMessage());
            }

            return [
                'saldoDisponible' => $saldoDisponible,
                'totalIngresado' => $totalIngresado,
                'totalEntregado' => $totalEntregado,
                'consumoDia' => $consumoDia,
                'recibosMes' => $recibosMes,
                'saldoTrend' => $this->calcTrend($saldoDisponible, $saldoInicioMes, 'vs. mes anterior'),
                'ingresadoTrend' => $this->calcTrend($ingresoMesActual, $ingresoMesAnterior, 'vs. mes anterior'),
                'entregadoTrend' => $this->calcTrend($entregaMesActual, $entregaMesAnterior, 'vs. mes anterior'),
                'consumoDiaTrend' => $this->calcTrend($consumoDia, $consumoAyer, 'vs. ayer'),
                'recibosMesTrend' => $this->calcTrend((float) $recibosMes, (float) $recibosMesAnterior, 'vs. mes anterior'),
            ];
        } catch (Throwable $e) {
            return [
                'saldoDisponible' => 0.00,
                'totalIngresado' => 0.00,
                'totalEntregado' => 0.00,
                'consumoDia' => 0.00,
                'recibosMes' => 0,
                'saldoTrend' => null,
                'ingresadoTrend' => null,
                'entregadoTrend' => null,
                'consumoDiaTrend' => null,
                'recibosMesTrend' => null,
            ];
        }
    }

    /**
     * Calcula la variación porcentual entre un valor actual y uno de referencia
     * (periodo anterior). Devuelve null cuando no hay una base válida de
     * comparación, para que la vista pueda ocultar el indicador en vez de
     * mostrar un porcentaje sin sentido (ej. división por cero).
     *
     * @return array{direction: string, pct: float, label: string}|null
     */
    private function calcTrend(float $current, float $previous, string $label): ?array
    {
        if ($previous <= 0.0) {
            return null;
        }

        $diffPct = (($current - $previous) / $previous) * 100;
        $direction = 'flat';
        if ($diffPct > 0.05) {
            $direction = 'up';
        } elseif ($diffPct < -0.05) {
            $direction = 'down';
        }

        return [
            'direction' => $direction,
            'pct' => abs($diffPct),
            'label' => $label,
        ];
    }

    public function getLatestDeliveries(int $limit = 6): array
    {
        try {
            $db = Database::getConnection();
            $sql = 'SELECT 
                    fd.id, 
                    fd.receipt_number, 
                    fd.vehicle_plate, 
                    fd.driver_name, 
                    COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") AS fuel_type,
                    fd.gallons, 
                    COALESCE(fd.destination, "Despacho General") AS destination, 
                    fd.notes,
                    fd.status, 
                    fd.created_at 
                FROM fuel_deliveries fd
                LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id
                ORDER BY fd.created_at DESC 
                LIMIT :limit';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($deliveries)) {
                return $deliveries;
            }
        } catch (Throwable $e) {
            // Intentar fallback a recibos
            try {
                $db = Database::getConnection();
                $sql = 'SELECT 
                            r.id, 
                            r.correlativo AS receipt_number, 
                            COALESCE(v.placa, "MUNI-UNID") AS vehicle_plate, 
                            r.responsable AS driver_name, 
                            COALESCE(r.tipo_combustible, "Diesel B5 S-50") AS fuel_type,
                            r.cantidad_entregada AS gallons, 
                            COALESCE(v.area, r.observaciones, "Despacho General") AS destination, 
                            "Completado" AS status, 
                            r.fecha_hora AS created_at 
                        FROM recibos r
                        LEFT JOIN vehiculos v ON v.id = r.vehiculo_id
                        ORDER BY r.fecha_hora DESC 
                        LIMIT :limit';

                $stmt = $db->prepare($sql);
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->execute();
                $deliveries = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($deliveries)) {
                    return $deliveries;
                }
            } catch (\Throwable $t2) {
            error_log($t2->getMessage());
            }
        }

        return [];
    }

    public function getDeliveriesStats(array $filters = []): array
    {
        $queryText = trim((string) ($filters['q'] ?? $filters['query'] ?? ''));
        $vehicle = trim((string) ($filters['vehicle'] ?? ''));
        $driver = trim((string) ($filters['driver'] ?? ''));
        $destination = trim((string) ($filters['destination'] ?? ''));
        $fuelType = trim((string) ($filters['fuel_type'] ?? ''));
        $dateFrom = trim((string) ($filters['date_from'] ?? ''));
        $dateTo = trim((string) ($filters['date_to'] ?? ''));
        $minGallons = isset($filters['min_gallons']) && $filters['min_gallons'] !== '' ? (float) $filters['min_gallons'] : null;
        $maxGallons = isset($filters['max_gallons']) && $filters['max_gallons'] !== '' ? (float) $filters['max_gallons'] : null;
        $status = trim((string) ($filters['status'] ?? ''));

        $where = ['1=1'];
        $params = [];

        if ($queryText !== '') {
            $where[] = '(
                fd.receipt_number LIKE :q1 OR 
                fd.vehicle_plate LIKE :q2 OR 
                fd.driver_name LIKE :q3 OR 
                fd.destination LIKE :q4 OR 
                fd.notes LIKE :q5 OR 
                fd.status LIKE :q6 OR 
                COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") LIKE :q7 OR 
                DATE_FORMAT(fd.created_at, "%d/%m/%Y %H:%i") LIKE :q8 OR 
                DATE_FORMAT(fd.created_at, "%d/%m/%Y") LIKE :q9 OR 
                DATE_FORMAT(fd.created_at, "%Y-%m-%d") LIKE :q10
            )';
            $qVal = '%' . $queryText . '%';
            for ($i = 1; $i <= 10; $i++) {
                $params['q' . $i] = $qVal;
            }
        }

        if ($vehicle !== '') {
            $where[] = 'fd.vehicle_plate LIKE :vehicle';
            $params['vehicle'] = '%' . $vehicle . '%';
        }

        if ($driver !== '') {
            $where[] = 'fd.driver_name LIKE :driver';
            $params['driver'] = '%' . $driver . '%';
        }

        if ($destination !== '') {
            $where[] = 'fd.destination LIKE :dest';
            $params['dest'] = '%' . $destination . '%';
        }

        if ($fuelType !== '') {
            $where[] = 'COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") LIKE :fuel_type';
            $params['fuel_type'] = '%' . $fuelType . '%';
        }

        if ($dateFrom !== '') {
            $where[] = 'DATE(fd.created_at) >= :date_from';
            $params['date_from'] = $dateFrom;
        }

        if ($dateTo !== '') {
            $where[] = 'DATE(fd.created_at) <= :date_to';
            $params['date_to'] = $dateTo;
        }

        if ($minGallons !== null) {
            $where[] = 'fd.gallons >= :min_gallons';
            $params['min_gallons'] = $minGallons;
        }

        if ($maxGallons !== null) {
            $where[] = 'fd.gallons <= :max_gallons';
            $params['max_gallons'] = $maxGallons;
        }

        if ($status !== '') {
            $where[] = 'fd.status = :status';
            $params['status'] = $status;
        } else {
            // Por defecto se excluyen los vales anulados, igual que el
            // gráfico "Por Área" del Dashboard y el Calendario
            // (getDeliveryEvents()). Antes esta función SÍ los contaba
            // salvo que el usuario eligiera el filtro de estado a mano,
            // así que el total de un área (o de cualquier filtro) salía
            // distinto aquí que en el Dashboard para los mismos datos.
            // Si el usuario quiere ver los anulados, sigue pudiendo
            // elegir ese estado explícitamente en el filtro.
            $where[] = "fd.status <> 'Anulado'";
        }

        try {
            $db = Database::getConnection();
            $sql = 'SELECT COUNT(fd.id) as count, COALESCE(SUM(fd.gallons), 0) as total_gallons
                    FROM fuel_deliveries fd
                    LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id
                    WHERE ' . implode(' AND ', $where);

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'count' => (int) ($row['count'] ?? 0),
                'total_gallons' => (float) ($row['total_gallons'] ?? 0)
            ];
        } catch (Throwable $e) {
            return ['count' => 0, 'total_gallons' => 0.0];
        }
    }

    public function searchDeliveries(array $filters = [], ?int $limit = null, ?int $offset = null): array
    {
        $queryText = trim((string) ($filters['q'] ?? $filters['query'] ?? ''));
        $vehicle = trim((string) ($filters['vehicle'] ?? ''));
        $driver = trim((string) ($filters['driver'] ?? ''));
        $destination = trim((string) ($filters['destination'] ?? ''));
        $fuelType = trim((string) ($filters['fuel_type'] ?? ''));
        $dateFrom = trim((string) ($filters['date_from'] ?? ''));
        $dateTo = trim((string) ($filters['date_to'] ?? ''));
        $minGallons = isset($filters['min_gallons']) && $filters['min_gallons'] !== '' ? (float) $filters['min_gallons'] : null;
        $maxGallons = isset($filters['max_gallons']) && $filters['max_gallons'] !== '' ? (float) $filters['max_gallons'] : null;
        $status = trim((string) ($filters['status'] ?? ''));

        try {
            $db = Database::getConnection();

            $where = ['1=1'];
            $params = [];

            if ($queryText !== '') {
                $where[] = '(
                    fd.receipt_number LIKE :q1 OR 
                    fd.vehicle_plate LIKE :q2 OR 
                    fd.driver_name LIKE :q3 OR 
                    fd.destination LIKE :q4 OR 
                    fd.notes LIKE :q5 OR 
                    fd.status LIKE :q6 OR 
                    COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") LIKE :q7 OR 
                    DATE_FORMAT(fd.created_at, "%d/%m/%Y %H:%i") LIKE :q8 OR 
                    DATE_FORMAT(fd.created_at, "%d/%m/%Y") LIKE :q9 OR 
                    DATE_FORMAT(fd.created_at, "%Y-%m-%d") LIKE :q10
                )';
                $qVal = '%' . $queryText . '%';
                for ($i = 1; $i <= 10; $i++) {
                    $params['q' . $i] = $qVal;
                }
            }

            if ($vehicle !== '') {
                $where[] = 'fd.vehicle_plate LIKE :vehicle';
                $params['vehicle'] = '%' . $vehicle . '%';
            }

            if ($driver !== '') {
                $where[] = 'fd.driver_name LIKE :driver';
                $params['driver'] = '%' . $driver . '%';
            }

            if ($destination !== '') {
                $where[] = 'fd.destination LIKE :dest';
                $params['dest'] = '%' . $destination . '%';
            }

            if ($fuelType !== '') {
                $where[] = 'COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") LIKE :fuel_type';
                $params['fuel_type'] = '%' . $fuelType . '%';
            }

            if ($dateFrom !== '') {
                $where[] = 'DATE(fd.created_at) >= :date_from';
                $params['date_from'] = $dateFrom;
            }

            if ($dateTo !== '') {
                $where[] = 'DATE(fd.created_at) <= :date_to';
                $params['date_to'] = $dateTo;
            }

            if ($minGallons !== null) {
                $where[] = 'fd.gallons >= :min_gallons';
                $params['min_gallons'] = $minGallons;
            }

            if ($maxGallons !== null) {
                $where[] = 'fd.gallons <= :max_gallons';
                $params['max_gallons'] = $maxGallons;
            }

            if ($status !== '') {
                $where[] = 'fd.status = :status';
                $params['status'] = $status;
            } else {
                // Ver el comentario equivalente en getDeliveriesStats():
                // por defecto se excluyen los anulados para que el total
                // que ve el usuario aquí coincida con el del Dashboard.
                $where[] = "fd.status <> 'Anulado'";
            }

            $sql = 'SELECT 
                        fd.id, 
                        fd.receipt_number, 
                        fd.vehicle_plate, 
                        fd.driver_name, 
                        COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") AS fuel_type,
                        fd.gallons, 
                        COALESCE(fd.destination, "Despacho General") AS destination, 
                        fd.notes,
                        fd.status, 
                        fd.created_at,
                        COALESCE(v.unit_code, "-") AS vehicle_unit_code,
                        COALESCE(vt.name, "Vehículo Municipal") AS vehicle_type_name
                    FROM fuel_deliveries fd
                    LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id
                    LEFT JOIN vehicles v ON ((fd.vehicle_id IS NOT NULL AND fd.vehicle_id > 0 AND v.id = fd.vehicle_id) OR v.plate = fd.vehicle_plate OR UPPER(REPLACE(v.plate, "-", "")) = UPPER(REPLACE(fd.vehicle_plate, "-", "")))
                    LEFT JOIN system_catalogs vt ON vt.id = v.vehicle_type_id
                    WHERE ' . implode(' AND ', $where) . '
                    ORDER BY fd.created_at DESC';

            if ($limit !== null) {
                $sql .= ' LIMIT ' . (int) $limit;
                if ($offset !== null) {
                    $sql .= ' OFFSET ' . (int) $offset;
                }
            }

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            error_log('Error en searchDeliveries (intentando fallback): ' . $e->getMessage());
            try {
                $sqlFallback = 'SELECT 
                                        fd.id, 
                                        fd.receipt_number, 
                                        fd.vehicle_plate, 
                                        fd.driver_name, 
                                        COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") AS fuel_type,
                                        fd.gallons, 
                                        COALESCE(fd.destination, "Despacho General") AS destination, 
                                        fd.notes,
                                        fd.status, 
                                        fd.created_at,
                                        COALESCE(v.unit_code, "-") AS vehicle_unit_code,
                                        COALESCE(vt.name, "Vehículo Municipal") AS vehicle_type_name
                                    FROM fuel_deliveries fd
                                    LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id
                                    LEFT JOIN vehicles v ON (v.plate = fd.vehicle_plate OR UPPER(REPLACE(v.plate, "-", "")) = UPPER(REPLACE(fd.vehicle_plate, "-", "")))
                                    LEFT JOIN system_catalogs vt ON vt.id = v.vehicle_type_id
                                    WHERE ' . implode(' AND ', $where) . '
                                    ORDER BY fd.created_at DESC';

                if ($limit !== null) {
                    $sqlFallback .= ' LIMIT ' . (int) $limit;
                    if ($offset !== null) {
                        $sqlFallback .= ' OFFSET ' . (int) $offset;
                    }
                }

                $stmt = $db->prepare($sqlFallback);
                $stmt->execute($params);
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Throwable $t2) {
                error_log('Error en searchDeliveries fallback: ' . $t2->getMessage());
                return [];
            }
        }
    }

    public function getDailyDispatchedFuel(int $days = 7): array
    {
        try {
            $db = Database::getConnection();

            $sql = 'SELECT 
                        DATE(fd.created_at) AS date,
                        COALESCE(SUM(fd.gallons), 0) AS total_gallons,
                        COUNT(fd.id) AS delivery_count
                    FROM fuel_deliveries fd
                    WHERE fd.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL :days DAY) AND fd.status <> "Anulado"
                    GROUP BY DATE(fd.created_at)
                    ORDER BY date ASC';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Indexar lo que sí vino de la BD por fecha (Y-m-d)
            $byDate = [];
            foreach ($results as $row) {
                $byDate[$row['date']] = $row;
            }

            $months = ['01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'];

            // Completar TODOS los días del rango (aunque no tengan despachos) para
            // que el eje del gráfico "últimos N días" siempre muestre N barras.
            $mapped = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-{$i} days"));
                $dayNum = date('d', strtotime($date));
                $mNum = date('m', strtotime($date));
                $row = $byDate[$date] ?? null;

                $mapped[] = [
                    'date' => $date,
                    'label' => $dayNum . ' ' . ($months[$mNum] ?? ''),
                    'total_gallons' => $row ? (float) $row['total_gallons'] : 0.0,
                    'delivery_count' => $row ? (int) $row['delivery_count'] : 0
                ];
            }

            return $mapped;
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        return [];
    }

    public function getFuelStats(string $view = 'diario'): array
    {
        $view = strtolower(trim($view));

        switch ($view) {
            case 'tipo':
            case 'tipo_combustible':
                return $this->getFuelDispatchedByType();

            case 'mensual':
                return $this->getMonthlyFuelDispatched();

            case 'anual':
                return $this->getYearlyFuelDispatched();

            case 'area':
            case 'area_destino':
                return $this->getFuelDispatchedByArea();

            case 'diario':
            default:
                return $this->getDailyDispatchedFuel(7);
        }
    }

    public function getMonthlyFuelDispatched(): array
    {
        $monthsMap = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        try {
            $db = Database::getConnection();
            $sql = 'SELECT 
                        MONTH(fd.created_at) AS month_num,
                        COALESCE(SUM(fd.gallons), 0) AS total_gallons,
                        COUNT(fd.id) AS delivery_count
                    FROM fuel_deliveries fd
                    WHERE YEAR(fd.created_at) = YEAR(CURRENT_DATE()) AND fd.status <> "Anulado"
                    GROUP BY MONTH(fd.created_at)
                    ORDER BY month_num ASC';

            $stmt = $db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                $output = [];
                foreach ($results as $row) {
                    $mNum = (int) $row['month_num'];
                    $output[] = [
                        'label' => $monthsMap[$mNum] ?? 'Mes ' . $mNum,
                        'total_gallons' => (float) $row['total_gallons'],
                        'delivery_count' => (int) $row['delivery_count']
                    ];
                }
                return $output;
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        return [];
    }

    public function getYearlyFuelDispatched(): array
    {
        try {
            $db = Database::getConnection();
            $sql = 'SELECT 
                        YEAR(fd.created_at) AS year_num,
                        COALESCE(SUM(fd.gallons), 0) AS total_gallons,
                        COUNT(fd.id) AS delivery_count
                    FROM fuel_deliveries fd
                    WHERE fd.status <> "Anulado"
                    GROUP BY YEAR(fd.created_at)
                    ORDER BY year_num ASC';

            $stmt = $db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                $output = [];
                foreach ($results as $row) {
                    $output[] = [
                        'label' => 'Año ' . $row['year_num'],
                        'total_gallons' => (float) $row['total_gallons'],
                        'delivery_count' => (int) $row['delivery_count']
                    ];
                }
                return $output;
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        return [];
    }

    public function getFuelDispatchedByArea(): array
    {
        try {
            $db = Database::getConnection();
            // Sin LIMIT en el SQL: se trae todo agrupado y se recorta en
            // PHP, agrupando el resto en "Otros" en vez de descartarlo
            // silenciosamente (antes un LIMIT 8 en la consulta hacía que
            // las áreas más allá de la 8va desaparecieran del reporte).
            //
            // ORDER BY con desempate por nombre: sin el "area_name ASC",
            // dos áreas con el mismo total_gallons (muy común con pocos
            // datos, o simplemente 0) no tienen un orden garantizado por
            // MySQL entre una ejecución y otra. Eso hacía que el área que
            // cae justo en el límite de "top 7" pudiera cambiar de una
            // carga de página a la siguiente sin que nadie tocara nada,
            // y su conteo individual "desaparecía" dentro de "Otros" y
            // volvía a aparecer de forma aparentemente aleatoria.
            $sql = 'SELECT 
                        COALESCE(fd.destination, "Despacho General") AS area_name,
                        COALESCE(SUM(fd.gallons), 0) AS total_gallons,
                        COUNT(fd.id) AS delivery_count
                    FROM fuel_deliveries fd
                    WHERE fd.status <> "Anulado"
                    GROUP BY area_name
                    ORDER BY total_gallons DESC, area_name ASC';

            $stmt = $db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                $topLimit = 7;
                $output = [];
                $otrosGallons = 0.0;
                $otrosCount = 0;

                foreach ($results as $i => $row) {
                    if ($i < $topLimit) {
                        $output[] = [
                            'label' => $row['area_name'],
                            'total_gallons' => (float) $row['total_gallons'],
                            'delivery_count' => (int) $row['delivery_count']
                        ];
                    } else {
                        $otrosGallons += (float) $row['total_gallons'];
                        $otrosCount += (int) $row['delivery_count'];
                    }
                }

                if ($otrosCount > 0) {
                    // Si alguna de las áreas del catálogo se llama
                    // literalmente "Otros" (o "Otras"), no se puede usar
                    // esa misma etiqueta para el sobrante: sus galones
                    // quedarían sumados dentro del bucket de sobrantes y
                    // ya no se podría distinguir cuánto es del área real
                    // y cuánto es del resto. Se desambigua con un
                    // asterisco solo en ese caso raro.
                    $usedLabels = array_column($output, 'label');
                    $sobranteLabel = in_array('Otros', $usedLabels, true) ? 'Otros*' : 'Otros';

                    $output[] = [
                        'label' => $sobranteLabel,
                        'total_gallons' => $otrosGallons,
                        'delivery_count' => $otrosCount
                    ];
                }

                return $output;
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        return [];
    }

    public function getFuelDispatchedByType(): array
    {
        try {
            $db = Database::getConnection();
            $sql = 'SELECT 
                    COALESCE(fd.fuel_type, fi.fuel_type, "Diesel B5 S-50") AS fuel_type_name,
                    COALESCE(SUM(fd.gallons), 0) AS total_gallons,
                    COUNT(fd.id) AS delivery_count
                FROM fuel_deliveries fd
                LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id
                WHERE fd.status <> "Anulado"
                GROUP BY fuel_type_name
                ORDER BY total_gallons DESC';

            $stmt = $db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                $output = [];
                foreach ($results as $row) {
                    $output[] = [
                        'label' => $row['fuel_type_name'],
                        'total_gallons' => (float) $row['total_gallons'],
                        'delivery_count' => (int) $row['delivery_count']
                    ];
                }
                return $output;
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        return [];
    }
    /**
     * Recalcula gallons_remaining de TODOS los lotes desde
     * fuel_delivery_lot_allocations (fuente real de verdad, ver
     * comentario más abajo). Es una herramienta de reconciliación
     * "por si acaso" para reparar drift manual en la base de datos
     * (ediciones directas por SQL, migraciones, etc.) — NO se debe
     * llamar automáticamente en cada carga de página: en operación
     * normal los triggers trg_alloc_* (database.sql) ya mantienen
     * gallons_remaining exacto en tiempo real fila por fila, y este
     * método hace un UPDATE de tabla completa que puede chocar (lock
     * wait / deadlock) con los SELECT ... FOR UPDATE que toma
     * planFifoAllocation() durante un despacho concurrente. Antes se
     * invocaba desde getStockByFuelType(), getTankLevels() e
     * IncomeController::index() en cada simple visita de solo
     * lectura; se quitaron esas llamadas. Se deja disponible para
     * invocarla manualmente (p.ej. un botón de admin "Recalcular
     * stock") si alguna vez hace falta reparar datos.
     */
    public function recalculateStockBalances(): void
    {
        try {
            $db = Database::getConnection();
            // IMPORTANTE: el descuento real de stock vive en
            // fuel_delivery_lot_allocations (ver triggers trg_alloc_*
            // en database.sql), porque un despacho puede repartirse
            // entre varios lotes y en ese caso fuel_deliveries.fuel_income_id
            // queda en NULL (ver FuelController::store()). La versión
            // anterior de este método sumaba fuel_deliveries.gallons
            // filtrando por fd.fuel_income_id = fi.id, así que cualquier
            // despacho multi-lote (fuel_income_id NULL) quedaba fuera
            // de la cuenta y esta función terminaba "revirtiendo" el
            // descuento correcto que ya habían hecho los triggers cada
            // vez que se cargaba el Dashboard. Ahora se calcula desde
            // fuel_delivery_lot_allocations, que sí tiene una fila por
            // cada lote realmente usado en cada despacho.
            $db->exec("
                UPDATE fuel_incomes fi
                SET gallons_remaining = GREATEST(0, fi.gallons - (
                    SELECT COALESCE(SUM(fdla.gallons_taken), 0)
                    FROM fuel_delivery_lot_allocations fdla
                    INNER JOIN fuel_deliveries fd ON fd.id = fdla.delivery_id
                    WHERE fdla.fuel_income_id = fi.id
                    AND fd.status <> 'Anulado'
                ))
            ");
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }
    }

    public function getStockByFuelType(): array
    {
        try {
            // Ya no se llama recalculateStockBalances() aquí: los
            // triggers mantienen gallons_remaining al día en cada
            // despacho/anulación, así que una vista de solo lectura
            // no necesita (ni debe) reescribir toda la tabla primero.
            $db = Database::getConnection();
            $stmt = $db->query('SELECT 
                                    COALESCE(fuel_type, "Diesel B5 S-50") AS fuel_type,
                                    COALESCE(SUM(gallons_remaining), 0) AS remaining_gallons
                                FROM fuel_incomes
                                WHERE gallons_remaining > 0
                                GROUP BY fuel_type');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                $output = [];
                foreach ($results as $row) {
                    $output[] = [
                        'label' => $row['fuel_type'],
                        'value' => (float) $row['remaining_gallons']
                    ];
                }
                return $output;
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        // No se muestran datos simulados: el dashboard debe reflejar únicamente
        // ingresos registrados y su saldo disponible real.
        return [];
    }

    public function getTankLevels(): array
    {
        // Ídem getStockByFuelType(): ya no se recalcula en cada lectura,
        // los triggers mantienen el saldo al día.
        $catalog = new Catalog();
        $catalogTypes = $catalog->getFuelTypes();

        $incomeStats = [];
        try {
            $db = Database::getConnection();
            $stmt = $db->query('SELECT 
                                    COALESCE(fuel_type, "Diesel B5 S-50") AS fuel_type_name,
                                    COALESCE(SUM(gallons), 0) AS total_income,
                                    COALESCE(SUM(gallons_remaining), 0) AS current_stock
                                FROM fuel_incomes
                                GROUP BY fuel_type_name');
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $r) {
                $incomeStats[trim($r['fuel_type_name'])] = [
                    'total_income' => (float) $r['total_income'],
                    'current_stock' => (float) $r['current_stock'],
                ];
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        $capacities = [
            'Diesel B5 S-50' => 10000.0,
            'Gasohol Regular' => 5000.0,
            'Gasohol Premium' => 3000.0,
        ];

        $tanks = [];
        foreach ($catalogTypes as $type) {
            $stats = $incomeStats[$type] ?? ['total_income' => 0.0, 'current_stock' => 0.0];
            $current = $stats['current_stock'];
            // Si hay total ingresado se usa ese volumen como base del estanque; sino se toma la capacidad nominal
            $maxCap = $stats['total_income'] > 0 ? $stats['total_income'] : ($capacities[$type] ?? 5000.0);

            $percentage = $maxCap > 0 ? min(100.0, round(($current / $maxCap) * 100, 1)) : 0.0;

            $statusColor = '#10b981';
            $statusText = 'Óptimo';
            $isEmpty = false;
            if ($current <= 0) {
                // Rojo más intenso que "Crítico" para que un depósito vacío
                // no se confunda visualmente con un simple nivel bajo.
                $statusColor = '#dc2626';
                $statusText = 'Sin Stock';
                $isEmpty = true;
            } elseif ($percentage <= 20) {
                $statusColor = '#ef4444';
                $statusText = 'Crítico';
            } elseif ($percentage <= 45) {
                $statusColor = '#f59e0b';
                $statusText = 'Advertencia';
            }

            $tanks[] = [
                'name' => 'Depósito ' . $type,
                'fuel_type' => $type,
                'capacity' => $maxCap,
                'current' => $current,
                'percentage' => $percentage,
                'status_color' => $statusColor,
                'status_text' => $statusText,
                'is_empty' => $isEmpty,
            ];
        }

        return $tanks;
    }

    public function getFilteredDeliveries(?string $startDate = null, ?string $endDate = null, ?string $vehicle = null, ?string $driver = null, ?string $destination = null, ?int $limit = null): array
    {
        $filters = [
            'date_from' => $startDate,
            'date_to' => $endDate,
            'vehicle' => $vehicle,
            'driver' => $driver,
            'destination' => $destination,
        ];

        // Se pasa el límite directo al SQL en vez de traer toda la BD y
        // hacer array_slice en memoria PHP.
        return $this->searchDeliveries($filters, $limit > 0 ? $limit : null);
    }

    /**
     * @param int|null $days  Si se indica, solo trae eventos de los últimos
     *                        N días (evita cargar el historial completo en
     *                        cada carga de Dashboard/Calendario, que antes
     *                        no tenía ningún límite y crecía sin fin).
     * @param int|null $limit Tope de filas por tipo (despachos / ingresos).
     */
    public function getDeliveryEvents(?int $days = null, ?int $limit = null): array
    {
        $events = [];
        try {
            $db = Database::getConnection();

            // 1. Obtener Despachos
            $sqlDeliveries = 'SELECT 
                                fd.id,
                                fd.receipt_number AS title,
                                DATE(fd.created_at) AS date,
                                fd.created_at AS date_time,
                                fd.gallons,
                                fd.vehicle_plate AS vehicle,
                                fd.driver_name AS driver,
                                COALESCE(fd.destination, "Despacho General") AS destination
                            FROM fuel_deliveries fd
                            WHERE fd.status <> "Anulado"'
                            . ($days !== null ? ' AND fd.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL :days DAY)' : '')
                            . ' ORDER BY fd.created_at DESC'
                            . ($limit !== null ? ' LIMIT :limit' : '');

            $stmt1 = $db->prepare($sqlDeliveries);
            if ($days !== null) {
                $stmt1->bindValue(':days', $days, PDO::PARAM_INT);
            }
            if ($limit !== null) {
                $stmt1->bindValue(':limit', $limit, PDO::PARAM_INT);
            }
            $stmt1->execute();
            $rawDeliveries = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rawDeliveries as $row) {
                $events[] = [
                    'id' => 'fuel_' . $row['id'],
                    'type' => 'fuel',
                    'title' => 'Despacho ' . $row['title'] . ' (' . $row['vehicle'] . ')',
                    'date' => $row['date'],
                    'date_time' => $row['date_time'],
                    'time' => date('H:i', strtotime($row['date_time'])),
                    'gallons' => (float) $row['gallons'],
                    'vehicle' => $row['vehicle'],
                    'driver' => $row['driver'],
                    'destination' => $row['destination'],
                    'badge' => 'Despacho Combustible',
                    'badge_class' => 'badge-fuel'
                ];
            }

            // 2. Obtener Ingresos de Lotes/Stock
            $sqlIncomes = 'SELECT 
                            fi.id,
                            fi.receipt_number AS title,
                            DATE(fi.created_at) AS date,
                            fi.created_at AS date_time,
                            fi.gallons,
                            COALESCE(fi.fuel_type, "Combustible") AS fuel_type,
                            COALESCE(fi.supplier, "Proveedor General") AS supplier
                        FROM fuel_incomes fi'
                        . ($days !== null ? ' WHERE fi.created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL :days DAY)' : '')
                        . ' ORDER BY fi.created_at DESC'
                        . ($limit !== null ? ' LIMIT :limit' : '');

            $stmt2 = $db->prepare($sqlIncomes);
            if ($days !== null) {
                $stmt2->bindValue(':days', $days, PDO::PARAM_INT);
            }
            if ($limit !== null) {
                $stmt2->bindValue(':limit', $limit, PDO::PARAM_INT);
            }
            $stmt2->execute();
            $rawIncomes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rawIncomes as $row) {
                $events[] = [
                    'id' => 'income_' . $row['id'],
                    'type' => 'income',
                    'title' => 'Ingreso Lote ' . $row['title'] . ' (' . $row['fuel_type'] . ')',
                    'date' => $row['date'],
                    'date_time' => $row['date_time'],
                    'time' => date('H:i', strtotime($row['date_time'])),
                    'gallons' => (float) $row['gallons'],
                    'vehicle' => 'Stock Lote',
                    'driver' => $row['supplier'],
                    'destination' => 'Almacén Central',
                    'badge' => 'Ingreso de Stock',
                    'badge_class' => 'badge-stock'
                ];
            }

        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        return $events;
    }

    public function getUniqueVehicles(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT DISTINCT vehicle_plate FROM fuel_deliveries WHERE vehicle_plate IS NOT NULL AND vehicle_plate <> '' ORDER BY vehicle_plate ASC");
            $plates = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $plates ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getUniqueDrivers(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT DISTINCT driver_name FROM fuel_deliveries WHERE driver_name IS NOT NULL AND driver_name <> '' ORDER BY driver_name ASC");
            $drivers = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $drivers ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getUniqueDestinations(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT DISTINCT destination FROM fuel_deliveries WHERE destination IS NOT NULL AND destination <> '' ORDER BY destination ASC");
            $destinations = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $destinations ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getUniqueFuelTypes(): array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT DISTINCT fuel_type FROM fuel_deliveries WHERE fuel_type IS NOT NULL AND fuel_type <> '' ORDER BY fuel_type ASC");
            $types = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $types ?: [];
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getAreaRelations(): array
    {
        $relations = [];
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT DISTINCT destination, vehicle_plate, driver_name FROM fuel_deliveries WHERE destination IS NOT NULL AND destination <> ''");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $area = trim($row['destination']);
                $plate = trim($row['vehicle_plate']);
                $driver = trim($row['driver_name']);

                if (!isset($relations[$area])) {
                    $relations[$area] = [
                        'plates' => [],
                        'drivers' => []
                    ];
                }

                if ($plate !== '' && !in_array($plate, $relations[$area]['plates'], true)) {
                    $relations[$area]['plates'][] = $plate;
                }

                if ($driver !== '' && !in_array($driver, $relations[$area]['drivers'], true)) {
                    $relations[$area]['drivers'][] = $driver;
                }
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        return $relations;
    }

    /**
     * Calcula qué lotes usar (del más antiguo al más nuevo) para cubrir
     * $gallonsNeeded galones de $fuelType, repartiendo entre varios si
     * ninguno alcanza solo.
     *
     * @return array<int, array{income_id:int, receipt_number:string, take:float}>
     * @throws \RuntimeException si el stock TOTAL de ese tipo no alcanza
     */
    public function planFifoAllocation(string $fuelType, float $gallonsNeeded): array
    {
        $db = Database::getConnection();

        // FOR UPDATE: bloquea las filas de lotes candidatos hasta que la
        // transacción que llama a este método haga commit/rollback, para
        // que dos despachos simultáneos no calculen el plan sobre el
        // mismo stock "viejo" y lo descuenten dos veces.
        $stmt = $db->prepare(
            'SELECT id, receipt_number, gallons_remaining
               FROM fuel_incomes
              WHERE fuel_type = :fuel_type
                AND gallons_remaining > 0
              ORDER BY id ASC
              FOR UPDATE'
        );
        $stmt->execute(['fuel_type' => $fuelType]);
        $lots = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $remainingNeeded = $gallonsNeeded;
        $plan = [];

        foreach ($lots as $lot) {
            if ($remainingNeeded <= 0) {
                break;
            }

            $stock = (float) $lot['gallons_remaining'];
            $take = min($stock, $remainingNeeded);

            $plan[] = [
                'income_id'      => (int) $lot['id'],
                'receipt_number' => $lot['receipt_number'],
                'take'           => round($take, 2),
            ];

            $remainingNeeded = round($remainingNeeded - $take, 2);
        }

        if ($remainingNeeded > 0.001) {
            $totalStock = array_sum(array_column($lots, 'gallons_remaining'));
            throw new \RuntimeException(
                'Stock insuficiente. Se pidieron ' . number_format($gallonsNeeded, 2)
                . ' Gal de ' . $fuelType . ', pero el stock total disponible es de '
                . number_format((float) $totalStock, 2) . ' Gal.'
            );
        }

        return $plan;
    }
}