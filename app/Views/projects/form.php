<div class="search-page-wrapper" style="max-width: 650px; margin: 0 auto;">
    <section class="page-head">
        <div>
            <h1>⛽ Registrar Nuevo Despacho de Combustible</h1>
            <p class="subtitle">Emite un vale de despacho para vehículos y maquinaria municipal</p>
        </div>
        <div class="actions">
            <a class="button secondary" href="<?= BASE_URL ?>dashboard">← Volver al Dashboard</a>
        </div>
    </section>

    <div class="search-filter-card" style="padding: 24px; border-radius: 12px; background: var(--bg-card, #fff); border: 1px solid var(--border, #e2e8f0);">
        <form method="post" action="<?= BASE_URL ?>proyectos/guardar" class="form wide">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="correlativo" style="display: block; font-weight: 600; margin-bottom: 6px;">📄 N° Vale / Correlativo</label>
                <input type="text" name="correlativo" id="correlativo" class="filter-input" placeholder="Ej: VAL-2026-0012" maxlength="40" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="placa" style="display: block; font-weight: 600; margin-bottom: 6px;">🚗 Vehículo / Placa</label>
                <input type="text" name="placa" id="placa" class="filter-input" placeholder="Ej: EAB-123 o MUNI-01" maxlength="40" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="responsable" style="display: block; font-weight: 600; margin-bottom: 6px;">👤 Conductor / Responsable</label>
                <input type="text" name="responsable" id="responsable" class="filter-input" placeholder="Ej: Carlos Mendoza" maxlength="40" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="tipo_combustible" style="display: block; font-weight: 600; margin-bottom: 6px;">⛽ Tipo de Combustible</label>
                <select name="tipo_combustible" id="tipo_combustible" class="filter-select" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
                    <option value="Diesel B5 S-50">Diesel B5 S-50</option>
                    <option value="Gasohol Regular">Gasohol Regular</option>
                    <option value="Gasohol Premium">Gasohol Premium</option>
                </select>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="cantidad" style="display: block; font-weight: 600; margin-bottom: 6px;">📦 Cantidad Entregada (Galones)</label>
                <input type="number" step="0.01" min="0.1" name="cantidad" id="cantidad" class="filter-input" placeholder="Ej: 15.50" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="destino" style="display: block; font-weight: 600; margin-bottom: 6px;">📍 Destino / Área Solicitante</label>
                <input type="text" name="destino" id="destino" class="filter-input" placeholder="Ej: Recolección de Residuos / Obras" maxlength="40" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);">
            </div>

            <div class="filter-field" style="margin-bottom: 24px;">
                <label for="observaciones" style="display: block; font-weight: 600; margin-bottom: 6px;">📝 Observaciones</label>
                <input type="text" name="observaciones" id="observaciones" class="filter-input" placeholder="Ej: Turno Mañana" maxlength="40" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);">
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="<?= BASE_URL ?>dashboard" class="button secondary">Cancelar</a>
                <button type="submit" class="button primary">Emitir Despacho</button>
            </div>
        </form>
    </div>
</div>