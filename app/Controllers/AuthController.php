<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

final class AuthController extends Controller 
{
    // Tras este número de intentos fallidos seguidos, se bloquea el login
    // por un tiempo. Se lleva la cuenta por IP en base de datos (tabla
    // login_attempts), NO en la sesión: un contador en $_SESSION se
    // reinicia con solo borrar la cookie o abrir una ventana de
    // incógnito, lo que lo vuelve inútil contra fuerza bruta real.
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 60;

    public function showLogin(): void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('dashboard');
        }

        $this->view('auth/login', ['title' => 'Acceso', 'csrfToken' => $this->csrfToken()]);
    }

    private function ensureAttemptsTable(\PDO $db): void
    {
        try {
            $db->exec('CREATE TABLE IF NOT EXISTS login_attempts (
                identifier VARCHAR(191) NOT NULL PRIMARY KEY,
                attempts INT UNSIGNED NOT NULL DEFAULT 0,
                locked_until DATETIME NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB');
        } catch (\Throwable $e) {
        }
    }

    // Identificador del origen del intento. Se usa la IP (no el email)
    // para que el bloqueo también frene ataques que prueban muchos
    // correos distintos desde el mismo origen.
    private function loginIdentifier(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
    }

    /**
     * Devuelve los segundos restantes de bloqueo, o null si no está bloqueado.
     */
    private function isLockedOut(): ?int
    {
        try {
            $db = \App\Core\Database::getConnection();
            $this->ensureAttemptsTable($db);

            $stmt = $db->prepare('SELECT locked_until FROM login_attempts WHERE identifier = :id');
            $stmt->execute(['id' => $this->loginIdentifier()]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row && $row['locked_until'] !== null) {
                $remaining = strtotime($row['locked_until']) - time();
                if ($remaining > 0) {
                    return $remaining;
                }
            }
        } catch (\Throwable $e) {
        }

        return null;
    }

    private function registerFailedAttempt(): void
    {
        try {
            $db = \App\Core\Database::getConnection();
            $this->ensureAttemptsTable($db);
            $id = $this->loginIdentifier();

            $db->beginTransaction();

            // FOR UPDATE bloquea la fila de esta IP mientras se calcula el
            // nuevo conteo, para que dos intentos fallidos simultáneos de
            // la misma IP no se pisen y ambos cuenten como "intento 1".
            $stmt = $db->prepare('SELECT attempts FROM login_attempts WHERE identifier = :id FOR UPDATE');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            $attempts = (int) ($row['attempts'] ?? 0) + 1;
            $lockedUntil = null;
            if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
                $lockedUntil = date('Y-m-d H:i:s', time() + self::LOCKOUT_SECONDS);
                $attempts = 0;
            }

            $db->prepare(
                'INSERT INTO login_attempts (identifier, attempts, locked_until)
                 VALUES (:id, :attempts, :locked_until)
                 ON DUPLICATE KEY UPDATE attempts = VALUES(attempts), locked_until = VALUES(locked_until)'
            )->execute([
                'id' => $id,
                'attempts' => $attempts,
                'locked_until' => $lockedUntil,
            ]);

            $db->commit();
        } catch (\Throwable $e) {
            if (isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
        }
    }

    private function clearLoginAttempts(): void
    {
        try {
            $db = \App\Core\Database::getConnection();
            $this->ensureAttemptsTable($db);
            $db->prepare('DELETE FROM login_attempts WHERE identifier = :id')
               ->execute(['id' => $this->loginIdentifier()]);
        } catch (\Throwable $e) {
        }
    }

    public function login():void
    {
        $this->verifyCsrf();

        $waitSeconds = $this->isLockedOut();
        if ($waitSeconds !== null) {
            $this->view('auth/login', [
                'title' => 'Acceso',
                'csrfToken' => $this->csrfToken(),
                'error' => 'Demasiados intentos fallidos. Intenta nuevamente en ' . max(1, $waitSeconds) . ' segundos.',
            ]);
            return;
        }

        $user = (new User())->findByEmail(trim($_POST['email']??''));

        if (!$user || !password_verify($_POST['password']??'',$user['password_hash'])) {
            $this->registerFailedAttempt();
            $this->view('auth/login',[
                'title' => 'Acceso',
                'csrfToken' =>$this->csrfToken(),
                'error' => 'Credenciales invalidas.',
            ]);
            return;
        }

        $this->clearLoginAttempts();
        session_regenerate_id(true);
        // Si por algún motivo el rol no viene definido, se asume el de MENOR
        // privilegio (operador). Jamás se debe asumir 'admin' por defecto.
        $role = $user['role'] ?? 'operador';
        $_SESSION['user'] = [
            'id' => $user['id'], 
            'name' => $user['name'], 
            'email' => $user['email'],
            'role' => $role
        ];
        $this->redirect('dashboard');
    }
    
    public function showRegister():void
    {
        if (!empty($_SESSION['user'])) {
            $this->redirect('dashboard');
        }

        $this->view('auth/register', ['title' => 'Registro','csrfToken' => $this->csrfToken()]);
    }
    public function register(): void
    {
        $this->verifyCsrf();
        $name = trim((string) ($_POST['name'] ?? ''));
        $name = preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/u', '', $name);
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        // El registro público siempre crea cuentas 'operador'.
        // El rol enviado por el formulario se ignora a propósito: permitir que
        // el propio usuario elija 'admin' u 'supervisor' aquí es una escalada
        // de privilegios. Subir de rol a un usuario debe hacerse desde una
        // pantalla protegida con requireAdmin().
        $role = 'operador';

        if (mb_strlen($name) > 50 || mb_strlen($email) > 60 || mb_strlen($password) > 50) {
            $this->view('auth/register', [
                'title' => 'Registro',
                'csrfToken' => $this->csrfToken(),
                'error' => 'Los campos superan la longitud máxima permitida (Nombre max 50, Email max 60, Clave max 50).'
            ]);
            return;
        }

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            $this->view('auth/register', [
                'title' => 'Registro',
                'csrfToken' => $this->csrfToken(),
                'error' => 'Complete los datos correctamente. La clave debe tener al menos 8 caracteres.'
            ]);
            return;
        }

        if ((new User())->findByEmail($email) !== null) {
            $this->view('auth/register', [
                'title' => 'Registro',
                'csrfToken' => $this->csrfToken(),
                'error' => 'Ese correo ya está registrado. Intenta iniciar sesión.'
            ]);
            return;
        }

        $created = (new User())->create($name, $email, $password, $role);

        if (!$created) {
            $this->view('auth/register', [
                'title' => 'Registro',
                'csrfToken' => $this->csrfToken(),
                'error' => 'No se pudo crear la cuenta. Intenta nuevamente.'
            ]);
            return;
        }

        $this->redirect('login');
    }
    public function logout(): never
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $cookie = session_get_cookie_params();
            // Forma posicional compatible con versiones de PHP que no aceptan
            // el arreglo de opciones como cuarto parámetro de setcookie().
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $cookie['path'] ?? '/',
                $cookie['domain'] ?? '',
                (bool) ($cookie['secure'] ?? false),
                (bool) ($cookie['httponly'] ?? true)
            );
        }

        session_destroy();
        $this->redirect('login');
    }
}