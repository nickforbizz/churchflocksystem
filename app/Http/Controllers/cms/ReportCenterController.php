<?php

namespace App\Http\Controllers\cms;

use App\DTOs\ReportFilterDTO;
use App\Http\Controllers\Controller;
use App\Services\ExportService;
use App\Services\MemberAnalyticsService;
use App\Models\Group;
use App\Models\Homecell;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Valuelist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controller for the unified Report Center.
 * Provides a single interface for all reporting needs.
 */
class ReportCenterController extends Controller
{
    public function __construct(
        protected ExportService $exportService,
        protected MemberAnalyticsService $analyticsService
    ) {}

    /**
     * Display the Report Center dashboard.
     */
    public function index()
    {
        $exportTypes = $this->exportService->getExportsByCategory();
        $filterOptions = $this->getFilterOptions();

        return view('cms.reports.center', compact('exportTypes', 'filterOptions'));
    }

    /**
     * Export data based on the selected report type and filters.
     */
    public function export(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
        ]);

        $type = $request->input('report_type');

        if (!$this->exportService->hasExportType($type)) {
            return back()->with('error', 'Invalid report type selected.');
        }

        $filters = ReportFilterDTO::fromRequest($request);

        try {
            if ($request->ajax()) {
                // For preview, we return the data as JSON
                $data = $this->exportService->export($type, $filters);
                return response()->json([
                    'success' => true,
                    'data' => $data,
                ]);
            }
            // if it was a normal request, we return the file download
            return $this->exportService->export($type, $filters);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate report: ' . $e->getMessage());
        }
    }

    /**
     * Get chart data for the report preview (AJAX endpoint).
     */
    public function getChartData(Request $request): JsonResponse
    {
        $chartType = $request->input('chart_type', 'membership_growth');
        $filters = ReportFilterDTO::fromRequest($request);

        $data = match ($chartType) {
            'membership_growth' => $this->analyticsService->getGrowthTrend($filters),
            'new_members' => $this->analyticsService->getNewMembersTrend($filters),
            'age_distribution' => $this->analyticsService->getAgeDistribution($filters),
            'gender_distribution' => $this->analyticsService->getGenderDistribution($filters),
            'marital_status' => $this->analyticsService->getMaritalStatusDistribution($filters),
            'members_by_group' => $this->analyticsService->getMembersByGroup($filters),
            'members_by_homecell' => $this->analyticsService->getMembersByHomecell($filters),
            'members_by_ministry' => $this->analyticsService->getMembersByMinistry($filters),
            'spiritual_growth' => $this->analyticsService->getSpiritualGrowthMetrics($filters),
            'donation_trend' => $this->analyticsService->getDonationTrend($filters),
            'donations_by_purpose' => $this->analyticsService->getDonationsByPurpose($filters),
            'donations_by_method' => $this->analyticsService->getDonationsByMethod($filters),
            'attendance_summary' => $this->analyticsService->getAttendanceSummary($filters),
            'attendance_trend' => $this->analyticsService->getAttendanceTrend($filters),
            'summary_stats' => $this->analyticsService->getSummaryStats($filters),
            default => [],
        };

        return response()->json([
            'success' => true,
            'chart_type' => $chartType,
            'data' => $data,
            'filters' => $filters->toArray(),
        ]);
    }

    /**
     * Get filter options for dynamic dropdowns (AJAX endpoint).
     */
    public function getFilterOptionsAjax(Request $request): JsonResponse
    {
        $filterType = $request->input('filter_type');
        
        $options = match ($filterType) {
            'groups' => Group::where('active', 1)
                ->where('name', '!=', 'All')
                ->orderBy('name')
                ->get(['id', 'name']),
            'homecells' => Homecell::where('active', 1)
                ->orderBy('primary_cell')
                ->get(['id', 'primary_cell']),
            'ministries' => Ministry::where('active', 1)
                ->orderBy('name')
                ->get(['id', 'name']),
            'events' => Event::orderByDesc('event_date')
                ->limit(50)
                ->get(['id', 'title', 'event_date']),
            'genders' => $this->getValuelistOptions('gender'),
            'marital_statuses' => $this->getValuelistOptions('marital_status'),
            'donation_purposes' => $this->getValuelistOptions('donation_purpose'),
            'donation_methods' => $this->getValuelistOptions('donation_method'),
            default => [],
        };

        return response()->json([
            'success' => true,
            'options' => $options,
        ]);
    }

    /**
     * Get all filter options for initial page load.
     */
    protected function getFilterOptions(): array
    {
        return [
            'groups' => Group::where('active', 1)
                ->where('name', '!=', 'All')
                ->orderBy('name')
                ->get(['id', 'name']),
            'homecells' => Homecell::where('active', 1)
                ->orderBy('primary_cell')
                ->get(['id', 'primary_cell']),
            'ministries' => Ministry::where('active', 1)
                ->orderBy('name')
                ->get(['id', 'name']),
            'events' => Event::orderByDesc('event_date')
                ->limit(50)
                ->get(['id', 'title', 'event_date']),
            'genders' => $this->getValuelistOptions('gender'),
            'marital_statuses' => $this->getValuelistOptions('marital_status'),
            'donation_purposes' => $this->getDonationPurposes(),
            'donation_methods' => $this->getDonationMethods(),
            'years' => $this->getAvailableYears(),
            'months' => $this->getMonthOptions(),
        ];
    }

    /**
     * Get options from valuelist by type.
     */
    protected function getValuelistOptions(string $type): array
    {
        try {
            return Valuelist::where('type', $type)
                ->where('active', 1)
                ->orderBy('index')
                ->get(['value', 'value as label'])
                ->toArray();
        } catch (\Exception $e) {
            // Return common defaults if valuelist doesn't exist
            return match ($type) {
                'gender' => [
                    ['value' => 'male', 'label' => 'Male'],
                    ['value' => 'female', 'label' => 'Female'],
                ],
                'marital_status' => [
                    ['value' => 'single', 'label' => 'Single'],
                    ['value' => 'married', 'label' => 'Married'],
                    ['value' => 'divorced', 'label' => 'Divorced'],
                    ['value' => 'widowed', 'label' => 'Widowed'],
                ],
                default => [],
            };
        }
    }

    /**
     * Get donation purposes from existing data.
     */
    protected function getDonationPurposes(): array
    {
        return \App\Models\Donation::select('purpose')
            ->distinct()
            ->whereNotNull('purpose')
            ->orderBy('purpose')
            ->pluck('purpose')
            ->map(fn($p) => ['value' => $p, 'label' => ucfirst($p)])
            ->toArray();
    }

    /**
     * Get donation methods from existing data.
     */
    protected function getDonationMethods(): array
    {
        return \App\Models\Donation::select('method')
            ->distinct()
            ->whereNotNull('method')
            ->orderBy('method')
            ->pluck('method')
            ->map(fn($m) => ['value' => $m, 'label' => ucfirst($m)])
            ->toArray();
    }

    /**
     * Get available years from data.
     */
    protected function getAvailableYears(): array
    {
        $currentYear = now()->year;
        $years = [];

        for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
            $years[] = ['value' => $y, 'label' => (string) $y];
        }

        return $years;
    }

    /**
     * Get month options.
     */
    protected function getMonthOptions(): array
    {
        $months = [];
        
        for ($m = 1; $m <= 12; $m++) {
            $months[] = [
                'value' => $m,
                'label' => \Carbon\Carbon::create(null, $m)->format('F'),
            ];
        }

        return $months;
    }

    /**
     * Get dashboard summary data (AJAX endpoint).
     */
    public function getDashboardSummary(Request $request): JsonResponse
    {
        $filters = ReportFilterDTO::fromRequest($request);
        
        return response()->json([
            'success' => true,
            'summary' => $this->analyticsService->getSummaryStats($filters),
            'spiritual_growth' => $this->analyticsService->getSpiritualGrowthMetrics($filters),
        ]);
    }
}
