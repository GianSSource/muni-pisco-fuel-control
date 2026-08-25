<div class="search-page-wrapper">
    <section class="page-head">
        <div>
            <h1>Buscador de Despachos de Combustible</h1>
            <p class="subtitle">Filtra y consulta en tiempo real entre todas las características del despacho</p>
        </div>
        <div class="actions">
            <a class="button primary" href="<?= BASE_URL ?>proyectos/nuevo">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"></path></svg>
                Nuevo Despacho
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
                        placeholder="Buscar por N° Recibo, Placa, Conductor, Destino, Estado..." 
                        value="<?= htmlspecialchars($filters['q']) ?>"
                        autocomplete="off"
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
                        <label for="filterVehicle">🚗 Vehículo / Placa</label>
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
                        <label for="filterDriver">👤 Conductor / Responsable</label>
                        <select id="filterDriver" name="driver" class="filter-select">
                            <option value="">-- Todos los conductores --</option>
                            <?php foreach ($drivers as $d): ?>
                                <option value="<?= htmlspecialchars($d) ?>" <?= $filters['driver'] === $d ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- 3. Destino / Área -->
                    <div class="filter-field">
                        <label for="filterDestination">📍 Destino / Área</label>
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
                        <label for="filterDateFrom">📅 Fecha Desde</label>
                        <input type="date" id="filterDateFrom" name="date_from" class="filter-input" value="<?= htmlspecialchars($filters['date_from']) ?>">
                    </div>

                    <!-- 5. Rango de Fechas: Hasta -->
                    <div class="filter-field">
                        <label for="filterDateTo">📅 Fecha Hasta</label>
                        <input type="date" id="filterDateTo" name="date_to" class="filter-input" value="<?= htmlspecialchars($filters['date_to']) ?>">
                    </div>

                    <!-- 6. Galones Mínimos -->
                    <div class="filter-field">
                        <label for="filterMinGallons">⛽ Galones Mínimos</label>
                        <input type="number" step="0.1" id="filterMinGallons" name="min_gallons" placeholder="0" class="filter-input" value="<?= $filters['min_gallons'] !== null ? htmlspecialchars((string) $filters['min_gallons']) : '' ?>">
                    </div>

                    <!-- 7. Galones Máximos -->
                    <div class="filter-field">
                        <label for="filterMaxGallons">⛽ Galones Máximos</label>
                        <input type="number" step="0.1" id="filterMaxGallons" name="max_gallons" placeholder="500" class="filter-input" value="<?= $filters['max_gallons'] !== null ? htmlspecialchars((string) $filters['max_gallons']) : '' ?>">
                    </div>

                    <!-- 8. Estado -->
                    <div class="filter-field">
                        <label for="filterStatus">📌 Estado</label>
                        <select id="filterStatus" name="status" class="filter-select">
                            <option value="">-- Todos los estados --</option>
                            <option value="Completado" <?= $filters['status'] === 'Completado' ? 'selected' : '' ?>>Completado</option>
                            <option value="Pendiente" <?= $filters['status'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        </select>
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
        <div class="table-header">
            <div>
                <h2>Despachos Encontrados</h2>
                <p class="section-desc">Resultados filtrados dinámicamente según tus criterios</p>
            </div>
        </div>

        <div class="table-wrap">
            <table id="deliveriesTable">
                <thead>
                    <tr>
                        <th>N° Recibo / Vale</th>
                        <th>Fecha y Hora</th>
                        <th>Vehículo / Placa</th>
                        <th>Conductor</th>
                        <th>Destino / Área</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
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
                                <td><?= htmlspecialchars($d['destination'] ?? 'Despacho General') ?></td>
                                <td>
                                    <span class="quantity-highlight"><?= number_format((float) $d['gallons'], 2) ?> Gal</span>
                                </td>
                                <td>
                                    <span class="status-pill status-completed">
                                        <?= htmlspecialchars($d['status'] ?? 'Completado') ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="button secondary small-btn" onclick="event.stopPropagation(); openDetailModal(<?= htmlspecialchars(json_encode($d)) ?>)">Ver Detalle</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="emptyRow">
                            <td colspan="8" class="empty-state">
                                🔍 No se encontraron despachos de combustible que coincidan con la búsqueda.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Modal Detalle del Despacho -->
<div id="dispatchModal" class="modal-overlay" onclick="closeDispatchModal(event)">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>⛽ Detalle del Despacho de Combustible</h3>
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

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const globalInput = document.getElementById("globalSearchInput");
        
        // Búsqueda en tiempo real instantánea cliente/servidor al escribir
        let timer = null;
        globalInput.addEventListener("input", () => {
            clearTimeout(timer);
            timer = setTimeout(() => {
                performLiveSearch();
            }, 250);
        });

        // Escuchar cambios en selects para actualización instantánea
        document.querySelectorAll(".filter-select, .filter-input").forEach(el => {
            el.addEventListener("change", () => {
                performLiveSearch();
            });
        });
    });

    function toggleAdvancedFilters() {
        const panel = document.getElementById("advancedFiltersPanel");
        panel.classList.toggle("show");
    }

    function clearGlobalInput() {
        document.getElementById("globalSearchInput").value = "";
        performLiveSearch();
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        performLiveSearch();
        return false;
    }

    function performLiveSearch() {
        const form = document.getElementById("searchForm");
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();

        fetch(`<?= BASE_URL ?>api/despachos/buscar?${params}`)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    renderTableResults(res.data, res.count, res.total_gallons);
                }
            })
            .catch(err => console.error("Error al buscar:", err));
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
                    <td colspan="8" class="empty-state">
                        🔍 No se encontraron despachos de combustible que coincidan con los filtros aplicados.
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
            const dateObj = new Date(d.created_at);
            const dateStr = !isNaN(dateObj) 
                ? `${String(dateObj.getDate()).padStart(2, '0')}/${String(dateObj.getMonth() + 1).padStart(2, '0')}/${dateObj.getFullYear()} ${String(dateObj.getHours()).padStart(2, '0')}:${String(dateObj.getMinutes()).padStart(2, '0')}`
                : d.created_at;

            tr.innerHTML = `
                <td><span class="receipt-tag">${escapeHtml(d.receipt_number)}</span></td>
                <td><span class="text-muted">${escapeHtml(dateStr)}</span></td>
                <td><strong>${escapeHtml(d.vehicle_plate)}</strong></td>
                <td>${escapeHtml(d.driver_name)}</td>
                <td>${escapeHtml(d.destination || 'Despacho General')}</td>
                <td><span class="quantity-highlight">${gallonsFormatted} Gal</span></td>
                <td><span class="status-pill status-completed">${escapeHtml(d.status || 'Completado')}</span></td>
                <td>
                    <button type="button" class="button secondary small-btn" onclick="event.stopPropagation(); openDetailModal(${JSON.stringify(d).replace(/"/g, '&quot;')})">Ver Detalle</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function openDetailModal(d) {
        const body = document.getElementById("dispatchModalBody");
        const dateObj = new Date(d.created_at);
        const dateStr = !isNaN(dateObj) ? dateObj.toLocaleString('es-PE') : d.created_at;

        body.innerHTML = `
            <div class="modal-detail-card">
                <div class="modal-badge-row">
                    <span class="badge badge-success">${escapeHtml(d.status || 'Completado')}</span>
                    <span class="modal-date-tag">📅 Fecha y Hora: <strong>${escapeHtml(dateStr)}</strong></span>
                </div>
                
                <h4 style="margin-top: 10px;">Vale N°: ${escapeHtml(d.receipt_number)}</h4>

                <div class="modal-grid">
                    <div class="modal-field">
                        <span class="lbl">🚗 Vehículo / Placa:</span>
                        <strong>${escapeHtml(d.vehicle_plate)}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl">👤 Conductor / Responsable:</span>
                        <strong>${escapeHtml(d.driver_name)}</strong>
                    </div>
                    <div class="modal-field">
                        <span class="lbl">📍 Destino / Área:</span>
                        <span>${escapeHtml(d.destination || 'Despacho General')}</span>
                    </div>
                    <div class="modal-field">
                        <span class="lbl">📌 Estado:</span>
                        <span>${escapeHtml(d.status || 'Completado')}</span>
                    </div>
                    <div class="modal-field highlight-field">
                        <span class="lbl">⛽ Cantidad Despachada:</span>
                        <strong class="qty-val">${parseFloat(d.gallons).toFixed(2)} Galones</strong>
                    </div>
                </div>
            </div>
        `;

        document.getElementById("dispatchModal").classList.add("active");
    }

    function closeDispatchModal(e) {
        document.getElementById("dispatchModal").classList.remove("active");
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
