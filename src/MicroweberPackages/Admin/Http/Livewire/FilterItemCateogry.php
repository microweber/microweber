<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemCateogry extends DropdownComponent
{
    public $name = 'Category';
    public string $view = 'admin::livewire.filters.filter-item-category';



    public function hideFilterItem($id)
    {
        if ($this->id == $id) {
            $this->emit('hideFilterItem', strtolower($this->name));
            // $this->resetProperties();
        }
    }

/*    protected function getListeners()
    {
        return array_merge($this->listeners, [

        ]);
    }*/

}
