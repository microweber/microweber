<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\ProcessCampaigns;

class NewsletterProcessCampaignsModal extends AdminModalComponent
{
    public $log = '';

    public $modalSettings = [
        'width'=>'900px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public function render()
    {
        return view('microweber-module-newsletter::livewire.admin.process-campaigns-modal');
    }

    public function processCampaigns()
    {
        $processCampaigns = new ProcessCampaigns();
        $processCampaigns->setLogger($this);
        $processCampaigns->run();
    }

    public function info($message)
    {
        $this->log .= '<span class="text-primary">' . $message . '</span><br />';
    }

    public function warn($message)
    {
        $this->log .= '<span class="text-warning">' . $message . '</span><br />';
    }

    public function error($message)
    {
        $this->log .= '<span style="text-danger">' . $message . '</span><br />';
    }
}
