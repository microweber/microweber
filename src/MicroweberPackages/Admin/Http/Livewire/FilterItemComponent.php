<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Tag\Model\Tag;
use MicroweberPackages\User\Models\User;

class FilterItemComponent extends AutoCompleteComponent
{
    public $name = 'Component';
    public $perPage = 10;
    public $total = 0;

    public string $view = 'admin::livewire.filters.filter-item';


    public function refreshQueryData()
    {
        $this->showDropdown();

    }
}
