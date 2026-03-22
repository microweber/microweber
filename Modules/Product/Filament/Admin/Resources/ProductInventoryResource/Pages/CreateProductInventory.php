<?php

namespace Modules\Product\Filament\Admin\Resources\ProductInventoryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Product\Filament\Admin\Resources\ProductInventoryResource;
use Modules\Product\Services\InventoryService;

class CreateProductInventory extends CreateRecord
{
    protected static string $resource = ProductInventoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Use InventoryService to handle the stock adjustment
        $inventoryService = app(InventoryService::class);

        $productId = $data['product_id'];
        $variantId = $data['variant_id'] ?? null;
        $quantityChange = $data['quantity_change'];
        $type = $data['type'];
        $notes = $data['notes'] ?? null;

        // Get current quantity before
        $quantityBefore = $inventoryService->getStock($productId, $variantId);
        $quantityAfter = $quantityBefore + $quantityChange;

        // Add computed fields
        $data['quantity_before'] = $quantityBefore;
        $data['quantity_after'] = $quantityAfter;
        $data['user_id'] = auth()->id();

        // If this is a restock or adjustment, actually update the stock
        if (in_array($type, [
            \Modules\Product\Models\ProductInventoryMovement::TYPE_RESTOCK,
            \Modules\Product\Models\ProductInventoryMovement::TYPE_INITIAL,
        ])) {
            $inventoryService->adjustStock($productId, $quantityAfter, $variantId, $notes, auth()->id());
        }

        return $data;
    }
}
