<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Export\Export;
use MicroweberPackages\Export\SessionStepper;

class GenerateBackup extends Export
{
    public $type = 'json';
    public $allowSkipTables = false;

    public function __construct() {
        $this->logger = new BackupLogger();
    }

}
