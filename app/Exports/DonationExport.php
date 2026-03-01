<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Donation;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

/**
 * Export donations to Excel with comprehensive filtering options.
 * Includes summary totals at the bottom of the report.
 */
class DonationExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    private int $rowNumber = 0;
    private float $totalAmount = 0;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Donations';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'I'; // 9 columns
    }

    /**
     * Register events for Excel customization.
     * Adds summary row at the end.
     */
    public function registerEvents(): array
    {
        $parentEvents = parent::registerEvents();

        return array_merge($parentEvents, [
            AfterSheet::class => function (AfterSheet $event) {
                // Call parent's AfterSheet handler first
                $sheet = $event->sheet->getDelegate();
                
                // Insert header rows
                $sheet->insertNewRowBefore(1, $this->headerRowCount);
                $titleText = $this->buildTitleText();
                $sheet->setCellValue('A1', $titleText);
                $sheet->mergeCells("A1:{$this->getLastColumn()}{$this->headerRowCount}");
                $this->applyTitleStyles($sheet, $this->getLastColumn());
                
                $headerRow = $this->headerRowCount + 1;
                $this->applyColumnHeaderStyles($sheet, $headerRow, $this->getLastColumn());

                // Add summary row at the end
                $lastRow = $sheet->getHighestRow() + 1;
                $sheet->setCellValue("A{$lastRow}", 'TOTAL');
                $sheet->setCellValue("E{$lastRow}", $this->totalAmount);
                
                // Style summary row
                $sheet->getStyle("A{$lastRow}:{$this->getLastColumn()}{$lastRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFFF2CC'],
                    ],
                ]);

                // Format amount column as currency
                $sheet->getStyle("E{$headerRow}:E{$lastRow}")->getNumberFormat()
                    ->setFormatCode('#,##0.00');
            },
        ]);
    }

    public function query()
    {
        $query = Donation::query()
            ->with(['member', 'member.group'])
            ->orderBy('date', 'desc');

        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }

        if ($this->filters->dateFrom) {
            $query->where('date', '>=', $this->filters->dateFrom);
        }

        if ($this->filters->dateTo) {
            $query->where('date', '<=', $this->filters->dateTo);
        }

        if ($this->filters->donationPurpose) {
            $query->where('purpose', $this->filters->donationPurpose);
        }

        if ($this->filters->donationMethod) {
            $query->where('method', $this->filters->donationMethod);
        }

        if ($this->filters->amountMin !== null) {
            $query->where('amount', '>=', $this->filters->amountMin);
        }

        if ($this->filters->amountMax !== null) {
            $query->where('amount', '<=', $this->filters->amountMax);
        }

        if ($this->filters->memberId) {
            $query->where('member_id', $this->filters->memberId);
        }

        if ($this->filters->groupIds) {
            $query->whereHas('member', function ($q) {
                $q->whereIn('group_id', $this->filters->groupIds);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Receipt Number',
            'Member',
            'Member #',
            'Amount',
            'Method',
            'Purpose',
            'Date',
            'Group',
        ];
    }

    public function map($donation): array
    {
        $this->rowNumber++;
        $this->totalAmount += (float) $donation->amount;

        return [
            $this->rowNumber,
            $donation->receipt_number ?? 'N/A',
            $donation->member->full_name ?? 'Anonymous',
            $donation->member->member_number ?? 'N/A',
            (float) $donation->amount,
            ucfirst($donation->method ?? 'N/A'),
            ucfirst($donation->purpose ?? 'N/A'),
            $this->formatDate($donation->date),
            $this->getRelationValue($donation->member ?? new \stdClass(), 'group', 'name', 'N/A'),
        ];
    }
}
