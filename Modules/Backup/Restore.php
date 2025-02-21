<?php

namespace Modules\Backup;

use Modules\Backup\Loggers\BackupLogger;
use Modules\Restore\Restore;

class Restore extends Restore
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
