<div class="dashboard-wrapper">
    <section class="page-head">
        <div>
            <h1>Dashboard General</h1>
            <p class="subtitle">Control y Monitoreo de Combustible y Entregas</p>
        </div>
        <div class="actions" style="display: flex; gap: 10px;">
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
            <a class="button secondary" href="<?= BASE_URL ?>despachos">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Consultar Despachos
            </a>
        </div>
    </section>

    <!-- Indicadores Principales / Dashboard Stats -->
    <section class="stats-grid">
        <!-- 1. Saldo disponible -->
        <article class="stat-card balance-card">
            <div class="stat-header">
                <span class="stat-icon icon-balance">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                </span>
                <span class="badge badge-success">En Stock</span>
            </div>
            <div class="stat-body">
                <strong><?= number_format((float) $saldoDisponible, 2) ?> <small>Gal</small></strong>
                <span>Saldo disponible</span>
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
                <span>Recibos del mes</span>
            </div>
        </article>
    </section>

    <!-- Nivel de Tanques y Depósitos de Combustible -->
    <section class="tanks-level-section"
        style="margin-bottom: 24px; background: var(--card-bg, #ffffff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
                <h2
                    style="font-size: 1.15rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b); display: flex; align-items: center; gap: 8px;">
                    🛢️ Nivel de Tanques y Depósitos Generales
                </h2>
                <p style="font-size: 0.85rem; color: var(--text-muted, #64748b); margin-top: 4px;">Porcentaje de volumen
                    actual y capacidad nominal por tipo de unidad/depósito</p>
            </div>
            <span
                style="font-size: 0.8rem; font-weight: 600; padding: 4px 10px; border-radius: 12px; background: #e0f2fe; color: #0369a1;">
                Monitoreo en Tiempo Real
            </span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
            <?php if (!empty($tankLevels)): ?>
                <?php foreach ($tankLevels as $tank): ?>
                    <div
                        style="background: var(--bg-body, #f8fafc); border-radius: 10px; border: 1px solid var(--border-color, #cbd5e1); padding: 16px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span
                                style="font-size: 0.9rem; font-weight: 700; color: var(--text-color, #0f172a);"><?= htmlspecialchars($tank['name']) ?></span>
                            <span
                                style="font-size: 0.75rem; font-weight: 600; padding: 2px 8px; border-radius: 6px; background: <?= $tank['status_color'] ?>22; color: <?= $tank['status_color'] ?>;">
                                <?= htmlspecialchars($tank['status_text']) ?>
                            </span>
                        </div>

                        <div style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 6px;">
                            <strong style="font-size: 1.6rem; font-weight: 800; color: <?= $tank['status_color'] ?>;">
                                <?= $tank['percentage'] ?>%
                            </strong>
                            <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-muted, #64748b);">
                                <?= number_format($tank['current'], 1) ?> / <?= number_format($tank['capacity'], 0) ?> Gal
                            </span>
                        </div>

                        <!-- Barra de Nivel Líquido -->
                        <div
                            style="width: 100%; height: 12px; border-radius: 6px; background: #e2e8f0; overflow: hidden; position: relative;">
                            <div
                                style="width: <?= $tank['percentage'] ?>%; height: 100%; border-radius: 6px; background: linear-gradient(90deg, <?= $tank['status_color'] ?>, <?= $tank['status_color'] ?>dd); transition: width 0.6s ease;">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Visualizaciones: Gráfico Dinámico Multivista, Gráfico Circular de Stock y Resumen del Calendario -->
    <section class="visuals-grid"
        style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
        <!-- Gráfico interactivo con alternancia de vista (Diario, Mensual, Anual, Por Área) -->
        <article class="chart-container-card">
            <div class="card-header-flex chart-header-wrap">
                <div>
                    <h2 id="chartTitle">Combustible Despachado por Día</h2>
                    <p class="section-desc" id="chartSubtitle">Volumen diario consumido en galones (últimos 7 días)</p>
                </div>
                <div class="chart-view-switcher" role="tablist">
                    <button type="button" class="switcher-btn active" data-view="diario"
                        onclick="switchChartView(event, 'diario')">Diario</button>
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

            <div class="chart-wrapper">
                <canvas id="dailyFuelChart" height="230"></canvas>
            </div>

            <!-- Fallback visual bar chart si Chart.js tarda en cargar o no tiene soporte -->
            <div class="visual-bars-fallback" id="barsFallback">
                <?php
                $maxGallons = 1;
                if (!empty($dailyFuel)) {
                    foreach ($dailyFuel as $d) {
                        if ($d['total_gallons'] > $maxGallons)
                            $maxGallons = $d['total_gallons'];
                    }
                }
                ?>
                <div class="custom-bars-row">
                    <?php foreach ($dailyFuel as $day): ?>
                        <?php $heightPct = max(15, round(($day['total_gallons'] / $maxGallons) * 100)); ?>
                        <div class="bar-col">
                            <span class="bar-val"><?= number_format($day['total_gallons'], 1) ?>G</span>
                            <div class="bar-fill" style="height: <?= $heightPct ?>%;"></div>
                            <span class="bar-lbl"><?= htmlspecialchars($day['label']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </article>

        <!-- Gráfico Circular: Stock de Combustible Disponible por Tipo -->
        <article class="chart-container-card"
            style="background: var(--card-bg, #ffffff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="margin-bottom: 12px;">
                <h2 style="font-size: 1.1rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);">⛽ Stock
                    Disponible por Tipo</h2>
                <p class="section-desc" style="font-size: 0.85rem; color: var(--text-muted, #64748b); margin-top: 4px;">
                    Distribución de galones restantes en inventario</p>
            </div>

            <div
                style="position: relative; height: 210px; display: flex; align-items: center; justify-content: center;">
                <canvas id="stockDoughnutChart" height="200"></canvas>
            </div>

            <div id="stockLegend"
                style="margin-top: 12px; display: flex; flex-direction: column; gap: 6px; font-size: 0.85rem;">
                <?php
                $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'];
                $totalStockRem = array_sum(array_column($stockByFuelType ?? [], 'value'));
                $idx = 0;
                ?>
                <?php if (!empty($stockByFuelType)): ?>
                    <?php foreach ($stockByFuelType as $st): ?>
                        <?php
                        $c = $colors[$idx % count($colors)];
                        $idx++;
                        $pct = $totalStockRem > 0 ? round(($st['value'] / $totalStockRem) * 100, 1) : 0;
                        ?>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span
                                    style="width: 10px; height: 10px; border-radius: 50%; background: <?= $c ?>; display: inline-block;"></span>
                                <span><?= htmlspecialchars($st['label']) ?></span>
                            </div>
                            <strong style="color: var(--text-color, #0f172a);"><?= number_format((float) $st['value'], 1) ?> Gal
                                (<?= $pct ?>%)</strong>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color: var(--text-muted, #94a3b8); font-size: 0.85rem; text-align: center;">Sin información de
                        lotes</p>
                <?php endif; ?>
            </div>
        </article>
    </section>

    <!-- Widget del Calendario de Entregas Próximas -->
    <article class="calendar-widget-card">
        <div class="card-header-flex">
            <div>
                <h2>Calendario de Entregas</h2>
                <p class="section-desc">Próximos despachos y entregas de combustible</p>
            </div>
            <a href="<?= BASE_URL ?>calendario" class="link-more">Ver Completo →</a>
        </div>

        <div class="widget-events-list">
            <?php if (!empty($calendarEvents)): ?>
                <?php $count = 0; ?>
                <?php foreach ($calendarEvents as $ev): ?>
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
                                <?php if ($ev['type'] === 'fuel'): ?>
                                    <span>⛽ <?= number_format($ev['gallons'], 2) ?> Gal - 👤
                                        <?= htmlspecialchars($ev['driver']) ?></span>
                                <?php else: ?>
                                    <span>🏢 <?= htmlspecialchars($ev['client']) ?> - 📌
                                        <?= htmlspecialchars($ev['status']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="empty-state">No hay próximas fechas de entrega.</p>
            <?php endif; ?>
        </div>

        <div class="widget-footer">
            <a href="<?= BASE_URL ?>calendario" class="button secondary full-width">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                Abrir Calendario Interactivo
            </a>
        </div>
    </article>
    </section>

    <!-- 6. Ultimas Entregas Table Section con Buscador Rápido -->
    <section class="table-container">
        <div class="table-header">
            <div>
                <h2>Últimas Entregas de Combustible</h2>
                <p class="section-desc">Historial reciente con filtro y búsqueda instantánea</p>
            </div>
            <div class="table-header-right">
                <form action="<?= BASE_URL ?>despachos" method="GET" class="dash-search-form">
                    <div class="dash-search-input-wrap">
                        <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="dashSearchInput" name="q" placeholder="Buscar vale, placa, conductor..."
                            class="dash-search-input" maxlength="30">
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
                        <th>Vehículo / Placa</th>
                        <th>Conductor</th>
                        <th>Tipo de Combustible</th>
                        <th>Destino</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
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
                                    <span class="quantity-highlight"><?= number_format((float) $entrega['gallons'], 2) ?>
                                        Gal</span>
                                </td>
                                <td>
                                    <span class="status-pill status-completed">
                                        <?= htmlspecialchars($entrega['status'] ?? 'Completado') ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>despachos/imprimir?id=<?= $entrega['id'] ?>" target="_blank" style="padding: 4px 10px; background: #2563eb; color: #ffffff; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        🖨️ Imprimir
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

    <!-- Exportación y Reportes de Entregas con Filtros -->
    <section class="reports-section">
        <div class="reports-header">
            <h2>Exportación y Reportes de Entregas</h2>
            <p class="section-desc">Filtra por rango de fechas, unidad o responsable y descarga en PDF o Excel</p>
        </div>

        <form method="GET" action="<?= BASE_URL ?>reportes/pdf" target="_blank" class="report-filters-form">
            <div class="filter-group">
                <label for="desde">Desde (Fecha inicio):</label>
                <input type="date" id="desde" name="desde">
            </div>

            <div class="filter-group">
                <label for="hasta">Hasta (Fecha fin):</label>
                <input type="date" id="hasta" name="hasta">
            </div>

            <div class="filter-group">
                <label for="unidad">Por Unidad / Placa:</label>
                <input type="text" id="unidad" name="unidad" placeholder="Ej: EAB-123 o MUNI-01" maxlength="10">
            </div>

            <div class="filter-group">
                <label for="responsable">Por Responsable / Conductor:</label>
                <input type="text" id="responsable" name="responsable" placeholder="Ej: Carlos Mendoza" maxlength="25">
            </div>

            <div class="report-buttons">
                <button type="submit" formaction="<?= BASE_URL ?>reportes/pdf" formtarget="_blank"
                    class="btn-export pdf-btn">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    Descargar Reporte PDF
                </button>

                <button type="submit" formaction="<?= BASE_URL ?>reportes/excel" class="btn-export excel-btn">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="8" y1="13" x2="16" y2="13"></line>
                        <line x1="8" y1="17" x2="16" y2="17"></line>
                    </svg>
                    Exportación a CSV para Excel
                </button>
            </div>
        </form>
    </section>


</div>

<script>
    let fuelChartInstance = null;

    const chartDataSets = {
        diario: {
            title: "Combustible Despachado por Día",
            subtitle: "Volumen diario consumido en galones (últimos 7 días)",
            type: "bar",
            color: "rgba(37, 99, 235, 0.85)",
            borderColor: "#1d4ed8",
            data: <?= json_encode($dailyFuel, JSON_UNESCAPED_UNICODE) ?>
        },
        tipo: {
            title: "Despacho por Tipo de Combustible",
            subtitle: "Distribución de galones consumidos según Diesel B5, Gasohol Regular o Premium",
            type: "bar",
            color: "rgba(16, 185, 129, 0.85)",
            borderColor: "#059669",
            data: <?= json_encode($typeFuel, JSON_UNESCAPED_UNICODE) ?>
        },
        mensual: {
            title: "Combustible Despachado Mensualmente",
            subtitle: "Evolución y total de galones despachados por mes (año actual)",
            type: "bar",
            color: "rgba(13, 148, 136, 0.85)",
            borderColor: "#0f766e",
            data: <?= json_encode($monthlyFuel, JSON_UNESCAPED_UNICODE) ?>
        },
        anual: {
            title: "Acumulado de Combustible Anual",
            subtitle: "Consumo total de combustible en galones por año",
            type: "bar",
            color: "rgba(124, 58, 237, 0.85)",
            borderColor: "#6d28d9",
            data: <?= json_encode($yearlyFuel, JSON_UNESCAPED_UNICODE) ?>
        },
        area: {
            title: "Despacho por Área o Destino",
            subtitle: "Distribución del combustible entregado a cada área municipal",
            type: "bar",
            color: "rgba(234, 88, 12, 0.85)",
            borderColor: "#c2410c",
            data: <?= json_encode($areaFuel, JSON_UNESCAPED_UNICODE) ?>
        }
    };

    const stockData = <?= json_encode($stockByFuelType ?? [], JSON_UNESCAPED_UNICODE) ?>;

    document.addEventListener("DOMContentLoaded", () => {
        initChart('diario');
        initDoughnutChart();

        // Buscador en tiempo real para la tabla del Dashboard
        const dashInput = document.getElementById("dashSearchInput");
        if (dashInput) {
            dashInput.addEventListener("input", () => {
                const term = dashInput.value.toLowerCase().trim();
                const rows = document.querySelectorAll(".table-wrap tbody tr");
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(term) ? "" : "none";
                });
            });
        }
    });

    function initDoughnutChart() {
        if (typeof Chart === 'undefined') return;
        const canvas = document.getElementById('stockDoughnutChart');
        if (!canvas) return;

        const labels = stockData.map(i => i.label);
        const dataValues = stockData.map(i => i.value);

        new Chart(canvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                return ` ${ctx.label}: ${ctx.parsed.toFixed(2)} Gal`;
                            }
                        }
                    }
                }
            }
        });
    }

    function initChart(viewKey) {
        if (typeof Chart === 'undefined') return;

        const config = chartDataSets[viewKey];
        if (!config || !config.data) return;

        const fallbackEl = document.getElementById('barsFallback');
        if (fallbackEl) fallbackEl.style.display = 'none';

        const ctx = document.getElementById('dailyFuelChart').getContext('2d');
        const labels = config.data.map(item => item.label);
        const dataValues = config.data.map(item => item.total_gallons);

        if (fuelChartInstance) {
            fuelChartInstance.destroy();
        }

        const isHorizontal = viewKey === 'area';

        fuelChartInstance = new Chart(ctx, {
            type: config.type,
            data: {
                labels: labels,
                datasets: [{
                    label: 'Galones Despachados',
                    data: dataValues,
                    backgroundColor: config.color,
                    borderColor: config.borderColor,
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: isHorizontal ? 'y' : 'x',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                const val = isHorizontal ? context.parsed.x : context.parsed.y;
                                return ` Total: ${val.toFixed(2)} Galones`;
                            }
                        }
                    }
                },
                scales: isHorizontal ? {
                    x: {
                        beginAtZero: true,
                        ticks: { callback: v => v + ' Gal' }
                    }
                } : {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { callback: v => v + ' Gal', font: { size: 12 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' } }
                    }
                }
            }
        });
    }

    function switchChartView(evt, viewKey) {
        if (evt) {
            evt.preventDefault();
            evt.stopPropagation();
        }

        const config = chartDataSets[viewKey];
        if (!config) return false;

        // Actualizar estados activos de los botones sin modificar la navegación
        document.querySelectorAll(".switcher-btn").forEach(btn => {
            if (btn.getAttribute("data-view") === viewKey) {
                btn.classList.add("active");
            } else {
                btn.classList.remove("active");
            }
        });

        // Actualizar títulos
        const titleEl = document.getElementById("chartTitle");
        const subTitleEl = document.getElementById("chartSubtitle");
        if (titleEl) titleEl.textContent = config.title;
        if (subTitleEl) subTitleEl.textContent = config.subtitle;

        // Renderizado instantáneo en memoria para estabilidad absoluta
        initChart(viewKey);

        return false;
    }
</script>