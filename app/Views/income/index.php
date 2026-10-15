<style>
    /* ==========================================================================
       Estilos Flexibles y Adaptables para Historial de Ingresos
       ========================================================================== */
    .search-page-wrapper {
        color: var(--text-color, #0f172a);
    }

    /* Tarjetas de Estadísticas */
    .stat-card {
        background-color: var(--card-bg, #ffffff) !important;
        border: 1px solid var(--border-color, #e2e8f0) !important;
        padding: 18px;
        border-radius: 10px;
        transition: border-color 0.2s ease, background-color 0.25s ease;
    }

    .stat-card:hover {
        border-color: var(--primary, #3b82f6) !important;
    }

    .stat-card .stat-label {
        font-size: 0.85rem;
        color: var(--text-muted, #64748b) !important;
        font-weight: 600;
    }

    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin-top: 6px;
    }


    /* Dropdown de desglose de combustibles en la tarjeta de Líquidos */
    .stats-grid {
        position: relative;
        z-index: 50;
    }

    .stat-card.dropdown-open {
        z-index: 100;
        position: relative;
    }

    .stat-dropdown-trigger {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        background: rgba(37, 99, 235, 0.10);
        color: #2563eb;
        border: 1px solid rgba(37, 99, 235, 0.25);
        cursor: pointer;
        transition: all 0.18s ease;
    }

    .stat-dropdown-trigger:hover {
        background: rgba(37, 99, 235, 0.18);
        border-color: #2563eb;
    }

    [data-theme="dark"] .stat-dropdown-trigger {
        background: rgba(59, 130, 246, 0.18);
        color: #60a5fa;
        border-color: rgba(96, 165, 250, 0.35);
    }

    [data-theme="dark"] .stat-dropdown-trigger:hover {
        background: rgba(59, 130, 246, 0.30);
    }

    /* Menú desplegable — usa variables de tema para adaptarse automáticamente */
    .stat-dropdown-menu {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        width: 250px;
        background: var(--card-bg, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 12px;
        box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.18), 0 4px 10px -2px rgba(15, 23, 42, 0.06);
        z-index: 1000;
        overflow: hidden;
        animation: fadeInDropdown 0.18s cubic-bezier(0.16, 1, 0.3, 1);
        color: var(--text-color, #0f172a);
    }

    [data-theme="dark"] .stat-dropdown-menu {
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.55);
    }

    .stat-dropdown-header {
        padding: 10px 14px;
        background: var(--bg-body, #f8fafc);
        border-bottom: 1px solid var(--border-color, #e2e8f0);
    }

    .stat-dropdown-body {
        max-height: 260px;
        overflow-y: auto;
        padding: 6px 12px;
        background: var(--card-bg, #ffffff);
    }

    .stat-dropdown-item {
        padding: 10px 4px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        color: var(--text-color, #0f172a);
    }

    .stat-dropdown-item:last-child {
        border-bottom: none;
    }

    .stat-dropdown-footer {
        padding: 9px 14px;
        background: var(--bg-body, #f8fafc);
        border-top: 1px solid var(--border-color, #e2e8f0);
        font-size: 0.74rem;
        color: var(--text-muted, #64748b);
        text-align: right;
    }

    @keyframes fadeInDropdown {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Tarjeta del Buscador e Input */
    .search-card {
        background-color: var(--card-bg, #ffffff) !important;
        border: 1px solid var(--border-color, #e2e8f0) !important;
        padding: 16px;
        border-radius: 12px;
    }

    .search-input-dark {
        width: 100%;
        padding: 12px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #cbd5e1) !important;
        font-size: 0.95rem;
        background-color: var(--input-bg, #ffffff) !important;
        color: var(--text-color, #0f172a) !important;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-input-dark:focus {
        border-color: var(--primary, #3b82f6) !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .search-input-dark::placeholder {
        color: var(--text-muted, #64748b) !important;
    }

    /* Botón Filtros Avanzados adaptativo (Blanco en Modo Claro, Oscuro en Modo Nocturno) */
    #toggleFiltersBtn {
        background-color: #ffffff !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    #toggleFiltersBtn:hover {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border-color: #94a3b8 !important;
    }

    [data-theme="dark"] #toggleFiltersBtn {
        background-color: #1e293b !important;
        color: #f8fafc !important;
        border-color: #334155 !important;
    }

    [data-theme="dark"] #toggleFiltersBtn:hover {
        background-color: #334155 !important;
        color: #ffffff !important;
        border-color: #475569 !important;
    }

    /* Tabla y Contenedores */
    .table-card {
        background-color: var(--card-bg, #ffffff) !important;
        border: 1px solid var(--border-color, #e2e8f0) !important;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
    }

    .table-dark {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-dark th {
        padding: 12px;
        background: color-mix(in srgb, var(--bg-body, #f8fafc) 92%, var(--primary, #2563eb));
        border-bottom: 2px solid var(--border-color, #e2e8f0) !important;
        text-align: left;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--text-muted, #64748b) !important;
        font-weight: 700;
    }

    .table-dark tr.income-row {
        border-bottom: 1px solid var(--border-color, #e2e8f0) !important;
        transition: background-color 0.15s ease;
    }

    .table-dark tr.income-row:hover {
        background-color: rgba(37, 99, 235, 0.04) !important;
    }

    .table-dark td {
        padding: 14px 12px;
        color: var(--text-color, #0f172a);
        vertical-align: middle;
    }

    /* Badges y Pastillas de Estado / Datos */
    .receipt-tag {
        background-color: rgba(59, 130, 246, 0.15) !important;
        color: var(--primary, #2563eb) !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
        font-weight: 700 !important;
        padding: 4px 10px !important;
        border-radius: 6px !important;
        font-size: 0.85rem !important;
        font-family: monospace !important;
        display: inline-block !important;
    }

    .fuel-type-pill {
        background-color: rgba(59, 130, 246, 0.12) !important;
        color: var(--primary, #2563eb) !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
        padding: 4px 10px !important;
        border-radius: 12px !important;
        font-size: 0.82rem !important;
        font-weight: 600 !important;
        display: inline-block !important;
    }

    .status-pill {
        padding: 4px 12px !important;
        border-radius: 20px !important;
        font-size: 0.78rem !important;
        font-weight: 700 !important;
        display: inline-block !important;
    }

    .status-pill.status-available {
        background-color: rgba(34, 197, 94, 0.18) !important;
        color: #10b981 !important;
        border: 1px solid rgba(34, 197, 94, 0.4) !important;
    }

    .status-pill.status-exhausted {
        background-color: rgba(239, 68, 68, 0.18) !important;
        color: #ef4444 !important;
        border: 1px solid rgba(239, 68, 68, 0.4) !important;
    }

    /* Botones de Cabecera */
    .btn-primary-dark {
        background-color: #2563eb !important;
        color: #ffffff !important;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: background-color 0.2s;
    }

    .btn-primary-dark:hover {
        background-color: #1d4ed8 !important;
    }

    .btn-secondary-dark {
        background-color: var(--card-bg, #ffffff) !important;
        color: var(--text-color, #0f172a) !important;
        border: 1px solid var(--border-color, #cbd5e1) !important;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.2s;
    }

    .btn-secondary-dark:hover {
        background-color: rgba(37, 99, 235, 0.08) !important;
    }

    .info-obs-btn {
        background: rgba(59, 130, 246, 0.15);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.4);
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .info-obs-btn:hover {
        background: #3b82f6;
        color: #ffffff;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.4);
    }

    /* Cabecera de tabla con selector de límite */
    .table-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .limit-selector-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .limit-selector-wrap select {
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid var(--border-color, #cbd5e1);
        background-color: var(--card-bg, #ffffff);
        color: var(--text-color, #0f172a);
        font-size: 0.9rem;
    }

    /* Paginación */
    .pagination-container {
        margin-top: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pagination-info {
        color: var(--text-muted, #64748b);
        font-size: 0.9rem;
    }

    .pagination-controls {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    /* Corrección exacta para inputs de fecha generados por Flatpickr para igualar a custom-select */
    .filter-field .filter-input,
    .filter-field .flatpickr-wrapper,
    .filter-field .flatpickr-input {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        height: 42px !important;
        box-sizing: border-box !important;
        font-size: 13px !important;
        padding: 10px 12px !important;
        margin: 0 !important;
    }
</style>

<div class="search-page-wrapper">
    <section class="page-head"
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 class="icon-with-text"><svg class="ui-icon">
                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-download"></use>
                </svg>Buscador e Historial de Ingresos de Combustible</h1>
            <p class="subtitle" style="color: var(--text-muted, #64748b);">Gestión y control de abastecimientos y
                recargas de inventario municipal</p>
        </div>
        <div class="actions" style="display: flex; gap: 10px;">
            <a class="btn-primary-dark" href="<?= BASE_URL ?>ingresos/nuevo">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
                Registrar Nuevo Ingreso
            </a>

        </div>
    </section>

    <?php if (!empty($success)): ?>
        <div
            style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #10b981; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            <span class="icon-with-text"><svg class="ui-icon">
                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-check"></use>
                </svg><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Resumen rápido de Ingresos -->
    <?php
    $totalLiquidGallons = 0;
    $totalLiquidRemaining = 0;
    $totalGnvQuantity = 0;
    $totalGnvRemaining = 0;
    $activeLots = 0;
    $exhaustedLots = 0;
    $activeLiquidLots = 0;
    $activeGnvLots = 0;
    $hasGnv = false;
    $hasLiquid = false;

    // Desglose histórico por cada tipo de combustible líquido
    $liquidBreakdown = [];

    if (!empty($incomes)) {
        foreach ($incomes as $inc) {
            $qty = (float) ($inc['gallons'] ?? 0);
            $rem = (float) ($inc['gallons_remaining'] ?? 0);
            if ($rem > 0) {
                $activeLots++;
            } else {
                $exhaustedLots++;
            }
            $rawFt = trim($inc['fuel_type'] ?? 'Diesel B5 S-50');
            $ft = strtolower($rawFt);
            if (str_contains($ft, 'gnv') || str_contains($ft, 'gas natural')) {
                $hasGnv = true;
                $totalGnvQuantity += $qty;
                $totalGnvRemaining += $rem;
                if ($rem > 0) {
                    $activeGnvLots++;
                }
            } else {
                $hasLiquid = true;
                $totalLiquidGallons += $qty;
                $totalLiquidRemaining += $rem;
                if ($rem > 0) {
                    $activeLiquidLots++;
                }
                if (!isset($liquidBreakdown[$rawFt])) {
                    $liquidBreakdown[$rawFt] = [
                        'name' => $rawFt,
                        'entered' => 0.0,
                        'remaining' => 0.0,
                        'lots' => 0,
                        'activeLots' => 0,
                    ];
                }
                $liquidBreakdown[$rawFt]['entered'] += $qty;
                $liquidBreakdown[$rawFt]['remaining'] += $rem;
                $liquidBreakdown[$rawFt]['lots']++;
                if ($rem > 0) {
                    $liquidBreakdown[$rawFt]['activeLots']++;
                }
            }
        }
    }
    // Ordenar desglose de mayor ingreso a menor
    uasort($liquidBreakdown, fn($a, $b) => $b['entered'] <=> $a['entered']);
    ?>
    <section class="stats-grid"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; position: relative; z-index: 50;">

        <?php if ($hasLiquid || !$hasGnv): ?>
            <!-- Card 1: Total Líquidos Ingresados con Dropdown de Desglose Histórico -->
            <div class="stat-card" style="border-left: 3px solid #2563eb !important; position: relative;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                    <span
                        class="stat-label"><?= $hasGnv ? 'Total Líquidos Ingresados' : 'Total Histórico Ingresado' ?></span>

                    <?php if (!empty($liquidBreakdown)): ?>
                        <div style="position: relative;">
                            <button type="button" id="liquidDropdownBtn" class="stat-dropdown-trigger"
                                onclick="toggleLiquidDropdown(event)" title="Ver histórico desglosado por combustible">
                                <span>Por tipo</span>
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                    style="transition: transform 0.2s;">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>

                            <!-- Menú Desplegable Flotante -->
                            <div id="liquidDropdownMenu" class="stat-dropdown-menu" style="display: none;"
                                onclick="event.stopPropagation()">
                                <div class="stat-dropdown-header">
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span
                                            style="font-weight: 700; font-size: 0.82rem; color: var(--text-color, #0f172a);">Histórico
                                            por Combustible</span>
                                        <span
                                            style="font-size: 0.72rem; color: var(--text-muted, #64748b); font-weight: 600;"><?= count($liquidBreakdown) ?>
                                            tipos</span>
                                    </div>
                                </div>
                                <div class="stat-dropdown-body">
                                    <?php foreach ($liquidBreakdown as $bName => $bData): ?>
                                        <div class="stat-dropdown-item"
                                            style="display: flex; align-items: center; justify-content: space-between; padding: 10px 4px;">
                                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-color, #0f172a);">
                                                <?= htmlspecialchars($bName) ?>
                                            </span>
                                            <span style="font-size: 0.88rem; font-weight: 800; color: #2563eb;">
                                                <?= number_format($bData['entered'], 2) ?> Gal
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="stat-dropdown-footer">
                                    <span>Total acumulado: <strong
                                            style="color: #2563eb;"><?= number_format($totalLiquidGallons, 2) ?>
                                            Gal</strong></span>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <span
                            style="font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; background: rgba(37, 99, 235, 0.12); color: #2563eb; border: 1px solid rgba(37, 99, 235, 0.25);">Gal</span>
                    <?php endif; ?>
                </div>

                <div class="stat-value" style="color: #2563eb;"><?= number_format($totalLiquidGallons, 2) ?> Gal</div>

                <div style="font-size: 0.8rem; color: var(--text-muted, #64748b); font-weight: 600; margin-top: 6px;">
                    <?= count($liquidBreakdown) ?> tipo(s) de combustible
                </div>
            </div>

            <!-- Card 2: Stock Remanente (Líquidos) -->
            <div class="stat-card" style="border-left: 3px solid #10b981 !important;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                    <span
                        class="stat-label"><?= $hasGnv ? 'Stock Remanente (Líquidos)' : 'Stock Remanente en Lotes' ?></span>
                    <span
                        style="font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25);">Disponible</span>
                </div>
                <div class="stat-value" style="color: #10b981;"><?= number_format($totalLiquidRemaining, 2) ?> Gal</div>
                <div style="font-size: 0.8rem; color: var(--text-muted, #64748b); font-weight: 600; margin-top: 6px;">
                    Saldo en <strong style="color: #10b981;"><?= $activeLiquidLots ?> lote(s)</strong> activos
                </div>
            </div>
        <?php endif; ?>

        <!-- Card 3: Total GNV Ingresado (Condicional si hay GNV) -->
        <?php if ($hasGnv && $totalGnvQuantity > 0): ?>
            <div class="stat-card" style="border-left: 3px solid #0284c7 !important;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                    <span class="stat-label">Total GNV Ingresado</span>
                    <span
                        style="font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; background: rgba(2, 132, 199, 0.12); color: #0284c7; border: 1px solid rgba(2, 132, 199, 0.25);">m³</span>
                </div>
                <div class="stat-value" style="color: #0284c7;"><?= number_format($totalGnvQuantity, 2) ?> m³</div>
                <div style="font-size: 0.8rem; color: var(--text-muted, #64748b); font-weight: 600; margin-top: 6px;">
                    Remanente: <strong
                        style="color: <?= $totalGnvRemaining > 0 ? '#0284c7' : '#ef4444' ?>;"><?= number_format($totalGnvRemaining, 2) ?>
                        m³</strong>
                </div>
            </div>
        <?php endif; ?>

        <!-- Card 4: Lotes con Stock Activo -->
        <div class="stat-card" style="border-left: 3px solid #8b5cf6 !important;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2px;">
                <span class="stat-label">Lotes con Stock Activo</span>
                <span
                    style="font-size: 0.72rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; background: rgba(139, 92, 246, 0.12); color: #8b5cf6; border: 1px solid rgba(139, 92, 246, 0.25);">En
                    uso</span>
            </div>
            <div class="stat-value" style="color: #8b5cf6;"><?= $activeLots ?> Lotes</div>
            <div style="font-size: 0.8rem; color: var(--text-muted, #64748b); font-weight: 600; margin-top: 6px;">
                <?= $exhaustedLots ?> lote(s) agotado(s) histórico
            </div>
        </div>
    </section>

    <!-- Formulario / Panel de Búsqueda y Filtros Multicriterio -->
    <section class="search-filter-card"
        style="margin-bottom: 24px; background: var(--card-bg, #ffffff); border: 1px solid var(--border-color, #e2e8f0); border-radius: 12px; padding: 16px;">
        <!-- Barra de Búsqueda Principal General -->
        <div class="main-search-bar" style="display: flex; gap: 12px; align-items: center;">
            <div class="search-input-group"
                style="position: relative; flex: 1; display: flex; align-items: center; border: 1px solid var(--border-color, #cbd5e1); border-radius: 8px; background: var(--input-bg, #ffffff); transition: border-color 0.2s;">
                <svg class="search-icon"
                    style="position: absolute; left: 14px; color: var(--text-muted, #64748b); pointer-events: none;"
                    width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" id="incomeSearchInput" class="search-input-dark"
                    style="border: none; background: transparent; flex: 1; padding: 12px 10px 12px 42px; outline: none; color: var(--text-color, #0f172a);"
                    placeholder="Buscar por N° Recibo, Proveedor, Tipo de Combustible, Fecha..." autocomplete="off"
                    maxlength="60" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-_/ñÑáéíóúÁÉÍÓÚ\s.,]/g, '')">
                <button type="button" class="clear-search-btn" id="clearGlobalBtn"
                    style="display:none; position: absolute; right: 10px; background: transparent; border: none; font-size: 1.2rem; cursor: pointer; color: var(--text-muted);">&times;</button>
            </div>

            <button type="button" id="toggleFiltersBtn" class="button secondary icon-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                Filtros Avanzados
            </button>
        </div>

        <!-- Panel Plegable de Filtros por Características Específicas -->
        <div id="advancedFiltersPanel" class="advanced-filters-panel"
            style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-color, #e2e8f0);">
            <div class="filters-grid"
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                <!-- Proveedor -->
                <div class="filter-field">
                    <label for="filterSupplier"
                        style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Proveedor
                        / Grifo</label>
                    <select id="filterSupplier" class="filter-select"
                        style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color);">
                        <option value="">-- Todos --</option>
                        <?php
                        $suppliers = [];
                        if (!empty($incomes)) {
                            $suppliers = array_unique(array_map(function ($i) {
                                return $i['supplier']; }, $incomes));
                            sort($suppliers);
                        }
                        foreach ($suppliers as $s): ?>
                            <option value="<?= htmlspecialchars($s) ?>"><?= htmlspecialchars($s) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tipo de Combustible -->
                <div class="filter-field">
                    <label for="filterFuelType"
                        style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Tipo
                        Combustible</label>
                    <select id="filterFuelType" class="filter-select"
                        style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color);">
                        <option value="">-- Todos --</option>
                        <?php
                        $fuels = [];
                        if (!empty($incomes)) {
                            $fuels = array_unique(array_map(function ($i) {
                                return $i['fuel_type'] ?? 'Diesel B5 S-50'; }, $incomes));
                            sort($fuels);
                        }
                        foreach ($fuels as $f): ?>
                            <option value="<?= htmlspecialchars($f) ?>"><?= htmlspecialchars($f) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Estado -->
                <div class="filter-field">
                    <label for="filterStatus"
                        style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Estado
                        del Lote</label>
                    <select id="filterStatus" class="filter-select"
                        style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color);">
                        <option value="">-- Todos --</option>
                        <option value="Disponible">Disponible (con stock)</option>
                        <option value="Agotado">Agotado (sin stock)</option>
                    </select>
                </div>

                <!-- Fecha Desde -->
                <div class="filter-field">
                    <label for="filterDateFrom"
                        style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Fecha
                        Desde</label>
                    <div style="position: relative;">
                        <input type="date" id="filterDateFrom" class="filter-input"
                            style="width: 100%; height: 38px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); box-sizing: border-box; padding: 0 36px 0 12px;">
                        <span
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--text-muted,#64748b);">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Fecha Hasta -->
                <div class="filter-field">
                    <label for="filterDateTo"
                        style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 6px;">Fecha
                        Hasta</label>
                    <div style="position: relative;">
                        <input type="date" id="filterDateTo" class="filter-input"
                            style="width: 100%; height: 38px; border-radius: 6px; border: 1px solid var(--border-color); background: var(--input-bg); color: var(--text-color); box-sizing: border-box; padding: 0 36px 0 12px;">
                        <span
                            style="position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--text-muted,#64748b);">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <div class="filters-actions" style="margin-top: 16px; text-align: right;">
                <button type="button" class="button secondary small-btn" id="clearAllFiltersBtn"
                    style="padding: 6px 12px; border-radius: 6px; background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); cursor: pointer;">Limpiar
                    Todos los Filtros</button>
            </div>
        </div>
    </section>

    <!-- Tabla de Ingresos -->
    <section class="table-card">
        <div class="table-header-row">
            <div>
                <h2 style="margin: 0;">Ingresos Registrados</h2>
                <p class="section-desc" style="margin: 4px 0 0; color: var(--text-muted, #64748b); font-size: 0.9rem;">
                    Resultados filtrados según tu búsqueda</p>
            </div>
            <div class="limit-selector-wrap">
                <label for="limitSelector" style="font-size: 0.9rem;">Mostrar:</label>
                <select id="limitSelector" class="limit-select-native" onchange="changeLimit()">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="table-wrap" style="overflow-x: auto;">
            <table class="table-dark">
                <thead>
                    <tr>
                        <th>N° Recibo / Lote</th>
                        <th>Fecha y Hora</th>
                        <th>Proveedor / Grifo</th>
                        <th>Tipo Combustible</th>
                        <th>Cantidad Inicial</th>
                        <th>Stock Remanente</th>
                        <th>Observaciones</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="incomeTableBody">
                    <?php if (!empty($incomes)): ?>
                        <?php foreach ($incomes as $inc): ?>
                            <?php
                            $rem = (float) ($inc['gallons_remaining'] ?? 0);
                            $isAvailable = $rem > 0;
                            // Precomputar valores crudos para facilitar el filtro JS
                            $rawDate = date('Y-m-d', strtotime($inc['created_at']));
                            $rawSupplier = strtolower(trim($inc['supplier']));
                            $rawFuel = strtolower(trim($inc['fuel_type'] ?? 'Diesel B5 S-50'));
                            $rawStatus = $isAvailable ? 'disponible' : 'agotado';
                            ?>
                            <tr class="income-row" style="cursor: pointer;"
                                onclick="openIncomeModal(<?= htmlspecialchars(json_encode($inc)) ?>)"
                                data-date="<?= $rawDate ?>" data-supplier="<?= htmlspecialchars($rawSupplier) ?>"
                                data-fuel="<?= htmlspecialchars($rawFuel) ?>" data-status="<?= $rawStatus ?>">
                                <td>
                                    <span class="receipt-tag">
                                        <?= htmlspecialchars($inc['receipt_number']) ?>
                                    </span>
                                </td>
                                <td style="font-size: 0.9rem; color: var(--text-muted, #64748b);">
                                    <?= date('d/m/Y H:i', strtotime($inc['created_at'])) ?>
                                </td>
                                <td style="font-weight: 600; color: var(--text-color, #0f172a);">
                                    <?= htmlspecialchars($inc['supplier']) ?>
                                </td>
                                <td>
                                    <span class="fuel-type-pill">
                                        <?= htmlspecialchars($inc['fuel_type'] ?? 'Diesel B5 S-50') ?>
                                    </span>
                                </td>
                                <td style="font-weight: 700; color: #2563eb;">
                                    <?= number_format((float) $inc['gallons'], 2) ?>
                                    <?= htmlspecialchars(\App\Models\Catalog::getFuelUnit($inc['fuel_type'] ?? '', true)) ?>
                                </td>
                                <td style="font-weight: 800; color: <?= $isAvailable ? '#10b981' : '#ef4444' ?>;">
                                    <?= number_format($rem, 2) ?>
                                    <?= htmlspecialchars(\App\Models\Catalog::getFuelUnit($inc['fuel_type'] ?? '', true)) ?>
                                </td>
                                <td>
                                    <?php
                                    $obs = trim($inc['notes'] ?? '');
                                    if (empty($obs) || $obs === '-'):
                                        ?>
                                        <span style="font-size: 0.85rem; color: var(--text-muted, #64748b);">-</span>
                                    <?php elseif (mb_strlen($obs) > 25): ?>
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <span
                                                style="font-size: 0.85rem; color: var(--text-muted, #64748b); max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                                title="<?= htmlspecialchars($obs) ?>">
                                                <?= htmlspecialchars($obs) ?>
                                            </span>
                                            <button type="button" class="info-obs-btn" title="Ver observación completa"
                                                onclick="event.stopPropagation(); showObsModal(<?= htmlspecialchars(json_encode($obs)) ?>)">
                                                <svg class="ui-icon">
                                                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-info"></use>
                                                </svg> INFO
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span
                                            style="font-size: 0.85rem; color: var(--text-muted, #64748b);"><?= htmlspecialchars($obs) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-pill <?= $isAvailable ? 'status-available' : 'status-exhausted' ?>">
                                        <?= $isAvailable ? 'Disponible' : 'Agotado' ?>
                                    </span>
                                </td>
                                <td style="white-space: nowrap;">
                                    <button type="button"
                                        style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 10px; background: transparent; border: 1px solid var(--line, #334155); color: var(--ink); border-radius: 7px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background .15s, border-color .15s;"
                                        onmouseover="this.style.background='var(--hover-bg, rgba(255,255,255,.07))'"
                                        onmouseout="this.style.background='transparent'"
                                        onclick="event.stopPropagation(); openIncomeModal(<?= htmlspecialchars(json_encode($inc)) ?>)">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                        Ver Detalle
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="noIncomesRow">
                            <td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted, #64748b);">
                                No hay ingresos registrados en el sistema.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="9" style="text-align: center; padding: 30px; color: var(--text-muted, #64748b);">
                            <span class="icon-with-text"><svg class="ui-icon">
                                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-search"></use>
                                </svg>No se encontraron ingresos que coincidan con la búsqueda.</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination-container">
            <div class="pagination-info">
                Mostrando página <span id="currentPageInfo">1</span> de <span id="totalPagesInfo">1</span>
                (<span id="totalResultsInfo">0</span> resultados)
            </div>
            <div class="pagination-controls">
                <button type="button" class="button secondary small-btn" id="prevPageBtn"
                    onclick="changePage(currentPage - 1)">Anterior</button>
                <div id="pageButtons" style="display: flex; gap: 5px;"></div>
                <button type="button" class="button secondary small-btn" id="nextPageBtn"
                    onclick="changePage(currentPage + 1)">Siguiente</button>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detalle del Ingreso -->
<div id="incomeModal" class="modal-overlay" onclick="closeIncomeModal(event)">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="icon-with-text"><svg class="ui-icon">
                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use>
                </svg>Detalle del Ingreso de Combustible</h3>
        </div>
        <div class="modal-body" id="incomeModalBody">
            <!-- Inyectado por JavaScript -->
        </div>
        <div class="modal-footer">
            <button class="button secondary" onclick="closeIncomeModal()">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal Emergente Exclusivo para Observación Completa (INFO) en Ingresos -->
<div id="obsInfoModal" class="modal-overlay" onclick="closeObsModal(event)">
    <div class="modal-card" style="max-width: 480px;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="icon-with-text"><svg class="ui-icon">
                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-note"></use>
                </svg>Observación Completa</h3>
        </div>
        <div class="modal-body">
            <div style="background: var(--bg-body, rgba(255,255,255,0.05)); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); font-size: 0.95rem; line-height: 1.5; color: var(--text-color, inherit); word-wrap: break-word;"
                id="obsModalContent">
            </div>
        </div>
        <div class="modal-footer">
            <button class="button secondary" onclick="closeObsModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let currentLimit = 10;
    let allIncomeRows = [];

    document.addEventListener("DOMContentLoaded", () => {
        allIncomeRows = Array.from(document.querySelectorAll("#incomeTableBody tr.income-row"));

        const searchInput = document.getElementById("incomeSearchInput");
        const clearGlobalBtn = document.getElementById("clearGlobalBtn");
        const toggleFiltersBtn = document.getElementById("toggleFiltersBtn");
        const advancedFiltersPanel = document.getElementById("advancedFiltersPanel");
        const clearAllFiltersBtn = document.getElementById("clearAllFiltersBtn");
        const filterSelectsInputs = document.querySelectorAll(".filter-select, .filter-input");

        // Convertir todos los <select class="filter-select"> en custom dropdowns visuales
        document.querySelectorAll('.filter-select').forEach(select => {
            initFilterCustomDropdown(select);
        });

        // Toggle panel
        if (toggleFiltersBtn) {
            toggleFiltersBtn.addEventListener("click", () => {
                const isHidden = advancedFiltersPanel.style.display === "none";
                advancedFiltersPanel.style.display = isHidden ? "block" : "none";
            });
        }

        // Search text input
        if (searchInput) {
            searchInput.addEventListener("input", () => {
                clearGlobalBtn.style.display = searchInput.value.length > 0 ? "block" : "none";
                currentPage = 1;
                renderIncomeTable();
            });
        }

        // Clear search text
        if (clearGlobalBtn) {
            clearGlobalBtn.addEventListener("click", () => {
                searchInput.value = "";
                clearGlobalBtn.style.display = "none";
                currentPage = 1;
                renderIncomeTable();
            });
        }

        // Advanced filter changes
        filterSelectsInputs.forEach(el => {
            el.addEventListener("change", () => {
                currentPage = 1;
                renderIncomeTable();
            });
            if (el.tagName === "INPUT" && el.type === "date") {
                el.addEventListener("input", () => {
                    currentPage = 1;
                    renderIncomeTable();
                });
            }
        });

        // Clear all filters
        if (clearAllFiltersBtn) {
            clearAllFiltersBtn.addEventListener("click", () => {
                searchInput.value = "";
                clearGlobalBtn.style.display = "none";
                filterSelectsInputs.forEach(el => {
                    if (el.tagName === "SELECT") {
                        // Resetear select y notificar al custom dropdown visual
                        el.selectedIndex = 0;
                        el.dispatchEvent(new Event("change", { bubbles: true }));
                    } else if (el._flatpickr) {
                        // Usar API de Flatpickr para fechas (evita el reflow/kickback)
                        el._flatpickr.clear();
                    } else {
                        el.value = "";
                    }
                });
                currentPage = 1;
                renderIncomeTable();
            });
        }

        renderIncomeTable();
    });

    function getFilteredIncomeRows() {
        const term = document.getElementById("incomeSearchInput").value.toLowerCase().trim();
        const fSupplier = document.getElementById("filterSupplier").value.toLowerCase();
        const fFuel = document.getElementById("filterFuelType").value.toLowerCase();
        const fStatus = document.getElementById("filterStatus").value.toLowerCase();
        const fDateFrom = document.getElementById("filterDateFrom").value;
        const fDateTo = document.getElementById("filterDateTo").value;

        return allIncomeRows.filter(row => {
            // 1. Text filter
            if (term && !row.innerText.toLowerCase().includes(term)) return false;

            // 2. Advanced filters
            if (fSupplier && row.dataset.supplier !== fSupplier) return false;
            if (fFuel && row.dataset.fuel !== fFuel) return false;
            if (fStatus && row.dataset.status !== fStatus) return false;

            const rowDate = row.dataset.date;
            if (fDateFrom && rowDate < fDateFrom) return false;
            if (fDateTo && rowDate > fDateTo) return false;

            return true;
        });
    }

    function renderIncomeTable() {
        const filtered = getFilteredIncomeRows();
        const totalResults = filtered.length;
        const totalPages = Math.max(1, Math.ceil(totalResults / currentLimit));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        // Ocultar todas las filas primero
        allIncomeRows.forEach(row => row.style.display = "none");

        const noResultsRow = document.getElementById("noResultsRow");
        if (noResultsRow) noResultsRow.style.display = "none";

        if (totalResults === 0) {
            if (noResultsRow) noResultsRow.style.display = "";
        } else {
            const start = (currentPage - 1) * currentLimit;
            const pageRows = filtered.slice(start, start + currentLimit);
            pageRows.forEach(row => row.style.display = "");
        }

        // Info de paginación
        document.getElementById("currentPageInfo").textContent = currentPage;
        document.getElementById("totalPagesInfo").textContent = totalPages;
        document.getElementById("totalResultsInfo").textContent = totalResults;

        const prevBtn = document.getElementById("prevPageBtn");
        const nextBtn = document.getElementById("nextPageBtn");
        prevBtn.disabled = currentPage <= 1;
        nextBtn.disabled = currentPage >= totalPages;

        renderPageButtons(currentPage, totalPages);
    }

    function renderPageButtons(page, totalPages) {
        const pageButtons = document.getElementById("pageButtons");
        pageButtons.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || Math.abs(i - page) <= 2) {
                const btn = document.createElement("button");
                btn.type = "button";
                btn.className = `button ${i === page ? 'primary' : 'secondary'} small-btn`;
                btn.textContent = i;
                btn.onclick = () => changePage(i);
                pageButtons.appendChild(btn);
            } else if (Math.abs(i - page) === 3) {
                const span = document.createElement("span");
                span.style.padding = "5px";
                span.style.color = "var(--text-muted, #64748b)";
                span.textContent = "...";
                pageButtons.appendChild(span);
            }
        }
    }

    function changePage(page) {
        currentPage = page;
        renderIncomeTable();
    }

    function changeLimit() {
        currentLimit = parseInt(document.getElementById("limitSelector").value, 10);
        currentPage = 1;
        renderIncomeTable();
    }

    function showObsModal(obsText) {
        document.getElementById("obsModalContent").textContent = obsText;
        document.getElementById("obsInfoModal").classList.add("active");
    }

    function closeObsModal(e) {
        document.getElementById("obsInfoModal").classList.remove("active");
    }

    /**
     * Convierte un <select class="filter-select"> en un dropdown visual (.custom-select-container).
     */
    function initFilterCustomDropdown(selectEl) {
        if (!selectEl || selectEl.dataset.customDropdown === 'true') return;
        selectEl.dataset.customDropdown = 'true';

        selectEl.style.display = 'none';

        const container = document.createElement('div');
        container.className = 'custom-select-container';
        selectEl.parentNode.insertBefore(container, selectEl);
        container.appendChild(selectEl);

        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.tabIndex = 0;
        trigger.setAttribute('role', 'combobox');
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');

        const menu = document.createElement('div');
        menu.className = 'custom-select-menu';
        menu.setAttribute('role', 'listbox');

        container.appendChild(trigger);
        container.appendChild(menu);

        function getPlaceholderText() {
            const first = selectEl.options[0];
            return (first && first.value === '') ? first.text : '-- Seleccionar --';
        }

        function renderTrigger() {
            const selected = selectEl.options[selectEl.selectedIndex];
            const isPlaceholder = !selected || selected.value === '';
            const labelText = isPlaceholder ? getPlaceholderText() : selected.text;
            const labelColor = isPlaceholder
                ? 'var(--text-muted, #94a3b8)'
                : 'var(--text-color, #0f172a)';

            trigger.innerHTML = `
                <div class="custom-select-selected-content" style="flex:1;min-width:0;">
                    <span class="custom-select-label-text" style="color:${labelColor};">${escapeHtml(labelText)}</span>
                </div>
                ${!isPlaceholder ? `
                <span class="custom-select-clear-btn" title="Limpiar" data-clear="1"
                    style="flex-shrink:0;margin-right:4px;cursor:pointer;color:var(--text-muted,#94a3b8);display:flex;align-items:center;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </span>` : ''}
                <span class="custom-select-chevron">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m6 9 6 6 6-6"/>
                    </svg>
                </span>
            `;

            const clearBtn = trigger.querySelector('[data-clear="1"]');
            if (clearBtn) {
                clearBtn.addEventListener('click', e => {
                    e.stopPropagation();
                    selectEl.selectedIndex = 0;
                    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                    container.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                    renderTrigger();
                    renderMenu();
                });
            }
        }

        function renderMenu() {
            menu.innerHTML = '';
            Array.from(selectEl.options).forEach((opt, idx) => {
                const isSelected = idx === selectEl.selectedIndex;
                const isPlaceholder = opt.value === '';

                const item = document.createElement('div');
                item.className = 'custom-select-item' + (isSelected ? ' selected' : '');
                item.setAttribute('role', 'option');
                item.setAttribute('aria-selected', String(isSelected));

                const textColor = isPlaceholder
                    ? 'var(--text-muted, #94a3b8)'
                    : (isSelected ? 'var(--primary, #2563eb)' : 'var(--text-color, #0f172a)');

                item.innerHTML = `
                    <span class="item-title" style="font-size:0.88rem;font-weight:600;color:${textColor};">
                        ${escapeHtml(opt.text)}
                    </span>
                    ${isSelected && !isPlaceholder
                        ? `<span class="item-check"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"></path></svg></span>`
                        : ''}
                `;

                item.addEventListener('click', e => {
                    e.stopPropagation();
                    selectEl.selectedIndex = idx;
                    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                    container.classList.remove('open');
                    trigger.setAttribute('aria-expanded', 'false');
                    renderTrigger();
                    renderMenu();
                });

                menu.appendChild(item);
            });
        }

        trigger.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = container.classList.toggle('open');
            trigger.setAttribute('aria-expanded', String(isOpen));
            if (isOpen) renderMenu();
        });

        trigger.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); trigger.click(); }
            if (e.key === 'Escape') {
                container.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('click', e => {
            if (!container.contains(e.target)) {
                container.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        selectEl.addEventListener('change', () => {
            renderTrigger();
            renderMenu();
        });

        renderTrigger();
    }

    function openIncomeModal(inc) {
        const body = document.getElementById("incomeModalBody");
        const dateObj = new Date(String(inc.created_at || '').replace(' ', 'T'));
        const dateStr = !isNaN(dateObj) ? dateObj.toLocaleString('es-PE') : inc.created_at;

        const invoiceText = inc.invoice_number && inc.invoice_number.trim() !== '' ? inc.invoice_number : 'No registrada';
        const notesText = inc.notes && inc.notes.trim() !== '' && inc.notes !== '-' ? inc.notes : 'Sin observaciones';

        const rem = parseFloat(inc.gallons_remaining || 0);
        const isAvailable = rem > 0;
        const statusText = isAvailable ? 'Disponible' : 'Agotado';
        const statusColor = isAvailable ? '#10b981' : '#ef4444';
        const isGnv = (inc.fuel_type || '').toLowerCase().includes('gnv') || (inc.fuel_type || '').toLowerCase().includes('gas natural');
        const unitLabel = isGnv ? 'm³' : 'Galones';

        body.innerHTML = `
            <div class="modal-detail-card">
                <div class="modal-badge-row">
                    <span class="modal-date-tag icon-with-text">${uiIcon('calendar')}Fecha y Hora: <strong>${escapeHtml(dateStr)}</strong></span>
                </div>
                
                <h4 style="margin-top: 10px; font-size: 1.15rem; color: var(--primary);">N° Recibo / Lote: ${escapeHtml(inc.receipt_number)}</h4>

                <div class="modal-grid">
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('truck')}Proveedor / Grifo:</span>
                        <strong>${escapeHtml(inc.supplier)}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('fuel')}Tipo de Combustible:</span>
                        <strong style="color: var(--primary);">${escapeHtml(inc.fuel_type || 'Diesel B5 S-50')}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('note')}N° de Factura:</span>
                        <strong>${escapeHtml(invoiceText)}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('info')}Estado:</span>
                        <strong style="color: ${statusColor};">${statusText}</strong>
                    </div>
                    
                    <div class="modal-field" style="grid-column: span 2; background: var(--bg-body); padding: 10px 12px; border-radius: 8px; border: 1px dashed var(--border-color, var(--line)); margin-top: 4px; overflow-wrap: break-word; word-break: break-word; hyphens: auto;">
                        <span class="lbl icon-with-text">${uiIcon('note')}Observaciones:</span>
                        <span style="color: var(--ink); font-style: italic; font-weight: 500; font-size: 0.92em; margin-top: 4px; display: block; word-break: break-word; white-space: pre-wrap;">${escapeHtml(notesText)}</span>
                    </div>

                    <div class="modal-field highlight-field" style="background: rgba(37, 99, 235, 0.05);">
                        <span class="lbl icon-with-text">${uiIcon('fuel')}Cantidad Inicial:</span>
                        <strong class="qty-val">${parseFloat(inc.gallons).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${unitLabel}</strong>
                    </div>
                    
                    <div class="modal-field highlight-field" style="background: rgba(16, 185, 129, 0.05);">
                        <span class="lbl icon-with-text">${uiIcon('fuel')}Stock Remanente:</span>
                        <strong class="qty-val" style="color: ${statusColor};">${rem.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${unitLabel}</strong>
                    </div>
                </div>
            </div>
        `;

        document.getElementById("incomeModal").classList.add("active");
    }

    function closeIncomeModal(e) {
        document.getElementById("incomeModal").classList.remove("active");
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Limpiar la URL para evitar que el mensaje reaparezca al presionar F5 (recargar)
    if (window.history.replaceState) {
        const url = new URL(window.location);
        if (url.searchParams.has('success') || url.searchParams.has('error')) {
            url.searchParams.delete('success');
            url.searchParams.delete('error');
            window.history.replaceState(null, null, url.toString());
        }
    }

    // Control del menú desplegable de desglose por combustible líquido
    function toggleLiquidDropdown(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const menu = document.getElementById("liquidDropdownMenu");
        const btn = document.getElementById("liquidDropdownBtn");
        const card = btn ? btn.closest(".stat-card") : null;
        if (!menu) return;
        const isVisible = menu.style.display === "block";
        menu.style.display = isVisible ? "none" : "block";
        if (card) {
            card.classList.toggle("dropdown-open", !isVisible);
            card.style.zIndex = isVisible ? "" : "100";
        }
        if (btn) {
            const svg = btn.querySelector("svg");
            if (svg) svg.style.transform = isVisible ? "" : "rotate(180deg)";
        }
    }

    document.addEventListener("click", (e) => {
        const menu = document.getElementById("liquidDropdownMenu");
        const btn = document.getElementById("liquidDropdownBtn");
        if (menu && menu.style.display === "block") {
            if (!menu.contains(e.target) && (!btn || !btn.contains(e.target))) {
                menu.style.display = "none";
                const card = btn ? btn.closest(".stat-card") : null;
                if (card) {
                    card.classList.remove("dropdown-open");
                    card.style.zIndex = "";
                }
                if (btn) {
                    const svg = btn.querySelector("svg");
                    if (svg) svg.style.transform = "";
                }
            }
        }
    });

</script>