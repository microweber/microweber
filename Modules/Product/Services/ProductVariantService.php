<?php

namespace Modules\Product\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Content\Models\Content;
use Modules\Product\Models\ProductVariantAttribute;
use Modules\Product\Models\ProductVariantAttributeValue;
use Modules\Product\Models\ProductVariantCombination;

/**
 * Product Variant Service
 *
 * Handles business logic for product variants including:
 * - Creating variant attributes and values
 * - Generating variant combinations
 * - Managing variant inventory
 * - Finding variants by attributes
 */
class ProductVariantService
{
    /**
     * Create a new variant attribute (e.g., Size, Color)
     */
    public function createAttribute(array $data): ProductVariantAttribute
    {
        return DB::transaction(function () use ($data) {
            $attribute = ProductVariantAttribute::create($data);

            // Create values if provided
            if (!empty($data['values'])) {
                foreach ($data['values'] as $valueData) {
                    $this->createAttributeValue($attribute->id, $valueData);
                }
            }

            return $attribute->load('values');
        });
    }

    /**
     * Update a variant attribute
     */
    public function updateAttribute(int $attributeId, array $data): ProductVariantAttribute
    {
        return DB::transaction(function () use ($attributeId, $data) {
            $attribute = ProductVariantAttribute::findOrFail($attributeId);
            $attribute->update($data);

            // Handle values updates if provided
            if (!empty($data['values'])) {
                $this->syncAttributeValues($attribute, $data['values']);
            }

            return $attribute->load('values');
        });
    }

    /**
     * Create a value for an attribute
     */
    public function createAttributeValue(int $attributeId, array $data): ProductVariantAttributeValue
    {
        $data['attribute_id'] = $attributeId;
        return ProductVariantAttributeValue::create($data);
    }

    /**
     * Sync attribute values (create, update, delete)
     */
    public function syncAttributeValues(ProductVariantAttribute $attribute, array $values): void
    {
        $existingIds = $attribute->values()->pluck('id')->toArray();
        $processedIds = [];

        foreach ($values as $valueData) {
            if (!empty($valueData['id']) && in_array($valueData['id'], $existingIds)) {
                // Update existing
                $value = ProductVariantAttributeValue::find($valueData['id']);
                $value->update($valueData);
                $processedIds[] = $value->id;
            } else {
                // Create new
                $value = $this->createAttributeValue($attribute->id, $valueData);
                $processedIds[] = $value->id;
            }
        }

        // Delete values not in the list
        $toDelete = array_diff($existingIds, $processedIds);
        if (!empty($toDelete)) {
            ProductVariantAttributeValue::whereIn('id', $toDelete)->delete();
        }
    }

    /**
     * Generate all possible variant combinations for a product
     */
    public function generateVariantCombinations(Content $product, array $attributeIds): Collection
    {
        // Get all attribute values for the given attributes
        $attributeValues = ProductVariantAttributeValue::whereIn('attribute_id', $attributeIds)
            ->where('is_active', true)
            ->with('attribute')
            ->get()
            ->groupBy('attribute_id');

        // Generate cartesian product of all value combinations
        $combinations = $this->cartesianProduct($attributeValues->toArray());

        $createdCombinations = collect();

        DB::transaction(function () use ($product, $combinations, &$createdCombinations) {
            foreach ($combinations as $combinationValues) {
                $valueIds = collect($combinationValues)->pluck('id')->toArray();

                // Check if combination already exists
                $existing = $this->findCombinationByValues($product->id, $valueIds);

                if (!$existing) {
                    $combination = ProductVariantCombination::create([
                        'product_id' => $product->id,
                        'quantity' => 0,
                        'quantity_type' => 'limited',
                        'is_active' => true,
                    ]);

                    // Attach attribute values
                    $combination->attributeValues()->attach($valueIds);
                    $createdCombinations->push($combination);
                } else {
                    $createdCombinations->push($existing);
                }
            }
        });

        return $createdCombinations;
    }

