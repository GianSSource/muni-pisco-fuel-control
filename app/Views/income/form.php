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

    /* ── Bloque integrado N° de Documento ────────────────── */
    .doc-block {
        position: relative;
        display: flex;
        align-items: stretch;
        border-radius: 8px;
        border: 1px solid var(--border-color, #334155);
        background: var(--input-bg, #1e293b);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        height: 42px;
        box-sizing: border-box;
    }

    .doc-block:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.22);
    }

    .doc-block__type {
        position: relative;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        border-right: 1px solid var(--border-color, #334155);
        background: var(--card-bg, #0f172a);
        border-top-left-radius: 7px;
        border-bottom-left-radius: 7px;
        user-select: none;
    }

    .doc-type-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        height: 100%;
        padding: 0 14px;
        background: transparent;
        border: none;
        color: var(--text-color, #e2e8f0);
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        outline: none;
        transition: background 0.15s ease, color 0.15s ease;
        border-top-left-radius: 7px;
        border-bottom-left-radius: 7px;
        min-width: 156px;
    }

    .doc-type-trigger:hover {
        background: rgba(255, 255, 255, 0.04);
    }

    .doc-type-trigger-left {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        overflow: hidden;
    }

    .doc-type-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3b82f6;
        flex-shrink: 0;
        box-shadow: 0 0 6px rgba(59, 130, 246, 0.6);
        transition: background-color 0.2s ease, box-shadow 0.2s ease;
    }

    .doc-type-label {
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--text-color, #f1f5f9);
        white-space: nowrap;
    }

    .doc-type-chevron {
        display: inline-flex;
        align-items: center;
        color: var(--text-muted, #64748b);
        transition: transform 0.2s ease, color 0.2s ease;
        margin-left: 6px;
    }

    .doc-block__type.open .doc-type-chevron {
        transform: rotate(180deg);
        color: #3b82f6;
    }

    .doc-type-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        min-width: 230px;
        background: var(--card-bg, #0f172a);
        border: 1px solid var(--border-color, #334155);
        border-radius: 10px;
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.45), 0 4px 10px rgba(0, 0, 0, 0.25);
        padding: 6px;
        z-index: 1000;
        display: none;
        opacity: 0;
        transform: translateY(-6px);
        transition: opacity 0.15s ease, transform 0.15s ease;
    }

    .doc-block__type.open .doc-type-menu {
        display: block;
        opacity: 1;
        transform: translateY(0);
    }

    .doc-type-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 12px;
        border-radius: 7px;
        font-size: 0.86rem;
        font-weight: 600;
        color: var(--text-color, #cbd5e1);
        cursor: pointer;
        transition: all 0.15s ease;
        margin-bottom: 2px;
    }

    .doc-type-item:last-child {
        margin-bottom: 0;
    }

    .doc-type-item:hover {
        background: rgba(59, 130, 246, 0.12);
        color: #fff;
    }

    .doc-type-item.selected {
        background: rgba(59, 130, 246, 0.18);
        color: #60a5fa;
    }

    .doc-type-item-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .doc-type-item-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .doc-type-item-badge {
        font-size: 0.72rem;
        font-family: monospace;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.08);
        color: var(--text-muted, #94a3b8);
        letter-spacing: 0.04em;
    }

    .doc-type-item.selected .doc-type-item-badge {
        background: rgba(59, 130, 246, 0.25);
        color: #93c5fd;
    }

    .doc-block__input {
        flex: 1;
        height: 100%;
        padding: 0 14px;
        border: none;
        background: transparent;
        color: var(--text-color, #e2e8f0);
        font-size: 0.9rem;
        font-family: 'Courier New', 'Consolas', monospace;
        letter-spacing: 0.04em;
        outline: none;
        min-width: 0;
        border-top-right-radius: 7px;
        border-bottom-right-radius: 7px;
    }

    .doc-block__input::placeholder {
        color: var(--text-muted, #475569);
        font-family: inherit;
        letter-spacing: 0.02em;
    }

    .doc-format-hint {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        font-size: 0.74rem;
        color: var(--text-muted, #64748b);
        transition: color 0.2s ease;
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
                <label for="invoice" style="display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; color: var(--text-color, #1e293b);">N° de documento</label>

                <!-- Bloque integrado: Tipo + Número (SUNAT) -->
                <div id="docBlock" class="doc-block">

                    <!-- Parte 1: Tipo de documento con dropdown estilizado -->
                    <div class="doc-block__type" id="docTypeContainer">
                        <!-- Select nativo oculto sincronizado -->
                        <select id="doc_type" name="doc_type" style="display:none;" onchange="aplicarFormatoDocumento(true)">
                            <option value="FACTURA" selected>Factura</option>
                            <option value="BOLETA">Boleta de venta</option>
                            <option value="NOTA_CREDITO">Nota de crédito</option>
                            <option value="NOTA_DEBITO">Nota de débito</option>
                            <option value="GUIA_REMISION">Guía de remisión</option>
                            <option value="LIBRE">Libre</option>
                        </select>

                        <!-- Botón Trigger Personalizado -->
                        <button type="button" class="doc-type-trigger" id="docTypeTrigger" aria-label="Seleccionar tipo de comprobante">
                            <span class="doc-type-trigger-left">
                                <span class="doc-type-dot" id="docTypeDot"></span>
                                <span class="doc-type-label" id="docTypeLabel">Factura</span>
                            </span>
                            <span class="doc-type-chevron">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </span>
                        </button>

                        <!-- Menú Desplegable Estilizado -->
                        <div class="doc-type-menu" id="docTypeMenu">
                            <div class="doc-type-item selected" data-value="FACTURA" onclick="seleccionarTipoDocumento('FACTURA')">
                                <div class="doc-type-item-left">
                                    <span class="doc-type-item-dot" style="background:#3b82f6; box-shadow:0 0 5px rgba(59,130,246,0.6);"></span>
                                    <span>Factura</span>
                                </div>
                                <span class="doc-type-item-badge">F001</span>
                            </div>
                            <div class="doc-type-item" data-value="BOLETA" onclick="seleccionarTipoDocumento('BOLETA')">
                                <div class="doc-type-item-left">
                                    <span class="doc-type-item-dot" style="background:#10b981; box-shadow:0 0 5px rgba(16,185,129,0.6);"></span>
                                    <span>Boleta de venta</span>
                                </div>
                                <span class="doc-type-item-badge">B001</span>
                            </div>
                            <div class="doc-type-item" data-value="NOTA_CREDITO" onclick="seleccionarTipoDocumento('NOTA_CREDITO')">
                                <div class="doc-type-item-left">
                                    <span class="doc-type-item-dot" style="background:#f59e0b; box-shadow:0 0 5px rgba(245,158,11,0.6);"></span>
                                    <span>Nota de crédito</span>
                                </div>
                                <span class="doc-type-item-badge">FC01</span>
                            </div>
                            <div class="doc-type-item" data-value="NOTA_DEBITO" onclick="seleccionarTipoDocumento('NOTA_DEBITO')">
                                <div class="doc-type-item-left">
                                    <span class="doc-type-item-dot" style="background:#ef4444; box-shadow:0 0 5px rgba(239,68,68,0.6);"></span>
                                    <span>Nota de débito</span>
                                </div>
                                <span class="doc-type-item-badge">FD01</span>
                            </div>
                            <div class="doc-type-item" data-value="GUIA_REMISION" onclick="seleccionarTipoDocumento('GUIA_REMISION')">
                                <div class="doc-type-item-left">
                                    <span class="doc-type-item-dot" style="background:#8b5cf6; box-shadow:0 0 5px rgba(139,92,246,0.6);"></span>
                                    <span>Guía de remisión</span>
                                </div>
                                <span class="doc-type-item-badge">T001</span>
                            </div>
                            <div class="doc-type-item" data-value="LIBRE" onclick="seleccionarTipoDocumento('LIBRE')">
                                <div class="doc-type-item-left">
                                    <span class="doc-type-item-dot" style="background:#94a3b8; box-shadow:0 0 5px rgba(148,163,184,0.6);"></span>
                                    <span>Libre</span>
                                </div>
                                <span class="doc-type-item-badge">Manual</span>
                            </div>
                        </div>
                    </div>

                    <!-- Parte 2: Número de documento -->
                    <input type="text" name="invoice" id="invoice" required
                        class="doc-block__input"
                        placeholder="F001-00004582"
                        oninput="formatearNumeroDocumento(this)"
                        onblur="verificarCamposIncompletos()">
                </div>

                <!-- Hint formato SUNAT -->
                <div id="docFormatHint" class="doc-format-hint">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                    <span id="docFormatHintText">Formato SUNAT: F + 3 dígitos serie - 8 dígitos correlativo</span>
                </div>
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

        // Cerrar menús de custom dropdowns que estuviesen abiertos
        document.querySelectorAll('.custom-select-container.open').forEach(c => c.classList.remove('open'));
        document.getElementById('docTypeContainer')?.classList.remove('open');

        // Reset completo del bloque de documento: volver a Factura por defecto
        const docTypeSelect = document.getElementById('doc_type');
        if (docTypeSelect) {
            docTypeSelect.value = 'FACTURA';
        }
        if (typeof actualizarDocTypeUI === 'function') {
            actualizarDocTypeUI('FACTURA');
        }

        const invoiceInput = document.getElementById('invoice');
        if (invoiceInput) {
            invoiceInput.value = 'F'; // prefijo automático de Factura
            invoiceInput.placeholder = 'F001-00004582';
        }

        const docBlock = document.getElementById('docBlock');
        if (docBlock) {
            docBlock.style.borderColor = '';
            docBlock.style.boxShadow = '';
        }

        const hintText = document.getElementById('docFormatHintText');
        if (hintText) {
            hintText.textContent = 'Formato SUNAT: F + 3 dígitos de serie - 8 dígitos (Ej: F001-00004582)';
        }
        const hintEl = document.getElementById('docFormatHint');
        if (hintEl) {
            hintEl.style.color = 'var(--text-muted, #64748b)';
        }

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
        const docType = document.getElementById('doc_type')?.value;

        const alertBox = document.getElementById('formValidationAlert');
        if (!alertBox) return [];

        const hasAnyField = fuelType || quantityVal || supplier || invoice;
        if (!hasAnyField) {
            alertBox.style.display = 'none';
            return [];
        }

        const faltantes = [];
        const isGnvCheck = (fuelType || '').toLowerCase().includes('gnv') || (fuelType || '').toLowerCase().includes('gas natural');
        const qtyLabel = isGnvCheck ? 'Cantidad de Metros Cúbicos (m³)' : 'Cantidad de Galones';
        if (!fuelType) faltantes.push('Tipo de Combustible (Ficha visual)');
        if (!quantityVal || parseFloat(quantityVal.replace(/,/g, '')) <= 0) faltantes.push(qtyLabel);
        if (!supplier) faltantes.push('Proveedor / Grifo');
        if (!invoice) faltantes.push('N° Documento');

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
        const docType = document.getElementById('doc_type')?.value;

        const faltantes = [];
        const isGnvSubmit = (fuelType || '').toLowerCase().includes('gnv') || (fuelType || '').toLowerCase().includes('gas natural');
        const qtyLabelSubmit = isGnvSubmit ? 'Cantidad de Metros Cúbicos (m³)' : 'Cantidad de Galones';
        if (!fuelType) faltantes.push('Tipo de Combustible (Ficha visual)');
        if (!quantityVal || parseFloat(quantityVal.replace(/,/g, '')) <= 0) faltantes.push(qtyLabelSubmit);
        if (!supplier) faltantes.push('Proveedor / Grifo');
        if (!invoice) faltantes.push('N° Documento');

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

    /* =========================================================
       FORMATOS DE DOCUMENTO SUNAT (legislación peruana)
       ========================================================= */
    const SUNAT_FORMATS = {
        FACTURA: {
            label: 'Factura Electrónica',
            hint: 'Formato SUNAT: F + 3 dígitos de serie - 8 dígitos (Ej: F001-00004582)',
            prefix: 'F',
            serieLen: 4,     // 1 letra + 3 dígitos
            correlativoLen: 8,
            seriePattern: /^F[0-9]{0,3}$/,
            placeholder: 'F001-00004582',
            maxRaw: 12       // 4 serie + 8 correlativo
        },
        BOLETA: {
            label: 'Boleta de Venta Electrónica',
            hint: 'Formato SUNAT: B + 3 dígitos de serie - 8 dígitos (Ej: B001-00004582)',
            prefix: 'B',
            serieLen: 4,
            correlativoLen: 8,
            seriePattern: /^B[0-9]{0,3}$/,
            placeholder: 'B001-00004582',
            maxRaw: 12
        },
        NOTA_CREDITO: {
            label: 'Nota de Crédito Electrónica',
            hint: 'Formato SUNAT: FC/BC + 2 dígitos - 8 dígitos (Ej: FC01-00004582)',
            prefix: '',
            serieLen: 4,
            correlativoLen: 8,
            placeholder: 'FC01-00004582',
            maxRaw: 12
        },
        NOTA_DEBITO: {
            label: 'Nota de Débito Electrónica',
            hint: 'Formato SUNAT: FD/BD + 2 dígitos - 8 dígitos (Ej: FD01-00004582)',
            prefix: '',
            serieLen: 4,
            correlativoLen: 8,
            placeholder: 'FD01-00004582',
            maxRaw: 12
        },
        GUIA_REMISION: {
            label: 'Guía de Remisión Electrónica',
            hint: 'Formato SUNAT: T + 3 dígitos de serie - 8 dígitos (Ej: T001-00004582)',
            prefix: 'T',
            serieLen: 4,
            correlativoLen: 8,
            placeholder: 'T001-00004582',
            maxRaw: 12
        },
        LIBRE: {
            label: 'Documento Libre',
            hint: 'Ingrese el número de documento libremente',
            prefix: '',
            serieLen: null,
            correlativoLen: null,
            placeholder: 'Número de documento',
            maxRaw: 30
        }
    };

    /* =========================================================
       METADATA Y ESTILOS VISUALES DEL TIPO DE COMPROBANTE
       ========================================================= */
    const DOC_TYPE_META = {
        FACTURA:      { label: 'Factura',          color: '#3b82f6' },
        BOLETA:       { label: 'Boleta de venta',   color: '#10b981' },
        NOTA_CREDITO: { label: 'Nota de crédito',   color: '#f59e0b' },
        NOTA_DEBITO:  { label: 'Nota de débito',    color: '#ef4444' },
        GUIA_REMISION:{ label: 'Guía de remisión',  color: '#8b5cf6' },
        LIBRE:        { label: 'Libre',             color: '#94a3b8' }
    };

    function actualizarDocTypeUI(val) {
        const meta = DOC_TYPE_META[val] || DOC_TYPE_META.FACTURA;
        const label = document.getElementById('docTypeLabel');
        const dot = document.getElementById('docTypeDot');
        if (label) label.textContent = meta.label;
        if (dot) {
            dot.style.background = meta.color;
            dot.style.boxShadow = `0 0 6px ${meta.color}99`;
        }

        document.querySelectorAll('.doc-type-item').forEach(item => {
            if (item.getAttribute('data-value') === val) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }

    function seleccionarTipoDocumento(val) {
        const select = document.getElementById('doc_type');
        const container = document.getElementById('docTypeContainer');
        if (select) {
            select.value = val;
        }
        actualizarDocTypeUI(val);
        if (container) {
            container.classList.remove('open');
        }
        aplicarFormatoDocumento(true);

        const invoiceInput = document.getElementById('invoice');
        if (invoiceInput) {
            invoiceInput.focus();
        }
    }

    function aplicarFormatoDocumento(highlight = false) {
        const docType = document.getElementById('doc_type')?.value || 'FACTURA';
        const invoiceInput = document.getElementById('invoice');
        const hintText = document.getElementById('docFormatHintText');
        const docBlock = document.getElementById('docBlock');
        if (!docType || !invoiceInput) return;

        const fmt = SUNAT_FORMATS[docType];
        if (!fmt) return;

        invoiceInput.placeholder = fmt.placeholder;
        if (hintText) hintText.textContent = fmt.hint;

        // Pre-rellenar automáticamente la letra del formato (F, B, T…)
        // para que el usuario solo tenga que escribir los dígitos
        if (fmt.prefix) {
            invoiceInput.value = fmt.prefix;
            // Mover el cursor al final del prefijo
            setTimeout(() => {
                if (document.activeElement === invoiceInput || highlight) {
                    invoiceInput.setSelectionRange(invoiceInput.value.length, invoiceInput.value.length);
                }
            }, 0);
        } else {
            invoiceInput.value = '';
        }

        const colors = {
            FACTURA:      '#3b82f6',
            BOLETA:       '#10b981',
            NOTA_CREDITO: '#f59e0b',
            NOTA_DEBITO:  '#ef4444',
            GUIA_REMISION:'#8b5cf6',
            LIBRE:        '#64748b'
        };

        if (highlight && docBlock) {
            docBlock.style.borderColor = colors[docType] || 'var(--border-color, #cbd5e1)';
            docBlock.style.boxShadow = `0 0 0 3px ${colors[docType] || '#3b82f6'}22`;
        } else if (docBlock) {
            docBlock.style.borderColor = '';
            docBlock.style.boxShadow = '';
        }

        // Colorear hint según tipo
        const hintEl = document.getElementById('docFormatHint');
        if (hintEl) {
            hintEl.style.color = highlight && colors[docType] ? `${colors[docType]}cc` : 'var(--text-muted, #64748b)';
        }

        verificarCamposIncompletos();
    }

    function formatearNumeroDocumento(input) {
        const docType = document.getElementById('doc_type')?.value || 'FACTURA';
        const fmt = SUNAT_FORMATS[docType];
        if (!fmt) return;

        if (docType === 'LIBRE') {
            // Sin restricción de formato
            input.value = input.value.slice(0, fmt.maxRaw);
            return;
        }

        // Limpiar: solo letras A-Z y dígitos, convertir a mayúsculas
        let raw = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        raw = raw.slice(0, fmt.maxRaw); // máx caracteres sin guión

        // Proteger el prefijo: si el usuario lo borrió, reponerlo automáticamente
        if (fmt.prefix && !raw.startsWith(fmt.prefix)) {
            // Quitar cualquier otra letra al inicio que no sea el prefijo esperado
            raw = fmt.prefix + raw.replace(/^[A-Z]+/, '');
            raw = raw.slice(0, fmt.maxRaw);
        }

        // Insertar guión en posición de separación serie-correlativo
        let result = '';
        if (raw.length <= fmt.serieLen) {
            result = raw;
        } else {
            result = raw.slice(0, fmt.serieLen) + '-' + raw.slice(fmt.serieLen);
        }

        input.value = result;
    }

    document.addEventListener("DOMContentLoaded", () => {
        // Inicializar formato del documento al cargar (sin highlight inicial agresivo)
        aplicarFormatoDocumento(false);
        actualizarDocTypeUI('FACTURA');

        // Toggle del dropdown de tipo de documento
        const docTrigger = document.getElementById('docTypeTrigger');
        const docContainer = document.getElementById('docTypeContainer');
        if (docTrigger && docContainer) {
            docTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                // Cerrar otros dropdowns si estuvieran abiertos
                document.querySelectorAll('.custom-select-container.open').forEach(c => c.classList.remove('open'));
                docContainer.classList.toggle('open');
            });
        }

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', (e) => {
            if (docContainer && !docContainer.contains(e.target)) {
                docContainer.classList.remove('open');
            }
        });

        // Cerrar menú con tecla Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                docContainer?.classList.remove('open');
            }
        });

        // Efectos visuales del bloque de documento al enfocar/desenfocar
        const invoiceInput = document.getElementById('invoice');
        const docBlock = document.getElementById('docBlock');
        if (invoiceInput && docBlock) {
            invoiceInput.addEventListener('focus', () => {
                const docType = document.getElementById('doc_type')?.value || 'FACTURA';
                const colors = { FACTURA:'#3b82f6', BOLETA:'#10b981', NOTA_CREDITO:'#f59e0b', NOTA_DEBITO:'#ef4444', GUIA_REMISION:'#8b5cf6', LIBRE:'#64748b' };
                const c = colors[docType] || '#3b82f6';
                docBlock.style.borderColor = c;
                docBlock.style.boxShadow = `0 0 0 3px ${c}33`;
            });
            invoiceInput.addEventListener('blur', () => {
                if (invoiceInput.value.trim() === '') {
                    docBlock.style.borderColor = '';
                    docBlock.style.boxShadow = '';
                } else {
                    const docType = document.getElementById('doc_type')?.value || 'FACTURA';
                    const colors = { FACTURA:'#3b82f6', BOLETA:'#10b981', NOTA_CREDITO:'#f59e0b', NOTA_DEBITO:'#ef4444', GUIA_REMISION:'#8b5cf6', LIBRE:'#64748b' };
                    const c = colors[docType] || '#3b82f6';
                    docBlock.style.borderColor = c;
                    docBlock.style.boxShadow = `0 0 0 2px ${c}22`;
                }
                verificarCamposIncompletos();
            });
        }

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

            function updateQuantityLabel() {
                const qLabel = document.querySelector('label[for="quantity"]');
                const qInput = document.getElementById('quantity');
                if (!qLabel || !selectEl) return;
                const isGnv = (selectEl.value || '').toLowerCase().includes('gnv') || (selectEl.value || '').toLowerCase().includes('gas natural');
                if (isGnv) {
                    qLabel.textContent = 'Cantidad de Metros Cúbicos — m³ (Máx. 10,000.00)';
                    if (qInput) qInput.placeholder = 'Ej: 5,000.00 m³';
                } else {
                    qLabel.textContent = 'Cantidad de Galones (Máx. 10,000.00)';
                    if (qInput) qInput.placeholder = 'Ej: 5,000.00';
                }
            }

            selectEl.addEventListener('change', () => {
                updatePills();
                updateQuantityLabel();
                verificarCamposIncompletos();
            });
            updatePills();
            updateQuantityLabel();
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