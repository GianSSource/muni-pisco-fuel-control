<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon-wrap">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
            </div>
            <h1>Crear nueva cuenta</h1>
            <p class="auth-subtitle">Registra un nuevo usuario para el control municipal</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= BASE_URL ?>registro" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

            <div class="form-group">
                <label for="name">Nombre Completo</label>
                <div class="input-with-icon">
                    <svg class="input-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <input type="text" id="name" name="name" placeholder="Ej: Juan Pérez" required maxlength="30" autocomplete="name" oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')">
                </div>
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <div class="input-with-icon">
                    <svg class="input-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <input type="email" id="email" name="email" placeholder="ejemplo@muni.gob.pe" required maxlength="60" autocomplete="email">
                </div>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <div class="input-with-icon">
                    <svg class="input-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8" maxlength="50" autocomplete="new-password">
                </div>
            </div>

            <div class="form-group">
                <label for="role">Rol de Usuario</label>
                <select id="role" name="role" required style="width: 100%; padding: 12px 14px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--bg-body, #f8fafc); color: inherit; font-size: 0.95rem; font-weight: 600;">
                    <option value="operador" selected>Operador (Registro y consultas de despachos)</option>
                    <option value="supervisor">Supervisor (Visualización de reportes y métricas)</option>
                    <option value="admin">Administrador (Acceso total e ingresos de stock)</option>
                </select>
            </div>

            <button type="submit" class="auth-submit-btn">
                <span>Registrar Usuario</span>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </button>
        </form>

        <div class="auth-footer">
            <p>¿Ya tienes cuenta? <a href="<?= BASE_URL ?>login">Ingresar</a></p>
        </div>
    </div>
</div>