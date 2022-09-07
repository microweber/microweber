<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;

class ContentBulkOptions extends Component
{
    protected $listeners = [
        'multipleMoveToCategory' => 'multipleMoveToCategoryExecute',
        'multiplePublish' => 'multiplePublishExecute',
        'multipleUnpublish' => 'multipleUnpublishExecute',
        'multipleDelete' => 'multipleDeleteExecute',
    ];

    public $moveToCategory = false;

    public function multipleMoveToCategoryExecute($params)
    {
        $this->moveToCategory = true;
    }

    public $multiplePublish = false;

    public function multiplePublishExecute($params)
    {
        $this->multiplePublish = true;
    }

    public $multipleUnpublish = false;

    public function multipleUnpublishExecute($params)
    {
        $this->multipleUnpublish = true;
    }

    public $multipleDelete = false;

    public function multipleDeleteExecute($params)
    {
        $this->multipleDelete = true;
    }

    public function render()
    {
        return view('livewire::livewire.content-bulk-options');
    }
}

