<?php

declare(strict_types=1);

namespace Modules\Shipping\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shipping\Models\ShippingProvider;

/**
 * @mixin ShippingProvider
 */
final class ShippingProviderResource extends JsonResource
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
            'name' => $this->name,
            'provider' => $this->provider,
            'is_active' => (bool) $this->is_active,
            'is_default' => (bool) $this->is_default,
            'position' => (int) $this->position,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];

        // Provider settings often hold API keys / webhook secrets — admin-only.
        if ($isAdmin) {
            $data['settings'] = $this->settings;
        }

        return $data;
    }
}
