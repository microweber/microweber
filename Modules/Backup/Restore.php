<?php

namespace Modules\Backup;

use MicroweberPackages\Restore\Restore;
use Modules\Backup\Loggers\BackupLogger;

class Restore extends Restore
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
