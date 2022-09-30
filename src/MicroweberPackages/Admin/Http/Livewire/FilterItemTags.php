<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Tag\Model\Tag;
use MicroweberPackages\User\Models\User;

class FilterItemTags extends FilterItemComponent
{
    public $name = 'Tags';
    public $model = Tag::class;
    public $selectedItemKey = 'tags';
    public string $placeholder = 'Type to search by tags...';

    /**
     * @var string[]
     */
    public $listeners = [
        'filterItemTagsRefresh'=>'$refresh',
        'filterItemTagsResetProperties'=>'resetProperties'
    ];

    public function loadMore()
    {
        $this->emit('loadMoreExecuted');
        $this->perPage = $this->perPage + 5;
        $this->refreshQueryData();
    }


    public $firstItemName;
    public $firstTimeLoading = false;

    public function mount()
    {
        $this->firstTimeLoading = true;
        $this->refreshFirstItemName();
    }

    public function updatedSelectedItems(array $items)
    {
        parent::updatedSelectedItems($items);

        $this->refreshFirstItemName();
    }

    public function refreshFirstItemName()
    {
        if (isset($this->selectedItems[0])) {
            $getItem = $this->model::where('slug', $this->selectedItems[0])->first();
            if ($getItem != null) {
                $this->firstItemName = $getItem->name;
            }
        }
    }

    public function refreshQueryData()
    {

        $this->showDropdown();

        $firstData = [];

        if ($this->firstTimeLoading) {
            if (!empty($this->selectedItems)) {
                $query = $this->model::query();
                $query->whereIn('slug', $this->selectedItems);
                $get = $query->get();
                if ($get != null) {
                    foreach ($get as $item) {
                        $firstData[$item->slug] = ['key' => $item->slug, 'value' => $item->name];
                    }
                }
            }
        }

        $this->firstTimeLoading = false;

        $query = $this->model::query();
        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('slug', 'like', '%' . $keyword . '%');
            $query->orWhere('name', 'like', '%' . $keyword . '%');
        }

        $this->total = $query->count();

        $query->limit($this->perPage);

        $get = $query->get();
        if ($get != null) {
            $lastData = [];
            foreach ($get as $item) {
                if (isset($firstData[$item->slug])) {
                    continue;
                }
                $lastData[$item->slug] = ['key'=>$item->slug, 'value'=>$item->name];
            }

            $this->data = array_merge($firstData, $lastData);
        }
    }
}
