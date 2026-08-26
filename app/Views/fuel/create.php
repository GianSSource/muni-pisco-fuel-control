<section class="container" style="max-width: 720px; margin: 40px auto; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);">⛽ Registrar Vale de Despacho</h1>
            <p style="color: var(--text-muted, #64748b); margin-top: 4px; font-size: 0.95rem;">Emisión de vale de consumo de combustible para flota vehicular</p>
        </div>
        <a href="<?= BASE_URL ?>despachos" style="padding: 8px 16px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); color: inherit; text-decoration: none; font-size: 0.9rem; font-weight: 500;">
            ← Volver a Despachos
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 14px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
            ⚠️ <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>despachos/guardar" style="background: var(--card-bg, #ffffff); padding: 28px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Lote de Ingreso (Origen del Stock) *</label>
                <select name="fuel_income_id" id="fuel_income_id" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
                    <?php if (!empty($incomes)): ?>
                        <?php foreach ($incomes as $inc): ?>
                            <?php $fType = $inc['fuel_type'] ?? 'Diesel B5 S-50'; ?>
                            <option value="<?= $inc['id'] ?>" data-stock="<?= (float)$inc['gallons_remaining'] ?>" data-fuel-type="<?= htmlspecialchars($fType) ?>">
                                <?= htmlspecialchars($inc['receipt_number']) ?> - Combustible: <?= htmlspecialchars($fType) ?> | Stock: <?= number_format((float)$inc['gallons_remaining'], 2) ?> Gal (<?= htmlspecialchars($inc['supplier']) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="1" data-stock="10000.00" data-fuel-type="Diesel B5 S-50">Lote General - Combustible: Diesel B5 S-50 | Stock: 10,000 Gal</option>
                    <?php endif; ?>
                </select>

                <!-- Indicador visual destacado del tipo de combustible que se está despachando -->
                <div id="fuelTypeBadgeBox" style="margin-top: 8px; padding: 8px 12px; background: #e0f2fe; border: 1px solid #7dd3fc; border-radius: 6px; font-size: 0.85rem; color: #0369a1; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                    <span>⛽ Tipo de Combustible a Despachar:</span>
                    <span id="fuelTypeNameText" style="background: #0284c7; color: #ffffff; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">Diesel B5 S-50</span>
                </div>
            </div>

            <div>
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Placa Vehicular *</label>
                <input type="text" name="vehicle_plate" required placeholder="Ej. EAB-123" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit; text-transform: uppercase;">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Conductor / Responsable *</label>
                <input type="text" name="driver_name" required placeholder="Nombre completo del chofer" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>

            <div>
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Cantidad de Galones *</label>
                <input type="number" step="0.01" min="0.1" name="gallons" id="gallons" required placeholder="0.00" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>
        </div>

        <div id="stockWarning" style="display: none; background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px; font-size: 0.88rem; font-weight: 600;">
            ⚠️ Atencion: La cantidad ingresada supera el stock disponible de este lote.
        </div>

        <!-- Campos Independientes para Área/Destino (Desplegable) y Observaciones -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
            <div>
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Área / Subgerencia Destino *</label>
                <select name="destination" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
                    <?php if (!empty($areas)): ?>
                        <?php foreach ($areas as $ar): ?>
                            <option value="<?= htmlspecialchars($ar) ?>"><?= htmlspecialchars($ar) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="Serenazgo y Seguridad Ciudadana">Serenazgo y Seguridad Ciudadana</option>
                        <option value="Limpieza Pública y Recolección">Limpieza Pública y Recolección de Residuos</option>
                        <option value="Obras Públicas e Infraestructura">Obras Públicas e Infraestructura</option>
                        <option value="Parques y Jardines">Parques y Jardines</option>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Observaciones (Opcional)</label>
                <input type="text" name="notes" placeholder="Ej. Turno noche, patrullaje sector 4..." style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); background: var(--input-bg, #fff); color: inherit;">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="<?= BASE_URL ?>despachos" style="padding: 10px 20px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); color: inherit; text-decoration: none; font-weight: 600;">
                Cancelar
            </a>
            <button type="submit" id="submitBtn" style="padding: 10px 24px; border-radius: 8px; background: #3b82f6; color: #ffffff; border: none; font-weight: 600; cursor: pointer;">
                Emitir Vale de Despacho
            </button>
        </div>
    </form>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const incomeSelect = document.getElementById("fuel_income_id");
    const gallonsInput = document.getElementById("gallons");
    const warningBox = document.getElementById("stockWarning");
    const submitBtn = document.getElementById("submitBtn");
    const fuelTypeNameText = document.getElementById("fuelTypeNameText");

    function updateSelectedFuelInfo() {
        if (!incomeSelect) return;
        const selectedOption = incomeSelect.options[incomeSelect.selectedIndex];
        if (!selectedOption) return;

        const fuelType = selectedOption.getAttribute("data-fuel-type") || "Diesel B5 S-50";
        if (fuelTypeNameText) {
            fuelTypeNameText.textContent = fuelType;
        }
    }

    function validateStock() {
        if (!incomeSelect || !gallonsInput) return;
        const selectedOption = incomeSelect.options[incomeSelect.selectedIndex];
        if (!selectedOption) return;

        const maxStock = parseFloat(selectedOption.getAttribute("data-stock") || "0");
        const requested = parseFloat(gallonsInput.value || "0");

        if (requested > 0 && requested > maxStock) {
            warningBox.style.display = "block";
            warningBox.innerHTML = `🛑 <strong>BLOQUEO DE SEGURIDAD:</strong> La cantidad solicitada (${requested.toFixed(2)} Gal) supera el stock disponible en el lote seleccionado (${maxStock.toFixed(2)} Gal).`;
            gallonsInput.style.borderColor = "#ef4444";
            gallonsInput.style.backgroundColor = "#fef2f2";
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

    incomeSelect.addEventListener("change", () => {
        updateSelectedFuelInfo();
        validateStock();
    });
    gallonsInput.addEventListener("input", validateStock);

    // Ejecutar inicio
    updateSelectedFuelInfo();
});
</script>
