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
        $this->projects->create($_POST);
        $this->redirect('proyectos');
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