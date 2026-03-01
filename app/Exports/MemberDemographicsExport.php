<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Export member demographics across multiple sheets:
 * - Gender Distribution
 * - Age Distribution
 * - Marital Status Distribution
 * - Spiritual Milestones
 */
class MemberDemographicsExport implements WithMultipleSheets
{
    protected ReportFilterDTO $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function sheets(): array
    {
        return [
            'Gender' => new GenderDistributionSheet($this->filters),
            'Age Groups' => new AgeDistributionSheet($this->filters),
            'Marital Status' => new MaritalStatusSheet($this->filters),
            'Spiritual Milestones' => new SpiritualMilestonesSheet($this->filters),
        ];
    }
}

/**
 * Gender distribution summary sheet.
 */
class GenderDistributionSheet extends BaseExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
{
    protected ReportFilterDTO $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Gender Distribution';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'C';
    }

    public function collection()
    {
        $query = Member::query()
            ->select('gender', DB::raw('COUNT(*) as count'))
            ->groupBy('gender')
            ->orderByDesc('count');

        $this->applyFilters($query);

        $results = $query->get();
        $total = $results->sum('count');

        return $results->map(function ($row) use ($total) {
            $row->percentage = $total > 0 ? round(($row->count / $total) * 100, 1) : 0;
            return $row;
        });
    }

    public function headings(): array
    {
        return ['Gender', 'Count', 'Percentage'];
    }

    public function map($row): array
    {
        return [
            ucfirst($row->gender ?? 'Unspecified'),
            $row->count,
            $row->percentage . '%',
        ];
    }

    private function applyFilters($query): void
    {
        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }
        if ($this->filters->groupIds) {
            $query->whereIn('group_id', $this->filters->groupIds);
        }
    }
}

/**
 * Age distribution summary sheet.
 */
class AgeDistributionSheet extends BaseExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings
{
    protected ReportFilterDTO $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Age Distribution';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'C';
    }

    public function collection()
    {
        $now = Carbon::now();
        
        $query = Member::query()->whereNotNull('birth_date');
        
        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }
        if ($this->filters->groupIds) {
            $query->whereIn('group_id', $this->filters->groupIds);
        }

        $members = $query->get(['birth_date']);

        $distribution = [
            '0-17 (Youth)' => 0,
            '18-25 (Young Adult)' => 0,
            '26-35 (Adult)' => 0,
            '36-45 (Middle Age)' => 0,
            '46-55 (Mature)' => 0,
            '56-65 (Senior)' => 0,
            '65+ (Elder)' => 0,
        ];

        foreach ($members as $member) {
            $age = $now->diffInYears($member->birth_date);
            
            if ($age <= 17) $distribution['0-17 (Youth)']++;
            elseif ($age <= 25) $distribution['18-25 (Young Adult)']++;
            elseif ($age <= 35) $distribution['26-35 (Adult)']++;
            elseif ($age <= 45) $distribution['36-45 (Middle Age)']++;
            elseif ($age <= 55) $distribution['46-55 (Mature)']++;
            elseif ($age <= 65) $distribution['56-65 (Senior)']++;
            else $distribution['65+ (Elder)']++;
        }

        $total = array_sum($distribution);

        return collect($distribution)->map(function ($count, $bracket) use ($total) {
            return [
                'Age Bracket' => $bracket,
                'Count' => $count,
                'Percentage' => $total > 0 ? round(($count / $total) * 100, 1) . '%' : '0%',
            ];
        })->values();
    }

    public function headings(): array
    {
        return ['Age Bracket', 'Count', 'Percentage'];
    }
}

/**
 * Marital status distribution sheet.
 */
class MaritalStatusSheet extends BaseExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
{
    protected ReportFilterDTO $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Marital Status';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'C';
    }

    public function collection()
    {
        $query = Member::query()
            ->select('marital_status', DB::raw('COUNT(*) as count'))
            ->groupBy('marital_status')
            ->orderByDesc('count');

        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }
        if ($this->filters->groupIds) {
            $query->whereIn('group_id', $this->filters->groupIds);
        }

        $results = $query->get();
        $total = $results->sum('count');

        return $results->map(function ($row) use ($total) {
            $row->percentage = $total > 0 ? round(($row->count / $total) * 100, 1) : 0;
            return $row;
        });
    }

    public function headings(): array
    {
        return ['Marital Status', 'Count', 'Percentage'];
    }

    public function map($row): array
    {
        return [
            ucfirst($row->marital_status ?? 'Unspecified'),
            $row->count,
            $row->percentage . '%',
        ];
    }
}

/**
 * Spiritual milestones summary sheet.
 */
class SpiritualMilestonesSheet extends BaseExport implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings
{
    protected ReportFilterDTO $filters;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Spiritual Milestones';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'C';
    }

    public function collection()
    {
        $query = Member::query();
        
        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }
        if ($this->filters->groupIds) {
            $query->whereIn('group_id', $this->filters->groupIds);
        }

        $total = (clone $query)->count();
        $bornAgain = (clone $query)->where('born_again', 1)->count();
        $waterBaptized = (clone $query)->whereNotNull('water_immersed_when')->count();
        $spiritFilled = (clone $query)->whereNotNull('spirit_filled_when')->count();

        return collect([
            [
                'Milestone' => 'Total Members',
                'Count' => $total,
                'Percentage' => '100%',
            ],
            [
                'Milestone' => 'Born Again',
                'Count' => $bornAgain,
                'Percentage' => $total > 0 ? round(($bornAgain / $total) * 100, 1) . '%' : '0%',
            ],
            [
                'Milestone' => 'Water Baptized',
                'Count' => $waterBaptized,
                'Percentage' => $total > 0 ? round(($waterBaptized / $total) * 100, 1) . '%' : '0%',
            ],
            [
                'Milestone' => 'Spirit Filled',
                'Count' => $spiritFilled,
                'Percentage' => $total > 0 ? round(($spiritFilled / $total) * 100, 1) . '%' : '0%',
            ],
        ]);
    }

    public function headings(): array
    {
        return ['Milestone', 'Count', 'Percentage'];
    }
}
