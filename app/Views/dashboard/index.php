<style>
    /* Fix para mantener fija la botonera del gráfico arriba a la derecha */
    .card-header-flex.chart-header-wrap {
        display: flex !important;
        justify-content: space-between !important;
        align-items: flex-start !important;
        /* Mantiene los botones alineados al tope superior */
        flex-wrap: nowrap !important;
        /* Evita que la botonera caiga al siguiente renglón */
        gap: 16px !important;
    }

    /* Contenedor del título y subtítulo */
    .card-header-flex.chart-header-wrap>div:first-child {
        flex: 1 1 auto !important;
        min-width: 0 !important;
        /* Fuerza al texto largo a ajustarse al espacio disponible */
    }

    /* Contenedor de botones (Filtros) */
    .chart-view-switcher {
        flex-shrink: 0 !important;
        /* Impide que la botonera se comprima o desplaze */
        display: flex !important;
        flex-wrap: nowrap !important;
        gap: 6px !important;
    }

    /* Adaptación del panel de gráficos para pantallas medianas y móviles. */
    @media (max-width: 1024px) {
        .visuals-grid {
            grid-template-columns: 1fr !important;
        }
    }

    @media (max-width: 700px) {
        .card-header-flex.chart-header-wrap {
            flex-direction: column !important;
            gap: 12px !important;
        }

        .chart-view-switcher {
            width: 100% !important;
            max-width: 100%;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .chart-view-switcher::-webkit-scrollbar {
            display: none;
        }

        .switcher-btn {
            flex: 1 0 auto;
            padding: 6px 9px;
            font-size: 11px;
            white-space: nowrap;
        }
    }

    @media (max-width: 480px) {
        .chart-container-card {
            padding: 16px;
        }

        .chart-wrapper {
            height: 220px;
        }
    }

    /* Ambas tarjetas ocupan la misma altura. El gráfico usa el espacio
       adicional, evitando un área vacía al pie de la tarjeta izquierda. */
    .visuals-grid {
        align-items: stretch;
    }

    .visuals-grid>.chart-container-card {
        align-self: stretch !important;
    }

    .visuals-grid .chart-wrapper {
        height: auto;
        min-height: 220px;
        flex: 1 1 auto;
    }

    .stock-chart-wrapper.native-stock-chart-wrapper {
        height: auto;
        min-height: 260px;
    }

    .native-stock-chart {
        display: flex;
        flex-direction: column;
        gap: 16px;
        height: 100%;
    }

    .native-stock-total span,
    .native-stock-row-header,
    .native-stock-row-value {
        color: var(--text-muted, #64748b);
    }

    .native-stock-total span {
        display: block;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .native-stock-total strong {
        display: block;
        margin-top: 2px;
        color: var(--text-color, #1e293b);
        font-size: 1.35rem;
        line-height: 1.2;
    }

    .native-stock-bars {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .native-stock-row-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 5px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .native-stock-row-header strong {
        color: var(--text-color, #1e293b);
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .native-stock-row-value {
        flex: 0 0 auto;
    }

    .native-stock-track {
        height: 10px;
        overflow: hidden;
        border-radius: 999px;
        background: var(--bg-body, #e2e8f0);
    }

    .native-stock-fill {
        display: block;
        height: 100%;
        border-radius: inherit;
        transition: width 0.35s ease;
    }

    .native-stock-row-gallons {
        display: block;
        margin-top: 4px;
        color: var(--text-muted, #64748b);
        font-size: 0.72rem;
        text-align: right;
    }

    .native-stock-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 7px 12px;
        margin: auto 0 0;
        padding: 12px 0 0;
        border-top: 1px solid var(--border-color, #e2e8f0);
        list-style: none;
    }

    .native-stock-legend li {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--text-muted, #64748b);
        font-size: 0.72rem;
    }

    .native-stock-legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    /* Estilos de alto contraste para badges, pills y etiquetas en modo oscuro */
    .badge,
    .status-pill,
    .receipt-tag,
    .fuel-type-pill,
    .event-sub .badge {
        color: #000000 !important;
        font-weight: 700 !important;
        letter-spacing: 0.3px;
    }

    /* Ajuste de colores de fondo para legibilidad impecable con texto negro */
    .badge-success,
    .status-completed {
        background-color: #4ade80 !important;
        /* Verde claro vibrante */
        color: #000000 !important;
    }

    .badge-info {
        background-color: #38bdf8 !important;
        /* Celeste / Cían claro */
        color: #000000 !important;
    }

    .badge-purple {
        background-color: #c084fc !important;
        /* Púrpura claro */
        color: #000000 !important;
    }

    .badge-warning,
    .badge-fuel {
        background-color: #facc15 !important;
        /* Amarillo radiante */
        color: #000000 !important;
    }

    /* Badge INGRESO DE STOCK — fondo verde sólido, texto blanco para máxima visibilidad */
    .badge-stock,
    .event-sub .badge-stock,
    .agenda-title-row .badge-stock {
        background-color: #16a34a !important;
        color: #ffffff !important;
        border: 1px solid #15803d !important;
    }

    .receipt-tag {
        background-color: #60a5fa !important;
        /* Azul claro de alto contraste */
        color: #000000 !important;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
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

    .fuel-type-pill {
        background-color: #cbd5e1 !important;
        color: #000000 !important;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* Card GNV — cuando el grid tiene 6 tarjetas ampliar a 6 columnas */
    @media (min-width: 1200px) {
        .stats-grid.has-gnv {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    /* Ícono GNV con color cyan */
    .icon-gnv {
        background: rgba(2, 132, 199, 0.12);
        color: #0284c7;
    }

    /* Badge GNV */
    .badge-gnv {
        background: rgba(2, 132, 199, 0.15);
        color: #0284c7;
        border: 1px solid rgba(2, 132, 199, 0.30);
    }
</style>

<div class="dashboard-wrapper">

    <?php
    $__userName = trim((string) ($_SESSION['user']['name'] ?? 'Usuario'));
    $__firstName = explode(' ', $__userName)[0];

    $__diasSemana = [0 => 'Domingo', 1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles', 4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado'];
    $__meses = [1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'];
    $__fechaTexto = $__diasSemana[(int) date('w')] . ' ' . (int) date('j') . ' de ' . $__meses[(int) date('n')] . ' de ' . date('Y');
    ?>

    <!-- Encabezado / Mensaje de bienvenida + Botones de Acción Rápida -->
    <div
        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0; color: var(--text-color, #1e293b);">
                <span id="dashboardGreeting" class="icon-with-text"
                    data-user-name="<?= htmlspecialchars($__firstName, ENT_QUOTES, 'UTF-8') ?>">Buenos días,
                    <?= htmlspecialchars($__firstName) ?><span class="greeting-effect"><svg
                            class="ui-icon greeting-wave" role="img" aria-label="Saludo">
                            <use href="<?= BASE_URL ?>assets/icons.svg#icon-greeting"></use>
                        </svg></span></span>
            </h1>
            <p
                style="font-size: 0.9rem; color: var(--text-muted, #64748b); margin-top: 4px; text-transform: capitalize;">
                <?= htmlspecialchars($__fechaTexto) ?> — Panel General
            </p>
        </div>

        <div style="display: flex; gap: 10px;">
            <a class="button secondary" href="<?= BASE_URL ?>despachos/nuevo"
                style="background: #3b82f6; color: #ffffff; padding: 10px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
                Registrar Despacho
            </a>
            <a class="button primary" href="<?= BASE_URL ?>ingresos/nuevo">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
                Registrar Ingreso
            </a>
        </div>
    </div>

    <!-- Indicadores Principales / Dashboard Stats -->
    <?php
    $renderTrend = function (?array $trend): string {
        if ($trend === null) {
            return '';
        }
        $pct = number_format($trend['pct'], 1);
        $label = htmlspecialchars($trend['label']);
        $color = '#64748b';
        $arrow = '▬';
        $text = 'Sin cambios';
        if ($trend['direction'] === 'up') {
            $color = '#16a34a';
            $arrow = '▲';
            $text = $pct . '%';
        } elseif ($trend['direction'] === 'down') {
            $color = '#dc2626';
            $arrow = '▼';
            $text = $pct . '%';
        }
        return '<div class="stat-trend" style="display:flex;align-items:center;gap:4px;font-size:0.75rem;margin-top:4px;">'
            . '<strong style="color:' . $color . ';font-weight:700;">' . $arrow . ' ' . $text . '</strong>'
            . '<span style="font-weight:500;color:var(--text-muted,#64748b);">' . $label . '</span>'
            . '</div>';
    };
    ?>

    <section class="stats-grid<?= !empty($hasGnv) && $hasGnv ? ' has-gnv' : '' ?>">
        <!-- 1. Saldo disponible -->
        <article class="stat-card balance-card">
            <div class="stat-header">
                <span class="stat-icon icon-balance">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <rect x="4" y="3" width="16" height="18" rx="4" />
                        <line x1="4" y1="8" x2="20" y2="8" />
                        <line x1="4" y1="16" x2="20" y2="16" />
                        <ellipse cx="12" cy="12" rx="3" ry="2" />
                    </svg>
                </span>
                <span class="badge badge-success">En Stock</span>
            </div>
            <div class="stat-body">
                <strong><?= number_format((float) $saldoDisponible, 2) ?> <small>Gal</small></strong>
                <span>Combustible total disponible</span>
                <?= $renderTrend($saldoTrend) ?>
            </div>
        </article>

        <!-- 2. Total de combustible ingresado -->
        <article class="stat-card">
            <div class="stat-header">
                <span class="stat-icon icon-income">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 19V5M5 12l7-7 7 7"></path>
                    </svg>
                </span>
            </div>
            <div class="stat-body">
                <strong><?= number_format((float) $totalIngresado, 2) ?> <small>Gal</small></strong>
                <span>Total combustible ingresado</span>
                <?= $renderTrend($ingresadoTrend) ?>
            </div>
        </article>

        <!-- 3. Total entregado -->
        <article class="stat-card">
            <div class="stat-header">
                <span class="stat-icon icon-delivered">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12l7 7 7-7"></path>
                    </svg>
                </span>
            </div>
            <div class="stat-body">
                <strong><?= number_format((float) $totalEntregado, 2) ?> <small>Gal</small></strong>
                <span>Total entregado</span>
                <?= $renderTrend($entregadoTrend) ?>
            </div>
        </article>

        <!-- 4. Consumo del día -->
        <article class="stat-card">
            <div class="stat-header">
                <span class="stat-icon icon-today">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                    </svg>
                </span>
                <span class="badge badge-info">Hoy</span>
            </div>
            <div class="stat-body">
                <strong><?= number_format((float) $consumoDia, 2) ?> <small>Gal</small></strong>
                <span>Consumo del día</span>
                <?= $renderTrend($consumoDiaTrend) ?>
            </div>
        </article>

        <!-- 5. Cantidad de recibos del mes -->
        <article class="stat-card">
            <div class="stat-header">
                <span class="stat-icon icon-receipt">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                </span>
                <span class="badge badge-purple">Mes Actual</span>
            </div>
            <div class="stat-body">
                <strong><?= (int) $recibosMes ?></strong>
                <span>Recibos Despachos del mes</span>
                <?= $renderTrend($recibosMesTrend) ?>
            </div>
        </article>

        <?php if (!empty($hasGnv) && $hasGnv): ?>
        <!-- 6. GNV Ingresado (solo visible si hay ingresos GNV) -->
        <article class="stat-card" style="border-left: 3px solid #0284c7;">
            <div class="stat-header">
                <span class="stat-icon icon-gnv">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M12 22c5.523 0 9-3.582 9-8 0-3-1.5-5.5-4-7 .5 2-1 4-3 4-1.5 0-2.5-1-2.5-2.5C11.5 7 10 5.5 8 5c1 2.5-.5 5-2 6.5C4.5 13 4 14.5 4 16c0 3.314 3.582 6 8 6z"/>
                    </svg>
                </span>
                <span class="badge badge-gnv">GNV</span>
            </div>
            <div class="stat-body">
                <strong><?= number_format((float) $gnvIngresado, 2) ?> <small>m³</small></strong>
                <span>Total GNV ingresado</span>
                <span style="font-size: 0.98rem; margin-top: 4px;"><strong style="font-weight: 700; color: var(--text-color, #1e293b); font-size: 0.98rem;"><?= number_format((float) $gnvRemaining, 2) ?> m³</strong> disponibles</span>
            </div>
        </article>
        <?php endif; ?>
    </section>

    <!-- Visualizaciones: Gráfico Dinámico Multivista y Resumen de Stock por Tipo -->
    <section class="visuals-grid"
        style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
        <article class="chart-container-card">
            <div class="card-header-flex chart-header-wrap" style="gap: 12px; align-items: center; justify-content: space-between;">
                <div>
                    <h2 id="chartTitle">Combustible Despachado Semanal</h2>
                    <p class="section-desc" id="chartSubtitle">Volumen diario consumido (últimos 7 días)</p>
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                    <!-- Filtro de Unidad / Energía -->
                    <div class="energy-filter-switcher" role="radiogroup" aria-label="Filtro de Energía">
                        <button type="button" class="energy-btn active" data-energy="all"
                            onclick="switchEnergyFilter(event, 'all')" title="Ver todos los tipos">Todos</button>
                        <button type="button" class="energy-btn" data-energy="liquid"
                            onclick="switchEnergyFilter(event, 'liquid')" title="Solo combustibles líquidos (Gal)">Líquidos (Gal)</button>
                        <?php if (!empty($hasGnv)): ?>
                        <button type="button" class="energy-btn" data-energy="gnv"
                            onclick="switchEnergyFilter(event, 'gnv')" title="Solo Gas Natural (m³)">GNV (m³)</button>
                        <?php endif; ?>
                    </div>
                    <!-- Selector de Vista Temporal / Criterio -->
                    <div class="chart-view-switcher" role="tablist">
                        <button type="button" class="switcher-btn active" data-view="semanal"
                            onclick="switchChartView(event, 'semanal')">Semanal</button>
                        <button type="button" class="switcher-btn" data-view="tipo"
                            onclick="switchChartView(event, 'tipo')">Por Tipo</button>
                        <button type="button" class="switcher-btn" data-view="mensual"
                            onclick="switchChartView(event, 'mensual')">Mensual</button>
                        <button type="button" class="switcher-btn" data-view="anual"
                            onclick="switchChartView(event, 'anual')">Anual</button>
                        <button type="button" class="switcher-btn" data-view="area"
                            onclick="switchChartView(event, 'area')">Por Área</button>
                    </div>
                </div>
            </div>

            <div class="chart-wrapper">
                <div id="dailyFuelChart" class="echarts-chart" role="img"
                    aria-label="Gráfico de combustible despachado"></div>
            </div>
            <p id="chartInteractionStatus" class="sr-only" aria-live="polite"></p>
        </article>

        <!-- Resumen nativo de stock disponible por tipo -->
        <?php
        $stockItems = array_map(
            static fn($item) => [
                'label' => (string) ($item['label'] ?? 'Sin tipo'),
                'value' => max(0, (float) ($item['value'] ?? 0)),
            ],
            $stockByFuelType ?? []
        );

        $isGnvFuel = static function (string $fuelType): bool {
            $type = mb_strtolower($fuelType);
            return str_contains($type, 'gnv') || str_contains($type, 'gas natural');
        };

        $liquidStockItems = [];
        $gnvStockItems = [];
        foreach ($stockItems as $stockItem) {
            if ($isGnvFuel($stockItem['label'])) {
                $gnvStockItems[] = $stockItem;
            } else {
                $liquidStockItems[] = $stockItem;
            }
        }

        $totalLiquidStock = array_sum(array_column($liquidStockItems, 'value'));
        $totalGnvStock = array_sum(array_column($gnvStockItems, 'value'));

        // Paleta de colores únicos: cada tipo de combustible recibe un color distinto,
        // sin repetir, aunque compartan familia (p. ej. Gasohol Premium vs Regular).
        $stockPalette = ['#f59e0b', '#8b5cf6', '#06b6d4', '#2563eb', '#ec4899', '#10b981', '#f43f5e', '#eab308', '#a855f7', '#14b8a6', '#f97316', '#64748b'];
        $stockPreferredColor = static function (string $fuelType): string {
            $type = mb_strtolower($fuelType);

            return match (true) {
                str_contains($type, 'diesel') => '#f59e0b',
                str_contains($type, 'gasohol premium') => '#8b5cf6',
                str_contains($type, 'gasohol regular') => '#ec4899',
                str_contains($type, 'gasohol') => '#a855f7',
                str_contains($type, 'glp') || str_contains($type, 'gas licuado') => '#06b6d4',
                str_contains($type, 'gnv') || str_contains($type, 'gas natural') => '#10b981',
                default => '#64748b',
            };
        };

        $stockColorMap = [];
        $stockUsedColors = [];
        $stockPaletteIndex = 0;
        foreach ($stockItems as $stockItemForColor) {
            $label = $stockItemForColor['label'];
            if (isset($stockColorMap[$label])) {
                continue;
            }
            $preferred = $stockPreferredColor($label);
            if (!in_array($preferred, $stockUsedColors, true)) {
                $color = $preferred;
            } else {
                while (in_array($stockPalette[$stockPaletteIndex % count($stockPalette)], $stockUsedColors, true)) {
                    $stockPaletteIndex++;
                }
                $color = $stockPalette[$stockPaletteIndex % count($stockPalette)];
                $stockPaletteIndex++;
            }
            $stockColorMap[$label] = $color;
            $stockUsedColors[] = $color;
        }
        $stockColorForType = static function (string $fuelType) use ($stockColorMap): string {
            return $stockColorMap[$fuelType] ?? '#64748b';
        };
        ?>
        <article class="chart-container-card stock-summary-card"
            style="background: var(--card-bg, #ffffff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px; display: flex; flex-direction: column; justify-content: flex-start; align-self: start;">
            <div style="margin-bottom: 14px;">
                <h2 class="icon-with-text"
                    style="font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);"><svg
                        class="ui-icon">
                        <use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use>
                    </svg>Stock Disponible en Tanques</h2>
                <p class="section-desc" style="font-size: 0.82rem; color: var(--text-muted, #64748b); margin-top: 4px;">
                    Distribución de saldo restante en inventario</p>
            </div>

            <!-- Bloque Combustibles Líquidos -->
            <div class="native-stock-chart" style="margin-bottom: <?= (!empty($hasGnv) || $totalGnvStock > 0) ? '14px' : '0' ?>;">
                <div class="native-stock-total" style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted, #64748b);">Líquidos en Tanques</span>
                    <strong style="color: #2563eb;"><?= number_format($totalLiquidStock, 2) ?> Gal</strong>
                </div>
                <?php if ($totalLiquidStock > 0): ?>
                    <div class="native-stock-bars" style="margin-top: 8px;">
                        <?php foreach ($liquidStockItems as $stockItem): ?>
                            <?php
                            $percentage = ($stockItem['value'] / $totalLiquidStock) * 100;
                            $color = $stockColorForType($stockItem['label']);
                            ?>
                            <div class="native-stock-row">
                                <div class="native-stock-row-header">
                                    <strong
                                        title="<?= htmlspecialchars($stockItem['label']) ?>"><?= htmlspecialchars($stockItem['label']) ?></strong>
                                    <span class="native-stock-row-value"><?= number_format($percentage, 2) ?>%</span>
                                </div>
                                <div class="native-stock-track" aria-hidden="true">
                                    <span class="native-stock-fill"
                                        style="width: <?= number_format($percentage, 2, '.', '') ?>%; background: <?= $color ?>;"></span>
                                </div>
                                <span class="native-stock-row-gallons"><?= number_format($stockItem['value'], 2) ?> Gal</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="empty-state" style="font-size: 0.85rem; margin: 6px 0;">Sin stock de combustibles líquidos.</p>
                <?php endif; ?>
            </div>

            <!-- Bloque Separado GNV (Gas Natural) -->
            <?php if (!empty($hasGnv) || $totalGnvStock > 0 || !empty($gnvStockItems)): ?>
            <?php 
                $gnvDisplayStock = $totalGnvStock > 0 ? $totalGnvStock : (float)($gnvRemaining ?? 0);
                $hasMultipleGnv = count($gnvStockItems) > 1;
            ?>
            <div style="background: rgba(16, 185, 129, 0.06); border: 1px solid rgba(16, 185, 129, 0.25); border-radius: 10px; padding: 12px; margin-top: auto;">
                <div style="display: flex; justify-content: space-between; align-items: center;<?= $hasMultipleGnv ? ' margin-bottom: 8px;' : '' ?>">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                        <span style="font-size: 0.82rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #047857;">Gas Natural (GNV)</span>
                    </div>
                    <span style="font-size: 0.88rem; font-weight: 800; color: #059669; background: rgba(16,185,129,0.15); padding: 2px 8px; border-radius: 6px;">
                        <?= number_format($gnvDisplayStock, 2) ?> m³
                    </span>
                </div>
                <?php if ($hasMultipleGnv): ?>
                    <div style="padding-top: 6px; border-top: 1px dashed rgba(16, 185, 129, 0.25);">
                        <?php foreach ($gnvStockItems as $gnvItem): ?>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.82rem; margin-top: 4px;">
                                <span style="color: var(--text-muted, #64748b);"><?= htmlspecialchars($gnvItem['label']) ?></span>
                                <strong style="color: #047857;"><?= number_format($gnvItem['value'], 2) ?> m³</strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </article>
    </section>

    <!-- Widget del Calendario de Entregas e Ingresos Próximos -->
    <article class="calendar-widget-card">
        <div class="card-header-flex" style="flex-wrap: wrap; gap: 12px;">
            <div>
                <h2>Agenda de Movimientos</h2>
                <p class="section-desc">Próximos despachos e ingresos registrados</p>
            </div>
            <a href="<?= BASE_URL ?>calendario" class="link-more">Ver Completo →</a>
        </div>

        <!-- Pestañas de Selección -->
        <div
            style="display: flex; gap: 8px; margin-top: 12px; margin-bottom: 16px; border-bottom: 2px solid var(--border-color, #e2e8f0); padding-bottom: 8px;">
            <button type="button" id="tabDeliveriesBtn" class="calendar-tab-btn active"
                onclick="switchCalendarTab('deliveries')"
                style="padding: 6px 14px; border-radius: 8px; border: none; font-size: 0.85rem; font-weight: 700; cursor: pointer; background: #2563eb; color: #ffffff; transition: all 0.2s;">
                <svg class="ui-icon">
                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use>
                </svg> Despachos / Entregas
            </button>
            <button type="button" id="tabIncomesBtn" class="calendar-tab-btn" onclick="switchCalendarTab('incomes')"
                style="padding: 6px 14px; border-radius: 8px; border: none; font-size: 0.85rem; font-weight: 700; cursor: pointer; background: var(--bg-body, #f1f5f9); color: var(--text-muted, #64748b); transition: all 0.2s;">
                <svg class="ui-icon">
                    <use href="<?= BASE_URL ?>assets/icons.svg#icon-download"></use>
                </svg> Ingresos de Lotes
            </button>
        </div>

        <!-- Contenedor Despachos -->
        <div id="calendarDeliveriesList" class="widget-events-list">
            <?php
            $deliveriesOnly = array_filter($calendarEvents, fn($ev) => ($ev['type'] ?? '') === 'fuel');
            ?>
            <?php if (!empty($deliveriesOnly)): ?>
                <?php $count = 0; ?>
                <?php foreach ($deliveriesOnly as $ev): ?>
                    <?php if ($count >= 4)
                        break;
                    $count++; ?>
                    <div class="widget-event-item" onclick="location.href='<?= BASE_URL ?>calendario'">
                        <div class="event-date-chip">
                            <span class="d-num"><?= date('d', strtotime($ev['date'])) ?></span>
                            <span class="d-month"><?= date('M', strtotime($ev['date'])) ?></span>
                        </div>
                        <div class="event-info">
                            <strong><?= htmlspecialchars($ev['title']) ?></strong>
                            <div class="event-sub">
                                <span class="badge <?= $ev['badge_class'] ?>"><?= htmlspecialchars($ev['badge']) ?></span>
                                <?php
                                $evFuel = $ev['fuel_type'] ?? '';
                                if (empty($evFuel) && !empty($ev['title']) && preg_match('/\((.*?)\)/', $ev['title'], $m)) {
                                    $evFuel = $m[1];
                                }
                                $evUnit = \App\Models\Catalog::getFuelUnit($evFuel, true);
                                ?>
                                <span class="icon-with-text"><svg class="ui-icon">
                                        <use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use>
                                    </svg><?= number_format((float) ($ev['gallons'] ?? 0), 2) ?> <?= $evUnit ?> - <svg class="ui-icon">
                                        <use href="<?= BASE_URL ?>assets/icons.svg#icon-user"></use>
                                    </svg>
                                    <?= htmlspecialchars($ev['driver'] ?? $ev['title'] ?? 'Responsable') ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">No hay fechas de despacho registradas.</p>
            <?php endif; ?>
        </div>

        <!-- Contenedor Ingresos -->
        <div id="calendarIncomesList" class="widget-events-list" style="display: none;">
            <?php
            $incomesOnly = array_filter($calendarEvents, fn($ev) => ($ev['type'] ?? '') === 'income');
            ?>
            <?php if (!empty($incomesOnly)): ?>
                <?php $count = 0; ?>
                <?php foreach ($incomesOnly as $ev): ?>
                    <?php if ($count >= 4)
                        break;
                    $count++; ?>
                    <div class="widget-event-item" onclick="location.href='<?= BASE_URL ?>calendario'"
                        style="border-left: 4px solid #16a34a;">
                        <div class="event-date-chip" style="background: #166534;">
                            <span class="d-num"><?= date('d', strtotime($ev['date'])) ?></span>
                            <span class="d-month"><?= date('M', strtotime($ev['date'])) ?></span>
                        </div>
                        <div class="event-info">
                            <strong><?= htmlspecialchars($ev['title']) ?></strong>
                            <div class="event-sub">
                                <span class="badge <?= $ev['badge_class'] ?>"><?= htmlspecialchars($ev['badge']) ?></span>
                                <?php
                                $evFuel = $ev['fuel_type'] ?? '';
                                if (empty($evFuel) && !empty($ev['title']) && preg_match('/\((.*?)\)/', $ev['title'], $m)) {
                                    $evFuel = $m[1];
                                }
                                $evUnit = \App\Models\Catalog::getFuelUnit($evFuel, true);
                                ?>
                                <span class="icon-with-text"><svg class="ui-icon">
                                        <use href="<?= BASE_URL ?>assets/icons.svg#icon-download"></use>
                                    </svg><?= number_format((float) ($ev['gallons'] ?? 0), 2) ?> <?= $evUnit ?> - <svg class="ui-icon">
                                        <use href="<?= BASE_URL ?>assets/icons.svg#icon-store"></use>
                                    </svg>
                                    <?= htmlspecialchars($ev['driver'] ?? 'Proveedor') ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">No hay fechas de ingresos registradas.</p>
            <?php endif; ?>
        </div>

        <div class="widget-footer" style="margin-top: 16px;">
            <a href="<?= BASE_URL ?>calendario" class="button secondary full-width">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Abrir Calendario Interactivo
            </a>
        </div>
    </article>

    <!-- 6. Últimas Entregas Table Section con Buscador Rápido -->
    <section class="table-container">
        <div class="table-header">
            <div>
                <h2>Últimos Despachos de Combustible</h2>
                <p class="section-desc">Historial reciente con filtro y búsqueda instantánea</p>
            </div>
            <div class="table-header-right">
                <form action="<?= BASE_URL ?>despachos" method="GET" class="dash-search-form" onsubmit="return false;">
                    <div class="dash-search-input-wrap">
                        <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="dashSearchInput" name="q" placeholder="Buscar vale, placa, conductor..."
                            class="dash-search-input" maxlength="30"
                            oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-\s]/g, '')">
                    </div>
                </form>
                <a href="<?= BASE_URL ?>despachos" class="link-more">Buscador Avanzado →</a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>N° Recibo / Vale</th>
                        <th>Fecha y Hora</th>
                        <th>Placa</th>
                        <th>Conductor</th>
                        <th>Tipo de Combustible</th>
                        <th>Área</th>
                        <th>Observaciones</th>
                        <th>Cantidad</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ultimasEntregas)): ?>
                        <?php foreach ($ultimasEntregas as $entrega): ?>
                            <tr>
                                <td>
                                    <span class="receipt-tag"><?= htmlspecialchars($entrega['receipt_number']) ?></span>
                                </td>
                                <td>
                                    <span class="text-muted"><?= date('d/m/Y H:i', strtotime($entrega['created_at'])) ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($entrega['vehicle_plate']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($entrega['driver_name']) ?></td>
                                <td>
                                    <span
                                        class="fuel-type-pill"><?= htmlspecialchars($entrega['fuel_type'] ?? 'Diesel B5') ?></span>
                                </td>
                                <td><?= htmlspecialchars($entrega['destination'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $obs = trim($entrega['notes'] ?? '');
                                    if (empty($obs) || $obs === '-'):
                                        ?>
                                        <span style="font-size: 0.85rem; color: var(--muted);">-</span>
                                    <?php else: ?>
                                        <span
                                            style="font-size: 0.85rem; color: var(--muted); display: inline-block; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                            title="<?= htmlspecialchars($obs) ?>">
                                            <?= htmlspecialchars($obs) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $dUnit = \App\Models\Catalog::getFuelUnit($entrega['fuel_type'] ?? '', true); ?>
                                    <span class="quantity-highlight"><?= number_format((float) $entrega['gallons'], 2) ?> <?= $dUnit ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>despachos/imprimir?id=<?= $entrega['id'] ?>" target="_blank"
                                        title="Imprimir vale"
                                        style="padding: 5px; background: #2563eb; color: #ffffff; border-radius: 6px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">
                                        <svg class="ui-icon">
                                            <use href="<?= BASE_URL ?>assets/icons.svg#icon-print"></use>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="empty-state">No se registran entregas recientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal Emergente Exclusivo para Observación Completa (INFO) en Dashboard -->
    <div id="obsInfoModal" class="modal-overlay" onclick="closeObsModal(event)">
        <div class="modal-card" style="max-width: 480px;" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="icon-with-text"><svg class="ui-icon">
                        <use href="<?= BASE_URL ?>assets/icons.svg#icon-note"></use>
                    </svg>Observación Completa</h3>
                <button class="modal-close-btn" onclick="closeObsModal()">&times;</button>
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

</div>

<script>
    let fuelChartInstance = null;
    let chartResizeObserver = null;
    let currentViewKey = 'semanal';
    let currentEnergyKey = 'all';
    const hasGnv = <?= !empty($hasGnv) ? 'true' : 'false' ?>;

    const chartDataSets = {
        semanal: { title: 'Combustible Despachado Semanal', subtitle: 'Volumen diario consumido (últimos 7 días)', colors: ['#2563eb'], lightColors: ['#60a5fa'], data: <?= json_encode($dailyFuel, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> },
        tipo: { title: 'Despacho por Tipo de Combustible', subtitle: 'Distribución consumida según tipo de combustible', colors: ['#10b981', '#0ea5e9', '#f59e0b', '#8b5cf6', '#ec4899'], lightColors: ['#6ee7b7', '#7dd3fc', '#fcd34d', '#c4b5fd', '#f9a8d4'], data: <?= json_encode($typeFuel, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> },
        mensual: { title: 'Combustible Despachado Mensualmente', subtitle: 'Evolución y total despachado por mes (año actual)', colors: ['#0d9488'], lightColors: ['#5eead4'], data: <?= json_encode($monthlyFuel, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> },
        anual: { title: 'Acumulado de Combustible Anual', subtitle: 'Consumo total de combustible por año', colors: ['#7c3aed'], lightColors: ['#c4b5fd'], data: <?= json_encode($yearlyFuel, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> },
        area: { title: 'Despacho por Área o Destino', subtitle: 'Distribución del combustible entregado a cada área municipal', colors: ['#f97316', '#ef4444', '#eab308', '#14b8a6', '#3b82f6'], lightColors: ['#fdba74', '#fca5a5', '#fde047', '#5eead4', '#93c5fd'], data: <?= json_encode($areaFuel, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?> }
    };

    function isDarkMode() {
        return document.documentElement.getAttribute('data-theme') === 'dark';
    }

    function formatNumber(value, decimals = 2) {
        return Number(value || 0).toLocaleString('es-PE', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
    }

    function formatGallons(value, decimals = 2) {
        return `${formatNumber(value, decimals)} Gal`;
    }

    function formatGnv(value, decimals = 2) {
        return `${formatNumber(value, decimals)} m³`;
    }

    function getThemeColors() {
        const dark = isDarkMode();
        return {
            text: dark ? '#f8fafc' : '#475569',
            muted: dark ? '#cbd5e1' : '#64748b',
            grid: dark ? 'rgba(255,255,255,0.12)' : '#e2e8f0',
            tooltipBg: dark ? '#0f172a' : '#ffffff',
            tooltipBorder: dark ? '#334155' : '#bfdbfe'
        };
    }

    const FUEL_COLOR_PALETTE = [
        { color: '#f59e0b', lightColor: '#fcd34d' },
        { color: '#8b5cf6', lightColor: '#c4b5fd' },
        { color: '#06b6d4', lightColor: '#67e8f9' },
        { color: '#2563eb', lightColor: '#93c5fd' },
        { color: '#ec4899', lightColor: '#f9a8d4' },
        { color: '#10b981', lightColor: '#6ee7b7' },
        { color: '#f43f5e', lightColor: '#fda4af' },
        { color: '#eab308', lightColor: '#fde047' },
        { color: '#a855f7', lightColor: '#d8b4fe' },
        { color: '#14b8a6', lightColor: '#5eead4' },
        { color: '#f97316', lightColor: '#fdba74' },
        { color: '#64748b', lightColor: '#cbd5e1' }
    ];

    function getPreferredFuelColor(label) {
        const fuelType = String(label || '').toLocaleLowerCase('es-PE');

        if (fuelType.includes('diesel')) return { color: '#f59e0b', lightColor: '#fcd34d' };
        if (fuelType.includes('gasohol premium')) return { color: '#8b5cf6', lightColor: '#c4b5fd' };
        if (fuelType.includes('gasohol regular')) return { color: '#ec4899', lightColor: '#f9a8d4' };
        if (fuelType.includes('gasohol')) return { color: '#a855f7', lightColor: '#d8b4fe' };
        if (fuelType.includes('glp') || fuelType.includes('gas licuado')) return { color: '#06b6d4', lightColor: '#67e8f9' };
        if (fuelType.includes('gnv') || fuelType.includes('gas natural')) return { color: '#10b981', lightColor: '#6ee7b7' };

        return { color: '#64748b', lightColor: '#cbd5e1' };
    }

    function buildFuelTypeColorMap(items) {
        const map = new Map();
        const usedColors = new Set();
        let paletteIndex = 0;

        (items || []).forEach((item) => {
            const label = String(item?.label ?? '');
            if (map.has(label)) return;

            const preferred = getPreferredFuelColor(label);
            let assigned = preferred;

            if (usedColors.has(preferred.color)) {
                while (usedColors.has(FUEL_COLOR_PALETTE[paletteIndex % FUEL_COLOR_PALETTE.length].color)) {
                    paletteIndex++;
                }
                assigned = FUEL_COLOR_PALETTE[paletteIndex % FUEL_COLOR_PALETTE.length];
                paletteIndex++;
            }

            map.set(label, assigned);
            usedColors.add(assigned.color);
        });

        return map;
    }

    function getFuelTypeColors(label, colorMap) {
        return (colorMap && colorMap.get(String(label || ''))) || getPreferredFuelColor(label);
    }

    function updateChartTitles() {
        const config = chartDataSets[currentViewKey];
        if (!config) return;

        let title = config.title;
        let subtitle = config.subtitle;

        if (currentEnergyKey === 'liquid') {
            title += ' (Líquidos)';
            subtitle += ' - Solo combustibles líquidos (Gal)';
        } else if (currentEnergyKey === 'gnv') {
            title += ' (GNV)';
            subtitle = 'Consumo de Gas Natural Vehicular en metros cúbicos (m³)';
        } else {
            if (currentViewKey !== 'tipo') {
                subtitle += ' - Comparativa Líquidos (Gal) y GNV (m³)';
            }
        }

        const titleEl = document.getElementById('chartTitle');
        const subEl = document.getElementById('chartSubtitle');
        if (titleEl) titleEl.textContent = title;
        if (subEl) subEl.textContent = subtitle;
    }

    function calcNiceMax(max) {
        const num = Number(max || 0);
        if (num <= 0) return 10;
        const target = num * 1.25; // 25% de margen superior para evitar colisiones con leyendas o borde
        if (target <= 10) return 10;
        if (target <= 20) return 20;
        if (target <= 25) return 25;
        if (target <= 50) return 50;
        if (target <= 100) return 100;
        const mag = Math.pow(10, Math.floor(Math.log10(target)));
        return Math.ceil(target / mag) * mag;
    }

    function initChart(viewKey) {
        if (viewKey) currentViewKey = viewKey;
        if (typeof echarts === 'undefined') return;

        const element = document.getElementById('dailyFuelChart');
        const config = chartDataSets[currentViewKey];
        if (!element || !config) return;

        fuelChartInstance = echarts.getInstanceByDom(element) || echarts.init(element);
        const theme = getThemeColors();
        const horizontal = currentViewKey === 'area';
        const rawData = Array.isArray(config.data) ? config.data : [];

        // Modo Por Tipo
        if (currentViewKey === 'tipo') {
            let data = rawData;
            if (currentEnergyKey === 'liquid') {
                data = rawData.filter(d => !d.is_gnv);
            } else if (currentEnergyKey === 'gnv') {
                data = rawData.filter(d => d.is_gnv);
            }

            const tipoColorMap = buildFuelTypeColorMap(data);

            fuelChartInstance.setOption({
                animationDuration: 300,
                legend: { show: false },
                grid: { left: 8, right: 14, top: 38, bottom: 12, containLabel: true },
                tooltip: {
                    trigger: 'axis', axisPointer: { type: 'shadow' },
                    backgroundColor: theme.tooltipBg, borderColor: theme.tooltipBorder, borderWidth: 1,
                    textStyle: { color: theme.text },
                    formatter: (params) => {
                        const item = data[params[0]?.dataIndex];
                        const unit = item?.unit || (item?.is_gnv ? 'm³' : 'Gal');
                        return `${params[0]?.axisValueLabel || ''}<br/><strong>Total: ${formatNumber(params[0]?.value, 2)} ${unit}</strong>`;
                    }
                },
                xAxis: {
                    type: 'category', data: data.map((item) => item.label),
                    axisLabel: { color: theme.text, fontSize: window.innerWidth <= 480 ? 9 : 11 },
                    axisTick: { show: false }, axisLine: { lineStyle: { color: theme.grid } }
                },
                yAxis: {
                    type: 'value', min: 0,
                    max: (val) => calcNiceMax(val.max),
                    axisLabel: { color: theme.text, formatter: (value) => `${Number(value).toLocaleString('es-PE')}` },
                    splitLine: { lineStyle: { color: theme.grid } }, axisLine: { show: false }
                },
                series: [{
                    name: 'Consumo por Tipo', type: 'bar', barMaxWidth: 54,
                    data: data.map((item) => {
                        const fuelColors = getFuelTypeColors(item.label, tipoColorMap);
                        return {
                            value: Number(item.total_gallons || 0),
                            itemStyle: {
                                borderRadius: [6, 6, 0, 0],
                                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                                    { offset: 0, color: fuelColors.lightColor },
                                    { offset: 0.45, color: fuelColors.color },
                                    { offset: 1, color: fuelColors.color }
                                ])
                            }
                        };
                    }),
                    label: {
                        show: true, position: 'top', distance: 6, color: theme.text, fontSize: 10, fontWeight: 600,
                        formatter: ({ dataIndex, value }) => {
                            const u = data[dataIndex]?.unit || (data[dataIndex]?.is_gnv ? 'm³' : 'Gal');
                            return `${formatNumber(value, 2)} ${u}`;
                        }
                    },
                    emphasis: { focus: 'series', itemStyle: { borderColor: '#ffffff', borderWidth: 2 } }
                }]
            }, { notMerge: true, lazyUpdate: true });
            return;
        }

        // Vistas Semanal, Mensual, Anual, Área
        const categories = rawData.map(d => d.label);

        // Si filtro es solo Líquidos o solo GNV: una sola serie
        if (currentEnergyKey === 'liquid' || currentEnergyKey === 'gnv') {
            const isGnv = currentEnergyKey === 'gnv';
            const unit = isGnv ? 'm³' : 'Gal';
            const seriesName = isGnv ? 'GNV (m³)' : 'Líquidos (Gal)';
            const seriesColor = isGnv ? { c: '#10b981', light: '#6ee7b7' } : { c: '#2563eb', light: '#60a5fa' };
            const values = rawData.map(d => isGnv ? Number(d.gnv_m3 || 0) : Number(d.liquid_gallons || 0));

            fuelChartInstance.setOption({
                animationDuration: 300,
                legend: { show: false },
                grid: horizontal
                    ? { left: 12, right: 36, top: 24, bottom: 24, containLabel: true }
                    : { left: 8, right: 14, top: 38, bottom: 12, containLabel: true },
                tooltip: {
                    trigger: 'axis', axisPointer: { type: 'shadow' },
                    backgroundColor: theme.tooltipBg, borderColor: theme.tooltipBorder, borderWidth: 1,
                    textStyle: { color: theme.text },
                    formatter: (params) => `${params[0]?.axisValueLabel || ''}<br/><strong>${seriesName}: ${formatNumber(params[0]?.value, 2)} ${unit}</strong>`
                },
                xAxis: horizontal ? {
                    type: 'value', min: 0,
                    max: (val) => calcNiceMax(val.max),
                    axisLabel: { color: theme.text, formatter: (val) => `${Number(val).toLocaleString('es-PE')} ${unit}` },
                    splitLine: { lineStyle: { color: theme.grid } }, axisLine: { lineStyle: { color: theme.grid } }
                } : {
                    type: 'category', data: categories,
                    axisLabel: { color: theme.text, fontSize: window.innerWidth <= 480 ? 9 : 11 },
                    axisTick: { show: false }, axisLine: { lineStyle: { color: theme.grid } }
                },
                yAxis: horizontal ? {
                    type: 'category', data: categories,
                    axisLabel: { color: theme.text, fontSize: window.innerWidth <= 480 ? 9 : 11 },
                    axisTick: { show: false }, axisLine: { show: false }
                } : {
                    type: 'value', min: 0,
                    max: (val) => calcNiceMax(val.max),
                    axisLabel: { color: theme.text, formatter: (val) => `${Number(val).toLocaleString('es-PE')} ${unit}` },
                    splitLine: { lineStyle: { color: theme.grid } }, axisLine: { show: false }
                },
                series: [{
                    name: seriesName, type: 'bar', barMaxWidth: horizontal ? 34 : 52,
                    data: values.map(val => ({
                        value: val,
                        itemStyle: {
                            borderRadius: horizontal ? [0, 6, 6, 0] : [6, 6, 0, 0],
                            color: new echarts.graphic.LinearGradient(horizontal ? 0 : 0, horizontal ? 0 : 0, horizontal ? 1 : 0, horizontal ? 0 : 1, [
                                { offset: 0, color: seriesColor.light },
                                { offset: 1, color: seriesColor.c }
                            ])
                        }
                    })),
                    label: {
                        show: true, position: horizontal ? 'right' : 'top', distance: 6, color: theme.text, fontSize: 10, fontWeight: 600,
                        formatter: ({ value }) => value > 0 ? `${formatNumber(value, 2)} ${unit}` : ''
                    },
                    emphasis: { focus: 'series', itemStyle: { borderColor: '#ffffff', borderWidth: 2 } }
                }]
            }, { notMerge: true, lazyUpdate: true });
            return;
        }

        // Modo TODOS: Doble Serie Comparativa (Líquidos en Gal + GNV en m3)
        fuelChartInstance.setOption({
            animationDuration: 300,
            legend: {
                show: true, top: 2, right: 14, itemGap: 14,
                textStyle: { color: theme.text, fontSize: 11, fontWeight: 600 }
            },
            grid: horizontal
                ? { left: 12, right: 36, top: 38, bottom: 24, containLabel: true }
                : { left: 8, right: 14, top: 50, bottom: 12, containLabel: true },
            tooltip: {
                trigger: 'axis', axisPointer: { type: 'shadow' },
                backgroundColor: theme.tooltipBg, borderColor: theme.tooltipBorder, borderWidth: 1,
                textStyle: { color: theme.text },
                formatter: (params) => {
                    const idx = params[0]?.dataIndex;
                    const item = rawData[idx] || {};
                    let html = `<strong>${params[0]?.axisValueLabel || ''}</strong><br/>`;
                    html += `<span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#2563eb;margin-right:6px;"></span>Líquidos: <strong>${formatGallons(item.liquid_gallons || 0, 2)}</strong><br/>`;
                    if (Number(item.gnv_m3 || 0) > 0 || hasGnv) {
                        html += `<span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#10b981;margin-right:6px;"></span>GNV: <strong>${formatGnv(item.gnv_m3 || 0, 2)}</strong>`;
                    }
                    return html;
                }
            },
            xAxis: horizontal ? {
                type: 'value', min: 0,
                max: (val) => calcNiceMax(val.max),
                axisLabel: { color: theme.text, formatter: (val) => `${Number(val).toLocaleString('es-PE')}` },
                splitLine: { lineStyle: { color: theme.grid } }, axisLine: { lineStyle: { color: theme.grid } }
            } : {
                type: 'category', data: categories,
                axisLabel: { color: theme.text, fontSize: window.innerWidth <= 480 ? 9 : 11 },
                axisTick: { show: false }, axisLine: { lineStyle: { color: theme.grid } }
            },
            yAxis: horizontal ? {
                type: 'category', data: categories,
                axisLabel: { color: theme.text, fontSize: window.innerWidth <= 480 ? 9 : 11 },
                axisTick: { show: false }, axisLine: { show: false }
            } : {
                type: 'value', min: 0,
                max: (val) => calcNiceMax(val.max),
                axisLabel: { color: theme.text, formatter: (val) => `${Number(val).toLocaleString('es-PE')}` },
                splitLine: { lineStyle: { color: theme.grid } }, axisLine: { show: false }
            },
            series: [
                {
                    name: 'Líquidos (Gal)', type: 'bar', barMaxWidth: horizontal ? 18 : 26, barGap: '30%',
                    data: rawData.map(d => Number(d.liquid_gallons || 0)),
                    itemStyle: {
                        borderRadius: horizontal ? [0, 4, 4, 0] : [4, 4, 0, 0],
                        color: new echarts.graphic.LinearGradient(horizontal ? 0 : 0, horizontal ? 0 : 0, horizontal ? 1 : 0, horizontal ? 0 : 1, [
                            { offset: 0, color: '#60a5fa' },
                            { offset: 1, color: '#2563eb' }
                        ])
                    },
                    label: {
                        show: !horizontal, position: 'top', distance: 5,
                        formatter: ({ value }) => value > 0 ? `{val|${formatNumber(value, 2)}}\n{unit|Gal}` : '',
                        rich: {
                            val: {
                                color: theme.text,
                                fontSize: 8.5,
                                fontWeight: 700,
                                lineHeight: 11,
                                align: 'center'
                            },
                            unit: {
                                color: theme.muted,
                                fontSize: 7.5,
                                fontWeight: 600,
                                lineHeight: 10,
                                align: 'center'
                            }
                        }
                    }
                },
                {
                    name: 'GNV (m³)', type: 'bar', barMaxWidth: horizontal ? 18 : 26,
                    data: rawData.map(d => Number(d.gnv_m3 || 0)),
                    itemStyle: {
                        borderRadius: horizontal ? [0, 4, 4, 0] : [4, 4, 0, 0],
                        color: new echarts.graphic.LinearGradient(horizontal ? 0 : 0, horizontal ? 0 : 0, horizontal ? 1 : 0, horizontal ? 0 : 1, [
                            { offset: 0, color: '#6ee7b7' },
                            { offset: 1, color: '#10b981' }
                        ])
                    },
                    label: {
                        show: !horizontal, position: 'top', distance: 5,
                        formatter: ({ value }) => value > 0 ? `{val|${formatNumber(value, 2)}}\n{unit|m³}` : '',
                        rich: {
                            val: {
                                color: theme.text,
                                fontSize: 8.5,
                                fontWeight: 700,
                                lineHeight: 11,
                                align: 'center'
                            },
                            unit: {
                                color: theme.muted,
                                fontSize: 7.5,
                                fontWeight: 600,
                                lineHeight: 10,
                                align: 'center'
                            }
                        }
                    }
                }
            ]
        }, { notMerge: true, lazyUpdate: true });
    }

    function resizeCharts() {
        fuelChartInstance?.resize();
    }

    function disposeCharts() {
        chartResizeObserver?.disconnect();
        fuelChartInstance?.dispose();
        fuelChartInstance = null;
    }

    document.addEventListener('DOMContentLoaded', () => {
        initChart('semanal');

        const chartElements = [document.getElementById('dailyFuelChart')].filter(Boolean);
        if ('ResizeObserver' in window) {
            chartResizeObserver = new ResizeObserver(resizeCharts);
            chartElements.forEach((element) => chartResizeObserver.observe(element));
        } else {
            window.addEventListener('resize', resizeCharts);
        }
        window.addEventListener('themeChanged', () => initChart(currentViewKey));
        window.addEventListener('pagehide', disposeCharts, { once: true });

        const dashInput = document.getElementById('dashSearchInput');
        if (dashInput) dashInput.addEventListener('input', () => {
            const term = dashInput.value.toLowerCase().trim();
            document.querySelectorAll('.table-wrap tbody tr').forEach((row) => {
                row.style.display = row.innerText.toLowerCase().includes(term) ? '' : 'none';
            });
        });
    });

    function switchEnergyFilter(evt, energyKey) {
        evt?.preventDefault();
        evt?.stopPropagation();
        currentEnergyKey = energyKey;
        document.querySelectorAll('.energy-btn').forEach((button) => {
            button.classList.toggle('active', button.dataset.energy === energyKey);
        });
        updateChartTitles();
        initChart(currentViewKey);
        return false;
    }

    function switchChartView(evt, viewKey) {
        evt?.preventDefault();
        evt?.stopPropagation();
        currentViewKey = viewKey;
        document.querySelectorAll('.switcher-btn').forEach((button) => {
            button.classList.toggle('active', button.dataset.view === viewKey);
        });
        updateChartTitles();
        initChart(viewKey);
        return false;
    }

    function switchCalendarTab(tabType) {
        const delList = document.getElementById("calendarDeliveriesList");
        const incList = document.getElementById("calendarIncomesList");
        const btnDel = document.getElementById("tabDeliveriesBtn");
        const btnInc = document.getElementById("tabIncomesBtn");

        if (!delList || !incList || !btnDel || !btnInc) return;

        if (tabType === 'deliveries') {
            delList.style.display = 'block';
            incList.style.display = 'none';

            btnDel.style.background = '#2563eb';
            btnDel.style.color = '#ffffff';
            btnInc.style.background = 'var(--bg-body, #f1f5f9)';
            btnInc.style.color = 'var(--text-muted, #64748b)';
        } else {
            delList.style.display = 'none';
            incList.style.display = 'block';

            btnInc.style.background = '#16a34a';
            btnInc.style.color = '#ffffff';
            btnDel.style.background = 'var(--bg-body, #f1f5f9)';
            btnDel.style.color = 'var(--text-muted, #64748b)';
        }
    }

    const areaRelationsData = <?= json_encode($areaRelations ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const allPlatesData = <?= json_encode($uniquePlates ?? [], JSON_UNESCAPED_UNICODE) ?>;
    const allDriversData = <?= json_encode($uniqueDrivers ?? [], JSON_UNESCAPED_UNICODE) ?>;

    function onAreaFilterChange(selectedArea) {
        const inputPlaca = document.getElementById("unidad");
        const inputChofer = document.getElementById("responsable");
        const platesList = document.getElementById("plates_list");
        const driversList = document.getElementById("drivers_list");

        if (!selectedArea || !areaRelationsData[selectedArea]) {
            // Si elige "Todas las Áreas" o área sin registros, restaurar datalists completos
            updateDatalist(platesList, allPlatesData);
            updateDatalist(driversList, allDriversData);
            return;
        }

        const areaInfo = areaRelationsData[selectedArea];
        const areaPlates = areaInfo.plates || [];
        const areaDrivers = areaInfo.drivers || [];

        updateDatalist(platesList, areaPlates);
        updateDatalist(driversList, areaDrivers);

        // Si el área tiene exactamente 1 placa asociada, la coloca automáticamente
        if (areaPlates.length === 1) {
            if (inputPlaca) inputPlaca.value = areaPlates[0];
        } else if (inputPlaca && inputPlaca.value && !areaPlates.includes(inputPlaca.value)) {
            // Si hay varias placas y la actual no pertenece al área, se resetea
            inputPlaca.value = "";
        }

        // Si el área tiene exactamente 1 conductor asociado, lo coloca automáticamente
        if (areaDrivers.length === 1) {
            if (inputChofer) inputChofer.value = areaDrivers[0];
        } else if (inputChofer && inputChofer.value && !areaDrivers.includes(inputChofer.value)) {
            // Si hay varios conductores y el actual no pertenece al área, se resetea
            inputChofer.value = "";
        }
    }

    function updateDatalist(datalistEl, items) {
        if (!datalistEl) return;
        datalistEl.innerHTML = "";
        items.forEach(item => {
            const opt = document.createElement("option");
            opt.value = item;
            datalistEl.appendChild(opt);
        });
    }

    function showObsModal(obsText) {
        document.getElementById("obsModalContent").textContent = obsText;
        document.getElementById("obsInfoModal").classList.add("active");
    }

    function closeObsModal(e) {
        document.getElementById("obsInfoModal").classList.remove("active");
    }

    // Usa la hora local del equipo que abre el panel (formato de 24 horas).
    (function updateDashboardGreeting() {
        const greeting = document.getElementById('dashboardGreeting');
        if (!greeting) return;

        const hour = new Date().getHours();
        const message = hour < 12
            ? 'Buenos días'
            : hour < 19
                ? 'Buenas tardes'
                : 'Buenas noches';

        greeting.firstChild.nodeValue = `${message}, ${greeting.dataset.userName}`;
    })();

</script>