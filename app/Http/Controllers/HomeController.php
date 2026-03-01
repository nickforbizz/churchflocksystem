<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Group;
use App\Models\Homecell;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\EventAttendance;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard_stats', 300, function () {
            return $this->getStats();
        });

        $charts = Cache::remember('dashboard_charts', 300, function () {
            return $this->getChartData();
        });

        return view('cms.dashboard.index', compact('stats', 'charts'));
    }

    public function cms()
    {
        // Database view data
        $event_attendance = DB::table('vw_event_participation_rate')->get();
        $attendance = DB::table('vw_event_attendance_summary')->get();
        $membership = DB::table('vw_group_membership_summary')->get();
        $donations = DB::table('vw_donations_monthly_trend')->get();
        $donations_summary = DB::table('vw_donations_summary')->get();

        // Dashboard stats and charts
        $stats = Cache::remember('dashboard_stats', 300, function () {
            return $this->getStats();
        });

        $charts = Cache::remember('dashboard_charts', 300, function () {
            return $this->getChartData();
        });


        return view('cms.index', compact(
            'attendance', 
            'membership', 
            'donations', 
            'donations_summary', 
            'event_attendance',
            'stats',
            'charts'
        ));
    }

    private function getStats(): array
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        return [
            // Membership Stats
            'total_members' => Member::where('active', 1)->count(),
            'new_members_this_month' => Member::where('active', 1)
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count(),
            'new_members_last_month' => Member::where('active', 1)
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count(),
            'inactive_members' => Member::where('active', 0)->count(),

            // Group Stats
            'total_groups' => Group::where('active', 1)->where('name', '!=', 'All')->count(),
            'total_homecells' => Homecell::where('active', 1)->count(),
            'total_ministries' => Ministry::where('active', 1)->count(),

            // Event Stats
            'upcoming_events' => Event::where('event_date', '>=', $now)->count(),
            'events_this_month' => Event::whereMonth('event_date', $now->month)
                ->whereYear('event_date', $now->year)
                ->count(),

            // Attendance Stats (if you have attendance table)
            'avg_attendance_this_month' => $this->getAverageAttendance($now),
            'avg_attendance_last_month' => $this->getAverageAttendance($lastMonth),

            // Demographics
            'gender_distribution' => Member::where('active', 1)
                ->select('gender', DB::raw('count(*) as count'))
                ->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray(),

            'marital_status_distribution' => Member::where('active', 1)
                ->select('marital_status', DB::raw('count(*) as count'))
                ->groupBy('marital_status')
                ->pluck('count', 'marital_status')
                ->toArray(),

            // Spiritual Growth
            'born_again_count' => Member::where('active', 1)->where('born_again', 1)->count(),
            'water_baptized_count' => Member::where('active', 1)->whereNotNull('water_immersed_when')->count(),
            'spirit_filled_count' => Member::where('active', 1)->whereNotNull('spirit_filled_when')->count(),
        ];
    }

    private function getChartData(): array
    {
        $now = Carbon::now();

        return [
            // Membership Growth (Last 12 months)
            'membership_growth' => $this->getMembershipGrowth(),

            // Members by Group
            'members_by_group' => Group::where('active', 1)
                ->where('name', '!=', 'All')
                ->withCount(['members' => function ($q) {
                    $q->where('active', 1);
                }])
                ->orderByDesc('members_count')
                ->limit(10)
                ->get(['name', 'members_count'])
                ->toArray(),

            // Members by Homecell
            'members_by_homecell' => Homecell::where('active', 1)
                ->withCount(['members' => function ($q) {
                    $q->where('active', 1);
                }])
                ->orderByDesc('members_count')
                ->limit(10)
                ->get(['name', 'members_count'])
                ->toArray(),

            // Age Distribution
            'age_distribution' => $this->getAgeDistribution(),

            // Join Date Trend
            'join_trend' => $this->getJoinTrend(),
        ];
    }

    private function getMembershipGrowth(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Member::where('active', 1)
                ->whereDate('created_at', '<=', $date->endOfMonth())
                ->count();
            $data[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        return $data;
    }

    private function getAgeDistribution(): array
    {
        $now = Carbon::now();
        $members = Member::where('active', 1)
            ->whereNotNull('birth_date')
            ->get(['birth_date']);

        $distribution = [
            '0-17' => 0,
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56-65' => 0,
            '65+' => 0,
        ];

        foreach ($members as $member) {
            $age = $now->diffInYears($member->birth_date);
            if ($age <= 17) $distribution['0-17']++;
            elseif ($age <= 25) $distribution['18-25']++;
            elseif ($age <= 35) $distribution['26-35']++;
            elseif ($age <= 45) $distribution['36-45']++;
            elseif ($age <= 55) $distribution['46-55']++;
            elseif ($age <= 65) $distribution['56-65']++;
            else $distribution['65+']++;
        }

        return $distribution;
    }

    private function getJoinTrend(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Member::where('active', 1)
                ->whereMonth('join_date', $date->month)
                ->whereYear('join_date', $date->year)
                ->count();
            $data[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }
        return $data;
    }

    private function getAverageAttendance($date): int
    {
        // Using EventAttendance model - count records for the month
        return (int) EventAttendance::whereMonth('attendance_date', $date->month)
            ->whereYear('attendance_date', $date->year)
            ->count() ?? 0;
    }

    /**
     * Get dashboard data via AJAX for real-time updates
     */
    public function getWidgetData(string $widget)
    {
        return match ($widget) {
            'birthdays' => $this->getUpcomingBirthdays(),
            'recent_members' => $this->getRecentMembers(),
            'attendance_trend' => $this->getAttendanceTrend(),
            'group_growth' => $this->getGroupGrowth(),
            default => response()->json(['error' => 'Widget not found'], 404),
        };
    }

    private function getUpcomingBirthdays()
    {
        $today = Carbon::now()->startOfDay();
        $nextWeek = $today->copy()->addDays(7)->endOfDay();

        $birthdays = Member::where('active', 1)
            ->whereNotNull('birth_date')
            ->get()
            ->filter(function ($member) use ($today, $nextWeek) {
                $birthday = $this->getNextBirthday($member->birth_date, $today);
                return $birthday->between($today, $nextWeek);
            })
            ->map(function ($member) use ($today) {
                $birthday = $this->getNextBirthday($member->birth_date, $today);
                return [
                    'id' => $member->id,
                    'name' => $member->full_name,
                    'date' => $birthday->format('M d'),
                    'age' => $today->diffInYears($member->birth_date),
                    'days_until' => $today->diffInDays($birthday),
                ];
            })
            ->sortBy('days_until')
            ->values();

        return response()->json($birthdays);
    }

    private function getNextBirthday($birthDate, $fromDate)
    {
        $birthday = $birthDate->copy()->year($fromDate->year);
        if ($birthday->lt($fromDate)) {
            $birthday->addYear();
        }
        return $birthday;
    }

    private function getRecentMembers()
    {
        $members = Member::where('active', 1)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'full_name', 'member_number', 'created_at', 'group_id', 'email'])
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->full_name,
                    'member_number' => $member->member_number,
                    'email' => $member->email,
                    'joined' => $member->created_at->diffForHumans(),
                    'group' => $member->group->name ?? 'N/A',
                ];
            });

        return response()->json($members);
    }

    private function getAttendanceTrend()
    {
        // Last 4 weeks attendance
        $data = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = Carbon::now()->subWeeks($i)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();

            // Count attendance records for the week
            $count = EventAttendance::whereBetween('attendance_date', [$weekStart, $weekEnd])
                ->count() ?? 0;

            $data[] = [
                'week' => 'Week ' . (4 - $i),
                'date' => $weekStart->format('M d'),
                'count' => $count,
            ];
        }

        return response()->json($data);
    }

    private function getGroupGrowth()
    {
        $groups = Group::where('active', 1)
            ->where('name', '!=', 'All')
            ->withCount(['members' => function ($q) {
                $q->where('active', 1);
            }])
            ->orderByDesc('members_count')
            ->limit(5)
            ->get();

        return response()->json($groups);
    }
}
