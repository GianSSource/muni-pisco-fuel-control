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
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12" />
            </svg>
            <span><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></span>
            <button onclick="document.getElementById('toast-usr').remove()" class="usr-toast__close"
                aria-label="Cerrar">&times;</button>
        </div>
    <?php elseif (!empty($error)): ?>
        <div id="toast-usr" class="usr-toast usr-toast--error" role="alert">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            <span><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></span>
            <button onclick="document.getElementById('toast-usr').remove()" class="usr-toast__close"
                aria-label="Cerrar">&times;</button>
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
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            min-width: 280px;
            max-width: 400px;
            animation: usrToastIn 0.35s cubic-bezier(.22, 1, .36, 1) both;
        }

        .usr-toast--success {
            background: #059669;
            color: #ffffff;
            border: 1px solid #047857;
        }

        .usr-toast--success svg {
            color: #ffffff;
            flex-shrink: 0;
        }

        .usr-toast--error {
            background: #dc2626;
            color: #ffffff;
            border: 1px solid #b91c1c;
        }

        .usr-toast--error svg {
            color: #ffffff;
            flex-shrink: 0;
        }

        .usr-toast span {
            flex: 1;
        }

        .usr-toast__close {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            line-height: 1;
            color: inherit;
            opacity: 0.5;
            padding: 0 2px;
            transition: opacity .2s;
        }

        .usr-toast__close:hover {
            opacity: 1;
        }

        @keyframes usrToastIn {
            from {
                opacity: 0;
                transform: translateY(-16px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes usrToastOut {
            from {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            to {
                opacity: 0;
                transform: translateY(-16px) scale(.97);
            }
        }

        .usr-toast--hiding {
            animation: usrToastOut 0.3s ease forwards;
        }
    </style>
    <script>
        (function () {
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
            var timer = setTimeout(function () {
                t.classList.add('usr-toast--hiding');
                setTimeout(function () { if (t.parentNode) t.remove(); }, 320);
            }, 4000);
            t.querySelector('.usr-toast__close').addEventListener('click', function () {
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
            <input type="email" name="fake_email" style="position: absolute; left: -9999px;" aria-hidden="true"
                tabindex="-1" autocomplete="username">
            <input type="password" name="fake_password" style="position: absolute; left: -9999px;" aria-hidden="true"
                tabindex="-1" autocomplete="current-password">

            <!-- Campo Nombre -->
            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Nombre y Apellido
                    *</label>
                <input type="text" name="name" minlength="3" maxlength="25" required pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                    title="El nombre debe contener solo letras y espacios (mínimo 3 y máximo 25 caracteres)."
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '')"
                    placeholder="Nombre y Apellido" autocomplete="off"
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
                <input type="password" name="password" autocomplete="new-password" readonly
                    onfocus="this.removeAttribute('readonly');" minlength="8" maxlength="50" required pattern="^\S+$"
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
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px;">
            <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #0369a1;">
                Usuarios Registrados (<span id="usrCount"><?= count($users) ?></span>)
            </h3>
            <!-- Barra de búsqueda y filtro -->
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <div style="position:relative;">
                    <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--text-muted,#94a3b8);pointer-events:none;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </span>
                    <input id="usrSearch" type="text" placeholder="Buscar por nombre o correo"
                        maxlength="30"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s@._-]/g, ''); filterUsers()"
                        style="height:36px;padding:0 12px 0 32px;border-radius:8px;border:1px solid var(--border-color,#cbd5e1);background:var(--input-bg,var(--card-bg,#fff));color:var(--text-color,inherit);font-size:0.85rem;outline:none;width:230px;box-sizing:border-box;">
                </div>
                <select id="usrRoleFilter" onchange="filterUsers()" style="display:none;">
                    <option value="">Todos los roles</option>
                    <option value="admin">Admin</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="operador">Operador</option>
                </select>
            </div>
        </div>

        <table id="usrTable" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid var(--border-color, #e2e8f0);">
                    <th
                        style="padding: 8px; font-size: 0.78rem; font-weight: 700; letter-spacing:.05em; text-transform:uppercase; color: var(--text-muted, #64748b);">
                        Nombre</th>
                    <th
                        style="padding: 8px; font-size: 0.78rem; font-weight: 700; letter-spacing:.05em; text-transform:uppercase; color: var(--text-muted, #64748b);">
                        Correo</th>
                    <th
                        style="padding: 8px; font-size: 0.78rem; font-weight: 700; letter-spacing:.05em; text-transform:uppercase; color: var(--text-muted, #64748b);">
                        Rol Actual</th>
                    <th
                        style="padding: 8px; font-size: 0.78rem; font-weight: 700; letter-spacing:.05em; text-transform:uppercase; color: var(--text-muted, #64748b); text-align:center;">
                        Último Acceso</th>
                    <th
                        style="padding: 8px; font-size: 0.78rem; font-weight: 700; letter-spacing:.05em; text-transform:uppercase; color: var(--text-muted, #64748b); text-align:center;">
                        Estado</th>
                    <th
                        style="padding: 8px; font-size: 0.78rem; font-weight: 700; letter-spacing:.05em; text-transform:uppercase; color: var(--text-muted, #64748b); text-align:center;">
                        Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" style="padding: 36px 16px; text-align: center; color: var(--text-muted, #64748b); font-size: 0.9rem;">
                            No hay usuarios registrados actualmente en el sistema.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                    <?php
                    $roleClean = strtolower((string) $u['role']);
                    $roleColor = match ($roleClean) {
                        'admin' => '#ef4444',
                        'supervisor' => '#8b5cf6',
                        default => '#3b82f6',
                    };
                    $isSelf = ((int) ($_SESSION['user']['id'] ?? 0)) === (int) $u['id'];
                    $isActive = ((int) ($u['is_active'] ?? 1)) === 1;

                    // Calcular texto de último acceso
                    $lastLoginRaw = $u['last_login_at'] ?? null;
                    if ($lastLoginRaw) {
                        $diff = time() - strtotime($lastLoginRaw);
                        if ($diff < 60) {
                            $lastLoginText = 'Ahora';
                        } elseif ($diff < 86400) {
                            $lastLoginText = 'Hoy, ' . date('H:i', strtotime($lastLoginRaw));
                        } elseif ($diff < 172800) {
                            $lastLoginText = 'Ayer, ' . date('H:i', strtotime($lastLoginRaw));
                        } elseif ($diff < 2592000) {
                            $lastLoginText = 'Hace ' . floor($diff / 86400) . ' días';
                        } elseif ($diff < 31536000) {
                            $lastLoginText = 'Hace ' . floor($diff / 2592000) . ' meses';
                        } else {
                            $lastLoginText = date('d/m/Y', strtotime($lastLoginRaw));
                        }
                        $lastLoginTitle = date('d/m/Y H:i:s', strtotime($lastLoginRaw));
                        $lastLoginColor = ($diff > 1209600 && $lastLoginText !== 'Ahora') ? 'var(--text-muted, #94a3b8)' : 'inherit';
                    } else {
                        $lastLoginText = 'Nunca';
                        $lastLoginTitle = 'Este usuario aún no ha iniciado sesión';
                        $lastLoginColor = 'var(--text-muted, #94a3b8)';
                    }

                    // Data JSON para el modal de edición
                    $editData = htmlspecialchars(json_encode([
                        'id' => (int) $u['id'],
                        'name' => (string) $u['name'],
                        'email' => (string) $u['email'],
                        'role' => $roleClean,
                        'isSelf' => $isSelf,
                    ]), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr class="usr-row"
                        data-name="<?= htmlspecialchars(mb_strtolower((string) $u['name'], 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>"
                        data-email="<?= strtolower(htmlspecialchars((string) $u['email'], ENT_QUOTES, 'UTF-8')) ?>"
                        data-role="<?= $roleClean ?>"
                        style="border-bottom: 1px solid var(--border-color, #e2e8f0); <?= !$isActive ? 'opacity: 0.55;' : '' ?>">

                        <!-- Nombre -->
                        <td style="padding: 10px 8px; font-weight: 700;">
                            <?= htmlspecialchars((string) $u['name'], ENT_QUOTES, 'UTF-8') ?>
                            <?php if ($isSelf): ?>
                                <span style="color: var(--text-muted, #64748b); font-weight: 500; font-size: 0.78rem;">
                                    (tú)</span>
                            <?php endif; ?>
                        </td>

                        <!-- Correo -->
                        <td style="padding: 10px 8px; color: var(--text-muted, #64748b); font-size: 0.88rem;">
                            <?= htmlspecialchars((string) $u['email'], ENT_QUOTES, 'UTF-8') ?>
                        </td>

                        <!-- Rol -->
                        <td style="padding: 10px 8px;">
                            <span
                                style="font-size: 0.75rem; font-weight: 700; background: <?= $roleColor ?>; color: #fff; padding: 3px 10px; border-radius: 12px; letter-spacing:.04em;">
                                <?= strtoupper($roleClean) ?>
                            </span>
                        </td>

                        <!-- Último Acceso -->
                        <td style="padding: 10px 8px; text-align: center; white-space: nowrap;">
                            <span title="<?= htmlspecialchars($lastLoginTitle, ENT_QUOTES, 'UTF-8') ?>"
                                style="font-size: 0.82rem; font-weight: 600; color: <?= $lastLoginColor ?>;">
                                <?= htmlspecialchars($lastLoginText, ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>

                        <!-- Estado: toggle switch -->
                        <td style="padding: 10px 8px; text-align: center;">
                            <?php if ($isSelf): ?>
                                <span
                                    style="display:inline-flex;align-items:center;gap:6px;font-size:0.82rem;font-weight:600;color:#15803d;">
                                    <span
                                        style="width:36px;height:20px;border-radius:10px;background:#22c55e;position:relative;display:inline-block;">
                                        <span
                                            style="position:absolute;right:3px;top:3px;width:14px;height:14px;border-radius:50%;background:#fff;"></span>
                                    </span>
                                    Activo
                                </span>
                            <?php else: ?>
                                <form method="post" action="<?= BASE_URL ?>admin/usuarios/estado"
                                    style="margin:0;display:inline;">
                                    <input type="hidden" name="csrf_token"
                                        value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                    <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                    <button type="submit" title="<?= $isActive ? 'Desactivar' : 'Activar' ?>"
                                        style="background:none;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:8px;font-weight:700;font-size:0.82rem;color:<?= $isActive ? '#15803d' : '#6b7280' ?>;padding:4px 0;">
                                        <span
                                            style="width:36px;height:20px;border-radius:10px;background:<?= $isActive ? '#22c55e' : '#d1d5db' ?>;position:relative;display:inline-block;transition:background .2s;">
                                            <span
                                                style="position:absolute;<?= $isActive ? 'right:3px' : 'left:3px' ?>;top:3px;width:14px;height:14px;border-radius:50%;background:#fff;transition:all .2s;"></span>
                                        </span>
                                        <?= $isActive ? 'Activo' : 'Inactivo' ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>

                        <!-- Acción: editar + eliminar -->
                        <td style="padding: 10px 8px; text-align: center; white-space: nowrap;">
                            <div style="display:inline-flex;gap:6px;align-items:center;">
                                <!-- Botón Editar (lápiz) -->
                                <button type="button" onclick="openEditModal(<?= $editData ?>)" title="<?= $isSelf ? 'Editar mis datos' : 'Editar usuario' ?>"
                                    style="padding:6px 8px;border-radius:6px;background:var(--card-bg,#f8fafc);border:1px solid var(--border-color,#cbd5e1);cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all .2s;color:var(--text-color,#334155);">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>

                                <?php if (!$isSelf): ?>
                                    <!-- Botón Eliminar (papelera) -->
                                    <form method="post" action="<?= BASE_URL ?>admin/usuarios/eliminar" style="margin:0;"
                                        data-confirm="¿Eliminar permanentemente a <?= htmlspecialchars((string) $u['name'], ENT_QUOTES, 'UTF-8') ?>?"
                                        onsubmit="return showCustomConfirm(this.dataset.confirm, this);">
                                        <input type="hidden" name="csrf_token"
                                            value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                                        <button type="submit" title="Eliminar usuario"
                                            style="padding:6px 8px;border-radius:6px;background:#fef2f2;border:1px solid #fecaca;color:#dc2626;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;transition:all .2s;">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                <line x1="10" y1="11" x2="10" y2="17" />
                                                <line x1="14" y1="11" x2="14" y2="17" />
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Leyenda de iconos -->
        <div
            style="margin-top:14px;padding-top:12px;border-top:1px solid var(--border-color,#e2e8f0);display:flex;gap:20px;flex-wrap:wrap;font-size:0.78rem;color:var(--text-muted,#64748b);align-items:center;">
            <span style="display:inline-flex;align-items:center;gap:6px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" style="color:#0284c7;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" /><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" /></svg>
                Editar datos y rol
            </span>
            <span style="display:inline-flex;align-items:center;gap:6px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" style="color:#dc2626;"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                Eliminar (con confirmación)
            </span>
            <span style="display:inline-flex;align-items:center;gap:6px;">
                <span style="width:20px;height:12px;border-radius:6px;background:#22c55e;position:relative;display:inline-block;"><span style="position:absolute;right:2px;top:2px;width:8px;height:8px;border-radius:50%;background:#fff;"></span></span>
                Interruptor: activa o desactiva el acceso sin perder el historial
            </span>
        </div>
    </div>
</section>

<!-- ===================== MODAL EDITAR USUARIO ===================== -->
<div id="editUserModal"
    style="display:none;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;overflow-y:auto;padding:24px 0;">
    <!-- Backdrop -->
    <div onclick="closeEditModal()"
        style="position:absolute;inset:0;background:rgba(0,0,0,0.55);backdrop-filter:blur(3px);"></div>

    <!-- Card -->
    <div
        style="position:relative;background:var(--card-bg,#fff);border-radius:16px;box-shadow:0 25px 60px rgba(0,0,0,0.25);padding:32px;width:100%;max-width:460px;margin:16px;animation:modalIn .25s cubic-bezier(.22,1,.36,1) both;">
        <div style="margin-bottom:24px;">
            <h2 style="margin:0;font-size:1.2rem;font-weight:700;color:var(--text-color,#0f172a);">Editar Usuario</h2>
            <p style="margin:4px 0 0;font-size:0.85rem;color:var(--text-muted,#64748b);">Modifica nombre, correo o rol</p>
        </div>

        <form id="editUserForm" method="post" action="<?= BASE_URL ?>admin/usuarios/editar">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="user_id" id="edit_user_id">

            <div style="display:flex;flex-direction:column;gap:16px;">
                <!-- Nombre -->
                <div>
                    <label
                        style="display:block;font-size:0.83rem;font-weight:700;margin-bottom:6px;color:var(--text-color,#334155);">Nombre
                        y Apellido *</label>
                    <input type="text" id="edit_name" name="name" required minlength="3" maxlength="25"
                        pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$"
                        oninput="this.value=this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g,'')"
                        placeholder="Nombre y Apellido"
                        style="width:100%;height:42px;box-sizing:border-box;padding:10px 12px;border-radius:8px;border:1px solid var(--border-color,#cbd5e1);background:var(--input-bg,#fff);color:var(--text-color,inherit);font-size:0.9rem;outline:none;transition:border-color .2s;">
                </div>

                <!-- Correo -->
                <div>
                    <label
                        style="display:block;font-size:0.83rem;font-weight:700;margin-bottom:6px;color:var(--text-color,#334155);">Correo
                        electrónico *</label>
                    <input type="email" id="edit_email" name="email" required maxlength="60"
                        pattern="[a-zA-Z0-9._%+\-]+@(muni\.gob\.pe|gmail\.com|tecnosoluciones\.com)$"
                        oninput="this.value=this.value.replace(/[^a-zA-Z0-9@._\-]/g,'').toLowerCase()"
                        placeholder="correo@muni.gob.pe"
                        style="width:100%;height:42px;box-sizing:border-box;padding:10px 12px;border-radius:8px;border:1px solid var(--border-color,#cbd5e1);background:var(--input-bg,#fff);color:var(--text-color,inherit);font-size:0.9rem;outline:none;transition:border-color .2s;">
                </div>

                <!-- Rol -->
                <div id="edit_role_wrapper">
                    <label
                        style="display:block;font-size:0.83rem;font-weight:700;margin-bottom:6px;color:var(--text-color,#334155);">Rol</label>
                    <select id="edit_role" name="role" required>
                        <option value="operador">Operador</option>
                        <option value="supervisor">Supervisor</option>
                        <option value="admin">Admin</option>
                    </select>
                    <p id="edit_role_self_note" style="display:none;margin:4px 0 0;font-size:0.75rem;color:var(--text-muted,#64748b);">No puedes modificar tu propio rol de administrador.</p>
                </div>

                <!-- Botones -->
                <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                    <button type="button" onclick="closeEditModal()"
                        style="padding:10px 20px;border-radius:8px;border:1px solid var(--border-color,#cbd5e1);background:transparent;color:var(--text-color,#334155);font-weight:600;cursor:pointer;font-size:0.9rem;">
                        Cancelar
                    </button>
                    <button type="submit"
                        style="padding:10px 24px;border-radius:8px;border:none;background:#0284c7;color:#fff;font-weight:700;cursor:pointer;font-size:0.9rem;transition:background .2s;">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes modalIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    #editUserModal.is-open {
        display: flex;
    }
</style>

<script>
    function openEditModal(data) {
        document.getElementById('edit_user_id').value = data.id;
        document.getElementById('edit_name').value = data.name;
        document.getElementById('edit_email').value = data.email;
        var roleEl = document.getElementById('edit_role');
        roleEl.value = data.role;
        roleEl.dispatchEvent(new Event('change')); // actualiza el custom dropdown

        // Proteger el rol de administrador si se edita a sí mismo
        var roleWrap = document.getElementById('edit_role_wrapper');
        var roleNote = document.getElementById('edit_role_self_note');
        var roleTrigger = roleWrap ? roleWrap.querySelector('.custom-select-trigger') : null;
        if (data.isSelf) {
            if (roleTrigger) {
                roleTrigger.style.pointerEvents = 'none';
                roleTrigger.style.opacity = '0.7';
                roleTrigger.title = 'No puedes modificar tu propio rol';
            }
            if (roleNote) roleNote.style.display = 'block';
        } else {
            if (roleTrigger) {
                roleTrigger.style.pointerEvents = '';
                roleTrigger.style.opacity = '';
                roleTrigger.title = '';
            }
            if (roleNote) roleNote.style.display = 'none';
        }

        var modal = document.getElementById('editUserModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        setTimeout(function () { document.getElementById('edit_name').focus(); }, 50);
    }

    function closeEditModal() {
        document.getElementById('editUserModal').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeEditModal();
    });

    document.addEventListener('DOMContentLoaded', () => {
        initRoleCustomDropdown('createUserRole');
        initRoleCustomDropdown('edit_role');
        initRoleCustomDropdown('usrRoleFilter');
    });

    const roleColors = {
        'admin': '#ef4444',
        'supervisor': '#8b5cf6',
        'operador': '#3b82f6'
    };

    function initRoleCustomDropdown(selectId) {
        const selectEl = document.getElementById(selectId);
        if (!selectEl || selectEl.dataset.customDropdown === 'true') return;
        selectEl.dataset.customDropdown = 'true';

        const isFilter = selectId === 'usrRoleFilter';
        const options = Array.from(selectEl.options);
        const container = document.createElement('div');
        container.className = 'custom-select-container';
        if (isFilter) {
            container.style.width = '180px';
            container.style.minWidth = '165px';
            container.style.flexShrink = '0';
        }
        selectEl.parentNode.insertBefore(container, selectEl);
        container.appendChild(selectEl);
        selectEl.style.display = 'none';

        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.tabIndex = 0;
        if (isFilter) {
            trigger.style.height = '36px';
            trigger.style.padding = '0 12px';
            trigger.style.fontSize = '0.85rem';
        }

        const menu = document.createElement('div');
        menu.className = 'custom-select-menu';

        container.appendChild(trigger);
        container.appendChild(menu);

        function updateUI() {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (!selectedOpt) return;
            const val = (selectedOpt.value || '').toLowerCase();
            const dot = roleColors[val]
                ? `<span style="width:8px;height:8px;border-radius:50%;background:${roleColors[val]};display:inline-block;flex-shrink:0;"></span>`
                : (val === '' && isFilter ? `<span style="width:8px;height:8px;border-radius:50%;border:1.5px solid var(--text-muted, #94a3b8);display:inline-block;flex-shrink:0;"></span>` : '');

            trigger.innerHTML = `
            <div class="custom-select-selected-content" style="display:flex;align-items:center;gap:8px;min-width:0;flex:1;">
                ${dot}
                <span class="custom-select-label-text" style="font-weight:600;font-size:${isFilter ? '0.85rem' : '0.88rem'};overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${selectedOpt.text}</span>
            </div>
            <span class="custom-select-chevron">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
            </span>`;
            menu.innerHTML = '';
            options.forEach((opt, idx) => {
                const isSelected = idx === selectEl.selectedIndex;
                const item = document.createElement('div');
                item.className = `custom-select-item ${isSelected ? 'selected' : ''}`;
                const itemVal = (opt.value || '').toLowerCase();
                const itemDot = roleColors[itemVal]
                    ? `<span style="width:8px;height:8px;border-radius:50%;background:${roleColors[itemVal]};display:inline-block;flex-shrink:0;"></span>`
                    : (itemVal === '' && isFilter ? `<span style="width:8px;height:8px;border-radius:50%;border:1.5px solid var(--text-muted, #94a3b8);display:inline-block;flex-shrink:0;"></span>` : '');

                item.innerHTML = `
                    <div style="display:flex;align-items:center;gap:8px;width:100%;">
                        ${itemDot}
                        <span class="item-title" style="font-size:0.85rem;font-weight:600;">${opt.text}</span>
                    </div>
                `;
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectEl.selectedIndex = idx;
                    selectEl.value = opt.value;
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

        // Escuchar cambios externos
        selectEl.addEventListener('change', updateUI);

        updateUI();
    }

    function filterUsers() {
        const searchInput = document.getElementById('usrSearch');
        const roleSelect = document.getElementById('usrRoleFilter');
        const norm = (s) => (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();

        const search = norm(searchInput ? searchInput.value : '');
        const role = (roleSelect ? roleSelect.value : '').toLowerCase().trim();

        const rows = document.querySelectorAll('#usrTable tbody tr.usr-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const name = norm(row.getAttribute('data-name'));
            const email = (row.getAttribute('data-email') || '').toLowerCase().trim();
            const userRole = (row.getAttribute('data-role') || '').toLowerCase().trim();

            const matchesSearch = !search || name.includes(search) || email.includes(search);
            const matchesRole = !role || userRole === role;

            if (matchesSearch && matchesRole) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        const countEl = document.getElementById('usrCount');
        if (countEl) countEl.textContent = visibleCount;

        // Fila cuando no hay coincidencias
        let noResultsRow = document.getElementById('usrNoResultsRow');
        if (visibleCount === 0 && rows.length > 0) {
            if (!noResultsRow) {
                const tbody = document.querySelector('#usrTable tbody');
                if (tbody) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'usrNoResultsRow';
                    noResultsRow.innerHTML = '<td colspan="6" style="padding: 32px 16px; text-align: center; color: var(--text-muted, #64748b); font-size: 0.9rem;">No se encontraron usuarios que coincidan con la búsqueda.</td>';
                    tbody.appendChild(noResultsRow);
                }
            } else {
                noResultsRow.style.display = '';
            }
        } else if (noResultsRow) {
            noResultsRow.style.display = 'none';
        }
    }
</script>