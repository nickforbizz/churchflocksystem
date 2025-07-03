<?php

namespace App\Http\Controllers\cms;

use App\Exports\PostReportExport;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Member;
use App\Models\Donation;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request, ReportService $reportService)
    {
        $selectedYear = $request->get('year', Carbon::now()->year);
    
        try {
            // 1. Posts Report
            $posts_report = $reportService->getCountByMonth(new Post, $selectedYear);
    
            // 2. Membership Growth Report (using Member model is more accurate)
            $members_report = $reportService->getCountByMonth(new Member, $selectedYear);
    
            // 3. Donations Report (sum of amounts)
            $donationsByMonth = Donation::select(
                DB::raw('MONTH(date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('date', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')->all();
    
            $donationsChartData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthName = Carbon::create()->month($i)->format('F');
                $donationsChartData[$monthName] = $donationsByMonth[$i] ?? 0;
            }
            $donationYears = Donation::select(DB::raw('YEAR(date) as year'))
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')->toArray();
    
            // Combine all available years for the dropdown
            $allYears = collect($posts_report['years'])
                ->merge($members_report['years'])
                ->merge($donationYears)
                ->unique()
                ->sortDesc()
                ->values()
                ->all();
    
        } catch (\Throwable $th) {
            throw $th;
        }
        
        return view('cms.reports.index', [
            'postsChartData' => $posts_report['chartData'],
            'membersChartData' => $members_report['chartData'],
            'donationsChartData' => $donationsChartData,
            'allYears' => $allYears,
            'selectedYear' => $selectedYear
        ]);
    }


    public function downloadCsv(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $type = $request->input('type', 'posts');

        switch ($type) {
            case 'posts':
                $data = Post::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                            ->whereYear('created_at', $year)
                            ->groupBy('month')
                            ->get();
                // Assumes PostReportExport exists
                return Excel::download(new PostReportExport($data), 'posts_report_'.$year.'.xlsx');

            case 'members':
                $data = Member::select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                            ->whereYear('created_at', $year)
                            ->groupBy('month')
                            ->get();
                // TODO: Create MemberReportExport class
                // return Excel::download(new MemberReportExport($data), 'membership_report_'.$year.'.xlsx');
                break;

            case 'donations':
                $data = Donation::select(DB::raw('MONTH(date) as month'), DB::raw('SUM(amount) as total'))
                            ->whereYear('date', $year)
                            ->groupBy('month')
                            ->get();
                // TODO: Create DonationReportExport class
                // return Excel::download(new DonationReportExport($data), 'donations_report_'.$year.'.xlsx');
                break;
        }

        return redirect()->back()->with('error', 'Download for this report type is not yet implemented.');
    }

}
