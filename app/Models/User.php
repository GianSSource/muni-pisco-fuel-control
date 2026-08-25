<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use Throwable;

final class User
{
    public function create(string $name, string $email, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Intentar en la tabla 'usuarios' (Base de datos control_combustible)
            $sql = 'INSERT INTO usuarios (nombre, email, password_hash, rol, activo) VALUES (:name, :email, :password_hash, "operador", 1)';
            $stmt = Database::getConnection()->prepare($sql);
            return $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password_hash' => $hash,
            ]);
        } catch (Throwable $e) {
            // Fallback a la tabla 'users'
            $sql = 'INSERT INTO users (name, email, password_hash) VALUES (:name, :email, :password_hash)';
            $stmt = Database::getConnection()->prepare($sql);
            return $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password_hash' => $hash,
            ]);
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            // Intentar en la tabla 'usuarios'
            $stmt = Database::getConnection()->prepare('SELECT id, nombre AS name, email, password_hash FROM usuarios WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user) {
                return $user;
            }
        } catch (Throwable $e) {
            // Fallback a 'users'
        }

        try {
            $stmt = Database::getConnection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            return $user ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }
}