CREATE DATABASE IF NOT EXISTS tecnosoluciones
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE tecnosoluciones;

-- =========================================================
-- USERS
-- =========================================================
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'supervisor', 'operador') NOT NULL DEFAULT 'operador',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Drop legacy tables if exist
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS recibos;
DROP TABLE IF EXISTS ingresos_combustible;
DROP TABLE IF EXISTS saldos_combustible;

-- =========================================================
-- SYSTEM_CATALOGS (Tipos de Combustible, Áreas y Proveedores)
-- =========================================================
CREATE TABLE IF NOT EXISTS system_catalogs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category ENUM('fuel_type', 'area', 'supplier') NOT NULL,
    name VARCHAR(160) NOT NULL,
    status ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_catalogs_cat (category, status)
) ENGINE=InnoDB;

-- Datos iniciales del catálogo
INSERT IGNORE INTO system_catalogs (id, category, name) VALUES
(1, 'fuel_type', 'Diesel B5 S-50'),
(2, 'fuel_type', 'Gasohol Regular'),
(3, 'fuel_type', 'Gasohol Premium'),
(4, 'fuel_type', 'GLP (Gas Licuado)'),
(5, 'area', 'Serenazgo y Seguridad Ciudadana'),
(6, 'area', 'Limpieza Pública y Recolección'),
(7, 'area', 'Obras Públicas e Infraestructura'),
(8, 'area', 'Parques y Jardines'),
(9, 'area', 'Maquinaria y Equipo Pesado'),
(10, 'area', 'Fiscalización y Transporte'),
(11, 'area', 'Gerencia Municipal'),
(12, 'supplier', 'Petroperú Pisco'),
(13, 'supplier', 'Repsol Grifo Pisco'),
(14, 'supplier', 'Primax Estación Municipal'),
(15, 'supplier', 'Grifo Central Pisco');

-- =========================================================
-- FUEL_INCOMES (ingresos de combustible)
-- receipt_number ahora es UNIQUE para poder ser referenciado
-- =========================================================
CREATE TABLE IF NOT EXISTS fuel_incomes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    supplier VARCHAR(160) NOT NULL,
    fuel_type VARCHAR(80) NOT NULL DEFAULT 'Diesel B5 S-50',
    gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
    gallons_remaining DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    receipt_number VARCHAR(80) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_incomes_users
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT chk_incomes_gallons CHECK (gallons > 0),
    CONSTRAINT chk_incomes_price CHECK (unit_price >= 0),
    CONSTRAINT chk_incomes_remaining CHECK (gallons_remaining >= 0 AND gallons_remaining <= gallons),

    INDEX idx_incomes_supplier (supplier),
    INDEX idx_incomes_user (user_id)
) ENGINE=InnoDB;

-- =========================================================
-- FUEL_DELIVERIES (vales/entregas de combustible)
-- Ahora referencia a fuel_incomes mediante fuel_income_id (FK real).
-- receipt_number se mantiene como el número propio del vale
-- (es un documento distinto al recibo de ingreso).
-- =========================================================
CREATE TABLE IF NOT EXISTS fuel_deliveries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fuel_income_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NULL,
    receipt_number VARCHAR(80) NOT NULL UNIQUE,
    vehicle_plate VARCHAR(40) NOT NULL,
    driver_name VARCHAR(120) NOT NULL,
    gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
    destination VARCHAR(160) NULL,
    status ENUM('Pendiente','Completado','Anulado') NOT NULL DEFAULT 'Completado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_deliveries_incomes
        FOREIGN KEY (fuel_income_id)
        REFERENCES fuel_incomes(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_deliveries_users
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT chk_deliveries_gallons CHECK (gallons > 0),

    INDEX idx_deliveries_income (fuel_income_id),
    INDEX idx_deliveries_plate (vehicle_plate),
    INDEX idx_deliveries_status (status),
    INDEX idx_deliveries_user (user_id)
) ENGINE=InnoDB;

-- =========================================================
-- TRIGGERS: control automático de saldo de combustible
-- gallons_remaining se inicializa solo, y se ajusta cada vez
-- que se crea, edita o anula un vale (fuel_deliveries).
-- Un vale nunca puede superar el saldo disponible del lote.
-- =========================================================
DELIMITER $$

-- Al crear un ingreso, el saldo disponible arranca igual al total
CREATE TRIGGER trg_incomes_before_insert
BEFORE INSERT ON fuel_incomes
FOR EACH ROW
BEGIN
    SET NEW.gallons_remaining = NEW.gallons;
END$$

-- Al crear un vale: valida saldo y lo descuenta del lote
CREATE TRIGGER trg_deliveries_before_insert
BEFORE INSERT ON fuel_deliveries
FOR EACH ROW
BEGIN
    DECLARE available DECIMAL(10,2);
    SELECT gallons_remaining INTO available
        FROM fuel_incomes WHERE id = NEW.fuel_income_id FOR UPDATE;

    IF NEW.status <> 'Anulado' AND available < NEW.gallons THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'No hay suficiente combustible disponible en ese ingreso';
    END IF;
