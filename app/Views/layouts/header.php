<?php 
$isLoggedIn = empty($isPublicPage) && !empty($_SESSION['user']); 
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($title ?? 'Sistema') . ' - ' . APP_NAME) ?></title>

    <!-- Icono de la pestaña (Favicon) -->
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>favicon.png">
    <link rel="shortcut icon" type="image/png" href="<?= BASE_URL ?>favicon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Josefin+Sans:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <script>
        window.uiIcon = (name, className = '') => `<svg class="ui-icon ${className}" aria-hidden="true"><use href="<?= BASE_URL ?>assets/icons.svg#icon-${name}"></use></svg>`;
    </script>
    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <style>
        /* Estilos para los botones de navegación de la barra superior */
        .topbar nav a {
            font-family: var(--font-heading);
            padding: 7px 14px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text-color, #cbd5e1);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
        }

        .topbar nav a:hover {
            background: rgba(255, 255, 255, 0.12);
            color: var(--text-color, #0f172a);
        }

        /* Clase activa para resaltar el módulo actual */
        .topbar nav a.active {
            background-color: #2563eb !important; /* Azul destacado */
            color: #ffffff !important;
            font-weight: 700 !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
        }

        /* Menú desplegable de Administración */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown > button {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 7px 14px;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: var(--text-color, #cbd5e1);
            font-weight: 600;
            font-size: 0.9rem;
            font-family: var(--font-heading);
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .nav-dropdown > button:hover,
        .nav-dropdown.open > button {
            background: rgba(255, 255, 255, 0.12);
            color: var(--text-color, #0f172a);
        }

        .nav-dropdown.active > button {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
        }

        .nav-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            min-width: 170px;
            background: var(--card-bg, #1e293b);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            padding: 6px;
            z-index: 50;
        }

        .nav-dropdown.open .nav-dropdown-menu {
            display: block;
        }

        .nav-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Botón moderno de cerrar sesión */
        .topbar nav a.logout-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px 7px 10px;
            margin-left: 4px;
            border-radius: 8px;
            color: #fca5a5;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
        }

        .topbar nav a.logout-btn svg {
            flex-shrink: 0;
            transition: transform 0.2s ease-in-out;
        }

        .topbar nav a.logout-btn:hover,
        .topbar nav a.logout-btn:focus,
        .topbar nav a.logout-btn:active {
            background: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
        }

        .topbar nav a.logout-btn:hover svg {
            transform: translateX(2px);
        }

        [data-theme="dark"] .topbar nav a:hover,
        [data-theme="dark"] .nav-dropdown > button:hover,
        [data-theme="dark"] .nav-dropdown.open > button {
            color: #ffffff;
        }
    </style>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('app-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>
    <?php if ($isLoggedIn): ?>
        <?php
            $userRole = strtolower((string) ($_SESSION['user']['role'] ?? $_SESSION['user']['rol'] ?? 'operador'));
            $userDisplayName = trim((string) ($_SESSION['user']['name'] ?? 'Usuario'));
            $nameParts = preg_split('/\s+/', $userDisplayName, -1, PREG_SPLIT_NO_EMPTY);
            $userInitials = count($nameParts) >= 2
                ? mb_strtoupper(mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1], 0, 1))
                : mb_strtoupper(mb_substr($nameParts[0] ?? 'U', 0, 2));
            $firstName = $nameParts[0] ?? 'Usuario';
            $avatarPalette = ['#2563eb', '#16a34a', '#dc2626', '#c026d3', '#0891b2', '#ea580c', '#7c3aed', '#0d9488'];
            $avatarColor = $avatarPalette[crc32($userDisplayName) % count($avatarPalette)];
        ?>
        <div class="app-shell">
            <aside class="sidebar" id="appSidebar" aria-label="Navegación principal">
                <div class="sidebar-brand-row">
                    <a class="sidebar-brand" href="<?= BASE_URL ?>dashboard">
                        <span class="sidebar-brand-mark">M</span>
                        <span class="sidebar-brand-label"><?= htmlspecialchars(APP_NAME) ?></span>
                    </a>
                    <button class="sidebar-close" id="sidebarCloseBtn" type="button" aria-label="Cerrar menú">×</button>
                </div>
                <div class="sidebar-welcome">
                    <span class="user-avatar" style="background: <?= $avatarColor ?>;"><?= htmlspecialchars($userInitials) ?></span>
                    <div class="sidebar-welcome-copy"><strong>Hola, <?= htmlspecialchars($firstName) ?></strong><small>Gestión municipal</small></div>
                </div>
                <nav class="sidebar-nav">
                    <p class="sidebar-section-label">Principal</p>
                    <a class="sidebar-link <?= (strpos($currentUri, 'dashboard') !== false || $currentUri === '/' || $currentUri === BASE_URL) ? 'active' : '' ?>" href="<?= BASE_URL ?>dashboard" title="Panel"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-building"></use></svg><span class="sidebar-link-label">Panel</span></a>
                    <a class="sidebar-link <?= strpos($currentUri, 'despachos') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>despachos" title="Despachos"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-truck"></use></svg><span class="sidebar-link-label">Despachos</span></a>
                    <a class="sidebar-link <?= strpos($currentUri, 'ingresos') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>ingresos" title="Ingresos"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use></svg><span class="sidebar-link-label">Ingresos</span></a>
                    <p class="sidebar-section-label">Gestión</p>
                    <a class="sidebar-link <?= strpos($currentUri, 'calendario') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>calendario" title="Calendario"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-calendar"></use></svg><span class="sidebar-link-label">Calendario</span></a>
                    <?php if (in_array($userRole, ['admin', 'supervisor'], true)): ?>
                        <a class="sidebar-link <?= strpos($currentUri, 'reportes') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>reportes" title="Reportes"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-note"></use></svg><span class="sidebar-link-label">Reportes</span></a>
                    <?php endif; ?>
                    <?php if ($userRole === 'admin'): ?>
                        <p class="sidebar-section-label">Administración</p>
                        <a class="sidebar-link <?= strpos($currentUri, 'catalogos') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/catalogos" title="Catálogos"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-store"></use></svg><span class="sidebar-link-label">Catálogos</span></a>
                        <a class="sidebar-link <?= strpos($currentUri, 'usuarios') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>admin/usuarios" title="Usuarios"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-user"></use></svg><span class="sidebar-link-label">Usuarios</span></a>
                    <?php endif; ?>
                </nav>
                <div class="sidebar-footer">
                    <button id="themeToggleBtn" class="sidebar-utility" type="button"><svg class="theme-icon icon-sun ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-sun"></use></svg><svg class="theme-icon icon-moon ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-moon"></use></svg><span>Cambiar tema</span></button>
                    <form id="logoutForm" action="<?= BASE_URL ?>logout" method="post" style="display: none;">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? $_SESSION['csrf_token'] ?? '') ?>">
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();" class="sidebar-utility sidebar-logout"><span>↪</span><span>Cerrar sesión</span></a>
                </div>
            </aside>
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            <section class="app-content">
                <header class="sidebar-mobile-header">
                    <button id="sidebarToggleBtn" type="button" aria-label="Abrir menú">☰</button>
                    <a href="<?= BASE_URL ?>dashboard"><?= htmlspecialchars(APP_NAME) ?></a>
                    <span><?= htmlspecialchars($userInitials) ?></span>
                </header>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const overlay = document.getElementById('sidebarOverlay');
            const openSidebar = () => document.body.classList.add('sidebar-open');
            const closeSidebar = () => document.body.classList.remove('sidebar-open');

            document.getElementById('sidebarToggleBtn')?.addEventListener('click', openSidebar);
            document.getElementById('sidebarCloseBtn')?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeSidebar();
            });

            document.getElementById('themeToggleBtn')?.addEventListener('click', () => {
                const current = document.documentElement.getAttribute('data-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', next);
                localStorage.setItem('app-theme', next);
                window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: next } }));
            });

            // Convierte el selector de cantidad en una lista visual consistente
            // en todos los navegadores y temas.
            document.querySelectorAll('.limit-select-native').forEach((select) => {
                if (select.dataset.enhanced === 'true') return;
                select.dataset.enhanced = 'true';

                const customSelect = document.createElement('div');
                customSelect.className = 'limit-select-custom';

                const trigger = document.createElement('button');
                trigger.type = 'button';
                trigger.className = 'limit-select-trigger';
                trigger.setAttribute('aria-haspopup', 'listbox');
                trigger.setAttribute('aria-expanded', 'false');

                const menu = document.createElement('div');
                menu.className = 'limit-select-menu';
                menu.setAttribute('role', 'listbox');

                const updateSelection = () => {
                    const option = select.options[select.selectedIndex];
                    trigger.textContent = option ? option.textContent : '';
                    menu.querySelectorAll('.limit-select-option').forEach((item) => {
                        item.classList.toggle('selected', item.dataset.value === select.value);
                    });
                };

                Array.from(select.options).forEach((option) => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'limit-select-option';
                    item.dataset.value = option.value;
                    item.setAttribute('role', 'option');
                    item.textContent = option.textContent;
                    item.addEventListener('click', () => {
                        select.value = option.value;
                        updateSelection();
                        customSelect.classList.remove('open');
                        trigger.setAttribute('aria-expanded', 'false');
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                    menu.appendChild(item);
                });

                trigger.addEventListener('click', () => {
                    const isOpen = customSelect.classList.toggle('open');
                    trigger.setAttribute('aria-expanded', String(isOpen));
                });
                select.addEventListener('change', updateSelection);
                customSelect.append(trigger, menu);
                select.insertAdjacentElement('afterend', customSelect);
                updateSelection();
            });

            document.addEventListener('click', (event) => {
                if (!event.target.closest('.limit-select-custom')) {
                    document.querySelectorAll('.limit-select-custom.open').forEach((customSelect) => {
                        customSelect.classList.remove('open');
                        customSelect.querySelector('.limit-select-trigger')?.setAttribute('aria-expanded', 'false');
                    });
                }
            });

            // Inicializar Flatpickr globalmente en todos los inputs tipo "date"
            if (typeof flatpickr !== 'undefined') {
                // Inyectar un placeholder por defecto antes de que Flatpickr convierta a texto
                document.querySelectorAll('input[type="date"]').forEach(el => {
                    if (!el.getAttribute('placeholder')) {
                        el.setAttribute('placeholder', 'dd/mm/aaaa');
                    }
                });

                flatpickr('input[type="date"]', {
                    locale: "es",
                    dateFormat: "Y-m-d",
                    disableMobile: "true" // Fuerza el uso de Flatpickr en móviles en lugar del nativo
                });
            }
        });
    </script>
    <main>
