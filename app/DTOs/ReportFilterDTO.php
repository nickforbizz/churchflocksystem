<?php

namespace App\DTOs;

use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Data Transfer Object for report filters.
 * Provides type-safe filter handling across the reporting system.
 * 
 * Follows Open/Closed Principle - new filter types can be added via composition.
 */
class ReportFilterDTO
{
    public function __construct(
        public readonly ?Carbon $dateFrom = null,
        public readonly ?Carbon $dateTo = null,
        public readonly ?array $groupIds = null,
        public readonly ?array $homecellIds = null,
        public readonly ?array $ministryIds = null,
        public readonly ?array $eventIds = null,
        public readonly ?string $gender = null,
        public readonly ?string $maritalStatus = null,
        public readonly ?string $attendanceStatus = null,
        public readonly ?string $attendanceType = null,
        public readonly ?string $donationPurpose = null,
        public readonly ?string $donationMethod = null,
        public readonly ?float $amountMin = null,
        public readonly ?float $amountMax = null,
        public readonly ?int $ageMin = null,
        public readonly ?int $ageMax = null,
        public readonly ?int $year = null,
        public readonly ?int $month = null,
        public readonly bool $activeOnly = true,
        public readonly ?int $memberId = null,
        public readonly ?string $sortBy = null,
        public readonly string $sortDirection = 'asc',
    ) {}

    /**
     * Create a DTO from an HTTP request.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            dateFrom: $request->filled('date_from') ? Carbon::parse($request->input('date_from')) : null,
            dateTo: $request->filled('date_to') ? Carbon::parse($request->input('date_to')) : null,
            groupIds: $request->filled('group_ids') ? (array) $request->input('group_ids') : null,
            homecellIds: $request->filled('homecell_ids') ? (array) $request->input('homecell_ids') : null,
            ministryIds: $request->filled('ministry_ids') ? (array) $request->input('ministry_ids') : null,
            eventIds: $request->filled('event_ids') ? (array) $request->input('event_ids') : null,
            gender: $request->input('gender'),
            maritalStatus: $request->input('marital_status'),
            attendanceStatus: $request->input('attendance_status'),
            attendanceType: $request->input('attendance_type'),
            donationPurpose: $request->input('donation_purpose'),
            donationMethod: $request->input('donation_method'),
            amountMin: $request->filled('amount_min') ? (float) $request->input('amount_min') : null,
            amountMax: $request->filled('amount_max') ? (float) $request->input('amount_max') : null,
            ageMin: $request->filled('age_min') ? (int) $request->input('age_min') : null,
            ageMax: $request->filled('age_max') ? (int) $request->input('age_max') : null,
            year: $request->filled('year') ? (int) $request->input('year') : null,
            month: $request->filled('month') ? (int) $request->input('month') : null,
            activeOnly: $request->boolean('active_only', true),
            memberId: $request->filled('member_id') ? (int) $request->input('member_id') : null,
            sortBy: $request->input('sort_by'),
            sortDirection: $request->input('sort_direction', 'asc'),
        );
    }

    /**
     * Create a DTO with specific values.
     */
    public static function make(array $attributes = []): self
    {
        return new self(
            dateFrom: isset($attributes['date_from']) ? Carbon::parse($attributes['date_from']) : null,
            dateTo: isset($attributes['date_to']) ? Carbon::parse($attributes['date_to']) : null,
            groupIds: $attributes['group_ids'] ?? null,
            homecellIds: $attributes['homecell_ids'] ?? null,
            ministryIds: $attributes['ministry_ids'] ?? null,
            eventIds: $attributes['event_ids'] ?? null,
            gender: $attributes['gender'] ?? null,
            maritalStatus: $attributes['marital_status'] ?? null,
            attendanceStatus: $attributes['attendance_status'] ?? null,
            attendanceType: $attributes['attendance_type'] ?? null,
            donationPurpose: $attributes['donation_purpose'] ?? null,
            donationMethod: $attributes['donation_method'] ?? null,
            amountMin: $attributes['amount_min'] ?? null,
            amountMax: $attributes['amount_max'] ?? null,
            ageMin: $attributes['age_min'] ?? null,
            ageMax: $attributes['age_max'] ?? null,
            year: $attributes['year'] ?? null,
            month: $attributes['month'] ?? null,
            activeOnly: $attributes['active_only'] ?? true,
            memberId: $attributes['member_id'] ?? null,
            sortBy: $attributes['sort_by'] ?? null,
            sortDirection: $attributes['sort_direction'] ?? 'asc',
        );
    }

