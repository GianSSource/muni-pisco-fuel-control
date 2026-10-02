<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use Throwable;

final class User
{
    public function create(string $name, string $email, string $password, string $role = 'operador'): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $validRoles = ['admin', 'supervisor', 'operador'];
        if (!in_array($role, $validRoles, true)) {
            $role = 'operador';
        }

        try {
            $db = Database::getConnection();

            // Auto-creaciÃ³n de la tabla 'users' si no existiera en la base de datos activa
            $db->exec('CREATE TABLE IF NOT EXISTS users (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(120) NOT NULL,
                email VARCHAR(160) NOT NULL UNIQUE,
                password_hash VARCHAR(255) NOT NULL,
                role VARCHAR(40) NOT NULL DEFAULT "operador",
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB');

            // Agrega la columna is_active si la tabla ya existe sin ella
            try {
                $db->exec('ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1');
            } catch (\Throwable $ignore) {}

            $sql = 'INSERT INTO users (name, email, password_hash, role) VALUES (:name, :email, :password_hash, :role)';
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password_hash' => $hash,
                'role' => $role,
            ]);
        } catch (Throwable $e1) {
            // Intentar inserciÃ³n en users sin columna role si fallara por esquema antiguo
            try {
                $db = Database::getConnection();
                $sql = 'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)';
                $stmt = $db->prepare($sql);
                return $stmt->execute([
                    'name' => $name,
                    'email' => $email,
                    'password_hash' => $hash,
                ]);
            } catch (Throwable $e2) {
                // Fallback secundario seguro a tabla 'usuarios'
                try {
                    $db = Database::getConnection();
                    $sql = 'INSERT INTO usuarios (nombre, email, password_hash, rol, activo) VALUES (:name, :email, :password_hash, :role, 1)';
                    $stmt = $db->prepare($sql);
                    return $stmt->execute([
                        'name' => $name,
                        'email' => $email,
                        'password_hash' => $hash,
                        'role' => $role,
                    ]);
                } catch (Throwable $e3) {
                    return false;
                }
            }
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('SELECT id, name, email, password_hash, COALESCE(role, "operador") AS role, COALESCE(is_active, 1) AS is_active FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                return $user;
            }
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            // Fallback
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('SELECT id, nombre AS name, email, password_hash, COALESCE(rol, "operador") AS role FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            return $user ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function updateRole(int $userId, string $role): bool
    {
        $validRoles = ['admin', 'supervisor', 'operador'];
        if (!in_array($role, $validRoles, true)) {
            return false;
        }

        try {
            $stmt = Database::getConnection()->prepare('UPDATE users SET role = :role WHERE id = :id');
            return $stmt->execute(['role' => $role, 'id' => $userId]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function getAll(): array
    {
        try {
            $db = Database::getConnection();

            // Garantiza que la columna is_active exista (migración automática)
            try {
                $db->exec('ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1');
            } catch (\Throwable $ignore) {}

            $stmt = $db->query('SELECT id, name, email, COALESCE(role, "operador") AS role, COALESCE(is_active, 1) AS is_active, created_at FROM users ORDER BY created_at DESC');
            return $stmt->fetchAll();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = Database::getConnection()->prepare('DELETE FROM users WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (Throwable $e) {
            try {
                $stmt = Database::getConnection()->prepare('DELETE FROM usuarios WHERE id = :id');
                return $stmt->execute(['id' => $id]);
            } catch (Throwable $e2) {
                return false;
            }
        }
    }

    public function toggleStatus(int $id): bool
    {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare('UPDATE users SET is_active = 1 - COALESCE(is_active, 1) WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (Throwable $e) {
            return false;
        }
    }

    public function findById(int $id): ?array
    {
        try {
            $stmt = Database::getConnection()->prepare(
                'SELECT id, name, email, COALESCE(role, "operador") AS role, COALESCE(is_active, 1) AS is_active FROM users WHERE id = :id LIMIT 1'
            );
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            return $row ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }
}
