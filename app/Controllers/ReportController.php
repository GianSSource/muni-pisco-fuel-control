<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Catalog;
use App\Models\Fuel;
use App\Services\PdfReport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
            'catalogAreas' => $fuelModel->getUniqueDestinations(),
        ]);
    }

    public function apiCount(): void
    {
        $this->requireSupervisor();

        $startDate = !empty($_GET['desde']) ? trim((string) $_GET['desde']) : null;
        $endDate = !empty($_GET['hasta']) ? trim((string) $_GET['hasta']) : null;
        $vehicle = !empty($_GET['unidad']) ? trim((string) $_GET['unidad']) : null;
        $driver = !empty($_GET['responsable']) ? trim((string) $_GET['responsable']) : null;
        $area = !empty($_GET['area']) ? trim((string) $_GET['area']) : null;

        $fuelModel = new Fuel();
        $stats = $fuelModel->getDeliveriesStats([
            'date_from' => $startDate,
            'date_to' => $endDate,
            'vehicle' => $vehicle,
            'driver' => $driver,
            'destination' => $area,
        ]);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'count' => $stats['count'] ?? 0,
            'total_gallons' => $stats['total_gallons'] ?? 0
        ]);
        exit;
    }

    public function entregasPdf(): void
    {
        // Los reportes son información agregada/exportable de toda la
        // operación, por eso quedan por encima de "operador" (ver tabla de
        // roles del README): requireSupervisor() ya permite admin también.
        $this->requireSupervisor();

        $startDate = !empty($_GET['desde']) ? trim((string) $_GET['desde']) : null;
        $endDate = !empty($_GET['hasta']) ? trim((string) $_GET['hasta']) : null;

        if (empty($startDate) || empty($endDate)) {
            die("Error: Las fechas de inicio (desde) y fin (hasta) son obligatorias para exportar el reporte.");
        }

        try {
            $dStart = new \DateTime($startDate);
            $dEnd = new \DateTime($endDate);
            if ($dEnd < $dStart) {
                die("Error: La fecha de fin no puede ser anterior a la fecha de inicio.");
            }
            if ($dStart->diff($dEnd)->days > 366) {
                die("Error: El rango máximo de exportación es de 1 año (366 días). Por favor reduzca el período de fechas.");
            }
        } catch (\Exception $e) {
            die("Error: Formato de fecha inválido.");
        }

        $vehicle = !empty($_GET['unidad']) ? trim((string) $_GET['unidad']) : null;
        $driver = !empty($_GET['responsable']) ? trim((string) $_GET['responsable']) : null;
        $area = !empty($_GET['area']) ? trim((string) $_GET['area']) : null;

        $fuelModel = new Fuel();
        $deliveries = $fuelModel->getFilteredDeliveries($startDate, $endDate, $vehicle, $driver, $area);
        $deliveries = array_reverse($deliveries);

        $pdf = new PdfReport();
        $pdf->title('REPORTE DE ENTREGAS Y CONSUMO DE COMBUSTIBLE');
        $pdf->subtitle('Fecha de impresion: ' . date('d/m/Y H:i'));

        $filterText = 'Filtros: ';
        $filterText .= "Desde: " . date('d/m/Y', strtotime($startDate)) . " ";
        $filterText .= "Hasta: " . date('d/m/Y', strtotime($endDate)) . " ";
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
            'CANTIDAD',
            'OBSERVACIONES'
        ], $widths);

        $totalGalones = 0.0;
        $totalGnvM3   = 0.0;

        foreach ($deliveries as $d) {
            $cant    = (float) $d['gallons'];
            $fuelRaw = mb_strtolower((string) ($d['fuel_type'] ?? ''));
            $isGnv   = str_contains($fuelRaw, 'gnv') || str_contains($fuelRaw, 'gas natural');

            if ($isGnv) {
                $totalGnvM3 += $cant;
                $cantLabel   = number_format($cant, 2) . ' m³';
            } else {
                $totalGalones += $cant;
                $cantLabel    = number_format($cant, 2) . ' Gal';
            }

            $pdf->tableRow([
                $d['receipt_number'],
                date('d/m/Y H:i', strtotime($d['created_at'])),
                mb_substr($d['vehicle_plate'], 0, 7),
                $d['driver_name'],
                $d['destination'] ?? 'General',
                $cantLabel,
                $d['notes'] ?? '-',
            ], $widths);
        }

        $pdf->spacer();

        // Resumen de totales según tipos de combustible presentes
        $totalEntregas = count($deliveries);
        if ($totalGnvM3 > 0 && $totalGalones > 0) {
            // Mixto: hay líquidos y GNV
            $pdf->summaryBox(
                'TOTAL LÍQUIDOS: ' . number_format($totalGalones, 2) . ' Gal' .
                '   |   TOTAL GNV: ' . number_format($totalGnvM3, 2) . ' m³' .
                '   (' . $totalEntregas . ' ENTREGAS)'
            );
        } elseif ($totalGnvM3 > 0) {
            $pdf->summaryBox('TOTAL GNV DESPACHADO: ' . number_format($totalGnvM3, 2) . ' m³ (' . $totalEntregas . ' ENTREGAS)');
        } else {
            $pdf->summaryBox('TOTAL DESPACHADO: ' . number_format($totalGalones, 2) . ' GALONES (' . $totalEntregas . ' ENTREGAS)');
        }

        $pdf->output('reporte-entregas-' . date('Ymd-His') . '.pdf');
    }

    public function entregasExcel(): void
    {
        $this->requireSupervisor();

        $startDate = !empty($_GET['desde']) ? trim((string) $_GET['desde']) : null;
        $endDate = !empty($_GET['hasta']) ? trim((string) $_GET['hasta']) : null;

        if (empty($startDate) || empty($endDate)) {
            die("Error: Las fechas de inicio (desde) y fin (hasta) son obligatorias para exportar el reporte.");
        }

        try {
            $dStart = new \DateTime($startDate);
            $dEnd = new \DateTime($endDate);
            if ($dEnd < $dStart) {
                die("Error: La fecha de fin no puede ser anterior a la fecha de inicio.");
            }
            if ($dStart->diff($dEnd)->days > 366) {
                die("Error: El rango máximo de exportación es de 1 año (366 días). Por favor reduzca el período de fechas.");
            }
        } catch (\Exception $e) {
            die("Error: Formato de fecha inválido.");
        }

        $vehicle = !empty($_GET['unidad']) ? trim((string) $_GET['unidad']) : null;
        $driver = !empty($_GET['responsable']) ? trim((string) $_GET['responsable']) : null;
        $area = !empty($_GET['area']) ? trim((string) $_GET['area']) : null;

        $fuelModel = new Fuel();
        $deliveries = $fuelModel->getFilteredDeliveries($startDate, $endDate, $vehicle, $driver, $area);
        $deliveries = array_reverse($deliveries);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Entregas');

        $filterDesc = "Desde: " . date('d/m/Y', strtotime($startDate)) . " ";
        $filterDesc .= "Hasta: " . date('d/m/Y', strtotime($endDate)) . " ";
        if (!empty($area)) {
            $filterDesc .= " | Área: $area";
        }
        if (!empty($vehicle)) {
            $filterDesc .= " | Unidad: $vehicle";
        }
        if (!empty($driver)) {
            $filterDesc .= " | Conductor: $driver";
        }
        
        $sheet->setCellValue('A1', 'REPORTE DE ENTREGAS Y CONSUMO DE COMBUSTIBLE');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF0F172A'));
        
        $sheet->setCellValue('A2', 'Fecha de exportación: ' . date('d/m/Y H:i:s') . ' | ' . $filterDesc);
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A2')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        $headers = ['N° RECIBO / VALE', 'FECHA Y HORA', 'VEHÍCULO / PLACA', 'CONDUCTOR / RESPONSABLE', 'DESTINO / ÁREA', 'CANTIDAD', 'UNIDAD', 'OBSERVACIONES'];
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($col . '4', $h);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }
        // Ahora son 8 columnas (A–H)

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FF0F172A']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF1F5F9']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle('A4:H4')->applyFromArray($headerStyle);

        $row = 5;
        $totalGalones = 0.0;
        $totalGnvM3   = 0.0;

        foreach ($deliveries as $d) {
            $cant    = (float) $d['gallons'];
            $fuelRaw = mb_strtolower((string) ($d['fuel_type'] ?? ''));
            $isGnv   = str_contains($fuelRaw, 'gnv') || str_contains($fuelRaw, 'gas natural');

            if ($isGnv) {
                $totalGnvM3 += $cant;
                $unit        = 'm³';
            } else {
                $totalGalones += $cant;
                $unit         = 'Gal';
            }

            $sheet->setCellValue('A' . $row, $d['receipt_number']);
            $sheet->setCellValue('B' . $row, date('d/m/Y H:i', strtotime($d['created_at'])));
            $sheet->setCellValue('C' . $row, mb_substr($d['vehicle_plate'], 0, 7));
            $sheet->setCellValue('D' . $row, $d['driver_name']);
            $sheet->setCellValue('E' . $row, $d['destination'] ?? 'General');
            $sheet->setCellValue('F' . $row, $cant);
            $sheet->setCellValue('G' . $row, $unit);
            $sheet->setCellValue('H' . $row, $d['notes'] ?? '-');

            $row++;
        }

        if ($row > 5) {
            $dataStyle = [
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]]
            ];
            $sheet->getStyle('A5:H' . ($row - 1))->applyFromArray($dataStyle);
            $sheet->getStyle('A5:A' . ($row - 1))->getFont()->setBold(true);
            $sheet->getStyle('C5:C' . ($row - 1))->getFont()->setBold(true);
            $sheet->getStyle('F5:F' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00');
        }

        $totalGalones = round($totalGalones, 2);
        $totalGnvM3   = round($totalGnvM3, 2);

        // Fila de total líquidos (si hay)
        if ($totalGalones > 0) {
            $sheet->setCellValue('A' . $row, 'TOTAL LÍQUIDOS (Gal):');
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->setCellValue('F' . $row, $totalGalones);
            $sheet->setCellValue('G' . $row, 'Gal');
            $sheet->setCellValue('H' . $row, '');
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FF0284C7']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE0F2FE']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF93C5FD']]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ]);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $row++;
        }

        // Fila de total GNV (si hay)
        if ($totalGnvM3 > 0) {
            $sheet->setCellValue('A' . $row, 'TOTAL GNV (m³):');
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->setCellValue('F' . $row, $totalGnvM3);
            $sheet->setCellValue('G' . $row, 'm³');
            $sheet->setCellValue('H' . $row, '');
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FF0284C7']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFDBEAFE']],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF93C5FD']]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
            ]);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            $row++;
        }

        // Fila de total entregas
        $sheet->setCellValue('A' . $row, 'TOTAL ENTREGAS:');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->setCellValue('F' . $row, count($deliveries));
        $sheet->setCellValue('H' . $row, 'Entregas Registradas');

        $totalStyle = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FF0284C7']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE0F2FE']],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF93C5FD']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
        ];
        $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray($totalStyle);
        $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $filename = 'reporte-entregas-combustible-' . date('Ymd-His') . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function entregasCsv(): void
    {
        $this->requireSupervisor();

        $startDate = !empty($_GET['desde']) ? trim((string) $_GET['desde']) : null;
        $endDate = !empty($_GET['hasta']) ? trim((string) $_GET['hasta']) : null;

        if (empty($startDate) || empty($endDate)) {
            die("Error: Las fechas de inicio (desde) y fin (hasta) son obligatorias para exportar el reporte.");
        }

        try {
            $dStart = new \DateTime($startDate);
            $dEnd = new \DateTime($endDate);
            if ($dEnd < $dStart) {
                die("Error: La fecha de fin no puede ser anterior a la fecha de inicio.");
            }
            if ($dStart->diff($dEnd)->days > 366) {
                die("Error: El rango máximo de exportación es de 1 año (366 días). Por favor reduzca el período de fechas.");
            }
        } catch (\Exception $e) {
            die("Error: Formato de fecha inválido.");
        }

        $vehicle = !empty($_GET['unidad']) ? trim((string) $_GET['unidad']) : null;
        $driver = !empty($_GET['responsable']) ? trim((string) $_GET['responsable']) : null;
        $area = !empty($_GET['area']) ? trim((string) $_GET['area']) : null;

        $fuelModel = new Fuel();
        $deliveries = $fuelModel->getFilteredDeliveries($startDate, $endDate, $vehicle, $driver, $area);
        $deliveries = array_reverse($deliveries);

        $filterDesc = "Desde: " . date('d/m/Y', strtotime($startDate)) . " ";
        $filterDesc .= "Hasta: " . date('d/m/Y', strtotime($endDate)) . " ";
        if (!empty($area)) {
            $filterDesc .= " | Área: $area";
        }
        if (!empty($vehicle)) {
            $filterDesc .= " | Unidad: $vehicle";
        }
        if (!empty($driver)) {
            $filterDesc .= " | Conductor: $driver";
        }

        $filename = 'reporte-entregas-combustible-' . date('Ymd-His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        // Para que Excel lea correctamente los caracteres especiales (UTF-8 BOM)
        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        
        // Escribir título y filtros
        fputcsv($output, ['REPORTE DE ENTREGAS Y CONSUMO DE COMBUSTIBLE'], ';');
        fputcsv($output, ['Fecha de exportación: ' . date('d/m/Y H:i:s') . ' | ' . $filterDesc], ';');
        fputcsv($output, [], ';'); // Fila vacía

        // Cabeceras
        $headers = ['N° RECIBO / VALE', 'FECHA Y HORA', 'VEHÍCULO / PLACA', 'CONDUCTOR / RESPONSABLE', 'DESTINO / ÁREA', 'CANTIDAD', 'UNIDAD', 'OBSERVACIONES'];
        fputcsv($output, $headers, ';');

        $totalGalones = 0.0;
        $totalGnvM3   = 0.0;

        foreach ($deliveries as $d) {
            $cant    = (float) $d['gallons'];
            $fuelRaw = mb_strtolower((string) ($d['fuel_type'] ?? ''));
            $isGnv   = str_contains($fuelRaw, 'gnv') || str_contains($fuelRaw, 'gas natural');

            if ($isGnv) {
                $totalGnvM3 += $cant;
                $unit        = 'm³';
            } else {
                $totalGalones += $cant;
                $unit         = 'Gal';
            }

            $csvRow = [
                $d['receipt_number'],
                date('d/m/Y H:i', strtotime($d['created_at'])),
                mb_substr($d['vehicle_plate'], 0, 7),
                $d['driver_name'],
                $d['destination'] ?? 'General',
                number_format($cant, 2, '.', ''),
                $unit,
                $d['notes'] ?? '-'
            ];

            fputcsv($output, $csvRow, ';');
        }

        $totalGalones = round($totalGalones, 2);
        $totalGnvM3   = round($totalGnvM3, 2);

        fputcsv($output, [], ';'); // Fila vacía

        if ($totalGalones > 0) {
            fputcsv($output, ['TOTAL LÍQUIDOS (Gal):', '', '', '', number_format($totalGalones, 2, '.', ''), 'Gal', ''], ';');
        }
        if ($totalGnvM3 > 0) {
            fputcsv($output, ['TOTAL GNV (m³):', '', '', '', number_format($totalGnvM3, 2, '.', ''), 'm³', ''], ';');
        }
        fputcsv($output, ['TOTAL ENTREGAS:', '', '', '', count($deliveries), '', 'Entregas Registradas'], ';');

        fclose($output);
        exit;
    }
}