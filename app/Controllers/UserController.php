<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

final class UserController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();

        $users = (new User())->getAll();

        $this->view('admin/usuarios', [
            'title' => 'Gestión de Usuarios',
            'users' => $users,
            'csrfToken' => $this->csrfToken(),
            'success' => $_GET['success'] ?? null,
            'error' => $_GET['error'] ?? null,
        ]);
    }

    public function store(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $role = strtolower(trim((string) ($_POST['role'] ?? 'operador')));

        // 1. Validar campos obligatorios y longitud mínima
        if (mb_strlen($name) < 3 || strlen($password) < 8) {
            $this->redirect('admin/usuarios?error=Complete+los+datos+correctamente.+El+nombre+debe+tener+al+menos+3+caracteres+y+la+clave+8.');
        }

        // 2. Validar sintaxis de correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('admin/usuarios?error=El+formato+del+correo+electronico+no+es+valido.');
        }

        // 3. Validar dominio del correo (evita errores de tipeo como @muni.go.pe)
        $domain = strtolower(substr((string) strrchr($email, "@"), 1));
        $allowedDomains = ['muni.gob.pe', 'gmail.com', 'tecnosoluciones.com'];

        if (!in_array($domain, $allowedDomains, true)) {
            $this->redirect('admin/usuarios?error=El+correo+debe+pertenecer+a+un+dominio+oficial+permitido+(ej:+@muni.gob.pe).');
        }

        // 4. Validar rol dentro de la lista de roles permitidos
        $allowedRoles = ['operador', 'supervisor', 'admin'];
        if (!in_array($role, $allowedRoles, true)) {
            $this->redirect('admin/usuarios?error=El+rol+seleccionado+no+es+valido.');
        }

        // 5. Verificar que el correo no esté registrado previamente
        if ((new User())->findByEmail($email)) {
            $this->redirect('admin/usuarios?error=Ya+existe+un+usuario+registrado+con+ese+correo.');
        }

        // 6. Intentar registrar el usuario
        if ((new User())->create($name, $email, $password, $role)) {
            $this->redirect('admin/usuarios?success=Usuario+creado+correctamente.');
        } else {
            $this->redirect('admin/usuarios?error=No+se+pudo+crear+el+usuario.');
        }
    }

    public function updateRole(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $userId = (int) ($_POST['user_id'] ?? 0);
        $role = strtolower(trim((string) ($_POST['role'] ?? '')));
        $currentUserId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($userId <= 0) {
            $this->redirect('admin/usuarios?error=Usuario+invalido.');
        }

        $allowedRoles = ['operador', 'supervisor', 'admin'];
        if (!in_array($role, $allowedRoles, true)) {
            $this->redirect('admin/usuarios?error=El+rol+seleccionado+no+es+valido.');
        }

        if ($userId === $currentUserId) {
            // Evita que un admin se quite el rol a sí mismo
            $this->redirect('admin/usuarios?error=No+puedes+cambiar+tu+propio+rol.+Pide+a+otro+administrador+que+lo+haga.');
        }

        if ((new User())->updateRole($userId, $role)) {
            $this->redirect('admin/usuarios?success=Rol+actualizado+correctamente.');
        } else {
            $this->redirect('admin/usuarios?error=No+se+pudo+actualizar+el+rol.');
        }
    }

    public function delete(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $userId = (int) ($_POST['user_id'] ?? 0);
        $currentUserId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($userId <= 0) {
            $this->redirect('admin/usuarios?error=Usuario+invalido.');
        }

        if ($userId === $currentUserId) {
            $this->redirect('admin/usuarios?error=No+puedes+eliminar+tu+propia+cuenta+de+usuario.');
        }

        if ((new User())->delete($userId)) {
            $this->redirect('admin/usuarios?success=Usuario+eliminado+correctamente.');
        } else {
            $this->redirect('admin/usuarios?error=No+se+pudo+eliminar+el+usuario.');
        }
    }

    public function toggleStatus(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $userId = (int) ($_POST['user_id'] ?? 0);
        $currentUserId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($userId <= 0) {
            $this->redirect('admin/usuarios?error=Usuario+invalido.');
        }

        if ($userId === $currentUserId) {
            $this->redirect('admin/usuarios?error=No+puedes+desactivar+tu+propia+cuenta.');
        }

        $userModel = new User();
        if ($userModel->toggleStatus($userId)) {
            $user = $userModel->findById($userId);
            $estado = ((int)($user['is_active'] ?? 1)) === 1 ? 'activado' : 'desactivado';
            $this->redirect('admin/usuarios?success=Usuario+' . $estado . '+correctamente.');
        } else {
            $this->redirect('admin/usuarios?error=No+se+pudo+cambiar+el+estado+del+usuario.');
        }
    }
}