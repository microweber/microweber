<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Export\Export;
use MicroweberPackages\Export\SessionStepper;

class GenerateBackup extends Export
{
    public $type = 'json';
    public $allowSkipTables = true; // this will skip sensitive tables

    public function __construct() {
        $this->logger = new BackupLogger();
    }

}
