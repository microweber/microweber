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
    public $sort = '';
    public $direction = '';
    public $priceFrom;
    public $priceTo;
    public $limit = 10;

    public $queryString = [
        'keywords',
        'tags',
        'limit',
        'sort',
        'direction',
        'priceFrom',
        'priceTo',
    ];

    public function filterLimit($limit)
    {
        $this->limit = $limit;
    }

    public function filterSort($field,$direction)
    {
        $this->sort = $field;
        $this->direction = $direction;
    }

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
        if (!empty($this->sort) && !empty($this->direction)) {
            $filters['sort'] = $this->sort;
            $filters['direction'] = $this->direction;
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

        $products = $productsQuery->paginate($this->limit);

       return view('microweber-module-shop::livewire.shop.index', [
            'products' => $products,
            'availableTags' => $availableTags,
            'filteredTags' => $this->getTags(),
       ]);
    }
}
