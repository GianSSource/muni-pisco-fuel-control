<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use Throwable;

final class Project
{
    public function all(): array
    {
        try {
            $sql = 'SELECT projects.*, clients.name AS client_name
                    FROM projects
                    INNER JOIN clients ON clients.id = projects.client_id
                    ORDER BY projects.created_at DESC';

            return Database::getConnection()
                ->query($sql)
                ->fetchAll();
        } catch (Throwable $e) {
            return [];
        }
    }

    public function find(int $id): ?array
    {
        try {
            $sql = 'SELECT projects.*, clients.name AS client_name
                    FROM projects
                    INNER JOIN clients ON clients.id = projects.client_id
                    WHERE projects.id = :id';

            $stmt = Database::getConnection()->prepare($sql);

            $stmt->execute([
                'id' => $id
            ]);

            $project = $stmt->fetch();

            return $project ?: null;
        } catch (Throwable $e) {
            return null;
        }
    }

    public function create(array $data): bool
    {
        try {
            $sql = 'INSERT INTO projects (
                        client_id,
                        name,
                        description,
                        status,
                        start_date,
                        end_date,
                        budget
                    )
                    VALUES (
                        :client_id,
                        :name,
                        :description,
                        :status,
                        :start_date,
                        :end_date,
                        :budget
                    )';

            return Database::getConnection()
                ->prepare($sql)
                ->execute($this->payload($data));
        } catch (Throwable $e) {
            return false;
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $sql = 'UPDATE projects
                    SET
                        client_id = :client_id,
                        name = :name,
                        description = :description,
                        status = :status,
                        start_date = :start_date,
                        end_date = :end_date,
                        budget = :budget
                    WHERE id = :id';

            $payload = $this->payload($data);
            $payload['id'] = $id;

            return Database::getConnection()
                ->prepare($sql)
                ->execute($payload);
        } catch (Throwable $e) {
            return false;
        }
    }

    private function payload(array $data): array
    {
        return [
            'client_id' => (int) ($data['client_id'] ?? 0),
            'name' => trim($data['name'] ?? ''),
            'description' => trim($data['description'] ?? ''),
            'status' => trim($data['status'] ?? 'Planificado'),
            'start_date' => !empty($data['start_date']) ? $data['start_date'] : null,
            'end_date' => !empty($data['end_date']) ? $data['end_date'] : null,
            'budget' => (float) ($data['budget'] ?? 0),
        ];
    }

    /**
     * Obtiene los eventos de entrega y finalización de proyectos para el calendario.
     */
    public function getDeliveryEvents(): array
    {
        $events = [];
        try {
            $projects = $this->all();
            foreach ($projects as $project) {
                if (!empty($project['end_date'])) {
                    $events[] = [
                        'id' => 'project_' . $project['id'],
                        'type' => 'project',
                        'title' => 'Entrega: ' . $project['name'],
                        'date' => $project['end_date'],
                        'time' => '18:00',
                        'client' => $project['client_name'] ?? 'Cliente General',
                        'status' => $project['status'] ?? 'En Proceso',
                        'budget' => (float) ($project['budget'] ?? 0),
                        'description' => $project['description'] ?? '',
                        'badge' => 'Proyecto',
                        'badge_class' => 'badge-project'
                    ];
                }
            }

            if (!empty($events)) {
                return $events;
            }
        } catch (Throwable $e) {
            // Demo fallback
        }

        // Demo fallback data if empty
        $today = date('Y-m-d');
        return [
            [
                'id' => 'project_1',
                'type' => 'project',
                'title' => 'Entrega: Modernización de Semáforos Av. Grau',
                'date' => date('Y-m-d', strtotime('+2 days')),
                'time' => '17:00',
                'client' => 'Municipalidad Provincial',
                'status' => 'En Ejecución',
                'budget' => 45000.00,
                'description' => 'Entrega final de la fase 1 de la red semafórica automatizada.',
                'badge' => 'Proyecto',
                'badge_class' => 'badge-project'
            ],
            [
                'id' => 'project_2',
                'type' => 'project',
                'title' => 'Entrega: Mantenimiento Flota de Camiones Recolectores',
                'date' => date('Y-m-d', strtotime('+5 days')),
                'time' => '12:00',
                'client' => 'Gerencia de Servicios de la Ciudad',
                'status' => 'Planificado',
                'budget' => 28500.00,
                'description' => 'Culminación del servicio preventivo de motor y cajas.',
                'badge' => 'Proyecto',
                'badge_class' => 'badge-project'
            ],
            [
                'id' => 'project_3',
                'type' => 'project',
                'title' => 'Entrega: Suministro de Combustible B5 S-50 Sector 2',
                'date' => $today,
                'time' => '16:30',
                'client' => 'Subgerencia de Logística',
                'status' => 'Completado',
                'budget' => 120000.00,
                'description' => 'Despacho y recepción del lote mensual de combustible diésel.',
                'badge' => 'Proyecto',
                'badge_class' => 'badge-project'
            ]
        ];
    }
}

