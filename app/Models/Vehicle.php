<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

final class Vehicle
{
    public static function ensureTableExists(): void
    {
        try {
            $db = Database::getConnection();
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

            // Por si la tabla ya existía sin la columna (instalaciones previas)
            try {
                $db->exec("ALTER TABLE vehicles ADD COLUMN vehicle_type_id INT UNSIGNED NULL AFTER area_id");
            } catch (Throwable $e) {}

            // Por si la tabla ya existía con tamaños viejos (unit_code 40 / plate 20)
            try {
                $db->exec("ALTER TABLE vehicles MODIFY unit_code VARCHAR(10) NOT NULL");
            } catch (Throwable $e) {}
            try {
                $db->exec("ALTER TABLE vehicles MODIFY plate VARCHAR(7) NOT NULL");
            } catch (Throwable $e) {}
        } catch (Throwable $e) {}
    }

    /**
     * Solo letras, números y guiones (sin espacios ni otros símbolos).
     */
    private static function isValidCode(string $value): bool
    {
        return (bool) preg_match('/^[A-Za-z0-9\-]+$/', $value);
    }

    /**
     * Vehículos activos, con el nombre del área ya resuelto para mostrar en selects.
     */
    public function getActive(): array
    {
        self::ensureTableExists();
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT v.id, v.unit_code, v.plate, v.area_id, v.vehicle_type_id,
                                        COALESCE(sc.name, 'Sin área asignada') AS area_name,
                                        COALESCE(vt.name, 'Sin tipo') AS vehicle_type_name
                                 FROM vehicles v
                                 LEFT JOIN system_catalogs sc ON sc.id = v.area_id
                                 LEFT JOIN system_catalogs vt ON vt.id = v.vehicle_type_id
                                 WHERE v.status = 'activo'
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
            $stmt = $db->prepare("SELECT * FROM vehicles WHERE id = :id AND status = 'activo' LIMIT 1");
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
            $stmt = $db->prepare("SELECT 1 FROM vehicles WHERE plate = :plate OR unit_code = :unit_code LIMIT 1");
            $stmt->execute(['plate' => $plate, 'unit_code' => $unitCode]);
            return (bool) $stmt->fetchColumn();
        } catch (Throwable $e) {
            return false;
        }
    }

    public function create(string $unitCode, string $plate, ?int $areaId, ?int $vehicleTypeId = null): bool
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

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO vehicles (unit_code, plate, area_id, vehicle_type_id, status) VALUES (:unit_code, :plate, :area_id, :vehicle_type_id, 'activo')");
            return $stmt->execute([
                'unit_code' => $unitCode,
                'plate' => $plate,
                'area_id' => $areaId,
                'vehicle_type_id' => $vehicleTypeId,
            ]);
        } catch (Throwable $e) {
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
