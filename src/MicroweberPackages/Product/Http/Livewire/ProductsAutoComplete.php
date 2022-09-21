<?php

namespace MicroweberPackages\Product\Http\Livewire;

use MicroweberPackages\Admin\Http\Livewire\AutoCompleteComponent;
use MicroweberPackages\Product\Models\Product;

class ProductsAutoComplete extends AutoCompleteComponent
{
    public $model = Product::class;
    public $selectedItemKey = 'productId';
    public string $placeholder = 'Type to search by products...';

    public function refreshQueryData()
    {
        $this->closeDropdown();

        $query = $this->model::query();

        if ($this->selectedItem > 0) {
            $query->where('id', $this->selectedItem);
            $query->limit(1);
            $get = $query->first();
            if ($get != null) {
                $this->data = [];
                $this->showDropdown = true;
                $this->query = $get->title;
            }
            return;
        }

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
                $this->data[] = ['key'=>$item->id, 'value'=>$item->title, 'thumbnail'=>$item->thumbnail(30,30)];
            }
        }
    }
}
