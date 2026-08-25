<?php

declare(strict_types=1);

namespace App\Services;

final class PdfReport
{
    /** @var array<int, string> */
    private array $objects = [];
    private string $graphics = "";
    private string $content = "";
    private int $line = 720;

    public function title(string $text): void
    {
        $this->write($text, 14, 40, true);
        $this->line -= 6;
    }

    public function subtitle(string $text): void
    {
        $this->write($text, 10, 40, false);
        $this->line -= 10;
    }

    public function text(string $text): void
    {
        $this->write($text, 9, 40, false);
    }

    public function spacer(): void
    {
        $this->line -= 10;
    }

    public function tableHeader(array $headers, array $widths = [70, 95, 75, 120, 115, 65]): void
    {
        $this->line -= 6;
        $rectY = $this->line - 4;
        
        // Fondo gris/azul suave (#F1F5F9) para el encabezado de la tabla
        $this->graphics .= sprintf("0.94 0.96 0.98 rg 35 %d 535 20 re f\n", $rectY);
        // Borde superior e inferior del encabezado (#CBD5E1)
        $this->graphics .= sprintf("0.79 0.83 0.88 RG 0.8 w 35 %d m 570 %d l S\n", $rectY + 20, $rectY + 20);
        $this->graphics .= sprintf("0.79 0.83 0.88 RG 0.8 w 35 %d m 570 %d l S\n", $rectY, $rectY);

        $x = 40;
        foreach ($headers as $i => $headerText) {
            $w = $widths[$i] ?? 80;
            $this->writeColumn((string) $headerText, 8, $x, true);
            $x += $w;
        }
        $this->line -= 18;
    }

    public function tableRow(array $row, array $widths = [70, 95, 75, 120, 115, 65]): void
    {
        if ($this->line < 60) {
            $this->line = 720;
        }

        $lineY = $this->line - 4;
        // Línea divisoria horizontal fina entre filas (#E2E8F0)
        $this->graphics .= sprintf("0.89 0.91 0.94 RG 0.5 w 35 %d m 570 %d l S\n", $lineY, $lineY);

        $x = 40;
        foreach ($row as $i => $cellText) {
            $w = $widths[$i] ?? 80;
            $this->writeColumn((string) $cellText, 8, $x, false);
            $x += $w;
        }
        $this->line -= 18;
    }

    public function summaryBox(string $text): void
    {
        $this->line -= 10;
        $rectY = $this->line - 6;

        // Tarjeta de resumen con fondo azul claro (#EFF6FF) y borde (#BFDBFE)
        $this->graphics .= sprintf("0.93 0.96 1.00 rg 35 %d 535 24 re f\n", $rectY);
        $this->graphics .= sprintf("0.74 0.85 0.99 RG 1 w 35 %d 535 24 re S\n", $rectY);

        $this->write($text, 10, 45, true);
        $this->line -= 10;
    }

    public function output(string $filename): never
    {
        $this->addObject('<< /Type /Catalog /Pages 2 0 R >>');
        $this->addObject('<< /Type /Pages /Kids [3 0 R] /Count 1 >>');
        $this->addObject('<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>');
        $this->addObject('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>');

        $stream = "q\n" . $this->graphics . "Q\nBT\n" . $this->content . "ET";
        $this->addObject('<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream");

        $pdf    = "%PDF-1.4\n";
        $offsets = [];

        foreach ($this->objects as $number => $object) {
            $offsets[$number] = strlen($pdf);
            $pdf .= $number . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xref  = strlen($pdf);
        $count = count($this->objects);
        $pdf  .= "xref\n0 " . ($count + 1) . "\n0000000000 65535 f \n";
        for ($i = 1; $i <= $count; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }
        $pdf .= "trailer\n<< /Size " . ($count + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xref . "\n%%EOF";

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdf));
        echo $pdf;
        exit;
    }

    private function addObject(string $content): void
    {
        $this->objects[count($this->objects) + 1] = $content;
    }

    private function sanitize(string $text): string
    {
        if (mb_check_encoding($text, 'UTF-8')) {
            $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
            if ($converted === false) {
                $converted = @mb_convert_encoding($text, 'Windows-1252', 'UTF-8');
            }
        } else {
            $converted = $text;
        }

        $clean = ($converted !== false) ? $converted : $text;
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $clean);
    }

    private function write(string $text, int $size, int $x = 40, bool $bold = false): void
    {
        $safe = $this->sanitize($text);
        $this->content .= sprintf("/F1 %d Tf 1 0 0 1 %d %d Tm (%s) Tj\n", $size, $x, $this->line, $safe);
        $this->line -= 14;
    }

    private function writeColumn(string $text, int $size, int $x, bool $bold = false): void
    {
        $safe = $this->sanitize($text);
        $this->content .= sprintf("/F1 %d Tf 1 0 0 1 %d %d Tm (%s) Tj\n", $size, $x, $this->line, $safe);
    }
}