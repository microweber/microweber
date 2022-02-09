<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Backup\Loggers\ImportLogger;
use MicroweberPackages\Import\DatabaseWriter;
use MicroweberPackages\Import\Import;
use MicroweberPackages\Import\Traits\ExportGetSet;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class BackupManager
{
    use ExportGetSet;

    public $exportAllData = false;
    public $exportData = ['categoryIds' => [], 'contentIds' => [], 'tables' => []];
    public $exportType = 'json';

    public $importStep = 0;
    public $importType = false;
    public $importFile = false;
    public $importLanguage = false;


    public function __construct()
    {
        \Config::set('microweber.disable_model_cache', 1);

        if (php_can_use_func('ini_set')) {
            ini_set('memory_limit', '-1');
        }

        if (php_can_use_func('set_time_limit')) {
            set_time_limit(0);
        }
    }





}
