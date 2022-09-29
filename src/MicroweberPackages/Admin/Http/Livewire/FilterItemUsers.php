<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\User\Models\User;

class FilterItemUsers extends AutoCompleteMultipleItemsComponent
{
    public $name = 'Users';
    public $model = User::class;
    public $selectedItemKey = 'userIds';
    public string $placeholder = 'Type to search by users...';

    public $perPage = 10;

    /**
     * @var string[]
     */
    public $listeners = [
        'filterItemUsersRefresh'=>'$refresh',
        'filterItemUsersResetProperties'=>'resetProperties'
    ];

    public string $view = 'admin::livewire.filters.filter-item';

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
            $getItem = $this->model::where('id', $this->selectedItems[0])->first();
            if ($getItem != null) {
                $this->firstItemName = $getItem->displayName();
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
                $query->whereIn('id', $this->selectedItems);
                $get = $query->get();
                if ($get != null) {
                    foreach ($get as $item) {
                        $firstData[$item->id] = ['key' => $item->id, 'value' => $item->displayName()];
                    }
                }
            }
        }

        $this->firstTimeLoading = false;

        $query = $this->model::query();
        $keyword = trim($this->query);
        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $query->limit($this->perPage);

        $get = $query->get();
        if ($get != null) {
            $lastData = [];
            foreach ($get as $item) {
                if (isset($firstData[$item->id])) {
                    continue;
                }
                $lastData[$item->id] = ['key'=>$item->id, 'value'=>$item->displayName()];
            }

            $this->data = array_merge($firstData, $lastData);
        }
    }
}
