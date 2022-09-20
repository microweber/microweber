<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use MicroweberPackages\Tag\Model\Tag;
use MicroweberPackages\User\Models\User;

class TagsAutoComplete extends AutoCompleteComponent
{
    public $model = Tag::class;
    public $selectMultipleItems = true;
    public $selectedItemKey = 'tags';
    public string $placeholder = 'Type to search tags...';

    public function refreshQueryData()
    {
        $this->closeDropdown();

        $query = $this->model::query();

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('slug', 'like', '%' . $keyword . '%');
            $query->orWhere('name', 'like', '%' . $keyword . '%');
        }

        $query->limit(30);

        $get = $query->get();

        if ($get != null) {
            $this->showDropdown();
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->slug, 'value'=>$item->name];
            }
        }
    }
}
