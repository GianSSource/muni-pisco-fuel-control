

<style>
    .form-control-styled {
        width: 100%;
        height: 48px;
        min-height: 48px;
        max-height: 48px;
        padding: 0 14px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #cbd5e1);
        background: var(--input-bg, #fff);
        color: var(--text-color, inherit);
        font-size: 0.88rem;
        line-height: 46px;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
        display: block;
    }

    .form-control-styled:hover {
        border-color: #94a3b8;
    }

    .form-control-styled:focus {
        outline: none;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
    }

    /* Grilla de filas y etiquetas estandarizadas para alineación perfecta */
    .dispatch-form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-bottom: 18px;
        align-items: start;
    }

    .dispatch-field-label {
        display: flex;
        align-items: center;
        height: 22px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-color, #1e293b);
        margin-bottom: 6px;
        line-height: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    [data-theme="dark"] .dispatch-field-label {
        color: #f1f5f9;
    }

    .dispatch-helper-text {
        font-size: 0.74rem;
        line-height: 1.35;
        color: var(--text-muted, #64748b);
        margin: 5px 0 0 2px;
    }

    [data-theme="dark"] .dispatch-helper-text {
        color: #94a3b8;
    }

    .btn-submit-styled {
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 24px;
        border-radius: 8px;
        background: #3b82f6;
        color: #ffffff;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        box-sizing: border-box;
    }

    .btn-submit-styled:hover {
        background: #2563eb !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    .btn-submit-styled:disabled {
        opacity: 0.65;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-cancel-styled {
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 20px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #cbd5e1);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .btn-cancel-styled:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: #94a3b8;
    }

    .btn-clear-styled {
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 18px;
        border-radius: 8px;
        border: 1px solid #f97316;
        background: transparent;
        color: #f97316;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }

    .btn-clear-styled:hover {
        background: rgba(249, 115, 22, 0.10);
        border-color: #ea580c;
        color: #ea580c;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.2);
    }

    .btn-clear-styled:active {
        transform: translateY(0);
    }

    /* Altura estandarizada uniforme para selectores simples y multilínea (48px exactos) */
    .custom-select-trigger {
        height: 48px !important;
        min-height: 48px !important;
        max-height: 48px !important;
        padding: 0 14px !important;
        border-radius: 8px;
        border: 1px solid var(--border-color, #cbd5e1);
        background: var(--input-bg, #fff);
        color: var(--text-color, inherit);
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-select-trigger:hover {
        border-color: #94a3b8;
    }

    .custom-select-trigger:focus,
    .custom-select-container.open .custom-select-trigger {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
    }

    .custom-select-trigger .custom-select-chevron {
        margin-left: 10px;
        flex-shrink: 0;
        transition: transform 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .custom-select-container.open .custom-select-chevron {
        transform: rotate(180deg);
        color: #3b82f6;
    }

    /* =========================================================
       SELECTOR DE VEHÍCULO / UNIDAD AGRUPADO POR ÁREA
       ========================================================= */
    .vehicle-grouped-select-container {
        position: relative;
    }

    .vehicle-grouped-select-container .custom-select-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 48px !important;
        min-height: 48px !important;
        max-height: 48px !important;
        padding: 0 14px !important;
        cursor: pointer;
        user-select: none;
        box-sizing: border-box;
    }

    .v-trigger-layout {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        min-width: 0;
        gap: 8px;
    }

    .v-trigger-left {
        display: flex;
        align-items: center;
        gap: 6px;
        min-width: 0;
        overflow: hidden;
        flex: 1;
    }

    .v-trigger-left .v-item-plate {
        white-space: nowrap;
        flex-shrink: 0;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-color, #0f172a);
        letter-spacing: 0.2px;
        line-height: 1;
    }

    [data-theme="dark"] .v-trigger-left .v-item-plate {
        color: #f8fafc;
    }

    .v-trigger-left .v-item-sub {
        margin-top: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.74rem;
        font-weight: 500;
        color: var(--text-muted, #64748b);
        line-height: 1;
    }

    [data-theme="dark"] .v-trigger-left .v-item-sub {
        color: #94a3b8;
    }

    .v-trigger-right {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        margin-left: auto;
    }

    .vehicle-grouped-select-container.open .custom-select-trigger {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
    }

    .vehicle-grouped-select-container.open .custom-select-chevron {
        transform: rotate(180deg);
        color: #3b82f6;
    }

    .v-grouped-menu {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        z-index: 1000;
        background: var(--card-bg, #ffffff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 10px;
        box-shadow: 0 16px 36px -4px rgba(0, 0, 0, 0.22), 0 8px 16px -6px rgba(0, 0, 0, 0.12);
        display: none;
        overflow: hidden;
    }

    [data-theme="dark"] .v-grouped-menu {
        background: #0f172a;
        border-color: #1e293b;
        box-shadow: 0 20px 40px -4px rgba(0, 0, 0, 0.6);
    }

    .vehicle-grouped-select-container.open .v-grouped-menu {
        display: block;
    }

    .v-search-box {
        padding: 8px 10px;
        background: inherit;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
    }

    [data-theme="dark"] .v-search-box {
        border-bottom-color: rgba(255, 255, 255, 0.08);
    }

    .v-search-input {
        width: 100%;
        padding: 6px 10px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid var(--border-color, #cbd5e1);
        background: var(--input-bg, #ffffff);
        color: var(--text-color, #0f172a);
        font-size: 0.80rem;
        box-sizing: border-box;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .v-search-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25) !important;
    }

    [data-theme="dark"] .v-search-input {
        background: #0b1120;
        border-color: #334155;
        color: #f8fafc;
    }

    .v-list-container {
        max-height: 270px;
        overflow-y: auto;
        padding: 3px 5px;
    }

    .v-list-container::-webkit-scrollbar {
        width: 6px;
    }
    .v-list-container::-webkit-scrollbar-track {
        background: transparent;
    }
    .v-list-container::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 4px;
    }
    .v-list-container::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.5);
    }

    .v-group-wrap {
        margin-bottom: 6px;
    }

    .v-group-wrap:last-child {
        margin-bottom: 2px;
    }

    .v-group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 8px 3px 8px;
    }

    .v-group-title {
        font-size: 0.67rem;
        font-weight: 700;
        color: #2563eb;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    [data-theme="dark"] .v-group-title {
        color: #38bdf8;
    }

    .v-group-count {
        font-size: 0.67rem;
        font-weight: 600;
        color: #64748b;
    }

    [data-theme="dark"] .v-group-count {
        color: #94a3b8;
    }

    .v-group-item {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        text-align: left;
        padding: 6px 8px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.15s ease;
        margin: 1px 0;
        user-select: none;
    }

    /* Ocultar elementos filtrados */
    .v-hidden,
    .v-group-item.v-hidden,
    .v-group-wrap.v-hidden {
        display: none !important;
    }

    .v-group-item:hover,
    .v-group-item.is-focused {
        background: rgba(37, 99, 235, 0.08);
    }

    [data-theme="dark"] .v-group-item:hover,
    [data-theme="dark"] .v-group-item.is-focused {
        background: #1e293b;
    }

    .v-group-item.selected {
        background: rgba(37, 99, 235, 0.12);
    }

    [data-theme="dark"] .v-group-item.selected {
        background: rgba(59, 130, 246, 0.18);
    }

    .v-item-top {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        min-width: 0;
        gap: 8px;
    }

    .v-item-plate-wrap {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px;
        min-width: 0;
        flex: 1;
    }

    .v-item-plate {
        font-size: 0.84rem;
        font-weight: 700;
        color: var(--text-color, #0f172a);
        letter-spacing: 0.2px;
        white-space: nowrap;
    }

    [data-theme="dark"] .v-item-plate {
        color: #f8fafc;
    }

    .v-item-check {
        color: #2563eb;
        font-weight: 800;
        font-size: 0.78rem;
        line-height: 1;
    }

    [data-theme="dark"] .v-item-check {
        color: #38bdf8;
    }

    .v-item-badges {
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px;
        flex-shrink: 0;
        margin-left: auto;
    }

    .v-item-sub {
        font-size: 0.72rem;
        font-weight: 500;
        color: var(--text-muted, #64748b);
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.25;
        width: 100%;
        text-align: left !important;
    }

    [data-theme="dark"] .v-item-sub {
        color: #94a3b8;
    }

    /* Badges de Combustible */
    .v-badge {
        font-size: 0.65rem;
        font-weight: 600;
        height: 18px;
        padding: 0 6px;
        border-radius: 3px;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        letter-spacing: 0.1px;
        box-sizing: border-box;
    }

    /* Diésel */
    .v-badge-diesel {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid rgba(4, 120, 87, 0.25);
    }
    [data-theme="dark"] .v-badge-diesel {
        background: #064e3b;
        color: #34d399;
        border: 1px solid rgba(52, 211, 153, 0.25);
    }

    /* Gasolina */
    .v-badge-gasolina {
        background: #fff7ed;
        color: #c2410c;
        border: 1px solid rgba(234, 88, 12, 0.25);
    }
    [data-theme="dark"] .v-badge-gasolina {
        background: #451a03;
        color: #fb923c;
        border: 1px solid rgba(251, 146, 60, 0.25);
    }

    /* GNV */
    .v-badge-gnv {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid rgba(29, 78, 216, 0.25);
    }
    [data-theme="dark"] .v-badge-gnv {
        background: #1e3a8a;
        color: #60a5fa;
        border: 1px solid rgba(96, 165, 250, 0.25);
    }

    /* GLP */
    .v-badge-glp {
        background: #fefce8;
        color: #b45309;
        border: 1px solid rgba(180, 83, 9, 0.25);
    }
    [data-theme="dark"] .v-badge-glp {
        background: #422006;
        color: #fde047;
        border: 1px solid rgba(253, 224, 71, 0.25);
    }

    /* Otro */
    .v-badge-otro {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid rgba(100, 116, 139, 0.25);
    }
    [data-theme="dark"] .v-badge-otro {
        background: #1e293b;
        color: #94a3b8;
        border: 1px solid rgba(148, 163, 184, 0.25);
    }

    /* Footer del Menú */
    .v-select-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 10px;
        border-top: 1px solid var(--border-color, #e2e8f0);
        font-size: 0.68rem;
        color: var(--text-muted, #64748b);
        background: inherit;
        user-select: none;
    }

    [data-theme="dark"] .v-select-footer {
        border-top-color: rgba(255, 255, 255, 0.08);
        color: #94a3b8;
    }

    .v-footer-hints {
        opacity: 0.85;
        letter-spacing: 0.2px;
        font-size: 0.68rem;
    }
</style>

<section class="container" style="max-width: 900px; margin: 40px auto; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);">Registrar
                Vale de Despacho</h1>
            <p style="color: var(--text-muted, #64748b); margin-top: 4px; font-size: 0.95rem;">Emisión de vale de
                consumo de combustible para flota vehicular</p>
        </div>
        <a href="<?= BASE_URL ?>despachos" class="btn-cancel-styled" style="font-size: 0.9rem; font-weight: 500;">
            ← Volver a Despachos
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="catalog-alert catalog-alert-danger" style="margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>despachos/guardar" id="dispatchForm" novalidate autocomplete="off"
        onsubmit="return validarFormularioDespacho(event)"
        style="background: var(--card-bg, #ffffff); padding: 28px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <input type="hidden" name="auth_password" id="auth_password_input" value="">

        <!-- Carga de Alerta de Validación de Campos Faltantes -->
        <div id="formValidationAlert"
            style="display: none; background: rgba(249, 115, 22, 0.10); border: 1px solid #f97316; border-left: 4px solid #f97316; color: #f97316; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.88rem; line-height: 1.5;">
        </div>

        <div class="dispatch-form-row">
            <div style="min-width: 0;">
                <label class="dispatch-field-label">Tipo de Combustible (Stock Total)</label>
                <select id="fuel_type_select" name="fuel_type" class="form-control-styled" required
                    <?= empty($fuelGroups) ? 'disabled' : '' ?>>
                    <?php if (!empty($fuelGroups)): ?>
                        <option value="">Seleccione un tipo de combustible...</option>
                        <?php foreach ($fuelGroups as $grp): ?>
                            <?php
                            $totalFormatted = number_format($grp['total_stock'], 2);
                            $lotsJson = htmlspecialchars(json_encode($grp['lots']), ENT_QUOTES, 'UTF-8');
                            ?>
                            <?php $fuelUnit = \App\Models\Catalog::getFuelUnit($grp['fuel_type'], true); ?>
                            <option value="<?= htmlspecialchars($grp['fuel_type']) ?>"
                                data-stock="<?= $grp['total_stock'] ?>"
                                data-stock-formatted="<?= $totalFormatted ?>"
                                data-lots="<?= $lotsJson ?>">
                                <?= htmlspecialchars($grp['fuel_type']) ?> — Stock total: <?= $totalFormatted ?> <?= $fuelUnit ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay lotes de combustible disponibles</option>
                    <?php endif; ?>
                </select>

                <!-- El tipo de combustible ya se envía directo desde el select
                     de arriba (name="fuel_type"). El backend decide por sí solo
                     de qué lote(s) descontar (FIFO, repartiendo entre varios si
                     ninguno alcanza solo), así que ya no hace falta que el JS
                     elija y envíe un fuel_income_id fijo. -->

                <?php if (empty($fuelGroups)): ?>
                    <p style="color: #b45309; font-size: 0.8rem; margin: 6px 0 0;">No hay ingresos de combustible
                        registrados (o el stock está en 0). Un administrador debe registrar un ingreso en el módulo
                        de <a href="<?= BASE_URL ?>ingresos/nuevo" style="color:#b45309; text-decoration:underline;">Ingresos</a>
                        antes de poder emitir vales de despacho.</p>
                <?php endif; ?>

                <!-- Indica de qué lote específico se descontará, una vez el JS lo asigna -->
                <p id="lotAssignedInfo"
                    style="display: none; margin-top: 8px; padding: 8px 12px; background: #e0f2fe; border: 1px solid #7dd3fc; border-radius: 6px; font-size: 0.82rem; color: #0369a1; font-weight: 700;">
                </p>
            </div>

            <div style="min-width: 0;">
                <label class="dispatch-field-label">Vehículo / Unidad</label>
                <select name="vehicle_id" id="vehicle_id" class="form-control-styled" required>
                    <option value="">Seleccione un vehículo...</option>
                    <?php if (!empty($vehicles)): ?>
                        <?php
                        usort($vehicles, function ($a, $b) {
                            $areaA = trim($a['area_name'] ?? 'Sin Área');
                            $areaB = trim($b['area_name'] ?? 'Sin Área');
                            $isSinA = (stripos($areaA, 'sin área') !== false || stripos($areaA, 'sin area') !== false);
                            $isSinB = (stripos($areaB, 'sin área') !== false || stripos($areaB, 'sin area') !== false);
                            if ($isSinA && !$isSinB) return 1;
                            if (!$isSinA && $isSinB) return -1;
                            $cmpArea = strcoll($areaA, $areaB);
                            if ($cmpArea !== 0) return $cmpArea;
                            $cmpPlate = strnatcasecmp($a['plate'] ?? '', $b['plate'] ?? '');
                            if ($cmpPlate !== 0) return $cmpPlate;
                            return strnatcasecmp($a['unit_code'] ?? '', $b['unit_code'] ?? '');
                        });
                        ?>
                        <?php foreach ($vehicles as $v): ?>
                            <option value="<?= $v['id'] ?>" data-area="<?= htmlspecialchars($v['area_name'] ?? 'Sin Área') ?>"
                                data-plate="<?= htmlspecialchars($v['plate'] ?? '') ?>"
                                data-unit="<?= htmlspecialchars($v['unit_code'] ?? '') ?>"
                                data-type="<?= htmlspecialchars($v['vehicle_type_name'] ?? 'Sin tipo') ?>"
                                data-fuel-type="<?= htmlspecialchars($v['fuel_type_name'] ?? '') ?>"
                                data-fuel-unit="<?= htmlspecialchars(\App\Models\Catalog::getFuelUnit($v['fuel_type_name'] ?? '')) ?>">
                                <?= htmlspecialchars($v['area_name'] ?? 'Sin Área') ?> —
                                <?= htmlspecialchars($v['plate'] ?? '') ?> (<?= htmlspecialchars($v['unit_code'] ?? '') ?>)
                                [<?= htmlspecialchars($v['vehicle_type_name'] ?? 'Sin tipo') ?> · <?= htmlspecialchars($v['fuel_type_name'] ?? 'Sin combustible') ?>]
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <?php if (empty($vehicles)): ?>
                    <p style="color: #b45309; font-size: 0.8rem; margin: 6px 0 0;">No hay vehículos registrados. Un
                        administrador debe registrarlos en
                        <a href="<?= BASE_URL ?>admin/catalogos" style="color:#b45309; text-decoration:underline;">Catálogos</a>.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="dispatch-form-row">
            <div style="min-width: 0;">
                <label class="dispatch-field-label">Conductor / Responsable</label>
                <select name="driver_name" id="driver_name" class="form-control-styled" required>
                    <option value="">Seleccione o escriba un conductor...</option>
                    <?php foreach ($drivers as $d): ?>
                        <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($drivers)): ?>
                    <p class="dispatch-helper-text">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block; vertical-align:-1px; margin-right:3px; color:#2563eb;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        Escriba el nombre en el campo y seleccione <strong style="color: #2563eb;">"Usar nuevo conductor"</strong>.
                    </p>
                <?php endif; ?>
            </div>

            <div style="min-width: 0;">
                <label id="gallonsLabel" class="dispatch-field-label">Cantidad de Galones (Máx. 15,000.00)</label>
                <input type="text" name="gallons" id="gallons" class="form-control-styled" required
                    placeholder="Ej: 15,000" maxlength="12" autocomplete="off"
                    oninput="limitarGalones(this); verificarCamposIncompletos();"
                    onblur="formatearSalidaGalones(this); verificarCamposIncompletos();"
                    onfocus="limpiarFormatoGalones(this)">
            </div>
        </div>

        <div id="stockWarning"
            style="display:none; border-left:5px solid #ef4444; border-radius:10px; padding:14px 18px; margin-bottom:16px; align-items:flex-start; gap:12px;">
        </div>

        <!-- Campos Independientes para Área/Destino (Desplegable) y Observaciones -->
        <div class="dispatch-form-row" style="margin-bottom: 24px;">
            <div style="min-width: 0;">
                <label class="dispatch-field-label">Área / Subgerencia Destino</label>
                <select name="destination" id="destination" class="form-control-styled" required
                    <?= empty($areas) ? 'disabled' : '' ?>>
                    <option value="">Seleccione un área...</option>
                    <?php foreach ($areas as $ar): ?>
                        <option value="<?= htmlspecialchars($ar) ?>"><?= htmlspecialchars($ar) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($areas)): ?>
                    <p style="color: #b45309; font-size: 0.8rem; margin: 6px 0 0;">No hay áreas/subgerencias
                        registradas en el catálogo. Un administrador debe registrarlas en
                        <a href="<?= BASE_URL ?>admin/catalogos" style="color:#b45309; text-decoration:underline;">Catálogos</a>
                        antes de poder emitir vales de despacho.</p>
                <?php endif; ?>
            </div>

            <div style="min-width: 0;">
                <label class="dispatch-field-label">Observaciones (Opcional)</label>
                <input type="text" name="notes" id="notes" class="form-control-styled"
                    placeholder="Ej. Turno noche maximo 50 caracteres" maxlength="50" autocomplete="off"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s.,\-\/()]/g, '')">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
            <button type="button" class="btn-clear-styled" onclick="limpiarFormulario(true)" title="Limpiar todos los campos">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 2L7 12"/>
                    <path d="M7 12c-2 2-3 4.5-3 7l3-1 1 3c2.5 0 5-1 7-3L7 12z"/>
                    <path d="M14 9l3 3"/>
                </svg>
                Limpiar Campos
            </button>
            <div style="display: flex; gap: 12px;">
                <a href="<?= BASE_URL ?>despachos" class="btn-cancel-styled">
                    Cancelar
                </a>
                <button type="submit" id="submitBtn" class="btn-submit-styled">
                    Emitir Vale de Despacho
                </button>
            </div>
        </div>
    </form>
</section>

<!-- Modal de Validación de Identidad para Registro de Despacho -->
<div id="authValidateModal" class="modal-overlay" onclick="cerrarModalAuth(event)">
    <div class="modal-card" style="max-width: 440px;" onclick="event.stopPropagation()">
        <div class="modal-header" style="border-bottom: 1px solid var(--border-color, #e2e8f0); padding: 18px 22px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(37, 99, 235, 0.12); color: #2563eb; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--ink);">Validación de Identidad</h3>
                    <p style="margin: 2px 0 0; font-size: 0.8rem; color: var(--text-muted, #64748b);">Confirme sus credenciales para autorizar el despacho</p>
                </div>
            </div>
            <button type="button" onclick="cerrarModalAuth()" style="background: none; border: none; font-size: 1.4rem; color: var(--text-muted, #64748b); cursor: pointer; line-height: 1; padding: 4px; border-radius: 6px;" title="Cerrar">&times;</button>
        </div>

        <form id="authValidateForm" autocomplete="off" onsubmit="confirmarIdentidadYDespachar(event)" style="padding: 22px;">
            <!-- Inputs señuelo para evitar que el navegador muestre su popup de 'Contraseñas guardadas' -->
            <input type="text" name="fake_username_decoy" style="display:none;" tabindex="-1" autocomplete="off">
            <input type="password" name="fake_password_decoy" style="display:none;" tabindex="-1" autocomplete="off">

            <div style="background: var(--bg-body, #f8fafc); border: 1px solid var(--border-color, #e2e8f0); border-radius: 10px; padding: 12px 14px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 36px; height: 36px; border-radius: 50%; background: #2563eb; color: #fff; font-weight: 700; font-size: 0.95rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <?= strtoupper(substr($_SESSION['user']['name'] ?? 'U', 0, 1)) ?>
                </div>
                <div style="min-width: 0; flex: 1;">
                    <div style="font-size: 0.88rem; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Usuario') ?>
                    </div>
                    <div style="font-size: 0.78rem; color: var(--text-muted, #64748b); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        <?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>
                    </div>
                </div>
                <span style="font-size: 0.72rem; padding: 3px 8px; border-radius: 6px; font-weight: 700; background: rgba(37, 99, 235, 0.1); color: #2563eb; text-transform: uppercase;">
                    <?= htmlspecialchars($_SESSION['user']['role'] ?? 'operador') ?>
                </span>
            </div>

            <!-- Alerta de Error de Contraseña en el Modal -->
            <div id="authModalError" style="display: none; background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; color: #ef4444; border-radius: 8px; padding: 10px 12px; margin-bottom: 16px; font-size: 0.85rem; font-weight: 600; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span id="authModalErrorText">Contraseña incorrecta.</span>
            </div>

            <div style="margin-bottom: 20px;">
                <label for="authModalPassword" style="display: block; font-size: 0.86rem; font-weight: 600; margin-bottom: 6px; color: var(--ink);">
                    Ingrese su Contraseña
                </label>
                <div style="position: relative;">
                    <input type="password" id="authModalPassword" name="auth_confirm_key" class="form-control-styled" placeholder="••••••••••••" maxlength="50" required autocomplete="new-password" data-lpignore="true" data-1p-ignore="true" data-form-type="other" readonly onfocus="this.removeAttribute('readonly');" style="padding-right: 42px; width: 100%;">
                    <button type="button" id="toggleAuthPasswordBtn" onclick="toggleVisibilidadPasswordAuth()" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--text-muted, #64748b); cursor: pointer; padding: 6px; display: flex; align-items: center; border-radius: 4px;" title="Ver/Ocultar contraseña">
                        <svg id="eyeIconOpen" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg id="eyeIconClosed" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn-cancel-styled" onclick="cerrarModalAuth()" style="padding: 9px 16px; font-size: 0.88rem;">
                    Cancelar
                </button>
                <button type="submit" id="authConfirmSubmitBtn" class="btn-submit-styled" style="padding: 9px 20px; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 8px;">
                    <span id="authConfirmBtnText">Confirmar y Emitir</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function limpiarFormulario(animate = false) {
        const form = document.getElementById('dispatchForm');
        if (!form) return;

        // Reset nativo del formulario HTML
        form.reset();

        // Reset selects nativos y dispara 'change' para actualizar los dropdowns personalizados
        const nativeSelects = form.querySelectorAll('select');
        nativeSelects.forEach(sel => {
            sel.selectedIndex = 0;
            sel.dispatchEvent(new Event('change', { bubbles: true }));
        });

        // Limpiar inputs visibles
        const inputs = form.querySelectorAll('input:not([type="hidden"])');
        inputs.forEach(inp => {
            inp.value = '';
            inp.style.borderColor = '';
            inp.style.boxShadow = '';
        });

        const textarea = form.querySelectorAll('textarea');
        textarea.forEach(t => { t.value = ''; });

        // Reset buscador y elementos dentro de custom selects
        form.querySelectorAll('.custom-select-search-input').forEach(si => { si.value = ''; });
        form.querySelectorAll('.custom-select-item').forEach(item => { item.style.display = 'flex'; });
        form.querySelectorAll('.v-group-wrap').forEach(gw => { gw.style.display = 'block'; });
        form.querySelectorAll('.v-hidden').forEach(el => el.classList.remove('v-hidden'));
        form.querySelectorAll('.v-fuel-filter-badge').forEach(b => b.remove());
        form.querySelectorAll('.custom-select-create-box').forEach(cb => cb.remove());
        form.querySelectorAll('.custom-select-no-results').forEach(nr => nr.remove());
        form.querySelectorAll('.custom-select-container.open').forEach(c => c.classList.remove('open'));

        // Reset bordes de custom selects
        form.querySelectorAll('.custom-select-trigger').forEach(trigger => {
            trigger.style.borderColor = '';
            trigger.style.boxShadow = '';
        });

        // Ocultar alertas
        const alertBox = document.getElementById('formValidationAlert');
        if (alertBox) {
            alertBox.style.display = 'none';
            alertBox.innerHTML = '';
        }

        const stockWarning = document.getElementById('stockWarning');
        if (stockWarning) {
            stockWarning.style.display = 'none';
            stockWarning.innerHTML = '';
        }

        const lotInfo = document.getElementById('lotAssignedInfo');
        if (lotInfo) {
            lotInfo.style.display = 'none';
            lotInfo.textContent = '';
        }

        // Re-habilitar botón submit
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }

        // Animación visual de confirmación sólo si fue clic manual del usuario
        if (animate) {
            const btn = document.querySelector('.btn-clear-styled');
            if (btn) {
                btn.style.background = 'rgba(249,115,22,0.18)';
                btn.style.transform = 'scale(0.96)';
                setTimeout(() => { btn.style.background = ''; btn.style.transform = ''; }, 300);
            }
        }
    }

    function formatearPlaca(input) {
        if (!input) return;
        let val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (val.length > 3) {
            val = val.substring(0, 3) + '-' + val.substring(3, 6);
        }
        input.value = val;
    }

    function limitarGalones(input) {
        if (!input) return;

        // 1. Limpiar todo lo que no sea dígito ni punto decimal
        let val = input.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');

        // 2. Separa enteros y decimales para restringir límites
        let parts = val.split('.');
        
        if (parseFloat(parts[0]) > 15000) {
            parts[0] = '15000';
            parts[1] = undefined; // reset decimals if going over limit by typing
        }

        if (parts[1] !== undefined) {
            parts[1] = parts[1].slice(0, 2); // Máximo 2 decimales
            
            // Check full value after decimal truncation
            if (parseFloat(parts[0] + '.' + parts[1]) > 15000) {
                parts[0] = '15000';
                parts[1] = '00';
            }
            input.value = parts[0] + '.' + parts[1];
        } else {
            input.value = parts[0];
        }
    }

    function formatearSalidaGalones(input) {
        if (!input) return;
        let val = input.value.replace(/,/g, '');
        if (val !== '' && !isNaN(val)) {
            // Aplica las comas de miles automáticamente al salir del campo (Ej: 30000 -> 30,000)
            input.value = parseFloat(val).toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
        }
    }

    function limpiarFormatoGalones(input) {
        if (!input) return;
        // Remueve comas al enfocar el input para poder editar fluidamente
        input.value = input.value.replace(/,/g, '');
    }

    function verificarCamposIncompletos() {
        const fuelType = document.getElementById('fuel_type_select')?.value.trim();
        const vehicleId = document.getElementById('vehicle_id')?.value.trim();
        const driverName = document.getElementById('driver_name')?.value.trim();
        const gallonsVal = document.getElementById('gallons')?.value.trim();
        const destination = document.getElementById('destination')?.value.trim();

        const alertBox = document.getElementById('formValidationAlert');
        if (!alertBox) return [];

        const hasAnyField = fuelType || vehicleId || driverName || gallonsVal || destination;
        if (!hasAnyField) {
            alertBox.style.display = 'none';
            return [];
        }

        const isGnvDispatch = (fuelType || '').toLowerCase().includes('gnv') || (fuelType || '').toLowerCase().includes('gas natural');
        const qtyLabelDispatch = isGnvDispatch ? 'Cantidad en Metros Cúbicos (m³)' : 'Cantidad de Galones';
        const faltantes = [];
        if (!fuelType) faltantes.push('Tipo de Combustible');
        if (!vehicleId) faltantes.push('Vehículo / Unidad');
        if (!driverName) faltantes.push('Conductor / Responsable');
        if (!gallonsVal || parseFloat(gallonsVal.replace(/,/g, '')) <= 0) faltantes.push(qtyLabelDispatch);
        if (!destination) faltantes.push('Área / Subgerencia Destino');

        if (faltantes.length > 0) {
            alertBox.style.display = 'block';
            alertBox.innerHTML = `<span style="color:#f97316;">${uiIcon('alert')}</span> <strong style="color:#f97316;">Atención: Tiene campos obligatorios pendientes por completar:</strong><div style="margin-top:6px; font-weight:600; color:#f97316;">• ${faltantes.join('<br>• ')}</div>`;
        } else {
            alertBox.style.display = 'none';
        }

        return faltantes;
    }

    function validarFormularioDespacho(e) {
        let g = document.getElementById('gallons');
        if (g) g.value = g.value.replace(/,/g, '');

        const vehicleId = document.getElementById('vehicle_id')?.value.trim();
        const driverName = document.getElementById('driver_name')?.value.trim();
        const gallonsVal = document.getElementById('gallons')?.value.trim();
        const destination = document.getElementById('destination')?.value.trim();

        const fuelType = document.getElementById('fuel_type_select')?.value.trim();

        const faltantes = [];
        let firstInvalidEl = null;

        // Reset error borders
        ['fuel_type_select', 'vehicle_id', 'driver_name', 'destination'].forEach(id => {
            const select = document.getElementById(id);
            if (select) {
                const container = select.closest('.custom-select-container');
                const trigger = container ? container.querySelector('.custom-select-trigger') : select;
                if (trigger) {
                    trigger.style.borderColor = '';
                    trigger.style.boxShadow = '';
                }
            }
        });

        if (g) {
            g.style.borderColor = '';
            g.style.boxShadow = '';
        }

        if (!fuelType) {
            faltantes.push('Tipo de Combustible');
            const iSelect = document.getElementById('fuel_type_select');
            const trigger = iSelect?.closest('.custom-select-container')?.querySelector('.custom-select-trigger');
            if (trigger) {
                trigger.style.borderColor = '#ef4444';
                trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                if (!firstInvalidEl) firstInvalidEl = trigger;
            }
            if (!firstInvalidEl && g) firstInvalidEl = g;
        }

        if (!vehicleId) {
            faltantes.push('Vehículo / Unidad');
            const vSelect = document.getElementById('vehicle_id');
            const trigger = vSelect?.closest('.custom-select-container')?.querySelector('.custom-select-trigger');
            if (trigger) {
                trigger.style.borderColor = '#ef4444';
                trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                if (!firstInvalidEl) firstInvalidEl = trigger;
            }
        }

        if (!driverName) {
            faltantes.push('Conductor / Responsable');
            const dSelect = document.getElementById('driver_name');
            const trigger = dSelect?.closest('.custom-select-container')?.querySelector('.custom-select-trigger');
            if (trigger) {
                trigger.style.borderColor = '#ef4444';
                trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                if (!firstInvalidEl) firstInvalidEl = trigger;
            }
        }

        if (!gallonsVal || parseFloat(gallonsVal) <= 0) {
            const fTypeForVal = document.getElementById('fuel_type_select')?.value || '';
            const isGnvV = fTypeForVal.toLowerCase().includes('gnv') || fTypeForVal.toLowerCase().includes('gas natural');
            faltantes.push(isGnvV ? 'Cantidad en Metros Cúbicos (m³)' : 'Cantidad de Galones');
            if (g) {
                g.style.borderColor = '#ef4444';
                g.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                if (!firstInvalidEl) firstInvalidEl = g;
            }
        }

        if (!destination) {
            faltantes.push('Área / Subgerencia Destino');
            const destSelect = document.getElementById('destination');
            const trigger = destSelect?.closest('.custom-select-container')?.querySelector('.custom-select-trigger');
            if (trigger) {
                trigger.style.borderColor = '#ef4444';
                trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                if (!firstInvalidEl) firstInvalidEl = trigger;
            }
        }

        const alertBox = document.getElementById('formValidationAlert');

        if (faltantes.length > 0) {
            if (e) e.preventDefault();
            if (alertBox) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = `<span style="color:#f97316;">${uiIcon('alert')}</span> <strong style="color:#f97316;">Atención: Tiene campos obligatorios pendientes por completar:</strong><div style="margin-top:6px; font-weight:600; color:#f97316;">• ${faltantes.join('<br>• ')}</div>`;
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            if (firstInvalidEl) firstInvalidEl.focus();
            return false;
        }

        if (alertBox) alertBox.style.display = 'none';

        // Si ya se validó la identidad del usuario y se ingresó la contraseña, se permite el envío
        const authPassword = document.getElementById('auth_password_input')?.value;
        if (authPassword && authPassword.trim() !== '') {
            sessionStorage.setItem('despacho_completado', '1');
            setTimeout(() => {
                limpiarFormulario(false);
            }, 100);
            return true;
        }

        // Si los campos son válidos pero falta validar credenciales, abrimos el modal de confirmación
        if (e) e.preventDefault();
        abrirModalAuth();
        return false;
    }

    function abrirModalAuth() {
        const modal = document.getElementById('authValidateModal');
        const pwdInput = document.getElementById('authModalPassword');
        const errorBox = document.getElementById('authModalError');
        const submitBtn = document.getElementById('authConfirmSubmitBtn');
        const btnText = document.getElementById('authConfirmBtnText');

        if (!modal) return;
        if (pwdInput) {
            pwdInput.value = '';
            pwdInput.type = 'password';
            pwdInput.setAttribute('readonly', 'readonly');
        }
        if (errorBox) errorBox.style.display = 'none';
        if (submitBtn) submitBtn.disabled = false;
        if (btnText) btnText.textContent = 'Confirmar y Emitir';

        const eyeOpen = document.getElementById('eyeIconOpen');
        const eyeClosed = document.getElementById('eyeIconClosed');
        if (eyeOpen) eyeOpen.style.display = 'block';
        if (eyeClosed) eyeClosed.style.display = 'none';

        modal.classList.add('active');
        setTimeout(() => {
            if (pwdInput) {
                pwdInput.removeAttribute('readonly');
                pwdInput.focus();
            }
        }, 150);
    }

    function cerrarModalAuth(e) {
        if (e && e.target && e.target.id !== 'authValidateModal') {
            return;
        }
        const modal = document.getElementById('authValidateModal');
        if (modal) modal.classList.remove('active');
    }

    function toggleVisibilidadPasswordAuth() {
        const pwd = document.getElementById('authModalPassword');
        const eyeOpen = document.getElementById('eyeIconOpen');
        const eyeClosed = document.getElementById('eyeIconClosed');
        if (!pwd) return;

        if (pwd.type === 'password') {
            pwd.type = 'text';
            if (eyeOpen) eyeOpen.style.display = 'none';
            if (eyeClosed) eyeClosed.style.display = 'block';
        } else {
            pwd.type = 'password';
            if (eyeOpen) eyeOpen.style.display = 'block';
            if (eyeClosed) eyeClosed.style.display = 'none';
        }
    }

    async function confirmarIdentidadYDespachar(e) {
        if (e) e.preventDefault();
        const pwdInput = document.getElementById('authModalPassword');
        const errorBox = document.getElementById('authModalError');
        const errorText = document.getElementById('authModalErrorText');
        const submitBtn = document.getElementById('authConfirmSubmitBtn');
        const btnText = document.getElementById('authConfirmBtnText');
        const form = document.getElementById('dispatchForm');
        const authHidden = document.getElementById('auth_password_input');

        const password = pwdInput ? pwdInput.value.trim() : '';
        if (!password) {
            if (errorBox && errorText) {
                errorBox.style.display = 'flex';
                errorText.textContent = 'Por favor ingrese su contraseña.';
            }
            if (pwdInput) pwdInput.focus();
            return;
        }

        // Estado de carga
        if (submitBtn) submitBtn.disabled = true;
        if (btnText) btnText.textContent = 'Verificando...';
        if (errorBox) errorBox.style.display = 'none';

        try {
            const csrfToken = document.querySelector('input[name="csrf_token"]')?.value || '';
            const formData = new URLSearchParams();
            formData.append('password', password);
            formData.append('csrf_token', csrfToken);

            const res = await fetch('<?= BASE_URL ?>api/validar-credenciales', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData.toString()
            });

            let data = null;
            try {
                data = await res.json();
            } catch (jsonErr) {
                data = null;
            }

            if (res.ok && data && data.success) {
                if (btnText) btnText.textContent = 'Identidad Confirmada...';
                if (authHidden) authHidden.value = password;
                
                // Asegurar que galones esté formateado limpio (sin comas) para el backend
                const g = document.getElementById('gallons');
                if (g) g.value = g.value.replace(/,/g, '');

                // Deshabilitar botón principal para evitar dobles envíos
                const mainBtn = document.getElementById('submitBtn');
                if (mainBtn) mainBtn.disabled = true;

                // Cerrar modal y enviar formulario
                const modal = document.getElementById('authValidateModal');
                if (modal) modal.classList.remove('active');

                sessionStorage.setItem('despacho_completado', '1');
                if (form) form.submit();
            } else {
                let msg = 'Contraseña incorrecta. Verifique sus credenciales.';
                if (data && data.message) {
                    msg = data.message;
                } else if (res.status === 401) {
                    msg = 'Su sesión ha expirado. Por favor inicie sesión nuevamente.';
                } else if (res.status === 419) {
                    msg = 'Token de seguridad expirado. Por favor recargue la página.';
                }

                if (errorBox && errorText) {
                    errorBox.style.display = 'flex';
                    errorText.textContent = msg;
                }
                if (submitBtn) submitBtn.disabled = false;
                if (btnText) btnText.textContent = 'Confirmar y Emitir';
                if (pwdInput) {
                    pwdInput.value = '';
                    pwdInput.focus();
                }
            }
        } catch (err) {
            console.error('Error al validar credenciales:', err);
            if (errorBox && errorText) {
                errorBox.style.display = 'flex';
                errorText.textContent = 'Error de conexión con el servidor. Intente nuevamente.';
            }
            if (submitBtn) submitBtn.disabled = false;
            if (btnText) btnText.textContent = 'Confirmar y Emitir';
        }
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('authValidateModal');
            if (modal && modal.classList.contains('active')) {
                cerrarModalAuth();
            }
        }
    });

    document.addEventListener("DOMContentLoaded", () => {
        const fuelTypeSelect = document.getElementById("fuel_type_select");
        const gallonsInput = document.getElementById("gallons");
        const warningBox = document.getElementById("stockWarning");
        const submitBtn = document.getElementById("submitBtn");
        const lotAssignedInfo = document.getElementById("lotAssignedInfo");

        // Lee los lotes (id, receipt_number, supplier, stock) del tipo de
        // combustible actualmente seleccionado. Vienen ordenados del más
        // antiguo al más nuevo (ASC por id) desde el controlador.
        function getSelectedLots() {
            const opt = fuelTypeSelect.options[fuelTypeSelect.selectedIndex];
            if (!opt || !opt.getAttribute('data-lots')) return [];
            try {
                return JSON.parse(opt.getAttribute('data-lots'));
            } catch (e) {
                return [];
            }
        }

        // Ya no elige un único lote ni bloquea si ninguno alcanza solo:
        // el servidor reparte automáticamente entre los lotes más antiguos
        // que hagan falta. Aquí solo se muestra, a modo informativo, cuál
        // sería el primer lote usado (y si haría falta más de uno).
        function previewLotAssignment() {
            const lots = getSelectedLots();
            const requested = parseFloat((gallonsInput.value || "0").replace(/,/g, ''));

            if (lotAssignedInfo) lotAssignedInfo.style.display = 'none';
            if (!lots.length || requested <= 0) return;

            let remaining = requested;
            const used = [];
            for (const lot of lots) {
                if (remaining <= 0) break;
                if (lot.stock <= 0) continue;
                used.push(lot);
                remaining -= lot.stock;
            }

            if (used.length && lotAssignedInfo) {
                lotAssignedInfo.style.display = 'block';
                if (used.length === 1) {
                    lotAssignedInfo.textContent = `Se descontará del lote ${used[0].receipt_number} (${used[0].supplier}).`;
                } else {
                    const nombres = used.map(l => l.receipt_number).join(', ');
                    lotAssignedInfo.textContent = `Este despacho se repartirá entre ${used.length} lotes (empezando por el más antiguo): ${nombres}.`;
                }
            }
        }

        function validateStock() {
            if (!fuelTypeSelect || !gallonsInput) return;
            const selectedOption = fuelTypeSelect.options[fuelTypeSelect.selectedIndex];
            if (!selectedOption) return;

            const totalStock = parseFloat(selectedOption.getAttribute("data-stock") || "0");
            const rawVal = gallonsInput.value.replace(/,/g, '');
            const requested = parseFloat(rawVal || "0");
            previewLotAssignment();

            const fuelVal = (fuelTypeSelect.value || '').toLowerCase();
            const isGnv = fuelVal.includes('gnv') || fuelVal.includes('gas natural');
            const unitLabel = isGnv ? 'm³' : 'Gal';
            const gLabel = document.getElementById('gallonsLabel');
            if (gLabel) {
                gLabel.textContent = isGnv ? 'Cantidad en Metros Cúbicos — m³ (Máx. 15,000.00)' : 'Cantidad de Galones (Máx. 15,000.00)';
            }
            if (gallonsInput && !gallonsInput.value) {
                gallonsInput.placeholder = isGnv ? 'Ej: 50.00 m³' : 'Ej: 15,000';
            }

            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const fmtReq = requested.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const fmtTotal = totalStock.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            let mensaje = null;
            if (requested > 0 && requested > totalStock) {
                mensaje = `La cantidad solicitada <strong data-role="waccent">${fmtReq} ${unitLabel}</strong> supera el stock total disponible de este combustible <strong data-role="waccent">${fmtTotal} ${unitLabel}</strong>.`;
            }

            if (mensaje) {
                const bg = isDark ? 'rgba(239,68,68,0.14)' : '#fef2f2';
                const border = isDark ? '1.5px solid rgba(239,68,68,0.5)' : '1.5px solid #fca5a5';
                const titleCol = isDark ? '#fca5a5' : '#0f172a';
                const textCol = isDark ? '#e2e8f0' : '#1e293b';
                const accentCol = isDark ? '#f87171' : '#dc2626';

                warningBox.style.display = 'flex';
                warningBox.style.background = bg;
                warningBox.style.border = border;
                warningBox.style.borderLeft = '5px solid #ef4444';
                warningBox.innerHTML = `
                    <div style="flex-shrink:0;width:36px;height:36px;background:#ef4444;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2.5" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div data-role="wtitle" style="margin:0 0 2px;font-size:0.82rem;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;">Bloqueo de Seguridad</div>
                        <div data-role="wbody" style="margin:0;font-size:0.9rem;font-weight:600;line-height:1.5;">${mensaje}</div>
                    </div>
                `;

                warningBox.querySelectorAll('[data-role="wtitle"]').forEach(el => el.style.setProperty('color', titleCol, 'important'));
                warningBox.querySelectorAll('[data-role="wbody"]').forEach(el => el.style.setProperty('color', textCol, 'important'));
                warningBox.querySelectorAll('[data-role="waccent"]').forEach(el => el.style.setProperty('color', accentCol, 'important'));

                gallonsInput.style.borderColor = '#ef4444';

                submitBtn.disabled = true;
                submitBtn.style.opacity = "0.5";
                submitBtn.style.cursor = "not-allowed";
            } else {
                warningBox.style.display = "none";
                gallonsInput.style.borderColor = "";
                gallonsInput.style.backgroundColor = "";
                submitBtn.disabled = false;
                submitBtn.style.opacity = "1";
                submitBtn.style.cursor = "pointer";
            }
        }

        window.addEventListener('themeChanged', () => {
            validateStock();
        });

        fuelTypeSelect.addEventListener("change", () => {
            validateStock();
        });
        gallonsInput.addEventListener("input", validateStock);
        gallonsInput.addEventListener("keyup", validateStock);

        // Capitaliza la primera letra de cada palabra (soporta tildes y ñ)
        function toTitleCase(str) {
            return str.replace(/\S+/g, w => w.charAt(0).toUpperCase() + w.slice(1));
        }

        // Normaliza texto de combustible (minúsculas, sin tildes, sin espacios extras)
        function normalizeFuelText(str) {
            return (str || '')
                .toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .trim();
        }

        // Obtiene la categoría principal de un combustible (gnv, glp, diesel, gasolina)
        function getFuelCategory(str) {
            const n = normalizeFuelText(str);
            if (n.includes('gnv') || n.includes('gas natural')) return 'gnv';
            if (n.includes('glp')) return 'glp';
            if (n.includes('diesel')) return 'diesel';
            if (n.includes('gasolina') || n.includes('gasohol')) return 'gasolina';
            return n;
        }

        // Comprueba si el combustible de un vehículo (admite combinados como "Diésel / Gasolina")
        // es compatible con un tipo de combustible específico
        function isFuelCompatible(vehicleFuelType, targetFuelType) {
            if (!targetFuelType) return true;
            if (!vehicleFuelType) return false;

            const targetNorm = normalizeFuelText(targetFuelType);
            const targetCat = getFuelCategory(targetFuelType);

            // Desglosa si el vehículo admite varios combustibles separados por '/'
            const vParts = vehicleFuelType.split('/').map(p => p.trim()).filter(Boolean);

            for (const part of vParts) {
                const partNorm = normalizeFuelText(part);
                const partCat = getFuelCategory(part);

                // 1. Coincidencia por categoría canónica (ej. 'gnv' === 'gnv', 'diesel' === 'diesel')
                if (targetCat && partCat && targetCat === partCat) {
                    return true;
                }

                // 2. Coincidencia textual directa o contenida (ej. "Gasohol Regular" y "Gasolina")
                if (partNorm && targetNorm && (partNorm.includes(targetNorm) || targetNorm.includes(partNorm))) {
                    return true;
                }
            }

            return false;
        }

        // Determina la paleta de colores y metadata por tipo de combustible
        function getFuelStyle(name) {
            const n = (name || '').toLowerCase();
            if (n.includes('gnv') || n.includes('gas natural')) {
                return {
                    color: '#1d4ed8',
                    bg: 'rgba(29, 78, 216, 0.08)',
                    border: 'rgba(29, 78, 216, 0.25)',
                    tagBg: '#eff6ff',
                    category: 'GNV',
                    unit: 'm³'
                };
            } else if (n.includes('glp')) {
                return {
                    color: '#b45309',
                    bg: 'rgba(217, 119, 6, 0.08)',
                    border: 'rgba(217, 119, 6, 0.25)',
                    tagBg: '#fffbeb',
                    category: 'GLP',
                    unit: 'Gal'
                };
            } else if (n.includes('gasolina') || n.includes('gasohol')) {
                return {
                    color: '#c2410c',
                    bg: 'rgba(234, 88, 12, 0.08)',
                    border: 'rgba(234, 88, 12, 0.25)',
                    tagBg: '#fff7ed',
                    category: 'Gasolina',
                    unit: 'Gal'
                };
            } else if (n.includes('diesel')) {
                return {
                    color: '#047857',
                    bg: 'rgba(4, 120, 87, 0.08)',
                    border: 'rgba(4, 120, 87, 0.25)',
                    tagBg: '#ecfdf5',
                    category: 'Diesel',
                    unit: 'Gal'
                };
            } else {
                return {
                    color: '#475569',
                    bg: 'rgba(100, 116, 139, 0.08)',
                    border: 'rgba(100, 116, 139, 0.25)',
                    tagBg: '#f8fafc',
                    category: 'Combustible',
                    unit: 'Gal'
                };
            }
        }

        // Inicializador Visual de Desplegables Personalizados con Búsqueda y Formato Avanzado
        function initCustomDispatchSelect(selector, enableSearch = false, placeholder = "Buscar...", allowCreate = false, maxLength = null, onlyLetters = false, alphanumericOnly = false) {
            const selectEl = typeof selector === 'string' ? (document.getElementById(selector) || document.querySelector(`select[name="${selector}"]`)) : selector;
            if (!selectEl) return;

            const container = document.createElement('div');
            container.className = 'custom-select-container';
            selectEl.parentNode.insertBefore(container, selectEl);
            container.appendChild(selectEl);
            selectEl.style.display = 'none';

            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger';
            trigger.tabIndex = 0;

            const menu = document.createElement('div');
            menu.className = 'custom-select-menu';

            container.appendChild(trigger);
            container.appendChild(menu);

            if (selectEl.disabled) {
                trigger.classList.add('is-disabled');
                trigger.style.opacity = '0.6';
                trigger.style.cursor = 'not-allowed';
                trigger.style.pointerEvents = 'none';
            }

            let searchInput = null;

            function updateUI() {
                const selectedOpt = selectEl.options[selectEl.selectedIndex];
                if (!selectedOpt) return;

                let triggerHTML = '';
                if (selectedOpt.hasAttribute('data-area') && selectedOpt.getAttribute('data-area') !== '') {
                    const area = selectedOpt.getAttribute('data-area');
                    const plate = selectedOpt.getAttribute('data-plate');
                    const unit = selectedOpt.getAttribute('data-unit');
                    const type = selectedOpt.getAttribute('data-type');
                    const fType = selectedOpt.getAttribute('data-fuel-type') || '';
                    let fBadgeTrigger = '';
                    if (fType) {
                        const parts = fType.split('/').map(p => p.trim()).filter(Boolean);
                        fBadgeTrigger = parts.map(p => {
                            const st = getFuelStyle(p);
                            return `<span style="font-size:0.71rem; font-weight:700; background:${st.tagBg}; color:${st.color}; border:1px solid ${st.border}; padding:1px 6px; border-radius:4px; white-space:nowrap;">${p}</span>`;
                        }).join(' ');
                    }
                    triggerHTML = `
                        <div style="display:flex; flex-direction:column; gap:3px; flex:1; min-width:0; overflow:hidden;">
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                <span style="font-size:0.73rem; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">${area}</span>
                                <span style="font-size:0.70rem; font-weight:600; background:rgba(100,116,139,0.12); color:#475569; padding:1px 6px; border-radius:4px; white-space:nowrap; flex-shrink:0;">${type}</span>
                            </div>
                            <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                <span style="font-weight:700; font-size:0.90rem; color:var(--text-color, #0f172a); letter-spacing:0.3px; white-space:nowrap;">${plate}</span>
                                ${unit ? `<span style="font-weight:600; font-size:0.82rem; color:#64748b; white-space:nowrap;">— ${unit}</span>` : ''}
                                ${fBadgeTrigger ? `<div style="display:inline-flex; align-items:center; gap:4px; flex-wrap:wrap;">${fBadgeTrigger}</div>` : ''}
                            </div>
                        </div>
                    `;
                } else if (selectedOpt.hasAttribute('data-receipt') && selectedOpt.getAttribute('data-receipt') !== '') {
                    const fType = selectedOpt.getAttribute('data-fuel-type');
                    const receipt = selectedOpt.getAttribute('data-receipt');
                    const stockFormatted = selectedOpt.getAttribute('data-stock-formatted');
                    const supplier = selectedOpt.getAttribute('data-supplier');
                    const st = getFuelStyle(fType);
                    triggerHTML = `
                        <div style="display:flex; flex-direction:column; justify-content:center; gap:2px; flex:1; min-width:0; overflow:hidden;">
                            <span style="font-size:0.72rem; font-weight:700; line-height:1.15; color:${st.color}; text-transform:uppercase; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">${fType} — ${receipt}</span>
                            <span style="font-weight:600; font-size:0.84rem; line-height:1.2; color:var(--text-color, #0f172a); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">Stock: ${stockFormatted} ${st.unit} (${supplier})</span>
                        </div>
                    `;
                } else if (selectedOpt.hasAttribute('data-stock-formatted') && selectedOpt.getAttribute('data-stock-formatted') !== '') {
                    const fName = selectedOpt.value;
                    const fStock = selectedOpt.getAttribute('data-stock-formatted');
                    const st = getFuelStyle(fName);
                    triggerHTML = `
                        <div style="display:flex; flex-direction:column; justify-content:center; gap:2px; flex:1; min-width:0; overflow:hidden;">
                            <span style="font-size:0.72rem; font-weight:700; line-height:1.15; color:${st.color}; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">${fName}</span>
                            <span style="font-weight:600; font-size:0.84rem; line-height:1.2; color:var(--text-color, #0f172a); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">Stock disponible: ${fStock} ${st.unit}</span>
                        </div>
                    `;
                } else {
                    const isPlaceholder = selectedOpt.value === '';
                    triggerHTML = `
                        <div class="custom-select-selected-content" style="flex:1; min-width:0; overflow:hidden; display:flex; align-items:center;">
                            <span class="custom-select-label-text" style="font-weight:${isPlaceholder ? '500' : '600'}; font-size:0.86rem; color:${isPlaceholder ? 'var(--text-muted, #94a3b8)' : 'var(--text-color, inherit)'}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">${selectedOpt.text}</span>
                        </div>
                    `;
                }

                trigger.innerHTML = `
                    ${triggerHTML}
                    <span class="custom-select-chevron" style="flex-shrink:0; margin-left:8px; display:inline-flex; align-items:center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </span>
                `;

                menu.innerHTML = '';

                if (enableSearch) {
                    const searchBox = document.createElement('div');
                    searchBox.className = 'custom-select-search-box';

                    searchInput = document.createElement('input');
                    searchInput.type = 'text';
                    searchInput.className = 'custom-select-search-input';
                    searchInput.placeholder = placeholder;

                    searchInput.addEventListener('click', (e) => e.stopPropagation());
                    searchInput.addEventListener('input', (e) => {
                        if (onlyLetters) {
                            e.target.value = e.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚàèìòùüñÑ\s]/g, '');
                        }
                        if (alphanumericOnly) {
                            e.target.value = e.target.value.replace(/[^a-zA-Z0-9\-\s]/g, '');
                        }
                        filterItems(e.target.value);
                    });
                    if (maxLength) searchInput.maxLength = maxLength;
                    searchInput.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            e.stopPropagation();

                            const term = searchInput.value.trim();
                            if (!term) return;

                            // Si el botón "Usar nuevo conductor" está visible, activarlo directamente
                            // (ya aplica toTitleCase y cierra el dropdown correctamente)
                            const createBox = menu.querySelector('.custom-select-create-box');
                            if (createBox && createBox.style.display !== 'none' && createBox.offsetParent !== null) {
                                createBox.click();
                                return;
                            }

                            const listContainer = menu.querySelector('.custom-select-list-container');
                            const visibleItems = Array.from(listContainer.querySelectorAll('.custom-select-item')).filter(i => i.style.display !== 'none');

                            if (visibleItems.length > 0) {
                                visibleItems[0].click();
                            } else if (allowCreate) {
                                const titleTerm = typeof toTitleCase === 'function' ? toTitleCase(term) : term;
                                let exists = Array.from(selectEl.options).find(o => o.value.toLowerCase() === titleTerm.toLowerCase());
                                if (!exists) {
                                    const newOpt = new Option(titleTerm, titleTerm, true, true);
                                    selectEl.add(newOpt);
                                } else {
                                    selectEl.value = exists.value;
                                }
                                selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                                container.classList.remove('open');
                            }
                        }
                    });

                    searchBox.appendChild(searchInput);
                    menu.appendChild(searchBox);
                }

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

                    if (opt.hasAttribute('data-area') && opt.getAttribute('data-area') !== '') {
                        const area = opt.getAttribute('data-area');
                        const plate = opt.getAttribute('data-plate');
                        const unit = opt.getAttribute('data-unit');
                        const type = opt.getAttribute('data-type');
                        const fType = opt.getAttribute('data-fuel-type') || '';
                        let fBadge = '';
                        if (fType) {
                            const parts = fType.split('/').map(p => p.trim()).filter(Boolean);
                            fBadge = parts.map(p => {
                                const st = getFuelStyle(p);
                                return `<span style="font-size:0.71rem; font-weight:700; padding:1px 7px; border-radius:4px; background:${st.tagBg}; color:${st.color}; border:1px solid ${st.border}; white-space:nowrap; display:inline-flex; align-items:center;">${p}</span>`;
                            }).join(' ');
                        }
                        item.innerHTML = `
                            <div class="item-left" style="flex:1; min-width:0; overflow:hidden;">
                                <div style="display:flex; flex-direction:column; gap:4px; width:100%;">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:6px;">
                                        <span style="font-size:0.72rem; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                            ${area}
                                        </span>
                                        <span style="font-size:0.70rem; font-weight:600; background:rgba(100,116,139,0.12); color:#475569; padding:1px 6px; border-radius:4px; white-space:nowrap; flex-shrink:0;">
                                            ${type}
                                        </span>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                        <span style="font-size:0.90rem; font-weight:700; color:var(--text-color, #0f172a); letter-spacing:0.3px;">
                                            ${plate}
                                        </span>
                                        ${unit ? `<span style="font-size:0.82rem; font-weight:600; color:#64748b; white-space:nowrap;">— ${unit}</span>` : ''}
                                        ${fBadge ? `<div style="display:inline-flex; align-items:center; gap:4px; flex-wrap:wrap;">${fBadge}</div>` : ''}
                                    </div>
                                </div>
                            </div>
                            ${isSelected ? `<span class="item-check" style="flex-shrink:0; margin-left:8px;">${uiIcon('check')}</span>` : ''}
                        `;
                    } else if (opt.hasAttribute('data-receipt') && opt.getAttribute('data-receipt') !== '') {
                        const fType = opt.getAttribute('data-fuel-type');
                        const receipt = opt.getAttribute('data-receipt');
                        const stockFormatted = opt.getAttribute('data-stock-formatted');
                        const supplier = opt.getAttribute('data-supplier');
                        const st = getFuelStyle(fType);
                        item.innerHTML = `
                            <div class="item-left" style="flex:1; min-width:0; overflow:hidden;">
                                <div style="display:flex; flex-direction:column; gap:2px;">
                                    <div style="font-size:0.73rem; font-weight:700; color:${st.color}; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        ${fType} — ${receipt}
                                    </div>
                                    <div style="font-size:0.88rem; font-weight:600; color:var(--text-color, #0f172a); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        Stock: ${stockFormatted} ${st.unit} <span style="color:#64748b; font-weight:500;">(${supplier})</span>
                                    </div>
                                </div>
                            </div>
                            ${isSelected ? `<span class="item-check" style="flex-shrink:0; margin-left:8px;">${uiIcon('check')}</span>` : ''}
                        `;
                    } else if (opt.hasAttribute('data-stock-formatted') && opt.getAttribute('data-stock-formatted') !== '') {
                        const fName = opt.value;
                        const fStock = opt.getAttribute('data-stock-formatted');
                        const st = getFuelStyle(fName);
                        item.innerHTML = `
                            <div class="item-left" style="flex:1; min-width:0; overflow:hidden;">
                                <div style="display:flex; flex-direction:column; gap:2px;">
                                    <div style="font-size:0.88rem; font-weight:700; color:${st.color}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        ${fName}
                                    </div>
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <span style="font-size:0.8rem; font-weight:600; color:var(--text-color, #0f172a);">Stock disponible: ${fStock} ${st.unit}</span>
                                    </div>
                                </div>
                            </div>
                            ${isSelected ? `<span class="item-check" style="flex-shrink:0; margin-left:8px;">${uiIcon('check')}</span>` : ''}
                        `;
                    } else {
                        item.innerHTML = `
                            <div class="item-left" style="flex:1; min-width:0; overflow:hidden;">
                                <span class="item-title" style="font-size:0.88rem; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">${opt.text}</span>
                            </div>
                            ${isSelected ? `<span class="item-check" style="flex-shrink:0; margin-left:8px;">${uiIcon('check')}</span>` : ''}
                        `;
                    }

                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectEl.selectedIndex = idx;
                        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                        container.classList.remove('open');
                    });
                    listContainer.appendChild(item);
                });
            }

            function filterItems(term) {
                const cleanTerm = term.toLowerCase().trim();
                const listContainer = menu.querySelector('.custom-select-list-container');
                if (!listContainer) return;

                const items = listContainer.querySelectorAll('.custom-select-item');
                let matchCount = 0;
                let exactMatch = false;

                items.forEach(item => {
                    const text = item.getAttribute('data-text') || '';
                    if (text === cleanTerm) exactMatch = true;

                    if (cleanTerm === '' || text.includes(cleanTerm)) {
                        item.style.display = 'flex';
                        matchCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Si permite agregar manual y se escribió algo nuevo que no coincide exactamente
                let createBox = menu.querySelector('.custom-select-create-box');
                if (allowCreate && cleanTerm !== '' && !exactMatch) {
                    if (!createBox) {
                        createBox = document.createElement('div');
                        createBox.className = 'custom-select-create-box';
                        createBox.style.cssText = 'padding: 8px 12px; margin: 4px; border-radius: 8px; cursor: pointer; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; gap: 6px;';
                        menu.insertBefore(createBox, listContainer);
                    }
                    createBox.innerHTML = `<span>+</span> Usar nuevo conductor: "<strong>${toTitleCase(term.trim())}</strong>"`;
                    createBox.onclick = (e) => {
                        e.stopPropagation();
                        const val = toTitleCase(term.trim());
                        const newOpt = new Option(val, val, true, true);
                        selectEl.add(newOpt);
                        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                        container.classList.remove('open');
                    };
                } else if (createBox) {
                    createBox.remove();
                }

                let noResultsMsg = menu.querySelector('.custom-select-no-results');
                if (matchCount === 0 && (!allowCreate || cleanTerm === '')) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'custom-select-no-results';
                        noResultsMsg.textContent = 'No se encontraron coincidencias';
                        listContainer.appendChild(noResultsMsg);
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = container.classList.contains('open');

                document.querySelectorAll('.custom-select-container.open').forEach(c => {
                    if (c !== container) c.classList.remove('open');
                });

                if (!isOpen) {
                    container.classList.add('open');
                    if (enableSearch && searchInput) {
                        searchInput.value = '';
                        filterItems('');
                        setTimeout(() => searchInput.focus(), 50);
                    }
                } else {
                    container.classList.remove('open');
                }
            });

            document.addEventListener('click', (e) => {
                if (!container.contains(e.target)) {
                    container.classList.remove('open');
                }
            });

            selectEl.addEventListener('change', () => {
                updateUI();
                verificarCamposIncompletos();
                if (selectEl.value !== '') {
                    const triggerEl = container.querySelector('.custom-select-trigger');
                    if (triggerEl) {
                        triggerEl.style.borderColor = '';
                        triggerEl.style.boxShadow = '';
                    }
                }
            });
            updateUI();
        }

        // =========================================================
        // SELECTOR DE VEHÍCULO / UNIDAD CON AGRUPACIÓN POR ÁREA
        // =========================================================
        function initVehicleGroupedSelect(selector, placeholder = "Buscar por área, placa, unidad o tipo...") {
            const selectEl = typeof selector === 'string' ? (document.getElementById(selector) || document.querySelector(`select[name="${selector}"]`)) : selector;
            if (!selectEl) return;

            const container = document.createElement('div');
            container.className = 'custom-select-container vehicle-grouped-select-container';
            selectEl.parentNode.insertBefore(container, selectEl);
            container.appendChild(selectEl);
            selectEl.style.display = 'none';

            const trigger = document.createElement('div');
            trigger.className = 'custom-select-trigger v-grouped-trigger';
            trigger.tabIndex = 0;

            const menu = document.createElement('div');
            menu.className = 'v-grouped-menu';

            container.appendChild(trigger);
            container.appendChild(menu);

            if (selectEl.disabled) {
                trigger.classList.add('is-disabled');
                trigger.style.opacity = '0.6';
                trigger.style.cursor = 'not-allowed';
                trigger.style.pointerEvents = 'none';
            }

            // Barra de búsqueda
            const searchBox = document.createElement('div');
            searchBox.className = 'v-search-box';

            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'v-search-input custom-select-search-input';
            searchInput.placeholder = placeholder;
            searchInput.autocomplete = 'off';
            searchInput.maxLength = 50;
            searchBox.appendChild(searchInput);
            menu.appendChild(searchBox);

            // Contenedor scrolleable con lista de grupos
            const listContainer = document.createElement('div');
            listContainer.className = 'v-list-container custom-select-list-container';
            menu.appendChild(listContainer);

            // Pie de menú con conteo y sugerencias de teclado
            const footer = document.createElement('div');
            footer.className = 'v-select-footer';
            footer.innerHTML = `
                <span class="v-footer-count">0 vehículos</span>
                <span class="v-footer-hints">↑↓ mover · Enter elegir · Esc cerrar</span>
            `;
            menu.appendChild(footer);
            const footerCountEl = footer.querySelector('.v-footer-count');

            // Renderizar insignias de combustible según el tipo de forma limpia y sin duplicados
            function renderFuelBadges(fType) {
                if (!fType) return '';
                const rawParts = fType.split('/').map(p => p.trim()).filter(Boolean);
                const seen = new Set();
                const badges = [];

                rawParts.forEach(p => {
                    const lower = p.toLowerCase();
                    let label = p;
                    let badgeClass = 'v-badge-otro';

                    if (lower.includes('diesel') || lower.includes('diésel')) {
                        label = 'Diésel';
                        badgeClass = 'v-badge-diesel';
                    } else if (lower.includes('gasolina') || lower.includes('gasohol')) {
                        label = 'Gasolina';
                        badgeClass = 'v-badge-gasolina';
                    } else if (lower.includes('gnv') || lower.includes('gas natural')) {
                        label = 'GNV';
                        badgeClass = 'v-badge-gnv';
                    } else if (lower.includes('glp')) {
                        label = 'GLP';
                        badgeClass = 'v-badge-glp';
                    }

                    if (!seen.has(label)) {
                        seen.add(label);
                        badges.push(`<span class="v-badge ${badgeClass}">${label}</span>`);
                    }
                });

                return badges.join('');
            }

            // Actualizar la apariencia del botón disparador
            function updateTrigger() {
                const selectedOpt = selectEl.options[selectEl.selectedIndex];
                if (!selectedOpt || selectedOpt.value === '') {
                    trigger.innerHTML = `
                        <div class="custom-select-selected-content" style="flex:1; min-width:0; overflow:hidden; display:flex; align-items:center;">
                            <span class="custom-select-label-text" style="color:var(--text-muted, #94a3b8); font-size:0.86rem; font-weight:500;">Seleccione un vehículo...</span>
                        </div>
                        <span class="custom-select-chevron" style="display:inline-flex; align-items:center;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </span>
                    `;
                    return;
                }

                const plate = selectedOpt.getAttribute('data-plate') || selectedOpt.text;
                const unit = selectedOpt.getAttribute('data-unit') || '';
                const type = selectedOpt.getAttribute('data-type') || '';
                const fType = selectedOpt.getAttribute('data-fuel-type') || '';

                let subParts = [];
                if (type && type !== 'Sin tipo') subParts.push(type);
                if (unit) subParts.push(unit);
                const subText = subParts.join(' · ');

                const badgesHtml = renderFuelBadges(fType);

                trigger.innerHTML = `
                    <div class="v-trigger-layout">
                        <div class="v-trigger-left">
                            <span class="v-item-plate">${plate}</span>
                            ${subText ? `<span class="v-item-sub">${subText}</span>` : ''}
                        </div>
                        <div class="v-trigger-right">
                            ${badgesHtml ? `<div class="v-item-badges">${badgesHtml}</div>` : ''}
                            <span class="custom-select-chevron" style="display:inline-flex; align-items:center;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </span>
                        </div>
                    </div>
                `;
            }

            let allVehicleItems = [];
            let groupElements = [];
            let activeFocusedIndex = -1;

            // Construir los grupos y sus vehículos correspondientes
            function buildList() {
                listContainer.innerHTML = '';
                allVehicleItems = [];
                groupElements = [];

                const groups = new Map();
                for (let i = 1; i < selectEl.options.length; i++) {
                    const opt = selectEl.options[i];
                    const area = (opt.getAttribute('data-area') || 'Sin Área Asignada').trim();
                    if (!groups.has(area)) {
                        groups.set(area, []);
                    }
                    groups.get(area).push({
                        index: i,
                        id: opt.value,
                        area: area,
                        plate: opt.getAttribute('data-plate') || opt.text,
                        unit: opt.getAttribute('data-unit') || '',
                        type: opt.getAttribute('data-type') || '',
                        fuelType: opt.getAttribute('data-fuel-type') || '',
                        opt: opt
                    });
                }

                if (groups.size === 0) {
                    listContainer.innerHTML = `<div class="custom-select-no-results">No hay vehículos registrados</div>`;
                    if (footerCountEl) footerCountEl.textContent = '0 vehículos';
                    return;
                }

                let totalCount = 0;

                // Ordenar las áreas alfabéticamente (A - Z), dejando 'Sin Área Asignada' al final
                const sortedAreas = Array.from(groups.keys()).sort((a, b) => {
                    const isSinA = a.toLowerCase().includes('sin área') || a.toLowerCase().includes('sin area');
                    const isSinB = b.toLowerCase().includes('sin área') || b.toLowerCase().includes('sin area');
                    if (isSinA && !isSinB) return 1;
                    if (!isSinA && isSinB) return -1;
                    return a.localeCompare(b, 'es', { sensitivity: 'base' });
                });

                sortedAreas.forEach(areaName => {
                    const items = groups.get(areaName) || [];

                    // Ordenar los vehículos dentro del área por Placa y Unidad (orden natural: ej. ABC-2 antes de ABC-10)
                    items.sort((a, b) => {
                        const plateCompare = (a.plate || '').localeCompare(b.plate || '', 'es', { numeric: true, sensitivity: 'base' });
                        if (plateCompare !== 0) return plateCompare;
                        return (a.unit || '').localeCompare(b.unit || '', 'es', { numeric: true, sensitivity: 'base' });
                    });

                    const groupWrap = document.createElement('div');
                    groupWrap.className = 'v-group-wrap';

                    const groupHeader = document.createElement('div');
                    groupHeader.className = 'v-group-header';
                    groupHeader.innerHTML = `
                        <span class="v-group-title">${areaName}</span>
                        <span class="v-group-count">${items.length}</span>
                    `;
                    groupWrap.appendChild(groupHeader);

                    const itemsWrap = document.createElement('div');
                    itemsWrap.className = 'v-group-items';

                    items.forEach(v => {
                        totalCount++;
                        const isSelected = v.index === selectEl.selectedIndex;

                        const item = document.createElement('div');
                        item.className = `v-group-item custom-select-item ${isSelected ? 'selected' : ''}`;
                        item.setAttribute('data-index', v.index);
                        item.setAttribute('data-vehicle-id', v.id);
                        // Guardar el tipo de combustible del vehículo en la propiedad del elemento
                        // para que filterVehicles() pueda verificar compatibilidad sin re-leer el DOM
                        item.__fuelType = v.fuelType;

                        let subParts = [];
                        if (v.type && v.type !== 'Sin tipo') subParts.push(v.type);
                        if (v.unit) subParts.push(v.unit);
                        const subText = subParts.join(' · ');

                        const badgesHtml = renderFuelBadges(v.fuelType);
                        const searchMeta = `${v.area} ${v.plate} ${v.unit} ${v.type} ${v.fuelType}`.toLowerCase();
                        item.setAttribute('data-search', searchMeta);

                        item.innerHTML = `
                            <div class="v-item-top">
                                <div class="v-item-plate-wrap">
                                    <span class="v-item-plate">${v.plate}</span>
                                    <span class="v-item-check" style="${isSelected ? '' : 'display:none;'}">✓</span>
                                </div>
                                <div class="v-item-badges">
                                    ${badgesHtml}
                                </div>
                            </div>
                            ${subText ? `<div class="v-item-sub">${subText}</div>` : ''}
                        `;

                        item.addEventListener('click', (e) => {
                            e.stopPropagation();
                            selectEl.selectedIndex = v.index;
                            selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                            container.classList.remove('open');
                        });

                        item.addEventListener('mouseenter', () => {
                            setActiveItem(item);
                        });

                        itemsWrap.appendChild(item);
                        allVehicleItems.push({
                            el: item,
                            index: v.index,
                            meta: searchMeta,
                            group: groupWrap,
                            groupCountBadge: groupHeader.querySelector('.v-group-count')
                        });
                    });

                    groupWrap.appendChild(itemsWrap);
                    listContainer.appendChild(groupWrap);
                    groupElements.push({
                        wrap: groupWrap,
                        countBadge: groupHeader.querySelector('.v-group-count'),
                        totalInGroup: items.length
                    });
                });

                if (footerCountEl) {
                    footerCountEl.textContent = `${totalCount} vehículo${totalCount === 1 ? '' : 's'}`;
                }
            }

            function getVisibleItems() {
                return allVehicleItems.filter(v => !v.el.classList.contains('v-hidden'));
            }

            function setActiveItem(targetEl) {
                allVehicleItems.forEach(v => v.el.classList.remove('is-focused'));
                if (targetEl) {
                    targetEl.classList.add('is-focused');
                    const vis = getVisibleItems();
                    activeFocusedIndex = vis.findIndex(v => v.el === targetEl);
                } else {
                    activeFocusedIndex = -1;
                }
            }

            // Filtro activo de combustible (vacío = sin filtro)
            let activeFuelFilter = '';

            function fuelMatchesFilter(vehicleFuelType) {
                if (!activeFuelFilter) return true;
                return isFuelCompatible(vehicleFuelType, activeFuelFilter);
            }

            function filterVehicles(term) {
                const clean = term.toLowerCase().trim();
                let totalVisible = 0;

                groupElements.forEach(g => {
                    const groupItems = allVehicleItems.filter(v => v.group === g.wrap);
                    let groupCount = 0;

                    groupItems.forEach(v => {
                        const matchesTerm = !clean || v.meta.includes(clean);
                        const matchesFuel = fuelMatchesFilter(v.el.__fuelType || '');
                        if (matchesTerm && matchesFuel) {
                            v.el.classList.remove('v-hidden');
                            groupCount++;
                            totalVisible++;
                        } else {
                            v.el.classList.add('v-hidden');
                            v.el.classList.remove('is-focused');
                        }
                    });

                    if (groupCount > 0) {
                        g.wrap.classList.remove('v-hidden');
                        g.countBadge.textContent = groupCount;
                    } else {
                        g.wrap.classList.add('v-hidden');
                    }
                });

                if (footerCountEl) {
                    footerCountEl.textContent = `${totalVisible} vehículo${totalVisible === 1 ? '' : 's'}`;
                }

                let noRes = listContainer.querySelector('.custom-select-no-results');
                const noResMsg = activeFuelFilter
                    ? `Sin vehículos compatibles con "${activeFuelFilter}"`
                    : 'No se encontraron vehículos que coincidan';
                if (totalVisible === 0) {
                    if (!noRes) {
                        noRes = document.createElement('div');
                        noRes.className = 'custom-select-no-results';
                        listContainer.appendChild(noRes);
                    }
                    noRes.textContent = noResMsg;
                } else if (noRes) {
                    noRes.remove();
                }

                const vis = getVisibleItems();
                if (vis.length > 0) {
                    setActiveItem(vis[0].el);
                } else {
                    setActiveItem(null);
                }
            }

            // Aplica un filtro externo por tipo de combustible y refresca la lista
            function filterVehiclesByFuel(fuelType) {
                activeFuelFilter = (fuelType || '').trim();

                // Si el vehículo actualmente seleccionado no es compatible, deseleccionarlo
                const selOpt = selectEl.options[selectEl.selectedIndex];
                if (selOpt && selOpt.value !== '') {
                    const vFuel = selOpt.getAttribute('data-fuel-type') || '';
                    if (!fuelMatchesFilter(vFuel)) {
                        selectEl.selectedIndex = 0;
                        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }

                // Actualizar el texto de búsqueda en el selector agrupado
                if (activeFuelFilter) {
                    const searchBox = container.querySelector('.v-search-input');
                    if (searchBox) searchBox.value = '';
                }

                filterVehicles(container.querySelector('.v-search-input')?.value || '');

                // Badge informativo encima del trigger cuando hay filtro activo
                const existingBadge = container.parentNode.querySelector('.v-fuel-filter-badge');
                if (activeFuelFilter) {
                    if (!existingBadge) {
                        const badge = document.createElement('div');
                        badge.className = 'v-fuel-filter-badge';
                        badge.style.cssText = 'margin-top:5px; font-size:0.73rem; color:#2563eb; font-weight:600; display:flex; align-items:center; gap:4px;';
                        badge.innerHTML = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> Mostrando solo vehículos compatibles con <strong style="color:#1d4ed8;">${activeFuelFilter}</strong>`;
                        container.parentNode.appendChild(badge);
                    } else {
                        existingBadge.innerHTML = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> Mostrando solo vehículos compatibles con <strong style="color:#1d4ed8;">${activeFuelFilter}</strong>`;
                        existingBadge.style.display = 'flex';
                    }
                } else if (existingBadge) {
                    existingBadge.style.display = 'none';
                }
            }

            // Registrar la función en el elemento para acceso externo
            container.__filterByFuel = filterVehiclesByFuel;
            container.__selectEl = selectEl;

            function handleKeyNavigation(e) {
                if (!container.classList.contains('open')) return;
                const vis = getVisibleItems();

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (vis.length === 0) return;
                    activeFocusedIndex = (activeFocusedIndex + 1) % vis.length;
                    setActiveItem(vis[activeFocusedIndex].el);
                    vis[activeFocusedIndex].el.scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (vis.length === 0) return;
                    activeFocusedIndex = (activeFocusedIndex - 1 + vis.length) % vis.length;
                    setActiveItem(vis[activeFocusedIndex].el);
                    vis[activeFocusedIndex].el.scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();
                    if (activeFocusedIndex >= 0 && activeFocusedIndex < vis.length) {
                        vis[activeFocusedIndex].el.click();
                    } else if (vis.length > 0) {
                        vis[0].el.click();
                    }
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    e.stopPropagation();
                    container.classList.remove('open');
                    trigger.focus();
                }
            }

            searchInput.addEventListener('click', (e) => e.stopPropagation());
            searchInput.addEventListener('input', (e) => {
                filterVehicles(e.target.value);
            });
            searchInput.addEventListener('keydown', handleKeyNavigation);

            trigger.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (!container.classList.contains('open')) {
                        openDropdown();
                    }
                }
            });

            function openDropdown() {
                document.querySelectorAll('.custom-select-container.open').forEach(c => {
                    if (c !== container) c.classList.remove('open');
                });
                container.classList.add('open');
                searchInput.value = '';
                filterVehicles('');

                const currentSelected = allVehicleItems.find(v => v.index === selectEl.selectedIndex && !v.el.classList.contains('v-hidden'));
                if (currentSelected) {
                    setActiveItem(currentSelected.el);
                    setTimeout(() => currentSelected.el.scrollIntoView({ block: 'nearest' }), 30);
                } else {
                    const vis = getVisibleItems();
                    if (vis.length > 0) setActiveItem(vis[0].el);
                }

                setTimeout(() => searchInput.focus(), 50);
            }

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                if (container.classList.contains('open')) {
                    container.classList.remove('open');
                } else {
                    openDropdown();
                }
            });

            document.addEventListener('click', (e) => {
                if (!container.contains(e.target)) {
                    container.classList.remove('open');
                }
            });

            selectEl.addEventListener('change', () => {
                updateTrigger();
                allVehicleItems.forEach(v => {
                    const isSel = v.index === selectEl.selectedIndex;
                    v.el.classList.toggle('selected', isSel);
                    const chk = v.el.querySelector('.v-item-check');
                    if (chk) chk.style.display = isSel ? 'inline' : 'none';
                });

                if (selectEl.selectedIndex === 0) {
                    filterVehicles('');
                }

                verificarCamposIncompletos();
                if (selectEl.value !== '') {
                    trigger.style.borderColor = '';
                    trigger.style.boxShadow = '';
                }
            });

            buildList();
            updateTrigger();
        }

        initCustomDispatchSelect('fuel_type_select', true, 'Buscar tipo de combustible...');
        initVehicleGroupedSelect('vehicle_id', 'Buscar por área, placa, unidad o tipo...');
        initCustomDispatchSelect('driver_name', true, 'Buscar o escribir conductor...', true, 40, true);
        initCustomDispatchSelect('destination', true, 'Buscar área o subgerencia...');

        // Referencia al contenedor del selector de vehículos (para acceso a filterByFuel)
        const vehicleGroupedContainer = document.querySelector('.vehicle-grouped-select-container');

        // === FILTRO DE VEHÍCULOS POR TIPO DE COMBUSTIBLE ===
        // Cuando cambia el combustible seleccionado, se filtran los vehículos
        // mostrando únicamente los que son compatibles con ese combustible.
        fuelTypeSelect.addEventListener('change', () => {
            const selectedFuel = fuelTypeSelect.value || '';
            if (vehicleGroupedContainer && vehicleGroupedContainer.__filterByFuel) {
                vehicleGroupedContainer.__filterByFuel(selectedFuel);
            }
        });

        // Auto-sugerir combustible y área cuando se selecciona un vehículo
        const vehicleSelectEl = document.getElementById('vehicle_id');
        if (vehicleSelectEl) {
            vehicleSelectEl.addEventListener('change', () => {
                const opt = vehicleSelectEl.options[vehicleSelectEl.selectedIndex];
                if (opt) {
                    const fuelName = opt.getAttribute('data-fuel-type');
                    if (fuelName && fuelTypeSelect) {
                        const currentFuelVal = fuelTypeSelect.value || '';

                        // Si el combustible ya seleccionado en el formulario es compatible con el vehículo, no lo sobreescribimos
                        const alreadyCompatible = currentFuelVal && isFuelCompatible(fuelName, currentFuelVal);

                        if (!alreadyCompatible) {
                            for (let i = 1; i < fuelTypeSelect.options.length; i++) {
                                const ftVal = fuelTypeSelect.options[i].value || '';
                                if (ftVal && isFuelCompatible(fuelName, ftVal)) {
                                    fuelTypeSelect.selectedIndex = i;
                                    fuelTypeSelect.dispatchEvent(new Event('change', { bubbles: true }));
                                    break;
                                }
                            }
                        }
                    }
                    const vArea = opt.getAttribute('data-area');
                    const destSelect = document.getElementById('destination');
                    if (vArea && destSelect && !destSelect.value) {
                        for (let j = 0; j < destSelect.options.length; j++) {
                            if (destSelect.options[j].value.toLowerCase() === vArea.toLowerCase()) {
                                destSelect.selectedIndex = j;
                                destSelect.dispatchEvent(new Event('change', { bubbles: true }));
                                break;
                            }
                        }
                    }
                }
            });
        }

        // Ejecutar inicio (deja el campo oculto y las alertas en su estado inicial)
        validateStock();

        // Si se acaba de completar un registro, asegurar que los campos comiencen totalmente limpios
        if (sessionStorage.getItem('despacho_completado') === '1') {
            sessionStorage.removeItem('despacho_completado');
            limpiarFormulario(false);
        }
    });

    // Limpieza al restaurar desde el historial o caché del navegador (Back/Forward Cache)
    window.addEventListener('pageshow', (event) => {
        if (event.persisted || sessionStorage.getItem('despacho_completado') === '1') {
            sessionStorage.removeItem('despacho_completado');
            limpiarFormulario(false);
        }
    });
</script>