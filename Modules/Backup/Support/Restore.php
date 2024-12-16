<?php

namespace Modules\Backup\Support;

use MicroweberPackages\Import\Import;
use Modules\Backup\Loggers\BackupLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Restore extends Import
{
    public $batchImporting = true;
    public $ovewriteById = true;
    public $deleteOldContent = true;

    public function __construct() {
        $this->logger = new BackupLogger();
    }


}
