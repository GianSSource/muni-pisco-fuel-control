<?php
declare(strict_types=1);

$totalGallons = (float) ($delivery['gallons'] ?? 0);

if (!function_exists('numberToWordsEs')) {
  function numberToWordsEs(int $num): string
  {
    $units = [
      '',
      'un',
      'dos',
      'tres',
      'cuatro',
      'cinco',
      'seis',
      'siete',
      'ocho',
      'nueve',
      'diez',
      'once',
      'doce',
      'trece',
      'catorce',
      'quince',
      'dieciséis',
      'diecisiete',
      'dieciocho',
      'diecinueve'
    ];
    $tens = ['', '', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
    $hundreds = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

    if ($num === 0)
      return 'cero';
    if ($num === 100)
      return 'cien';

    $res = '';

    if ($num >= 1000) {
      $k = intdiv($num, 1000);
      $num %= 1000;
      $res .= ($k === 1 ? 'mil ' : numberToWordsEs($k) . ' mil ');
    }

    if ($num >= 100) {
      $h = intdiv($num, 100);
      $num %= 100;
      $res .= $hundreds[$h] . ' ';
    }

    if ($num >= 20) {
      $t = intdiv($num, 10);
      $u = $num % 10;
      if ($t === 2 && $u > 0) {
        $special20 = ['veintiuno', 'veintidós', 'veintitrés', 'veinticuatro', 'veinticinco', 'veintiséis', 'veintisiete', 'veintiocho', 'veintinueve'];
        $res .= $special20[$u - 1] . ' ';
      } else {
        $res .= $tens[$t] . ($u > 0 ? ' y ' . $units[$u] : '') . ' ';
      }
    } elseif ($num > 0) {
      $res .= $units[$num] . ' ';
    }

    return trim($res);
  }
}

if (!function_exists('gallonsToTextEs')) {
  function gallonsToTextEs(float $gallons): string
  {
    $integerPart = (int) floor($gallons);
    $cents = (int) round(($gallons - $integerPart) * 100);
    $words = numberToWordsEs($integerPart);
    $unitWord = ($integerPart === 1) ? 'galón' : 'galones';
    return ucfirst($words) . ' ' . $unitWord . ' con ' . sprintf('%02d', $cents) . '/100';
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Vale <?= htmlspecialchars($delivery['receipt_number'] ?? 'Despacho') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --ink: #18212d;
      --muted: #5a6676;
      --line: #dce1e8;
      --soft: #f5f7fa;
      --brand: #14375e;
      --brand-2: #1f5a96;
      --brand-soft: #e8eff8;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      background: #e6ebf1;
      font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      color: var(--ink);
      font-variant-numeric: tabular-nums;
      line-height: 1.4;
      -webkit-font-smoothing: antialiased;
    }

    .bar {
      width: 100%;
      background: transparent;
      padding: 20px 16px 14px;
      box-sizing: border-box;
    }

    .bar-inner {
      max-width: 780px;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
    }

    .bar-back {
      font-family: inherit;
      font-size: 14px;
      font-weight: 600;
      color: #334155;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 7px 12px;
      border-radius: 8px;
      transition: all 0.15s ease;
    }

    .bar-back:hover {
      color: #1d4ed8;
      background: rgba(0, 0, 0, 0.05);
    }

    .bar-actions {
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }

    .bar-btn {
      font-family: inherit;
      font-size: 13.5px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 8px 16px;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s ease;
      line-height: 1.4;
      white-space: nowrap;
    }

    .bar-btn svg,
    .bar-back svg {
      width: 15px;
      height: 15px;
      flex-shrink: 0;
    }

    /* Botones outline en fondo claro (Guardar PDF e Imprimir) */
    .bar-btn-outline {
      background: #ffffff;
      color: #1e293b;
      border: 1px solid #cbd5e1;
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .bar-btn-outline:hover {
      background: #f8fafc;
      border-color: #94a3b8;
      color: #0f172a;
    }

    .bar-btn-outline:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    /* Botón azul destacado (Nuevo despacho) */
    .bar-btn-primary {
      background: #2563eb;
      color: #ffffff;
      border: 1px solid #2563eb;
      box-shadow: 0 1px 3px rgba(37, 99, 235, 0.25);
    }

    .bar-btn-primary:hover {
      background: #1d4ed8;
      border-color: #1d4ed8;
      color: #ffffff;
    }

    .bar-back:focus-visible,
    .bar-btn:focus-visible {
      outline: 2px solid #3b82f6;
      outline-offset: 2px;
    }

    @media (max-width: 640px) {
      .bar {
        padding: 14px 16px 8px;
      }
      .bar-inner {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
      }
      .bar-actions {
        flex-wrap: wrap;
        justify-content: flex-start;
      }
    }

    .vale {
      width: 100%;
      max-width: 780px;
      margin: 0 auto 32px;
      background: #fff;
      border-radius: 4px;
      padding: 0 0 28px;
      box-shadow: 0 1px 2px rgba(20, 40, 70, .12), 0 10px 30px rgba(20, 40, 70, .08);
      overflow: hidden;
      page-break-inside: avoid;
      break-inside: avoid;
    }

    .top {
      height: 8px;
      background: linear-gradient(90deg, var(--brand), var(--brand-2));
    }

    .in {
      padding: 0 44px;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 24px;
      padding: 24px 0 20px;
      border-bottom: 1px solid var(--line);
    }

    .org {
      display: flex;
      align-items: center;
      gap: 14px;
      min-width: 0;
    }

    .crest {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: var(--brand);
      color: #fff;
      display: grid;
      place-items: center;
      font-weight: 700;
      font-size: 15px;
      letter-spacing: .04em;
      flex: none;
    }

    header h1 {
      margin: 0;
      font-size: 17px;
      line-height: 1.2;
      font-weight: 700;
      color: var(--ink);
    }

    header .area {
      margin: 3px 0 0;
      color: var(--muted);
      font-size: 12.5px;
    }

    .num {
      text-align: right;
      flex: none;
    }

    .num .t {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      letter-spacing: .02em;
    }

    .num strong {
      display: block;
      font-size: 24px;
      color: var(--brand);
      line-height: 1.15;
      margin: 2px 0;
    }

    .num time {
      font-size: 12.5px;
      color: var(--muted);
    }

    .doc-title {
      margin: 20px 0 4px;
      font-size: 13px;
      font-weight: 700;
      color: var(--brand);
      letter-spacing: .06em;
      text-transform: uppercase;
    }

    section {
      padding: 16px 0;
      border-bottom: 1px solid var(--line);
      break-inside: avoid;
      page-break-inside: avoid;
    }

    h2 {
      margin: 0 0 10px;
      font-size: 13px;
      font-weight: 700;
      color: var(--brand);
      display: flex;
      align-items: center;
      gap: 8px;
    }

    h2::before {
      content: "";
      width: 4px;
      height: 14px;
      border-radius: 2px;
      background: var(--brand-2);
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 12px 20px;
      margin: 0;
    }

    dt {
      font-size: 12px;
      color: var(--muted);
      margin-bottom: 2px;
    }

    dd {
      margin: 0;
      font-size: 14.5px;
      font-weight: 600;
      overflow-wrap: anywhere;
    }

    dd.none {
      font-weight: 400;
      font-style: italic;
      color: var(--muted);
    }

    .qty {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      background: var(--brand-soft);
      border-left: 6px solid var(--brand);
      border-radius: 0 6px 6px 0;
      padding: 14px 20px;
      margin-top: 12px;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    .qty small {
      display: block;
      font-size: 12px;
      color: var(--muted);
      margin-bottom: 2px;
    }

    .qty .words {
      font-size: 13.5px;
      font-weight: 600;
      color: var(--brand);
    }

    .qty .n {
      font-size: 36px;
      font-weight: 700;
      color: var(--brand);
      line-height: 1;
      white-space: nowrap;
    }

    .qty .n span {
      font-size: 16px;
      font-weight: 600;
    }

    .tag {
      display: inline-block;
      font-size: 12.5px;
      font-weight: 600;
      padding: 3px 12px;
      border-radius: 999px;
      background: var(--brand-soft);
      color: var(--brand);
      border: 1px solid #c5d6ea;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13.5px;
    }

    th {
      text-align: left;
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      padding: 0 0 7px;
    }

    td {
      padding: 8px 0;
      border-top: 1px solid var(--line);
      vertical-align: middle;
    }

    th:last-child,
    td:last-child {
      text-align: right;
    }

    .cant {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 12px;
    }

    .meter {
      width: 90px;
      height: 6px;
      border-radius: 3px;
      background: var(--line);
      overflow: hidden;
      flex: none;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    .meter i {
      display: block;
      height: 100%;
      background: var(--brand-2);
    }

    .pct {
      width: 34px;
      font-size: 12px;
      color: var(--muted);
      text-align: right;
    }

    .cant b {
      min-width: 68px;
      text-align: right;
    }

    tfoot td {
      border-top: 2px solid var(--ink);
      font-weight: 700;
      padding-top: 10px;
    }

    .firmas {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 56px;
      padding: 42px 0 0;
      border: 0;
      break-inside: avoid;
      page-break-inside: avoid;
    }

    .firma {
      border-top: 1px solid var(--ink);
      padding-top: 8px;
      text-align: center;
      font-size: 13px;
    }

    .firma b {
      display: block;
    }

    .firma span {
      display: block;
      color: var(--muted);
      font-size: 12px;
    }

    .aviso {
      margin: 18px 44px 0;
      padding-top: 10px;
      border-top: 1px dashed var(--line);
      display: flex;
      justify-content: space-between;
      gap: 16px;
      font-size: 11px;
      color: var(--muted);
    }

    .bar button:disabled {
      opacity: .6;
      cursor: wait;
    }

    @media (max-width: 620px) {
      .in {
        padding: 0 20px;
      }

      .aviso {
        margin: 16px 20px 0;
        flex-direction: column;
        gap: 4px;
      }

      header {
        flex-direction: column;
        align-items: flex-start;
      }

      .num {
        text-align: left;
      }

      .grid {
        grid-template-columns: 1fr 1fr;
      }

      .qty {
        flex-direction: column;
        align-items: flex-start;
      }

      .firmas {
        gap: 24px;
      }

      .meter {
        display: none;
      }
    }

    @media print {
      @page {
        size: A4 portrait;
        margin: 10mm 12mm;
      }

      body {
        background: #fff;
        margin: 0;
        padding: 0;
      }

      .bar {
        display: none !important;
      }

      .vale {
        box-shadow: none;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        border-radius: 0;
        break-inside: avoid;
        page-break-inside: avoid;
      }

      /* Preservar colores originales para que el desplegable del
         navegador (Color / Blanco y negro) controle la salida.
         Si el usuario elige "Color" → se imprime a color.
         Si elige "Blanco y negro" → el navegador convierte a escala de grises. */
      .top {
        background: linear-gradient(90deg, var(--brand), var(--brand-2));
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .crest {
        background: var(--brand);
        color: #fff;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .tag {
        background: var(--brand-soft);
        color: var(--brand);
        border: 1px solid #c5d6ea;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .qty {
        background: var(--brand-soft);
        border-left: 6px solid var(--brand);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .meter {
        background: var(--line);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      .meter i {
        background: var(--brand-2);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }

      h2::before {
        background: var(--brand-2);
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
</head>

<body>

  <div class="bar">
    <div class="bar-inner">
      <a href="<?= BASE_URL ?>despachos" class="bar-back" <?= empty($_GET['auto']) ? 'onclick="if (window.history.length > 1) { window.history.back(); return false; }"' : '' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <line x1="19" y1="12" x2="5" y2="12"></line>
          <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        <span>Volver a despachos</span>
      </a>

      <div class="bar-actions">
        <button id="btn-pdf" class="bar-btn bar-btn-outline" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
          </svg>
          <span id="pdf-lbl">Guardar PDF</span>
        </button>

        <button id="btn-print" class="bar-btn bar-btn-outline" type="button">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="6 9 6 2 18 2 18 9"></polyline>
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
            <rect x="6" y="14" width="12" height="8"></rect>
          </svg>
          <span>Imprimir</span>
        </button>

        <a href="<?= BASE_URL ?>despachos/nuevo" class="bar-btn bar-btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
          </svg>
          <span>Nuevo despacho</span>
        </a>
      </div>
    </div>
  </div>

  <article class="vale">
    <div class="top"></div>
    <div class="in">

      <header>
        <div class="org">
          <div class="crest" aria-hidden="true">MPP</div>
          <div>
            <h1>Municipalidad Provincial de Pisco</h1>
            <p class="area">Subgerencia de Logística y Maquinaria Municipal</p>
          </div>
        </div>
        <div class="num">
          <span class="t">N.° de vale</span>
          <strong><?= htmlspecialchars($delivery['receipt_number'] ?? 'VAL-000000') ?></strong>
          <time datetime="<?= htmlspecialchars($delivery['created_at'] ?? '') ?>">
            <?= date('d/m/Y, H:i:s', strtotime($delivery['created_at'] ?? 'now')) ?>
          </time>
        </div>
      </header>

      <p class="doc-title">Vale de despacho de combustible</p>

      <section>
        <h2>Vehículo</h2>
        <dl class="grid">
          <div>
            <dt>Placa</dt>
            <dd><?= htmlspecialchars($delivery['vehicle_plate'] ?? '-') ?></dd>
          </div>
          <div>
            <dt>Código de unidad</dt>
            <dd><?= htmlspecialchars($delivery['vehicle_unit_code'] ?? '-') ?></dd>
          </div>
          <div>
            <dt>Tipo de vehículo</dt>
            <dd><?= htmlspecialchars($delivery['vehicle_type_name'] ?? 'Sin tipo') ?></dd>
          </div>
        </dl>
      </section>

      <section>
        <h2>Responsable y destino</h2>
        <dl class="grid">
          <div>
            <dt>Conductor</dt>
            <dd><?= htmlspecialchars($delivery['driver_name'] ?? '-') ?></dd>
          </div>
          <div>
            <dt>Destino / área</dt>
            <dd><?= htmlspecialchars($delivery['destination'] ?? 'Despacho General') ?></dd>
          </div>
          <div>
            <dt>Observaciones</dt>
            <?php if (!empty($delivery['notes'])): ?>
              <dd><?= htmlspecialchars($delivery['notes']) ?></dd>
            <?php else: ?>
              <dd class="none">Sin observaciones</dd>
            <?php endif; ?>
          </div>
        </dl>
      </section>

      <section>
        <h2>Combustible autorizado</h2>
        <span class="tag"><?= htmlspecialchars($delivery['fuel_type'] ?? 'Diesel B5 S-50') ?></span>
        <div class="qty">
          <div>
            <small>Cantidad autorizada</small>
            <span class="words"><?= gallonsToTextEs($totalGallons) ?></span>
          </div>
          <div class="n"><?= number_format($totalGallons, 2) ?> <span>galones</span></div>
        </div>
      </section>

      <section>
        <h2>Lotes de donde se tomó</h2>
        <table>
          <thead>
            <tr>
              <th>Lote</th>
              <th>Proveedor</th>
              <th>Cantidad</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($lotAllocations)): ?>
              <?php foreach ($lotAllocations as $alloc): ?>
                <?php
                $taken = (float) ($alloc['gallons_taken'] ?? 0);
                $pct = $totalGallons > 0 ? ($taken / $totalGallons) * 100 : 0;
                ?>
                <tr>
                  <td><b><?= htmlspecialchars($alloc['lot_receipt'] ?? 'Lote') ?></b></td>
                  <td><?= htmlspecialchars($alloc['supplier'] ?? '-') ?></td>
                  <td>
                    <div class="cant">
                      <span class="meter"><i style="width: <?= min(100, max(2, round($pct))) ?>%"></i></span>
                      <span class="pct"><?= round($pct) ?>%</span>
                      <b><?= number_format($taken, 2) ?> gal</b>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td><b>Lote Único</b></td>
                <td>Proveedor Municipal</td>
                <td>
                  <div class="cant">
                    <span class="meter"><i style="width: 100%"></i></span>
                    <span class="pct">100%</span>
                    <b><?= number_format($totalGallons, 2) ?> gal</b>
                  </div>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2">Total despachado</td>
              <td>
                <div class="cant"><b><?= number_format($totalGallons, 2) ?> gal</b></div>
              </td>
            </tr>
          </tfoot>
        </table>
      </section>

      <section class="firmas">
        <div class="firma">
          <b>Conductor</b>
          <span>Recibí conforme</span>
        </div>
        <div class="firma">
          <b>Despachador de combustible</b>
          <span>Entregado y registrado</span>
        </div>
      </section>

    </div>

    <div class="aviso">
      <span>Documento generado por el sistema de control de combustible.</span>
      <span>Vale válido solo con ambas firmas.</span>
    </div>
  </article>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    const NUM = '<?= htmlspecialchars($delivery['receipt_number'] ?? 'Despacho') ?>';
    const lbl = document.getElementById('pdf-lbl');
    const btnPdf = document.getElementById('btn-pdf');

    document.getElementById('btn-print').addEventListener('click', () => window.print());

    btnPdf.addEventListener('click', async () => {
      if (typeof html2pdf === 'undefined') {
        alert('No se pudo cargar el generador de PDF. Revisa tu conexión e inténtalo de nuevo.');
        return;
      }

      const element = document.querySelector('.vale');
      btnPdf.disabled = true;
      lbl.textContent = 'Generando…';

      // Desplazar al tope para asegurar que no haya desfase por scroll
      window.scrollTo(0, 0);

      const prevShadow = element.style.boxShadow;
      element.style.boxShadow = 'none';

      try {
        const opt = {
          margin: [8, 8, 8, 8],
          filename: 'Vale_' + NUM + '.pdf',
          image: { type: 'jpeg', quality: 0.98 },
          html2canvas: {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
          },
          jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        await html2pdf().set(opt).from(element).save();
      } catch (err) {
        console.error('Error al generar PDF:', err);
        window.print();
      } finally {
        element.style.boxShadow = prevShadow;
        btnPdf.disabled = false;
        lbl.textContent = 'Guardar PDF';
      }
    });

    <?php if (!empty($_GET['auto'])): ?>
      window.addEventListener('DOMContentLoaded', () => {
        window.print();
      });
    <?php endif; ?>
  </script>
</body>

</html>