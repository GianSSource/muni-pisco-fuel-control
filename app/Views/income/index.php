<div class="search-page-wrapper">
    <section class="page-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
            <h1>📥 Buscador e Historial de Ingresos de Combustible</h1>
            <p class="subtitle">Gestión y control de abastecimientos y recargas de inventario municipal</p>
        </div>
        <div class="actions" style="display: flex; gap: 10px;">
            <a class="button primary" href="<?= BASE_URL ?>ingresos/nuevo">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"></path></svg>
                Registrar Nuevo Ingreso
            </a>
            <a class="button secondary" href="<?= BASE_URL ?>dashboard">← Volver al Dashboard</a>
        </div>
    </section>

    <?php if (!empty($success)): ?>
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #059669; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
            ✅ <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <!-- Resumen rápido de Ingresos -->
    <?php
        $totalGallons = 0;
        $totalRemaining = 0;
        $activeLots = 0;
        if (!empty($incomes)) {
            foreach ($incomes as $inc) {
                $totalGallons += (float)($inc['gallons'] ?? 0);
                $rem = (float)($inc['gallons_remaining'] ?? 0);
                $totalRemaining += $rem;
                if ($rem > 0) $activeLots++;
            }
        }
    ?>
    <section class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div style="background: var(--card-bg, #fff); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color, #e2e8f0);">
            <span style="font-size: 0.85rem; color: var(--text-muted, #64748b);">Total Histórico Ingresado</span>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #2563eb; margin-top: 4px;"><?= number_format($totalGallons, 2) ?> Gal</h3>
        </div>
        <div style="background: var(--card-bg, #fff); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color, #e2e8f0);">
            <span style="font-size: 0.85rem; color: var(--text-muted, #64748b);">Stock Remanente en Lotes</span>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #10b981; margin-top: 4px;"><?= number_format($totalRemaining, 2) ?> Gal</h3>
        </div>
        <div style="background: var(--card-bg, #fff); padding: 16px; border-radius: 10px; border: 1px solid var(--border-color, #e2e8f0);">
            <span style="font-size: 0.85rem; color: var(--text-muted, #64748b);">Lotes con Stock Activo</span>
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #8b5cf6; margin-top: 4px;"><?= $activeLots ?> Lotes</h3>
        </div>
    </section>

    <!-- Tarjeta del Buscador -->
    <section class="search-filter-card" style="background: var(--card-bg, #fff); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); margin-bottom: 24px;">
        <div style="display: flex; gap: 12px; align-items: center;">
            <div style="position: relative; flex: 1;">
                <input type="text" id="incomeSearchInput" placeholder="Buscar por N° Recibo, Proveedor, Tipo de Combustible..." style="width: 100%; padding: 12px 16px; border-radius: 8px; border: 1px solid var(--border-color, #cbd5e1); font-size: 0.95rem; background: var(--bg-body, #f8fafc); color: inherit;">
            </div>
        </div>
    </section>

    <!-- Tabla de Ingresos -->
    <section class="table-card" style="background: var(--card-bg, #fff); border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); padding: 20px;">
        <div class="table-wrap" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-color, #cbd5e1); text-align: left; font-size: 0.85rem; color: var(--text-muted, #64748b);">
                        <th style="padding: 12px;">N° Recibo / Lote</th>
                        <th style="padding: 12px;">Fecha y Hora</th>
                        <th style="padding: 12px;">Proveedor / Grifo</th>
                        <th style="padding: 12px;">Tipo Combustible</th>
                        <th style="padding: 12px;">Cantidad Inicial</th>
                        <th style="padding: 12px;">Stock Remanente</th>
                        <th style="padding: 12px;">Estado</th>
                    </tr>
                </thead>
                <tbody id="incomeTableBody">
                    <?php if (!empty($incomes)): ?>
                        <?php foreach ($incomes as $inc): ?>
                            <?php 
                                $rem = (float)($inc['gallons_remaining'] ?? 0);
                                $isAvailable = $rem > 0;
                            ?>
                            <tr class="income-row" style="border-bottom: 1px solid var(--border-color, #f1f5f9);">
                                <td style="padding: 14px 12px;">
                                    <span style="background: #eff6ff; color: #2563eb; font-weight: 700; padding: 4px 8px; border-radius: 6px; font-size: 0.85rem;">
                                        <?= htmlspecialchars($inc['receipt_number']) ?>
                                    </span>
                                </td>
                                <td style="padding: 14px 12px; font-size: 0.9rem; color: var(--text-muted, #64748b);">
                                    <?= date('d/m/Y H:i', strtotime($inc['created_at'])) ?>
                                </td>
                                <td style="padding: 14px 12px; font-weight: 600;">
                                    <?= htmlspecialchars($inc['supplier']) ?>
                                </td>
                                <td style="padding: 14px 12px;">
                                    <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 12px; font-size: 0.82rem; font-weight: 600;">
                                        <?= htmlspecialchars($inc['fuel_type'] ?? 'Diesel B5 S-50') ?>
                                    </span>
                                </td>
                                <td style="padding: 14px 12px; font-weight: 700; color: #2563eb;">
                                    <?= number_format((float)$inc['gallons'], 2) ?> Gal
                                </td>
                                <td style="padding: 14px 12px; font-weight: 800; color: <?= $isAvailable ? '#10b981' : '#ef4444' ?>;">
                                    <?= number_format($rem, 2) ?> Gal
                                </td>
                                <td style="padding: 14px 12px;">
                                    <span style="padding: 4px 10px; border-radius: 12px; font-size: 0.78rem; font-weight: 700; background: <?= $isAvailable ? '#d1fae5' : '#fee2e2' ?>; color: <?= $isAvailable ? '#047857' : '#b91c1c' ?>;">
                                        <?= $isAvailable ? 'Disponible' : 'Agotado' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-muted, #64748b);">
                                No hay ingresos registrados en el sistema.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const searchInput = document.getElementById("incomeSearchInput");
    if (searchInput) {
        searchInput.addEventListener("input", () => {
            const term = searchInput.value.toLowerCase().trim();
            const rows = document.querySelectorAll("#incomeTableBody tr.income-row");
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? "" : "none";
            });
        });
    }
});
</script>
