<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            DB_HOST,
            DB_NAME,
            DB_CHARSET
        );

        try {

            self::$connection = new PDO(
                $dsn,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

        } catch (PDOException $exception) {

            error_log('Error de conexión a la base de datos: ' . $exception->getMessage());

            http_response_code(500);

            exit('No se pudo conectar con la base de datos. Intenta nuevamente más tarde.');

        }

        return self::$connection;
    }

    /**
     * Genera un correlativo atómico para $name (p. ej. "VAL-2026" o
     * "REC-ING") usando una tabla contador dedicada, en vez de
     * "SELECT último número + 1". Ese patrón anterior fallaba cuando
     * todavía no existía ninguna fila con ese prefijo: el SELECT ...
     * FOR UPDATE no encuentra nada que bloquear, así que dos
     * transacciones simultáneas podían calcular el mismo "1" y una de
     * las dos terminaba fallando por la restricción UNIQUE.
     *
     * El truco de "INSERT ... ON DUPLICATE KEY UPDATE value =
     * LAST_INSERT_ID(value + 1)" es atómico en MySQL/MariaDB incluso
     * para la PRIMERA fila (no depende de que exista una fila previa
     * que bloquear) y LAST_INSERT_ID() devuelve el valor ya
     * incrementado, listo para usar.
     *
     * Crea la tabla sequence_counters si no existe. IMPORTANTE: se debe
     * llamar SIEMPRE antes de $db->beginTransaction(), nunca dentro de una
     * transacción ya abierta. CREATE TABLE es una sentencia DDL y en
     * MySQL/MariaDB toda DDL provoca un COMMIT implícito de la transacción
     * en curso (aunque la tabla ya exista y el IF NOT EXISTS no haga nada).
     * Si esto se ejecuta después de beginTransaction(), la transacción
     * queda cerrada sin que PHP se entere: los INSERT posteriores se
     * guardan igual (en autocommit), pero el $db->commit() final falla con
     * "There is no active transaction" y el flujo termina en el catch,
     * mostrando un error aunque el registro sí se haya guardado.
     */
    public static function ensureSequenceTable(PDO $db): void
    {
        try {
            $db->exec('CREATE TABLE IF NOT EXISTS sequence_counters (
                name VARCHAR(60) NOT NULL PRIMARY KEY,
                value BIGINT UNSIGNED NOT NULL DEFAULT 0
            ) ENGINE=InnoDB');
        } catch (PDOException $e) {
        }
    }

    /**
     * Genera el siguiente correlativo para $name. No ejecuta DDL: la tabla
     * debe existir ya (ver ensureSequenceTable(), llamado antes de abrir
     * cualquier transacción).
     */
    public static function nextSequence(PDO $db, string $name): int
    {
        $stmt = $db->prepare(
            'INSERT INTO sequence_counters (name, value) VALUES (:name, LAST_INSERT_ID(1))
             ON DUPLICATE KEY UPDATE value = LAST_INSERT_ID(value + 1)'
        );
        $stmt->execute(['name' => $name]);

        return (int) $db->lastInsertId();
    }
}
