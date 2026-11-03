<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

final class Vehicle
{
    private static bool $tableEnsured = false;

    public static function ensureTableExists(): void
    {
        if (self::$tableEnsured) {
            return;
        }
        self::$tableEnsured = true;

        try {
            $db = Database::getConnection();
            $checkTable = $db->query("SHOW TABLES LIKE 'vehicles'")->fetch();
            if (!$checkTable) {
                $db->exec("CREATE TABLE IF NOT EXISTS vehicles (
                    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    unit_code VARCHAR(10) NOT NULL UNIQUE,
                    plate VARCHAR(7) NOT NULL UNIQUE,
                    area_id INT UNSIGNED NULL,
                    vehicle_type_id INT UNSIGNED NULL,
                    status ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_vehicles_status (status)
                ) ENGINE=InnoDB;");
            }

            $checkCol = $db->query("SHOW COLUMNS FROM vehicles LIKE 'vehicle_type_id'")->fetch();
            if (!$checkCol) {
                try {
                    $db->exec("ALTER TABLE vehicles ADD COLUMN vehicle_type_id INT UNSIGNED NULL AFTER area_id");
                } catch (\Throwable $e) {}
            }

            $checkFuelCol = $db->query("SHOW COLUMNS FROM vehicles LIKE 'fuel_type_id'")->fetch();
            if (!$checkFuelCol) {
                try {
                    $db->exec("ALTER TABLE vehicles ADD COLUMN fuel_type_id INT UNSIGNED NULL AFTER vehicle_type_id");
                } catch (\Throwable $e) {}
            }

            // Tabla pivot para soporte de múltiples tipos de combustible por vehículo (ej. Gasolina + GNV)
            $checkPivot = $db->query("SHOW TABLES LIKE 'vehicle_fuel_types'")->fetch();
            if (!$checkPivot) {
                try {
                    $db->exec("CREATE TABLE IF NOT EXISTS vehicle_fuel_types (
                        vehicle_id INT UNSIGNED NOT NULL,
                        fuel_type_id INT UNSIGNED NOT NULL,
                        PRIMARY KEY (vehicle_id, fuel_type_id),
                        INDEX idx_vft_fuel (fuel_type_id),
                        CONSTRAINT fk_vft_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles (id) ON DELETE CASCADE ON UPDATE CASCADE,
                        CONSTRAINT fk_vft_fuel FOREIGN KEY (fuel_type_id) REFERENCES system_catalogs (id) ON DELETE CASCADE ON UPDATE CASCADE
                    ) ENGINE=InnoDB");

                    $db->exec("INSERT IGNORE INTO vehicle_fuel_types (vehicle_id, fuel_type_id)
                               SELECT id, fuel_type_id FROM vehicles
                               WHERE fuel_type_id IS NOT NULL AND fuel_type_id > 0");
                } catch (\Throwable $e) {}
            }
        } catch (\Throwable $e) {
            error_log('Vehicle ensureTableExists: ' . $e->getMessage());
        }
    }

    /**
     * Solo letras, números y guiones (sin espacios ni otros símbolos).
     */
    private static function isValidCode(string $value): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9\-]+$/', $value);
    }

    /**
     * Vehículos activos, con el nombre del área, tipo y combustibles ya resueltos para mostrar en selects y listados.
     */
    public function getActive(): array
    {
        self::ensureTableExists();
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT v.id, v.unit_code, v.plate, v.area_id, v.vehicle_type_id, v.fuel_type_id,
                                        COALESCE(sc.name, 'Sin área asignada') AS area_name,
                                        COALESCE(vt.name, 'Sin tipo') AS vehicle_type_name,
                                        COALESCE(
                                            GROUP_CONCAT(DISTINCT ft.name ORDER BY ft.name SEPARATOR ' / '),
                                            COALESCE(legacy_ft.name, 'Sin combustible')
                                        ) AS fuel_type_name,
                                        GROUP_CONCAT(DISTINCT vft.fuel_type_id ORDER BY vft.fuel_type_id) AS fuel_type_ids
                                 FROM vehicles v
                                 LEFT JOIN system_catalogs sc ON sc.id = v.area_id
                                 LEFT JOIN system_catalogs vt ON vt.id = v.vehicle_type_id
                                 LEFT JOIN vehicle_fuel_types vft ON vft.vehicle_id = v.id
                                 LEFT JOIN system_catalogs ft ON ft.id = vft.fuel_type_id AND ft.status = 'activo'
                                 LEFT JOIN system_catalogs legacy_ft ON legacy_ft.id = v.fuel_type_id
                                 WHERE v.status = 'activo'
                                 GROUP BY v.id, v.unit_code, v.plate, v.area_id, v.vehicle_type_id, v.fuel_type_id, sc.name, vt.name, legacy_ft.name
                                 ORDER BY v.plate ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function findById(int $id): ?array
    {
        self::ensureTableExists();
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT v.*,
                                         COALESCE(sc.name, 'Sin área asignada') AS area_name,
                                         COALESCE(vt.name, 'Sin tipo') AS vehicle_type_name,
                                         COALESCE(
                                             GROUP_CONCAT(DISTINCT ft.name ORDER BY ft.name SEPARATOR ' / '),
                                             COALESCE(legacy_ft.name, 'Sin combustible')
                                         ) AS fuel_type_name,
                                         GROUP_CONCAT(DISTINCT vft.fuel_type_id ORDER BY vft.fuel_type_id) AS fuel_type_ids
                                  FROM vehicles v
                                  LEFT JOIN system_catalogs sc ON sc.id = v.area_id
                                  LEFT JOIN system_catalogs vt ON vt.id = v.vehicle_type_id
                                  LEFT JOIN vehicle_fuel_types vft ON vft.vehicle_id = v.id
                                  LEFT JOIN system_catalogs ft ON ft.id = vft.fuel_type_id AND ft.status = 'activo'
                                  LEFT JOIN system_catalogs legacy_ft ON legacy_ft.id = v.fuel_type_id
                                  WHERE v.id = :id AND v.status = 'activo'
                                  GROUP BY v.id, v.unit_code, v.plate, v.area_id, v.vehicle_type_id, v.fuel_type_id, sc.name, vt.name, legacy_ft.name
                                  LIMIT 1");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function existsByPlateOrCode(string $plate, string $unitCode): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT 1 FROM vehicles WHERE (plate = :plate OR unit_code = :unit_code) AND status = 'activo' LIMIT 1");
            $stmt->execute(['plate' => $plate, 'unit_code' => $unitCode]);
            return (bool) $stmt->fetchColumn();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function create(string $unitCode, string $plate, ?int $areaId, ?int $vehicleTypeId = null, array|int|null $fuelTypeIds = null): bool
    {
        self::ensureTableExists();
        $unitCode = trim($unitCode);
        $plate = strtoupper(trim($plate));

        if ($unitCode === '' || $plate === '') {
            return false;
        }

        // Código de unidad: máx. 10 caracteres, solo letras/números/guiones
        if (mb_strlen($unitCode) > 10 || !self::isValidCode($unitCode)) {
            return false;
        }

        // Placa: máx. 7 caracteres, solo letras/números/guiones
        if (mb_strlen($plate) > 7 || !self::isValidCode($plate)) {
            return false;
        }

        // Normalizar los identificadores de combustible recibidos
        $fuelIds = [];
        if (is_array($fuelTypeIds)) {
            foreach ($fuelTypeIds as $fid) {
                $intId = (int) $fid;
                if ($intId > 0 && !in_array($intId, $fuelIds, true)) {
                    $fuelIds[] = $intId;
                }
            }
        } elseif ($fuelTypeIds !== null && (int) $fuelTypeIds > 0) {
            $fuelIds[] = (int) $fuelTypeIds;
        }

        $primaryFuelId = !empty($fuelIds) ? $fuelIds[0] : null;

        $ownsTransaction = false;
        try {
            $db = Database::getConnection();
            if (!$db->inTransaction()) {
                $db->beginTransaction();
                $ownsTransaction = true;
            }

            $stmt = $db->prepare("INSERT INTO vehicles (unit_code, plate, area_id, vehicle_type_id, fuel_type_id, status) VALUES (:unit_code, :plate, :area_id, :vehicle_type_id, :fuel_type_id, 'activo')");
            $ok = $stmt->execute([
                'unit_code' => $unitCode,
                'plate' => $plate,
                'area_id' => $areaId,
                'vehicle_type_id' => $vehicleTypeId,
                'fuel_type_id' => $primaryFuelId,
            ]);

            if (!$ok) {
                if ($ownsTransaction && $db->inTransaction()) {
                    $db->rollBack();
                }
                return false;
            }

            $vehicleId = (int) $db->lastInsertId();

            if (!empty($fuelIds)) {
                $stmtVft = $db->prepare("INSERT IGNORE INTO vehicle_fuel_types (vehicle_id, fuel_type_id) VALUES (:v_id, :f_id)");
                foreach ($fuelIds as $fId) {
                    $stmtVft->execute([
                        'v_id' => $vehicleId,
                        'f_id' => $fId,
                    ]);
                }
            }

            if ($ownsTransaction && $db->inTransaction()) {
                $db->commit();
            }
            return true;
        } catch (Throwable $e) {
            error_log('Vehicle::create error: ' . $e->getMessage());
            try {
                if ($ownsTransaction && isset($db) && $db->inTransaction()) {
                    $db->rollBack();
                }
            } catch (Throwable $ignore) {}
            return false;
        }
    }

    public function delete(int $id): bool
    {
        self::ensureTableExists();
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE vehicles SET status = 'inactivo' WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (Throwable $e) {
            return false;
        }
    }
}
