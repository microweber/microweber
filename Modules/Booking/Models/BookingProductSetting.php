<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Per-product booking configuration: the booking type, how many units can be
 * booked at once (capacity), pricing unit, duration bounds and (for time-slot
 * products) the daily slot window.
 */
class BookingProductSetting extends Model
{
    public const TYPE_DATE_RANGE = 'date_range';
    public const TYPE_TIME_SLOT = 'time_slot';

    protected $table = 'booking_product_settings';

    protected $fillable = [
        'product_id',
        'booking_type',
        'capacity',
        'price_unit',
        'unit_price',
        'min_duration',
        'max_duration',
        'slot_minutes',
        'opens_at',
        'closes_at',
        'buffer_minutes',
        'check_in_time',
        'check_out_time',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'unit_price' => 'decimal:4',
        'min_duration' => 'integer',
        'max_duration' => 'integer',
        'slot_minutes' => 'integer',
        'buffer_minutes' => 'integer',
        'is_active' => 'boolean',
    ];

    public function isDateRange(): bool
    {
        return $this->booking_type === self::TYPE_DATE_RANGE;
    }

    public function isTimeSlot(): bool
    {
        return $this->booking_type === self::TYPE_TIME_SLOT;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Upsert a product's booking settings from Product-form field values. When
     * the product is not (any longer) bookable the row is deactivated rather
     * than deleted, so its configuration survives a toggle-off/on.
     *
     * Expects the `booking_*` form keys (booking_type, booking_capacity,
     * booking_price_unit, booking_unit_price, booking_min_duration,
     * booking_max_duration, booking_slot_minutes, booking_opens_at,
     * booking_closes_at, booking_buffer_minutes, booking_check_in_time,
     * booking_check_out_time).
     */
    public static function syncFromForm(int $productId, array $form, bool $isBookable): ?self
    {
        if (!$isBookable) {
            self::where('product_id', $productId)->update(['is_active' => false]);

            return null;
        }

        $int = static fn ($v) => ($v === null || $v === '') ? null : (int) $v;

        return self::updateOrCreate(
            ['product_id' => $productId],
            [
                'booking_type' => ($form['booking_type'] ?? self::TYPE_DATE_RANGE) === self::TYPE_TIME_SLOT
                    ? self::TYPE_TIME_SLOT : self::TYPE_DATE_RANGE,
                'capacity' => max(1, (int) ($form['booking_capacity'] ?? 1)),
                'price_unit' => in_array(($form['booking_price_unit'] ?? null), ['per_night', 'per_hour', 'per_slot', 'flat'], true)
                    ? $form['booking_price_unit'] : 'per_night',
                'unit_price' => ($form['booking_unit_price'] ?? null) === '' ? null : ($form['booking_unit_price'] ?? null),
                'min_duration' => $int($form['booking_min_duration'] ?? null),
                'max_duration' => $int($form['booking_max_duration'] ?? null),
                'slot_minutes' => $int($form['booking_slot_minutes'] ?? null),
                'opens_at' => $form['booking_opens_at'] ?? null,
                'closes_at' => $form['booking_closes_at'] ?? null,
                'buffer_minutes' => (int) ($form['booking_buffer_minutes'] ?? 0),
                'check_in_time' => $form['booking_check_in_time'] ?? null,
                'check_out_time' => $form['booking_check_out_time'] ?? null,
                'is_active' => true,
            ]
        );
    }

    /**
     * Reflect a stored settings row back into Product-form field values.
     */
    public function toFormData(): array
    {
        return [
            'booking_type' => $this->booking_type,
            'booking_capacity' => $this->capacity,
            'booking_price_unit' => $this->price_unit,
            'booking_unit_price' => $this->unit_price,
            'booking_min_duration' => $this->min_duration,
            'booking_max_duration' => $this->max_duration,
            'booking_slot_minutes' => $this->slot_minutes,
            'booking_opens_at' => $this->opens_at,
            'booking_closes_at' => $this->closes_at,
            'booking_buffer_minutes' => $this->buffer_minutes,
            'booking_check_in_time' => $this->check_in_time,
            'booking_check_out_time' => $this->check_out_time,
        ];
    }
}
