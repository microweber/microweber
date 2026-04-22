<?php

declare(strict_types=1);

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Models\Order;

/**
 * @mixin Order
 */
final class OrderResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $viewer = $request->user();
        $isAdmin = $viewer !== null && (int) $viewer->is_admin === 1;

        $data = [
            'id' => (int) $this->id,
            'order_reference_id' => $this->order_reference_id,
            'order_status' => $this->order_status,
            'amount' => $this->amount !== null ? (float) $this->amount : null,
            'currency' => $this->currency,
            'currency_code' => $this->currency_code,
            'is_paid' => (bool) $this->is_paid,
            'order_completed' => (bool) $this->order_completed,
            'items_count' => $this->items_count !== null ? (int) $this->items_count : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        // Customer contact details, payment data, and IP are admin-only — these
        // are PII and shouldn't leak via the public API even for active orders.
        if ($isAdmin) {
            $data += [
                'email' => $this->email,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'phone' => $this->phone,
                'country' => $this->country,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'address' => $this->address,
                'address2' => $this->address2,
                'other_info' => $this->other_info,
                'customer_id' => $this->customer_id !== null ? (int) $this->customer_id : null,
                'payment_provider_id' => $this->payment_provider_id !== null ? (int) $this->payment_provider_id : null,
                'payment_provider' => $this->payment_provider,
                'shipping_provider_id' => $this->shipping_provider_id !== null ? (int) $this->shipping_provider_id : null,
                'shipping_provider' => $this->shipping_provider,
                'transaction_id' => $this->transaction_id,
                'payment_status' => $this->payment_status,
                'shipping_amount' => $this->shipping_amount !== null ? (float) $this->shipping_amount : null,
                'discount_value' => $this->discount_value !== null ? (float) $this->discount_value : null,
                'taxes_amount' => $this->taxes_amount !== null ? (float) $this->taxes_amount : null,
                'session_id' => $this->session_id,
                'user_ip' => $this->user_ip,
                'promo_code' => $this->promo_code,
                'coupon_id' => $this->coupon_id !== null ? (int) $this->coupon_id : null,
            ];
        }

        return $data;
    }
}