    /**
     * Find or create a variant combination
     */
    public function findOrCreateCombination(int $productId, array $attributeValueIds): ProductVariantCombination
    {
        $existing = $this->findCombinationByValues($productId, $attributeValueIds);

        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($productId, $attributeValueIds) {
            $combination = ProductVariantCombination::create([
                'product_id' => $productId,
                'quantity' => 0,
                'quantity_type' => 'limited',
                'is_active' => true,
            ]);

            $combination->attributeValues()->attach($attributeValueIds);

            return $combination->load('attributeValues.attribute');
        });
    }

    /**
     * Find combination by attribute value IDs
     */
    public function findCombinationByValues(int $productId, array $valueIds): ?ProductVariantCombination
    {
        // Sort value IDs for consistent comparison
        sort($valueIds);

        $combinations = ProductVariantCombination::where('product_id', $productId)
            ->with('attributeValues')
            ->get();

        foreach ($combinations as $combination) {
            $combinationValueIds = $combination->attributeValues->pluck('id')->sort()->values()->toArray();
            if ($combinationValueIds === $valueIds) {
                return $combination;
            }
        }

        return null;
    }

    /**
     * Find combination by attribute keys and value keys
     */
    public function findCombinationByAttributeKeys(int $productId, array $attributes): ?ProductVariantCombination
    {
        $valueIds = [];

        foreach ($attributes as $attributeKey => $valueKey) {
            $attribute = ProductVariantAttribute::where('key', $attributeKey)->first();
            if (!$attribute) {
                return null;
            }

            $value = ProductVariantAttributeValue::where('attribute_id', $attribute->id)
                ->where('key', $valueKey)
                ->first();

            if (!$value) {
                return null;
            }

            $valueIds[] = $value->id;
        }

        return $this->findCombinationByValues($productId, $valueIds);
    }

    /**
     * Update variant combination
     */
    public function updateCombination(int $combinationId, array $data): ProductVariantCombination
    {
        $combination = ProductVariantCombination::findOrFail($combinationId);
        $combination->update($data);

        return $combination->fresh('attributeValues.attribute');
    }

    /**
     * Set default variant for a product
     */
    public function setDefaultVariant(int $productId, int $combinationId): bool
    {
        return DB::transaction(function () use ($productId, $combinationId) {
            // Clear existing default
            ProductVariantCombination::where('product_id', $productId)
                ->where('is_default', true)
                ->update(['is_default' => false]);

            // Set new default
            $combination = ProductVariantCombination::findOrFail($combinationId);
            $combination->is_default = true;

            return $combination->save();
        });
    }

    /**
     * Get all variant combinations for a product
     */
    public function getProductVariants(int $productId): Collection
    {
        return ProductVariantCombination::where('product_id', $productId)
            ->with('attributeValues.attribute')
            ->orderBy('is_default', 'desc')
            ->orderBy('id')
            ->get();
    }

    /**
     * Delete variant combination
     */
    public function deleteCombination(int $combinationId): bool
    {
        $combination = ProductVariantCombination::findOrFail($combinationId);

        // Detach attribute values
        $combination->attributeValues()->detach();

        return $combination->delete();
    }

    /**
     * Update variant inventory
     */
    public function updateInventory(int $combinationId, int $quantity, ?bool $trackQuantity = null): bool
    {
        $combination = ProductVariantCombination::findOrFail($combinationId);

        if ($trackQuantity !== null) {
            $combination->track_quantity = $trackQuantity;
        }

        $combination->quantity = max(0, $quantity);

        return $combination->save();
    }

    /**
     * Get available variant options for frontend
     */
    public function getVariantOptions(int $productId): array
    {
        $combinations = $this->getProductVariants($productId);

        $options = [
            'attributes' => [],
            'variants' => [],
        ];

        $attributes = [];

        foreach ($combinations as $combination) {
            $variantData = [
                'id' => $combination->id,
                'sku' => $combination->sku,
                'price' => $combination->getFinalPrice(),
                'quantity' => $combination->getAvailableQuantity(),
                'in_stock' => $combination->isInStock(),
                'is_default' => $combination->is_default,
                'image' => $combination->image,
                'attributes' => [],
            ];

            foreach ($combination->attributeValues as $value) {
                $attribute = $value->attribute;

                if (!isset($attributes[$attribute->id])) {
                    $attributes[$attribute->id] = [
                        'id' => $attribute->id,
                        'name' => $attribute->name,
                        'key' => $attribute->key,
                        'type' => $attribute->type,
                        'values' => [],
                    ];
                }

                if (!isset($attributes[$attribute->id]['values'][$value->id])) {
                    $attributes[$attribute->id]['values'][$value->id] = [
                        'id' => $value->id,
                        'value' => $value->value,
                        'key' => $value->key,
                        'color_code' => $value->color_code,
                        'image' => $value->image,
                    ];
                }

                $variantData['attributes'][$attribute->key] = $value->key;
            }

            $options['variants'][] = $variantData;
        }

        // Re-index values array
        foreach ($attributes as &$attribute) {
            $attribute['values'] = array_values($attribute['values']);
        }

        $options['attributes'] = array_values($attributes);

        return $options;
    }

    /**
     * Calculate cartesian product of arrays
     */
    private function cartesianProduct(array $input): array
    {
        $result = [[]];

        foreach ($input as $key => $values) {
            $append = [];

            foreach ($result as $product) {
                foreach ($values as $value) {
                    $append[] = $product + [$key => $value];
                }
            }

            $result = $append;
        }

        return $result;
    }

    /**
     * Get all active attributes
     */
    public function getActiveAttributes(): Collection
    {
        return ProductVariantAttribute::active()
            ->ordered()
            ->with('activeValues')
            ->get();
    }

    /**
     * Import variants from array (useful for CSV import)
     */
    public function importVariants(int $productId, array $variantsData): Collection
    {
        $imported = collect();

        DB::transaction(function () use ($productId, $variantsData, &$imported) {
            foreach ($variantsData as $variantData) {
                $attributes = $variantData['attributes'] ?? [];
                $valueIds = [];

                // Find or create attribute values
                foreach ($attributes as $attrKey => $valKey) {
                    $attribute = ProductVariantAttribute::where('key', $attrKey)->first();
                    if (!$attribute) {
                        continue;
                    }

                    $value = ProductVariantAttributeValue::firstOrCreate(
                        ['attribute_id' => $attribute->id, 'key' => $valKey],
                        ['value' => $valKey, 'is_active' => true]
                    );

                    $valueIds[] = $value->id;
                }

                if (empty($valueIds)) {
                    continue;
                }

                // Find or create combination
                $combination = $this->findOrCreateCombination($productId, $valueIds);

                // Update combination data
                $updateData = [
                    'sku' => $variantData['sku'] ?? null,
                    'barcode' => $variantData['barcode'] ?? null,
                    'price' => $variantData['price'] ?? null,
                    'quantity' => $variantData['quantity'] ?? 0,
                    'track_quantity' => $variantData['track_quantity'] ?? true,
                    'is_active' => $variantData['is_active'] ?? true,
                ];

                $combination->update($updateData);
                $imported->push($combination);
            }
        });

        return $imported;
    }
}
