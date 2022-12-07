<?php

namespace MicroweberPackages\Content\Http\Livewire;

use Livewire\Component;
use MicroweberPackages\Content\Models\Content;

class ContentBulkOptions extends Component
{
    protected $listeners = [
        'multipleMoveToCategory' => 'multipleMoveToCategoryShowModal',

        'multiplePublish' => 'multiplePublishShowModal',
        'multiplePublishExecute' => 'multiplePublishExecute',

        'multipleUnpublish' => 'multipleUnpublishShowModal',
        'multipleUnpublishExecute' => 'multipleUnpublishExecute',

        'multipleDelete' => 'multipleDeleteShowModal',
        'multipleDeleteExecute' => 'multipleDeleteExecute',

        'multipleUndelete' => 'multipleUndeleteShowModal',
        'multipleUndeleteExecute' => 'multipleUndeleteExecute',

        'multipleDeleteForever' => 'multipleDeleteForeverShowModal',
        'multipleDeleteForeverExecute' => 'multipleDeleteForeverExecute',


    ];


    // Move to category modal

    public $multipleMoveToCategoryShowModal = false;
    public $multipleMoveToCategoryIds = [];
    public function multipleMoveToCategoryShowModal($params)
    {
        $this->multipleMoveToCategoryIds = $params;
        $this->multipleMoveToCategoryShowModal = true;
        $this->emit('multipleMoveToCategoryShowModalOpen');

    }

    public function multipleMoveToCategoryExecute()
    {
        $this->emit('refreshProductsListAndDeselectAll');
        $this->multipleMoveToCategoryShowModal = false;
    }


    // Publish modal
    public $multiplePublishShowModal = false;
    public $multiplePublishIds = [];

    public function multiplePublishShowModal($params)
    {
        $this->multiplePublishIds = $params;
        $this->multiplePublishShowModal = true;
    }

    public function multiplePublishExecute()
    {
        if (!empty($this->multiplePublishIds)) {
            foreach ($this->multiplePublishIds as $publishId) {
                $findContent = Content::where('id', $publishId)->first();
                if ($findContent !== null) {
                    $findContent->is_active = 1;
                    $findContent->save();
                }
            }
        }

        $this->emit('refreshProductsListAndDeselectAll');
        $this->multiplePublishShowModal = false;
    }

    // Unpublish modal
    public $multipleUnpublishShowModal = false;
    public $multipleUnpublishIds = [];

    public function multipleUnpublishShowModal($params)
    {
        $this->multipleUnpublishIds = $params;
        $this->multipleUnpublishShowModal = true;
    }

    public function multipleUnpublishExecute()
    {
        if (!empty($this->multipleUnpublishIds)) {
            foreach ($this->multipleUnpublishIds as $unpublishId) {
                $findContent = Content::where('id', $unpublishId)->first();
                if ($findContent !== null) {
                    $findContent->is_active = 0;
                    $findContent->save();
                }
            }
        }

        $this->emit('refreshProductsListAndDeselectAll');
        $this->multipleUnpublishShowModal = false;
    }

    // Delete modal
    public $multipleDeleteShowModal = false;
    public $multipleUndeleteShowModal = false;
    public $multipleDeleteForeverShowModal = false;
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
                $findContent = Content::where('id', $deleteId)->first();
                if ($findContent !== null) {
                    $findContent->is_deleted = 1;
                    $findContent->save();
                    //$findContent->delete();
                }
            }
        }

        $this->emit('refreshProductsListAndDeselectAll');
        $this->multipleDeleteShowModal = false;
    }


    public function multipleDeleteForeverShowModal($params)
    {
        $this->multipleDeleteIds = $params;
        $this->multipleDeleteForeverShowModal = true;
    }






    public function multipleUndeleteShowModal($params)
    {
        $this->multipleUndeleteIds = $params;
        $this->multipleUndeleteShowModal = true;
    }



    public function multipleUndeleteExecute()
    {
        if (!empty($this->multipleUndeleteIds)) {
            foreach ($this->multipleUndeleteIds as $deleteId) {
                $findContent = Content::where('id', $deleteId)->first();
                if ($findContent !== null) {
                    $findContent->is_deleted = 0;
                    $findContent->save();
                 }
            }
        }

        $this->emit('refreshProductsListAndDeselectAll');
        $this->multipleUndeleteShowModal = false;
    }


    public function multipleUndeleteForeverShowModal($params)
    {
        $this->multipleUndeleteIds = $params;
        $this->multipleUndeleteForeverShowModal = true;
    }





    public function multipleDeleteForeverExecute()
    {
        if (!empty($this->multipleDeleteIds)) {
            foreach ($this->multipleDeleteIds as $deleteId) {
                $findContent = Content::where('id', $deleteId)->first();
                if ($findContent !== null) {
                    $findContent->delete();
                }
            }
        }

        $this->emit('refreshProductsListAndDeselectAll');
        $this->multipleDeleteForeverShowModal = false;
    }



    public function render()
    {
        return view('content::admin.livewire.content-bulk-options');
    }
}

