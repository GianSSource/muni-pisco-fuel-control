<style>
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

    .btn-clear-styled {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        border-radius: 8px;
        border: 1px solid #f97316;
        background: transparent;
        color: #f97316;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
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
</style>

<section class="container" style="max-width: 900px; margin: 40px auto; padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 700; margin: 0; color: var(--text-color, #1e293b);">Registrar
                Ingreso de Combustible</h1>
            <p style="color: var(--text-muted, #64748b); margin-top: 4px; font-size: 0.95rem;">Ingresa una nueva recarga
                o abastecimiento de combustible al stock municipal</p>
        </div>
        <a href="<?= BASE_URL ?>dashboard" class="btn-cancel-styled" style="font-size: 0.9rem; font-weight: 500;">
            ← Volver al Dashboard
        </a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="catalog-alert catalog-alert-danger" style="margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>ingresos/guardar" id="incomeForm"
        style="background: var(--card-bg, #ffffff); padding: 28px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); box-shadow: 0 4px 12px rgba(0,0,0,0.04);"
        onsubmit="return validarFormularioIngreso(event)">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <!-- Carga de Alerta de Validación de Campos Faltantes -->
        <div id="formValidationAlert"
            style="display: none; background: rgba(249, 115, 22, 0.10); border: 1px solid #f97316; border-left: 4px solid #f97316; color: #f97316; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.88rem; line-height: 1.5;">
        </div>

        <div style="margin-bottom: 20px;">
            <label for="fuel_type"
                style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 8px; color: var(--text-color, #1e293b);">Tipo
                de Combustible</label>

            <!-- Fichas Visuales Compactas -->
            <div class="visual-options-grid" id="fuelTypePillsGrid">
                <!-- Se genera dinámicamente vía JS -->
            </div>

            <!-- Select Nativo Oculto -->
            <select name="fuel_type" id="fuel_type" style="display:none;" required <?= empty($fuelTypes) ? 'disabled' : '' ?>>
                <?php if (!empty($fuelTypes)): ?>
                    <option value="">Seleccione un tipo de combustible...</option>
                    <?php foreach ($fuelTypes as $ft): ?>
                        <option value="<?= htmlspecialchars($ft) ?>"><?= htmlspecialchars($ft) ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="">No hay tipos de combustible registrados</option>
                <?php endif; ?>
            </select>
            <?php if (empty($fuelTypes)): ?>
                <p style="color:#f97316; font-size:0.82rem; margin-top:6px;">
                    No hay tipos de combustible activos en el catálogo.
                    <a href="<?= BASE_URL ?>admin/catalogos" style="color:#2563eb; font-weight:600;">Ve a Catálogos para
                        agregar
                        uno</a> antes de registrar un ingreso.
                </p>
            <?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 20px;">
            <div style="min-width: 0;">
                <label for="quantity"
                    style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; color: var(--text-color, #1e293b);">Cantidad
                    de Galones (Máx. 10,000.00)</label>
                <input type="text" name="quantity" id="quantity" class="form-control-styled" placeholder="Ej: 5,000.00"
                    maxlength="12" oninput="
                        let val = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
                        let parts = val.split('.');
                        if (parseFloat(parts[0]) > 10000) {
                            parts[0] = '10000';
                            if (parts[1] !== undefined) parts[1] = '00';
                        } else {
                            parts[0] = parts[0].slice(0, 5);
                            if (parseFloat(parts[0]) === 10000 && parts[1] !== undefined) {
                                parts[1] = '00';
                            }
                        }
                        if (parts[1] !== undefined) {
                            parts[1] = parts[1].slice(0, 2);
                            this.value = parts[0] + '.' + parts[1];
                        } else {
                            this.value = parts[0];
                        }
                    " onblur="
                        let val = this.value.replace(/,/g, '');
                        if (val !== '' && !isNaN(val)) {
                            let num = parseFloat(val);
                            if (num > 10000) num = 10000;
                            this.value = num.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
                        }
                        verificarCamposIncompletos();
                    " onfocus="
                        this.value = this.value.replace(/,/g, '');
                    " required>
            </div>

            <div style="min-width: 0;">
                <label for="supplier"
                    style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; color: var(--text-color, #1e293b);">Proveedor
                    / Grifo</label>
                <select name="supplier" id="supplier" class="form-control-styled" required <?= empty($suppliers) ? 'disabled' : '' ?>>
                    <?php if (!empty($suppliers)): ?>
                        <option value="">Seleccione un proveedor o grifo...</option>
                        <?php foreach ($suppliers as $sup): ?>
                            <option value="<?= htmlspecialchars($sup) ?>"><?= htmlspecialchars($sup) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No hay proveedores registrados</option>
                    <?php endif; ?>
                </select>
                <?php if (empty($suppliers)): ?>
                    <p style="color:#f97316; font-size:0.82rem; margin-top:6px;">
                        No hay proveedores activos en el catálogo.
                        <a href="<?= BASE_URL ?>admin/catalogos" style="color:#2563eb; font-weight:600;">Ve a Catálogos para
                            agregar uno</a> antes de registrar un ingreso.
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div style="min-width: 0;">
                <label for="invoice"
                    style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; color: var(--text-color, #1e293b);">N°
                    Factura / Guía de Remisión</label>
                <input type="text" name="invoice" id="invoice" required class="form-control-styled"
                    placeholder="Ej: F001-0004582" maxlength="13" oninput="
                        let val = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                        val = val.slice(0, 12);
                        if (val.length > 4) {
                             val = val.slice(0, 4) + '-' + val.slice(4);
                            }
                        this.value = val;
                    " onblur="verificarCamposIncompletos()">
            </div>

            <div style="min-width: 0;">
                <label for="notes"
                    style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; color: var(--text-color, #1e293b);">Observaciones</label>
                <input type="text" name="notes" id="notes" class="form-control-styled"
                    placeholder="Ej: Abastecimiento mensual Cisterna Uno" maxlength="50"
                    oninput="this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\-_]/g, '')">
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 24px;">
            <button type="button" class="btn-clear-styled" onclick="limpiarFormulario()" title="Limpiar todos los campos">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 2L7 12"/>
                    <path d="M7 12c-2 2-3 4.5-3 7l3-1 1 3c2.5 0 5-1 7-3L7 12z"/>
                    <path d="M14 9l3 3"/>
                </svg>
                Limpiar Campos
            </button>
            <div style="display: flex; gap: 12px;">
                <a href="<?= BASE_URL ?>dashboard" class="btn-cancel-styled">Cancelar</a>
                <button type="submit" id="submitBtn" class="btn-submit-styled">Guardar Ingreso</button>
            </div>
        </div>
    </form>
</section>

<script>
    function limpiarFormulario() {
        const form = document.getElementById('incomeForm');
        if (!form) return;

        // Reset inputs de texto / número (excepto hidden como csrf_token)
        const inputs = form.querySelectorAll('input:not([type="hidden"])');
        inputs.forEach(inp => {
            inp.value = '';
            inp.style.borderColor = '';
            inp.style.boxShadow = '';
        });

        const textareas = form.querySelectorAll('textarea');
        textareas.forEach(t => { t.value = ''; });

        // Reset selects nativos (dispara 'change' para resetear fichas visuales y custom dropdown)
        const nativeSelects = form.querySelectorAll('select');
        nativeSelects.forEach(sel => {
            sel.selectedIndex = 0;
            sel.dispatchEvent(new Event('change', { bubbles: true }));
        });

        // Reset bordes de custom selects
        form.querySelectorAll('.custom-select-trigger').forEach(trigger => {
            trigger.style.borderColor = '';
            trigger.style.boxShadow = '';
        });

        // Ocultar alerta de validación
        const alertBox = document.getElementById('formValidationAlert');
        if (alertBox) {
            alertBox.style.display = 'none';
            alertBox.innerHTML = '';
        }

        // Re-habilitar botón submit si estuviese deshabilitado
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }

        // Animación visual de confirmación en el botón
        const btn = document.querySelector('.btn-clear-styled');
        if (btn) {
            btn.style.background = 'rgba(249, 115, 22, 0.18)';
            btn.style.transform = 'scale(0.96)';
            setTimeout(() => {
                btn.style.background = '';
                btn.style.transform = '';
            }, 300);
        }
    }

    function verificarCamposIncompletos() {
        const fuelType = document.getElementById('fuel_type')?.value.trim();
        const quantityVal = document.getElementById('quantity')?.value.trim();
        const supplier = document.getElementById('supplier')?.value.trim();
        const invoice = document.getElementById('invoice')?.value.trim();

        const alertBox = document.getElementById('formValidationAlert');
        if (!alertBox) return [];

        const hasAnyField = fuelType || quantityVal || supplier || invoice;
        if (!hasAnyField) {
            alertBox.style.display = 'none';
            return [];
        }

        const faltantes = [];
        if (!fuelType) faltantes.push('Tipo de Combustible (Ficha visual)');
        if (!quantityVal || parseFloat(quantityVal.replace(/,/g, '')) <= 0) faltantes.push('Cantidad de Galones');
        if (!supplier) faltantes.push('Proveedor / Grifo');
        if (!invoice) faltantes.push('N° Factura / Guía de Remisión');

        if (faltantes.length > 0) {
            alertBox.style.display = 'block';
            alertBox.innerHTML = `<span style="color:#f97316;">${uiIcon('alert')}</span> <strong style="color:#f97316;">Atención: Tiene campos obligatorios pendientes por completar:</strong><div style="margin-top:6px; font-weight:600; color:#f97316;">• ${faltantes.join('<br>• ')}</div>`;
        } else {
            alertBox.style.display = 'none';
        }

        return faltantes;
    }

    function validarFormularioIngreso(e) {
        const fuelType = document.getElementById('fuel_type')?.value.trim();
        const quantityVal = document.getElementById('quantity')?.value.trim();
        const supplier = document.getElementById('supplier')?.value.trim();
        const invoice = document.getElementById('invoice')?.value.trim();

        const faltantes = [];
        if (!fuelType) faltantes.push('Tipo de Combustible (Ficha visual)');
        if (!quantityVal || parseFloat(quantityVal.replace(/,/g, '')) <= 0) faltantes.push('Cantidad de Galones');
        if (!supplier) faltantes.push('Proveedor / Grifo');
        if (!invoice) faltantes.push('N° Factura / Guía de Remisión');

        const alertBox = document.getElementById('formValidationAlert');

        if (faltantes.length > 0) {
            if (e) e.preventDefault();
            if (alertBox) {
                alertBox.style.display = 'block';
                alertBox.innerHTML = `<span style="color:#f97316;">${uiIcon('alert')}</span> <strong style="color:#f97316;">Por favor complete los siguientes campos obligatorios antes de guardar:</strong><div style="margin-top:6px; font-weight:600; color:#f97316;">• ${faltantes.join('<br>• ')}</div>`;
                alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return false;
        }

        if (alertBox) alertBox.style.display = 'none';

        let q = document.getElementById('quantity');
        if (q) {
            let cleaned = q.value.replace(/,/g, '');
            let parsed = parseFloat(cleaned);
            if (!isNaN(parsed)) {
                if (parsed > 10000) parsed = 10000;
                q.value = parsed.toFixed(2);
            }
        }
        return true;
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Función para obtener metadata enriquecida de cada opción (Icono, Título, Subtítulo y Estilo de Badge)
        function getOptionMeta(selectType, val) {
            const v = val.toLowerCase();
            if (selectType === 'fuel_type') {
                if (v.includes('diesel')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Combustible Diesel', badgeClass: 'bg-fuel-diesel' };
                } else if (v.includes('regular')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Gasolina 90 Octanos', badgeClass: 'bg-fuel-regular' };
                } else if (v.includes('premium')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Gasolina 95/97 Octanos', badgeClass: 'bg-fuel-premium' };
                } else if (v.includes('glp') || v.includes('gas licuado')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Gas Licuado', badgeClass: 'bg-fuel-glp' };
                } else if (v.includes('gnv') || v.includes('gas natural')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Gas Natural Vehicular', badgeClass: 'bg-fuel-gnv' };
                } else if (v.includes('butano')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Gas Butano', badgeClass: 'bg-fuel-butano' };
                }
                return { icon: uiIcon('fuel'), title: val, sub: 'Combustible Municipal', badgeClass: 'bg-fuel-diesel' };
            } else if (selectType === 'supplier') {
                if (v.includes('petroperu') || v.includes('petroperú')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Estación Petroperú', badgeClass: 'bg-sup-petroperu' };
                } else if (v.includes('repsol')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Estación Repsol', badgeClass: 'bg-sup-repsol' };
                } else if (v.includes('primax')) {
                    return { icon: uiIcon('fuel'), title: val, sub: 'Estación Primax', badgeClass: 'bg-sup-primax' };
                } else if (v.includes('central') || v.includes('municipal')) {
                    return { icon: uiIcon('building'), title: val, sub: 'Grifo Central Municipal', badgeClass: 'bg-sup-central' };
                }
                return { icon: uiIcon('store'), title: val, sub: 'Proveedor Autorizado', badgeClass: 'bg-sup-default' };
            }
            return { icon: uiIcon('store'), title: val, sub: '', badgeClass: 'bg-sup-default' };
        }

        // 1. Exclusivo para Tipo de Combustible: Solo Fichas Visuales (Pills Cards)
        function setupFuelPillsOnly(selectId, gridId) {
            const selectEl = document.getElementById(selectId);
            const gridEl = document.getElementById(gridId);
            if (!selectEl || !gridEl) return;

            selectEl.style.display = 'none'; // Ocultar select nativo para dejar solo las fichas
            const options = Array.from(selectEl.options);

            function updatePills() {
                gridEl.innerHTML = '';
                options.forEach((opt, idx) => {
                    if (opt.value === '') return; // No generar ficha para la opción placeholder
                    const isSelected = idx === selectEl.selectedIndex;
                    const meta = getOptionMeta('fuel_type', opt.value);
                    const card = document.createElement('div');
                    card.className = `visual-option-card ${isSelected ? 'active' : ''}`;
                    card.setAttribute('data-value', opt.value);
                    card.innerHTML = `
                    <div class="card-check">${uiIcon('check')}</div>
                    <div class="card-icon-badge ${meta.badgeClass}">${meta.icon}</div>
                    <div class="card-title">${meta.title}</div>
                    <div class="card-sub">${meta.sub}</div>
                `;
                    card.addEventListener('click', () => {
                        selectEl.selectedIndex = idx;
                        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
                        updatePills();
                    });
                    gridEl.appendChild(card);
                });
            }

            selectEl.addEventListener('change', () => {
                updatePills();
                verificarCamposIncompletos();
            });
            updatePills();
        }

        // 2. Exclusivo para Proveedor / Grifo: Solo Lista Desplegable Personalizada
        function setupSupplierCustomDropdown(selectId) {
            const selectEl = document.getElementById(selectId);
            if (!selectEl) return;

            const options = Array.from(selectEl.options);

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
                const selectedOpt = selectEl.options[selectEl.selectedIndex];
                if (!selectedOpt) return;

                const isPlaceholder = selectedOpt.value === '';
                const labelText = isPlaceholder ? 'Seleccione un proveedor o grifo...' : selectedOpt.text;
                const labelColor = isPlaceholder ? 'var(--text-muted, #94a3b8)' : 'var(--text-color, #0f172a)';

                trigger.innerHTML = `
                <div class="custom-select-selected-content">
                    <span class="custom-select-label-text" style="font-weight:600; font-size:0.9rem; color:${labelColor};">${labelText}</span>
                </div>
                <span class="custom-select-chevron">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </span>
            `;

                menu.innerHTML = '';
                options.forEach((opt, idx) => {
                    if (opt.value === '' && idx === 0) return; // Omitir placeholder del menú desplegable
                    const isSelected = idx === selectEl.selectedIndex;

                    const item = document.createElement('div');
                    item.className = `custom-select-item ${isSelected ? 'selected' : ''}`;
                    item.innerHTML = `
                    <div class="item-left" style="flex:1; min-width:0;">
                        <span class="item-title" style="font-size:0.88rem; font-weight:600; color:var(--text-color, #0f172a);">${opt.text}</span>
                    </div>
                    ${isSelected ? `<span class="item-check">${uiIcon('check')}</span>` : ''}
                `;

                    item.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectEl.selectedIndex = idx;
                        selectEl.dispatchEvent(new Event('change', { bubbles: true }));
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
                verificarCamposIncompletos();
            });
            updateUI();
        }

        setupFuelPillsOnly('fuel_type', 'fuelTypePillsGrid');
        setupSupplierCustomDropdown('supplier');
    });
</script>