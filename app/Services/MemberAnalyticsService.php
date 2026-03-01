<?php

namespace App\Services;

use App\DTOs\ReportFilterDTO;
use App\Models\Member;
use App\Models\Group;
use App\Models\Homecell;
use App\Models\Ministry;
use App\Models\Donation;
use App\Models\EventAttendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Service for generating analytics data for reports and charts.
 * Provides data for dashboard widgets and report visualizations.
 */
class MemberAnalyticsService
{
    /**
     * Get membership growth trend over time.
     */
    public function getGrowthTrend(ReportFilterDTO $filters, int $months = 12): array
    {
        $data = [];
        $now = Carbon::now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            
            $query = Member::query()
                ->whereDate('created_at', '<=', $date->endOfMonth());

            $this->applyMemberFilters($query, $filters);

            $count = $query->count();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'month_short' => $date->format('M'),
                'year' => $date->year,
                'count' => $count,
            ];
        }

        return $data;
    }

    /**
     * Get new members per month.
     */
    public function getNewMembersTrend(ReportFilterDTO $filters, int $months = 12): array
    {
        $data = [];
        $now = Carbon::now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            
            $query = Member::query()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year);

            $this->applyMemberFilters($query, $filters);

            $count = $query->count();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'month_short' => $date->format('M'),
                'year' => $date->year,
                'count' => $count,
            ];
        }

        return $data;
    }

    /**
     * Get age distribution of members.
     */
    public function getAgeDistribution(ReportFilterDTO $filters): array
    {
        $now = Carbon::now();
        
        $query = Member::query()->whereNotNull('birth_date');
        $this->applyMemberFilters($query, $filters);
        
        $members = $query->get(['birth_date']);

        $distribution = [
            '0-17' => ['label' => 'Youth (0-17)', 'count' => 0, 'color' => '#FF6384'],
            '18-25' => ['label' => 'Young Adult (18-25)', 'count' => 0, 'color' => '#36A2EB'],
            '26-35' => ['label' => 'Adult (26-35)', 'count' => 0, 'color' => '#FFCE56'],
            '36-45' => ['label' => 'Middle Age (36-45)', 'count' => 0, 'color' => '#4BC0C0'],
            '46-55' => ['label' => 'Mature (46-55)', 'count' => 0, 'color' => '#9966FF'],
            '56-65' => ['label' => 'Senior (56-65)', 'count' => 0, 'color' => '#FF9F40'],
            '65+' => ['label' => 'Elder (65+)', 'count' => 0, 'color' => '#C9CBCF'],
        ];

        foreach ($members as $member) {
            $age = $now->diffInYears($member->birth_date);
            
            if ($age <= 17) $distribution['0-17']['count']++;
            elseif ($age <= 25) $distribution['18-25']['count']++;
            elseif ($age <= 35) $distribution['26-35']['count']++;
            elseif ($age <= 45) $distribution['36-45']['count']++;
            elseif ($age <= 55) $distribution['46-55']['count']++;
            elseif ($age <= 65) $distribution['56-65']['count']++;
            else $distribution['65+']['count']++;
        }

        return array_values($distribution);
    }

    /**
     * Get gender distribution.
     */
    public function getGenderDistribution(ReportFilterDTO $filters): array
    {
        $query = Member::query()
            ->select('gender', DB::raw('count(*) as count'))
            ->whereNotNull('gender')
            ->groupBy('gender');

        $this->applyMemberFilters($query, $filters);

        $results = $query->get();

        $colors = [
            'male' => '#36A2EB',
            'female' => '#FF6384',
            'other' => '#FFCE56',
        ];

        return $results->map(function ($item) use ($colors) {
            return [
                'label' => ucfirst($item->gender),
                'value' => $item->gender,
                'count' => $item->count,
                'color' => $colors[strtolower($item->gender)] ?? '#C9CBCF',
            ];
        })->toArray();
    }

    /**
     * Get marital status distribution.
     */
    public function getMaritalStatusDistribution(ReportFilterDTO $filters): array
    {
        $query = Member::query()
            ->select('marital_status', DB::raw('count(*) as count'))
            ->whereNotNull('marital_status')
            ->groupBy('marital_status');

        $this->applyMemberFilters($query, $filters);

        $results = $query->get();

        $colors = [
            'single' => '#36A2EB',
            'married' => '#4BC0C0',
            'divorced' => '#FF9F40',
            'widowed' => '#9966FF',
            'separated' => '#FF6384',
        ];

        return $results->map(function ($item) use ($colors) {
            return [
                'label' => ucfirst($item->marital_status),
                'value' => $item->marital_status,
                'count' => $item->count,
                'color' => $colors[strtolower($item->marital_status)] ?? '#C9CBCF',
            ];
        })->toArray();
    }

    /**
     * Get members by group.
     */
    public function getMembersByGroup(ReportFilterDTO $filters, int $limit = 10): array
    {
        $query = Group::query()
            ->where('name', '!=', 'All')
            ->withCount(['members' => function ($q) use ($filters) {
                if ($filters->activeOnly) {
                    $q->where('active', 1);
                }
            }])
            ->orderByDesc('members_count')
            ->limit($limit);

        if ($filters->activeOnly) {
            $query->where('active', 1);
        }

        return $query->get(['id', 'name', 'members_count'])->toArray();
    }

    /**
     * Get members by homecell.
     */
    public function getMembersByHomecell(ReportFilterDTO $filters, int $limit = 10): array
    {
        $query = Homecell::query()
            ->withCount(['members' => function ($q) use ($filters) {
                if ($filters->activeOnly) {
                    $q->where('members.active', 1);
                }
            }])
            ->orderByDesc('members_count')
            ->limit($limit);

        if ($filters->activeOnly) {
            $query->where('active', 1);
        }

        return $query->get(['id', 'primary_cell as name', 'members_count'])->toArray();
    }

    /**
     * Get members by ministry.
     */
    public function getMembersByMinistry(ReportFilterDTO $filters, int $limit = 10): array
    {
        $query = Ministry::query()
            ->withCount(['members as members_count' => function ($q) use ($filters) {
                if ($filters->activeOnly) {
                    $q->where('members.active', 1);
                }
            }])
            ->orderByDesc('members_count')
            ->limit($limit);

        if ($filters->activeOnly) {
            $query->where('active', 1);
        }

        return $query->get(['id', 'name', 'members_count'])->toArray();
    }

    /**
     * Get spiritual growth metrics.
     */
    public function getSpiritualGrowthMetrics(ReportFilterDTO $filters): array
    {
        $query = Member::query();
        $this->applyMemberFilters($query, $filters);
        
        $total = (clone $query)->count();
        $bornAgain = (clone $query)->where('born_again', 1)->count();
        $waterBaptized = (clone $query)->whereNotNull('water_immersed_when')->count();
        $spiritFilled = (clone $query)->whereNotNull('spirit_filled_when')->count();

        return [
            'total_members' => $total,
            'born_again' => [
                'count' => $bornAgain,
                'percentage' => $total > 0 ? round(($bornAgain / $total) * 100, 1) : 0,
            ],
            'water_baptized' => [
                'count' => $waterBaptized,
                'percentage' => $total > 0 ? round(($waterBaptized / $total) * 100, 1) : 0,
            ],
            'spirit_filled' => [
                'count' => $spiritFilled,
                'percentage' => $total > 0 ? round(($spiritFilled / $total) * 100, 1) : 0,
            ],
        ];
    }

    /**
     * Get donation trends over time.
     */
    public function getDonationTrend(ReportFilterDTO $filters, int $months = 12): array
    {
        $data = [];
        $now = Carbon::now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            
            $query = Donation::query()
                ->whereMonth('date', $date->month)
                ->whereYear('date', $date->year);

            $this->applyDonationFilters($query, $filters);

            $result = $query->selectRaw('SUM(amount) as total, COUNT(*) as count')->first();
            
            $data[] = [
                'month' => $date->format('M Y'),
                'month_short' => $date->format('M'),
                'year' => $date->year,
                'total' => (float) ($result->total ?? 0),
                'count' => (int) ($result->count ?? 0),
            ];
        }

        return $data;
    }

    /**
     * Get donations by purpose.
     */
    public function getDonationsByPurpose(ReportFilterDTO $filters): array
    {
        $query = Donation::query()
            ->select('purpose', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->whereNotNull('purpose')
            ->groupBy('purpose')
            ->orderByDesc('total');

        $this->applyDonationFilters($query, $filters);

        $colors = ['#4472C4', '#ED7D31', '#A5A5A5', '#FFC000', '#5B9BD5', '#70AD47', '#264478', '#9E480E'];

        return $query->get()->map(function ($item, $index) use ($colors) {
            return [
                'label' => ucfirst($item->purpose),
                'value' => $item->purpose,
                'total' => (float) $item->total,
                'count' => (int) $item->count,
                'color' => $colors[$index % count($colors)],
            ];
        })->toArray();
    }

    /**
     * Get donations by method.
     */
    public function getDonationsByMethod(ReportFilterDTO $filters): array
    {
        $query = Donation::query()
            ->select('method', DB::raw('SUM(amount) as total'), DB::raw('COUNT(*) as count'))
            ->whereNotNull('method')
            ->groupBy('method')
            ->orderByDesc('total');

        $this->applyDonationFilters($query, $filters);

        $colors = ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF'];

        return $query->get()->map(function ($item, $index) use ($colors) {
            return [
                'label' => ucfirst($item->method),
                'value' => $item->method,
                'total' => (float) $item->total,
                'count' => (int) $item->count,
                'color' => $colors[$index % count($colors)],
            ];
        })->toArray();
    }

    /**
     * Get attendance summary statistics.
     */
    public function getAttendanceSummary(ReportFilterDTO $filters): array
    {
        $query = EventAttendance::query();
        $this->applyAttendanceFilters($query, $filters);

        $total = (clone $query)->count();
        $present = (clone $query)->where('status', 'present')->count();
        $absent = (clone $query)->where('status', 'absent')->count();
        $excused = (clone $query)->where('status', 'excused')->count();

        return [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'excused' => $excused,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
        ];
    }

    /**
     * Get attendance trend over time.
     */
    public function getAttendanceTrend(ReportFilterDTO $filters, int $weeks = 8): array
    {
        $data = [];
        $now = Carbon::now();

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $weekStart = $now->copy()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            $query = EventAttendance::query()
                ->whereBetween('attendance_date', [$weekStart, $weekEnd]);

            $this->applyAttendanceFilters($query, $filters);

            $result = $query->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent
            ")->first();

            $data[] = [
                'week' => "Week " . ($weeks - $i),
                'date_range' => $weekStart->format('M d') . ' - ' . $weekEnd->format('M d'),
                'total' => (int) ($result->total ?? 0),
                'present' => (int) ($result->present ?? 0),
                'absent' => (int) ($result->absent ?? 0),
                'rate' => $result->total > 0 ? round(($result->present / $result->total) * 100, 1) : 0,
            ];
        }

        return $data;
    }

    /**
     * Get summary statistics for dashboard.
     */
    public function getSummaryStats(ReportFilterDTO $filters): array
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        $memberQuery = Member::query();
        $this->applyMemberFilters($memberQuery, $filters);

        $totalMembers = (clone $memberQuery)->count();
        
        $newThisMonth = (clone $memberQuery)
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $newLastMonth = (clone $memberQuery)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        $donationQuery = Donation::query();
        $this->applyDonationFilters($donationQuery, $filters);

        $donationsThisMonth = (clone $donationQuery)
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $donationsLastMonth = (clone $donationQuery)
            ->whereMonth('date', $lastMonth->month)
            ->whereYear('date', $lastMonth->year)
            ->sum('amount');

        return [
            'total_members' => $totalMembers,
            'new_members_this_month' => $newThisMonth,
            'new_members_last_month' => $newLastMonth,
            'member_growth_rate' => $newLastMonth > 0 
                ? round((($newThisMonth - $newLastMonth) / $newLastMonth) * 100, 1) 
                : ($newThisMonth > 0 ? 100 : 0),
            'donations_this_month' => (float) $donationsThisMonth,
            'donations_last_month' => (float) $donationsLastMonth,
            'donation_growth_rate' => $donationsLastMonth > 0 
                ? round((($donationsThisMonth - $donationsLastMonth) / $donationsLastMonth) * 100, 1) 
                : ($donationsThisMonth > 0 ? 100 : 0),
        ];
    }

    /**
     * Apply member-related filters to a query.
     */
    protected function applyMemberFilters(Builder $query, ReportFilterDTO $filters): void
    {
        if ($filters->activeOnly) {
            $query->where('active', 1);
        }

        if ($filters->groupIds) {
            $query->whereIn('group_id', $filters->groupIds);
        }

        if ($filters->homecellIds) {
            $query->whereIn('homecell_id', $filters->homecellIds);
        }

        if ($filters->ministryIds) {
            $query->whereHas('ministries', function ($q) use ($filters) {
                $q->whereIn('ministries.id', $filters->ministryIds);
            });
        }

        if ($filters->gender) {
            $query->where('gender', $filters->gender);
        }

        if ($filters->maritalStatus) {
            $query->where('marital_status', $filters->maritalStatus);
        }

        if ($filters->ageMin !== null || $filters->ageMax !== null) {
            $query->whereNotNull('birth_date');
            
            if ($filters->ageMin !== null) {
                $maxBirthDate = Carbon::now()->subYears($filters->ageMin);
                $query->where('birth_date', '<=', $maxBirthDate);
            }
            
            if ($filters->ageMax !== null) {
                $minBirthDate = Carbon::now()->subYears($filters->ageMax + 1)->addDay();
                $query->where('birth_date', '>=', $minBirthDate);
            }
        }

        if ($filters->dateFrom) {
            $query->where('created_at', '>=', $filters->dateFrom);
        }

        if ($filters->dateTo) {
            $query->where('created_at', '<=', $filters->dateTo);
        }
    }

    /**
     * Apply donation-related filters to a query.
     */
    protected function applyDonationFilters(Builder $query, ReportFilterDTO $filters): void
    {
        if ($filters->activeOnly) {
            $query->where('active', 1);
        }

        if ($filters->dateFrom) {
            $query->where('date', '>=', $filters->dateFrom);
        }

        if ($filters->dateTo) {
            $query->where('date', '<=', $filters->dateTo);
        }

        if ($filters->donationPurpose) {
            $query->where('purpose', $filters->donationPurpose);
        }

        if ($filters->donationMethod) {
            $query->where('method', $filters->donationMethod);
        }

        if ($filters->amountMin !== null) {
            $query->where('amount', '>=', $filters->amountMin);
        }

        if ($filters->amountMax !== null) {
            $query->where('amount', '<=', $filters->amountMax);
        }

        if ($filters->memberId) {
            $query->where('member_id', $filters->memberId);
        }

        if ($filters->groupIds) {
            $query->whereHas('member', function ($q) use ($filters) {
                $q->whereIn('group_id', $filters->groupIds);
            });
        }
    }

    /**
     * Apply attendance-related filters to a query.
     */
    protected function applyAttendanceFilters(Builder $query, ReportFilterDTO $filters): void
    {
        if ($filters->activeOnly) {
            $query->where('active', 1);
        }

        if ($filters->dateFrom) {
            $query->where('attendance_date', '>=', $filters->dateFrom);
        }

        if ($filters->dateTo) {
            $query->where('attendance_date', '<=', $filters->dateTo);
        }

        if ($filters->eventIds) {
            $query->whereIn('event_id', $filters->eventIds);
        }

        if ($filters->attendanceStatus) {
            $query->where('status', $filters->attendanceStatus);
        }

        if ($filters->attendanceType) {
            $query->where('attendance_type', $filters->attendanceType);
        }

        if ($filters->memberId) {
            $query->where('member_id', $filters->memberId);
        }

        if ($filters->groupIds) {
            $query->whereHas('member', function ($q) use ($filters) {
                $q->whereIn('group_id', $filters->groupIds);
            });
        }
    }
}
