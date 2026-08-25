<section class="auth">
    <h1>Acceso seguro</h1>
    <?php if (!empty($error)): ?> <p class="alert"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post" action="<?= BASE_URL ?>login" class="form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <label>Email
            <input type="email" name="email" required autocomplete="email">
        </label>
        <label >Clave
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit">Ingresar</button>
    </form>
    <p>¿Nuevo Usuario?<a href="<?= BASE_URL ?>registro">Crear cuenta</a></p>
</section> 
