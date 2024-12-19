<?php

namespace Modules\Comments\Livewire\Editors;

use Livewire\Component;

class EasyMdeComponent extends Component
{
    public $model;
    public $autofocus = false;
    public $placeholder = '';

    public function mount($model, $autofocus = false, $placeholder = '')
    {
        $this->model = $model;
        $this->autofocus = $autofocus;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return view('modules.comments::components.editors.easymde');
    }
}
