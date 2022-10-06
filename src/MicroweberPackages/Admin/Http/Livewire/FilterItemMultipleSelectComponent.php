<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Tag\Model\Tag;
use MicroweberPackages\User\Models\User;

class FilterItemMultipleSelectComponent extends AutoCompleteMultipleSelectComponent
{
    public $name = 'Component';
    public $perPage = 10;
    public $total = 0;
    public $hideFilterItem = 0;

    public string $view = 'admin::livewire.filters.filter-item-mulitple-select';

    public function hideFilterItem($id)
    {
        if ($this->id == $id) {
            $this->hideFilterItem = 1;
            $this->closeDropdown($id);
            $this->emit('hideFilterItem', $this->selectedItemKey);
            $this->resetProperties();
        }
    }

}
