<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire;

use Livewire\WithPagination;
use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;
use MicroweberPackages\Modules\Shop\Http\Livewire\Traits\ShopTagsTrait;
use MicroweberPackages\Product\Models\Product;

class ShopComponent extends ModuleSettingsComponent
{
    use WithPagination;
    use ShopTagsTrait;

    public $keywords;

    public $queryString = [
        'keywords'
    ];

    public function render()
    {
        $productsQuery = Product::query();
        $productsQuery->where('is_active', 1);

        $filters = [];
        if (!empty($this->keywords)) {
            $filters['keyword'] = $this->keywords;
        }
        if (!empty($this->tags)) {
            $filters['tags'] = $this->tags;
        }

        if (!empty($filters)) {
            $productsQuery->filter($filters);
        }

        $availableTags = [];
        $productsQueryAll = Product::query();
        $productsQueryAll->where('is_active', 1);
        $allProducts = $productsQueryAll->get();
        foreach ($allProducts as $product) {
            $getTags = $product->tags;
            if (!empty($getTags)) {
                foreach ($getTags as $tag) {
                    $availableTags[$tag->tag_name] = $tag->tag_slug;
                }
            }
        }

        $products = $productsQuery->paginate(10);

       return view('microweber-module-shop::livewire.shop.index', [
            'products' => $products,
            'availableTags' => $availableTags,
            'filteredTags' => $this->getTags(),
       ]);
    }
}
