<?php

namespace Modules\Backup;

use MicroweberPackages\Export\Export;
use Modules\Backup\Loggers\BackupLogger;

class GenerateBackup extends Export
{
    public $type = 'json';
    public $allowSkipTables = true; // this will skip sensitive tables

    public function __construct() {
        $this->logger = new BackupLogger();
    }

}
