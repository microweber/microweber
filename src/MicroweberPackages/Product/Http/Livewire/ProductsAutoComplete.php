<?php

namespace MicroweberPackages\Product\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use MicroweberPackages\Product\Models\Product;

class ProductsAutoComplete extends AutoCompleteComponent
{
    public $model = Product::class;
    public $selectedItemKey = 'productId';
    public string $placeholder = 'Type to search products...';

    public function refreshQueryData()
    {
        $this->closeDropdown();

        $query = $this->model::query();

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->orWhere('title', 'like', '%' . $keyword . '%');
        }

        $query->limit(30);

        $get = $query->get();

        if ($get != null) {
            $this->showDropdown();
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->id, 'value'=>$item->title];
            }
        }
    }
}
