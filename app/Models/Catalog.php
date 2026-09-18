<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

final class Catalog
{
    /**
     * Asegura que la tabla de catálogos exista y contenga datos por defecto
     */
    public static function ensureTableExists(): void
    {
        try {
            $db = Database::getConnection();
            $db->exec("CREATE TABLE IF NOT EXISTS system_catalogs (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                category ENUM('fuel_type', 'area', 'supplier', 'vehicle_type') NOT NULL,
                name VARCHAR(160) NOT NULL,
                status ENUM('activo', 'inactivo') DEFAULT 'activo',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_catalogs_cat (category, status)
            ) ENGINE=InnoDB;");

            // Por si la tabla ya existía con el ENUM anterior (sin 'vehicle_type')
            try {
                $db->exec("ALTER TABLE system_catalogs MODIFY category ENUM('fuel_type', 'area', 'supplier', 'vehicle_type') NOT NULL");
            } catch (Throwable $e) {
            }

            // Verificar si tiene registros, de lo contrario sembrar iniciales
            $stmt = $db->query("SELECT COUNT(*) FROM system_catalogs");
            if ((int) $stmt->fetchColumn() === 0) {
                $db->exec("INSERT INTO system_catalogs (category, name) VALUES
                    ('fuel_type', 'Diesel B5 S-50'),
                    ('fuel_type', 'Gasohol Regular'),
                    ('fuel_type', 'Gasohol Premium'),
                    ('fuel_type', 'GLP (Gas Licuado)'),
                    ('area', 'Serenazgo y Seguridad Ciudadana'),
                    ('area', 'Limpieza Pública y Recolección'),
                    ('area', 'Obras Públicas e Infraestructura'),
                    ('area', 'Parques y Jardines'),
                    ('area', 'Maquinaria y Equipo Pesado'),
                    ('area', 'Fiscalización y Transporte'),
                    ('area', 'Gerencia Municipal'),
                    ('supplier', 'Petroperú Pisco'),
                    ('supplier', 'Repsol Grifo Pisco'),
                    ('supplier', 'Primax Estación Municipal'),
                    ('supplier', 'Grifo Central Pisco'),
                    ('vehicle_type', 'Patrullero'),
                    ('vehicle_type', 'Camioneta'),
                    ('vehicle_type', 'Moto'),
                    ('vehicle_type', 'Volquete'),
                    ('vehicle_type', 'Camión Cisterna'),
                    ('vehicle_type', 'Compactadora'),
                    ('vehicle_type', 'Retroexcavadora'),
                    ('vehicle_type', 'Ambulancia'),
                    ('vehicle_type', 'Automóvil');");
            } else {
                // Si ya existían catálogos pero nunca se sembró vehicle_type, agregarlos ahora
                $stmt = $db->query("SELECT COUNT(*) FROM system_catalogs WHERE category = 'vehicle_type'");
                if ((int) $stmt->fetchColumn() === 0) {
                    $db->exec("INSERT INTO system_catalogs (category, name) VALUES
                        ('vehicle_type', 'Patrullero'),
                        ('vehicle_type', 'Camioneta'),
                        ('vehicle_type', 'Moto'),
                        ('vehicle_type', 'Volquete'),
                        ('vehicle_type', 'Camión Cisterna'),
                        ('vehicle_type', 'Compactadora'),
                        ('vehicle_type', 'Retroexcavadora'),
                        ('vehicle_type', 'Ambulancia'),
                        ('vehicle_type', 'Automóvil');");
                }
            }
        } catch (Throwable $e) {
        }
    }

    public function getByCategory(string $category): array
    {
        self::ensureTableExists();
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM system_catalogs WHERE category = :cat AND status = 'activo' ORDER BY name ASC");
            $stmt->execute(['cat' => $category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return [];
        }
    }

    public function getFuelTypes(): array
    {
        return array_column($this->getByCategory('fuel_type'), 'name');
    }

    public function getAreas(): array
    {
        return array_column($this->getByCategory('area'), 'name');
    }

    public function getVehicleTypes(): array
    {
        return array_column($this->getByCategory('vehicle_type'), 'name');
    }

    public function getSuppliers(): array
    {
        return array_column($this->getByCategory('supplier'), 'name');
    }

    /**
     * Verifica que un valor exista como registro activo dentro de una
     * categoría del catálogo. Se usa para validar en servidor antes de
     * guardar (ver IncomeController::store()).
     */
    public function existsInCategory(string $category, string $name): bool
    {
        $items = $this->getByCategory($category);
        foreach ($items as $item) {
            if (strcasecmp($item['name'], $name) === 0) {
                return true;
            }
        }
        return false;
    }

    public function create(string $category, string $name): bool
    {
        self::ensureTableExists();
        $name = trim($name);
        if ($name === '')
            return false;

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("INSERT INTO system_catalogs (category, name, status) VALUES (:cat, :name, 'activo')");
            return $stmt->execute(['cat' => $category, 'name' => $name]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        self::ensureTableExists();
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE system_catalogs SET status = 'inactivo' WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (Throwable $e) {
            return false;
        }
    }
}