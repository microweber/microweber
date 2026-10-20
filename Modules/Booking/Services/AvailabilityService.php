<?php

namespace Modules\Booking\Services;

use Illuminate\Support\Carbon;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingProductSetting;

/**
 * The availability engine: how many identical units of a bookable product are
 * free over a given interval, and whether a requested booking fits. Works for
 * both booking types — date_range (nightly) and time_slot (datetime ranges) —
 * because both reduce to overlapping half-open [start, end) intervals weighed
 * against the product's capacity.
 *
 * Only CONSUMING bookings (confirmed/checked-in/checked-out) count; a pending
 * (unpaid) booking does not reserve, since availability is locked on payment.
 */
class AvailabilityService
{
    public function settingsFor($productId): ?BookingProductSetting
    {
        return BookingProductSetting::active()->where('product_id', $productId)->first();
    }

    /**
     * Units already reserved for $productId across [$start, $end), honouring the
     * product's turnaround buffer. Excludes one booking id when re-checking an
     * existing row.
     */
    public function unitsReserved($productId, $start, $end, int $bufferMinutes = 0, ?int $excludeBookingId = null): int
    {
        $query = Booking::query()
            ->consuming()
            ->overlapping((int) $productId, $start, $end, $bufferMinutes);

        if ($excludeBookingId !== null) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return (int) $query->sum('units');
    }

    /**
     * Units still free over [$start, $end) given a capacity (defaults to the
     * product's configured capacity). Never negative.
     */
    public function remainingUnits($productId, $start, $end, ?int $capacity = null, int $bufferMinutes = 0, ?int $excludeBookingId = null): int
    {
        if ($capacity === null) {
            $setting = $this->settingsFor($productId);
            $capacity = $setting ? (int) $setting->capacity : 1;
            $bufferMinutes = $bufferMinutes ?: ($setting ? (int) $setting->buffer_minutes : 0);
        }

        $reserved = $this->unitsReserved($productId, $start, $end, $bufferMinutes, $excludeBookingId);

        return max(0, $capacity - $reserved);
    }

    public function isAvailable($productId, $start, $end, int $units = 1, ?int $capacity = null, int $bufferMinutes = 0, ?int $excludeBookingId = null): bool
    {
        return $this->remainingUnits($productId, $start, $end, $capacity, $bufferMinutes, $excludeBookingId) >= $units;
    }

    /**
     * Validate a booking request against the product's settings AND live
     * availability. Returns ['ok' => bool, 'reason' => ?string, 'nights'|'minutes' => ...].
     */
    public function validateRequest($productId, $start, $end, int $units = 1): array
    {
        $setting = $this->settingsFor($productId);
        if (!$setting) {
            return ['ok' => false, 'reason' => 'not_bookable'];
        }

        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        if ($end->lessThanOrEqualTo($start)) {
            return ['ok' => false, 'reason' => 'invalid_range'];
        }
        if ($units < 1) {
            return ['ok' => false, 'reason' => 'invalid_units'];
        }

        // Duration in the type's natural unit (nights vs minutes).
        if ($setting->isDateRange()) {
            $duration = $this->nights($start, $end);
        } else {
            $duration = (int) round($start->diffInMinutes($end));
        }

        if ($setting->min_duration && $duration < $setting->min_duration) {
            return ['ok' => false, 'reason' => 'below_min_duration', 'duration' => $duration];
        }
        if ($setting->max_duration && $duration > $setting->max_duration) {
            return ['ok' => false, 'reason' => 'above_max_duration', 'duration' => $duration];
        }

        if (!$this->isAvailable($productId, $start, $end, $units, (int) $setting->capacity, (int) $setting->buffer_minutes)) {
            return [
                'ok' => false,
                'reason' => 'unavailable',
                'remaining' => $this->remainingUnits($productId, $start, $end, (int) $setting->capacity, (int) $setting->buffer_minutes),
            ];
        }

        return ['ok' => true, 'reason' => null, 'duration' => $duration];
    }

    /**
     * Number of nights between two days (half-open: the check-out day is free).
     */
    public function nights($start, $end): int
    {
        return (int) Carbon::parse($start)->startOfDay()->diffInDays(Carbon::parse($end)->startOfDay());
    }

    /**
     * date_range calendar helper: the 'Y-m-d' nights in [$from, $to) that are
     * fully booked (no unit free), so the front-end can disable them.
     *
     * @return string[]
     */
    public function unavailableDates($productId, $from, $to): array
    {
        $setting = $this->settingsFor($productId);
        if (!$setting) {
            return [];
        }
        $capacity = (int) $setting->capacity;
        $buffer = (int) $setting->buffer_minutes;

        $cursor = Carbon::parse($from)->startOfDay();
        $end = Carbon::parse($to)->startOfDay();
        $out = [];

        // Guard against an unbounded range.
        $maxDays = 730;
        while ($cursor->lt($end) && $maxDays-- > 0) {
            $nightEnd = $cursor->copy()->addDay();
            if ($this->remainingUnits($productId, $cursor, $nightEnd, $capacity, $buffer) < 1) {
                $out[] = $cursor->format('Y-m-d');
            }
            $cursor->addDay();
        }

        return $out;
    }

    /**
     * time_slot calendar helper: the bookable slots on $date that still have a
     * free unit, within the product's daily open/close window.
     *
     * @return array<int, array{start:string, end:string, remaining:int}>
     */
    public function availableSlots($productId, $date): array
    {
        $setting = $this->settingsFor($productId);
        if (!$setting || !$setting->isTimeSlot() || !$setting->slot_minutes) {
            return [];
        }

        $day = Carbon::parse($date)->startOfDay();
        $opens = $setting->opens_at ? $day->copy()->setTimeFromTimeString((string) $setting->opens_at) : $day->copy();
        $closes = $setting->closes_at ? $day->copy()->setTimeFromTimeString((string) $setting->closes_at) : $day->copy()->endOfDay();

        $capacity = (int) $setting->capacity;
        $buffer = (int) $setting->buffer_minutes;
        $slot = (int) $setting->slot_minutes;

        $out = [];
        $cursor = $opens->copy();
        $guard = 500;
        while ($cursor->copy()->addMinutes($slot)->lte($closes) && $guard-- > 0) {
            $slotEnd = $cursor->copy()->addMinutes($slot);
            $remaining = $this->remainingUnits($productId, $cursor, $slotEnd, $capacity, $buffer);
            if ($remaining > 0) {
                $out[] = [
                    'start' => $cursor->format('Y-m-d H:i'),
                    'end' => $slotEnd->format('Y-m-d H:i'),
                    'remaining' => $remaining,
                ];
            }
            $cursor->addMinutes($slot);
        }

        return $out;
    }
}
