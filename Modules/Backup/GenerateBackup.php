<?php

namespace Modules\Backup;

use Modules\Backup\Loggers\BackupLogger;
use Modules\Export\Export;
use Modules\Export\SessionStepper;

class GenerateBackup extends Export
{
    public $type = 'json';
    public $allowSkipTables = true; // this will skip sensitive tables

    public function __construct() {
        $this->logger = new BackupLogger();
    }

}
