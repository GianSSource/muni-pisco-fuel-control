<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vale de Despacho <?= htmlspecialchars($delivery['receipt_number'] ?? '') ?></title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        body {
            background-color: #f8fafc;
            color: #0f172a;
            padding: 20px;
        }
        .receipt-container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border: 2px solid #0f172a;
            padding: 30px;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }
        .header h2 {
            font-size: 13px;
            font-weight: normal;
            color: #475569;
            margin-top: 4px;
        }
        .badge-title {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 14px;
            background: #0f172a;
            color: #ffffff;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            letter-spacing: 1px;
        }
        .details-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .details-grid th, .details-grid td {
            border: 1px solid #cbd5e1;
            padding: 10px 12px;
            font-size: 13px;
            text-align: left;
        }
        .details-grid th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            width: 35%;
        }
        .details-grid td {
            font-weight: 600;
            color: #0f172a;
        }
        .quantity-box {
            background: #eff6ff;
            border: 2px dashed #2563eb;
            padding: 14px;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 30px;
        }
        .quantity-box span {
            display: block;
            font-size: 12px;
            color: #1e40af;
            text-transform: uppercase;
            font-weight: bold;
        }
        .quantity-box strong {
            font-size: 24px;
            color: #1d4ed8;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding: 0 20px;
        }
        .signature-block {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #0f172a;
            margin-bottom: 6px;
        }
        .signature-block p {
            font-size: 12px;
            color: #334155;
            font-weight: bold;
        }
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-back {
            background: #64748b;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            margin-left: 10px;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .receipt-container {
                border: 1px solid #000;
                box-shadow: none;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            🖨️ Imprimir / Guardar PDF
        </button>
        <a href="<?= BASE_URL ?>despachos" class="btn-back">← Volver</a>
    </div>

    <div class="receipt-container">
        <div class="header">
            <h1>MUNICIPALIDAD PROVINCIAL DE PISCO</h1>
            <h2>SUBGERENCIA DE LOGÍSTICA Y MAQUINARIA MUNICIPAL</h2>
            <div class="badge-title">VALE DE DESPACHO DE COMBUSTIBLE</div>
        </div>

        <table class="details-grid">
            <tr>
                <th>N° CORRELATIVO</th>
                <td style="color: #2563eb; font-size: 15px; font-weight: 800;"><?= htmlspecialchars($delivery['receipt_number'] ?? 'VAL-2026-0000') ?></td>
            </tr>
            <tr>
                <th>FECHA Y HORA</th>
                <td><?= date('d/m/Y H:i:s', strtotime($delivery['created_at'] ?? 'now')) ?></td>
            </tr>
            <tr>
                <th>PLACA / VEHÍCULO</th>
                <td style="text-transform: uppercase; font-size: 14px;"><?= htmlspecialchars($delivery['vehicle_plate'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>CONDUCTOR / RESPONSABLE</th>
                <td><?= htmlspecialchars($delivery['driver_name'] ?? 'N/A') ?></td>
            </tr>
            <tr>
                <th>TIPO DE COMBUSTIBLE</th>
                <td><?= htmlspecialchars($delivery['fuel_type'] ?? 'Diesel B5 S-50') ?></td>
            </tr>
            <tr>
                <th>DESTINO / ÁREA</th>
                <td><?= htmlspecialchars($delivery['destination'] ?? 'Despacho General') ?></td>
            </tr>
            <tr>
                <th>ESTADO DEL VALE</th>
                <td><?= htmlspecialchars($delivery['status'] ?? 'Completado') ?></td>
            </tr>
        </table>

        <div class="quantity-box">
            <span>CANTIDAD AUTORIZADA DE COMBUSTIBLE</span>
            <strong><?= number_format((float)($delivery['gallons'] ?? 0), 2) ?> GALONES</strong>
        </div>

        <div class="signatures">
            <div class="signature-block">
                <div class="signature-line"></div>
                <p>FIRMA DEL CONDUCTOR</p>
                <span style="font-size: 11px; color: #64748b;">Recibí Conforme</span>
            </div>
            <div class="signature-block">
                <div class="signature-line"></div>
                <p>DESPACHADOR DE COMBUSTIBLE</p>
                <span style="font-size: 11px; color: #64748b;">Entregado y Registrado</span>
            </div>
        </div>
    </div>

    <script>
        // Disparar impresión automática al abrir la vista si se solicita
        <?php if (!empty($_GET['auto'])): ?>
            window.onload = function() {
                window.print();
            };
        <?php endif; ?>
    </script>
</body>
</html>
