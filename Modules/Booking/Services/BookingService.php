<?php

namespace Modules\Booking\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Booking;
use Modules\Booking\Models\BookingProductSetting;

/**
 * Booking domain logic: price a requested booking by its duration, and turn a
 * paid order's bookable line items into confirmed Booking rows (which then
 * consume availability). Availability is only locked on payment, so this runs
 * from the OrderWasPaid / OrderWasUpdated(paid) path.
 */
class BookingService
{
    /** Cart line-item custom_fields_data keys carrying the chosen booking. */
    public const F_CHECK_IN = 'booking_check_in';
    public const F_CHECK_OUT = 'booking_check_out';
    public const F_START = 'booking_start';
    public const F_END = 'booking_end';
    public const F_UNITS = 'booking_units';
    public const F_GUESTS = 'booking_guests';

    public function __construct(protected AvailabilityService $availability)
    {
    }

    /**
     * Price a booking over [$start, $end) for $units, by the product's pricing
     * unit. Falls back to the product's own price when no unit_price is set.
     */
    public function priceFor(BookingProductSetting $setting, $start, $end, int $units = 1, ?float $productPrice = null): float
    {
        $unitPrice = $setting->unit_price !== null ? (float) $setting->unit_price : (float) ($productPrice ?? 0);
        $units = max(1, $units);

        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        if ($end->lessThanOrEqualTo($start)) {
            return 0.0;
        }

        switch ($setting->price_unit) {
            case 'flat':
                $quantity = 1;
                break;
            case 'per_hour':
                $quantity = $start->diffInMinutes($end) / 60;
                break;
            case 'per_slot':
                $slot = max(1, (int) ($setting->slot_minutes ?: 1));
                $quantity = max(1, (int) round($start->diffInMinutes($end) / $slot));
                break;
            case 'per_night':
            default:
                $quantity = max(1, $this->availability->nights($start, $end));
                break;
        }

        return round($unitPrice * $quantity * $units, 2);
    }

    /**
     * Read the booking interval chosen for a cart line item (from its
     * custom_fields_data), normalised to [start, end) datetimes, plus units and
     * guests. Returns null when the line item carries no booking selection.
     *
     * @return array{start:Carbon, end:Carbon, units:int, guests:?int}|null
     */
    public function intervalFromCartData(array $data, BookingProductSetting $setting): ?array
    {
        $units = max(1, (int) ($data[self::F_UNITS] ?? 1));
        $guests = isset($data[self::F_GUESTS]) ? (int) $data[self::F_GUESTS] : null;

        if ($setting->isDateRange()) {
            $in = $data[self::F_CHECK_IN] ?? null;
            $out = $data[self::F_CHECK_OUT] ?? null;
            if (!$in || !$out) {
                return null;
            }
            $start = Carbon::parse($in)->startOfDay();
            $end = Carbon::parse($out)->startOfDay();
            if ($setting->check_in_time) {
                $start->setTimeFromTimeString((string) $setting->check_in_time);
            }
            if ($setting->check_out_time) {
                $end->setTimeFromTimeString((string) $setting->check_out_time);
            }
        } else {
            $s = $data[self::F_START] ?? null;
            $e = $data[self::F_END] ?? null;
            if (!$s) {
                return null;
            }
            $start = Carbon::parse($s);
            $end = $e ? Carbon::parse($e)
                : $start->copy()->addMinutes((int) ($setting->slot_minutes ?: 60));
        }

        if ($end->lessThanOrEqualTo($start)) {
            return null;
        }

        return ['start' => $start, 'end' => $end, 'units' => $units, 'guests' => $guests];
    }

    /**
     * Create/confirm Booking rows for every bookable line item in a paid order.
     * Idempotent per (order_id, product_id, starts_at, ends_at). Re-checks
     * capacity at confirm time (the lock-on-pay point); an over-capacity confirm
     * is still recorded (the order is paid) but logged for the admin to resolve.
     *
     * @return Booking[]
     */
    public function confirmForOrder($order): array
    {
        $created = [];

        if (!$order || empty($order->id)) {
            return $created;
        }

        try {
            $items = $order->cart()->where('order_completed', 1)->get();
        } catch (\Throwable $e) {
            Log::warning('Booking: could not read order cart', ['order_id' => $order->id, 'exception' => $e->getMessage()]);

            return $created;
        }

        $email = data_get($order, 'email');
        $customerId = data_get($order, 'customer_id');
        $currency = data_get($order, 'currency');

        foreach ($items as $item) {
            $productId = $item->rel_id ?? null;
            if (!$productId) {
                continue;
            }

            $setting = BookingProductSetting::active()->where('product_id', $productId)->first();
            if (!$setting) {
                continue;
            }

            $data = $this->cartCustomData($item);
            $interval = $this->intervalFromCartData($data, $setting);
            if (!$interval) {
                Log::warning('Booking: paid booking line item has no date selection', [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                ]);
                continue;
            }

            $booking = Booking::firstOrCreate(
                [
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'starts_at' => $interval['start'],
                    'ends_at' => $interval['end'],
                ],
                [
                    'booking_type' => $setting->booking_type,
                    'units' => $interval['units'],
                    'guests' => $interval['guests'],
                    'status' => Booking::STATUS_CONFIRMED,
                    'customer_id' => $customerId,
                    'customer_email' => $email,
                    'price' => (float) ($item->price ?? 0),
                    'currency' => $currency,
                ]
            );

            // On first confirm, verify we didn't exceed capacity (lock-on-pay).
            if ($booking->wasRecentlyCreated) {
                $remaining = $this->availability->remainingUnits(
                    $productId,
                    $interval['start'],
                    $interval['end'],
                    (int) $setting->capacity,
                    (int) $setting->buffer_minutes,
                    $booking->id
                );
                if ($remaining < $interval['units']) {
                    Log::error('Booking: confirmed over capacity (paid order) — needs admin attention', [
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'booking_id' => $booking->id,
                        'requested_units' => $interval['units'],
                        'remaining_excluding_this' => $remaining,
                    ]);
                }
            }

            $created[] = $booking;
        }

        return $created;
    }

    private function cartCustomData($item): array
    {
        $data = $item->custom_fields_data ?? null;
        if (is_array($data)) {
            return $data;
        }
        if (is_string($data) && $data !== '') {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        // Fall back to the raw JSON copy.
        $json = $item->custom_fields_json ?? null;
        if (is_string($json) && $json !== '') {
            $decoded = json_decode($json, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return [];
    }
}
