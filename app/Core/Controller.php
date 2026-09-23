<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        // Las pantallas de autenticación no deben heredar la navegación de una
        // sesión existente o que esté siendo cerrada.
        $isPublicPage = str_starts_with($view, 'auth/');
        extract($data, EXTR_SKIP);

        $viewPath = dirname(__DIR__) . '/Views/' . $view . '.php';

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require $viewPath;
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . BASE_URL . ltrim($path, '/'));
        exit;
    }

    // Tiempo máximo de inactividad permitido (en segundos). Tras este tiempo
    // sin interacción, la sesión se cierra automáticamente para proteger
    // estaciones de trabajo desatendidas.
    private const SESSION_TIMEOUT = 3600; // 60 minutos

    protected function requireAuth(): void
    {
        if (empty($_SESSION['user'])) {
            $this->redirect('login');
        }

        // Control de inactividad: si el usuario lleva más de SESSION_TIMEOUT
        // segundos sin realizar ninguna petición, se cierra la sesión.
        $now = time();
        $lastActivity = $_SESSION['last_activity'] ?? $now;

        if (($now - $lastActivity) > self::SESSION_TIMEOUT) {
            // Limpiar sesión de forma segura
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $cookie = session_get_cookie_params();
                setcookie(
                    session_name(), '',
                    time() - 42000,
                    $cookie['path'] ?? '/',
                    $cookie['domain'] ?? '',
                    (bool) ($cookie['secure'] ?? false),
                    (bool) ($cookie['httponly'] ?? true)
                );
            }
            session_destroy();
            $this->redirect('login?motivo=inactividad');
        }

        // Renovar el timestamp en cada petición autenticada exitosa
        $_SESSION['last_activity'] = $now;
    }

    protected function getUserRole(): string
    {
        return strtolower((string) ($_SESSION['user']['role'] ?? $_SESSION['user']['rol'] ?? 'operador'));
    }

    protected function hasRole(string $role): bool
    {
        return $this->getUserRole() === strtolower($role);
    }

    protected function requireRole(array|string $allowedRoles): void
    {
        $this->requireAuth();
        $userRole = $this->getUserRole();
        $roles = is_array($allowedRoles) ? array_map('strtolower', $allowedRoles) : [strtolower($allowedRoles)];

        if (!in_array($userRole, $roles, true) && $userRole !== 'admin') {
            http_response_code(403);
            exit('<div style="text-align:center; padding: 50px; font-family: sans-serif; background: #0f172a; color: #f8fafc; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center;">'
                . '<h1 style="font-size: 3rem; color: #ef4444; margin-bottom: 0.5rem;">403</h1>'
                . '<h2>Acceso Restringido</h2>'
                . '<p style="color: #94a3b8; max-width: 480px;">No posee el rol requerido para acceder a este recurso. Su rol actual es: <strong style="text-transform: uppercase; color: #38bdf8;">' . htmlspecialchars($userRole) . '</strong>.</p>'
                . '<a href="' . BASE_URL . 'dashboard" style="margin-top: 1.5rem; display: inline-block; padding: 10px 20px; background: #3b82f6; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600;">Volver al Dashboard</a>'
                . '</div>');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireRole('admin');
    }

    protected function requireSupervisor(): void
    {
        $this->requireRole(['admin', 'supervisor']);
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
