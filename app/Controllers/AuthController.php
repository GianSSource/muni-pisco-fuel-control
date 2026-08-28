<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

final class AuthController extends Controller 
{
    public function showLogin(): void
    {
        $this->view('auth/login', ['title' => 'Acceso', 'csrfToken' => $this->csrfToken()]);
    }

    public function login():void
    {
        $this->verifyCsrf();
        $user = (new User())->findByEmail(trim($_POST['email']??''));

        if (!$user || !password_verify($_POST['password']??'',$user['password_hash'])) {
            $this->view('auth/login',[
                'title' => 'Acceso',
                'csrfToken' =>$this->csrfToken(),
                'error' => 'Credenciales invalidas.',
            ]);
            return;
        }

        session_regenerate_id(true);
        $role = $user['role'] ?? 'admin';
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
        $this->view('auth/register', ['title' => 'Registro','csrfToken' => $this->csrfToken()]);
    }
    public function register(): void
    {
        $this->verifyCsrf();
        $name = trim((string) ($_POST['name'] ?? ''));
        $name = preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/u', '', $name);
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $role = trim((string) ($_POST['role'] ?? 'operador'));

        if (mb_strlen($name) > 30 || mb_strlen($email) > 60 || mb_strlen($password) > 50) {
            $this->view('auth/register', [
                'title' => 'Registro',
                'csrfToken' => $this->csrfToken(),
                'error' => 'Los campos superan la longitud máxima permitida (Nombre max 30, Email max 60, Clave max 50).'
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

        (new User())->create($name, $email, $password, $role);
        $this->redirect('login');
    }
    public function logout(): never
    {
        session_destroy();
        $this->redirect('login');
    }
}
