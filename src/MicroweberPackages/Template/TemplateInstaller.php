<?php

namespace MicroweberPackages\Template;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Import\Import;

class TemplateInstaller extends Import
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }
}
