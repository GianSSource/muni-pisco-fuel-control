<style>
    /* ==========================================================================
       Corrección de Contraste y Legibilidad para Modo Oscuro
       ========================================================================== */
    .receipt-tag {
        background-color: rgba(59, 130, 246, 0.2) !important;
        color: #60a5fa !important;
        border: 1px solid rgba(59, 130, 246, 0.5) !important;
        padding: 4px 8px !important;
        border-radius: 6px !important;
        font-weight: 700 !important;
        font-family: monospace !important;
        display: inline-block !important;
    }

    .fuel-type-pill {
        background-color: rgba(59, 130, 246, 0.12) !important;
        color: var(--primary, #2563eb) !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
        padding: 3px 8px !important;
        border-radius: 6px !important;
        font-size: 0.85rem !important;
        display: inline-block !important;
        font-weight: 600 !important;
    }

    .status-pill.status-completed,
    .badge-success {
        background-color: rgba(34, 197, 94, 0.2) !important;
        color: #4ade80 !important;
        border: 1px solid rgba(34, 197, 94, 0.5) !important;
        padding: 3px 10px !important;
        border-radius: 20px !important;
        font-weight: 700 !important;
        font-size: 0.8rem !important;
        display: inline-block !important;
    }

    .quantity-highlight {
        color: #38bdf8 !important;
        font-weight: 700 !important;
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

    /* Botón Filtros Avanzados adaptativo (Blanco en Modo Claro, Oscuro en Modo Nocturno) */
    #toggleFiltersBtn {
        background-color: #ffffff !important;
        color: #334155 !important;
        border: 1px solid #cbd5e1 !important;
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

    /* ==========================================================================
       Tarjetas de Indicadores Rápidos (KPIs) de Despachos
       ========================================================================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
        position: relative;
        z-index: 50;
    }

    .stat-card {
        background-color: var(--card-bg, #ffffff) !important;
        border: 1px solid var(--border-color, #e2e8f0) !important;
        border-radius: 12px;
        padding: 18px 20px;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
    }

    .stat-card .stat-label {
        font-size: 0.82rem;
        color: var(--text-muted, #64748b) !important;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .stat-card .stat-value {
        font-size: 1.55rem;
        font-weight: 800;
        margin-top: 6px;
    }

    .stat-card .stat-footer {
        font-size: 0.8rem;
        color: var(--text-muted, #64748b);
        font-weight: 600;
        margin-top: 6px;
    }

    .stat-pill {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
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
</style>

<div class="search-page-wrapper">
    <section class="page-head">
        <div>
            <h1>Buscador de Despachos de Combustible</h1>
            <p class="subtitle">Filtra y consulta en tiempo real entre todas las características del despacho</p>
        </div>
        <div class="actions" style="display: flex; gap: 10px;">
            <a class="button secondary" href="<?= BASE_URL ?>despachos/nuevo" style="background: #3b82f6; color: #ffffff; padding: 10px 16px; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"></path></svg>
                Registrar Despacho
            </a>
        </div>
    </section>

    <?php
        $selectedFuel = $filters['fuel_type'] ?? '';
        $isGnvFilter = stripos($selectedFuel, 'GNV') !== false || stripos($selectedFuel, 'Gas Natural') !== false;
        $avgGnv = $gnvCount > 0 ? $gnvTotal / $gnvCount : 0;
    ?>
    <!-- Cards de Indicadores (KPIs) de Despachos -->
    <section style="margin-bottom: 8px;">
        <div class="stats-grid" id="despachosStatsGrid" style="grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));">

            <!-- Card 1: Total Despachos -->
            <div class="stat-card" style="border-top: 3px solid #6366f1;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div class="stat-label">Total Despachos</div>
                        <div class="stat-value" style="color: #6366f1;" id="resCount"><?= (int) $count ?></div>
                        <div class="stat-footer">registros encontrados</div>
                    </div>
                    <div style="width:40px; height:40px; border-radius:10px; background:rgba(99,102,241,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>
                    </div>
                </div>
                <?php if ($liquidCount > 0 || $gnvCount > 0): ?>
                <div style="margin-top:10px; display:flex; gap:6px; flex-wrap:wrap;">
                    <?php if ($liquidCount > 0): ?>
                    <span class="stat-pill" style="background:rgba(37,99,235,0.12); color:#2563eb;"><?= $liquidCount ?> líquido<?= $liquidCount !== 1 ? 's' : '' ?></span>
                    <?php endif; ?>
                    <?php if ($gnvCount > 0): ?>
                    <span class="stat-pill" style="background:rgba(16,185,129,0.12); color:#059669;"><?= $gnvCount ?> GNV</span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Card 2: Combustible Líquido -->
            <div class="stat-card" style="border-top: 3px solid #2563eb;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div class="stat-label">Comb. Líquido Despachado</div>
                        <div class="stat-value" style="color: #2563eb;" id="resTotalGallons"><?= number_format((float) $liquidGallons, 2) ?> <span style="font-size:1rem; font-weight:600;">Gal</span></div>
                        <div class="stat-footer"><?= $liquidCount ?> despacho<?= $liquidCount !== 1 ? 's' : '' ?> · Prom: <?= number_format($liquidCount > 0 ? $liquidGallons / $liquidCount : 0, 2) ?> Gal</div>
                    </div>
                    <div style="width:40px; height:40px; border-radius:10px; background:rgba(37,99,235,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22V8a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v14"/><path d="M3 14h10"/><path d="M13 10h2a2 2 0 0 1 2 2v2a2 2 0 0 0 2 2h0a2 2 0 0 0 2-2V8l-3-3"/><path d="M18 5l2 2"/><line x1="3" y1="22" x2="13" y2="22"/></svg>
                    </div>
                </div>
                <?php if (!empty($breakdown)): ?>
                <div style="margin-top:10px; border-top:1px solid var(--border-color, #e2e8f0); padding-top:8px;">
                    <?php foreach ($breakdown as $b):
                        $bIsGnv = stripos($b['fuel_name'], 'GNV') !== false || stripos($b['fuel_name'], 'Gas Natural') !== false;
                        if ($bIsGnv) continue;
                        $bUnit = \App\Models\Catalog::getFuelUnit($b['fuel_name'], true);
                    ?>
                    <div style="display:flex; justify-content:space-between; font-size:0.78rem; margin-bottom:3px; color:var(--text-color);">
                        <span style="color:var(--text-muted); max-width:130px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?= htmlspecialchars($b['fuel_name']) ?>"><?= htmlspecialchars($b['fuel_name']) ?></span>
                        <span style="font-weight:700; color:#2563eb;"><?= number_format((float)$b['total'], 2) ?> <?= $bUnit ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Card 3: GNV (solo si hay despachos GNV) -->
            <?php if ($hasGnv): ?>
            <div class="stat-card" style="border-top: 3px solid #10b981;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div class="stat-label">GNV Despachado</div>
                        <div class="stat-value" style="color: #10b981;" id="resTotalGnv"><?= number_format((float) $gnvTotal, 2) ?> <span style="font-size:1rem; font-weight:600;">m³</span></div>
                        <div class="stat-footer"><?= $gnvCount ?> despacho<?= $gnvCount !== 1 ? 's' : '' ?> · Prom: <?= number_format($avgGnv, 2) ?> m³</div>
                    </div>
                    <div style="width:40px; height:40px; border-radius:10px; background:rgba(16,185,129,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    </div>
                </div>
                <div style="margin-top:10px; border-top:1px solid rgba(16,185,129,0.2); padding-top:8px;">
                    <div style="display:flex; align-items:center; gap:6px;">
                        <div style="width:8px; height:8px; border-radius:50%; background:#10b981; flex-shrink:0;"></div>
                        <span style="font-size:0.78rem; color:var(--text-muted);">Gas Natural Vehicular</span>
                        <span style="margin-left:auto; font-size:0.78rem; font-weight:700; color:#10b981;"><?= number_format((float)$gnvTotal, 2) ?> m³</span>
                    </div>
                    <div style="margin-top:6px; font-size:0.74rem; color:var(--text-muted);">
                        Unidad: metros cúbicos (m³)
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Card 4: Promedio General -->
            <div class="stat-card" style="border-top: 3px solid #f59e0b;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div class="stat-label">Promedio por Despacho</div>
                        <div class="stat-value" style="color: #f59e0b;" id="resAvgGallons"><?= number_format((float) ($liquidCount > 0 ? $liquidGallons / $liquidCount : 0), 2) ?> <span style="font-size:1rem; font-weight:600;">Gal</span></div>
                        <div class="stat-footer">combustible líquido</div>
                    </div>
                    <div style="width:40px; height:40px; border-radius:10px; background:rgba(245,158,11,0.12); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    </div>
                </div>
                <?php if ($hasGnv && $gnvCount > 0): ?>
                <div style="margin-top:10px; border-top:1px solid var(--border-color, #e2e8f0); padding-top:8px;">
                    <div style="display:flex; justify-content:space-between; font-size:0.78rem;">
                        <span style="color:var(--text-muted);">Prom. GNV</span>
                        <span style="font-weight:700; color:#10b981;"><?= number_format($avgGnv, 2) ?> m³</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </section>

    <!-- Formulario / Panel de Búsqueda y Filtros Multicriterio -->
    <section class="search-filter-card">
        <form id="searchForm" method="GET" action="<?= BASE_URL ?>despachos" onsubmit="return handleFormSubmit(event)">
            <!-- Barra de Búsqueda Principal General -->
            <div class="main-search-bar">
                <div class="search-input-group">
                    <svg class="search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input 
                        type="text" 
                        id="globalSearchInput" 
                        name="q" 
                        class="search-input" 
                        placeholder="Buscar por N° Recibo, Placa, Conductor, Destino, Tipo, Fecha..." 
                        value="<?= htmlspecialchars($filters['q']) ?>"
                        autocomplete="off"
                        maxlength="60"
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-_/ñÑ\s]/g, '')"
                    >
                    <?php if (!empty($filters['q'])): ?>
                        <button type="button" class="clear-search-btn" onclick="clearGlobalInput()">&times;</button>
                    <?php endif; ?>
                </div>

                <button type="button" id="toggleFiltersBtn" class="button secondary icon-btn" onclick="toggleAdvancedFilters()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filtros Avanzados
                </button>
            </div>

            <!-- Panel Plegable de Filtros por Características Específicas -->
            <div id="advancedFiltersPanel" class="advanced-filters-panel <?= (!empty($filters['vehicle']) || !empty($filters['driver']) || !empty($filters['destination']) || !empty($filters['date_from']) || !empty($filters['date_to']) || isset($filters['min_gallons']) || !empty($filters['status'])) ? 'show' : '' ?>">
                <div class="filters-grid" style="grid-template-columns: repeat(4, 1fr);">

                    <!-- Fila 1: Vehículo, Conductor, Tipo Combustible, Destino -->
                    <!-- 1. Vehículo / Placa -->
                    <div class="filter-field">
                        <label for="filterVehicle" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-vehicle"></use></svg>Vehículo / Placa</label>
                        <select id="filterVehicle" name="vehicle" class="filter-select">
                            <option value="">-- Todos los vehículos --</option>
                            <?php foreach ($vehicles as $v): ?>
                                <option value="<?= htmlspecialchars($v) ?>" <?= $filters['vehicle'] === $v ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 2. Conductor / Responsable -->
                    <div class="filter-field">
                        <label for="filterDriver" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-user"></use></svg>Conductor / Responsable</label>
                        <select id="filterDriver" name="driver" class="filter-select">
                            <option value="">-- Todos los conductores --</option>
                            <?php foreach ($drivers as $d): ?>
                                <option value="<?= htmlspecialchars($d) ?>" <?= $filters['driver'] === $d ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 3. Tipo de Combustible -->
                    <div class="filter-field">
                        <label for="filterFuelType" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use></svg>Tipo de Combustible</label>
                        <select id="filterFuelType" name="fuel_type" class="filter-select">
                            <option value="">-- Todos los tipos --</option>
                            <?php foreach ($fuelTypes as $ft): ?>
                                <option value="<?= htmlspecialchars($ft) ?>" <?= $filters['fuel_type'] === $ft ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ft) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 4. Destino / Área -->
                    <div class="filter-field">
                        <label for="filterDestination" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-pin"></use></svg>Destino / Área</label>
                        <select id="filterDestination" name="destination" class="filter-select">
                            <option value="">-- Todos los destinos --</option>
                            <?php foreach ($destinations as $dest): ?>
                                <option value="<?= htmlspecialchars($dest) ?>" <?= $filters['destination'] === $dest ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dest) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Fila 2: Fecha Desde, Fecha Hasta, Galones Mín, Galones Máx -->
                    <!-- 5. Fecha Desde -->
                    <div class="filter-field">
                        <label for="filterDateFrom" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-calendar"></use></svg>Fecha Desde</label>
                        <div style="position: relative;">
                            <input type="date" id="filterDateFrom" name="date_from" class="filter-input" value="<?= htmlspecialchars($filters['date_from']) ?>" style="padding-right: 36px;">
                            <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--text-muted,#64748b);">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                    </div>

                    <!-- 6. Fecha Hasta -->
                    <div class="filter-field">
                        <label for="filterDateTo" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-calendar"></use></svg>Fecha Hasta</label>
                        <div style="position: relative;">
                            <input type="date" id="filterDateTo" name="date_to" class="filter-input" value="<?= htmlspecialchars($filters['date_to']) ?>" style="padding-right: 36px;">
                            <span style="position:absolute; right:10px; top:50%; transform:translateY(-50%); pointer-events:none; color:var(--text-muted,#64748b);">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </span>
                        </div>
                    </div>

                    <!-- 7. Galones Mínimos -->
                    <div class="filter-field">
                        <label for="filterMinGallons" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use></svg>Galones Mínimos</label>
                        <input type="number" step="0.1" id="filterMinGallons" name="min_gallons" placeholder="0" class="filter-input" value="<?= $filters['min_gallons'] !== null ? htmlspecialchars((string) $filters['min_gallons']) : '' ?>">
                    </div>

                    <!-- 8. Galones Máximos -->
                    <div class="filter-field">
                        <label for="filterMaxGallons" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use></svg>Galones Máximos</label>
                        <input type="number" step="0.1" id="filterMaxGallons" name="max_gallons" placeholder="500" class="filter-input" value="<?= $filters['max_gallons'] !== null ? htmlspecialchars((string) $filters['max_gallons']) : '' ?>">
                    </div>

                </div>


                <div class="filter-actions-row">
                    <button type="button" class="button secondary small-btn" onclick="clearAllFilters()">Limpiar Todos los Filtros</button>
                </div>
            </div>
        </form>
    </section>

    <!-- Tabla de Resultados -->
    <section class="table-container">
        <div class="table-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2>Despachos Encontrados</h2>
                <p class="section-desc">Resultados filtrados dinámicamente según tus criterios</p>
            </div>
            <div class="limit-selector-wrap">
                <label for="limitSelector" style="font-size: 0.9rem; margin-right: 5px;">Mostrar:</label>
                <select id="limitSelector" class="limit-select-native" name="limit" form="searchForm" onchange="changeLimit()">
                    <option value="10" <?= isset($limit) && $limit == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= isset($limit) && $limit == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= isset($limit) && $limit == 50 ? 'selected' : '' ?>>50</option>
                </select>
            </div>
        </div>

        <div class="table-wrap">
            <table id="deliveriesTable">
                <thead>
                    <tr>
                        <th>N° Recibo / Vale</th>
                        <th>Fecha y Hora</th>
                        <th>Placa</th>
                        <th>Conductor</th>
                        <th>Tipo Combustible</th>
                        <th>Área</th>
                        <th>Observaciones</th>
                        <th>Cantidad</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="deliveriesTableBody">
                    <?php if (!empty($deliveries)): ?>
                        <?php foreach ($deliveries as $d): ?>
                            <tr class="delivery-row" onclick="openDetailModal(<?= htmlspecialchars(json_encode($d)) ?>)">
                                <td>
                                    <span class="receipt-tag"><?= htmlspecialchars($d['receipt_number']) ?></span>
                                </td>
                                <td>
                                    <span class="text-muted"><?= date('d/m/Y H:i', strtotime($d['created_at'])) ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($d['vehicle_plate']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($d['driver_name']) ?></td>
                                <td>
                                    <span class="fuel-type-pill"><?= htmlspecialchars($d['fuel_type'] ?? 'Diesel B5') ?></span>
                                </td>
                                <td><?= htmlspecialchars($d['destination'] ?? 'Despacho General') ?></td>
                                <td>
                                    <?php 
                                        $obs = trim($d['notes'] ?? '');
                                        if (empty($obs) || $obs === '-'):
                                    ?>
                                        <span style="font-size: 0.85rem; color: var(--muted);">-</span>
                                    <?php else: ?>
                                        <span style="font-size: 0.85rem; color: var(--muted); display: inline-block; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?= htmlspecialchars($obs) ?>">
                                            <?= htmlspecialchars($obs) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $dUnit = \App\Models\Catalog::getFuelUnit($d['fuel_type'] ?? '', true); ?>
                                    <span class="quantity-highlight"><?= number_format((float) $d['gallons'], 2) ?> <?= $dUnit ?></span>
                                </td>

                                <td style="white-space: nowrap;">
                                    <div style="display: flex; align-items: center; gap: 6px;">
                                        <button type="button"
                                            style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 10px; background: transparent; border: 1px solid var(--line, #334155); color: var(--ink); border-radius: 7px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background .15s, border-color .15s;"
                                            onmouseover="this.style.background='var(--hover-bg, rgba(255,255,255,.07))'"
                                            onmouseout="this.style.background='transparent'"
                                            onclick="event.stopPropagation(); openDetailModal(<?= htmlspecialchars(json_encode($d)) ?>)">
                                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Ver Detalle
                                        </button>
                                        <a href="<?= BASE_URL ?>despachos/imprimir?id=<?= $d['id'] ?>" target="_blank"
                                            onclick="event.stopPropagation();"
                                            title="Imprimir vale"
                                            style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #2563eb; color: #ffffff; border-radius: 7px; text-decoration: none; flex-shrink: 0; transition: background .15s;"
                                            onmouseover="this.style.background='#1d4ed8'"
                                            onmouseout="this.style.background='#2563eb'">
                                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="emptyRow">
                            <td colspan="9" class="empty-state">
                                <span class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-search"></use></svg>No se encontraron despachos de combustible que coincidan con la búsqueda.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-container" style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center; padding: 10px;">
            <div class="pagination-info" style="color: var(--muted); font-size: 0.9rem;">
                Mostrando página <span id="currentPageInfo"><?= $page ?></span> de <span id="totalPagesInfo"><?= $totalPages ?></span>
            </div>
            <div class="pagination-controls" style="display: flex; gap: 5px;">
                <button type="button" class="button secondary small-btn" id="prevPageBtn" onclick="changePage(<?= $page - 1 ?>)" <?= $page <= 1 ? 'disabled' : '' ?>>Anterior</button>
                <div id="pageButtons" style="display: flex; gap: 5px;">
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == 1 || $i == $totalPages || abs($i - $page) <= 2): ?>
                            <button type="button" class="button <?= $i == $page ? 'primary' : 'secondary' ?> small-btn" onclick="changePage(<?= $i ?>)"><?= $i ?></button>
                        <?php elseif (abs($i - $page) == 3): ?>
                            <span style="padding: 5px; color: var(--muted);">...</span>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <button type="button" class="button secondary small-btn" id="nextPageBtn" onclick="changePage(<?= $page + 1 ?>)" <?= $page >= $totalPages ? 'disabled' : '' ?>>Siguiente</button>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detalle del Despacho -->
<div id="dispatchModal" class="modal-overlay" onclick="closeDispatchModal(event)">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use></svg>Detalle del Despacho de Combustible</h3>
        </div>
        <div class="modal-body" id="dispatchModalBody">
            <!-- Inyectado por JavaScript -->
        </div>
        <div class="modal-footer">
            <button class="button secondary" onclick="closeDispatchModal()">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal Emergente Exclusivo para Observación Completa (INFO) -->
<div id="obsInfoModal" class="modal-overlay" onclick="closeObsModal(event)">
    <div class="modal-card" style="max-width: 480px;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-note"></use></svg>Observación Completa</h3>
        </div>
        <div class="modal-body">
            <div style="background: var(--bg-body, rgba(255,255,255,0.05)); padding: 16px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); font-size: 0.95rem; line-height: 1.5; color: var(--text-color, inherit); word-wrap: break-word;" id="obsModalContent">
            </div>
        </div>
        <div class="modal-footer">
            <button class="button secondary" onclick="closeObsModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    let currentPage = <?= $page ?>;

    document.addEventListener("DOMContentLoaded", () => {
        const globalInput = document.getElementById("globalSearchInput");

        // Búsqueda en tiempo real instantánea cliente/servidor al escribir
        let timer = null;
        globalInput.addEventListener("input", () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                currentPage = 1;
                performLiveSearch();
            }, 250);
        });

        // Escuchar cambios en selects para actualización instantánea
        document.querySelectorAll(".filter-select, .filter-input").forEach(el => {
            el.addEventListener("change", () => {
                currentPage = 1;
                performLiveSearch();
            });
        });

        // Escuchar cambios en inputs de número (galones) al perder el foco
        document.querySelectorAll(".filter-input[type='number']").forEach(el => {
            el.addEventListener("input", () => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    currentPage = 1;
                    performLiveSearch();
                }, 400);
            });
        });

        // Convertir todos los <select class="filter-select"> en custom dropdowns
        document.querySelectorAll('.filter-select').forEach(select => {
            initFilterCustomDropdown(select);
        });
    });

    /**
     * Convierte un <select class="filter-select"> en un dropdown visual
     * con el mismo estilo que el resto del sistema (.custom-select-container).
     * El <select> nativo queda oculto y sincronizado para que performLiveSearch() funcione.
     */
    function initFilterCustomDropdown(selectEl) {
        if (!selectEl || selectEl.dataset.customDropdown === 'true') return;
        selectEl.dataset.customDropdown = 'true';

        // Ocultar el select nativo pero mantenerlo en el DOM para el formulario
        selectEl.style.display = 'none';

        // Contenedor
        const container = document.createElement('div');
        container.className = 'custom-select-container';
        selectEl.parentNode.insertBefore(container, selectEl);
        container.appendChild(selectEl);

        // Trigger (cabecera visible del dropdown)
        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.tabIndex = 0;
        trigger.setAttribute('role', 'combobox');
        trigger.setAttribute('aria-haspopup', 'listbox');
        trigger.setAttribute('aria-expanded', 'false');

        // Menú desplegable
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
            const labelText   = isPlaceholder ? getPlaceholderText() : selected.text;
            const labelColor  = isPlaceholder
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

            // Botón × limpia el filtro
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
                const isSelected  = idx === selectEl.selectedIndex;
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
                        ? `<span class="item-check">${uiIcon('check')}</span>`
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

        // Abrir / cerrar al hacer clic en el trigger
        trigger.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = container.classList.toggle('open');
            trigger.setAttribute('aria-expanded', String(isOpen));
            if (isOpen) renderMenu();
        });

        // Navegación por teclado
        trigger.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); trigger.click(); }
            if (e.key === 'Escape') {
                container.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Cerrar al hacer clic fuera
        document.addEventListener('click', e => {
            if (!container.contains(e.target)) {
                container.classList.remove('open');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });

        // Sincronizar trigger si el select cambia desde fuera
        selectEl.addEventListener('change', () => {
            renderTrigger();
            renderMenu();
        });

        // Inicializar
        renderTrigger();
    }

    function toggleAdvancedFilters() {
        const panel = document.getElementById("advancedFiltersPanel");
        panel.classList.toggle("show");
    }

    function clearGlobalInput() {
        document.getElementById("globalSearchInput").value = "";
        currentPage = 1;
        performLiveSearch();
    }

    function clearAllFilters() {
        // Limpiar barra de búsqueda global
        document.getElementById("globalSearchInput").value = "";

        // Limpiar todos los selects y disparar 'change' para actualizar custom dropdowns
        document.querySelectorAll(".filter-select").forEach(el => {
            el.selectedIndex = 0;
            el.dispatchEvent(new Event("change", { bubbles: true }));
        });

        // Limpiar inputs: usar API de Flatpickr para fechas (evita el reflow/kickback)
        // y asignación directa para el resto (números, texto)
        document.querySelectorAll(".filter-input").forEach(el => {
            if (el._flatpickr) {
                el._flatpickr.clear(); // Limpia sin causar reflow visual
            } else {
                el.value = "";
            }
        });

        currentPage = 1;
        performLiveSearch();
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        currentPage = 1;
        performLiveSearch();
        return false;
    }

    function changePage(page) {
        currentPage = page;
        performLiveSearch();
    }

    function changeLimit() {
        currentPage = 1;
        performLiveSearch();
    }
    

    function performLiveSearch() {
        const form = document.getElementById("searchForm");
        const formData = new FormData(form);
        formData.append('page', currentPage);
        const params = new URLSearchParams(formData).toString();

        fetch(`<?= BASE_URL ?>api/despachos/buscar?${params}`)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    currentPage = res.page;
                    renderTableResults(res);
                    renderPagination(res.page, res.totalPages);
                }
            })
            .catch(err => console.error("Error al buscar:", err));
    }

    function renderPagination(page, totalPages) {
        document.getElementById('currentPageInfo').textContent = page;
        document.getElementById('totalPagesInfo').textContent = totalPages;
        
        const prevBtn = document.getElementById('prevPageBtn');
        prevBtn.disabled = page <= 1;
        prevBtn.onclick = () => changePage(page - 1);
        
        const nextBtn = document.getElementById('nextPageBtn');
        nextBtn.disabled = page >= totalPages;
        nextBtn.onclick = () => changePage(page + 1);
        
        const pageButtons = document.getElementById('pageButtons');
        pageButtons.innerHTML = '';
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || Math.abs(i - page) <= 2) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = `button ${i === page ? 'primary' : 'secondary'} small-btn`;
                btn.textContent = i;
                btn.onclick = () => changePage(i);
                pageButtons.appendChild(btn);
            } else if (Math.abs(i - page) === 3) {
                const span = document.createElement('span');
                span.style.padding = '5px';
                span.style.color = 'var(--muted)';
                span.textContent = '...';
                pageButtons.appendChild(span);
            }
        }
    }

    function renderTableResults(res) {
        const deliveries = res.data || [];
        const count = res.count ?? 0;
        const totalGallons = res.total_gallons ?? '0.00';
        const liquidGallons = res.liquid_gallons ?? totalGallons;
        const gnvTotal = res.gnv_total ?? '0.00';
        const liquidCount = res.liquid_count ?? count;

        const countEl = document.getElementById("resCount");
        if (countEl) countEl.textContent = count;

        const fuelTypeFilter = (document.getElementById("filterFuelType")?.value || '').trim();
        const isGnvFilter = fuelTypeFilter.toUpperCase().includes('GNV') || fuelTypeFilter.toUpperCase().includes('GAS NATURAL');
        const filterUnitSuffix = isGnvFilter ? 'm³' : 'Gal';

        const totalGalEl = document.getElementById("resTotalGallons");
        if (totalGalEl) {
            totalGalEl.innerHTML = `${escapeHtml(String(liquidGallons))} <span style="font-size:1rem; font-weight:600;">${filterUnitSuffix}</span>`;
        }

        const totalGnvEl = document.getElementById("resTotalGnv");
        if (totalGnvEl) {
            totalGnvEl.innerHTML = `${escapeHtml(String(gnvTotal))} <span style="font-size:1rem; font-weight:600;">m³</span>`;
        }
        
        const avg = liquidCount > 0 ? (parseFloat(String(liquidGallons).replace(/,/g, '')) / liquidCount).toFixed(2) : '0.00';
        const avgEl = document.getElementById("resAvgGallons");
        if (avgEl) {
            avgEl.innerHTML = `${avg} <span style="font-size:1rem; font-weight:600;">${filterUnitSuffix}</span>`;
        }

        const tbody = document.getElementById("deliveriesTableBody");
        tbody.innerHTML = "";

        if (deliveries.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="empty-state">
                        <span class="icon-with-text">${uiIcon('search')}No se encontraron despachos de combustible que coincidan con los filtros aplicados.</span>
                    </td>
                </tr>
            `;
            return;
        }

        deliveries.forEach(d => {
            const tr = document.createElement("tr");
            tr.className = "delivery-row";
            tr.onclick = () => openDetailModal(d);

            const isGnv = (d.fuel_type || '').toUpperCase().includes('GNV') || (d.fuel_type || '').toUpperCase().includes('GAS NATURAL');
            const unitBadge = isGnv ? 'm³' : 'Gal';
            const gallonsFormatted = parseFloat(d.gallons || 0).toFixed(2);
            
            // Formato de fecha consistente DD/MM/YYYY HH:mm sin desfases ni saltos
            let dateStr = d.created_at || '';
            const matchDate = String(d.created_at || '').match(/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/);
            if (matchDate) {
                dateStr = `${matchDate[3]}/${matchDate[2]}/${matchDate[1]} ${matchDate[4]}:${matchDate[5]}`;
            }

            let obsHtml = `<span style="font-size: 0.85rem; color: var(--muted);">-</span>`;
            const obsText = (d.notes || '').trim();
            if (obsText && obsText !== '-') {
                obsHtml = `
                    <span style="font-size: 0.85rem; color: var(--muted); display: inline-block; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${escapeHtml(obsText)}">
                        ${escapeHtml(obsText)}
                    </span>
                `;
            }

            tr.innerHTML = `
                <td><span class="receipt-tag">${escapeHtml(d.receipt_number)}</span></td>
                <td><span class="text-muted" style="white-space: nowrap;">${escapeHtml(dateStr)}</span></td>
                <td><strong>${escapeHtml(d.vehicle_plate)}</strong></td>
                <td>${escapeHtml(d.driver_name)}</td>
                <td><span class="fuel-type-pill">${escapeHtml(d.fuel_type || 'Diesel B5')}</span></td>
                <td>${escapeHtml(d.destination || 'Despacho General')}</td>
                <td>${obsHtml}</td>
                <td><span class="quantity-highlight">${gallonsFormatted} ${unitBadge}</span></td>

                <td style="white-space: nowrap;">
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <button type="button"
                            style="display: inline-flex; align-items: center; gap: 5px; padding: 6px 10px; background: transparent; border: 1px solid var(--line, #334155); color: var(--ink); border-radius: 7px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: background .15s, border-color .15s;"
                            onmouseover="this.style.background='var(--hover-bg, rgba(255,255,255,.07))'"
                            onmouseout="this.style.background='transparent'"
                            onclick="event.stopPropagation(); openDetailModal(${JSON.stringify(d).replace(/"/g, '&quot;')})">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            Ver Detalle
                        </button>
                        <a href="<?= BASE_URL ?>despachos/imprimir?id=${d.id}" target="_blank"
                            onclick="event.stopPropagation();"
                            title="Imprimir vale"
                            style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: #2563eb; color: #ffffff; border-radius: 7px; text-decoration: none; flex-shrink: 0; transition: background .15s;"
                            onmouseover="this.style.background='#1d4ed8'"
                            onmouseout="this.style.background='#2563eb'">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                        </a>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function openDetailModal(d) {
        const body = document.getElementById("dispatchModalBody");
        const dateObj = new Date(String(d.created_at || '').replace(' ', 'T'));
        const dateStr = !isNaN(dateObj) ? dateObj.toLocaleString('es-PE') : d.created_at;

        const vehicleUnitText = d.vehicle_unit_code && d.vehicle_unit_code !== '-' ? ` (${escapeHtml(d.vehicle_unit_code)})` : '';
        const vehicleTypeText = d.vehicle_type_name || 'Vehículo Municipal';
        const registeredByText = d.registered_by || 'Administrador';
        const notesText = d.notes && d.notes.trim() !== '' ? d.notes : 'Sin observaciones';

        body.innerHTML = `
            <div class="modal-detail-card">
                <div class="modal-badge-row">

                    <span class="modal-date-tag icon-with-text">${uiIcon('calendar')}Fecha y Hora: <strong>${escapeHtml(dateStr)}</strong></span>
                </div>
                
                <h4 style="margin-top: 10px; font-size: 1.15rem; color: var(--primary);">Vale N°: ${escapeHtml(d.receipt_number)}</h4>

                <div class="modal-grid">
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('vehicle')}Vehículo / Placa:</span>
                        <strong>${escapeHtml(d.vehicle_plate)}${vehicleUnitText}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('truck')}Tipo de Vehículo:</span>
                        <strong>${escapeHtml(vehicleTypeText)}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('user')}Conductor / Responsable:</span>
                        <strong>${escapeHtml(d.driver_name)}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('fuel')}Tipo de Combustible:</span>
                        <strong style="color: var(--primary);">${escapeHtml(d.fuel_type || 'Diesel B5 S-50')}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('pin')}Destino / Área:</span>
                        <span>${escapeHtml(d.destination || 'Despacho General')}</span>
                    </div>
                    <div class="modal-field">
                        <span class="lbl icon-with-text">${uiIcon('user')}Registrado por:</span>
                        <span>${escapeHtml(registeredByText)}</span>
                    </div>
                    
                    <div class="modal-field" style="grid-column: span 2; background: var(--bg-body); padding: 10px 12px; border-radius: 8px; border: 1px dashed var(--border-color, var(--line)); margin-top: 4px; overflow-wrap: break-word; word-break: break-word; hyphens: auto;">
                        <span class="lbl icon-with-text">${uiIcon('note')}Observaciones:</span>
                        <span style="color: var(--ink); font-style: italic; font-weight: 500; font-size: 0.92em; margin-top: 4px; display: block; word-break: break-word; white-space: pre-wrap;">${escapeHtml(notesText)}</span>
                    </div>

                    <div class="modal-field highlight-field">
                        <span class="lbl icon-with-text">${uiIcon('fuel')}Cantidad Despachada:</span>
                        <strong class="qty-val">${parseFloat(d.gallons).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} ${((d.fuel_type || '').toUpperCase().includes('GNV') || (d.fuel_type || '').toUpperCase().includes('GAS NATURAL')) ? 'm³' : 'Galones'}</strong>
                    </div>
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <a href="<?= BASE_URL ?>despachos/imprimir?id=${d.id}" target="_blank" style="padding: 10px 20px; background: #2563eb; color: #ffffff; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                        ${uiIcon('print')} Imprimir Vale Oficial
                    </a>
                </div>
            </div>
        `;

        document.getElementById("dispatchModal").classList.add("active");
    }

    function closeDispatchModal(e) {
        document.getElementById("dispatchModal").classList.remove("active");
    }

    function showObsModal(obsText) {
        document.getElementById("obsModalContent").textContent = obsText;
        document.getElementById("obsInfoModal").classList.add("active");
    }

    function closeObsModal(e) {
        document.getElementById("obsInfoModal").classList.remove("active");
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
</script>