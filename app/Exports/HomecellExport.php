<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Homecell;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export homecells to Excel with member counts.
 */
class HomecellExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    private int $rowNumber = 0;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Homecells';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'H'; // 8 columns
    }

    public function query()
    {
        $query = Homecell::query()
            ->with(['user'])
            ->withCount(['members' => function ($q) {
                if ($this->filters->activeOnly) {
                    $q->where('members.active', 1);
                }
            }])
            ->orderBy('primary_cell', 'asc');

        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Primary Cell',
            'Prayercell Leader',
            'Members Count',
            'Date Joined',
            'Status',
            'Created By',
        ];
    }

    public function map($homecell): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $homecell->primary_cell ?? 'N/A',
            $homecell->prayercell_leader ?? 'N/A',
            $homecell->members_count ?? 0,
            $this->formatDate($homecell->date_joined),
            $this->formatBoolean($homecell->active, 'Active', 'Inactive'),
            $homecell->user->name ?? 'N/A',
        ];
    }
}
