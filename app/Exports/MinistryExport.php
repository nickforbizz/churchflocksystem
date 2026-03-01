<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Ministry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export ministries to Excel with member counts.
 */
class MinistryExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    private int $rowNumber = 0;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Ministries';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'F'; // 6 columns
    }

    public function query()
    {
        $query = Ministry::query()
            ->with(['user'])
            ->withCount(['members as members_count' => function ($q) {
                if ($this->filters->activeOnly) {
                    $q->where('members.active', 1);
                }
            }])
            ->orderBy('name', 'asc');

        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Ministry Name',
            'Members Count',
            'Status',
            'Created By',
            'Created At',
        ];
    }

    public function map($ministry): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $ministry->name ?? 'N/A',
            $ministry->members_count ?? 0,
            $this->formatBoolean($ministry->active, 'Active', 'Inactive'),
            $ministry->user->name ?? 'N/A',
            $this->formatDate($ministry->created_at, 'Y-m-d H:i'),
        ];
    }
}
