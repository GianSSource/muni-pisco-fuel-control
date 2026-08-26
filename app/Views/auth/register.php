<section class="auth">
    <h1>Registro de usuario</h1>

    <?php if (!empty($error)): ?>
        <p class="alert"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>registro" class="form">

        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

        <label>Nombre
            <input type="text" name="name" required autocomplete="name">
        </label>

        <label>Email
            <input type="email" name="email" required autocomplete="email">
        </label>

        <label>Contraseña
            <input type="password" name="password" required minlength="8" autocomplete="new-password">
        </label>

        <label>Rol de Usuario
            <select name="role" required style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 6px; border: 1px solid var(--border-color, #ccc); background: var(--card-bg, #fff); color: inherit;">
                <option value="operador" selected>Operador (Registro y consultas de despachos)</option>
                <option value="supervisor">Supervisor (Visualización de reportes y métricas)</option>
                <option value="admin">Administrador (Acceso total e ingresos de stock)</option>
            </select>
        </label>

        <button type="submit">Registrar</button>
    </form>

    <p>
        ¿Ya tienes cuenta?
        <a href="<?= BASE_URL ?>login">Ingresar</a>
    </p>
</section>