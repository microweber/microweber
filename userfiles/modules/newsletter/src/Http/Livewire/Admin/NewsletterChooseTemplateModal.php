<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;

class NewsletterChooseTemplateModal extends AdminModalComponent
{
    public $emailTemplates = [];

    public function render()
    {

        $this->emailTemplates[] = [
            'name' => 'Default',
            'path' => 'default',
            'image' => 'https://microweber.com/userfiles/media/2019/11/20/1574250009_5dd4e0d9e3e3e.png',
        ];

        return view('microweber-module-newsletter::livewire.admin.choose-template-modal');
    }

}
