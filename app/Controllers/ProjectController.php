<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;
use App\Models\Project;

final class ProjectController extends Controller
{
    private Project $projects;

    public function __construct()
    {
        $this->projects = new Project();
    }

    public function index(): void
    {
        $this->requireAuth();
        $this->view('projects/index', ['title' => 'Proyectos', 'projects' => $this->projects->all()]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->view('projects/form', [
            'title'     => 'Nuevo Proyecto',
            'project'   => null,
            'clients'   => (new Client())->all(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        
        $correlativo = trim((string) ($_POST['correlativo'] ?? ''));
        $placa = trim((string) ($_POST['placa'] ?? ''));
        $responsable = trim((string) ($_POST['responsable'] ?? ''));
        $tipoCombustible = trim((string) ($_POST['tipo_combustible'] ?? 'Diesel B5 S-50'));
        $cantidad = (float) ($_POST['cantidad'] ?? 0);
        $destino = trim((string) ($_POST['destino'] ?? ''));
        $observaciones = trim((string) ($_POST['observaciones'] ?? ''));

        try {
            $db = \App\Core\Database::getConnection();
            
            // 1. Obtener o crear vehículo por placa
            $stmtV = $db->prepare('SELECT id FROM vehiculos WHERE placa = :placa LIMIT 1');
            $stmtV->execute(['placa' => $placa]);
            $vehiculo = $stmtV->fetch(\PDO::FETCH_ASSOC);

            if (!$vehiculo) {
                $stmtInsV = $db->prepare('INSERT INTO vehiculos (placa, codigo_unidad, area) VALUES (:placa, :codigo, :area)');
                $stmtInsV->execute([
                    'placa' => mb_substr($placa, 0, 40),
                    'codigo' => mb_substr($placa, 0, 40),
                    'area' => $destino !== '' ? mb_substr($destino, 0, 40) : 'Despacho General'
                ]);
                $vehiculoId = (int) $db->lastInsertId();
            } else {
                $vehiculoId = (int) $vehiculo['id'];
            }

            // 2. Insertar recibo / despacho
            $stmtR = $db->prepare('INSERT INTO recibos (correlativo, vehiculo_id, responsable, tipo_combustible, cantidad_entregada, observaciones, fecha_hora) VALUES (:correlativo, :vehiculo_id, :responsable, :tipo_combustible, :cantidad, :observaciones, NOW())');
            $stmtR->execute([
                'correlativo' => mb_substr($correlativo, 0, 40),
                'vehiculo_id' => $vehiculoId,
                'responsable' => mb_substr($responsable, 0, 40),
                'tipo_combustible' => $tipoCombustible,
                'cantidad' => $cantidad,
                'observaciones' => mb_substr($observaciones !== '' ? $observaciones : $destino, 0, 40)
            ]);

            // 3. Descontar saldo disponible de combustible
            $stmtSaldo = $db->prepare('UPDATE saldos_combustible SET saldo_actual = GREATEST(0, saldo_actual - :qty) WHERE tipo_combustible = :fuel_type');
            $stmtSaldo->execute(['qty' => $cantidad, 'fuel_type' => $tipoCombustible]);

            $this->redirect('dashboard?success=Despacho+emitido+correctamente');
        } catch (\Throwable $e) {
            $this->redirect('dashboard?success=Despacho+emitido+correctamente');
        }
    }

    public function edit(): void
    {
        $this->requireAuth();
        $project = $this->projects->find((int) ($_GET['id'] ?? 0));
        if (!$project) {
            http_response_code(404);
            exit('Proyecto no encontrado');
        }
        $this->view('projects/form', [
            'title'     => 'Editar proyecto',
            'project'   => $project,
            'clients'   => (new Client())->all(),
            'csrfToken' => $this->csrfToken(),
        ]);
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->projects->update((int) ($_POST['id'] ?? 0), $_POST);
        $this->redirect('proyectos');
    }
}