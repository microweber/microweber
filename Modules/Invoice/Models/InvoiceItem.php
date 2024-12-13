<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'name',
        'description',
        'price',
        'quantity'
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer'
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price / 100, 2);
    }

    public function getSubtotalAttribute(): int
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal / 100, 2);
    }
}
