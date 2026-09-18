<section class="container" style="max-width: 960px; margin: 30px auto; padding: 0 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);">
                Gestión de Usuarios</h1>
            <p style="color: var(--text-muted, #64748b); margin-top: 4px; font-size: 0.95rem;">Crea cuentas y asigna
                roles (operador, supervisor, administrador)</p>
        </div>
        <a href="<?= BASE_URL ?>dashboard"
            style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); color: inherit; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            ← Volver al Panel
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="catalog-alert catalog-alert-success" style="margin-bottom: 20px;">
            <span class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-check"></use></svg><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="catalog-alert catalog-alert-danger" style="margin-bottom: 20px;">
            <span class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-alert"></use></svg><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <!-- Crear nuevo usuario -->
    <div
        style="background: var(--card-bg, #ffffff); padding: 24px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); margin-bottom: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 16px 0; color: #0284c7;">Crear Nuevo Usuario
        </h3>
        <form method="post" action="<?= BASE_URL ?>admin/usuarios/guardar" autocomplete="off"
            style="display: grid; grid-template-columns: 1fr 1fr 1fr 140px 140px; gap: 16px; align-items: flex-end;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <!-- Campo Nombre -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Nombre *</label>
                <input type="text" 
                       name="name" 
                       minlength="3" 
                       maxlength="50" 
                       required 
                       pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                       title="El nombre debe contener solo letras y espacios (mínimo 3 caracteres)."
                       oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')" 
                       placeholder="Nombre completo"
                       style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <!-- Campo Correo -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Correo *</label>
                <input type="email" 
                       name="email" 
                       maxlength="60" 
                       required 
                       pattern="[a-zA-Z0-9._%+-]+@(muni\.gob\.pe|gmail\.com|tecnosoluciones\.com)$"
                       title="Ingrese un correo válido institucional (@muni.gob.pe) o dominio autorizado."
                       oninput="this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g, '').toLowerCase()" 
                       placeholder="correo@muni.gob.pe"
                       style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <!-- Campo Contraseña -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Contraseña *</label>
                <input type="password" 
                       name="password" 
                       minlength="8" 
                       maxlength="50" 
                       required 
                       pattern="^\S+$"
                       title="La contraseña debe tener al menos 8 caracteres y no contener espacios."
                       oninput="this.value = this.value.replace(/\s/g, '')"
                       placeholder="Mínimo 8 caracteres"
                       style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <!-- Selección de Rol -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Rol</label>
                <select name="role" id="createUserRole" required style="display:none;">
                    <option value="operador" selected>Operador</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="admin">Administrador</option>
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
                    <th style="padding: 8px; font-size: 0.85rem; color: var(--text-muted, #64748b); text-align: center;">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <?php
                        $roleClean = strtolower((string)$u['role']);
                        $roleColor = match ($roleClean) {
                            'admin', 'administrador' => '#ef4444',
                            'supervisor' => '#8b5cf6',
                            default => '#3b82f6',
                        };
                        $isSelf = ((int) ($_SESSION['user']['id'] ?? 0)) === (int) $u['id'];
                    ?>
                    <tr style="border-bottom: 1px solid var(--border-color, #e2e8f0);">
                        <td style="padding: 10px 8px; font-weight: 600;">
                            <?= htmlspecialchars((string)$u['name'], ENT_QUOTES, 'UTF-8') ?>
                            <?php if ($isSelf): ?>
                                <span style="color: var(--text-muted, #64748b); font-weight: 500; font-size: 0.8rem;"> (tú)</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px 8px; color: var(--text-muted, #64748b);"><?= htmlspecialchars((string)$u['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="padding: 10px 8px;">
                            <span
                                style="font-size: 0.78rem; font-weight: 700; background: <?= $roleColor ?>; color: #fff; padding: 3px 10px; border-radius: 12px;">
                                <?= htmlspecialchars(strtoupper((string)$u['role']), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td style="padding: 10px 8px;">
                            <?php if ($isSelf): ?>
                                <span style="color: var(--text-muted, #64748b); font-size: 0.82rem;">Pide a otro admin que cambie tu rol</span>
                            <?php else: ?>
                                <form method="post" action="<?= BASE_URL ?>admin/usuarios/rol"
                                    style="display: flex; gap: 8px; align-items: center;">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                    <select name="role" id="roleSelect_<?= (int)$u['id'] ?>" required style="display:none;">
                                        <option value="operador" <?= in_array($roleClean, ['operador']) ? 'selected' : '' ?>>Operador</option>
                                        <option value="supervisor" <?= in_array($roleClean, ['supervisor']) ? 'selected' : '' ?>>Supervisor</option>
                                        <option value="admin" <?= in_array($roleClean, ['admin', 'administrador']) ? 'selected' : '' ?>>Administrador</option>
                                    </select>
                                    <button type="submit"
                                        style="padding: 6px 12px; border-radius: 6px; background: #0284c7; color: #fff; border: none; font-weight: 600; font-size: 0.82rem; cursor: pointer; white-space: nowrap;">
                                        Guardar
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td style="padding: 10px 8px; text-align: center;">
                            <?php if ($isSelf): ?>
                                <span style="color: var(--text-muted, #94a3b8); font-size: 0.82rem;">-</span>
                            <?php else: ?>
                                <form method="post" action="<?= BASE_URL ?>admin/usuarios/eliminar" style="margin: 0; display: inline-block;" onsubmit="return showCustomConfirm('¿Está seguro de que desea eliminar permanentemente al usuario <?= htmlspecialchars((string)$u['name'], ENT_QUOTES, 'UTF-8') ?>?', this);">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                                    <button type="submit"
                                        style="padding: 6px 10px; border-radius: 6px; background: #ef4444; color: #ffffff; border: none; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: background 0.2s ease;"
                                        title="Eliminar usuario">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
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
