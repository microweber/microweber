<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Tag\Model\Tag;
use MicroweberPackages\User\Models\User;

class FilterItemProduct extends FilterItemComponent
{
    public $model = Product::class;
    public $selectedItemKey = 'productId';
    public string $placeholder = 'Type to search by products...';
    public string $view = 'admin::livewire.filters.filter-item-product';

    public function refreshQueryData()
    {
        if ($this->selectedItem > 0) {
            $query = $this->model::query();
            $query->where('id', $this->selectedItem);
            $query->limit(1);
            $get = $query->first();
            if ($get != null) {
                $this->selectedItemText = $get->title;
            }
        }


        // New query
        $query = $this->model::query();
        $keyword = trim($this->query);
        if (!empty($keyword)) {
            $query->orWhere('title', 'like', '%' . $keyword . '%');
        }

        $query->limit(30);

        $get = $query->get();

        if ($get != null) {
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->id, 'value'=>$item->title, 'thumbnail'=>$item->thumbnail(30,30)];
            }
        }
    }
}
