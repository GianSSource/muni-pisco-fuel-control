<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewPath = dirname(__DIR__) . '/views/' . $view . '.php';

        require dirname(__DIR__) . '/views/layouts/header.php';
        require $viewPath;
        require dirname(__DIR__) . '/views/layouts/footer.php';
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit;
    }

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('login');
        }
    }

 
    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    
    protected function verifyCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';

        if (
            empty($_SESSION['csrf_token']) ||
            empty($token) ||
            !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            http_response_code(419);

            $this->view('auth/login', [
                'title' => 'Acceso',
                'csrfToken' => $this->csrfToken(),
                'error' => 'Token de seguridad inválido.'
            ]);

            exit;
        }
    }
}