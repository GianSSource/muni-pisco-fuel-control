<div class="dashboard-wrapper">

    <!-- Exportación y Reportes de Entregas con Filtros -->
    <section class="reports-section">
        <div class="reports-header">
            <h2>Exportación y Reportes de Entregas</h2>
            <p class="section-desc">Filtra por rango de fechas, unidad o responsable y descarga en PDF o Excel</p>
        </div>

        <?php
        $firstDayOfMonth = date('Y-m-01');
        $todayDate = date('Y-m-d');
        ?>
        <form id="exportReportForm" method="GET" action="<?= BASE_URL ?>reportes/pdf" target="_blank"
            class="report-filters-form" onsubmit="return validarExportacionReporte(event)">
            <div class="filter-group">
                <label for="desde">Desde (Fecha inicio):</label>
                <input type="date" id="desde" name="desde" value="<?= $firstDayOfMonth ?>" required>
            </div>

            <div class="filter-group">
                <label for="hasta">Hasta (Fecha fin):</label>
                <input type="date" id="hasta" name="hasta" value="<?= $todayDate ?>" required>
            </div>

            <div class="filter-group" id="area-filter-group">
                <label for="area">Por Área:</label>
                <!-- Select nativo oculto - reemplazado por custom dropdown -->
                <select name="area" id="area" style="display:none;">
                    <option value="">Todas las Áreas</option>
                    <?php if (!empty($catalogAreas)): ?>
                        <?php foreach ($catalogAreas as $areaItem): ?>
                            <option value="<?= htmlspecialchars($areaItem) ?>"><?= htmlspecialchars($areaItem) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="unidad">Por Placa:</label>
                <select name="unidad" id="unidad" style="display:none;">
                    <option value="">Todas las Placas</option>
                    <?php if (!empty($uniquePlates)): ?>
                        <?php foreach ($uniquePlates as $plate): ?>
                            <option value="<?= htmlspecialchars($plate) ?>"><?= htmlspecialchars($plate) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="filter-group">
                <label for="responsable">Por Conductor:</label>
                <select name="responsable" id="responsable" style="display:none;">
                    <option value="">Todos los Conductores</option>
                    <?php if (!empty($uniqueDrivers)): ?>
                        <?php foreach ($uniqueDrivers as $driver): ?>
                            <option value="<?= htmlspecialchars($driver) ?>"><?= htmlspecialchars($driver) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
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
    function validarExportacionReporte(e) {
        const inputDesde = document.getElementById("desde");
        const inputHasta = document.getElementById("hasta");

        if (!inputDesde || !inputHasta) return true;

        const valDesde = inputDesde.value;
        const valHasta = inputHasta.value;

        if (!valDesde || !valHasta) {
            alert("Por favor, seleccione un rango de fechas válido para exportar el reporte.");
            return false;
        }

        const dateDesde = new Date(valDesde + "T00:00:00");
        const dateHasta = new Date(valHasta + "T00:00:00");

        if (dateHasta < dateDesde) {
            alert("La Fecha Fin no puede ser anterior a la Fecha Inicio.");
            inputHasta.focus();
            return false;
        }

        const diffTime = Math.abs(dateHasta - dateDesde);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays > 366) {
            alert("El rango máximo de exportación es de 1 año (365 días). Por favor reduzca el período de fechas.");
            return false;
        }

        return true;
    }

    // ── Custom Dropdown para el filtro "Por Área" en Exportación ──
    function setupAreaCustomDropdown(selectId) {
        const selectEl = document.getElementById(selectId);
        if (!selectEl) return;

        const group = selectEl.closest('.filter-group') || selectEl.parentNode;

        const options = Array.from(selectEl.options).map(opt => ({ value: opt.value, text: opt.text }));

        const container = document.createElement('div');
        container.className = 'custom-select-container';
        group.appendChild(container);
        container.appendChild(selectEl);
        selectEl.style.display = 'none';

        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.tabIndex = 0;

        const menu = document.createElement('div');
        menu.className = 'custom-select-menu';

        container.appendChild(trigger);
        container.appendChild(menu);

        function updateUI() {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (!selectedOpt) return;

            const isPlaceholder = selectedOpt.value === '';
            const labelText = isPlaceholder ? 'Todas las Áreas' : selectedOpt.text;
            const labelColor = isPlaceholder ? 'var(--text-muted, #94a3b8)' : 'var(--text-color, #0f172a)';

            trigger.innerHTML = `
                <div class="custom-select-selected-content">
                    <span class="custom-select-label-text" style="font-weight:600; font-size:0.9rem; color:${labelColor};">${labelText}</span>
                </div>
            `;

            menu.innerHTML = '';
            options.forEach((opt, idx) => {
                const isSelected = idx === selectEl.selectedIndex;
                const item = document.createElement('div');
                item.className = `custom-select-item ${isSelected ? 'selected' : ''}`;
                item.innerHTML = `
                    <div class="item-left" style="flex:1; min-width:0;">
                        <span class="item-title" style="font-size:0.88rem; font-weight:600; color:var(--text-color, #0f172a);">${opt.text}</span>
                    </div>
                    ${isSelected ? `<span class="item-check">${uiIcon('check')}</span>` : ''}
                `;
                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectEl.selectedIndex = idx;
                    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                    container.classList.remove('open');
                });
                menu.appendChild(item);
            });
        }

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            document.querySelectorAll('.custom-select-container.open').forEach(c => {
                if (c !== container) c.classList.remove('open');
            });
            container.classList.toggle('open');
        });

        document.addEventListener('click', (e) => {
            if (!container.contains(e.target)) {
                container.classList.remove('open');
            }
        });

        selectEl.addEventListener('change', () => {
            updateUI();
        });

        updateUI();
    }

    // ── Custom Dropdown con buscador para Placa y Conductor en Exportación ──
    function setupSearchableExportDropdown(selectId, placeholder) {
        const selectEl = document.getElementById(selectId);
        if (!selectEl) return;

        const group = selectEl.closest('.filter-group') || selectEl.parentNode;

        const container = document.createElement('div');
        container.className = 'custom-select-container';
        group.appendChild(container);
        container.appendChild(selectEl);
        selectEl.style.display = 'none';

        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        trigger.tabIndex = 0;

        const menu = document.createElement('div');
        menu.className = 'custom-select-menu';

        container.appendChild(trigger);
        container.appendChild(menu);

        let searchInput = null;

        function buildMenu() {
            menu.innerHTML = '';

            // Caja de búsqueda
            const searchBox = document.createElement('div');
            searchBox.className = 'custom-select-search-box';
            searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'custom-select-search-input';
            searchInput.placeholder = placeholder;
            searchInput.addEventListener('click', e => e.stopPropagation());
            searchInput.addEventListener('input', e => filterItems(e.target.value));
            searchBox.appendChild(searchInput);
            menu.appendChild(searchBox);

            const listContainer = document.createElement('div');
            listContainer.className = 'custom-select-list-container';
            menu.appendChild(listContainer);

            const currentOptions = Array.from(selectEl.options);
            currentOptions.forEach((opt, idx) => {
                if (opt.value === '' && idx === 0) return;
                const isSelected = idx === selectEl.selectedIndex;
                const item = document.createElement('div');
                item.className = `custom-select-item ${isSelected ? 'selected' : ''}`;
                item.setAttribute('data-text', opt.text.toLowerCase());
                item.innerHTML = `
                    <div class="item-left" style="flex:1; min-width:0;">
                        <span class="item-title" style="font-size:0.88rem; font-weight:600; color:var(--text-color, #0f172a);">${opt.text}</span>
                    </div>
                    ${isSelected ? `<span class="item-check">${uiIcon('check')}</span>` : ''}
                `;
                item.addEventListener('click', e => {
                    e.stopPropagation();
                    selectEl.selectedIndex = idx;
                    selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                    container.classList.remove('open');
                });
                listContainer.appendChild(item);
            });
        }

        function filterItems(term) {
            const clean = term.toLowerCase().trim();
            const listContainer = menu.querySelector('.custom-select-list-container');
            if (!listContainer) return;
            listContainer.querySelectorAll('.custom-select-item').forEach(item => {
                const text = item.getAttribute('data-text') || '';
                item.style.display = (clean === '' || text.includes(clean)) ? 'flex' : 'none';
            });
        }

        function updateTrigger() {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (!selectedOpt) return;
            const isPlaceholder = selectedOpt.value === '';
            const labelText = isPlaceholder ? placeholder.replace('Buscar ', 'Todos los ').replace('Buscar', 'Todos') : selectedOpt.text;
            const labelColor = isPlaceholder ? 'var(--text-muted, #94a3b8)' : 'var(--text-color, #0f172a)';
            trigger.innerHTML = `
                <div class="custom-select-selected-content" style="flex:1; min-width:0;">
                    <span class="custom-select-label-text" style="font-weight:600; font-size:0.9rem; color:${labelColor};">${labelText}</span>
                </div>
                <span class="custom-select-chevron" style="flex-shrink:0; margin-left:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </span>
            `;
        }

        trigger.addEventListener('click', e => {
            e.stopPropagation();
            const isOpen = container.classList.contains('open');
            document.querySelectorAll('.custom-select-container.open').forEach(c => {
                if (c !== container) c.classList.remove('open');
            });
            if (!isOpen) {
                buildMenu();
                container.classList.add('open');
                setTimeout(() => searchInput && searchInput.focus(), 50);
            } else {
                container.classList.remove('open');
            }
        });

        document.addEventListener('click', e => {
            if (!container.contains(e.target)) container.classList.remove('open');
        });

        selectEl.addEventListener('change', () => {
            updateTrigger();
        });

        updateTrigger();
    }

    // Inicializar dropdowns al cargar la página
    document.addEventListener("DOMContentLoaded", () => {
        setupAreaCustomDropdown('area');
        setupSearchableExportDropdown('unidad', 'Buscar placa...');
        setupSearchableExportDropdown('responsable', 'Buscar conductor...');
    });
</script>
