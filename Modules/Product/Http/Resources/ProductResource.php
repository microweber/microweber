<?php

declare(strict_types=1);

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Models\Product;

/**
 * @mixin Product
 */
final class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get custom fields data
        $customFields = [];
        $contentData = [];

        if (function_exists('content_data')) {
            $contentData = content_data($this->id);
        }

        // Get price from custom fields
        $price = null;
        $specialPrice = null;
        if ($this->customField) {
            foreach ($this->customField as $field) {
                if ($field->type === 'price' && $field->value) {
                    if (!$price) {
                        $price = $field->value;
                    }
                }
            }
        }

        // Get currency
        $currency = get_currency_code() ?? 'USD';

        // Build the resource array
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->url,
            'description' => $this->description,
            'content_body' => $this->content_body,
            'short_description' => $this->content_meta_description,

            // Pricing
            'price' => $price ?? ($contentData['price'] ?? 0),
            'special_price' => $contentData['special_price'] ?? null,
            'currency' => $currency,

            // Inventory
            'sku' => $contentData['sku'] ?? '',
            'quantity' => $contentData['qty'] ?? 'nolimit',
            'track_quantity' => $contentData['track_quantity'] ?? false,
            'sell_out_of_stock' => $contentData['sell_oos'] ?? false,

            // Shipping
            'physical_product' => $contentData['physical_product'] ?? true,
            'free_shipping' => $contentData['free_shipping'] ?? false,
            'shipping_fixed_cost' => $contentData['shipping_fixed_cost'] ?? null,
            'weight' => $contentData['weight'] ?? null,
            'weight_type' => $contentData['weight_type'] ?? 'kg',
            'width' => $contentData['width'] ?? null,
            'height' => $contentData['height'] ?? null,
            'depth' => $contentData['depth'] ?? null,

            // Media
            'image' => get_picture($this->id),
            'media' => $this->whenLoaded('media', function () {
                return $this->media->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'url' => $item->filename,
                        'type' => $item->media_type,
                    ];
                });
            }),

            // Categorization
            'categories' => $this->whenLoaded('categories', function () {
                return $this->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'title' => $category->title,
                        'slug' => $category->url,
                    ];
                });
            }),

            // Custom fields/variants
            'custom_fields' => $this->whenLoaded('customField', function () {
                return $this->customField->map(function ($field) {
                    return [
                        'id' => $field->id,
                        'name' => $field->name,
                        'type' => $field->type,
                        'value' => $field->value,
                        'required' => $field->required ?? false,
                    ];
                });
            }),

            // SEO
            'meta_title' => $this->content_meta_title,
            'meta_description' => $this->content_meta_keywords,

            // Status
            'is_active' => (bool) $this->is_active,
            'is_featured' => (bool) ($this->is_featured ?? false),

            // Links
            'url' => content_link($this->id),
            'edit_link' => $this->when(
                is_admin(),
                admin_url() . 'content/edit/' . $this->id
            ),

            // Timestamps
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
