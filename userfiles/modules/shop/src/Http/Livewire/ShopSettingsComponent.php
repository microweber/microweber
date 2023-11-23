<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\Page\Models\Page;

class ShopSettingsComponent extends ModuleSettingsComponent
{

    public string $moduleId;
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

       return view('microweber-module-shop::admin.livewire.settings', [
           'shopPagesDropdownOptions'=>$shopPagesDropdownOptions
       ]);
    }
}
