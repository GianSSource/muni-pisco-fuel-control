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
        $deliveries = array_reverse($deliveries);

        $pdf = new PdfReport();
        $pdf->title('REPORTE DE ENTREGAS Y CONSUMO DE COMBUSTIBLE');
        $pdf->subtitle('Fecha de impresion: ' . date('d/m/Y H:i'));

        $filterText = 'Filtros: ';
        $filterText .= !empty($startDate) ? "Desde: " . date('d/m/Y', strtotime($startDate)) . " " : 'Desde: Inicio ';
        $filterText .= !empty($endDate) ? "Hasta: " . date('d/m/Y', strtotime($endDate)) . " " : 'Hasta: Hoy ';
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
        $deliveries = array_reverse($deliveries);

        // Se exporta como .xls, pero el contenido es XML Spreadsheet 2003
        // oficial de Microsoft. Esto retiene el estilo visual (colores, negritas)
        // SIN activar el recuadro de advertencia de formato.
        $filename = 'reporte-entregas-combustible-' . date('Ymd-His') . '.xls';

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $totalGalones = 0;
        foreach ($deliveries as $d) {
            $totalGalones += (float) $d['gallons'];
        }

        $filterDesc = !empty($startDate) ? "Desde: " . date('d/m/Y', strtotime($startDate)) . " " : 'Desde: Inicio ';
        $filterDesc .= !empty($endDate) ? "Hasta: " . date('d/m/Y', strtotime($endDate)) . " " : 'Hasta: Hoy ';
        if (!empty($area)) {
            $filterDesc .= " | Área: $area";
        }
        if (!empty($vehicle)) {
            $filterDesc .= " | Unidad: $vehicle";
        }
        if (!empty($driver)) {
            $filterDesc .= " | Conductor: $driver";
        }
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
        $xml .= 'xmlns:o="urn:schemas-microsoft-com:office:office" ';
        $xml .= 'xmlns:x="urn:schemas-microsoft-com:office:excel" ';
        $xml .= 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" ';
        $xml .= 'xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        
        // Estilos CSS traducidos a XML Spreadsheet
        $xml .= '<Styles>' . "\n";
        $xml .= ' <Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Bottom"/><Borders/><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/><Interior/><NumberFormat/><Protection/></Style>' . "\n";
        $xml .= ' <Style ss:ID="Header1"><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="14" ss:Color="#0F172A" ss:Bold="1"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="Header2"><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="10" ss:Color="#64748B"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="Th"><Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="10" ss:Color="#0F172A" ss:Bold="1"/><Interior ss:Color="#F1F5F9" ss:Pattern="Solid"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="Td"><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="10" ss:Color="#334155"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdBold"><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="10" ss:Color="#334155" ss:Bold="1"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdRight"><Alignment ss:Horizontal="Right" ss:Vertical="Bottom"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="10" ss:Color="#334155" ss:Bold="1"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="TdCenter"><Alignment ss:Horizontal="Center" ss:Vertical="Bottom"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#CBD5E1"/></Borders><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="10" ss:Color="#059669" ss:Bold="1"/></Style>' . "\n";
        $xml .= ' <Style ss:ID="Total"><Alignment ss:Horizontal="Right" ss:Vertical="Bottom"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#93C5FD"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#93C5FD"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#93C5FD"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="2" ss:Color="#93C5FD"/></Borders><Font ss:FontName="Arial" x:Family="Swiss" ss:Size="11" ss:Color="#0284C7" ss:Bold="1"/><Interior ss:Color="#E0F2FE" ss:Pattern="Solid"/></Style>' . "\n";
        $xml .= '</Styles>' . "\n";
        
        $xml .= '<Worksheet ss:Name="Entregas">' . "\n";
        $xml .= ' <Table>' . "\n";
        
        // Anchos de columna
        $xml .= '  <Column ss:Width="120"/>' . "\n";
        $xml .= '  <Column ss:Width="120"/>' . "\n";
        $xml .= '  <Column ss:Width="100"/>' . "\n";
        $xml .= '  <Column ss:Width="160"/>' . "\n";
        $xml .= '  <Column ss:Width="150"/>' . "\n";
        $xml .= '  <Column ss:Width="100"/>' . "\n";
        $xml .= '  <Column ss:Width="100"/>' . "\n";
        $xml .= '  <Column ss:Width="160"/>' . "\n";

        // Títulos
        $xml .= '  <Row ss:Height="20">' . "\n";
        $xml .= '   <Cell ss:StyleID="Header1"><Data ss:Type="String">REPORTE DE ENTREGAS Y CONSUMO DE COMBUSTIBLE</Data></Cell>' . "\n";
        $xml .= '  </Row>' . "\n";
        
        $xml .= '  <Row ss:Height="15">' . "\n";
        $xml .= '   <Cell ss:StyleID="Header2"><Data ss:Type="String">Fecha de exportación: ' . date('d/m/Y H:i:s') . ' | ' . htmlspecialchars($filterDesc) . '</Data></Cell>' . "\n";
        $xml .= '  </Row>' . "\n";
        $xml .= '  <Row ss:Height="10"></Row>' . "\n";

        // Cabecera de la tabla
        $xml .= '  <Row>' . "\n";
        $headers = ['N° RECIBO / VALE', 'FECHA Y HORA', 'VEHÍCULO / PLACA', 'CONDUCTOR / RESPONSABLE', 'DESTINO / ÁREA', 'CANTIDAD (GAL)', 'ESTADO', 'OBSERVACIONES'];
        foreach($headers as $h) {
            $xml .= '   <Cell ss:StyleID="Th"><Data ss:Type="String">' . $h . '</Data></Cell>' . "\n";
        }
        $xml .= '  </Row>' . "\n";

        // Filas de datos
        foreach ($deliveries as $d) {
            $cant = (float) $d['gallons'];
            $xml .= '  <Row>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdBold"><Data ss:Type="String">' . htmlspecialchars($d['receipt_number']) . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="Td"><Data ss:Type="String">' . date('d/m/Y H:i', strtotime($d['created_at'])) . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdBold"><Data ss:Type="String">' . htmlspecialchars(mb_substr($d['vehicle_plate'], 0, 7)) . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="Td"><Data ss:Type="String">' . htmlspecialchars($d['driver_name']) . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="Td"><Data ss:Type="String">' . htmlspecialchars($d['destination'] ?? 'General') . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdRight"><Data ss:Type="Number">' . $cant . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="TdCenter"><Data ss:Type="String">' . htmlspecialchars($d['status'] ?? 'Completado') . '</Data></Cell>' . "\n";
            $xml .= '   <Cell ss:StyleID="Td"><Data ss:Type="String">' . htmlspecialchars($d['notes'] ?? '') . '</Data></Cell>' . "\n";
            $xml .= '  </Row>' . "\n";
        }

        // Fila de resumen total
        $xml .= '  <Row>' . "\n";
        $xml .= '   <Cell ss:MergeAcross="4" ss:StyleID="Total"><Data ss:Type="String">TOTAL GENERAL DESPACHADO:</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:StyleID="Total"><Data ss:Type="Number">' . $totalGalones . '</Data></Cell>' . "\n";
        $xml .= '   <Cell ss:MergeAcross="1" ss:StyleID="Total"><Data ss:Type="String">' . count($deliveries) . ' Entregas Registradas</Data></Cell>' . "\n";
        $xml .= '  </Row>' . "\n";

        $xml .= ' </Table>' . "\n";
        $xml .= '</Worksheet>' . "\n";
        $xml .= '</Workbook>' . "\n";

        echo $xml;
        exit;
    }
}