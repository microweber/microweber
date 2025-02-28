<?php

namespace Modules\Newsletter\Livewire\Admin;

use Modules\Backup\Loggers\DefaultLogger;

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
