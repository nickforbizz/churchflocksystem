<?php

namespace App\Exports;

use App\DTOs\ReportFilterDTO;
use App\Models\EventAttendance;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export event attendance records to Excel.
 * Shows individual attendance records with filtering options.
 */
class AttendanceExport extends BaseExport implements FromQuery, WithHeadings, WithMapping
{
    protected ReportFilterDTO $filters;
    private int $rowNumber = 0;

    public function __construct(ReportFilterDTO $filters)
    {
        $this->filters = $filters;
    }

    public function title(): string
    {
        return 'Event Attendance';
    }

    protected function getFilterDescription(): string
    {
        return $this->filters->getDescription();
    }

    protected function getLastColumn(): string
    {
        return 'J'; // 10 columns
    }

    public function query()
    {
        $query = EventAttendance::query()
            ->with(['event', 'member', 'member.group'])
            ->orderBy('attendance_date', 'desc')
            ->orderBy('event_id');

        if ($this->filters->activeOnly) {
            $query->where('active', 1);
        }

        if ($this->filters->dateFrom) {
            $query->where('attendance_date', '>=', $this->filters->dateFrom);
        }

        if ($this->filters->dateTo) {
            $query->where('attendance_date', '<=', $this->filters->dateTo);
        }

        if ($this->filters->eventIds) {
            $query->whereIn('event_id', $this->filters->eventIds);
        }

        if ($this->filters->attendanceStatus) {
            $query->where('status', $this->filters->attendanceStatus);
        }

        if ($this->filters->attendanceType) {
            $query->where('attendance_type', $this->filters->attendanceType);
        }

        if ($this->filters->memberId) {
            $query->where('member_id', $this->filters->memberId);
        }

        if ($this->filters->groupIds) {
            $query->whereHas('member', function ($q) {
                $q->whereIn('group_id', $this->filters->groupIds);
            });
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'Event',
            'Member',
            'Member #',
            'Attendance Date',
            'Attendance Time',
            'Status',
            'Type',
            'Group',
            'Notes',
        ];
    }

    public function map($attendance): array
    {
        $this->rowNumber++;

        $statusLabels = [
            'present' => 'Present',
            'absent' => 'Absent',
            'excused' => 'Excused',
        ];

        $typeLabels = [
            'in-person' => 'In-Person',
            'online' => 'Online',
        ];

        return [
            $this->rowNumber,
            $attendance->event->title ?? 'Unknown Event',
            $attendance->member->full_name ?? 'Unknown Member',
            $attendance->member->member_number ?? 'N/A',
            $this->formatDate($attendance->attendance_date),
            $attendance->attendance_time ? $attendance->attendance_time->format('H:i') : '',
            $statusLabels[$attendance->status] ?? ucfirst($attendance->status ?? 'N/A'),
            $typeLabels[$attendance->attendance_type] ?? ucfirst($attendance->attendance_type ?? 'N/A'),
            $this->getRelationValue($attendance->member ?? new \stdClass(), 'group', 'name', 'N/A'),
            $attendance->notes ?? '',
        ];
    }
}
