<?php

namespace MicroweberPackages\Template\Http\Livewire;


use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;

class AdminTemplateUpdateModal extends AdminModalComponent
{
    public function render()
    {
        return view('template::livewire.admin.template-update-modal');
    }
}
