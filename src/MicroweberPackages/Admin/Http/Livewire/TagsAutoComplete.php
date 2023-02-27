<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use MicroweberPackages\Tag\Model\Tag;

class TagsAutoComplete extends AutoCompleteMultipleSelectComponent
{
    public $model = Tag::class;
    public $selectedItemKey = 'tags';
    public string $placeholder = 'Type to search by tags...';

    /**
     * @var string[]
     */
    public $listeners = [
        'tagsAutocompleteRefresh'=>'$refresh',
        'tagsResetProperties'=>'resetProperties'
    ];

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
            $this->showDropdown($this->id);
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->slug, 'value'=>$item->name];
            }
        }
    }
}
