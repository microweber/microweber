<?php

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * A single reservation of a bookable product over a half-open interval
 * [starts_at, ends_at). Only CONSUMING statuses (confirmed / checked_in /
 * checked_out) count against availability — a pending (unpaid) booking does
 * not, because availability is locked only when the order is paid.
 */
class Booking extends Model
{

    /**
     * Fire lifecycle events so other modules can hook this model's changes.
     */
    protected $dispatchesEvents = [
        'created' => \Modules\Booking\Events\BookingWasCreated::class,
        'updated' => \Modules\Booking\Events\BookingWasUpdated::class,
        'deleted' => \Modules\Booking\Events\BookingWasDeleted::class,
    ];
    protected $table = 'bookings';

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_CHECKED_OUT = 'checked_out';
    public const STATUS_CANCELLED = 'cancelled';

    /** Statuses that occupy a unit (count against capacity). */
    public const CONSUMING_STATUSES = [
        self::STATUS_CONFIRMED,
        self::STATUS_CHECKED_IN,
        self::STATUS_CHECKED_OUT,
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'booking_type',
        'starts_at',
        'ends_at',
        'units',
        'guests',
        'status',
        'customer_id',
        'customer_email',
        'price',
        'currency',
        'checked_in_at',
        'checked_out_at',
        'notified_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'units' => 'integer',
        'guests' => 'integer',
        'price' => 'decimal:4',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'notified_at' => 'datetime',
    ];

    public function isConsuming(): bool
    {
        return in_array($this->status, self::CONSUMING_STATUSES, true);
    }

    public function scopeConsuming(Builder $query): Builder
    {
        return $query->whereIn('status', self::CONSUMING_STATUSES);
    }

    /**
     * Rows for $productId that overlap the half-open interval [$start, $end),
     * optionally widened by a symmetric buffer (minutes) for turnaround time.
     * Overlap on half-open ranges: start < other.end AND end > other.start.
     */
    public function scopeOverlapping(Builder $query, int $productId, $start, $end, int $bufferMinutes = 0): Builder
    {
        $start = \Illuminate\Support\Carbon::parse($start);
        $end = \Illuminate\Support\Carbon::parse($end);

        if ($bufferMinutes > 0) {
            // Widen the requested window so it also clears the buffer around
            // existing bookings (and vice-versa).
            $start = $start->copy()->subMinutes($bufferMinutes);
            $end = $end->copy()->addMinutes($bufferMinutes);
        }

        return $query->where('product_id', $productId)
            ->where('starts_at', '<', $end)
            ->where('ends_at', '>', $start);
    }
}