END$$

CREATE TRIGGER trg_deliveries_after_insert
AFTER INSERT ON fuel_deliveries
FOR EACH ROW
BEGIN
    IF NEW.status <> 'Anulado' THEN
        UPDATE fuel_incomes
        SET gallons_remaining = gallons_remaining - NEW.gallons
        WHERE id = NEW.fuel_income_id;
    END IF;
END$$

-- Al editar un vale (cambia cantidad, lote o se anula/reactiva):
-- revierte el efecto anterior y aplica el nuevo
CREATE TRIGGER trg_deliveries_before_update
BEFORE UPDATE ON fuel_deliveries
FOR EACH ROW
BEGIN
    DECLARE available DECIMAL(10,2);
    IF NEW.status <> 'Anulado'
       AND (NEW.fuel_income_id <> OLD.fuel_income_id OR NEW.gallons <> OLD.gallons OR OLD.status = 'Anulado') THEN
        SELECT gallons_remaining INTO available
            FROM fuel_incomes WHERE id = NEW.fuel_income_id FOR UPDATE;
        IF NEW.fuel_income_id = OLD.fuel_income_id AND OLD.status <> 'Anulado' THEN
            SET available = available + OLD.gallons;
        END IF;
        IF available < NEW.gallons THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No hay suficiente combustible disponible en ese ingreso';
        END IF;
    END IF;
END$$

CREATE TRIGGER trg_deliveries_after_update
AFTER UPDATE ON fuel_deliveries
FOR EACH ROW
BEGIN
    -- revierte el descuento anterior si correspondía
    IF OLD.status <> 'Anulado' THEN
        UPDATE fuel_incomes SET gallons_remaining = gallons_remaining + OLD.gallons
        WHERE id = OLD.fuel_income_id;
    END IF;
    -- aplica el descuento nuevo si corresponde
    IF NEW.status <> 'Anulado' THEN
        UPDATE fuel_incomes SET gallons_remaining = gallons_remaining - NEW.gallons
        WHERE id = NEW.fuel_income_id;
    END IF;
END$$

-- Al eliminar un vale, devuelve el saldo al lote
CREATE TRIGGER trg_deliveries_after_delete
AFTER DELETE ON fuel_deliveries
FOR EACH ROW
BEGIN
    IF OLD.status <> 'Anulado' THEN
        UPDATE fuel_incomes SET gallons_remaining = gallons_remaining + OLD.gallons
        WHERE id = OLD.fuel_income_id;
    END IF;
END$$

DELIMITER ;

-- =========================================================
-- Datos de demostración
-- =========================================================
INSERT INTO users (name, email, password_hash, role, created_at) VALUES
('Admin Sistema', 'admin@tecnosoluciones.com', '$2y$10$e.wPZfJ2H9F9z2C.vB7Vd.o8G6Z6n5B1fW9.9X9X9X9X9X9X9X9X', 'admin', NOW() - INTERVAL 30 DAY);

-- gallons_remaining se calcula solo por el trigger BEFORE INSERT
INSERT INTO fuel_incomes (user_id, supplier, gallons, unit_price, receipt_number, created_at) VALUES
(1, 'PetroPerú S.A.', 5000.00, 16.50, 'REC-ING-1001', NOW() - INTERVAL 10 DAY),
(1, 'Primax Corp', 3500.00, 16.80, 'REC-ING-1002', NOW() - INTERVAL 2 DAY);

-- gallons_remaining de cada ingreso se descuenta solo por los triggers AFTER INSERT
INSERT INTO fuel_deliveries (fuel_income_id, user_id, receipt_number, vehicle_plate, driver_name, gallons, destination, status, created_at) VALUES
(1, 1, 'VAL-2026-001', 'EAB-123', 'Carlos Mendoza', 120.50, 'Cantera Norte', 'Completado', NOW() - INTERVAL 4 HOUR),
(1, 1, 'VAL-2026-002', 'FXY-456', 'Juan Pérez', 85.00, 'Obra Central', 'Completado', NOW() - INTERVAL 2 HOUR),
(1, 1, 'VAL-2026-003', 'GHJ-789', 'Miguel Ángel Torres', 200.00, 'Ruta Sur', 'Completado', NOW() - INTERVAL 30 MINUTE),
(2, 1, 'VAL-2026-004', 'EAB-123', 'Carlos Mendoza', 95.00, 'Almacén 2', 'Completado', NOW() - INTERVAL 1 DAY),
(2, 1, 'VAL-2026-005', 'KML-901', 'Roberto Silva', 150.00, 'Planta Principal', 'Completado', NOW() - INTERVAL 3 DAY);