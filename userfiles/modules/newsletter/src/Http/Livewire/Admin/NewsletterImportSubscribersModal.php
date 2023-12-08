<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Backup\Loggers\DefaultLogger;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\ProcessCampaigns;

class NewsletterImportSubscribersModal extends AdminModalComponent
{
    public $modalSettings = [
        'width'=>'900px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public $importSubscribers = [
        'sourceType' => 'uploadFile'
    ];

    public function render()
    {
        return view('microweber-module-newsletter::livewire.admin.import-subscribers-modal');
    }
}
