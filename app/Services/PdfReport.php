<?php

declare(strict_types=1);

namespace App\Services;

final class PdfReport
{
    /** @var array<int, string> */
    private array $objects = [];

    /** @var array<int, array{graphics: string, content: string}> Páginas ya cerradas */
    private array $pages = [];

    private string $graphics = "";
    private string $content = "";
    private int $line = 720;

    /** @var array<int, string>|null Último encabezado de tabla */
    private ?array $lastHeaders = null;

    /** 
     * Anchos optimizados para las 7 columnas (Suma total = 534px)
     * [N° RECIBO, FECHA Y HORA, PLACA, CONDUCTOR, DESTINO/AREA, CANT. (GAL), OBSERVACIONES]
     * @var array<int, int> 
     */
    private array $defaultWidths = [100, 78, 48, 73, 70, 55, 110];
    private array $lastWidths = [];

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

    public function tableHeader(array $headers, ?array $widths = null): void
    {
        $widths = $widths ?? $this->defaultWidths;
        $this->lastHeaders = $headers;
        $this->lastWidths = $widths;
        $this->renderTableHeader($headers, $widths);
    }

    private function renderTableHeader(array $headers, array $widths): void
    {
        $linesPerCell = [];
        $maxLines = 1;

        foreach ($headers as $i => $headerText) {
            $w = $widths[$i] ?? 75;
            $wrapped = $this->wrapText((string)$headerText, $w);
            $linesPerCell[$i] = $wrapped;
            $maxLines = max($maxLines, count($wrapped));
        }

        $rowHeight = $maxLines * 11 + 8;
        $this->line -= $rowHeight;
        $rectY = $this->line - 2;

        $this->graphics .= sprintf("0.94 0.96 0.98 rg 38 %d 534 %d re f\n", $rectY, $rowHeight);
        $this->graphics .= sprintf("0.79 0.83 0.88 RG 0.8 w 38 %d m 572 %d l S\n", $rectY + $rowHeight, $rectY + $rowHeight);
        $this->graphics .= sprintf("0.79 0.83 0.88 RG 0.8 w 38 %d m 572 %d l S\n", $rectY, $rectY);

        $x = 42;
        $startY = $this->line + $rowHeight - 12;

        foreach ($headers as $i => $headerText) {
            $w = $widths[$i] ?? 75;
            $lines = $linesPerCell[$i];
            $currentY = $startY;

            foreach ($lines as $lineText) {
                $this->writeColumnAtY($lineText, 8, $x, $currentY);
                $currentY -= 10;
            }
            $x += $w;
        }

        $this->line -= 6;
    }

    public function tableRow(array $row, ?array $widths = null): void
    {
        $widths = $widths ?? ($this->lastWidths ?: $this->defaultWidths);

        $linesPerCell = [];
        $maxLines = 1;

        foreach ($row as $i => $cellText) {
            $w = $widths[$i] ?? 75;
            $wrapped = $this->wrapText((string)$cellText, $w);
            $linesPerCell[$i] = $wrapped;
            $maxLines = max($maxLines, count($wrapped));
        }

        $rowHeight = $maxLines * 11 + 6;

        if ($this->line - $rowHeight < 50) {
            $this->newPage();
            if ($this->lastHeaders !== null) {
                $this->renderTableHeader($this->lastHeaders, $this->lastWidths);
            }
        }

        $this->line -= $rowHeight;
        $lineY = $this->line - 2;

        $this->graphics .= sprintf("0.89 0.91 0.94 RG 0.5 w 38 %d m 572 %d l S\n", $lineY, $lineY);

        $x = 42;
        $startY = $this->line + $rowHeight - 12;

        foreach ($row as $i => $cellText) {
            $w = $widths[$i] ?? 75;
            $lines = $linesPerCell[$i];
            $currentY = $startY;

            foreach ($lines as $lineText) {
                $this->writeColumnAtY($lineText, 8, $x, $currentY);
                $currentY -= 10;
            }
            $x += $w;
        }

        $this->line -= 4;
    }

    public function summaryBox(string $text): void
    {
        $this->line -= 10;
        $rectY = $this->line - 6;

        $this->graphics .= sprintf("0.93 0.96 1.00 rg 38 %d 534 24 re f\n", $rectY);
        $this->graphics .= sprintf("0.74 0.85 0.99 RG 1 w 38 %d 534 24 re S\n", $rectY);

        $this->write($text, 10, 48, true);
        $this->line -= 10;
    }

