<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Catalog;
use App\Models\Fuel;
use App\Services\PdfReport;

final class ReportController extends Controller
{
    /**
     * Página propia de Reportes: contiene el formulario de filtros y
     * exportación (PDF/Excel) que antes vivía embebido en el Dashboard.
     * Mismo control de acceso que las exportaciones: admin o supervisor.
     */
    public function index(): void
    {
        $this->requireSupervisor();

        $fuelModel = new Fuel();

        $this->view('reports/index', [
            'title' => 'Reportes',
            'uniquePlates' => $fuelModel->getUniqueVehicles(),
            'uniqueDrivers' => $fuelModel->getUniqueDrivers(),
            'catalogAreas' => (new Catalog())->getAreas(),
        ]);
    }

    public function entregasPdf(): void
    {
        // Los reportes son información agregada/exportable de toda la
        // operación, por eso quedan por encima de "operador" (ver tabla de
        // roles del README): requireSupervisor() ya permite admin también.
        $this->requireSupervisor();

        $startDate = !empty($_GET['desde']) ? trim((string) $_GET['desde']) : null;
        $endDate = !empty($_GET['hasta']) ? trim((string) $_GET['hasta']) : null;
        $vehicle = !empty($_GET['unidad']) ? trim((string) $_GET['unidad']) : null;
        $driver = !empty($_GET['responsable']) ? trim((string) $_GET['responsable']) : null;
        $area = !empty($_GET['area']) ? trim((string) $_GET['area']) : null;

        $fuelModel = new Fuel();
        $deliveries = $fuelModel->getFilteredDeliveries($startDate, $endDate, $vehicle, $driver, $area);

        $pdf = new PdfReport();
        $pdf->title('REPORTE DE ENTREGAS Y CONSUMO DE COMBUSTIBLE');
        $pdf->subtitle('Fecha de impresion: ' . date('d/m/Y H:i'));

        $filterText = 'Filtros: ';
        $filterText .= !empty($startDate) ? "Desde: $startDate " : 'Desde: Inicio ';
        $filterText .= !empty($endDate) ? "Hasta: $endDate " : 'Hasta: Hoy ';
        if (!empty($area)) {
            $filterText .= "│ Área: $area ";
        }
        if (!empty($vehicle)) {
            $filterText .= "│ Unidad: $vehicle ";
        }
        if (!empty($driver)) {
            $filterText .= "│ Responsable: $driver";
        }
        $pdf->subtitle($filterText);
        $pdf->spacer();

        // Anchos de columnas ajustados: [65, 85, 55, 100, 90, 55, 80]
        $widths = [65, 85, 55, 100, 90, 55, 80];

        // Encabezados de la tabla con fondo y bordes
        $pdf->tableHeader([
            'N° RECIBO',
            'FECHA Y HORA',
            'PLACA / UNID',
            'CONDUCTOR / RESP.',
            'DESTINO / AREA',
            'CANT. (GAL)',
            'OBSERVACIONES'
        ], $widths);

        $totalGalones = 0;

        foreach ($deliveries as $d) {
            $cant = (float) $d['gallons'];
            $totalGalones += $cant;

            $pdf->tableRow([
                $d['receipt_number'],
                date('d/m/Y H:i', strtotime($d['created_at'])),
                mb_substr($d['vehicle_plate'], 0, 7),
                $d['driver_name'],
                $d['destination'] ?? 'General',
                number_format($cant, 2) . ' Gal',
                $d['notes'] ?? '-',
            ], $widths);
        }

        $pdf->spacer();
        $pdf->summaryBox('TOTAL DESPACHADO: ' . number_format($totalGalones, 2) . ' GALONES (' . count($deliveries) . ' ENTREGAS)');

        $pdf->output('reporte-entregas-' . date('Ymd-His') . '.pdf');
    }

