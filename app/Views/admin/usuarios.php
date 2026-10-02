<section class="container" style="max-width: 960px; margin: 30px auto; padding: 0 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);">
                Gestión de Usuarios</h1>
            <p style="color: var(--text-muted, #64748b); margin-top: 4px; font-size: 0.95rem;">Crea cuentas y asigna
                roles (operador, supervisor, admin)</p>
        </div>
        <a href="<?= BASE_URL ?>dashboard"
            style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); color: inherit; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            ← Volver al Panel
        </a>
    </div>

    <?php if (!empty($success)): ?>
    <div id="toast-usr" class="usr-toast usr-toast--success" role="alert">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <span><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></span>
        <button onclick="document.getElementById('toast-usr').remove()" class="usr-toast__close" aria-label="Cerrar">&times;</button>
    </div>
    <?php elseif (!empty($error)): ?>
    <div id="toast-usr" class="usr-toast usr-toast--error" role="alert">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
        <button onclick="document.getElementById('toast-usr').remove()" class="usr-toast__close" aria-label="Cerrar">&times;</button>
    </div>
    <?php endif; ?>

    <style>
    .usr-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 18px;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 600;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        min-width: 280px;
        max-width: 400px;
        animation: usrToastIn 0.35s cubic-bezier(.22,1,.36,1) both;
    }
    .usr-toast--success { background: #059669; color: #ffffff; border: 1px solid #047857; }
    .usr-toast--success svg { color: #ffffff; flex-shrink:0; }
    .usr-toast--error   { background: #dc2626; color: #ffffff; border: 1px solid #b91c1c; }
    .usr-toast--error svg { color: #ffffff; flex-shrink:0; }
    .usr-toast span { flex: 1; }
    .usr-toast__close {
        background: none; border: none; cursor: pointer;
        font-size: 1.2rem; line-height: 1; color: inherit;
        opacity: 0.5; padding: 0 2px; transition: opacity .2s;
    }
    .usr-toast__close:hover { opacity: 1; }
    @keyframes usrToastIn {
        from { opacity: 0; transform: translateY(-16px) scale(.97); }
        to   { opacity: 1; transform: translateY(0)   scale(1); }
    }
    @keyframes usrToastOut {
        from { opacity: 1; transform: translateY(0)   scale(1); }
        to   { opacity: 0; transform: translateY(-16px) scale(.97); }
    }
    .usr-toast--hiding { animation: usrToastOut 0.3s ease forwards; }
    </style>
    <script>
    (function(){
        // Limpiar la URL para evitar que el mensaje reaparezca al presionar F5 (recargar)
        if (window.history.replaceState) {
            const url = new URL(window.location);
            if (url.searchParams.has('success') || url.searchParams.has('error')) {
                url.searchParams.delete('success');
                url.searchParams.delete('error');
                window.history.replaceState(null, null, url.toString());
            }
        }

        var t = document.getElementById('toast-usr');
        if (!t) return;
        var timer = setTimeout(function(){
            t.classList.add('usr-toast--hiding');
            setTimeout(function(){ if(t.parentNode) t.remove(); }, 320);
        }, 4000);
        t.querySelector('.usr-toast__close').addEventListener('click', function(){
            clearTimeout(timer);
        });
    })();
    </script>

    <!-- Crear nuevo usuario -->
    <div
        style="background: var(--card-bg, #ffffff); padding: 24px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); margin-bottom: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 16px 0; color: #0284c7;">Crear Nuevo Usuario
        </h3>
        <form method="post" action="<?= BASE_URL ?>admin/usuarios/guardar" autocomplete="off"
            style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; align-items: flex-end;">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
            
            <!-- Hack para evitar que Edge/Chrome autocompleten contraseñas guardadas -->
            <input type="email" name="fake_email" style="position: absolute; left: -9999px;" aria-hidden="true" tabindex="-1" autocomplete="username">
            <input type="password" name="fake_password" style="position: absolute; left: -9999px;" aria-hidden="true" tabindex="-1" autocomplete="current-password">

            <!-- Campo Nombre -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Nombre
                    *</label>
                <input type="text" name="name" minlength="3" maxlength="50" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                    title="El nombre debe contener solo letras y espacios (mínimo 3 caracteres)."
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')"
                    placeholder="Nombre completo" autocomplete="off"
                    style="width: 100%; height: 42px; box-sizing: border-box; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <!-- Campo Correo -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Correo
                    *</label>
                <input type="email" name="email" autocomplete="off" maxlength="60" required
                    pattern="[a-zA-Z0-9._%+-]+@(muni\.gob\.pe|gmail\.com|tecnosoluciones\.com)$"
                    title="Ingrese un correo válido institucional (@muni.gob.pe) o dominio autorizado."
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g, '').toLowerCase()"
                    placeholder="correo@muni.gob.pe"
                    style="width: 100%; height: 42px; box-sizing: border-box; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <!-- Campo Contraseña -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Contraseña
                    *</label>
                <input type="password" name="password" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" minlength="8" maxlength="50" required pattern="^\S+$"
                    title="La contraseña debe tener al menos 8 caracteres y no contener espacios."
                    oninput="this.value = this.value.replace(/\s/g, '')" placeholder="Mínimo 8 caracteres"
                    style="width: 100%; height: 42px; box-sizing: border-box; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <!-- Selección de Rol -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Rol</label>
                <select name="role" id="createUserRole" required style="display:none;">
                    <option value="operador" selected>Operador</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div>
                <button type="submit"
                    style="width: 100%; padding: 10px; border-radius: 8px; background: #0284c7; color: #ffffff; border: none; font-weight: 700; cursor: pointer; height: 42px;">
                    Crear
                </button>
            </div>
        </form>
    </div>

    <!-- Listado de usuarios -->
    <div
        style="background: var(--card-bg, #ffffff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0 0 16px 0; color: #0369a1;">Usuarios Registrados
            (<?= count($users) ?>)</h3>

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--border-color, #e2e8f0);">
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b);">Nombre</th>
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b);">Correo</th>
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b);">Rol actual</th>
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b);">Cambiar rol a...</th>
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b); text-align: center;">Estado</th>
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b); text-align: center;">Último Acceso</th>
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b); text-align: center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <?php
                    $roleClean = strtolower((string) $u['role']);
                    $roleColor = match ($roleClean) {
                        'admin'      => '#ef4444',
                        'supervisor' => '#8b5cf6',
                        default      => '#3b82f6',
                    };
                    $isSelf   = ((int) ($_SESSION['user']['id'] ?? 0)) === (int) $u['id'];
                    $isActive = ((int) ($u['is_active'] ?? 1)) === 1;

                    // Calcular texto de último acceso
                    $lastLoginRaw = $u['last_login_at'] ?? null;
                    if ($lastLoginRaw) {
                        $diff = time() - strtotime($lastLoginRaw);
                        if ($diff < 86400)        { $lastLoginText = 'Hoy ' . date('H:i', strtotime($lastLoginRaw)); }
                        elseif ($diff < 172800)   { $lastLoginText = 'Ayer ' . date('H:i', strtotime($lastLoginRaw)); }
                        elseif ($diff < 2592000)  { $lastLoginText = 'Hace ' . floor($diff / 86400) . ' días'; }
                        elseif ($diff < 31536000) { $lastLoginText = 'Hace ' . floor($diff / 2592000) . ' meses'; }
                        else                       { $lastLoginText = date('d/m/Y', strtotime($lastLoginRaw)); }
                        $lastLoginTitle = date('d/m/Y H:i:s', strtotime($lastLoginRaw));
                        $lastLoginColor = $diff > 1209600 ? 'var(--text-muted, #94a3b8)' : 'inherit'; // >14 días = gris
                    } else {
                        $lastLoginText  = 'Nunca';
                        $lastLoginTitle = 'Este usuario aún no ha iniciado sesión';
                        $lastLoginColor = 'var(--text-muted, #94a3b8)';
                    }
                    ?>
                    <tr style="border-bottom: 1px solid var(--border-color, #e2e8f0); <?= !$isActive ? 'opacity: 0.55;' : '' ?>">
                        <td style="padding: 10px 8px; font-weight: 600;">
                            <?= htmlspecialchars((string) $u['name'], ENT_QUOTES, 'UTF-8') ?>
                            <?php if ($isSelf): ?>
                                <span style="color: var(--text-muted, #64748b); font-weight: 500; font-size: 0.8rem;">
                                    (tú)</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px 8px; color: var(--text-muted, #64748b);">
                            <?= htmlspecialchars((string) $u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="padding: 10px 8px;">
                            <span
                                style="font-size: 0.78rem; font-weight: 700; background: <?= $roleColor ?>; color: #fff; padding: 3px 10px; border-radius: 12px;">
                                <?= htmlspecialchars(strtoupper((string) $u['role']), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td style="padding: 10px 8px;">
                            <?php if ($isSelf): ?>
                                <span style="color: var(--text-muted, #64748b); font-size: 0.82rem;">Pide a otro admin que
                                    cambie tu rol</span>
                            <?php else: ?>
                                <form method="post" action="<?= BASE_URL ?>admin/usuarios/rol"
                                    style="display: flex; gap: 8px; align-items: center;">
                                    <input type="hidden" name="csrf_token"
                                        value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                    <select name="role" id="roleSelect_<?= (int) $u['id'] ?>" required style="display:none;">
                                        <option value="operador" <?= $roleClean === 'operador' ? 'selected' : '' ?>>Operador
                                        </option>
                                        <option value="supervisor" <?= $roleClean === 'supervisor' ? 'selected' : '' ?>>Supervisor
                                        </option>
                                        <option value="admin" <?= $roleClean === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                    <button type="submit"
                                        style="padding: 6px 12px; border-radius: 6px; background: #0284c7; color: #fff; border: none; font-weight: 600; font-size: 0.82rem; cursor: pointer; white-space: nowrap;">
                                        Guardar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                        <!-- Columna Estado: toggle activo/inactivo -->
                        <td style="padding: 10px 8px; text-align: center; white-space: nowrap;">
                            <?php if ($isSelf): ?>
                                <span style="font-size: 0.78rem; font-weight: 700; background: #22c55e; color: #fff; padding: 3px 10px; border-radius: 12px;">ACTIVO</span>
                            <?php else: ?>
                                <form method="post" action="<?= BASE_URL ?>admin/usuarios/estado" style="margin:0; display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                    <button type="submit" title="<?= $isActive ? 'Desactivar usuario' : 'Activar usuario' ?>"
                                        style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 20px; border: none; font-weight: 700; font-size: 0.78rem; cursor: pointer; transition: all 0.2s ease;
                                               background: <?= $isActive ? '#dcfce7' : '#fee2e2' ?>;
                                               color: <?= $isActive ? '#15803d' : '#b91c1c' ?>;">
                                        <!-- Bolita de estado -->
                                        <span style="width: 8px; height: 8px; border-radius: 50%; background: <?= $isActive ? '#22c55e' : '#ef4444' ?>; flex-shrink:0;"></span>
                                        <?= $isActive ? 'Activo' : 'Inactivo' ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                        <!-- Columna Último Acceso -->
                        <td style="padding: 10px 8px; text-align: center; white-space: nowrap;">
                            <span title="<?= htmlspecialchars($lastLoginTitle, ENT_QUOTES, 'UTF-8') ?>"
                                  style="font-size: 0.82rem; font-weight: 600; color: <?= $lastLoginColor ?>;">
                                <?= htmlspecialchars($lastLoginText, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>

                        <td style="padding: 10px 8px; text-align: center;">
                            <?php if ($isSelf): ?>
                                <span style="color: var(--text-muted, #94a3b8); font-size: 0.82rem;">-</span>
                            <?php else: ?>
                                <form method="post" action="<?= BASE_URL ?>admin/usuarios/eliminar"
                                    style="margin: 0; display: inline-block;"
                                    onsubmit="return showCustomConfirm('¿Está seguro de que desea eliminar permanentemente al usuario <?= htmlspecialchars((string) $u['name'], ENT_QUOTES, 'UTF-8') ?>?', this);">
                                    <input type="hidden" name="csrf_token"
                                        value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                    <button type="submit"
                                        style="padding: 6px 10px; border-radius: 6px; background: #ef4444; color: #ffffff; border: none; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: background 0.2s ease;"
                                        title="Eliminar usuario">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path
                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                            </path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line>
                                            <line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<script>
    function initRoleCustomDropdown(selectId) {
        const selectEl = document.getElementById(selectId);
        if (!selectEl) return;

        const options = Array.from(selectEl.options);

        const container = document.createElement('div');
        container.className = 'custom-select-container';
        selectEl.parentNode.insertBefore(container, selectEl);
        container.appendChild(selectEl);
        selectEl.style.display = 'none';

        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.tabIndex = 0;

        const menu = document.createElement('div');
        menu.className = 'custom-select-menu';

        container.appendChild(trigger);
        container.appendChild(menu);

        function updateUI() {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (!selectedOpt) return;

            trigger.innerHTML = `
            <div class="custom-select-selected-content">
                <span class="custom-select-label-text" style="font-weight:600; font-size:0.88rem;">${selectedOpt.text}</span>
            </div>
            <span class="custom-select-chevron">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </span>
        `;

            menu.innerHTML = '';
            options.forEach((opt, idx) => {
                const isSelected = idx === selectEl.selectedIndex;
                const item = document.createElement('div');
                item.className = `custom-select-item ${isSelected ? 'selected' : ''}`;
                item.innerHTML = `
                <span class="item-title" style="font-size:0.88rem; font-weight:600;">${opt.text}</span>
                ${isSelected ? `<span class="item-check">${uiIcon('check')}</span>` : ''}
            `;
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectEl.selectedIndex = idx;
                    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                    updateUI();
                    container.classList.remove('open');
                });
                menu.appendChild(item);
            });
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.custom-select-container.open').forEach(c => {
                if (c !== container) c.classList.remove('open');
            });
            container.classList.toggle('open');
            updateUI();
        });

        trigger.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); trigger.click(); }
            if (e.key === 'Escape') container.classList.remove('open');
        });

        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) container.classList.remove('open');
        });

        updateUI();
    }

    document.addEventListener('DOMContentLoaded', () => {
        initRoleCustomDropdown('createUserRole');
        document.querySelectorAll('[id^="roleSelect_"]').forEach(sel => {
            initRoleCustomDropdown(sel.id);
        });
    });
</script>