<div class="calendar-wrapper">
    <section class="page-head">
        <div>
            <h1>Calendario de Entregas y Despachos</h1>
            <p class="subtitle">Monitoreo de fechas límite de proyectos y despachos diarios de combustible</p>
        </div>
        <div class="actions">
            <a class="button primary" href="<?= BASE_URL ?>dashboard">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
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
            <button class="filter-btn" data-filter="project">
                <span class="dot dot-project"></span> Fechas de Entrega (Proyectos)
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
                <h3>Agenda de Entregas</h3>
                <span class="badge badge-info" id="agendaCount"><?= count($events) ?> registros</span>
            </div>
            <div id="agendaList" class="agenda-list">
                <?php if (!empty($events)): ?>
                    <?php foreach ($events as $event): ?>
                        <div class="agenda-item event-type-<?= $event['type'] ?>" data-date="<?= $event['date'] ?>" onclick="showEventDetails(<?= htmlspecialchars(json_encode($event)) ?>)">
                            <div class="agenda-date">
                                <span class="day"><?= date('d', strtotime($event['date'])) ?></span>
                                <span class="month"><?= date('M', strtotime($event['date'])) ?></span>
                            </div>
                            <div class="agenda-details">
                                <div class="agenda-title-row">
                                    <strong class="agenda-title"><?= htmlspecialchars($event['title']) ?></strong>
                                    <span class="badge <?= $event['badge_class'] ?>"><?= htmlspecialchars($event['badge']) ?></span>
                                </div>
                                <div class="agenda-meta">
                                    <?php if ($event['type'] === 'fuel'): ?>
                                        <span>🚗 Placa: <?= htmlspecialchars($event['vehicle']) ?></span>
                                        <span>⛽ <?= number_format($event['gallons'], 2) ?> Gal</span>
                                        <span>👤 <?= htmlspecialchars($event['driver']) ?></span>
                                    <?php else: ?>
                                        <span>🏢 Cliente: <?= htmlspecialchars($event['client']) ?></span>
                                        <span>📌 Estado: <?= htmlspecialchars($event['status']) ?></span>
                                        <span>💰 S/ <?= number_format($event['budget'], 2) ?></span>
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
    
    let currentDate = new Date();
    let currentFilter = 'all';

    const monthNames = [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    document.addEventListener("DOMContentLoaded", () => {
        renderCalendar();

        document.getElementById('prevMonthBtn').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        document.getElementById('nextMonthBtn').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
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

        const todayStr = new Date().toISOString().split('T')[0];

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
                    pill.textContent = ev.type === 'fuel' ? `⛽ ${ev.gallons}G` : `📦 ${ev.title.replace('Entrega: ', '')}`;
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
        let visibleCount = 0;
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

        titleEl.textContent = ev.type === 'fuel' ? '⛽ Despacho de Combustible' : '📦 Entrega de Proyecto';

        let html = `
            <div class="modal-detail-card">
                <div class="modal-badge-row">
                    <span class="badge ${ev.badge_class}">${ev.badge}</span>
                    <span class="modal-date-tag">📅 Fecha: <strong>${ev.date}</strong> ${ev.time ? '⏰ ' + ev.time : ''}</span>
                </div>
                <h4>${ev.title}</h4>
        `;

        if (ev.type === 'fuel') {
            html += `
                <div class="modal-grid">
                    <div class="modal-field"><span class="lbl">N° Vale:</span> <strong>${ev.title}</strong></div>
                    <div class="modal-field"><span class="lbl">Vehículo / Placa:</span> <strong>${ev.vehicle}</strong></div>
                    <div class="modal-field"><span class="lbl">Conductor:</span> <span>${ev.driver}</span></div>
                    <div class="modal-field"><span class="lbl">Destino / Área:</span> <span>${ev.destination}</span></div>
                    <div class="modal-field highlight-field"><span class="lbl">Cantidad Despachada:</span> <strong class="qty-val">${ev.gallons} Galones</strong></div>
                </div>
            `;
        } else {
            html += `
                <div class="modal-grid">
                    <div class="modal-field"><span class="lbl">Cliente:</span> <strong>${ev.client}</strong></div>
                    <div class="modal-field"><span class="lbl">Estado Actual:</span> <span class="status-pill status-completed">${ev.status}</span></div>
                    <div class="modal-field"><span class="lbl">Presupuesto Asignado:</span> <strong>S/ ${parseFloat(ev.budget).toLocaleString('es-PE', {minimumFractionDigits: 2})}</strong></div>
                </div>
                ${ev.description ? `<div class="modal-desc"><strong>Descripción:</strong> <p>${ev.description}</p></div>` : ''}
            `;
        }

        html += `</div>`;
        bodyEl.innerHTML = html;

        document.getElementById('eventModal').classList.add('active');
    }

    function showDayEventsModal(dateStr, events) {
        const titleEl = document.getElementById('modalTitle');
        const bodyEl = document.getElementById('modalBody');

        titleEl.textContent = `Entregas y Despachos - ${dateStr}`;

        let html = `<div class="modal-day-list">`;
        events.forEach(ev => {
            html += `
                <div class="day-modal-item" onclick="showEventDetails(${JSON.stringify(ev).replace(/"/g, '&quot;')})">
                    <div class="day-modal-header">
                        <span class="badge ${ev.badge_class}">${ev.badge}</span>
                        <strong>${ev.title}</strong>
                    </div>
                    <div class="day-modal-sub">
                        ${ev.type === 'fuel' ? `⛽ ${ev.gallons} Gal - 👤 ${ev.driver}` : `🏢 ${ev.client} - 💰 S/ ${ev.budget}`}
                    </div>
                </div>
            `;
        });
        html += `</div>`;
        bodyEl.innerHTML = html;

        document.getElementById('eventModal').classList.add('active');
    }

    function closeEventModal() {
        document.getElementById('eventModal').classList.remove('active');
    }
</script>
