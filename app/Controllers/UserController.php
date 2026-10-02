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

        // 1. Validar nombre y apellido (longitud y caracteres permitidos)
        if (mb_strlen($name) < 3 || mb_strlen($name) > 25) {
            $this->redirect('admin/usuarios?error=' . urlencode('El nombre y apellido debe tener entre 3 y 25 caracteres.'));
        }

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $name)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El nombre y apellido solo debe contener letras y espacios.'));
        }

        // 2. Validar contraseña (longitud segura y sin espacios)
        if (strlen($password) < 8 || strlen($password) > 60) {
            $this->redirect('admin/usuarios?error=' . urlencode('La contraseña debe tener entre 8 y 60 caracteres.'));
        }

        if (preg_match('/\s/', $password)) {
            $this->redirect('admin/usuarios?error=' . urlencode('La contraseña no debe contener espacios en blanco.'));
        }

        // 3. Validar sintaxis y longitud de correo electrónico
        if (strlen($email) > 60 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El formato del correo electrónico no es válido o excede los 60 caracteres.'));
        }

        // 4. Validar dominio del correo (evita errores de tipeo como @muni.go.pe)
        $domain = strtolower(substr((string) strrchr($email, "@"), 1));
        $allowedDomains = ['muni.gob.pe', 'gmail.com', 'tecnosoluciones.com'];

        if (!in_array($domain, $allowedDomains, true)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El correo debe pertenecer a un dominio oficial permitido (ej: @muni.gob.pe).'));
        }

        // 5. Validar rol dentro de la lista de roles permitidos
        $allowedRoles = ['operador', 'supervisor', 'admin'];
        if (!in_array($role, $allowedRoles, true)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El rol seleccionado no es válido.'));
        }

        // 6. Verificar que el correo no esté registrado previamente
        if ((new User())->findByEmail($email)) {
            $this->redirect('admin/usuarios?error=' . urlencode('Ya existe un usuario registrado con ese correo.'));
        }

        // 7. Intentar registrar el usuario
        if ((new User())->create($name, $email, $password, $role)) {
            $this->redirect('admin/usuarios?success=' . urlencode('Usuario creado correctamente.'));
        } else {
            $this->redirect('admin/usuarios?error=' . urlencode('No se pudo crear el usuario.'));
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

    public function update(): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $userId = (int) ($_POST['user_id'] ?? 0);
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $role = strtolower(trim((string) ($_POST['role'] ?? '')));

        if ($userId <= 0) {
            $this->redirect('admin/usuarios?error=' . urlencode('Usuario inválido.'));
        }

        // 1. Validar nombre y apellido (longitud y caracteres permitidos)
        if (mb_strlen($name) < 3 || mb_strlen($name) > 25) {
            $this->redirect('admin/usuarios?error=' . urlencode('El nombre y apellido debe tener entre 3 y 25 caracteres.'));
        }

        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $name)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El nombre y apellido solo debe contener letras y espacios.'));
        }

        // 2. Validar sintaxis y longitud de correo electrónico
        if (strlen($email) > 60 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El formato del correo electrónico no es válido o excede los 60 caracteres.'));
        }

        $domain = strtolower(substr((string) strrchr($email, "@"), 1));
        $allowedDomains = ['muni.gob.pe', 'gmail.com', 'tecnosoluciones.com'];

        if (!in_array($domain, $allowedDomains, true)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El correo debe pertenecer a un dominio oficial permitido.'));
        }

        // 3. Validar rol dentro de la lista de roles permitidos
        $allowedRoles = ['operador', 'supervisor', 'admin'];
        if (!in_array($role, $allowedRoles, true)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El rol seleccionado no es válido.'));
        }

        $currentUserId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($userId === $currentUserId && $role !== 'admin') {
            $this->redirect('admin/usuarios?error=' . urlencode('No puedes quitarte el rol de administrador a ti mismo.'));
        }

        $userModel = new User();

        // 4. Verificar que el usuario exista
        if (!$userModel->findById($userId)) {
            $this->redirect('admin/usuarios?error=' . urlencode('El usuario a editar no existe.'));
        }
        
        // 5. Verificar si el nuevo correo pertenece a otro usuario
        $existing = $userModel->findByEmail($email);
        if ($existing && (int)$existing['id'] !== $userId) {
            $this->redirect('admin/usuarios?error=' . urlencode('Ya existe otro usuario con este correo.'));
        }

        if ($userModel->updateUser($userId, $name, $email, $role)) {
            if ($userId === $currentUserId) {
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['email'] = $email;
                $_SESSION['user']['role'] = $role;
            }
            $this->redirect('admin/usuarios?success=' . urlencode('Usuario actualizado correctamente.'));
        } else {
            $this->redirect('admin/usuarios?error=' . urlencode('No se pudo actualizar el usuario.'));
        }
    }
}