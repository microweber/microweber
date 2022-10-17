<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\User\Models\User;

class FilterItemUser extends FilterItemComponent
{
    public $name = 'User';
    public $model = User::class;
    public $selectedItemKey = 'userId';
    public string $placeholder = 'Type to search by user...';
    public $perPage = 10;

    protected function getListeners()
    {
        return array_merge($this->listeners, [
            'filterItemUsersRefresh'=>'$refresh',
            'filterItemUsersResetProperties'=>'resetProperties'
        ]);
    }

    public function loadMore()
    {
        $this->emit('loadMoreExecuted');
        $this->perPage = $this->perPage + 5;
        $this->refreshQueryData();
    }

    public function refreshQueryData()
    {
        $query = $this->model::query();

        $keyword = trim($this->query);
        if (!empty($keyword)) {
            $query->where('first_name', 'like', '%' . $keyword . '%');
            $query->orWhere('last_name', 'like', '%' . $keyword . '%');
            $query->orWhere('email', 'like', '%' . $keyword . '%');
        }

        $this->total = $query->count();
        $query->limit($this->perPage);

        $get = $query->get();

        if ($get != null) {
            $data = [];
            foreach ($get as $item) {
                $data[$item->id] = ['key'=>$item->id, 'value'=>$item->displayName()];
            }
            $this->data = $data;
        }
    }
}