    /**
     * Divide una cadena en varias líneas para ajustarse al ancho especificado.
     * @return array<int, string>
     */
    private function wrapText(string $text, int $width): array
    {
        // Factor 4.2 es más conservador que 4.6 para asegurar que el texto
        // no se desborde fuera de la celda en la fuente Helvetica tamaño 8
        $maxChars = (int) floor(($width - 6) / 4.2);
        if ($maxChars < 1) {
            $maxChars = 1;
        }

        if (mb_strlen($text) <= $maxChars) {
            return [$text];
        }

        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            if ($currentLine === '') {
                if (mb_strlen($word) > $maxChars) {
                    $chunks = $this->splitText($word, $maxChars);
                    foreach ($chunks as $chunk) {
                        $lines[] = $chunk;
                    }
                } else {
                    $currentLine = $word;
                }
            } else {
                if (mb_strlen($currentLine . ' ' . $word) <= $maxChars) {
                    $currentLine .= ' ' . $word;
                } else {
                    $lines[] = $currentLine;
                    if (mb_strlen($word) > $maxChars) {
                        $chunks = $this->splitText($word, $maxChars);
                        foreach ($chunks as $chunk) {
                            $lines[] = $chunk;
                        }
                        $currentLine = '';
                    } else {
                        $currentLine = $word;
                    }
                }
            }
        }

        if ($currentLine !== '') {
            $lines[] = $currentLine;
        }

        return $lines;
    }

    /**
     * Alternativa compatible para dividir texto multibyte sin mb_strsplit()
     * @return array<int, string>
     */
    private function splitText(string $text, int $length): array
    {
        $result = [];
        $totalLen = mb_strlen($text);
        for ($i = 0; $i < $totalLen; $i += $length) {
            $result[] = mb_substr($text, $i, $length);
        }
        return $result;
    }

    private function newPage(): void
    {
        $this->pages[] = [
            'graphics' => $this->graphics,
            'content' => $this->content,
        ];
        $this->graphics = '';
        $this->content = '';
        $this->line = 720;
    }

    public function output(string $filename): never
    {
        $this->pages[] = [
            'graphics' => $this->graphics,
            'content' => $this->content,
        ];
        $pageCount = count($this->pages);

        $firstPageObjNum = 3;
        $fontObjNum = $firstPageObjNum + $pageCount;

        $this->addObject('<< /Type /Catalog /Pages 2 0 R >>');

        $kids = [];
        for ($i = 0; $i < $pageCount; $i++) {
            $kids[] = ($firstPageObjNum + $i) . ' 0 R';
        }
        $this->addObject('<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . $pageCount . ' >>');

        for ($i = 0; $i < $pageCount; $i++) {
            $contentObjNum = $fontObjNum + 1 + $i;
            $this->addObject(
                '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] '
                . '/Resources << /Font << /F1 ' . $fontObjNum . ' 0 R >> >> '
                . '/Contents ' . $contentObjNum . ' 0 R >>'
            );
        }

        $this->addObject('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>');

        foreach ($this->pages as $page) {
            $stream = "q\n" . $page['graphics'] . "Q\nBT\n" . $page['content'] . "ET";
            $this->addObject('<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream");
        }

        $pdf     = "%PDF-1.4\n";
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
        // Mapa explícito de caracteres UTF-8 frecuentes en español que iconv
        // a veces pierde o transliterada de forma incorrecta al pasar a
        // Windows-1252. Se aplica ANTES de la conversión para garantizar que
        // ñ, tildes y diéresis siempre lleguen correctamente al PDF (Bug 24).
        $map = [
            'á' => "\xe1", 'é' => "\xe9", 'í' => "\xed", 'ó' => "\xf3", 'ú' => "\xfa",
            'Á' => "\xc1", 'É' => "\xc9", 'Í' => "\xcd", 'Ó' => "\xd3", 'Ú' => "\xda",
            'ñ' => "\xf1", 'Ñ' => "\xd1",
            'ü' => "\xfc", 'Ü' => "\xdc",
            'ä' => "\xe4", 'ë' => "\xeb", 'ï' => "\xef", 'ö' => "\xf6",
            '¿' => "\xbf", '¡' => "\xa1",
            '–' => '-',  '—' => '-', '"' => '"', '"' => '"', "'" => "'",
        ];

        // Reemplazar caracteres conocidos primero
        $text = strtr($text, $map);

        // Para cualquier carácter UTF-8 restante no cubierto por el mapa,
        // intentar iconv y si falla, simplificar a ASCII básico.
        $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
        $clean = ($converted !== false && $converted !== '') ? $converted : $text;

        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $clean);
    }

    private function write(string $text, int $size, int $x = 40, bool $bold = false): void
    {
        $safe = $this->sanitize($text);
        $this->content .= sprintf("/F1 %d Tf 1 0 0 1 %d %d Tm (%s) Tj\n", $size, $x, $this->line, $safe);
        $this->line -= 14;
    }

    private function writeColumnAtY(string $text, int $size, int $x, int $y): void
    {
        $safe = $this->sanitize($text);
        $this->content .= sprintf("/F1 %d Tf 1 0 0 1 %d %d Tm (%s) Tj\n", $size, $x, $y, $safe);
    }
}