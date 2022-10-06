<?php

namespace MicroweberPackages\Admin\Http\Livewire;

class FilterItemCateogry extends DropdownComponent
{
    public $name = 'Category';
    public string $view = 'admin::livewire.filters.filter-item-category';

    public $itemCategoryValue;
    public $itemPageValue;

    public $itemCategoryValueKey = 'category';
    public $itemPageValueKey = 'page';


    public function updatedItemCateogryValue()
    {
        $this->showDropdown($this->id);
     //   $this->emitEvents();
    }

    public function updatedItemPageValue()
    {
        $this->showDropdown($this->id);
     //   $this->emitEvents();
    }

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
