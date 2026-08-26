<?php $isLoggedIn = !empty($_SESSION['user']); ?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($title ?? 'Sistema') . ' - ' . APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('app-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="<?= BASE_URL ?>dashboard"><?= APP_NAME ?></a>
        <div class="topbar-right" style="display: flex; align-items: center; gap: 16px;">
            <?php if ($isLoggedIn): ?>
                <?php 
                    $userRole = strtolower((string)($_SESSION['user']['role'] ?? $_SESSION['user']['rol'] ?? 'operador')); 
                    $roleColor = match($userRole) {
                        'admin', 'administrador' => '#ef4444',
                        'supervisor' => '#8b5cf6',
                        default => '#3b82f6'
                    };
                ?>
                <nav style="display: flex; align-items: center; gap: 12px;">
                    <a href="<?= BASE_URL ?>dashboard">Panel</a>
                    <a href="<?= BASE_URL ?>despachos">Despachos</a>
                    <a href="<?= BASE_URL ?>ingresos">Ingresos</a>
                    <a href="<?= BASE_URL ?>calendario">Calendario</a>
                    <span style="font-size: 12px; padding: 3px 8px; border-radius: 12px; font-weight: 700; background: <?= $roleColor ?>; color: #ffffff;">
                        <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Usuario') ?> (<?= strtoupper($userRole) ?>)
                    </span>
                    <a href="<?= BASE_URL ?>logout">Salir</a>
                </nav>
            <?php endif; ?>
            <button id="themeToggleBtn" class="theme-toggle-btn" title="Cambiar Tema (Claro / Oscuro)">
                <span class="theme-icon icon-sun">☀️</span>
                <span class="theme-icon icon-moon">🌙</span>
            </button>
        </div>
    </header>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('themeToggleBtn');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const current = document.documentElement.getAttribute('data-theme');
                    const next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    localStorage.setItem('app-theme', next);
                });
            }
        });
    </script>
    <main>