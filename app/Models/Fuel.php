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

            // Auto-crear tablas base por resiliencia si la BD fuera nueva
            $db->exec('CREATE TABLE IF NOT EXISTS fuel_incomes (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNSIGNED NULL,
                supplier VARCHAR(160) NOT NULL,
                gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
                gallons_remaining DECIMAL(10,2) NOT NULL DEFAULT 0,
                unit_price DECIMAL(10,2) NOT NULL DEFAULT 0,
                receipt_number VARCHAR(80) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB');

            $db->exec('CREATE TABLE IF NOT EXISTS fuel_deliveries (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                fuel_income_id INT UNSIGNED NOT NULL DEFAULT 1,
                user_id INT UNSIGNED NULL,
                receipt_number VARCHAR(80) NOT NULL UNIQUE,
                vehicle_plate VARCHAR(40) NOT NULL,
                driver_name VARCHAR(120) NOT NULL,
                gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
                destination VARCHAR(160) NULL,
                status ENUM("Pendiente","Completado","Anulado") NOT NULL DEFAULT "Completado",
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB');

            // 1. Total de combustible ingresado
            $totalIngresado = 0.0;
            try {
                $stmt = $db->query('SELECT COALESCE(SUM(gallons), 0) AS total FROM fuel_incomes');
                $totalIngresado = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
            } catch (Throwable $t) {
                try {
                    $stmt = $db->query('SELECT COALESCE(SUM(cantidad), 0) AS total FROM ingresos_combustible');
                    $totalIngresado = (float) ($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);
                } catch (Throwable $t2) {
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
                } catch (Throwable $t2) {
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
                } catch (Throwable $t2) {
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
                } catch (Throwable $t2) {
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
                } catch (Throwable $t2) {
                }
            }

            return [
                'saldoDisponible' => $saldoDisponible,
                'totalIngresado' => $totalIngresado,
                'totalEntregado' => $totalEntregado,
                'consumoDia' => $consumoDia,
                'recibosMes' => $recibosMes,
            ];
        } catch (Throwable $e) {
            return [
                'saldoDisponible' => 0.00,
                'totalIngresado' => 0.00,
                'totalEntregado' => 0.00,
                'consumoDia' => 0.00,
                'recibosMes' => 0,
            ];
        }
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
                        "Diesel B5 S-50" AS fuel_type,
                        fd.gallons, 
                        COALESCE(fd.destination, "Despacho General") AS destination, 
                        fd.status, 
                        fd.created_at 
                    FROM fuel_deliveries fd
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
            } catch (Throwable $t2) {
            }
        }

        return [];
    }

    public function searchDeliveries(array $filters = []): array
    {
        $queryText = trim((string) ($filters['q'] ?? $filters['query'] ?? ''));
        $vehicle = trim((string) ($filters['vehicle'] ?? ''));
        $driver = trim((string) ($filters['driver'] ?? ''));
        $destination = trim((string) ($filters['destination'] ?? ''));
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
                $where[] = '(fd.receipt_number LIKE :q OR fd.vehicle_plate LIKE :q OR fd.driver_name LIKE :q OR fd.destination LIKE :q)';
                $params['q'] = '%' . $queryText . '%';
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
            }

            $sql = 'SELECT 
                        fd.id, 
                        fd.receipt_number, 
                        fd.vehicle_plate, 
                        fd.driver_name, 
                        COALESCE(fi.fuel_type, "Diesel B5 S-50") AS fuel_type,
                        fd.gallons, 
                        COALESCE(fd.destination, "Despacho General") AS destination, 
                        fd.status, 
                        fd.created_at 
                    FROM fuel_deliveries fd
                    LEFT JOIN fuel_incomes fi ON fi.id = fd.fuel_income_id
                    WHERE ' . implode(' AND ', $where) . '
                    ORDER BY fd.created_at DESC';

            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Throwable $e) {
            return [];
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

            if (!empty($results)) {
                $months = ['01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'];
                $mapped = [];
                foreach ($results as $row) {
                    $timestamp = strtotime($row['date']);
                    $dayNum = date('d', $timestamp);
                    $mNum = date('m', $timestamp);
                    $mapped[] = [
                        'date' => $row['date'],
                        'label' => $dayNum . ' ' . ($months[$mNum] ?? ''),
                        'total_gallons' => (float) $row['total_gallons'],
                        'delivery_count' => (int) $row['delivery_count']
                    ];
                }
                return $mapped;
            }
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
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
        } catch (Throwable $e) {
        }

        return [];
    }

    public function getFuelDispatchedByArea(): array
    {
        try {
            $db = Database::getConnection();
            $sql = 'SELECT 
                        COALESCE(fd.destination, "Despacho General") AS area_name,
                        COALESCE(SUM(fd.gallons), 0) AS total_gallons,
                        COUNT(fd.id) AS delivery_count
                    FROM fuel_deliveries fd
                    WHERE fd.status <> "Anulado"
                    GROUP BY area_name
                    ORDER BY total_gallons DESC
                    LIMIT 8';

            $stmt = $db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($results)) {
                $output = [];
                foreach ($results as $row) {
                    $output[] = [
                        'label' => $row['area_name'],
                        'total_gallons' => (float) $row['total_gallons'],
                        'delivery_count' => (int) $row['delivery_count']
                    ];
                }
                return $output;
            }
        } catch (Throwable $e) {
        }

        return [];
    }

    public function getFuelDispatchedByType(): array
    {
        try {
            $db = Database::getConnection();
            $sql = 'SELECT 
                        "Diesel B5 S-50" AS fuel_type_name,
                        COALESCE(SUM(fd.gallons), 0) AS total_gallons,
                        COUNT(fd.id) AS delivery_count
                    FROM fuel_deliveries fd
                    WHERE fd.status <> "Anulado"
                    GROUP BY fuel_type_name';

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
        } catch (Throwable $e) {
        }

        return [];
    }

    public function recalculateStockBalances(): void
    {
        try {
            $db = Database::getConnection();
            $db->exec("
                UPDATE fuel_incomes fi
                SET gallons_remaining = GREATEST(0, fi.gallons - (
                    SELECT COALESCE(SUM(fd.gallons), 0)
                    FROM fuel_deliveries fd
                    WHERE fd.fuel_income_id = fi.id
                    AND fd.status <> 'Anulado'
                ))
            ");
        } catch (Throwable $e) {
        }
    }

    public function getStockByFuelType(): array
    {
        try {
            $this->recalculateStockBalances();

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
        } catch (Throwable $e) {
        }

        return [
            ['label' => 'Diesel B5 S-50', 'value' => 5420.50],
            ['label' => 'Gasohol Regular', 'value' => 1850.00],
            ['label' => 'Gasohol Premium', 'value' => 859.00]
        ];
    }

    public function getTankLevels(): array
    {
        $this->recalculateStockBalances();

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
        } catch (Throwable $e) {}

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
            if ($current <= 0) {
                $statusColor = '#94a3b8';
                $statusText = 'Sin Stock';
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
            ];
        }

        return $tanks;
    }

    public function getFilteredDeliveries(?string $startDate = null, ?string $endDate = null, ?string $vehicle = null, ?string $driver = null, ?int $limit = null): array
    {
        $filters = [
            'date_from' => $startDate,
            'date_to' => $endDate,
            'vehicle' => $vehicle,
            'driver' => $driver,
        ];

        $deliveries = $this->searchDeliveries($filters);

        if ($limit !== null && $limit > 0) {
            return array_slice($deliveries, 0, $limit);
        }

        return $deliveries;
    }

    public function getDeliveryEvents(): array
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
                            WHERE fd.status <> "Anulado"
                            ORDER BY fd.created_at DESC';

            $stmt1 = $db->query($sqlDeliveries);
            $rawDeliveries = $stmt1->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rawDeliveries as $row) {
                $events[] = [
                    'id' => 'fuel_' . $row['id'],
                    'type' => 'fuel',
                    'title' => 'Despacho ' . $row['title'] . ' (' . $row['vehicle'] . ')',
                    'date' => $row['date'],
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
                        FROM fuel_incomes fi
                        ORDER BY fi.created_at DESC';

            $stmt2 = $db->query($sqlIncomes);
            $rawIncomes = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rawIncomes as $row) {
                $events[] = [
                    'id' => 'income_' . $row['id'],
                    'type' => 'income',
                    'title' => 'Ingreso Lote ' . $row['title'] . ' (' . $row['fuel_type'] . ')',
                    'date' => $row['date'],
                    'time' => date('H:i', strtotime($row['date_time'])),
                    'gallons' => (float) $row['gallons'],
                    'vehicle' => 'Stock Lote',
                    'driver' => $row['supplier'],
                    'destination' => 'Almacén Central',
                    'badge' => 'Ingreso de Stock',
                    'badge_class' => 'badge-stock'
                ];
            }

        } catch (Throwable $e) {
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
}