    /**
     * Get a human-readable description of the applied filters.
     */
    public function getDescription(): string
    {
        $parts = [];

        if ($this->dateFrom && $this->dateTo) {
            $parts[] = "Period: {$this->dateFrom->format('M j, Y')} - {$this->dateTo->format('M j, Y')}";
        } elseif ($this->dateFrom) {
            $parts[] = "From: {$this->dateFrom->format('M j, Y')}";
        } elseif ($this->dateTo) {
            $parts[] = "Until: {$this->dateTo->format('M j, Y')}";
        }

        if ($this->year) {
            $monthName = $this->month ? Carbon::create(null, $this->month)->format('F') . ' ' : '';
            $parts[] = "Year: {$monthName}{$this->year}";
        }

        if ($this->groupIds && count($this->groupIds) > 0) {
            $count = count($this->groupIds);
            $parts[] = $count === 1 ? "1 Group" : "{$count} Groups";
        }

        if ($this->homecellIds && count($this->homecellIds) > 0) {
            $count = count($this->homecellIds);
            $parts[] = $count === 1 ? "1 Homecell" : "{$count} Homecells";
        }

        if ($this->ministryIds && count($this->ministryIds) > 0) {
            $count = count($this->ministryIds);
            $parts[] = $count === 1 ? "1 Ministry" : "{$count} Ministries";
        }

        if ($this->eventIds && count($this->eventIds) > 0) {
            $count = count($this->eventIds);
            $parts[] = $count === 1 ? "1 Event" : "{$count} Events";
        }

        if ($this->gender) {
            $parts[] = "Gender: " . ucfirst($this->gender);
        }

        if ($this->maritalStatus) {
            $parts[] = "Marital Status: " . ucfirst($this->maritalStatus);
        }

        if ($this->attendanceStatus) {
            $parts[] = "Status: " . ucfirst($this->attendanceStatus);
        }

        if ($this->attendanceType) {
            $parts[] = "Type: " . ucfirst($this->attendanceType);
        }

        if ($this->donationPurpose) {
            $parts[] = "Purpose: " . ucfirst($this->donationPurpose);
        }

        if ($this->donationMethod) {
            $parts[] = "Method: " . ucfirst($this->donationMethod);
        }

        if ($this->amountMin !== null || $this->amountMax !== null) {
            if ($this->amountMin !== null && $this->amountMax !== null) {
                $parts[] = "Amount: " . number_format($this->amountMin) . " - " . number_format($this->amountMax);
            } elseif ($this->amountMin !== null) {
                $parts[] = "Amount Min: " . number_format($this->amountMin);
            } else {
                $parts[] = "Amount Max: " . number_format($this->amountMax);
            }
        }

        if ($this->ageMin !== null || $this->ageMax !== null) {
            if ($this->ageMin !== null && $this->ageMax !== null) {
                $parts[] = "Age: {$this->ageMin} - {$this->ageMax}";
            } elseif ($this->ageMin !== null) {
                $parts[] = "Age Min: {$this->ageMin}";
            } else {
                $parts[] = "Age Max: {$this->ageMax}";
            }
        }

        $parts[] = $this->activeOnly ? "Active Only" : "All Records";

        return empty($parts) ? "All Records" : implode(' | ', $parts);
    }

    /**
     * Convert to array for serialization.
     */
    public function toArray(): array
    {
        return array_filter([
            'date_from' => $this->dateFrom?->format('Y-m-d'),
            'date_to' => $this->dateTo?->format('Y-m-d'),
            'group_ids' => $this->groupIds,
            'homecell_ids' => $this->homecellIds,
            'ministry_ids' => $this->ministryIds,
            'event_ids' => $this->eventIds,
            'gender' => $this->gender,
            'marital_status' => $this->maritalStatus,
            'attendance_status' => $this->attendanceStatus,
            'attendance_type' => $this->attendanceType,
            'donation_purpose' => $this->donationPurpose,
            'donation_method' => $this->donationMethod,
            'amount_min' => $this->amountMin,
            'amount_max' => $this->amountMax,
            'age_min' => $this->ageMin,
            'age_max' => $this->ageMax,
            'year' => $this->year,
            'month' => $this->month,
            'active_only' => $this->activeOnly,
            'member_id' => $this->memberId,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ], fn($value) => $value !== null);
    }

    /**
     * Check if any filters are applied.
     */
    public function hasFilters(): bool
    {
        return $this->dateFrom !== null
            || $this->dateTo !== null
            || !empty($this->groupIds)
            || !empty($this->homecellIds)
            || !empty($this->ministryIds)
            || !empty($this->eventIds)
            || $this->gender !== null
            || $this->maritalStatus !== null
            || $this->attendanceStatus !== null
            || $this->attendanceType !== null
            || $this->donationPurpose !== null
            || $this->donationMethod !== null
            || $this->amountMin !== null
            || $this->amountMax !== null
            || $this->ageMin !== null
            || $this->ageMax !== null
            || $this->year !== null
            || $this->memberId !== null;
    }

    /**
     * Create a copy with modified attributes.
     */
    public function with(array $attributes): self
    {
        return self::make(array_merge($this->toArray(), $attributes));
    }
}
