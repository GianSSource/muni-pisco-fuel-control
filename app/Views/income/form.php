<div class="search-page-wrapper" style="max-width: 650px; margin: 0 auto;">
    <section class="page-head">
        <div>
            <h1>⛽ Registrar Ingreso de Combustible</h1>
            <p class="subtitle">Ingresa una nueva recarga o abastecimiento de combustible al stock municipal</p>
        </div>
        <div class="actions">
            <a class="button secondary" href="<?= BASE_URL ?>dashboard">← Volver al Dashboard</a>
        </div>
    </section>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.2);">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="search-filter-card" style="padding: 24px; border-radius: 12px; background: var(--bg-card, #fff); border: 1px solid var(--border, #e2e8f0);">
        <form method="post" action="<?= BASE_URL ?>ingresos/guardar" class="form wide">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="fuel_type" style="display: block; font-weight: 600; margin-bottom: 6px;">⛽ Tipo de Combustible *</label>
                <select name="fuel_type" id="fuel_type" class="filter-select" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
                    <?php if (!empty($fuelTypes)): ?>
                        <?php foreach ($fuelTypes as $ft): ?>
                            <option value="<?= htmlspecialchars($ft) ?>"><?= htmlspecialchars($ft) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="Diesel B5 S-50">Diesel B5 S-50</option>
                        <option value="Gasohol Regular">Gasohol Regular</option>
                        <option value="Gasohol Premium">Gasohol Premium</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="quantity" style="display: block; font-weight: 600; margin-bottom: 6px;">📦 Cantidad de Galones *</label>
                <input type="number" step="0.01" min="0.1" name="quantity" id="quantity" class="filter-input" placeholder="Ej: 500.00" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="supplier" style="display: block; font-weight: 600; margin-bottom: 6px;">🏢 Proveedor / Grifo *</label>
                <input type="text" name="supplier" id="supplier" list="suppliers_list" class="filter-input" placeholder="Ej: Repsol Pisco / Petroperú" maxlength="100" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);" required>
                <datalist id="suppliers_list">
                    <?php if (!empty($suppliers)): ?>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?= htmlspecialchars($sup) ?>">
                        <?php endforeach; ?>
                    <?php endif; ?>
                </datalist>
            </div>

            <div class="filter-field" style="margin-bottom: 16px;">
                <label for="invoice" style="display: block; font-weight: 600; margin-bottom: 6px;">📄 N° Factura / Guía de Remisión</label>
                <input type="text" name="invoice" id="invoice" class="filter-input" placeholder="Ej: F001-0004582" maxlength="40" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);">
            </div>

            <div class="filter-field" style="margin-bottom: 24px;">
                <label for="notes" style="display: block; font-weight: 600; margin-bottom: 6px;">📝 Observaciones</label>
                <input type="text" name="notes" id="notes" class="filter-input" placeholder="Ej: Abastecimiento mensual Cisterna 01" maxlength="40" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border);">
            </div>

            <div style="display: flex; gap: 12px; justify-content: flex-end;">
                <a href="<?= BASE_URL ?>dashboard" class="button secondary">Cancelar</a>
                <button type="submit" class="button primary">Guardar Ingreso</button>
            </div>
        </form>
    </div>
</div>
