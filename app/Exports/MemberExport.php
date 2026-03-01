<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Member;
use App\Models\Group;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export members to Excel with comprehensive filtering options.
 * Extends BaseExport for consistent styling and header formatting.
 */
class MemberExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    protected ?string $groupNames = null;

    /**
     * @param ReportFilterDTO|array|null $filters - Filters to apply, or legacy array of group IDs
     * @param string|null $groupNames - Group names for the title (legacy support)
     */
    public function __construct(ReportFilterDTO|array|null $filters = null, ?string $groupNames = null)
    {
        // Support both new DTO format and legacy array format for backward compatibility
        if ($filters instanceof ReportFilterDTO) {
            $this->filters = $filters;
        } elseif (is_array($filters)) {
            // Legacy support: treat array as group IDs
            $this->filters = ReportFilterDTO::make(['group_ids' => $filters]);
            $this->groupNames = $groupNames;
        } else {
            $this->filters = ReportFilterDTO::make();
        }

        // Load group names for display if not provided
        if ($this->groupNames === null && $this->filters->groupIds) {
            $this->groupNames = Group::whereIn('id', $this->filters->groupIds)
                ->pluck('name')
                ->implode(', ');
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Members';
    }

    /**
     * Get a human-readable description of the filters applied.
     */
    protected function getFilterDescription(): string
    {
        if ($this->groupNames) {
            return "Groups: " . $this->groupNames;
        }

        return $this->filters->getDescription();
    }

    /**
     * Get the last column letter for header merging.
     */
    protected function getLastColumn(): string
    {
        return 'AA'; // 27 columns
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Member::query()
            ->with(['group', 'homecell', 'ministries'])
            ->orderBy('member_number', 'asc');

        // Apply filters
        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }

        if ($this->filters->groupIds) {
            $query->whereIn('group_id', $this->filters->groupIds);
        }

        if ($this->filters->homecellIds) {
            $query->whereIn('homecell_id', $this->filters->homecellIds);
        }

        if ($this->filters->ministryIds) {
            $query->whereHas('ministries', function ($q) {
                $q->whereIn('ministries.id', $this->filters->ministryIds);
            });
        }

        if ($this->filters->gender) {
            $query->where('gender', $this->filters->gender);
        }

        if ($this->filters->maritalStatus) {
            $query->where('marital_status', $this->filters->maritalStatus);
        }

        // Age range filter
        if ($this->filters->ageMin !== null || $this->filters->ageMax !== null) {
            $query->whereNotNull('birth_date');
            
            if ($this->filters->ageMin !== null) {
                $maxBirthDate = Carbon::now()->subYears($this->filters->ageMin);
                $query->where('birth_date', '<=', $maxBirthDate);
            }
            
            if ($this->filters->ageMax !== null) {
                $minBirthDate = Carbon::now()->subYears($this->filters->ageMax + 1)->addDay();
                $query->where('birth_date', '>=', $minBirthDate);
            }
        }

        // Date range filter for join date
        if ($this->filters->dateFrom) {
            $query->where('join_date', '>=', $this->filters->dateFrom);
        }

        if ($this->filters->dateTo) {
            $query->where('join_date', '<=', $this->filters->dateTo);
        }

        return $query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Member #',
            'Full Name',
            'Phone',
            'Email',
            'Gender',
            'Birth Date',
            'Marital Status',
            'Spouse',
            'Spouse Phone',
            'Next of Kin',
            'Next of Kin Phone',
            'Join Date',
            'Official Join Date',
            'Residency',
            'Postal Address',
            'Occupation',
            'Born Again',
            'Spirit Filled When',
            'Water Immersed When',
            'From Church',
            'From Church Branch',
            'From Church Pastor',
            'From Church Pastor Phone',
            'Group',
            'Homecell',
            'Ministries',
            'Status',
        ];
    }

    /**
     * @param Member $member
     * @return array
     */
    public function map($member): array
    {
        return [
            $member->member_number,
            $member->full_name,
            $member->phone,
            $member->email,
            $member->gender,
            $this->formatDate($member->birth_date),
            $member->marital_status,
            $member->spouse,
            $member->spouse_number,
            $member->next_of_kin,
            $member->next_of_kin_number,
            $this->formatDate($member->join_date),
            $this->formatDate($member->official_join_date),
            $member->residency,
            $member->postal_address,
            $member->occupation,
            $this->formatBoolean($member->born_again),
            $this->formatDate($member->spirit_filled_when),
            $this->formatDate($member->water_immersed_when),
            $member->from_church,
            $member->from_church_branch,
            $member->from_church_pastor,
            $member->from_church_pastor_number,
            $this->getRelationValue($member, 'group', 'name'),
            $this->getRelationValue($member, 'homecell', 'name'),
            $member->ministries->pluck('name')->implode(', '),
            $this->formatBoolean($member->active, 'Active', 'Inactive'),
        ];
    }
}
