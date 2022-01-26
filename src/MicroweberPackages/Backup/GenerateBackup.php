<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Export\Export;

class GenerateBackup extends Export
{
    public $type = 'zip';
    public $exportAllData = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
