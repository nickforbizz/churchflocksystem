<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MemberExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected $groupIds;
    protected $groupNames;

    /**
     * @param array|null $groupIds - Array of group IDs to filter by, or null for all members
     * @param string|null $groupNames - Group names for the title
     */
    public function __construct(?array $groupIds = null, ?string $groupNames = null)
    {
        $this->groupIds = $groupIds;
        $this->groupNames = $groupNames;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Members';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Insert 3 rows at the top for title
                $sheet->insertNewRowBefore(1, 3);
                
                // Set title text
                $titleText = env('APP_NAME', 'Church Flock System') . ' - Members Report';
                if ($this->groupNames) {
                    $titleText .= "\nGroups: " . $this->groupNames;
                } else {
                    $titleText .= "\nAll Members";
                }
                $titleText .= "\nGenerated: " . now()->format('F j, Y \a\t g:i A');
                
                $sheet->setCellValue('A1', $titleText);
                
                // Merge title cells across all columns (A1 to AA1)
                $sheet->mergeCells('A1:AA3');
                
                // Style the title
                $sheet->getStyle('A1:AA3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF4472C4'],
                    ],
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                ]);
                
                // Set row height for title
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(20);

                $sheet->getStyle('A4:AA4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE0E0E0'],
                    ],
                ]);
            },
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $query = Member::query()
            ->with(['group', 'homecell', 'ministries'])
            ->orderBy('member_number', 'asc');

        if (!empty($this->groupIds)) {
            $query->whereIn('group_id', $this->groupIds);
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
            $member->birth_date ? $member->birth_date->format('Y-m-d') : '',
            $member->marital_status,
            $member->spouse,
            $member->spouse_number,
            $member->next_of_kin,
            $member->next_of_kin_number,
            $member->join_date ? $member->join_date->format('Y-m-d') : '',
            $member->official_join_date ? $member->official_join_date->format('Y-m-d') : '',
            $member->residency,
            $member->postal_address,
            $member->occupation,
            $member->born_again ? 'Yes' : 'No',
            $member->spirit_filled_when ? $member->spirit_filled_when->format('Y-m-d') : '',
            $member->water_immersed_when ? $member->water_immersed_when->format('Y-m-d') : '',
            $member->from_church,
            $member->from_church_branch,
            $member->from_church_pastor,
            $member->from_church_pastor_number,
            $member->group->name ?? 'N/A',
            $member->homecell->name ?? 'N/A',
            $member->ministries->pluck('name')->implode(', '),
            $member->active ? 'Active' : 'Inactive',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            
        ];
    }
}
