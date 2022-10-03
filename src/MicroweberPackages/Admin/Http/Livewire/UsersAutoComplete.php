<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use MicroweberPackages\User\Models\User;

class UsersAutoComplete extends AutoCompleteComponent
{
    public $model = User::class;
    public $selectedItemKey = 'userId';
    public string $placeholder = 'Type to search by users...';

    /**
     * @var string[]
     */
    public $listeners = [
        'usersAutocompleteRefresh'=>'$refresh',
        'usersResetProperties'=>'resetProperties'
    ];

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
                $this->query = $get->displayName() . ' (#'.$get->id.')';
            }
            return;
        }

        $keyword = trim($this->query);

        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $query->limit(200);

        $get = $query->get();

        if ($get != null) {
            $this->showDropdown($this->id);
            $this->data = [];
            foreach ($get as $item) {
                $this->data[] = ['key'=>$item->id, 'value'=>$item->displayName() . ' (#'.$item->id.')'];
            }
        }
    }
}
