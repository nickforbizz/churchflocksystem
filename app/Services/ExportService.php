<?php

namespace App\Services;

use App\DTOs\ReportFilterDTO;
use App\Exports\MemberExport;
use App\Exports\GroupExport;
use App\Exports\DonationExport;
use App\Exports\AttendanceExport;
use App\Exports\HomecellExport;
use App\Exports\MinistryExport;
use App\Exports\BirthdayReportExport;
use App\Exports\MemberDemographicsExport;
use App\Exports\DonationSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use InvalidArgumentException;

/**
 * Service for handling report exports.
 * Uses Factory pattern to instantiate the correct export class.
 * 
 * Follows Dependency Inversion Principle - controllers depend on this abstraction.
 */
class ExportService
{
    /**
     * Available export types and their configurations.
     */
    protected array $exportTypes = [
        'members' => [
            'class' => MemberExport::class,
            'label' => 'Members Report',
            'description' => 'Export all members with their details',
            'filters' => ['groups', 'homecells', 'ministries', 'gender', 'marital_status', 'age', 'active'],
        ],
        'member-demographics' => [
            'class' => MemberDemographicsExport::class,
            'label' => 'Member Demographics',
            'description' => 'Summary of member demographics (age, gender, marital status)',
            'filters' => ['groups', 'active'],
        ],
        'groups' => [
            'class' => GroupExport::class,
            'label' => 'Groups Report',
            'description' => 'Export all groups with member counts',
            'filters' => ['active'],
        ],
        'donations' => [
            'class' => DonationExport::class,
            'label' => 'Donations Report',
            'description' => 'Export donation records with member details',
            'filters' => ['date_range', 'groups', 'purpose', 'method', 'amount', 'member'],
        ],
        'donation-summary' => [
            'class' => DonationSummaryExport::class,
            'label' => 'Donation Summary',
            'description' => 'Aggregated donation totals by purpose, method, or period',
            'filters' => ['date_range', 'groups', 'purpose', 'method'],
        ],
        'attendance' => [
            'class' => AttendanceExport::class,
            'label' => 'Attendance Report',
            'description' => 'Export event attendance records',
            'filters' => ['date_range', 'events', 'groups', 'status', 'type'],
        ],
        'homecells' => [
            'class' => HomecellExport::class,
            'label' => 'Homecells Report',
            'description' => 'Export all homecells with their details',
            'filters' => ['active'],
        ],
        'ministries' => [
            'class' => MinistryExport::class,
            'label' => 'Ministries Report',
            'description' => 'Export all ministries with member counts',
            'filters' => ['active'],
        ],
        'birthdays' => [
            'class' => BirthdayReportExport::class,
            'label' => 'Birthday Report',
            'description' => 'Members with upcoming birthdays',
            'filters' => ['month', 'groups', 'upcoming_days'],
        ],
    ];

    /**
     * Export data to Excel file.
     *
     * @param string $type Export type key
     * @param ReportFilterDTO $filters Applied filters
     * @param string|null $filename Custom filename (optional)
     * @return BinaryFileResponse
     * @throws InvalidArgumentException
     */
    public function export(string $type, ReportFilterDTO $filters, ?string $filename = null): BinaryFileResponse
    {
        $export = $this->createExport($type, $filters);
        $filename = $filename ?? $this->generateFilename($type, $filters);

        return Excel::download($export, $filename);
    }

    /**
     * Create an export instance based on type.
     *
     * @param string $type Export type key
     * @param ReportFilterDTO $filters Applied filters
     * @return object Export instance
     * @throws InvalidArgumentException
     */
    public function createExport(string $type, ReportFilterDTO $filters): object
    {
        if (!isset($this->exportTypes[$type])) {
            throw new InvalidArgumentException("Unknown export type: {$type}");
        }

        $config = $this->exportTypes[$type];
        $class = $config['class'];

        return new $class($filters);
    }

    /**
     * Generate a descriptive filename for the export.
     */
    public function generateFilename(string $type, ReportFilterDTO $filters): string
    {
        $parts = [
            str_replace('-', '_', $type),
            'report',
        ];

        if ($filters->year) {
            $parts[] = (string) $filters->year;
        }

        if ($filters->month) {
            $parts[] = str_pad((string) $filters->month, 2, '0', STR_PAD_LEFT);
        }

        if ($filters->dateFrom && $filters->dateTo) {
            $parts[] = $filters->dateFrom->format('Ymd');
            $parts[] = $filters->dateTo->format('Ymd');
        }

        $parts[] = now()->format('YmdHis');

        return implode('_', $parts) . '.xlsx';
    }

    /**
     * Get all available export types with their metadata.
     */
    public function getAvailableExports(): array
    {
        return collect($this->exportTypes)->map(function ($config, $key) {
            return [
                'key' => $key,
                'label' => $config['label'],
                'description' => $config['description'],
                'available_filters' => $config['filters'],
            ];
        })->values()->toArray();
    }

    /**
     * Get available export types grouped by category.
     */
    public function getExportsByCategory(): array
    {
        return [
            'membership' => [
                'label' => 'Membership Reports',
                'icon' => 'fas fa-users',
                'exports' => [
                    $this->getExportInfo('members'),
                    $this->getExportInfo('member-demographics'),
                    $this->getExportInfo('birthdays'),
                ],
            ],
            'organization' => [
                'label' => 'Organization Reports',
                'icon' => 'fas fa-sitemap',
                'exports' => [
                    $this->getExportInfo('groups'),
                    $this->getExportInfo('homecells'),
                    $this->getExportInfo('ministries'),
                ],
            ],
            'financial' => [
                'label' => 'Financial Reports',
                'icon' => 'fas fa-money-bill-wave',
                'exports' => [
                    $this->getExportInfo('donations'),
                    $this->getExportInfo('donation-summary'),
                ],
            ],
            'attendance' => [
                'label' => 'Attendance Reports',
                'icon' => 'fas fa-calendar-check',
                'exports' => [
                    $this->getExportInfo('attendance'),
                ],
            ],
        ];
    }

    /**
     * Get export info by key.
     */
    protected function getExportInfo(string $key): array
    {
        $config = $this->exportTypes[$key] ?? null;

        if (!$config) {
            return ['key' => $key, 'label' => 'Unknown', 'description' => '', 'available_filters' => []];
        }

        return [
            'key' => $key,
            'label' => $config['label'],
            'description' => $config['description'],
            'available_filters' => $config['filters'],
        ];
    }

    /**
     * Check if an export type exists.
     */
    public function hasExportType(string $type): bool
    {
        return isset($this->exportTypes[$type]);
    }

    /**
     * Get the filters available for a specific export type.
     */
    public function getFiltersForType(string $type): array
    {
        return $this->exportTypes[$type]['filters'] ?? [];
    }
}
