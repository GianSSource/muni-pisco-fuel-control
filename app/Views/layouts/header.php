<?php $isLoggedIn = !empty($_SESSION['user']); ?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(($title ?? 'Sistema') . ' - ' . APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <a href="<?= BASE_URL ?>logout">Salir</a>
                </nav>
            <?php endif; ?>
        </div>
    </header>
    <main>