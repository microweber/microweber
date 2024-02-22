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
    public $batchImporting = true;
    public $importOvewriteById = false;
    public $importLanguage = false;

    public $deleteOldContent = false;

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

    /**
     * Set logger
     * @param class $logger
     */
    public function setLogger($logger)
    {

        ImportLogger::setLogger($logger);
        BackupLogger::setLogger($logger);

    }


    public function setImportStep($step)
    {
        $this->importStep = $step;
    }

    /**
     * Set import file format
     * @param string $type
     */
    public function setImportType($type)
    {
        $this->importType = $type;
    }

    public function setBatchImporting($batchImporting)
    {
        $this->batchImporting = $batchImporting;
    }

    public function setImportOvewriteById($overwrite)
    {
        $this->importOvewriteById = $overwrite;
    }

    public function setToDeleteOldContent($delete)
    {
        $this->deleteOldContent = $delete;
    }

    /**
     * Set import file path
     * @param string $file
     */
    public function setImportFile($file)
    {
        if (!is_file($file)) {
            return array('error' => 'Backup Manager: You have not provided a existing backup to restore.');
        }

        $this->setImportType(pathinfo($file, PATHINFO_EXTENSION));
        $this->importFile = $file;
    }

    public function setImportLanguage($abr)
    {
        $this->importLanguage = trim($abr);
    }

    /**
     * Start importing
     * @return array
     */
    public function startImport()
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        try {
            $import = new Import();
            $import->setStep($this->importStep);
            $import->setType($this->importType);
            $import->setFile($this->importFile);
            $import->setLanguage($this->importLanguage);

            $content = $import->readContent();
            if (isset($content['error'])) {
                return $content;
            }

            if (isset($content['must_choice_language']) && $content['must_choice_language']) {
                return $content;
            }

            $writer = new DatabaseWriter();
            $writer->setStep($this->importStep);
            $writer->setContent($content['data']);
            $writer->setOverwriteById($this->importOvewriteById);
            $writer->setDeleteOldContent($this->deleteOldContent);

            if ($this->batchImporting) {
                $writer->runWriterWithBatch();
            } else {
                $writer->runWriter();
            }

            return $writer->getImportLog();

        } catch (\Exception $e) {
            return array("file" => $e->getFile(), "line" => $e->getLine(), "error" => $e->getMessage());
        }
    }

}
