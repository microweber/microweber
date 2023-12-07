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
        $findShopPages = Page::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->where('is_shop', 1)
            ->get();

        $shopPagesDropdownOptions = [];
        if ($findShopPages->count() > 0) {
            foreach ($findShopPages as $shopPage) {
                $shopPagesDropdownOptions[$shopPage->id] = $shopPage->title;
            }
        }

        $mainPageId = $this->getMainPageId();

        $productsQueryAll = Product::query();
        $productsQueryAll->where('parent', $mainPageId);
        $productsQueryAll->where('is_active', 1);
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
           'shopPagesDropdownOptions'=>$shopPagesDropdownOptions,
           'customFields'=>$customFields
       ]);
    }

    public function getMainPageId()
    {
        $contentFromId = get_option('content_from_id', $this->moduleId);
        if ($contentFromId) {
            return $contentFromId;
        }

        $findFirstShop = Page::where('content_type', 'page')
            ->where('subtype','dynamic')
            ->where('is_shop', 1)
            ->first();

        if ($findFirstShop) {
            return $findFirstShop->id;
        }

        return 0;
    }
}
