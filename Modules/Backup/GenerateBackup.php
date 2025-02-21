<?php

namespace Modules\Backup;

use MicroweberPackages\Export\Backup;
use Modules\Backup\Loggers\BackupLogger;

class GenerateBackup extends Backup
{
    public $type = 'json';
    public $allowSkipTables = true; // this will skip sensitive tables

    public function __construct() {
        $this->logger = new BackupLogger();
    }

}
