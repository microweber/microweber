<?php

namespace Modules\Backup;

use Modules\Backup\Loggers\BackupLogger;
use Modules\Import\Import;

class Restore extends Import
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
