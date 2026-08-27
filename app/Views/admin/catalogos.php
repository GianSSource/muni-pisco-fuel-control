<section class="container" style="max-width: 960px; margin: 30px auto; padding: 0 16px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);">⚙️
                Administración de Catálogos y Parámetros</h1>
            <p style="color: var(--text-muted, #64748b); margin-top: 4px; font-size: 0.95rem;">Gestiona los tipos de
                combustible, áreas municipales y proveedores del sistema</p>
        </div>
        <a href="<?= BASE_URL ?>dashboard"
            style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); color: inherit; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
            ← Volver al Panel
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div
            style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #15803d; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div
            style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Formulario Rápido para Agregar Nuevo Elemento -->
    <div
        style="background: var(--card-bg, #ffffff); padding: 24px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); margin-bottom: 28px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <h3 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 16px 0; color: #0284c7;">➕ Agregar Nuevo Parámetro
            al Catálogo</h3>
        <form method="post" action="<?= BASE_URL ?>admin/catalogos/guardar"
            style="display: grid; grid-template-columns: 200px 1fr 140px; gap: 16px; align-items: flex-end;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Categoría
                    *</label>
                <select name="category" required
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit; font-weight: 600;">
                    <option value="fuel_type">⛽ Tipo de Combustible</option>
                    <option value="area">🏢 Área / Subgerencia</option>
                    <option value="supplier">🏪 Proveedor / Grifo</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px;">Nombre /
                    Descripción *</label>
                <input type="text" name="name" maxlength="50"
                    oninput="this.value = this.value.replace(/[^a-zA-Z0-9\-\s]/g, '')" required
                    placeholder="Ej. Gasohol 97 Octanos, Subgerencia de Fiscalización, Grifo Primax..."
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <div>
                <button type="submit"
                    style="width: 100%; padding: 10px; border-radius: 8px; background: #0284c7; color: #ffffff; border: none; font-weight: 700; cursor: pointer; height: 42px;">
                    + Registrar
                </button>
            </div>
        </form>
    </div>

    <!-- Rejilla de Catálogos Activos -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">

        <!-- Tarjeta 1: Tipos de Combustible -->
        <div
            style="background: var(--card-bg, #ffffff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div
                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 2px solid #e0f2fe; padding-bottom: 10px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #0369a1;">⛽ Tipos de Combustible</h3>
                <span
                    style="font-size: 0.8rem; font-weight: 700; background: #e0f2fe; color: #0284c7; padding: 2px 8px; border-radius: 12px;"><?= count($fuelTypes) ?>
                    Registrados</span>
            </div>

            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($fuelTypes as $item): ?>
                    <li
                        style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 600; font-size: 0.92rem;"><?= htmlspecialchars($item['name']) ?></span>
                        <a href="<?= BASE_URL ?>admin/catalogos/eliminar?id=<?= $item['id'] ?>"
                            onclick="return confirm('¿Seguro que deseas eliminar este tipo de combustible?')"
                            style="color: #ef4444; text-decoration: none; font-size: 0.82rem; font-weight: 600; padding: 2px 8px; border-radius: 6px; background: #fef2f2;">
                            🗑️ Eliminar
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Tarjeta 2: Áreas Municipales -->
        <div
            style="background: var(--card-bg, #ffffff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div
                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 2px solid #fef3c7; padding-bottom: 10px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #b45309;">🏢 Áreas / Subgerencias
                </h3>
                <span
                    style="font-size: 0.8rem; font-weight: 700; background: #fef3c7; color: #b45309; padding: 2px 8px; border-radius: 12px;"><?= count($areas) ?>
                    Registradas</span>
            </div>

            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($areas as $item): ?>
                    <li
                        style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 600; font-size: 0.92rem;"><?= htmlspecialchars($item['name']) ?></span>
                        <a href="<?= BASE_URL ?>admin/catalogos/eliminar?id=<?= $item['id'] ?>"
                            onclick="return confirm('¿Seguro que deseas eliminar esta área municipal?')"
                            style="color: #ef4444; text-decoration: none; font-size: 0.82rem; font-weight: 600; padding: 2px 8px; border-radius: 6px; background: #fef2f2;">
                            🗑️ Eliminar
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Tarjeta 3: Proveedores / Grifos -->
        <div
            style="background: var(--card-bg, #ffffff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div
                style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 2px solid #dcfce7; padding-bottom: 10px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #15803d;">🏪 Proveedores / Grifos
                </h3>
                <span
                    style="font-size: 0.8rem; font-weight: 700; background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 12px;"><?= count($suppliers) ?>
                    Registrados</span>
            </div>

            <ul style="list-style: none; padding: 0; margin: 0;">
                <?php foreach ($suppliers as $item): ?>
                    <li
                        style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-weight: 600; font-size: 0.92rem;"><?= htmlspecialchars($item['name']) ?></span>
                        <a href="<?= BASE_URL ?>admin/catalogos/eliminar?id=<?= $item['id'] ?>"
                            onclick="return confirm('¿Seguro que deseas eliminar este proveedor?')"
                            style="color: #ef4444; text-decoration: none; font-size: 0.82rem; font-weight: 600; padding: 2px 8px; border-radius: 6px; background: #fef2f2;">
                            🗑️ Eliminar
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</section>