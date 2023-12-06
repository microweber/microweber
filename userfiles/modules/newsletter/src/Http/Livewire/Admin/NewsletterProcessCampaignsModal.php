<?php

namespace MicroweberPackages\Modules\Newsletter\Http\Livewire\Admin;

use MicroweberPackages\Admin\Http\Livewire\AdminModalComponent;
use MicroweberPackages\Backup\Loggers\DefaultLogger;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;
use MicroweberPackages\Modules\Newsletter\ProcessCampaigns;

class NewsletterProcessCampaignsModal extends AdminModalComponent
{
    public $log = '';
    private $logger;
    public $logPublicUrl = '';

    public $modalSettings = [
        'width'=>'900px',
        'overlay' => true,
        'overlayClose' => true,
    ];

    public $listeners = [
        'processCampaigns'=>'processCampaigns'
    ];

    public function render()
    {
        return view('microweber-module-newsletter::livewire.admin.process-campaigns-modal');
    }

    public function mount()
    {
        $this->setupLogger();
    }

    public function setupLogger()
    {
        $this->logger = new ProcessCampaignsLogger();
        $this->logger->clearLog();

        $logPublicUrl = $this->logger->getLogFilepath();
        $logPublicUrl = str_replace(userfiles_path(), userfiles_url(), $logPublicUrl);

        $this->logPublicUrl = $logPublicUrl;
    }

    public function processCampaigns()
    {
        $this->setupLogger();

        $processCampaigns = new ProcessCampaigns();
        $processCampaigns->setLogger($this->logger);
        $processCampaigns->run();
    }

}

class ProcessCampaignsLogger extends DefaultLogger {

    public function info($msg) {
       $this->setLogInfo($msg . '<br>');
    }

    public function warn($msg) {
        $this->setLogInfo($msg. '<br>');
    }

    public function error($msg) {
        $this->setLogInfo($msg. '<br>');
    }

}
