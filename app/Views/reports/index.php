<?php
declare(strict_types=1);

$firstDayOfMonth = date('Y-m-01');
$todayDate = date('Y-m-d');
?>
<div class="dashboard-wrapper" style="padding: 24px 20px;">

  <style>
    .report-panel {
      font-family: var(--font-body, 'Inter', sans-serif);
      max-width: 1100px;
      margin: 0 auto;
      background: var(--card-bg, #ffffff);
      border: 1px solid var(--border-color, #e2e8f0);
      border-radius: 12px;
      padding: 24px 28px;
      box-shadow: var(--shadow, 0 1px 3px rgba(0, 0, 0, 0.04));
      color: var(--text-color, #0f172a);
    }

    .report-header-wrap {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 20px;
    }

    .report-icon-box {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      background: #eff6ff;
      color: #2563eb;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .report-panel h1 {
      font-family: var(--font-heading, 'Josefin Sans', sans-serif);
      font-size: 20px;
      margin: 0 0 3px;
      font-weight: 700;
      color: var(--text-color, #0f172a);
      letter-spacing: -0.01em;
    }

    .report-panel .sub {
      font-family: var(--font-body, 'Inter', sans-serif);
      margin: 0;
      color: var(--text-muted, #64748b);
      font-size: 13.5px;
    }

    .chips {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 20px;
    }

    .chip {
      font-family: var(--font-body, 'Inter', sans-serif);
      font-size: 13px;
      font-weight: 500;
      padding: 6px 16px;
      border-radius: 999px;
      cursor: pointer;
      border: 1px solid var(--border-color, #cbd5e1);
      background: var(--input-bg, #ffffff);
      color: var(--text-muted, #64748b);
      transition: all 0.15s ease;
      line-height: 1.2;
    }

    .chip:hover {
      border-color: var(--text-muted, #64748b);
      color: var(--text-color, #0f172a);
    }

    .chip.on {
      background: rgba(37, 99, 235, 0.1);
      border-color: var(--primary, #2563eb);
      color: var(--primary, #2563eb);
      font-weight: 600;
    }

    .filters-grid {
      display: grid;
      grid-template-columns: repeat(5, minmax(0, 1fr));
      gap: 16px;
    }

    .filters-grid label {
      display: block;
      font-size: 12.5px;
      font-weight: 600;
      color: var(--text-muted, #64748b);
      margin-bottom: 6px;
    }

    /* ── Estilos para Campos de Fecha y Flatpickr ── */
    .date-input-wrap {
      position: relative;
      width: 100%;
    }

    .date-input-wrap input,
    .filters-grid input.flatpickr-input,
    .filters-grid input.report-datepicker,
    .filters-grid .flatpickr-input,
    .filters-grid input[type="date"],
    .filters-grid input[type="text"] {
      font-family: var(--font-body, 'Inter', sans-serif) !important;
      width: 100% !important;
      height: 40px !important;
      padding: 0 36px 0 12px !important;
      font-size: 13.5px !important;
      font-weight: 500 !important;
      color: var(--text-color, #0f172a) !important;
      background: var(--input-bg, #ffffff) !important;
      border: 1px solid var(--border-color, #cbd5e1) !important;
      border-radius: 8px !important;
      outline: none !important;
      box-sizing: border-box !important;
      cursor: pointer !important;
      transition: border-color 0.15s, box-shadow 0.15s !important;
    }

    .date-input-wrap input:hover,
    .filters-grid input.flatpickr-input:hover {
      border-color: var(--primary, #2563eb) !important;
    }

    .date-input-wrap input:focus,
    .filters-grid input.flatpickr-input:focus,
    .filters-grid input.report-datepicker:focus {
      border-color: var(--primary, #2563eb) !important;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
    }

    .date-input-wrap .date-icon {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      color: var(--text-muted, #64748b);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 4;
    }

    /* ── Estilos Premium para Listas Desplegables (Custom Select) ── */
    .custom-select-container {
      position: relative;
      width: 100%;
      user-select: none;
    }

    .custom-select-trigger {
      display: flex;
      align-items: center;
      justify-content: space-between;
      width: 100%;
      height: 40px;
      padding: 0 12px;
      border-radius: 8px;
      border: 1px solid var(--border-color, #cbd5e1);
      background: var(--input-bg, #ffffff);
      color: var(--text-color, #0f172a);
      font-size: 13.5px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.15s ease;
      box-sizing: border-box;
    }

    .custom-select-trigger:hover {
      border-color: var(--primary, #2563eb);
    }

    .custom-select-container.open .custom-select-trigger,
    .custom-select-trigger:focus-visible {
      outline: none;
      border-color: var(--primary, #2563eb);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .custom-select-selected-content {
      display: flex;
      align-items: center;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      flex: 1;
      min-width: 0;
    }

    .custom-select-label-text {
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      font-size: 13.5px;
    }

    .custom-select-label-text.placeholder {
      color: var(--text-color, #0f172a);
    }

    .custom-select-label-text.has-value {
      color: var(--primary, #2563eb);
      font-weight: 600;
    }

    .custom-select-right {
      display: flex;
      align-items: center;
      gap: 5px;
      flex-shrink: 0;
      margin-left: 6px;
    }

    .custom-select-clear {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      color: var(--text-muted, #64748b);
      cursor: pointer;
      transition: all 0.15s;
    }

    .custom-select-clear:hover {
      background: rgba(0, 0, 0, 0.08);
      color: var(--text-color, #0f172a);
    }

    .custom-select-chevron {
      display: flex;
      align-items: center;
      color: var(--text-muted, #64748b);
      transition: transform 0.2s ease;
    }

    .custom-select-container.open .custom-select-chevron {
      transform: rotate(180deg);
      color: var(--primary, #2563eb);
    }

    .custom-select-menu {
      position: absolute;
      top: calc(100% + 5px);
      left: 0;
      right: 0;
      z-index: 100;
      background: var(--card-bg, #ffffff);
      border: 1px solid var(--border-color, #cbd5e1);
      border-radius: 10px;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
      max-height: 270px;
      display: none;
      flex-direction: column;
      overflow: hidden;
      animation: fadeInDropdown 0.15s ease-out;
    }

    @keyframes fadeInDropdown {
      from { opacity: 0; transform: translateY(-4px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .custom-select-container.open .custom-select-menu {
      display: flex;
    }

    .custom-select-search-wrap {
      padding: 8px;
      border-bottom: 1px solid var(--border-color, #e2e8f0);
      background: var(--card-bg, #ffffff);
      position: sticky;
      top: 0;
      z-index: 2;
    }

    .custom-select-search-input {
      width: 100%;
      height: 32px;
      padding: 0 10px;
      border: 1px solid var(--border-color, #cbd5e1);
      border-radius: 6px;
      background: var(--input-bg, #ffffff);
      color: var(--text-color, #0f172a);
      font-size: 12.5px;
      outline: none;
      box-sizing: border-box;
      transition: border-color 0.15s;
    }

    .custom-select-search-input:focus {
      border-color: var(--primary, #2563eb);
    }

    .custom-select-list {
      overflow-y: auto;
      padding: 4px;
      max-height: 210px;
      scrollbar-width: thin;
    }

    .custom-select-item {
      padding: 8px 10px;
      border-radius: 6px;
      font-size: 13px;
      color: var(--text-color, #0f172a);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: background 0.12s;
    }

    .custom-select-item:hover {
      background: var(--bg, #f8fafc);
    }

    .custom-select-item.selected {
      background: rgba(37, 99, 235, 0.08);
      color: var(--primary, #2563eb);
      font-weight: 600;
    }

    .custom-select-item .item-check {
      color: var(--primary, #2563eb);
      display: flex;
      align-items: center;
    }

    .custom-select-no-results {
      padding: 12px 10px;
      text-align: center;
      color: var(--text-muted, #64748b);
      font-size: 12.5px;
    }

    /* ── Acciones y Botones ── */
    .actions-bar {
      margin-top: 24px;
      padding-top: 18px;
      border-top: 1px solid var(--border-color, #e2e8f0);
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      flex-wrap: wrap;
    }

    .actions-bar .count {
      color: var(--text-muted, #64748b);
      font-size: 13.5px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .status-dot-green {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #10b981;
      display: inline-block;
      flex-shrink: 0;
    }

    .actions-bar .count strong {
      color: var(--text-color, #0f172a);
      font-weight: 700;
    }

    .actions-bar .right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .actions-bar .link {
      font-family: var(--font-body, 'Inter', sans-serif);
      font-size: 13.5px;
      font-weight: 500;
      background: none;
      border: 0;
      color: var(--text-muted, #64748b);
      cursor: pointer;
      padding: 8px 12px;
      border-radius: 8px;
      transition: background 0.15s, color 0.15s;
    }

    .actions-bar .link:hover {
      background: var(--bg, #f8fafc);
      color: var(--text-color, #0f172a);
    }

    .export-dropdown {
      position: relative;
    }

    .btn-main {
      font-family: var(--font-body, 'Inter', sans-serif);
      font-size: 13.5px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      height: 40px;
      padding: 0 16px;
      background: #0f172a;
      color: #ffffff;
      border: 0;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.15s ease;
    }

    .btn-main:hover {
      background: #1e293b;
    }

    .btn-main svg {
      width: 16px;
      height: 16px;
    }

    .export-menu {
      position: absolute;
      right: 0;
      top: calc(100% + 6px);
      min-width: 200px;
      background: var(--card-bg, #ffffff);
      border: 1px solid var(--border-color, #cbd5e1);
      border-radius: 10px;
      padding: 5px;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
      z-index: 50;
    }

    .export-menu[hidden] {
      display: none;
    }

    .export-menu button {
      font-family: var(--font-body, 'Inter', sans-serif);
      font-size: 13.5px;
      font-weight: 500;
      width: 100%;
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 9px 12px;
      background: none;
      border: 0;
      border-radius: 6px;
      color: var(--text-color, #0f172a);
      cursor: pointer;
      text-align: left;
      transition: background 0.15s;
    }

    .export-menu button:hover {
      background: var(--bg, #f8fafc);
    }

    .export-menu svg {
      width: 18px;
      height: 18px;
      flex: none;
    }

    .export-menu .pdf { color: #dc2626; }
    .export-menu .xls { color: #059669; }
    .export-menu .csv { color: var(--text-muted, #64748b); }

    .toast-msg {
      margin-top: 10px;
      font-size: 12.5px;
      color: var(--text-muted, #64748b);
      min-height: 18px;
    }

    @media (max-width: 960px) {
      .filters-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 540px) {
      .filters-grid {
        grid-template-columns: 1fr;
      }
      .actions-bar .right {
        width: 100%;
        justify-content: space-between;
      }
      .export-dropdown {
        flex: 1;
      }
      .btn-main {
        width: 100%;
        justify-content: center;
      }
    }
  </style>

  <section class="report-panel" aria-labelledby="reportTitle">
    <div class="report-header-wrap">
      <div class="report-icon-box" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
          <polyline points="14 2 14 8 20 8"></polyline>
          <line x1="16" y1="13" x2="8" y2="13"></line>
          <line x1="16" y1="17" x2="8" y2="17"></line>
          <line x1="10" y1="9" x2="8" y2="9"></line>
        </svg>
      </div>
      <div>
        <h1 id="reportTitle">Exportación y reportes de entregas</h1>
        <p class="sub">Filtra por fecha, área, placa o conductor.</p>
      </div>
    </div>

    <!-- Atajos de fecha -->
    <div class="chips" id="chips" role="group" aria-label="Atajos de fecha">
      <button type="button" class="chip" data-r="hoy">Hoy</button>
      <button type="button" class="chip" data-r="ayer">Ayer</button>
      <button type="button" class="chip" data-r="7">Últimos 7 días</button>
      <button type="button" class="chip on" data-r="mes">Este mes</button>
    </div>

    <!-- Campos de Filtro -->
    <div class="filters-grid">
      <div>
        <label for="desde">Desde</label>
        <div class="date-input-wrap">
          <input type="text" id="desde" name="desde" class="report-datepicker" value="<?= $firstDayOfMonth ?>" autocomplete="off">
          <span class="date-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </span>
        </div>
      </div>
      <div>
        <label for="hasta">Hasta</label>
        <div class="date-input-wrap">
          <input type="text" id="hasta" name="hasta" class="report-datepicker" value="<?= $todayDate ?>" autocomplete="off">
          <span class="date-icon" aria-hidden="true">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </span>
        </div>
      </div>
      <div id="wrap-area">
        <label for="area">Área</label>
        <select id="area" name="area">
          <option value="">Todas</option>
          <?php if (!empty($catalogAreas)): ?>
            <?php foreach ($catalogAreas as $areaItem): ?>
              <option value="<?= htmlspecialchars($areaItem) ?>"><?= htmlspecialchars($areaItem) ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      <div id="wrap-unidad">
        <label for="unidad">Placa</label>
        <select id="unidad" name="unidad">
          <option value="">Todas</option>
          <?php if (!empty($uniquePlates)): ?>
            <?php foreach ($uniquePlates as $plate): ?>
              <option value="<?= htmlspecialchars($plate) ?>"><?= htmlspecialchars($plate) ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
      <div id="wrap-responsable">
        <label for="responsable">Conductor</label>
        <select id="responsable" name="responsable">
          <option value="">Todos</option>
          <?php if (!empty($uniqueDrivers)): ?>
            <?php foreach ($uniqueDrivers as $driver): ?>
              <option value="<?= htmlspecialchars($driver) ?>"><?= htmlspecialchars($driver) ?></option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
      </div>
    </div>

    <!-- Barra de acciones y conteo -->
    <div class="actions-bar">
      <div class="count" aria-live="polite">
        <span class="status-dot-green" aria-hidden="true"></span>
        <strong id="n">...</strong> entregas coinciden con los filtros
      </div>
      <div class="right">
        <button class="link" id="limpiar" type="button">Limpiar filtros</button>
        <div class="export-dropdown">
          <button class="btn-main" id="exportar" type="button" aria-haspopup="true" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M12 3v12m0 0l-4-4m4 4l4-4M4 21h16"/>
            </svg>
            Exportar
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M6 9l6 6 6-6"/>
            </svg>
          </button>
          <div class="export-menu" id="menu" hidden>
            <button type="button" data-f="pdf">
              <svg class="pdf" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 3H7a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V8z"/>
                <path d="M14 3v5h5"/>
              </svg>
              Reporte PDF
            </button>
            <button type="button" data-f="excel">
              <svg class="xls" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="4" y="4" width="16" height="16" rx="2"/>
                <path d="M4 10h16M4 15h16M10 4v16"/>
              </svg>
              Excel (.xlsx)
            </button>
            <button type="button" data-f="csv">
              <svg class="csv" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
              CSV
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="toast-msg" id="toast" role="status"></div>
  </section>

</div>

<script>
  (function() {
    const $ = id => document.getElementById(id);
    const BASE_URL = '<?= BASE_URL ?>';
    let fpDesde = null;
    let fpHasta = null;

    const formatDateIso = (d) => {
      const pad = n => String(n).padStart(2, '0');
      return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    };

    function initReportFlatpickr() {
      if (typeof flatpickr === 'undefined') return;

      const elDesde = $('desde');
      const elHasta = $('hasta');
      if (!elDesde || !elHasta) return;

      if (elDesde._flatpickr) elDesde._flatpickr.destroy();
      if (elHasta._flatpickr) elHasta._flatpickr.destroy();

      const commonConfig = {
        locale: "es",
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        altInputClass: "report-datepicker flatpickr-input",
        disableMobile: "true",
        onChange: function() {
          document.querySelectorAll('.chip').forEach(c => c.classList.remove('on'));
          actualizarConteo();
        }
      };

      fpDesde = flatpickr(elDesde, commonConfig);
      fpHasta = flatpickr(elHasta, commonConfig);
    }

    function setRango(r) {
      const hoy = new Date();
      let desde = new Date();
      let hasta = new Date();

      if (r === 'hoy') {
        desde = hoy;
        hasta = hoy;
      } else if (r === 'ayer') {
        const d = new Date();
        d.setDate(hoy.getDate() - 1);
        desde = d;
        hasta = d;
      } else if (r === '7') {
        const d = new Date();
        d.setDate(hoy.getDate() - 6);
        desde = d;
        hasta = hoy;
      } else if (r === 'mes') {
        desde = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
        hasta = hoy;
      }

      const strDesde = formatDateIso(desde);
      const strHasta = formatDateIso(hasta);

      const inputDesde = $('desde');
      const inputHasta = $('hasta');

      if (fpDesde) {
        fpDesde.setDate(strDesde, false);
      } else if (inputDesde) {
        inputDesde.value = strDesde;
      }

      if (fpHasta) {
        fpHasta.setDate(strHasta, false);
      } else if (inputHasta) {
        inputHasta.value = strHasta;
      }
    }

    let abortController = null;
    function actualizarConteo() {
      const desde = $('desde').value;
      const hasta = $('hasta').value;
      const area = $('area').value;
      const unidad = $('unidad').value;
      const responsable = $('responsable').value;

      $('n').textContent = '...';

      if (abortController) {
        abortController.abort();
      }
      abortController = new AbortController();

      const params = new URLSearchParams({
        desde: desde || '',
        hasta: hasta || '',
        area: area || '',
        unidad: unidad || '',
        responsable: responsable || ''
      });

      fetch(`${BASE_URL}api/reportes/count?${params.toString()}`, {
        signal: abortController.signal
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          $('n').textContent = Number(data.count).toLocaleString('es-PE');
        } else {
          $('n').textContent = '0';
        }
      })
      .catch(err => {
        if (err.name !== 'AbortError') {
          $('n').textContent = '0';
        }
      });
    }

    // ── Componente de Listas Desplegables Elegantes (Custom Dropdowns) ──
    const dropdownControllers = [];

    function makeCustomDropdown(selectId, config = {}) {
      const select = $(selectId);
      if (!select) return;

      select.style.display = 'none';
      const wrapper = select.parentElement;

      const container = document.createElement('div');
      container.className = 'custom-select-container';

      const trigger = document.createElement('div');
      trigger.className = 'custom-select-trigger';
      trigger.tabIndex = 0;

      const content = document.createElement('div');
      content.className = 'custom-select-selected-content';

      const labelText = document.createElement('span');
      labelText.className = 'custom-select-label-text placeholder';
      content.appendChild(labelText);

      const rightBox = document.createElement('div');
      rightBox.className = 'custom-select-right';

      const clearBtn = document.createElement('span');
      clearBtn.className = 'custom-select-clear';
      clearBtn.title = 'Limpiar selección';
      clearBtn.style.display = 'none';
      clearBtn.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;

      const chevron = document.createElement('span');
      chevron.className = 'custom-select-chevron';
      chevron.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>`;

      rightBox.appendChild(clearBtn);
      rightBox.appendChild(chevron);

      trigger.appendChild(content);
      trigger.appendChild(rightBox);

      const menu = document.createElement('div');
      menu.className = 'custom-select-menu';

      let searchInput = null;
      if (config.searchable) {
        const searchWrap = document.createElement('div');
        searchWrap.className = 'custom-select-search-wrap';
        searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'custom-select-search-input';
        searchInput.placeholder = config.searchPlaceholder || 'Buscar...';
        searchWrap.appendChild(searchInput);
        menu.appendChild(searchWrap);
      }

      const list = document.createElement('div');
      list.className = 'custom-select-list';
      menu.appendChild(list);

      const noResults = document.createElement('div');
      noResults.className = 'custom-select-no-results';
      noResults.textContent = 'Sin coincidencias';
      noResults.style.display = 'none';
      menu.appendChild(noResults);

      container.appendChild(trigger);
      container.appendChild(menu);
      wrapper.appendChild(container);

      function updateTriggerUI() {
        const selectedOption = select.options[select.selectedIndex];
        const val = selectedOption ? selectedOption.value : '';
        const txt = selectedOption ? selectedOption.text : (config.defaultLabel || 'Todas');

        if (val === '') {
          labelText.textContent = config.defaultLabel || txt;
          labelText.className = 'custom-select-label-text placeholder';
          clearBtn.style.display = 'none';
        } else {
          labelText.textContent = txt;
          labelText.className = 'custom-select-label-text has-value';
          clearBtn.style.display = 'flex';
        }

        list.querySelectorAll('.custom-select-item').forEach(item => {
          const itemVal = item.dataset.value;
          const isSelected = (itemVal === val);
          item.classList.toggle('selected', isSelected);
          const checkIcon = item.querySelector('.item-check');
          if (checkIcon) checkIcon.style.visibility = isSelected ? 'visible' : 'hidden';
        });
      }

      function renderItems() {
        list.innerHTML = '';
        Array.from(select.options).forEach(opt => {
          const item = document.createElement('div');
          item.className = 'custom-select-item';
          item.dataset.value = opt.value;
          item.dataset.text = opt.text.toLowerCase();

          const textSpan = document.createElement('span');
          textSpan.textContent = opt.value === '' ? (config.defaultLabel || opt.text) : opt.text;

          const check = document.createElement('span');
          check.className = 'item-check';
          check.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`;

          item.appendChild(textSpan);
          item.appendChild(check);

          item.addEventListener('click', () => {
            select.value = opt.value;
            select.dispatchEvent(new Event('change', { bubbles: true }));
            closeMenu();
          });

          list.appendChild(item);
        });
        updateTriggerUI();
      }

      function filterItems(term) {
        const clean = term.toLowerCase().trim();
        let matches = 0;
        list.querySelectorAll('.custom-select-item').forEach(item => {
          const text = item.dataset.text || '';
          const isClearOption = item.dataset.value === '';
          if (isClearOption) {
            item.style.display = 'flex';
            matches++;
          } else {
            const visible = clean === '' || text.includes(clean);
            item.style.display = visible ? 'flex' : 'none';
            if (visible) matches++;
          }
        });
        noResults.style.display = matches === 0 ? 'block' : 'none';
      }

      if (searchInput) {
        searchInput.addEventListener('input', e => {
          filterItems(e.target.value);
        });
        searchInput.addEventListener('click', e => e.stopPropagation());
      }

      function openMenu() {
        document.querySelectorAll('.custom-select-container.open').forEach(c => {
          if (c !== container) c.classList.remove('open');
        });
        container.classList.add('open');
        if (searchInput) {
          searchInput.value = '';
          filterItems('');
          setTimeout(() => searchInput.focus(), 60);
        }
      }

      function closeMenu() {
        container.classList.remove('open');
      }

      trigger.addEventListener('click', e => {
        e.stopPropagation();
        if (container.classList.contains('open')) {
          closeMenu();
        } else {
          openMenu();
        }
      });

      trigger.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          trigger.click();
        } else if (e.key === 'Escape') {
          closeMenu();
        }
      });

      clearBtn.addEventListener('click', e => {
        e.stopPropagation();
        select.value = '';
        select.dispatchEvent(new Event('change', { bubbles: true }));
        updateTriggerUI();
        closeMenu();
      });

      select.addEventListener('change', () => {
        updateTriggerUI();
      });

      renderItems();

      dropdownControllers.push({
        reset: () => {
          select.value = '';
          updateTriggerUI();
        }
      });
    }

    // Inicializar los 3 selects con la nueva UI
    makeCustomDropdown('area', {
      defaultLabel: 'Todas',
      searchable: false
    });

    makeCustomDropdown('unidad', {
      defaultLabel: 'Todas',
      searchable: true,
      searchPlaceholder: 'Buscar placa...'
    });

    makeCustomDropdown('responsable', {
      defaultLabel: 'Todos',
      searchable: true,
      searchPlaceholder: 'Buscar conductor...'
    });

    // Cerrar al hacer click fuera
    document.addEventListener('click', e => {
      if (!e.target.closest('.custom-select-container')) {
        document.querySelectorAll('.custom-select-container.open').forEach(c => c.classList.remove('open'));
      }
    });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        document.querySelectorAll('.custom-select-container.open').forEach(c => c.classList.remove('open'));
      }
    });

    // Manejo de chips
    document.querySelectorAll('.chip').forEach(chip => {
      chip.addEventListener('click', () => {
        document.querySelectorAll('.chip').forEach(c => c.classList.remove('on'));
        chip.classList.add('on');
        setRango(chip.dataset.r);
        actualizarConteo();
      });
    });

    // Detectar cambios en los inputs de fecha y selects
    ['desde', 'hasta', 'area', 'unidad', 'responsable'].forEach(id => {
      const el = $(id);
      if (!el) return;
      el.addEventListener('change', () => {
        if (id === 'desde' || id === 'hasta') {
          document.querySelectorAll('.chip').forEach(c => c.classList.remove('on'));
        }
        actualizarConteo();
      });
    });

    // Limpiar filtros
    $('limpiar').addEventListener('click', () => {
      dropdownControllers.forEach(ctl => ctl.reset());
      document.querySelectorAll('.chip').forEach(c => c.classList.remove('on'));
      const chipMes = document.querySelector('.chip[data-r="mes"]');
      if (chipMes) chipMes.classList.add('on');
      setRango('mes');
      actualizarConteo();
    });

    // Menú desplegable Exportar
    const menu = $('menu');
    const btn = $('exportar');
    function toggleMenu(abrir) {
      menu.hidden = !abrir;
      btn.setAttribute('aria-expanded', String(abrir));
    }

    btn.addEventListener('click', e => {
      e.stopPropagation();
      toggleMenu(menu.hidden);
    });

    document.addEventListener('click', () => toggleMenu(false));

    // Validar y ejecutar exportación
    function ejecutarExportacion(formato) {
      const valDesde = $('desde').value;
      const valHasta = $('hasta').value;

      if (!valDesde || !valHasta) {
        alert("Por favor, seleccione un rango de fechas válido para exportar el reporte.");
        return;
      }

      const dDesde = new Date(valDesde + 'T00:00:00');
      const dHasta = new Date(valHasta + 'T00:00:00');

      if (dHasta < dDesde) {
        alert("La fecha fin no puede ser anterior a la fecha de inicio.");
        return;
      }

      const diffDays = Math.ceil(Math.abs(dHasta - dDesde) / (1000 * 60 * 60 * 24));
      if (diffDays > 366) {
        alert("El rango máximo de exportación es de 1 año (366 días). Por favor reduzca el período.");
        return;
      }

      const params = new URLSearchParams({
        desde: valDesde,
        hasta: valHasta,
        area: $('area').value,
        unidad: $('unidad').value,
        responsable: $('responsable').value
      });

      const endpointMap = {
        pdf: 'reportes/pdf',
        excel: 'reportes/excel',
        csv: 'reportes/csv'
      };

      const path = endpointMap[formato] || 'reportes/pdf';
      const url = `${BASE_URL}${path}?${params.toString()}`;

      $('toast').textContent = `Generando reporte ${formato.toUpperCase()}...`;
      setTimeout(() => { $('toast').textContent = ''; }, 3500);

      window.open(url, '_blank');
    }

    menu.addEventListener('click', e => {
      const targetBtn = e.target.closest('button');
      if (!targetBtn) return;
      const formato = targetBtn.dataset.f;
      ejecutarExportacion(formato);
      toggleMenu(false);
    });

    // Inicializar Flatpickr con formato visual d/m/Y y soporte de tema
    initReportFlatpickr();

    // Iniciar con "Este mes" y conteo
    setRango('mes');
    actualizarConteo();
  })();
</script>