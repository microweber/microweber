<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class NewsletterProcessCampaignsModal extends AdminModalComponent
{
    public $emailTemplates = [];

    public $modalSettings = [
        'width'=>'900px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public function render()
    {

        return view('microweber-module-newsletter::livewire.admin.process-campaigns-modal');
    }

}
