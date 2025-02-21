<?php

namespace MicroweberPackages\Template;

use MicroweberPackages\Restore\Restore;
use Modules\Backup\Loggers\BackupLogger;

class TemplateInstaller extends Restore
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = false;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
