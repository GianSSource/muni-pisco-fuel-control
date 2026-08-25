CREATE DATABASE IF NOT EXISTS tecnosoluciones
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE tecnosoluciones;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS clients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(160) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    phone VARCHAR(40) NULL,
    company VARCHAR(160) NULL,
    address TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS projects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    client_id INT UNSIGNED NOT NULL,
    name VARCHAR(180) NOT NULL,
    description TEXT NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'Planificado',
    start_date DATE NULL,
    end_date DATE NULL,
    budget DECIMAL(12,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_projects_clients
        FOREIGN KEY (client_id)
        REFERENCES clients(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fuel_incomes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    supplier VARCHAR(160) NOT NULL,
    gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
    unit_price DECIMAL(10,2) NOT NULL DEFAULT 0,
    receipt_number VARCHAR(80) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS fuel_deliveries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    receipt_number VARCHAR(80) NOT NULL,
    vehicle_plate VARCHAR(40) NOT NULL,
    driver_name VARCHAR(120) NOT NULL,
    gallons DECIMAL(10,2) NOT NULL DEFAULT 0,
    destination VARCHAR(160) NULL,
    status VARCHAR(40) NOT NULL DEFAULT 'Completado',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Datos de demostración iniciales
INSERT INTO fuel_incomes (supplier, gallons, unit_price, receipt_number, created_at) VALUES
('PetroPerú S.A.', 5000.00, 16.50, 'REC-ING-1001', NOW() - INTERVAL 10 DAY),
('Primax Corp', 3500.00, 16.80, 'REC-ING-1002', NOW() - INTERVAL 2 DAY);

INSERT INTO fuel_deliveries (receipt_number, vehicle_plate, driver_name, gallons, destination, status, created_at) VALUES
('VAL-2026-001', 'EAB-123', 'Carlos Mendoza', 120.50, 'Cantera Norte', 'Completado', NOW() - INTERVAL 4 HOUR),
('VAL-2026-002', 'FXY-456', 'Juan Pérez', 85.00, 'Obra Central', 'Completado', NOW() - INTERVAL 2 HOUR),
('VAL-2026-003', 'GHJ-789', 'Miguel Ángel Torres', 200.00, 'Ruta Sur', 'Completado', NOW() - INTERVAL 30 MINUTE),
('VAL-2026-004', 'EAB-123', 'Carlos Mendoza', 95.00, 'Almacén 2', 'Completado', NOW() - INTERVAL 1 DAY),
('VAL-2026-005', 'KML-901', 'Roberto Silva', 150.00, 'Planta Principal', 'Completado', NOW() - INTERVAL 3 DAY);