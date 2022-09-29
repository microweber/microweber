<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use MicroweberPackages\User\Models\User;

class FilterItemUsers extends AutoCompleteMultipleItemsComponent
{
    public $model = User::class;
    public $selectedItemKey = 'userIds';
    public string $placeholder = 'Type to search by users...';

    /**
     * @var string[]
     */
    public $listeners = [
        'usersAutocompleteRefresh'=>'$refresh',
        'usersResetProperties'=>'resetProperties'
    ];

    public string $view = 'admin::livewire.filters.filter-item';

    public function refreshQueryData()
    {
        $this->showDropdown();

        $query = $this->model::query();
        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $query->limit(10);

        $get = $query->get();

        if ($get != null) {
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->id, 'value'=>$item->displayName()];
            }
        }
    }
}
