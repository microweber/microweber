<?php

namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;

class ShopSettingsComponent extends ModuleSettingsComponent
{

    public string $moduleId = '';
    public string $moduleType = 'shop';

    public function render()
    {

        $allShops = app()->content_repository->getAllShopPages();

        $shopPagesDropdownOptions = [
            0 => 'Default'
        ];
        if ($allShops) {
            foreach ($allShops as $shopPage) {
                $shopPagesDropdownOptions[$shopPage['id']] = $shopPage['title'];
            }
        }

        $mainPageId = $this->getMainPageId();

        $productsQueryAll = Product::query();
        if ($mainPageId > 0) {
            $productsQueryAll->where('parent', $mainPageId);
        }
        $productsQueryAll->where('is_active', 1);
        $productsQueryAll->where('is_deleted', 0);

        $allProducts = $productsQueryAll->get();

        $customFields = [];
        if ($allProducts->count() > 0) {
            foreach ($allProducts as $product) {
                if (!empty($product->customField)) {
                    foreach ($product->customField as $productCustomField) {
                        if ($productCustomField->name_key == 'price') {
                            continue;
                        }
                        $customFields[$productCustomField->name_key] = $productCustomField->name;
                    }
                }
            }
        }

        return view('microweber-module-shop::admin.livewire.settings', [
            'shopPagesDropdownOptions' => $shopPagesDropdownOptions,
            'customFields' => $customFields,
            'moduleType' => $this->moduleType,
        ]);

    }

    public function getMainPageId()
    {
        $contentFromId = get_option('content_from_id', $this->moduleId);
        if ($contentFromId) {
            return $contentFromId;
        }

        return 0;
    }
}
