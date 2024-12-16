<?php

namespace Modules\Backup\Support;

use MicroweberPackages\Export\Export;
use MicroweberPackages\Export\SessionStepper;
use Modules\Backup\Loggers\BackupLogger;

class GenerateBackup extends Export
{
    public $type = 'json';
    public $allowSkipTables = true; // this will skip sensitive tables

    public function __construct()
    {
        parent::__construct();
        $this->logger = new BackupLogger();
    }

    public function setType($type): void
    {
        parent::setType($type);
    }

    public function setExportData($dataType, $dataIds): void
    {
        parent::setExportData($dataType, $dataIds);
    }

    public function setExportAllData($exportAllData = true): void
    {
        parent::setExportAllData($exportAllData);
    }

    public function setLogger($logger): void
    {
        parent::setLogger($logger);
    }

    public function setSessionId($sessionId): void
    {
        parent::setSessionId($sessionId);
    }

    public function start()
    {
        return parent::start();
    }
}
