<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Member;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export birthday report for members.
 * Can filter by month or upcoming days.
 */
class BirthdayReportExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    protected ?int $upcomingDays;
    private int $rowNumber = 0;

    public function __construct(ReportFilterDTO $filters, ?int $upcomingDays = null)
    {
        $this->filters = $filters;
        $this->upcomingDays = $upcomingDays;
    }

    public function title(): string
    {
        return 'Birthdays';
    }

    protected function getFilterDescription(): string
    {
        $description = [];

        if ($this->filters->month) {
            $monthName = Carbon::create(null, $this->filters->month)->format('F');
            $description[] = "Month: {$monthName}";
        } elseif ($this->upcomingDays) {
            $description[] = "Upcoming {$this->upcomingDays} Days";
        }

        if ($this->filters->groupIds) {
            $description[] = count($this->filters->groupIds) . " Group(s)";
        }

        $description[] = $this->filters->activeOnly ? "Active Only" : "All Members";

        return implode(' | ', $description);
    }

    protected function getLastColumn(): string
    {
        return 'H'; // 8 columns
    }

    public function query()
    {
        $query = Member::query()
            ->with(['group', 'homecell'])
            ->whereNotNull('birth_date')
            ->orderByRaw('MONTH(birth_date), DAY(birth_date)');

        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }

        if ($this->filters->groupIds) {
            $query->whereIn('group_id', $this->filters->groupIds);
        }

        if ($this->filters->homecellIds) {
            $query->whereIn('homecell_id', $this->filters->homecellIds);
        }

        // Filter by specific month
        if ($this->filters->month) {
            $query->whereMonth('birth_date', $this->filters->month);
        }

        // Filter by upcoming days (alternative to month filter)
        if ($this->upcomingDays && !$this->filters->month) {
            $today = Carbon::today();
            $endDate = $today->copy()->addDays($this->upcomingDays);

            // Handle year boundary
            if ($endDate->year > $today->year) {
                $query->where(function ($q) use ($today, $endDate) {
                    // Birthdays from today to end of year
                    $q->whereRaw('DATE_FORMAT(birth_date, "%m-%d") >= ?', [$today->format('m-d')])
                      ->orWhereRaw('DATE_FORMAT(birth_date, "%m-%d") <= ?', [$endDate->format('m-d')]);
                });
            } else {
                $query->whereRaw('DATE_FORMAT(birth_date, "%m-%d") >= ?', [$today->format('m-d')])
                      ->whereRaw('DATE_FORMAT(birth_date, "%m-%d") <= ?', [$endDate->format('m-d')]);
            }
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Member #',
            'Full Name',
            'Birth Date',
            'Age',
            'Phone',
            'Group',
            'Homecell',
        ];
    }

    public function map($member): array
    {
        $this->rowNumber++;
        $now = Carbon::now();
        $age = $now->diffInYears($member->birth_date);

        return [
            $this->rowNumber,
            $member->member_number ?? 'N/A',
            $member->full_name,
            $member->birth_date->format('F j'),
            $age,
            $member->phone ?? 'N/A',
            $this->getRelationValue($member, 'group', 'name'),
            $this->getRelationValue($member, 'homecell', 'name'),
        ];
    }
}
