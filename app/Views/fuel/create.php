<style>
    .form-control-styled {
        width: 100%;
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
        padding: 10px 24px;
        border-radius: 8px;
        background: #3b82f6;
        color: #ffffff;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.3);
    }

    .btn-submit-styled:hover {
        background: #2563eb !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
    }

    .btn-cancel-styled {
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid var(--border-color, #cbd5e1);
        color: inherit;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-cancel-styled:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: #94a3b8;
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

    <form method="post" action="<?= BASE_URL ?>despachos/guardar" id="dispatchForm" novalidate
        onsubmit="return validarFormularioDespacho(event)"
        style="background: var(--card-bg, #ffffff); padding: 28px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">

        <!-- Carga de Alerta de Validación de Campos Faltantes -->
        <div id="formValidationAlert"
            style="display: none; background: rgba(249, 115, 22, 0.10); border: 1px solid #f97316; border-left: 4px solid #f97316; color: #f97316; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.88rem; line-height: 1.5;">
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 16px;">
            <div style="min-width: 0;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Tipo de
                    Combustible (Stock Total)</label>
                <select id="fuel_type_select" name="fuel_type" class="form-control-styled" required
                    <?= empty($fuelGroups) ? 'disabled' : '' ?>>
                    <?php if (!empty($fuelGroups)): ?>
                        <option value="">Seleccione un tipo de combustible...</option>
                        <?php foreach ($fuelGroups as $grp): ?>
                            <?php
                            $totalFormatted = number_format($grp['total_stock'], 2);
                            $lotsJson = htmlspecialchars(json_encode($grp['lots']), ENT_QUOTES, 'UTF-8');
                            ?>
                            <option value="<?= htmlspecialchars($grp['fuel_type']) ?>"
                                data-stock="<?= $grp['total_stock'] ?>"
                                data-stock-formatted="<?= $totalFormatted ?>"
                                data-lots="<?= $lotsJson ?>">
                                <?= htmlspecialchars($grp['fuel_type']) ?> — Stock total: <?= $totalFormatted ?> Gal
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
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Vehículo /
                    Unidad</label>
                <select name="vehicle_id" id="vehicle_id" class="form-control-styled" required>
                    <option value="">Seleccione un vehículo...</option>
                    <?php if (!empty($vehicles)): ?>
                        <?php foreach ($vehicles as $v): ?>
                            <option value="<?= $v['id'] ?>" data-area="<?= htmlspecialchars($v['area_name'] ?? 'Sin Área') ?>"
                                data-plate="<?= htmlspecialchars($v['plate'] ?? '') ?>"
                                data-unit="<?= htmlspecialchars($v['unit_code'] ?? '') ?>"
                                data-type="<?= htmlspecialchars($v['vehicle_type_name'] ?? 'Sin tipo') ?>">
                                <?= htmlspecialchars($v['area_name'] ?? 'Sin Área') ?> —
                                <?= htmlspecialchars($v['plate'] ?? '') ?> (<?= htmlspecialchars($v['unit_code'] ?? '') ?>)
                                [<?= htmlspecialchars($v['vehicle_type_name'] ?? 'Sin tipo') ?>]
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

        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 16px;">
            <div style="min-width: 0;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Conductor /
                    Responsable</label>
                <select name="driver_name" id="driver_name" class="form-control-styled" required>
                    <option value="">Seleccione o escriba un conductor...</option>
                    <?php foreach ($drivers as $d): ?>
                        <option value="<?= htmlspecialchars($d) ?>"><?= htmlspecialchars($d) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($drivers)): ?>
                    <p style="color: #64748b; font-size: 0.8rem; margin: 6px 0 0;">Aún no hay conductores registrados
                        en despachos anteriores. Escribe el nombre del conductor en el campo y selecciona "Usar
                        nuevo conductor".</p>
                <?php endif; ?>
            </div>

            <div style="min-width: 0;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Cantidad de
                    Galones (Máx. 15,000.00)</label>
                <input type="text" name="gallons" id="gallons" class="form-control-styled" required
                    placeholder="Ej: 15,000" maxlength="12"
                    oninput="limitarGalones(this); verificarCamposIncompletos();"
                    onblur="formatearSalidaGalones(this); verificarCamposIncompletos();"
                    onfocus="limpiarFormatoGalones(this)">
            </div>
        </div>

        <div id="stockWarning"
            style="display:none; border-left:5px solid #ef4444; border-radius:10px; padding:14px 18px; margin-bottom:16px; align-items:flex-start; gap:12px;">
        </div>

        <!-- Campos Independientes para Área/Destino (Desplegable) y Observaciones -->
        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="min-width: 0;">
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Área /
                    Subgerencia Destino</label>
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
                <label style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px;">Observaciones
                    (Opcional)</label>
                <input type="text" name="notes" class="form-control-styled"
                    placeholder="Ej. Turno noche maximo 50 caracteres" maxlength="50"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s.,\-\/()]/g, '')">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <a href="<?= BASE_URL ?>despachos" class="btn-cancel-styled">
                Cancelar
            </a>
            <button type="submit" id="submitBtn" class="btn-submit-styled">
                Emitir Vale de Despacho
            </button>
        </div>
    </form>
