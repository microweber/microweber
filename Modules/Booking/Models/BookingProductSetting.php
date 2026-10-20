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
}
