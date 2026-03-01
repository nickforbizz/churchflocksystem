<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

/**
 * Abstract base class for all Excel exports.
 * Follows Single Responsibility Principle - handles only Excel formatting concerns.
 * 
 * Child classes must implement:
 * - title(): string - The sheet title
 * - headings(): array - Column headers
 * - getFilterDescription(): string - Description of applied filters
 * - getLastColumn(): string - Last column letter (e.g., 'Z', 'AA')
 */
abstract class BaseExport implements WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    /**
     * Number of rows to reserve for the header section (title + filter info).
     */
    protected int $headerRowCount = 3;

    /**
     * Primary brand color for header background (ARGB format).
     */
    protected string $primaryColor = 'FF4472C4';

    /**
     * Secondary color for column headers (ARGB format).
     */
    protected string $secondaryColor = 'FFE0E0E0';

    /**
     * Get the title for the export sheet.
     */
    abstract public function title(): string;

    /**
     * Get the column headings.
     */
    abstract public function headings(): array;

    /**
     * Get a human-readable description of the filters applied.
     * This appears in the header section of the export.
     */
    abstract protected function getFilterDescription(): string;

    /**
     * Get the last column letter for the export.
     * Used for merging header cells.
     */
    abstract protected function getLastColumn(): string;

    /**
     * Register events for Excel customization.
     * Inserts a styled header section with app name, filter info, and timestamp.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = $this->getLastColumn();
                
                // Insert rows at the top for the header section
                $sheet->insertNewRowBefore(1, $this->headerRowCount);
                
                // Build the title text
                $titleText = $this->buildTitleText();
                
                // Set and style the title
                $sheet->setCellValue('A1', $titleText);
                $sheet->mergeCells("A1:{$lastColumn}{$this->headerRowCount}");
                
                // Apply title styling
                $this->applyTitleStyles($sheet, $lastColumn);
                
                // Apply column header styling (row after title section)
                $headerRow = $this->headerRowCount + 1;
                $this->applyColumnHeaderStyles($sheet, $headerRow, $lastColumn);
            },
        ];
    }

    /**
     * Build the title text for the header section.
     */
    protected function buildTitleText(): string
    {
        $appName = config('app.name', 'Church Flock System');
        $reportTitle = $this->title();
        
        $titleText = "{$appName} - {$reportTitle} Report";
        $titleText .= "\n" . $this->getFilterDescription();
        $titleText .= "\nGenerated: " . now()->format('F j, Y \a\t g:i A');
        
        return $titleText;
    }

    /**
     * Apply styling to the title section.
     */
    protected function applyTitleStyles(Worksheet $sheet, string $lastColumn): void
    {
        $sheet->getStyle("A1:{$lastColumn}{$this->headerRowCount}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => $this->primaryColor],
            ],
        ]);

        // Set consistent row heights for the title section
        for ($i = 1; $i <= $this->headerRowCount; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(20);
        }
    }

    /**
     * Apply styling to the column header row.
     */
    protected function applyColumnHeaderStyles(Worksheet $sheet, int $headerRow, string $lastColumn): void
    {
        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => $this->secondaryColor],
            ],
        ]);
    }

    /**
     * Default styles implementation.
     * Child classes can override for additional styling.
     */
    public function styles(Worksheet $sheet): array
    {
        return [];
    }

    /**
     * Helper to format date for display.
     */
    protected function formatDate($date, string $format = 'Y-m-d'): string
    {
        if (!$date) {
            return '';
        }
        
        return $date instanceof \Carbon\Carbon 
            ? $date->format($format) 
            : (string) $date;
    }

    /**
     * Helper to format boolean values.
     */
    protected function formatBoolean($value, string $trueLabel = 'Yes', string $falseLabel = 'No'): string
    {
        return $value ? $trueLabel : $falseLabel;
    }

    /**
     * Helper to format currency values.
     */
    protected function formatCurrency($amount, string $currency = 'KES'): string
    {
        if ($amount === null) {
            return '';
        }
        
        return $currency . ' ' . number_format((float) $amount, 2);
    }

    /**
     * Helper to get a safe string value from a nullable relation.
     */
    protected function getRelationValue($model, string $relation, string $attribute, string $default = 'N/A'): string
    {
        return $model->{$relation}->{$attribute} ?? $default;
    }

    /**
     * Convert column number to Excel column letter.
     * e.g., 1 = A, 26 = Z, 27 = AA
     */
    protected function columnNumberToLetter(int $columnNumber): string
    {
        $letter = '';
        while ($columnNumber > 0) {
            $temp = ($columnNumber - 1) % 26;
            $letter = chr(65 + $temp) . $letter;
            $columnNumber = (int)(($columnNumber - $temp) / 26);
        }
        return $letter;
    }
}
