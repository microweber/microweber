<?php

declare(strict_types=1);

namespace Modules\Invoice\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Invoice\Models\Invoice;

/**
 * @mixin Invoice
 */
final class InvoiceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'invoice_number' => $this->invoice_number,
            'reference_number' => $this->reference_number,
            'invoice_date' => $this->invoice_date?->toDateString(),
            'due_date' => $this->due_date?->toDateString(),
            'customer_id' => $this->customer_id !== null ? (int) $this->customer_id : null,
            'company_id' => $this->company_id !== null ? (int) $this->company_id : null,
            'invoice_template_id' => $this->invoice_template_id !== null ? (int) $this->invoice_template_id : null,
            'status' => $this->status,
            'paid_status' => $this->paid_status,
            'sub_total' => (int) $this->sub_total,
            'discount' => $this->discount !== null ? (float) $this->discount : 0.0,
            'discount_type' => $this->discount_type,
            'discount_val' => (int) ($this->discount_val ?? 0),
            'total' => (int) $this->total,
            'due_amount' => (int) $this->due_amount,
            'tax' => $this->tax !== null ? (float) $this->tax : 0.0,
            'tax_per_item' => (bool) $this->tax_per_item,
            'discount_per_item' => (bool) $this->discount_per_item,
            'notes' => $this->notes,
            'unique_hash' => $this->unique_hash,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
