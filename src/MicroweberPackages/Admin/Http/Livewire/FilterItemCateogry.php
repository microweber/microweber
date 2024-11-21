<?php

namespace MicroweberPackages\Admin\Http\Livewire;

use Modules\Category\Models\Category;
use Modules\Page\Models\Page;

class FilterItemCateogry extends DropdownComponent
{
    public $name = 'Category';
    public string $view = 'admin::livewire.filters.filter-item-category';

    public $itemCategoryValue;
    public $itemPageValue;

    public $firstItemCategoryName;
    public $firstItemPageName;

    public $itemCategoryValueKey = 'category';
    public $itemPageValueKey = 'page';

    public $selectedItemsCount = 0;

    public function mount()
    {
        $this->calculateSelectedItemsCount();

        if ($this->itemPageValue) {
            $this->updatedItemPageValue($this->itemPageValue);
        }

        if ($this->itemCategoryValue) {
            $this->updatedItemCategoryValue($this->itemCategoryValue);
        }
    }

    public function calculateSelectedItemsCount()
    {
        $this->selectedItemsCount = 0;
        $explode = explode(',', $this->itemCategoryValue);
        $explode = array_filter($explode);
        if (!empty($explode)) {
            $this->selectedItemsCount = count($explode);
        }

        $explode = explode(',', $this->itemPageValue);
        $explode = array_filter($explode);
        if (!empty($explode)) {
            $this->selectedItemsCount = $this->selectedItemsCount + count($explode);
        }

    }

    public function updatedItemCategoryValue($categoryIds)
    {
        $explode = explode(',', $categoryIds);
        $explode = array_filter($explode);
        if (!empty($explode)) {
            foreach ($explode as $categoryId) {
                $findCategory = Category::where('id', $categoryId)->first();
                if ($findCategory !== null) {
                    $this->firstItemCategoryName = $findCategory->title;
                    break;
                }
            }
        }

        $this->showDropdown($this->getId());
        $this->calculateSelectedItemsCount();
        // $this->dispatchEvents();

    }

    public function updatedItemPageValue($pageIds)
    {
        $explode = explode(',', $pageIds);
        $explode = array_filter($explode);
        if (!empty($explode)) {
            foreach ($explode as $pageId) {
                $findPage = Page::where('id', $pageId)->first();
                if ($findPage !== null) {
                    $this->firstItemPageName = $findPage->title;
                    break;
                }
            }
        }

        $this->showDropdown($this->getId());
        $this->calculateSelectedItemsCount();
        // $this->dispatchEvents();
    }

    public function hideFilterItem($id)
    {
        if ($this->getId() == $id) {
            $this->dispatch('hideFilterItem', strtolower($this->getName()));
            // $this->resetProperties();
        }
    }


    public function emitEvents()
    {
        $this->dispatch('autoCompleteSelectItem', $this->itemCategoryValueKey, $this->itemCategoryValue);
        $this->dispatch('autoCompleteSelectItem', $this->itemPageValueKey, $this->itemPageValue);
    }

    public function selectItemCategory($itemId)
    {
        $this->itemCategoryValue = $itemId;
        $this->updatedItemCategoryValue($itemId);
        $this->closeDropdown($this->getId());
    }

    public function selectItemPage($itemId)
    {
        $this->itemPageValue = $itemId;
        $this->updatedItemPageValue($itemId);
        $this->closeDropdown($this->getId());
    }

    protected function getListeners()
    {
        return array_merge($this->listeners, [
            'selectItemPage'=>'selectItemPage',
            'selectItemCategory'=>'selectItemCategory'
        ]);
    }

}
