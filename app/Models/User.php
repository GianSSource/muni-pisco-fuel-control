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

    private static bool $schemaMigrated = false;

    private static function ensureSchema(\PDO $db): void
    {
        if (self::$schemaMigrated) {
            return;
        }
        try {
            $db->exec('ALTER TABLE users ADD COLUMN IF NOT EXISTS is_active TINYINT(1) NOT NULL DEFAULT 1');
            $db->exec('ALTER TABLE users ADD COLUMN IF NOT EXISTS last_login_at DATETIME NULL DEFAULT NULL');
        } catch (\Throwable $e) {
            try { $db->exec('ALTER TABLE users ADD COLUMN is_active TINYINT(1) NOT NULL DEFAULT 1'); } catch (\Throwable $ignore) {}
            try { $db->exec('ALTER TABLE users ADD COLUMN last_login_at DATETIME NULL DEFAULT NULL'); } catch (\Throwable $ignore) {}
        }
        self::$schemaMigrated = true;
    }

    public function getAll(): array
    {
        try {
            $db = Database::getConnection();
            self::ensureSchema($db);

            $stmt = $db->query(
                'SELECT id, name, email,
                        COALESCE(role, "operador") AS role,
                        COALESCE(is_active, 1) AS is_active,
                        last_login_at,
                        created_at
                 FROM users
                 ORDER BY created_at DESC'
            );
            return $stmt->fetchAll();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function recordLogin(int $id): void
    {
        try {
            $db = Database::getConnection();
            self::ensureSchema($db);
            $db->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')
               ->execute(['id' => $id]);
        } catch (\Throwable $e) {}
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

    public function findWithPassword(int|string $idOrEmail): ?array
    {
        try {
            $db = Database::getConnection();
            if (is_int($idOrEmail) || ctype_digit((string) $idOrEmail)) {
                $stmt = $db->prepare('SELECT id, name, email, password_hash, COALESCE(role, "operador") AS role, COALESCE(is_active, 1) AS is_active FROM users WHERE id = :id LIMIT 1');
                $stmt->execute(['id' => (int) $idOrEmail]);
            } else {
                $stmt = $db->prepare('SELECT id, name, email, password_hash, COALESCE(role, "operador") AS role, COALESCE(is_active, 1) AS is_active FROM users WHERE email = :email LIMIT 1');
                $stmt->execute(['email' => trim((string) $idOrEmail)]);
            }
            $row = $stmt->fetch();
            if ($row) {
                return $row;
            }
        } catch (\Throwable $e) {}

        try {
            $db = Database::getConnection();
            if (is_int($idOrEmail) || ctype_digit((string) $idOrEmail)) {
                $stmt = $db->prepare('SELECT id, nombre AS name, email, password_hash, COALESCE(rol, "operador") AS role, 1 AS is_active FROM usuarios WHERE id = :id LIMIT 1');
                $stmt->execute(['id' => (int) $idOrEmail]);
            } else {
                $stmt = $db->prepare('SELECT id, nombre AS name, email, password_hash, COALESCE(rol, "operador") AS role, 1 AS is_active FROM usuarios WHERE email = :email LIMIT 1');
                $stmt->execute(['email' => trim((string) $idOrEmail)]);
            }
            $row = $stmt->fetch();
            return $row ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function updateUser(int $id, string $name, string $email, string $role): bool
    {
        $validRoles = ['admin', 'supervisor', 'operador'];
        if (!in_array($role, $validRoles, true)) {
            return false;
        }
        try {
            $stmt = Database::getConnection()->prepare(
                'UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id'
            );
            return $stmt->execute(['name' => $name, 'email' => $email, 'role' => $role, 'id' => $id]);
        } catch (Throwable $e) {
            return false;
        }
    }
}
