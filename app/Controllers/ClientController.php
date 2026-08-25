<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Client;

final class ClientController extends Controller
{
    private Client $clients;

    public function __construct()
    {
        $this->clients = new Client();
    }

    public function index(): void
    {
        $this->requireAuth();
        $this->view('Clients/index', ['title' => 'Clientes', 'clients' => $this->clients->all()]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->view('Clients/form', ['title' => 'Nuevo cliente', 'client' => null, 'csrfToken' => $this->csrfToken()]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->clients->create($_POST);
        $this->redirect('clientes');
    }

    public function edit(): void
    {
        $this->requireAuth();
        $client = $this->clients->find((int) ($_GET['id'] ?? 0));
        if (!$client) {
            http_response_code(404);
            exit('Cliente no encontrado');
        }
        $this->view('Clients/form', ['title' => 'Editar Cliente', 'client' => $client, 'csrfToken' => $this->csrfToken()]);
    }

    public function update(): void
    {
        $this->requireAuth();
        $this->verifyCsrf();
        $this->clients->update((int) ($_POST['id'] ?? 0), $_POST);
        $this->redirect('clientes');
    }
}