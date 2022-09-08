<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;
use MicroweberPackages\Content\Content;

class ContentBulkOptions extends Component
{
    protected $listeners = [
        'multipleMoveToCategory' => 'multipleMoveToCategoryShowModal',
        'multipleMoveToCategoryExecute' => 'multipleMoveToCategoryExecute',


        'multiplePublish' => 'multiplePublishShowModal',
        'multiplePublishExecute' => 'multiplePublishExecute',

        'multipleUnpublish' => 'multipleUnpublishShowModal',
        'multipleUnpublishExecute' => 'multipleUnpublishExecute',

        'multipleDelete' => 'multipleDeleteShowModal',
        'multipleDeleteExecute' => 'multipleDeleteExecute',
    ];

    public $moveToCategory = false;

    public function multipleMoveToCategoryShowModal($params)
    {
        $this->moveToCategory = true;
    }

    public $multiplePublish = false;

    public function multiplePublishShowModal($params)
    {
        $this->multiplePublish = true;
    }

    public $multipleUnpublish = false;

    public function multipleUnpublishShowModal($params)
    {
        $this->multipleUnpublish = true;
    }



    // Delete modal
    public $multipleDeleteShowModal = false;
    public $multipleDeleteIds = [];

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
                    $findContent->delete();
                }
            }
        }
        
        $this->multipleDeleteShowModal = false;
    }

    public function render()
    {
        return view('livewire::livewire.content-bulk-options');
    }
}

