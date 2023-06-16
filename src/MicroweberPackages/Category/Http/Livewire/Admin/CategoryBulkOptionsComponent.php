<?php

namespace MicroweberPackages\Category\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Content\Models\Content;

class CategoryBulkOptionsComponent extends AdminComponent
{
    protected $listeners = [

        'multipleMoveToCategory' => 'multipleMoveToCategoryShowModal',

        'multipleVisible' => 'multipleVisibleShowModal',
        'multipleVisibleExecute' => 'multipleVisibleExecute',

        'multipleHidden' => 'multipleHiddenShowModal',
        'multipleHiddenExecute' => 'multipleHiddenExecute',

        'multipleDelete' => 'multipleDeleteShowModal',
        'multipleDeleteExecute' => 'multipleDeleteExecute',

    ];


    // Move to category modal

    public $multipleMoveToCategoryShowModal = false;
    public $multipleMoveToCategoryIds = [];
    public function multipleMoveToCategoryShowModal($selectedIds)
    {
        $this->multipleMoveToCategoryIds = $selectedIds;
        $this->multipleMoveToCategoryShowModal = true;
        $this->emit('multipleMoveToCategoryShowModalOpen',$selectedIds);

    }

    public function multipleMoveToCategoryExecute()
    {
        $this->emit('refreshContentListAndDeselectAll');
        $this->multipleMoveToCategoryShowModal = false;
    }


    // Visible modal
    public $multipleVisibleShowModal = false;
    public $multipleVisibleIds = [];

    public function multipleVisibleShowModal($params)
    {
        $this->multipleVisibleIds = $params;
        $this->multipleVisibleShowModal = true;
    }

    public function multipleVisibleExecute()
    {
        if (!empty($this->multipleVisibleIds)) {
            foreach ($this->multipleVisibleIds as $visibleId) {


            }
        }

        $this->emit('refreshCategoryManageAndDeselectAll');
        $this->multipleVisibleShowModal = false;
    }

    // Hidden modal
    public $multipleHiddenShowModal = false;
    public $multipleHiddenIds = [];

    public function multipleHiddenShowModal($params)
    {
        $this->multipleHiddenIds = $params;
        $this->multipleHiddenShowModal = true;
    }

    public function multipleHiddenExecute()
    {
        if (!empty($this->multipleHiddenIds)) {
            foreach ($this->multipleHiddenIds as $hiddenId) {


            }
        }

        $this->emit('refreshCategoryManageAndDeselectAll');
        $this->multipleHiddenShowModal = false;
    }

    // Delete modal
    public $multipleDeleteShowModal = false;
    public $multipleDeleteIds = [];
    public $multipleUndeleteIds = [];

    public function multipleDeleteShowModal($params)
    {
        $this->multipleDeleteIds = $params;
        $this->multipleDeleteShowModal = true;
    }

    public function multipleDeleteExecute()
    {
        if (!empty($this->multipleDeleteIds)) {
            foreach ($this->multipleDeleteIds as $deleteId) {

                dd($deleteId);

            }
        }

        $this->emit('refreshCategoryManageAndDeselectAll');
        $this->multipleDeleteShowModal = false;
    }



    public function render()
    {
        return view('category::admin.category.livewire.category-bulk-options');
    }
}

