<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Import\Import;

class Restore extends Import
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