    public function entregasCsv(): void
    {
        $this->requireSupervisor();

        $startDate = !empty($_GET['desde']) ? trim((string) $_GET['desde']) : null;
        $endDate = !empty($_GET['hasta']) ? trim((string) $_GET['hasta']) : null;
        $vehicle = !empty($_GET['unidad']) ? trim((string) $_GET['unidad']) : null;
        $driver = !empty($_GET['responsable']) ? trim((string) $_GET['responsable']) : null;
        $area = !empty($_GET['area']) ? trim((string) $_GET['area']) : null;

        $fuelModel = new Fuel();
        $deliveries = $fuelModel->getFilteredDeliveries($startDate, $endDate, $vehicle, $driver, $area);

        $filename = 'reporte-entregas-combustible-' . date('Ymd-His') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $totalGalones = 0;
        foreach ($deliveries as $d) {
            $totalGalones += (float) $d['gallons'];
        }

        $filterDesc = !empty($startDate) ? "Desde: $startDate " : 'Desde: Inicio ';
        $filterDesc .= !empty($endDate) ? "Hasta: $endDate " : 'Hasta: Hoy ';
        if (!empty($area)) {
            $filterDesc .= " | Área: $area";
        }
        if (!empty($vehicle)) {
            $filterDesc .= " | Unidad: $vehicle";
        }
        if (!empty($driver)) {
            $filterDesc .= " | Conductor: $driver";
        }

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta charset="utf-8"><style>';
        echo 'body { font-family: Arial, Helvetica, sans-serif; font-size: 13px; margin: 20px; }';
        echo 'table { border-collapse: collapse; width: 100%; margin-top: 15px; }';
        echo 'th { background-color: #f1f5f9; color: #0f172a; font-weight: bold; border: 1px solid #cbd5e1; padding: 12px 10px; font-size: 13px; text-align: left; }';
        echo 'td { border: 1px solid #cbd5e1; padding: 10px; color: #334155; font-size: 13px; }';
        echo '.tr-even { background-color: #f8fafc; }';
        echo '.receipt-code { font-weight: bold; color: #2563eb; background-color: #eff6ff; padding: 4px 8px; border-radius: 4px; display: inline-block; }';
        echo '.status-tag { color: #059669; font-weight: bold; background-color: #d1fae5; padding: 4px 10px; border-radius: 12px; display: inline-block; }';
        echo '.total-row td { background-color: #e0f2fe; color: #0369a1; font-weight: bold; font-size: 15px; padding: 14px 10px; border: 2px solid #93c5fd; }';
        echo '</style></head><body>';

        echo '<h2 style="color: #0f172a; font-size: 20px; margin-bottom: 4px;">REPORTE DE ENTREGAS Y CONSUMO DE COMBUSTIBLE</h2>';
        echo '<p style="color: #64748b; font-size: 13px; margin-top: 0; margin-bottom: 20px;">Fecha de exportación: <strong>' . date('d/m/Y H:i:s') . '</strong> | ' . htmlspecialchars($filterDesc) . '</p>';

        echo '<table>';
        echo '<colgroup>';
        echo '<col width="160">';
        echo '<col width="160">';
        echo '<col width="140">';
        echo '<col width="220">';
        echo '<col width="200">';
        echo '<col width="160">';
        echo '<col width="140">';
        echo '<col width="220">';
        echo '</colgroup>';

        echo '<thead>';
        echo '<tr>';
        echo '<th>N° RECIBO / VALE</th>';
        echo '<th>FECHA Y HORA</th>';
        echo '<th>VEHÍCULO / PLACA</th>';
        echo '<th>CONDUCTOR / RESPONSABLE</th>';
        echo '<th>DESTINO / ÁREA</th>';
        echo '<th style="text-align: right;">CANTIDAD (GAL)</th>';
        echo '<th style="text-align: center;">ESTADO</th>';
        echo '<th>OBSERVACIONES</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        $i = 0;
        foreach ($deliveries as $d) {
            $cant = (float) $d['gallons'];
            $rowClass = ($i++ % 2 === 0) ? '' : 'tr-even';

            echo '<tr class="' . $rowClass . '">';
            echo '<td><span class="receipt-code">' . htmlspecialchars($d['receipt_number']) . '</span></td>';
            echo '<td>' . date('d/m/Y H:i', strtotime($d['created_at'])) . '</td>';
            echo '<td><strong>' . htmlspecialchars(mb_substr($d['vehicle_plate'], 0, 7)) . '</strong></td>';
            echo '<td>' . htmlspecialchars($d['driver_name']) . '</td>';
            echo '<td>' . htmlspecialchars($d['destination'] ?? 'General') . '</td>';
            echo '<td style="text-align: right;"><strong>' . number_format($cant, 2) . ' Gal</strong></td>';
            echo '<td style="text-align: center;"><span class="status-tag">' . htmlspecialchars($d['status'] ?? 'Completado') . '</span></td>';
            echo '<td>' . htmlspecialchars($d['notes'] ?? '') . '</td>';
            echo '</tr>';
        }

        // Cuadro de Resumen Final Destacado en Grande
        echo '<tr class="total-row">';
        echo '<td colspan="4" style="text-align: left; font-size: 15px; letter-spacing: 0.5px;">TOTAL GENERAL DESPACHADO:</td>';
        echo '<td style="text-align: right; font-size: 16px; font-weight: bold; color: #0284c7;">' . number_format($totalGalones, 2) . ' Galones</td>';
        echo '<td colspan="3" style="text-align: center; font-size: 15px; font-weight: bold; color: #0369a1;">' . count($deliveries) . ' Entregas Registradas</td>';
        echo '</tr>';

        echo '</tbody></table>';
        echo '</body></html>';
        exit;
    }
}