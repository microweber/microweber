<?php
namespace MicroweberPackages\Modules\Shop\Http\Livewire\Traits;

trait ShopCategoriesTrait {

    public $category;

    public function filterCategory($category)
    {
        $this->category = $category;
        $this->setPage(1);
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function filterClearCategory()
    {
        $this->category = null;

        $this->setPage(1);
    }

}

