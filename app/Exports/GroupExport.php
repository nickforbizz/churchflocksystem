<?php

namespace App\Exports;

use App\Models\Group;
use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GroupExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    private int $rowNumber = 0;

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Group::query()
            ->with(['user', 'members'])
            ->withCount('members')
            ->orderBy('name', 'asc');
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
            $group->active ? 'Active' : 'Inactive',
            $group->user->name ?? 'N/A',
            $group->created_at ? $group->created_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
            ],
        ];
    }
}
