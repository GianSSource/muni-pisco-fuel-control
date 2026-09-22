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
</style>

<div class="search-page-wrapper">
    <section class="page-head"
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-download"></use></svg>Buscador e Historial de Ingresos de Combustible</h1>
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
            <a class="btn-secondary-dark" href="<?= BASE_URL ?>dashboard">← Volver al Dashboard</a>
        </div>
    </section>

    <?php if (!empty($success)): ?>
        <div
            style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.4); color: #10b981; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            <span class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-check"></use></svg><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <!-- Resumen rápido de Ingresos -->
    <?php
    $totalGallons = 0;
    $totalRemaining = 0;
    $activeLots = 0;
    if (!empty($incomes)) {
        foreach ($incomes as $inc) {
            $totalGallons += (float) ($inc['gallons'] ?? 0);
            $rem = (float) ($inc['gallons_remaining'] ?? 0);
            $totalRemaining += $rem;
            if ($rem > 0)
                $activeLots++;
        }
    }
    ?>
    <section class="stats-grid"
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="stat-card">
            <span class="stat-label">Total Histórico Ingresado</span>
            <div class="stat-value" style="color: #2563eb;"><?= number_format($totalGallons, 2) ?> Gal</div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Stock Remanente en Lotes</span>
            <div class="stat-value" style="color: #10b981;"><?= number_format($totalRemaining, 2) ?> Gal</div>
        </div>
        <div class="stat-card">
            <span class="stat-label">Lotes con Stock Activo</span>
            <div class="stat-value" style="color: #8b5cf6;"><?= $activeLots ?> Lotes</div>
        </div>
    </section>

    <!-- Tarjeta del Buscador -->
    <section class="search-card" style="margin-bottom: 24px;">
        <div style="display: flex; gap: 12px; align-items: center;">
            <div style="position: relative; flex: 1;">
                <input type="text" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-_/ñÑáéíóúÁÉÍÓÚ\s.,]/g, '')"
                    id="incomeSearchInput" class="search-input-dark"
                    placeholder="Buscar por N° Recibo, Proveedor, Tipo de Combustible, Fecha..." maxlength="60">
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
                    </tr>
                </thead>
                <tbody id="incomeTableBody">
                    <?php if (!empty($incomes)): ?>
                        <?php foreach ($incomes as $inc): ?>
                            <?php
                            $rem = (float) ($inc['gallons_remaining'] ?? 0);
                            $isAvailable = $rem > 0;
                            ?>
                            <tr class="income-row">
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
                                    <?= number_format((float) $inc['gallons'], 2) ?> Gal
                                </td>
                                <td style="font-weight: 800; color: <?= $isAvailable ? '#10b981' : '#ef4444' ?>;">
                                    <?= number_format($rem, 2) ?> Gal
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
                                                <svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-info"></use></svg> INFO
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
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr id="noIncomesRow">
                            <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted, #64748b);">
                                No hay ingresos registrados en el sistema.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-muted, #64748b);">
                            <span class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-search"></use></svg>No se encontraron ingresos que coincidan con la búsqueda.</span>
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

<!-- Modal Emergente Exclusivo para Observación Completa (INFO) en Ingresos -->
<div id="obsInfoModal" class="modal-overlay" onclick="closeObsModal(event)">
    <div class="modal-card" style="max-width: 480px;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="icon-with-text"><svg class="ui-icon"><use href="<?= BASE_URL ?>assets/icons.svg#icon-note"></use></svg>Observación Completa</h3>
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

<script>
    let currentPage = 1;
    let currentLimit = 10;
    let allIncomeRows = [];

    document.addEventListener("DOMContentLoaded", () => {
        allIncomeRows = Array.from(document.querySelectorAll("#incomeTableBody tr.income-row"));

        const searchInput = document.getElementById("incomeSearchInput");
        if (searchInput) {
            searchInput.addEventListener("input", () => {
                currentPage = 1;
                renderIncomeTable();
            });
        }

        renderIncomeTable();
    });

    function getFilteredIncomeRows() {
        const term = document.getElementById("incomeSearchInput")
            ? document.getElementById("incomeSearchInput").value.toLowerCase().trim()
            : "";
        if (!term) return allIncomeRows;
        return allIncomeRows.filter(row => row.innerText.toLowerCase().includes(term));
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
</script>
