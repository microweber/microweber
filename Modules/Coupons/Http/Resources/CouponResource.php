<?php

declare(strict_types=1);

namespace Modules\Coupons\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Coupons\Models\Coupon;

/**
 * @mixin Coupon
 */
final class CouponResource extends JsonResource
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
            'coupon_name' => $this->coupon_name,
            'coupon_code' => $this->coupon_code,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value !== null ? (float) $this->discount_value : null,
            'free_shipping' => (bool) ($this->free_shipping ?? false),
            'is_active' => (bool) $this->is_active,
            'valid_from' => $this->valid_from?->toIso8601String(),
            'valid_to' => $this->valid_to?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        // Usage caps, conversion counters, and advanced rule config are
        // admin-only — exposing them publicly would let scrapers harvest
        // per-customer caps and reverse-engineer discount strategy.
        if ($isAdmin) {
            $data += [
                'max_discount_amount' => $this->max_discount_amount !== null ? (float) $this->max_discount_amount : null,
                'total_amount' => $this->total_amount !== null ? (float) $this->total_amount : null,
                'uses_per_coupon' => (int) $this->uses_per_coupon,
                'uses_per_customer' => (int) $this->uses_per_customer,
                'times_used' => (int) ($this->times_used ?? 0),
                'total_discount_given' => (float) ($this->total_discount_given ?? 0),
                'is_stackable' => (bool) ($this->is_stackable ?? false),
                'first_time_only' => (bool) ($this->first_time_only ?? false),
                'auto_apply' => (bool) ($this->auto_apply ?? false),
                'product_ids' => $this->product_ids,
                'excluded_product_ids' => $this->excluded_product_ids,
                'category_ids' => $this->category_ids,
                'customer_group_ids' => $this->customer_group_ids,
                'bogo_enabled' => (bool) ($this->bogo_enabled ?? false),
                'tiered_enabled' => (bool) ($this->tiered_enabled ?? false),
            ];
        }

        return $data;
    }
}
