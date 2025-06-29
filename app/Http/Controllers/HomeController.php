<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\Group;
use App\Models\Member;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cms(ReportService $reportService)
    {
        // Top Stat Cards
        $totalMembers = Member::count();
        $totalGroups = Group::count();
        $donationsThisMonth = Donation::whereMonth('date', now()->month)->whereYear('date', now()->year)->sum('amount');
        $upcomingEvents = Event::where('event_date', '>=', now())->count();

        // Membership Growth Chart (for the current year)
        $membershipReport = $reportService->getCountByMonth(new Member, now()->year);
        $membershipGrowthData = $membershipReport['chartData'];

        // Donations by Purpose Chart (for the current year)
        $donationsByPurpose = Donation::whereYear('date', now()->year)
            ->select('purpose', DB::raw('SUM(amount) as total'))
            ->groupBy('purpose')
            ->orderBy('total', 'desc')
            ->limit(5) // Top 5 purposes
            ->pluck('total', 'purpose');

        // Recent Activity Lists
        $recentMembers = Member::with('group')->latest()->take(5)->get();
        $recentDonations = Donation::with('member')->latest()->take(5)->get();

        return view('cms.index', compact(
            'totalMembers',
            'totalGroups',
            'donationsThisMonth',
            'upcomingEvents',
            'membershipGrowthData',
            'donationsByPurpose',
            'recentMembers',
            'recentDonations'
        ));
    }
}
