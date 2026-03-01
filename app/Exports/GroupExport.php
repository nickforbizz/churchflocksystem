<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\Group;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export groups to Excel with member counts.
 * Extends BaseExport for consistent styling.
 */
class GroupExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    private int $rowNumber = 0;

    public function __construct(ReportFilterDTO|null $filters = null)
    {
        $this->filters = $filters ?? ReportFilterDTO::make();
    }

    public function title(): string
    {
        return 'Groups';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'F'; // 6 columns
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Group::query()
            ->with(['user', 'members'])
            ->withCount('members')
            ->orderBy('name', 'asc');

        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }

        return $query;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            'Group Name',
            'Members Count',
            'Status',
            'Created By',
            'Created At',
        ];
    } 

    /**
     * @param Group $group
     * @return array
     */
    public function map($group): array
    {
        $this->rowNumber++;

        // If group name is "All", show total active members count
        if (strtolower($group->name) === 'all') {
            $membersCount = Member::where('active', 1)->count();
        } else {
            // Ensure 0 is shown instead of empty/null
            $membersCount = $group->members_count > 0 ? $group->members_count : '0';
        }

        return [
            $this->rowNumber,
            $group->name,
            $membersCount,
            $this->formatBoolean($group->active, 'Active', 'Inactive'),
            $this->getRelationValue($group, 'user', 'name'),
            $this->formatDate($group->created_at, 'Y-m-d H:i:s'),
        ];
    }
}
