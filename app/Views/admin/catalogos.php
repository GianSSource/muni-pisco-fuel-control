<style>
    .catalog-container {
        max-width: 1050px;
        margin: 32px auto;
        padding: 0 16px;
    }

    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .catalog-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-color, #1e293b);
    }

    .catalog-subtitle {
        color: var(--text-muted, #64748b);
        margin: 4px 0 0 0;
        font-size: 0.95rem;
    }

    .btn-cancel-styled {
        padding: 9px 18px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #cbd5e1);
        color: var(--text-color, inherit);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.2s ease;
        background: var(--card-bg, #ffffff);
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel-styled:hover {
        background: var(--bg-body, #f8fafc);
        border-color: #94a3b8;
    }

    .catalog-alert {
        padding: 14px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
        font-size: 0.92rem;
    }

    .catalog-alert-success {
        background: #f0fdf4;
        border-left: 4px solid #22c55e;
        color: #15803d;
    }

    .catalog-alert-danger {
        background: #fef2f2;
        border-left: 4px solid #ef4444;
        color: #991b1b;
    }

    /* Form Cards */
    .catalog-card-box {
        background: var(--card-bg, #ffffff);
        padding: 24px;
        border-radius: 12px;
        border: 1px solid var(--border-color, #e2e8f0);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        margin-bottom: 24px;
    }

    .catalog-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 16px 0;
        color: var(--text-color, #1e293b);
    }

    .form-control-styled {
        width: 100%;
        height: 42px;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #cbd5e1);
        background: var(--input-bg, #fff);
        color: var(--text-color, inherit);
        font-size: 0.9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }

    .form-control-styled:hover {
        border-color: #94a3b8;
    }

    .form-control-styled:focus {
        outline: none;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
    }

    .btn-submit-styled {
        padding: 10px 20px;
        border-radius: 8px;
        background: #3b82f6;
        color: #ffffff;
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
        height: 42px;
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit-styled:hover {
        background: #2563eb !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    /* Tabs Layout */
    .catalog-tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        padding-bottom: 0;
        overflow-x: auto;
    }

    .catalog-tab-btn {
        background: transparent;
        border: none;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-muted, #64748b);
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .catalog-tab-btn:hover {
        color: var(--text-color, #1e293b);
    }

    .catalog-tab-btn.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }

    .catalog-tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .catalog-tab-content.active {
        display: block;
    }

    /* Reserva el mismo espacio para cada pestaña y evita saltos de proporción. */
    .catalog-tabs-panel {
        min-height: 410px;
    }

    @media (max-width: 640px) {

        .catalog-tabs-panel,
        .catalog-list-card {
            min-height: 0;
        }

        .catalog-list-box {
            height: min(290px, 55vh);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(4px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .catalog-list-card {
        background: var(--card-bg, #ffffff);
        border-radius: 12px;
        border: 1px solid var(--border-color, #e2e8f0);
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        min-height: 410px;
        box-sizing: border-box;
    }

    .catalog-list-card-header {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 14px;
        border-bottom: 1px solid var(--border-color, #e2e8f0);
        padding-bottom: 14px;
    }

    .catalog-list-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .catalog-list-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-color, #1e293b);
    }

    .catalog-pill-badge {
        font-size: 0.78rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 12px;
        background: rgba(59, 130, 246, 0.18);
        color: #60a5fa;
        border: 1px solid rgba(96, 165, 250, 0.45);
    }

    .catalog-list-box {
        height: 290px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .catalog-list-box::-webkit-scrollbar {
        width: 6px;
    }

    .catalog-list-box::-webkit-scrollbar-thumb {
        background: var(--border-color, #cbd5e1);
        border-radius: 4px;
    }

    .catalog-items-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .catalog-item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 14px;
        border-radius: 8px;
        background: var(--bg-body, #f8fafc);
        border: 1px solid var(--border-color, #e2e8f0);
        transition: all 0.15s ease;
    }

    .catalog-item-row:hover {
        background: var(--card-bg, #ffffff);
        border-color: #cbd5e1;
    }

    .catalog-item-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-color, #1e293b);
    }

    .btn-delete-styled {
        color: #f87171;
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(248, 113, 113, 0.45);
        font-size: 0.8rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-delete-styled:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

    .vehicle-plate-badge {
        font-family: monospace;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 2px 8px;
        border-radius: 6px;
        background: #1e293b;
        color: #ffffff;
    }

    .vehicle-unit-badge {
        font-size: 0.8rem;
        font-weight: 700;
        color: #60a5fa;
        background: rgba(59, 130, 246, 0.18);
        padding: 2px 8px;
        border-radius: 6px;
        border: 1px solid rgba(96, 165, 250, 0.45);
    }

    .vehicle-meta-tag {
        font-size: 0.78rem;
        color: var(--text-muted, #64748b);
    }

    .catalog-empty-text {
        text-align: center;
        color: var(--text-muted, #64748b);
        font-size: 0.88rem;
        padding: 16px;
        border: 1px dashed var(--border-color, #cbd5e1);
        border-radius: 8px;
    }
</style>

<div class="catalog-container">
    <!-- Encabezado -->
    <div class="catalog-header">
        <div>
            <h1 class="catalog-title">Administración de Catálogos y Parámetros</h1>
            <p class="catalog-subtitle">Gestiona los tipos de combustible, áreas municipales, proveedores y vehículos del sistema</p>
        </div>
        <a href="<?= BASE_URL ?>dashboard" class="btn-cancel-styled">
            ← Volver al Panel
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="catalog-alert catalog-alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="catalog-alert catalog-alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulario Rápido para Agregar Nuevo Elemento -->
    <div class="catalog-card-box">
        <h3 class="catalog-card-title">Agregar Nuevo Parámetro al Catálogo</h3>
        <form method="post" action="<?= BASE_URL ?>admin/catalogos/guardar" id="form_registrar_catalogo" novalidate
            style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

            <div id="catalogFormErrorBanner" class="form-error-banner" style="display: none; width: 100%;">
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Categoría *</label>
                <select name="category" id="select_category" required class="form-control-styled">
                    <option value="" disabled selected hidden>Seleccione una categoría...</option>
                    <option value="fuel_type">Tipo de Combustible</option>
                    <option value="area">Área / Subgerencia</option>
                    <option value="supplier">Proveedor / Grifo</option>
                    <option value="vehicle_type">Tipo de Vehículo</option>
                </select>
            </div>

            <div style="flex: 2; min-width: 260px;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Nombre / Descripción *</label>
                <input type="text" name="name" id="input_catalog_name" maxlength="50"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-\s]/g, '')" required
                    placeholder="Ej. Gasohol 97 Octanos, Subgerencia de Fiscalización, Grifo Primax..."
                    class="form-control-styled">
            </div>

            <div style="width: 140px;">
                <button type="submit" class="btn-submit-styled">
                    Registrar
                </button>
            </div>
        </form>
    </div>

    <!-- Formulario Rápido para Registrar Vehículo / Unidad -->
    <div class="catalog-card-box">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 18px;">
            <h3 class="catalog-card-title" style="margin: 0;">Registrar Vehículo / Unidad</h3>
        </div>

        <form method="post" action="<?= BASE_URL ?>admin/vehiculos/guardar" id="form_registrar_vehiculo" novalidate
            style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

            <div id="vehicleFormErrorBanner" class="form-error-banner" style="display: none; width: 100%;">
            </div>

            <div style="flex: 1; min-width: 140px;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Código de Unidad *</label>
                <input type="text" name="unit_code" id="input_unit_code" maxlength="8" required placeholder="Ej. UNID-045"
                    pattern="[A-Za-z0-9\-]+" title="Solo letras, números y guiones"
                    oninput="formatUnitCodeInput(this)"
                    class="form-control-styled">
            </div>

            <div style="flex: 1; min-width: 120px;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Placa *</label>
                <input type="text" name="plate" id="input_plate" maxlength="7" required placeholder="Ej. EAB-123"
                    pattern="[A-Za-z0-9\-]+" title="Solo letras, números y guiones (máx. 7 caracteres)"
                    oninput="formatPlateInput(this)"
                    class="form-control-styled" style="text-transform: uppercase;">
            </div>

            <div style="flex: 1; min-width: 160px;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Tipo de Vehículo *</label>
                <select name="vehicle_type_id" id="select_vehicle_type" required class="form-control-styled">
                    <option value="" disabled selected hidden>Seleccione un tipo de vehículo...</option>
                    <?php foreach ($vehicleTypes as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= htmlspecialchars($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="flex: 1.5; min-width: 180px;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Área Asignada *</label>
                <select name="area_id" id="select_area" required class="form-control-styled">
                    <option value="" disabled selected hidden>Seleccione un área asignada...</option>
                    <?php foreach ($areas as $area): ?>
                        <option value="<?= $area['id'] ?>"><?= htmlspecialchars($area['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="width: 140px;">
                <button type="submit" class="btn-submit-styled">
                    Registrar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabs de Catálogos Activos -->
    <div class="catalog-tabs">
        <button type="button" class="catalog-tab-btn active" onclick="switchCatalogTab(event, 'tab-combustible')">Tipos de Combustible</button>
        <button type="button" class="catalog-tab-btn" onclick="switchCatalogTab(event, 'tab-areas')">Áreas / Subgerencias</button>
        <button type="button" class="catalog-tab-btn" onclick="switchCatalogTab(event, 'tab-proveedores')">Proveedores / Grifos</button>
        <button type="button" class="catalog-tab-btn" onclick="switchCatalogTab(event, 'tab-vehiculos')">Tipos de Vehículo</button>
        <button type="button" class="catalog-tab-btn" onclick="switchCatalogTab(event, 'tab-unidades')">Vehículos Registrados</button>
    </div>

    <div class="catalog-tabs-panel">

        <!-- Tarjeta 1: Tipos de Combustible -->
        <div class="catalog-tab-content active" id="tab-combustible">
            <div class="catalog-list-card">
                <div class="catalog-list-card-header">
                    <div class="catalog-list-card-top">
                        <h3 class="catalog-list-card-title">Tipos de Combustible</h3>
                        <span class="catalog-pill-badge"><?= count($fuelTypes) ?> Registrados</span>
                    </div>
                    <div>
                        <input type="text" class="form-control-styled" maxlength="40" style="font-size: 0.82rem; padding: 7px 10px;" placeholder="Filtrar combustibles..." oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\-\s]/g, ''); filterCatalogList(this, 'fuelTypesList')">
                    </div>
                </div>

                <div class="catalog-list-box">
                    <ul class="catalog-items-list" id="fuelTypesList">
                        <?php foreach ($fuelTypes as $item): ?>
                            <li class="catalog-item-row">
                                <span class="catalog-item-name" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></span>
                                <form method="POST" action="<?= BASE_URL ?>admin/catalogos/eliminar" style="margin:0;"
                                    onsubmit="return showCustomConfirm('¿Seguro que deseas eliminar este tipo de combustible?', this)">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <button type="submit" class="btn-delete-styled">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($fuelTypes)): ?>
                            <li class="catalog-empty-text">No hay tipos de combustible registrados.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div> <!-- Fin de catalog-list-card -->
        </div> <!-- Fin de tab-combustible -->

        <!-- Tarjeta 2: Áreas Municipales -->
        <div class="catalog-tab-content" id="tab-areas">
            <div class="catalog-list-card">
                <div class="catalog-list-card-header">
                    <div class="catalog-list-card-top">
                        <h3 class="catalog-list-card-title">Áreas / Subgerencias</h3>
                        <span class="catalog-pill-badge"><?= count($areas) ?> Registradas</span>
                    </div>
                    <div>
                        <input type="text" class="form-control-styled" maxlength="40" style="font-size: 0.82rem; padding: 7px 10px;" placeholder="Filtrar áreas..." oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ''); filterCatalogList(this, 'areasList')">
                    </div>
                </div>

                <div class="catalog-list-box">
                    <ul class="catalog-items-list" id="areasList">
                        <?php foreach ($areas as $item): ?>
                            <li class="catalog-item-row">
                                <span class="catalog-item-name" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></span>
                                <form method="POST" action="<?= BASE_URL ?>admin/catalogos/eliminar" style="margin:0;"
                                    onsubmit="return showCustomConfirm('¿Seguro que deseas eliminar esta área municipal?', this)">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <button type="submit" class="btn-delete-styled">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($areas)): ?>
                            <li class="catalog-empty-text">No hay áreas municipales registradas.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div> <!-- Fin de catalog-list-card -->
        </div> <!-- Fin de tab-areas -->

        <!-- Tarjeta 3: Proveedores / Grifos -->
        <div class="catalog-tab-content" id="tab-proveedores">
            <div class="catalog-list-card">
                <div class="catalog-list-card-header">
                    <div class="catalog-list-card-top">
                        <h3 class="catalog-list-card-title">Proveedores / Grifos</h3>
                        <span class="catalog-pill-badge"><?= count($suppliers) ?> Registrados</span>
                    </div>
                    <div>
                        <input type="text" class="form-control-styled" maxlength="40" style="font-size: 0.82rem; padding: 7px 10px;" placeholder="Filtrar proveedores..." oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\-\s]/g, ''); filterCatalogList(this, 'suppliersList')">
                    </div>
                </div>

                <div class="catalog-list-box">
                    <ul class="catalog-items-list" id="suppliersList">
                        <?php foreach ($suppliers as $item): ?>
                            <li class="catalog-item-row">
                                <span class="catalog-item-name" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></span>
                                <form method="POST" action="<?= BASE_URL ?>admin/catalogos/eliminar" style="margin:0;"
                                    onsubmit="return showCustomConfirm('¿Seguro que deseas eliminar este proveedor?', this)">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <button type="submit" class="btn-delete-styled">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($suppliers)): ?>
                            <li class="catalog-empty-text">No hay proveedores registrados.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div> <!-- Fin de catalog-list-card -->
        </div> <!-- Fin de tab-proveedores -->

        <!-- Tarjeta 4: Tipos de Vehículo -->
        <div class="catalog-tab-content" id="tab-vehiculos">
            <div class="catalog-list-card">
                <div class="catalog-list-card-header">
                    <div class="catalog-list-card-top">
                        <h3 class="catalog-list-card-title">Tipos de Vehículo</h3>
                        <span class="catalog-pill-badge"><?= count($vehicleTypes) ?> Registrados</span>
                    </div>
                    <div>
                        <input type="text" class="form-control-styled" maxlength="40" style="font-size: 0.82rem; padding: 7px 10px;" placeholder="Filtrar tipos de vehículo..." oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ''); filterCatalogList(this, 'vtypesList')">
                    </div>
                </div>

                <div class="catalog-list-box">
                    <ul class="catalog-items-list" id="vtypesList">
                        <?php foreach ($vehicleTypes as $item): ?>
                            <li class="catalog-item-row">
                                <span class="catalog-item-name" title="<?= htmlspecialchars($item['name']) ?>"><?= htmlspecialchars($item['name']) ?></span>
                                <form method="POST" action="<?= BASE_URL ?>admin/catalogos/eliminar" style="margin:0;"
                                    onsubmit="return showCustomConfirm('¿Seguro que deseas eliminar este tipo de vehículo?', this)">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                                    <button type="submit" class="btn-delete-styled">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($vehicleTypes)): ?>
                            <li class="catalog-empty-text">No hay tipos de vehículo registrados.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div> <!-- Fin de catalog-list-card -->
        </div> <!-- Fin de tab-vehiculos -->
        <!-- Tarjeta 5: Vehículos Registrados -->
        <div class="catalog-tab-content" id="tab-unidades">
            <div class="catalog-list-card">
                <div class="catalog-list-card-header">
                    <div class="catalog-list-card-top">
                        <h3 class="catalog-list-card-title">Vehículos Registrados</h3>
                        <span class="catalog-pill-badge"><?= count($vehicles) ?> Vehículos</span>
                    </div>
                    <div>
                        <input type="text" class="form-control-styled" maxlength="40" style="font-size: 0.82rem; padding: 7px 10px;" placeholder="Buscar por placa, unidad o área..." oninput="this.value = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\-\s]/g, ''); filterCatalogList(this, 'vehiclesList')">
                    </div>
                </div>

                <div class="catalog-list-box">
                    <ul class="catalog-items-list" id="vehiclesList">
                        <?php foreach ($vehicles as $v): ?>
                            <li class="catalog-item-row" style="padding: 12px 14px;">
                                <div style="display: flex; flex-direction: column; gap: 4px; min-width: 0;">
                                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                        <span class="vehicle-plate-badge"><?= htmlspecialchars($v['plate']) ?></span>
                                        <span class="vehicle-unit-badge"><?= htmlspecialchars($v['unit_code']) ?></span>
                                    </div>
                                    <div class="vehicle-meta-tag">
                                        Tipo: <?= htmlspecialchars($v['vehicle_type_name'] ?? 'Sin tipo') ?> · Área: <?= htmlspecialchars($v['area_name'] ?? 'Sin área') ?>
                                    </div>
                                </div>
                                <form method="POST" action="<?= BASE_URL ?>admin/vehiculos/eliminar" style="margin:0;"
                                    onsubmit="return showCustomConfirm('¿Seguro que deseas eliminar este vehículo?', this)">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $v['id'] ?>">
                                    <button type="submit" class="btn-delete-styled">
                                        Eliminar
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                        <?php if (empty($vehicles)): ?>
                            <li class="catalog-empty-text">No hay vehículos registrados en el sistema.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div> <!-- Fin de tab-unidades -->

    </div> <!-- Fin contenedor global tabs -->
</div>

<script>
    // Limpiar la URL para evitar que el mensaje de alerta reaparezca al presionar F5 (recargar)
    if (window.history.replaceState) {
        const url = new URL(window.location);
        if (url.searchParams.has('success') || url.searchParams.has('error')) {
            url.searchParams.delete('success');
            url.searchParams.delete('error');
            window.history.replaceState(null, null, url.toString());
        }
    }

    function switchCatalogTab(e, tabId) {
        document.querySelectorAll('.catalog-tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.catalog-tab-content').forEach(content => content.classList.remove('active'));
        e.currentTarget.classList.add('active');
        document.getElementById(tabId).classList.add('active');

        // Limpiar los inputs de filtro de todas las pestañas y resetear las listas
        document.querySelectorAll('.catalog-tab-content input[type="text"]').forEach(input => {
            if (input.value !== '') {
                input.value = '';
                input.dispatchEvent(new Event('input'));
            }
        });
    }

    /**
     * Helper para quitar acentos de una cadena de texto
     */
    function removeAccents(str) {
        return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
    }

    /**
     * Filtrado interactivo en tiempo real para las listas de catálogos
     */
    function filterCatalogList(inputEl, listId) {
        const query = removeAccents(inputEl.value || '').toLowerCase().trim();
        const listEl = document.getElementById(listId);
        if (!listEl) return;

        const items = listEl.querySelectorAll('.catalog-item-row');
        items.forEach(item => {
            const text = removeAccents(item.innerText).toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    }

    /**
     * Convierte selects nativos en desplegables personalizados con bordes redondeados y sombra
     */
    function initCustomDropdown(selectId) {
        const selectEl = typeof selectId === 'string' ? document.getElementById(selectId) : selectId;
        if (!selectEl) return;
        if (selectEl.dataset.customized === 'true') return;
        selectEl.dataset.customized = 'true';

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

        function updateUI() {
            const selectedOpt = selectEl.options[selectEl.selectedIndex] || selectEl.options[0];
            if (!selectedOpt) return;

            const isPlaceholder = selectedOpt.value === '';
            const labelText = selectedOpt.text;
            const labelColor = isPlaceholder ? 'var(--text-muted, #94a3b8)' : 'var(--text-color, #0f172a)';

            trigger.innerHTML = `
                <div class="custom-select-selected-content">
                    <span class="custom-select-label-text" style="color: ${labelColor};">${labelText}</span>
                </div>
                <span class="custom-select-chevron">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </span>
            `;

            menu.innerHTML = '';
            Array.from(selectEl.options).forEach((opt, idx) => {
                if (opt.value === '' || opt.disabled) return; // Omitir la opción placeholder del menú desplegable
                const isSelected = idx === selectEl.selectedIndex;

                const item = document.createElement('div');
                item.className = `custom-select-item ${isSelected ? 'selected' : ''}`;
                item.innerHTML = `
                    <span class="item-title" style="font-size: 0.88rem; font-weight: 600; color: ${isSelected ? '#2563eb' : 'var(--text-color, #0f172a)'};">${opt.text}</span>
                    ${isSelected ? `<span class="item-check" style="color: #2563eb; font-weight: bold; font-size: 0.9rem;">${uiIcon('check')}</span>` : ''}
                `;

                item.addEventListener('click', (e) => {
                    e.stopPropagation();
                    selectEl.selectedIndex = idx;
                    selectEl.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                    updateUI();
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

    function setupFormValidation() {
        // Validar Formulario de Vehículos
        const vehicleForm = document.getElementById('form_registrar_vehiculo');
        if (vehicleForm) {
            vehicleForm.addEventListener('submit', function(e) {
                let isValid = true;
                let firstInvalidEl = null;

                const unitInput = document.getElementById('input_unit_code');
                const plateInput = document.getElementById('input_plate');
                const vTypeSelect = document.getElementById('select_vehicle_type');
                const areaSelect = document.getElementById('select_area');
                const errorBanner = document.getElementById('vehicleFormErrorBanner');

                // Limpiar errores previos
                [unitInput, plateInput].forEach(input => {
                    if (input) {
                        input.style.borderColor = '';
                        input.style.boxShadow = '';
                    }
                });

                [vTypeSelect, areaSelect].forEach(select => {
                    if (select) {
                        const container = select.closest('.custom-select-container');
                        const trigger = container ? container.querySelector('.custom-select-trigger') : select;
                        if (trigger) {
                            trigger.style.borderColor = '';
                            trigger.style.boxShadow = '';
                        }
                    }
                });

                if (errorBanner) {
                    errorBanner.style.display = 'none';
                }

                // 1. Código de Unidad
                if (!unitInput || !unitInput.value.trim()) {
                    isValid = false;
                    if (unitInput) {
                        unitInput.style.borderColor = '#ef4444';
                        unitInput.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                        if (!firstInvalidEl) firstInvalidEl = unitInput;
                    }
                }

                // 2. Placa
                if (!plateInput || !plateInput.value.trim()) {
                    isValid = false;
                    if (plateInput) {
                        plateInput.style.borderColor = '#ef4444';
                        plateInput.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                        if (!firstInvalidEl) firstInvalidEl = plateInput;
                    }
                }

                // 3. Tipo de Vehículo
                if (!vTypeSelect || !vTypeSelect.value) {
                    isValid = false;
                    if (vTypeSelect) {
                        const container = vTypeSelect.closest('.custom-select-container');
                        const trigger = container ? container.querySelector('.custom-select-trigger') : vTypeSelect;
                        if (trigger) {
                            trigger.style.borderColor = '#ef4444';
                            trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                            if (!firstInvalidEl) firstInvalidEl = trigger;
                        }
                    }
                }

                // 4. Área Asignada
                if (!areaSelect || !areaSelect.value) {
                    isValid = false;
                    if (areaSelect) {
                        const container = areaSelect.closest('.custom-select-container');
                        const trigger = container ? container.querySelector('.custom-select-trigger') : areaSelect;
                        if (trigger) {
                            trigger.style.borderColor = '#ef4444';
                            trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                            if (!firstInvalidEl) firstInvalidEl = trigger;
                        }
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    if (errorBanner) {
                        errorBanner.style.display = 'block';
                        errorBanner.innerHTML = `${uiIcon('alert')} <strong>Campos obligatorios requeridos:</strong> Debes completar Código de Unidad, Placa, Tipo de Vehículo y Área Asignada.`;
                    }
                    if (firstInvalidEl) {
                        firstInvalidEl.focus();
                    }
                    return false;
                }
            });

            // Limpieza dinámica de errores para inputs
            ['input_unit_code', 'input_plate'].forEach(id => {
                const input = document.getElementById(id);
                if (input) {
                    input.addEventListener('input', () => {
                        if (input.value.trim() !== '') {
                            input.style.borderColor = '';
                            input.style.boxShadow = '';
                        }
                    });
                }
            });
        }

        // Validar Formulario de Catálogos
        const catalogForm = document.getElementById('form_registrar_catalogo');
        if (catalogForm) {
            catalogForm.addEventListener('submit', function(e) {
                let isValid = true;
                let firstInvalidEl = null;

                const categorySelect = document.getElementById('select_category');
                const nameInput = document.getElementById('input_catalog_name');
                const errorBanner = document.getElementById('catalogFormErrorBanner');

                if (categorySelect) {
                    const container = categorySelect.closest('.custom-select-container');
                    const trigger = container ? container.querySelector('.custom-select-trigger') : categorySelect;
                    if (trigger) {
                        trigger.style.borderColor = '';
                        trigger.style.boxShadow = '';
                    }
                }
                if (nameInput) {
                    nameInput.style.borderColor = '';
                    nameInput.style.boxShadow = '';
                }
                if (errorBanner) {
                    errorBanner.style.display = 'none';
                }

                if (!categorySelect || !categorySelect.value) {
                    isValid = false;
                    const container = categorySelect ? categorySelect.closest('.custom-select-container') : null;
                    const trigger = container ? container.querySelector('.custom-select-trigger') : categorySelect;
                    if (trigger) {
                        trigger.style.borderColor = '#ef4444';
                        trigger.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                        if (!firstInvalidEl) firstInvalidEl = trigger;
                    }
                }

                if (!nameInput || !nameInput.value.trim()) {
                    isValid = false;
                    if (nameInput) {
                        nameInput.style.borderColor = '#ef4444';
                        nameInput.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.25)';
                        if (!firstInvalidEl) firstInvalidEl = nameInput;
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    if (errorBanner) {
                        errorBanner.style.display = 'block';
                        errorBanner.innerHTML = `${uiIcon('alert')} <strong>Campos obligatorios requeridos:</strong> Seleccione una Categoría e ingrese el Nombre / Descripción.`;
                    }
                    if (firstInvalidEl) {
                        firstInvalidEl.focus();
                    }
                    return false;
                }
            });

            const nameInput = document.getElementById('input_catalog_name');
            if (nameInput) {
                nameInput.addEventListener('input', () => {
                    if (nameInput.value.trim() !== '') {
                        nameInput.style.borderColor = '';
                        nameInput.style.boxShadow = '';
                    }
                });
            }
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Restaurar categoría seleccionada para facilitar ingresos múltiples
        const catSelect = document.getElementById('select_category');
        if (catSelect) {
            const savedCat = sessionStorage.getItem('lastCatalogCategory');
            if (savedCat) {
                catSelect.value = savedCat;
            }
            catSelect.addEventListener('change', (e) => {
                sessionStorage.setItem('lastCatalogCategory', e.target.value);
            });
        }

        initCustomDropdown('select_category');
        initCustomDropdown('select_vehicle_type');
        initCustomDropdown('select_area');
        setupFormValidation();
    });
   /**
 * Formatea automáticamente la Placa agregando un guion tras los primeros 3 caracteres alfanuméricos.
 * Formato resultante: XXX-XXX
 */
    function formatPlateInput(input) {
    // 1. Limpia cualquier carácter que no sea letra o número y convierte a mayúsculas
    let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    
    // 2. Si tiene más de 3 caracteres, inserta el guion
    if (value.length > 3) {
        value = value.slice(0, 3) + '-' + value.slice(3, 6);
    }
    
    input.value = value;
    }

/**
 * Formatea automáticamente el Código de Unidad agregando un guion tras los primeros 4 caracteres alfanuméricos.
 * Formato resultante: XXXX-XXX
 */
    function formatUnitCodeInput(input) {
    // 1. Limpia caracteres no permitidos y convierte a mayúsculas
    let value = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    
    // 2. Si tiene más de 4 caracteres, inserta el guion
    if (value.length > 4) {
        value = value.slice(0, 4) + '-' + value.slice(4, 7);
    }
    
    input.value = value;
    }
</script>