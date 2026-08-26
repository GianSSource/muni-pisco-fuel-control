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
                category ENUM('fuel_type', 'area', 'supplier') NOT NULL,
                name VARCHAR(160) NOT NULL,
                status ENUM('activo', 'inactivo') DEFAULT 'activo',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_catalogs_cat (category, status)
            ) ENGINE=InnoDB;");

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
                    ('supplier', 'Grifo Central Pisco');");
            }
        } catch (Throwable $e) {}
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
        $items = $this->getByCategory('fuel_type');
        if (empty($items)) {
            return ['Diesel B5 S-50', 'Gasohol Regular', 'Gasohol Premium', 'GLP (Gas Licuado)'];
        }
        return array_column($items, 'name');
    }

    public function getAreas(): array
    {
        $items = $this->getByCategory('area');
        if (empty($items)) {
            return [
                'Serenazgo y Seguridad Ciudadana',
                'Limpieza Pública y Recolección',
                'Obras Públicas e Infraestructura',
                'Parques y Jardines',
                'Maquinaria y Equipo Pesado',
                'Fiscalización y Transporte',
                'Gerencia Municipal',
                'Servicios Generales'
            ];
        }
        return array_column($items, 'name');
    }

    public function getSuppliers(): array
    {
        $items = $this->getByCategory('supplier');
        if (empty($items)) {
            return ['Petroperú Pisco', 'Repsol Grifo Pisco', 'Primax Estación Municipal', 'Grifo Central Pisco'];
        }
        return array_column($items, 'name');
    }

    public function create(string $category, string $name): bool
    {
        self::ensureTableExists();
        $name = trim($name);
        if ($name === '') return false;

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
