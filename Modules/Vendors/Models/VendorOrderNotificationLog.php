<?php

namespace Modules\Vendors\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One row per (vendor, order) that has already been notified — the
 * idempotency ledger that keeps a vendor from being told twice about the
 * same order when both OrderWasPaid and the admin mark-paid fire.
 */
class VendorOrderNotificationLog extends Model
{
    protected $table = 'vendor_order_notifications';

    protected $fillable = [
        'vendor_id',
        'order_id',
    ];
}
