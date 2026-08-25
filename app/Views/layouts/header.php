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
                <nav>
                    <a href="<?= BASE_URL ?>dashboard">Panel</a>
                    <a href="<?= BASE_URL ?>despachos">Despachos</a>
                    <a href="<?= BASE_URL ?>calendario">Calendario</a>
                    <a href="<?= BASE_URL ?>clientes">Clientes</a>
                    <a href="<?= BASE_URL ?>proyectos">Proyectos</a>
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