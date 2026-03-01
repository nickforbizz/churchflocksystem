<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Export donation summaries with multiple sheets:
 * - Summary by Purpose
 * - Summary by Method
 * - Summary by Month
 */
class DonationSummaryExport implements WithMultipleSheets
{
    protected ReportFilterDTO $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function sheets(): array
    {
        return [
            'By Purpose' => new DonationSummaryByPurposeSheet($this->filters),
            'By Method' => new DonationSummaryByMethodSheet($this->filters),
            'By Month' => new DonationSummaryByMonthSheet($this->filters),
        ];
    }
}

/**
 * Summary sheet grouped by purpose.
 */
class DonationSummaryByPurposeSheet extends BaseExport implements FromCollection, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    private int $rowNumber = 0;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'By Purpose';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'E';
    }

    public function collection()
    {
        $query = Donation::query()
            ->select(
                'purpose',
                DB::raw('COUNT(*) as donation_count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('AVG(amount) as avg_amount'),
                DB::raw('COUNT(DISTINCT member_id) as unique_donors')
            )
            ->groupBy('purpose')
            ->orderByDesc('total_amount');

        $this->applyFilters($query);

        return $query->get();
    }

    public function headings(): array
    {
        return ['#', 'Purpose', 'Total Donations', 'Total Amount', 'Avg Amount', 'Unique Donors'];
    }

    public function map($row): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            ucfirst($row->purpose ?? 'Unspecified'),
            $row->donation_count,
            number_format($row->total_amount, 2),
            number_format($row->avg_amount, 2),
            $row->unique_donors,
        ];
    }

    private function applyFilters($query): void
    {
        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }
        if ($this->filters->dateFrom) {
            $query->where('date', '>=', $this->filters->dateFrom);
        }
        if ($this->filters->dateTo) {
            $query->where('date', '<=', $this->filters->dateTo);
        }
        if ($this->filters->groupIds) {
            $query->whereHas('member', function ($q) {
                $q->whereIn('group_id', $this->filters->groupIds);
            });
        }
    }
}

/**
 * Summary sheet grouped by payment method.
 */
class DonationSummaryByMethodSheet extends BaseExport implements FromCollection, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    private int $rowNumber = 0;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'By Method';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'E';
    }

    public function collection()
    {
        $query = Donation::query()
            ->select(
                'method',
                DB::raw('COUNT(*) as donation_count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('AVG(amount) as avg_amount'),
                DB::raw('COUNT(DISTINCT member_id) as unique_donors')
            )
            ->groupBy('method')
            ->orderByDesc('total_amount');

        $this->applyFilters($query);

        return $query->get();
    }

    public function headings(): array
    {
        return ['#', 'Method', 'Total Donations', 'Total Amount', 'Avg Amount', 'Unique Donors'];
    }

    public function map($row): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            ucfirst($row->method ?? 'Unspecified'),
            $row->donation_count,
            number_format($row->total_amount, 2),
            number_format($row->avg_amount, 2),
            $row->unique_donors,
        ];
    }

    private function applyFilters($query): void
    {
        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }
        if ($this->filters->dateFrom) {
            $query->where('date', '>=', $this->filters->dateFrom);
        }
        if ($this->filters->dateTo) {
            $query->where('date', '<=', $this->filters->dateTo);
        }
        if ($this->filters->groupIds) {
            $query->whereHas('member', function ($q) {
                $q->whereIn('group_id', $this->filters->groupIds);
            });
        }
    }
}

/**
 * Summary sheet grouped by month.
 */
class DonationSummaryByMonthSheet extends BaseExport implements FromCollection, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'By Month';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'F';
    }

    public function collection()
    {
        $query = Donation::query()
            ->select(
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month'),
                DB::raw('COUNT(*) as donation_count'),
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('AVG(amount) as avg_amount'),
                DB::raw('COUNT(DISTINCT member_id) as unique_donors')
            )
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'))
            ->orderByDesc('year')
            ->orderByDesc('month');

        $this->applyFilters($query);

        return $query->get();
    }

    public function headings(): array
    {
        return ['Period', 'Total Donations', 'Total Amount', 'Avg Amount', 'Unique Donors', 'Growth %'];
    }

    public function map($row): array
    {
        $monthName = \Carbon\Carbon::create($row->year, $row->month)->format('M Y');
        
        return [
            $monthName,
            $row->donation_count,
            number_format($row->total_amount, 2),
            number_format($row->avg_amount, 2),
            $row->unique_donors,
            'N/A', // Growth calculation would need previous period
        ];
    }

    private function applyFilters($query): void
    {
        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }
        if ($this->filters->dateFrom) {
            $query->where('date', '>=', $this->filters->dateFrom);
        }
        if ($this->filters->dateTo) {
            $query->where('date', '<=', $this->filters->dateTo);
        }
        if ($this->filters->groupIds) {
            $query->whereHas('member', function ($q) {
                $q->whereIn('group_id', $this->filters->groupIds);
            });
        }
    }
}
