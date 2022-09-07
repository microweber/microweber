<?php

namespace MicroweberPackages\Product\Http\Livewire\Admin;

use Livewire\Component;

class ContentBulkOptions extends Component
{
    protected $listeners = ['multipleMoveToCategory' => 'multipleMoveToCategoryExecute'];

    public $moveToCategory = false;

    public function multipleMoveToCategoryExecute($params)
    {
        $this->moveToCategory = true;
    }

    public function render()
    {
        return view('livewire::livewire.content-bulk-options');
    }
}

