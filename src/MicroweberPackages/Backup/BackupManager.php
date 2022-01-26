<?php

namespace MicroweberPackages\Backup;

use MicroweberPackages\Backup\Loggers\BackupImportLogger;
use MicroweberPackages\Backup\Loggers\BackupExportLogger;
use MicroweberPackages\Backup\Traits\ExportGetSet;
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
    public $importBatch = true;
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

        BackupImportLogger::setLogger($logger);
        BackupExportLogger::setLogger($logger);

    }

    /**
     * Set export full
     * @param string $type
     */
    public function setExportAllData($exportAllData = true)
    {
        $this->exportAllData = $exportAllData;
    }

    /**
     * Set export file format
     * @param string $type
     */
    public function setExportType($type)
    {
        $this->exportType = $type;
    }

    /**
     * Set wich data want to export
     * @param array $data
     */
    public function setExportData($dataType, $dataIds)
    {
        $this->exportData[$dataType] = $dataIds;
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

    public function setImportBatch($importBatch)
    {
        $this->importBatch = $importBatch;
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

            if ($this->importBatch) {
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
