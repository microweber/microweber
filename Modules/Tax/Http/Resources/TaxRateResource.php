<?php

declare(strict_types=1);

namespace Modules\Tax\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Tax\Models\TaxRate;

/**
 * @mixin TaxRate
 */
final class TaxRateResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'country_code' => $this->country_code,
            'state_code' => $this->state_code,
            'zip_code_pattern' => $this->zip_code_pattern,
            'city' => $this->city,
            'type' => $this->type,
            'rate' => (float) $this->rate,
            'compound_tax' => (bool) $this->compound_tax,
            'priority' => (int) $this->priority,
            'is_default' => (bool) $this->is_default,
            'is_active' => (bool) $this->is_active,
            'valid_from' => $this->valid_from?->toIso8601String(),
            'valid_until' => $this->valid_until?->toIso8601String(),
            'applies_to_products' => $this->applies_to_products,
            'applies_to_categories' => $this->applies_to_categories,
            'applies_to_customer_groups' => $this->applies_to_customer_groups,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
