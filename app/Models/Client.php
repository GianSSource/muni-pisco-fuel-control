<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use Throwable;

final class Client
{
    public function all(): array
    {
        try {
            return Database::getConnection()
                ->query('SELECT * FROM clients ORDER BY created_at DESC')
                ->fetchAll();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function find(int $id): ?array
    {
        try {
            $stmt = Database::getConnection()->prepare('SELECT * FROM clients WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $client = $stmt->fetch();
            return $client ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): bool
    {
        try {
            $sql = 'INSERT INTO clients (name, email, phone, company, address)
                    VALUES (:name, :email, :phone, :company, :address)';

            return Database::getConnection()->prepare($sql)->execute($this->payload($data));
        } catch (Throwable $e) {
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $sql = 'UPDATE clients
                    SET name = :name, email = :email, phone = :phone, company = :company, address = :address
                    WHERE id = :id';

            $payload = $this->payload($data);
            $payload['id'] = $id;

            return Database::getConnection()->prepare($sql)->execute($payload);
        } catch (Throwable $e) {
            return false;
        }
    }

    private function payload(array $data): array
    {
        return [
            'name'    => trim($data['name']    ?? ''),
            'email'   => trim($data['email']   ?? ''),
            'phone'   => trim($data['phone']   ?? ''),
            'company' => trim($data['company'] ?? ''),
            'address' => trim($data['address'] ?? ''),
        ];
    }
}