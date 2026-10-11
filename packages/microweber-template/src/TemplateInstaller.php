<?php

namespace MicroweberPackages\Template;

use Modules\Backup\Loggers\BackupLogger;
use Modules\Restore\Restore;

class TemplateInstaller extends Restore
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = false;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
