<?php

namespace MicroweberPackages\Template;

use MicroweberPackages\Import\Import;
use Modules\Backup\Loggers\BackupLogger;

class TemplateInstaller extends Import
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = false;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