</section>

<script>
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

        const faltantes = [];
        if (!fuelType) faltantes.push('Tipo de Combustible');
        if (!vehicleId) faltantes.push('Vehículo / Unidad');
        if (!driverName) faltantes.push('Conductor / Responsable');
        if (!gallonsVal || parseFloat(gallonsVal.replace(/,/g, '')) <= 0) faltantes.push('Cantidad de Galones');
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
            faltantes.push('Cantidad de Galones');
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
        return true;
    }

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

            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const fmtReq = requested.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const fmtTotal = totalStock.toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            let mensaje = null;
            if (requested > 0 && requested > totalStock) {
                mensaje = `La cantidad solicitada <strong data-role="waccent">${fmtReq} Gal</strong> supera el stock total disponible de este combustible <strong data-role="waccent">${fmtTotal} Gal</strong>.`;
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
                    triggerHTML = `
                        <div style="display:flex; flex-direction:column; gap:1px; flex:1; min-width:0; overflow:hidden;">
                            <span style="font-size:0.72rem; font-weight:700; color:#2563eb; text-transform:uppercase; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">${area}</span>
                            <span style="font-weight:600; font-size:0.88rem; color:var(--text-color, #0f172a); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">${plate} — ${unit} [${type}]</span>
                        </div>
                    `;
                } else if (selectedOpt.hasAttribute('data-receipt') && selectedOpt.getAttribute('data-receipt') !== '') {
                    const fType = selectedOpt.getAttribute('data-fuel-type');
                    const receipt = selectedOpt.getAttribute('data-receipt');
                    const stockFormatted = selectedOpt.getAttribute('data-stock-formatted');
                    const supplier = selectedOpt.getAttribute('data-supplier');
                    triggerHTML = `
                        <div style="display:flex; flex-direction:column; gap:1px; flex:1; min-width:0; overflow:hidden;">
                            <span style="font-size:0.72rem; font-weight:700; color:#0284c7; text-transform:uppercase; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">${fType} — ${receipt}</span>
                            <span style="font-weight:600; font-size:0.88rem; color:var(--text-color, #0f172a); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-align:left;">Stock: ${stockFormatted} Gal (${supplier})</span>
                        </div>
                    `;
                } else {
                    triggerHTML = `
                        <div class="custom-select-selected-content" style="flex:1; min-width:0; overflow:hidden;">
                            <span class="custom-select-label-text" style="font-weight:600; font-size:0.9rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">${selectedOpt.text}</span>
                        </div>
                    `;
                }

                trigger.innerHTML = `
                    ${triggerHTML}
                    <span class="custom-select-chevron" style="flex-shrink:0; margin-left:8px;">
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

                            const listContainer = menu.querySelector('.custom-select-list-container');
                            const visibleItems = Array.from(listContainer.querySelectorAll('.custom-select-item')).filter(i => i.style.display !== 'none');

                            if (visibleItems.length > 0) {
                                visibleItems[0].click();
                            } else if (allowCreate) {
                                let exists = Array.from(selectEl.options).find(o => o.value.toLowerCase() === term.toLowerCase());
                                if (!exists) {
                                    const newOpt = new Option(term, term, true, true);
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
                        item.innerHTML = `
                            <div class="item-left" style="flex:1; min-width:0; overflow:hidden;">
                                <div style="display:flex; flex-direction:column; gap:2px;">
                                    <div style="font-size:0.73rem; font-weight:700; color:#2563eb; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        ${area}
                                    </div>
                                    <div style="font-size:0.88rem; font-weight:600; color:var(--text-color, #0f172a); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        ${plate} <span style="color:#64748b; font-weight:500;">— ${unit}</span> <span style="font-size:0.76rem; background:#f1f5f9; padding:2px 6px; border-radius:4px; color:#475569; margin-left:4px;">[${type}]</span>
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
                        item.innerHTML = `
                            <div class="item-left" style="flex:1; min-width:0; overflow:hidden;">
                                <div style="display:flex; flex-direction:column; gap:2px;">
                                    <div style="font-size:0.73rem; font-weight:700; color:#0284c7; text-transform:uppercase; letter-spacing:0.3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        ${fType} — ${receipt}
                                    </div>
                                    <div style="font-size:0.88rem; font-weight:600; color:var(--text-color, #0f172a); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        Stock: ${stockFormatted} Gal <span style="color:#64748b; font-weight:500;">(${supplier})</span>
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
                    createBox.innerHTML = `<span>+</span> Usar nuevo conductor: "<strong>${term.trim()}</strong>"`;
                    createBox.onclick = (e) => {
                        e.stopPropagation();
                        const val = term.trim();
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

        initCustomDispatchSelect('fuel_type_select', true, 'Buscar tipo de combustible...');
        initCustomDispatchSelect('vehicle_id', true, 'Buscar por área, placa, unidad o tipo...', false, 20, false, true);
        initCustomDispatchSelect('driver_name', true, 'Buscar o escribir conductor...', true, 40, true);
        initCustomDispatchSelect('destination', false);

        // Ejecutar inicio (deja el campo oculto y las alertas en su estado inicial)
        validateStock();
    });
</script>