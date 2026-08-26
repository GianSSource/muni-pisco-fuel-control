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
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = trim((string) ($_POST['role'] ?? 'operador'));

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            $this->view('auth/register', [
                'title' => 'Registro',
                'csrfToken' => $this->csrfToken(),
                'error' => 'Complete los datos. La clave debe tener al menos 8 caracteres.'
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
