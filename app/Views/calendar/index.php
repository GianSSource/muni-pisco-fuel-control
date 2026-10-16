<div class="calendar-wrapper">
    <section class="page-head">
        <div>
            <h1>Calendario de Entregas y Despachos</h1>
            <p class="subtitle">Monitoreo de despachos diarios y entregas de combustible</p>
        </div>
        <div class="actions">
            <a class="button primary" href="<?= BASE_URL ?>dashboard">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Volver al Panel
            </a>
        </div>
    </section>

    <!-- Filtros y Leyenda -->
    <section class="calendar-toolbar">
        <div class="calendar-filters">
            <button class="filter-btn active" data-filter="all">
                <span class="dot dot-all"></span> Todos los Eventos
            </button>
            <button class="filter-btn" data-filter="fuel">
                <span class="dot dot-fuel"></span> Despachos de Combustible
            </button>
            <button class="filter-btn" data-filter="income">
                <span class="dot" style="background: #16a34a;"></span> Ingresos de Stock
            </button>
        </div>

        <div class="calendar-nav-controls">
            <button id="prevMonthBtn" class="button secondary small-btn">&larr; Anterior</button>
            <h2 id="currentMonthLabel" class="current-month-title">Agosto 2026</h2>
            <button id="todayBtn" class="button secondary small-btn">Hoy</button>
            <button id="nextMonthBtn" class="button secondary small-btn">Siguiente &rarr;</button>
        </div>
    </section>

    <div class="calendar-grid-container">
        <!-- Rejilla Interactiva del Calendario -->
        <div class="calendar-card">
            <div class="calendar-weekdays">
                <div>Lun</div>
                <div>Mar</div>
                <div>Mié</div>
                <div>Jue</div>
                <div>Vie</div>
                <div>Sáb</div>
                <div>Dom</div>
            </div>
            <div id="calendarDaysGrid" class="calendar-days-grid">
                <!-- Se llena con JavaScript -->
            </div>
        </div>

        <!-- Panel Lateral / Agenda de Eventos Próximos -->
        <div class="agenda-card">
            <div class="agenda-header">
                <h3 id="agendaTitle">Agenda de Movimientos</h3>
                <span class="badge badge-info" id="agendaCount"><?= count($events) ?> registros</span>
            </div>
            <div id="agendaList" class="agenda-list">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <div class="agenda-item event-type-<?= $event['type'] ?>" data-date="<?= $event['date'] ?>"
                            onclick="showEventDetails(<?= htmlspecialchars(json_encode($event)) ?>)">
                            <div class="agenda-date">
                                <span class="day"><?= date('d', strtotime($event['date'])) ?></span>
                                <span class="month"><?= date('M', strtotime($event['date'])) ?></span>
                            </div>
                            <div class="agenda-details">
                                <?php
                                $displayTitle = $event['title'];
                                if ($event['type'] === 'income') {
                                    $displayTitle = preg_replace('/^Ingreso Lote\s+/i', '', $displayTitle);
                                    $displayTitle = preg_replace('/\s*\(.*?\)/', '', $displayTitle);
                                } elseif ($event['type'] === 'fuel') {
                                    $displayTitle = preg_replace('/^Despacho\s+/i', '', $displayTitle);
                                    $displayTitle = preg_replace('/\s*\(.*?\)/', '', $displayTitle);
                                }
                                ?>
                                <div class="agenda-title-row">
                                    <strong class="agenda-title"><?= htmlspecialchars($displayTitle) ?></strong>
                                    <span
                                        class="badge <?= $event['badge_class'] ?>"><?= htmlspecialchars($event['badge']) ?></span>
                                </div>
                                <div class="agenda-meta">
                                    <?php
                                    $evFuel = $event['fuel_type'] ?? '';
                                    if (empty($evFuel) && !empty($event['title']) && preg_match('/\((.*?)\)/', $event['title'], $m)) {
                                        $evFuel = $m[1];
                                    }
                                    $evUnit = \App\Models\Catalog::getFuelUnit($evFuel, true);
                                    ?>
                                    <?php if ($event['type'] === 'income'): ?>
                                        <span>Proveedor: <?= htmlspecialchars($event['driver']) ?></span>
                                        <span><?= number_format($event['gallons'], 2) ?> <?= $evUnit ?></span>
                                    <?php else: ?>
                                        <span>Placa: <?= htmlspecialchars($event['vehicle']) ?></span>
                                        <span><?= number_format($event['gallons'], 2) ?> <?= $evUnit ?></span>
                                        <span> <?= htmlspecialchars($event['driver']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="empty-state">No hay fechas de entrega registradas en la agenda.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalle de Eventos -->
<div id="eventModal" class="modal-overlay" onclick="closeEventModal(event)">
    <div class="modal-card" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modalTitle">Detalle de la Entrega</h3>
            <button class="modal-close-btn" onclick="closeEventModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Contenido inyectado por JS -->
        </div>
        <div class="modal-footer">
            <button class="button secondary" onclick="closeEventModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    // Datos de eventos PHP inyectados en JavaScript
    const allEventsData = <?= json_encode($events, JSON_UNESCAPED_UNICODE) ?>;

    // Formateador de números con comas y 2 decimales (ej: 30,000.00)
    function fmtGal(val) {
        const n = parseFloat(val);
        if (isNaN(n)) return val;
        return n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function getFuelUnit(ft, title = '') {
        let s = (ft || '').toLowerCase();
        if (!s && title) {
            const m = title.match(/\((.*?)\)/);
            if (m) s = m[1].toLowerCase();
        }
        return (s.includes('gnv') || s.includes('gas natural')) ? 'm³' : 'Gal';
    }

    function getFuelUnitFull(ft, title = '') {
        let s = (ft || '').toLowerCase();
        if (!s && title) {
            const m = title.match(/\((.*?)\)/);
            if (m) s = m[1].toLowerCase();
        }
        return (s.includes('gnv') || s.includes('gas natural')) ? 'm³' : 'Galones';
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

    let currentDate = new Date();
    let currentFilter = 'all';

    const monthNames = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    document.addEventListener("DOMContentLoaded", () => {
        renderCalendar();

        document.getElementById('prevMonthBtn').addEventListener('click', () => {
            currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() - 1, 1);
            renderCalendar();
        });

        document.getElementById('nextMonthBtn').addEventListener('click', () => {
            currentDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
            renderCalendar();
        });

        document.getElementById('todayBtn').addEventListener('click', () => {
            currentDate = new Date();
            renderCalendar();
        });

        // Filtros
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.dataset.filter;
                renderCalendar();
                filterAgenda();
            });
        });
    });

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        document.getElementById('currentMonthLabel').textContent = `${monthNames[month]} ${year}`;

        const grid = document.getElementById('calendarDaysGrid');
        grid.innerHTML = '';

        // Primer día del mes
        const firstDayIndex = new Date(year, month, 1).getDay();
        // Ajuste para lunes como primer día (0: Dom, 1: Lun, ...)
        const startOffset = (firstDayIndex === 0) ? 6 : firstDayIndex - 1;

        const totalDays = new Date(year, month + 1, 0).getDate();
        const prevMonthTotalDays = new Date(year, month, 0).getDate();

        // Días mes anterior
        for (let i = startOffset; i > 0; i--) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day other-month';
            dayDiv.innerHTML = `<span class="day-num">${prevMonthTotalDays - i + 1}</span>`;
            grid.appendChild(dayDiv);
        }

        const now = new Date();
        const todayStr = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')}`;

        // Días mes actual
        for (let day = 1; day <= totalDays; day++) {
            const dayDiv = document.createElement('div');
            const dayFormatted = String(day).padStart(2, '0');
            const monthFormatted = String(month + 1).padStart(2, '0');
            const dateStr = `${year}-${monthFormatted}-${dayFormatted}`;

            dayDiv.className = 'calendar-day';
            if (dateStr === todayStr) {
                dayDiv.classList.add('is-today');
            }

            dayDiv.innerHTML = `<span class="day-num">${day}</span>`;

            // Filtrar eventos de esta fecha
            const dayEvents = allEventsData.filter(ev => {
                if (ev.date !== dateStr) return false;
                if (currentFilter === 'all') return true;
                return ev.type === currentFilter;
            });

            if (dayEvents.length > 0) {
                const eventsWrapper = document.createElement('div');
                eventsWrapper.className = 'day-events';

                dayEvents.slice(0, 3).forEach(ev => {
                    const pill = document.createElement('div');
                    pill.className = `event-pill ${ev.badge_class}`;
                    pill.title = `${ev.title} (${ev.time || ''})`;
                    if (ev.type === 'fuel') {
                        pill.textContent = `${fmtGal(ev.gallons)} ${getFuelUnit(ev.fuel_type)}`;
                    } else {
                        pill.textContent = ev.title.replace(/^Ingreso Lote\s+/i, '').replace(/\s*\(.*?\)/, '');
                    }
                    pill.addEventListener('click', (e) => {
                        e.stopPropagation();
                        showEventDetails(ev);
                    });
                    eventsWrapper.appendChild(pill);
                });

                if (dayEvents.length > 3) {
                    const morePill = document.createElement('div');
                    morePill.className = 'event-pill badge-more';
                    morePill.textContent = `+${dayEvents.length - 3} más`;
                    eventsWrapper.appendChild(morePill);
                }

                dayDiv.appendChild(eventsWrapper);
                dayDiv.addEventListener('click', () => {
                    showDayEventsModal(dateStr, dayEvents);
                });
            }

            grid.appendChild(dayDiv);
        }

        // Días mes siguiente para completar la rejilla (42 celdas)
        const totalCells = startOffset + totalDays;
        const nextDays = (totalCells > 35) ? 42 - totalCells : 35 - totalCells;
        for (let i = 1; i <= nextDays; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day other-month';
            dayDiv.innerHTML = `<span class="day-num">${i}</span>`;
            grid.appendChild(dayDiv);
        }
    }

    function filterAgenda() {
        const items = document.querySelectorAll('.agenda-item');
        const titleEl = document.getElementById('agendaTitle');
        let visibleCount = 0;

        if (titleEl) {
            if (currentFilter === 'fuel') {
                titleEl.textContent = 'Agenda de Despachos';
            } else if (currentFilter === 'income') {
                titleEl.textContent = 'Agenda de Ingresos';
            } else {
                titleEl.textContent = 'Agenda de Movimientos';
            }
        }

        items.forEach(item => {
            if (currentFilter === 'all') {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                if (item.classList.contains(`event-type-${currentFilter}`)) {
                    item.style.display = 'flex';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            }
        });
        document.getElementById('agendaCount').textContent = `${visibleCount} registros`;
    }

    function showEventDetails(ev) {
        const titleEl = document.getElementById('modalTitle');
        const bodyEl = document.getElementById('modalBody');
        const closeBtn = document.querySelector('#eventModal .modal-close-btn');

        const isIncome = ev.type === 'income';

        if (isIncome) {
            titleEl.className = 'icon-with-text';
            titleEl.innerHTML = `${uiIcon('fuel')}Detalle del Ingreso de Combustible`;
            if (closeBtn) closeBtn.style.display = 'none';

            const createdDate = ev.created_at || ev.date_time || (ev.date ? (ev.date + (ev.time ? ' ' + ev.time : '')) : '');
            const dateObj = new Date(String(createdDate).replace(' ', 'T'));
            const dateStr = !isNaN(dateObj.getTime()) ? dateObj.toLocaleString('es-PE') : createdDate;

            let receiptNum = ev.receipt_number;
            if (!receiptNum && ev.title) {
                receiptNum = ev.title.replace(/\s*\(.*?\)/, '').replace(/^Ingreso Lote\s+/i, '');
            }

            let fuelType = ev.fuel_type;
            if (!fuelType && ev.title) {
                const match = ev.title.match(/\((.*?)\)/);
                if (match) fuelType = match[1];
            }
            if (!fuelType) fuelType = 'Diesel B5 S-50';

            const supplier = ev.supplier || ev.driver || 'Proveedor General';
            const invoiceText = ev.invoice_number && ev.invoice_number.trim() !== '' ? ev.invoice_number : 'No registrada';
            const notesText = ev.notes && ev.notes.trim() !== '' && ev.notes !== '-' ? ev.notes : 'Sin observaciones';

            const rem = parseFloat(ev.gallons_remaining !== undefined ? ev.gallons_remaining : 0);
            const isAvailable = rem > 0;
            const statusText = isAvailable ? 'Disponible' : 'Agotado';
            const statusColor = isAvailable ? '#10b981' : '#ef4444';
            const isGnv = (fuelType || '').toLowerCase().includes('gnv') || (fuelType || '').toLowerCase().includes('gas natural');
            const unitLabel = isGnv ? 'm³' : 'Galones';

            bodyEl.innerHTML = `
                <div class="modal-detail-card">
                    <div class="modal-badge-row">
                        <span class="modal-date-tag icon-with-text">${uiIcon('calendar')}Fecha y Hora: <strong>${escapeHtml(dateStr)}</strong></span>
                    </div>
                    
                    <h4 style="margin-top: 10px; font-size: 1.15rem; color: var(--primary);">N° Recibo / Lote: ${escapeHtml(receiptNum)}</h4>

                    <div class="modal-grid">
                        <div class="modal-field">
                            <span class="lbl icon-with-text">${uiIcon('truck')}Proveedor / Grifo:</span>
                            <strong>${escapeHtml(supplier)}</strong>
                        </div>
                        <div class="modal-field">
                            <span class="lbl icon-with-text">${uiIcon('fuel')}Tipo de Combustible:</span>
                            <strong style="color: var(--primary);">${escapeHtml(fuelType)}</strong>
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
                            <strong class="qty-val">${parseFloat(ev.gallons).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${unitLabel}</strong>
                        </div>
                        
                        <div class="modal-field highlight-field" style="background: rgba(16, 185, 129, 0.05);">
                            <span class="lbl icon-with-text">${uiIcon('fuel')}Stock Remanente:</span>
                            <strong class="qty-val" style="color: ${statusColor};">${rem.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} ${unitLabel}</strong>
                        </div>
                    </div>
                </div>
            `;
        } else {
            titleEl.className = 'icon-with-text';
            titleEl.innerHTML = `${uiIcon('fuel')}Detalle del Despacho de Combustible`;
            if (closeBtn) closeBtn.style.display = 'none';

            let receiptNum = ev.receipt_number || ev.title.replace(/^Despacho\s+/i, '').replace(/\s*\(.*?\)/, '');
            const actualFuel = ev.fuel_type || 'Diesel B5 S-50';
            const isGnv = (actualFuel || '').toLowerCase().includes('gnv') || (actualFuel || '').toLowerCase().includes('gas natural');
            const unitLabel = isGnv ? 'm³' : 'Galones';

            const createdDate = ev.created_at || ev.date_time || (ev.date ? (ev.date + (ev.time ? ' ' + ev.time : '')) : '');
            const dateObj = new Date(String(createdDate).replace(' ', 'T'));
            const dateStr = !isNaN(dateObj.getTime()) ? dateObj.toLocaleString('es-PE') : (ev.date + (ev.time ? ' ' + ev.time : ''));

            const notesText = ev.notes && ev.notes.trim() !== '' && ev.notes !== '-' ? ev.notes : 'Sin observaciones';
            const rawId = String(ev.id || '').replace(/^fuel_/, '');

            bodyEl.innerHTML = `
                <div class="modal-detail-card">
                    <div class="modal-badge-row">
                        <span class="modal-date-tag icon-with-text">${uiIcon('calendar')}Fecha y Hora: <strong>${escapeHtml(dateStr)}</strong></span>
                    </div>
                    
                    <h4 style="margin-top: 10px; font-size: 1.15rem; color: var(--primary);">Vale N°: ${escapeHtml(receiptNum)}</h4>

                    <div class="modal-grid">
                        <div class="modal-field">
                            <span class="lbl icon-with-text">${uiIcon('vehicle')}Vehículo / Placa:</span>
                            <strong>${escapeHtml(ev.vehicle || '-')}</strong>
                        </div>
                        <div class="modal-field">
                            <span class="lbl icon-with-text">${uiIcon('user')}Conductor / Responsable:</span>
                            <strong>${escapeHtml(ev.driver || '-')}</strong>
                        </div>
                        <div class="modal-field">
                            <span class="lbl icon-with-text">${uiIcon('fuel')}Tipo de Combustible:</span>
                            <strong style="color: var(--primary);">${escapeHtml(actualFuel)}</strong>
                        </div>
                        <div class="modal-field">
                            <span class="lbl icon-with-text">${uiIcon('pin')}Destino / Área:</span>
                            <span>${escapeHtml(ev.destination || 'Despacho General')}</span>
                        </div>
                        
                        <div class="modal-field" style="grid-column: span 2; background: var(--bg-body); padding: 10px 12px; border-radius: 8px; border: 1px dashed var(--border-color, var(--line)); margin-top: 4px; overflow-wrap: break-word; word-break: break-word; hyphens: auto;">
                            <span class="lbl icon-with-text">${uiIcon('note')}Observaciones:</span>
                            <span style="color: var(--ink); font-style: italic; font-weight: 500; font-size: 0.92em; margin-top: 4px; display: block; word-break: break-word; white-space: pre-wrap;">${escapeHtml(notesText)}</span>
                        </div>

                        <div class="modal-field highlight-field">
                            <span class="lbl icon-with-text">${uiIcon('fuel')}Cantidad Despachada:</span>
                            <strong class="qty-val">${fmtGal(ev.gallons)} ${unitLabel}</strong>
                        </div>
                    </div>

                    ${rawId ? `
                    <div style="margin-top: 20px; text-align: right;">
                        <a href="<?= BASE_URL ?>despachos/imprimir?id=${rawId}" target="_blank" style="padding: 10px 20px; background: #2563eb; color: #ffffff; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                            ${uiIcon('print')} Imprimir Vale Oficial
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
        }

        document.getElementById('eventModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function showDayEventsModal(dateStr, events) {
        const titleEl = document.getElementById('modalTitle');
        const bodyEl = document.getElementById('modalBody');
        const closeBtn = document.querySelector('#eventModal .modal-close-btn');
        if (closeBtn) closeBtn.style.display = '';

        titleEl.className = '';
        titleEl.textContent = `Entregas y Despachos - ${dateStr}`;

        let html = `<div class="modal-day-list">`;
        events.forEach(ev => {
            html += `
                <div class="day-modal-item" onclick="showEventDetails(${JSON.stringify(ev).replace(/"/g, '&quot;')})">
                    <div class="day-modal-header">
                        <span class="badge ${ev.badge_class}">${ev.badge}</span>
                        <strong>${escapeHtml(ev.title)}</strong>
                    </div>
                    <div class="day-modal-sub">
                        <span class="icon-with-text">${uiIcon('fuel')}${fmtGal(ev.gallons)} ${getFuelUnit(ev.fuel_type, ev.title)} - ${uiIcon('user')}${escapeHtml(ev.driver)}</span>
                    </div>
                </div>
            `;
        });
        html += `</div>`;
        bodyEl.innerHTML = html;

        document.getElementById('eventModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEventModal(e) {
        if (e && e.target && e.target !== document.getElementById('eventModal') && !e.target.classList.contains('button') && !e.target.classList.contains('modal-close-btn')) {
            return;
        }
        document.getElementById('eventModal').classList.remove('active');
        document.body.style.overflow = '';
        const closeBtn = document.querySelector('#eventModal .modal-close-btn');
        if (closeBtn) closeBtn.style.display = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('eventModal');
            if (modal && modal.classList.contains('active')) {
                closeEventModal();
            }
        }
    });
</script>