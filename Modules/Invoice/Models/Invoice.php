<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Customer\Models\Customer;

class Invoice extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_VIEWED = 'viewed';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_PAID = 'paid';
    const STATUS_COMPLETED = 'completed';
    const STATUS_VOID = 'void';

    const STATUS_UNPAID = 'unpaid';
    const STATUS_PARTIALLY_PAID = 'partially_paid';
    const STATUS_REFUNDED = 'refunded';


    protected $fillable = [
        'invoice_date',
        'due_date',
        'invoice_number',
        'reference_number',
        'customer_id',
        'company_id',
        'invoice_template_id',
        'status',
        'paid_status',
        'sub_total',
        'discount',
        'discount_type',
        'discount_val',
        'total',
        'due_amount',
        'tax_per_item',
        'discount_per_item',
        'tax',
        'notes',
        'unique_hash'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'tax_per_item' => 'boolean',
        'discount_per_item' => 'boolean',
        'sub_total' => 'integer',
        'discount_val' => 'integer',
        'total' => 'integer',
        'due_amount' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public static function getNextInvoiceNumber($prefix = ''): int
    {
        $lastInvoice = self::orderBy('id', 'desc')->first();
        if (!$lastInvoice) {
            return 1;
        }

        $lastNumber = (int)str_replace($prefix . '-', '', $lastInvoice->invoice_number);
        return $lastNumber + 1;
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }


}
