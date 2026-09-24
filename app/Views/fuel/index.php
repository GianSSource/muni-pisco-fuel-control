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

    <!-- Métricas del Resultado de Búsqueda -->
    <section class="search-summary-chips">
        <div class="summary-chip">
            <span class="chip-label">Resultados Encontrados</span>
            <strong class="chip-val" id="resCount"><?= (int) $count ?></strong>
        </div>
        <div class="summary-chip">
            <span class="chip-label">Total Galones Filtrados</span>
            <strong class="chip-val highlight-blue" id="resTotalGallons"><?= number_format((float) $totalGallons, 2) ?> Gal</strong>
        </div>
        <div class="summary-chip">
            <span class="chip-label">Promedio por Despacho</span>
            <strong class="chip-val" id="resAvgGallons"><?= number_format((float) $avgGallons, 2) ?> Gal</strong>
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
                        oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-_/ñÑ]/g, '')"
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
                <div class="filters-grid">
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

                    <!-- 2b. Tipo de Combustible -->
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

                    <!-- 3. Destino / Área -->
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

                    <!-- 4. Rango de Fechas: Desde -->
                    <div class="filter-field">
                        <label for="filterDateFrom" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-calendar"></use></svg>Fecha Desde</label>
                        <input type="date" id="filterDateFrom" name="date_from" class="filter-input" value="<?= htmlspecialchars($filters['date_from']) ?>">
                    </div>

                    <!-- 5. Rango de Fechas: Hasta -->
                    <div class="filter-field">
                        <label for="filterDateTo" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-calendar"></use></svg>Fecha Hasta</label>
                        <input type="date" id="filterDateTo" name="date_to" class="filter-input" value="<?= htmlspecialchars($filters['date_to']) ?>">
                    </div>

                    <!-- 6. Galones Mínimos -->
                    <div class="filter-field">
                        <label for="filterMinGallons" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use></svg>Galones Mínimos</label>
                        <input type="number" step="0.1" id="filterMinGallons" name="min_gallons" placeholder="0" class="filter-input" value="<?= $filters['min_gallons'] !== null ? htmlspecialchars((string) $filters['min_gallons']) : '' ?>">
                    </div>

                    <!-- 7. Galones Máximos -->
                    <div class="filter-field">
                        <label for="filterMaxGallons" class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-fuel"></use></svg>Galones Máximos</label>
                        <input type="number" step="0.1" id="filterMaxGallons" name="max_gallons" placeholder="500" class="filter-input" value="<?= $filters['max_gallons'] !== null ? htmlspecialchars((string) $filters['max_gallons']) : '' ?>">
                    </div>


                </div>

                <div class="filter-actions-row">
                    <a href="<?= BASE_URL ?>despachos" class="button secondary small-btn">Limpiar Todos los Filtros</a>
                    <button type="submit" class="button primary small-btn">Aplicar Filtros</button>
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
                                    <span class="quantity-highlight"><?= number_format((float) $d['gallons'], 2) ?> Gal</span>
                                </td>

                                <td>
                                    <button type="button" class="button secondary small-btn" onclick="event.stopPropagation(); openDetailModal(<?= htmlspecialchars(json_encode($d)) ?>)">Ver Detalle</button>
                                    <a href="<?= BASE_URL ?>despachos/imprimir?id=<?= $d['id'] ?>" target="_blank" onclick="event.stopPropagation();" style="padding: 4px 10px; background: #2563eb; color: #ffffff; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-left: 6px;">
                                        <svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-print"></use></svg> Imprimir
                                    </a>
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
            <button class="modal-close-btn" onclick="closeDispatchModal()">&times;</button>
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
            <button class="modal-close-btn" onclick="closeObsModal()">&times;</button>
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
                    renderTableResults(res.data, res.count, res.total_gallons);
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

    function renderTableResults(deliveries, count, totalGallons) {
        document.getElementById("resCount").textContent = count;
        document.getElementById("resTotalGallons").textContent = `${totalGallons} Gal`;
        
        const avg = count > 0 ? (parseFloat(totalGallons.replace(/,/g, '')) / count).toFixed(2) : '0.00';
        document.getElementById("resAvgGallons").textContent = `${avg} Gal`;

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

            const gallonsFormatted = parseFloat(d.gallons).toFixed(2);
            const dateObj = new Date(String(d.created_at || '').replace(' ', 'T'));
            const dateStr = !isNaN(dateObj) 
                ? `${String(dateObj.getDate()).padStart(2, '0')}/${String(dateObj.getMonth() + 1).padStart(2, '0')}/${dateObj.getFullYear()} ${String(dateObj.getHours()).padStart(2, '0')}:${String(dateObj.getMinutes()).padStart(2, '0')}`
                : d.created_at;

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
                <td><span class="text-muted">${escapeHtml(dateStr)}</span></td>
                <td><strong>${escapeHtml(d.vehicle_plate)}</strong></td>
                <td>${escapeHtml(d.driver_name)}</td>
                <td><span class="fuel-type-pill">${escapeHtml(d.fuel_type || 'Diesel B5')}</span></td>
                <td>${escapeHtml(d.destination || 'Despacho General')}</td>
                <td>${obsHtml}</td>
                <td><span class="quantity-highlight">${gallonsFormatted} Gal</span></td>

                <td>
                    <button type="button" class="button secondary small-btn" onclick="event.stopPropagation(); openDetailModal(${JSON.stringify(d).replace(/"/g, '&quot;')})">Ver Detalle</button>
                    <a href="<?= BASE_URL ?>despachos/imprimir?id=${d.id}" target="_blank" onclick="event.stopPropagation();" style="padding: 4px 10px; background: #2563eb; color: #ffffff; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; margin-left: 6px;">
                        ${uiIcon('print')} Imprimir
                    </a>
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
                        <strong class="qty-val">${parseFloat(d.gallons).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})} Galones</strong>
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