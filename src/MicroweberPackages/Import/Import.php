<?php

namespace MicroweberPackages\Import;

use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Formats\CsvReader;
use MicroweberPackages\Import\Formats\JsonReader;
use MicroweberPackages\Import\Formats\XlsxReader;
use MicroweberPackages\Import\Formats\XmlReader;
use MicroweberPackages\Import\Formats\ZipReader;
use MicroweberPackages\Import\Loggers\ImportLogger;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

class Import
{

    /**
     * The import file type
     *
     * @var string
     */
    public $type;

    /**
     * The import file path
     *
     * @var string
     */
    public $file;

    /**
     * The import language
     * @var string
     */
    public $language = 'en';

    public $batchImporting = true;
    public $ovewriteById = false;
    public $deleteOldContent = false;
    public $deleteOldCssFiles = false;
    public $sessionId;
    public $logger;
    public $writeOnDatabase = true;

    public function __construct()
    {
        $this->logger = new ImportLogger();
    }

    /**
     * Set file type
     *
     * @param string $file
     */
    public function setType($type)
    {
        if ($type == '' or $type == false) {
            throw new \Exception('The import type should be set');
        }
        $this->type = $type;
    }

    /**
     * Set import file path
     * @param string $file
     */
    public function setFile($file)
    {
        if (!is_file($file)) {
            return array('error' => 'Backup Manager: You have not provided a existing backup to restore.');
        }

        $this->setType(get_file_extension($file));
        $this->file = $file;
    }

    public function setLanguage($abr)
    {
        $this->language = trim($abr);
    }

    public function setBatchImporting($batchImporting)
    {
        $this->batchImporting = $batchImporting;
    }


    public function setOvewriteById($overwrite)
    {
        $this->ovewriteById = $overwrite;
    }

    public function setWriteOnDatabase($write)
    {
        $this->writeOnDatabase = $write;
    }

    public function setToDeleteOldContent($delete)
    {
        $this->deleteOldContent = $delete;
    }

    public function setToDeleteOldCssFiles($delete)
    {
        $this->deleteOldCssFiles = $delete;
    }

    /**
     * Set logger
     * @param class $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    }


    /**
     * Start importing
     * @return array
     */
    public function start()
    {
        MultilanguageHelpers::setMultilanguageEnabled(false);

        if (!$this->sessionId) {
            return array("error" => "SessionId is missing.");
        }

        SessionStepper::setSessionId($this->sessionId);

        if (!SessionStepper::isFinished()) {
            SessionStepper::nextStep();
        }

        try {
            $content = $this->readContent();

            if (isset($content['error'])) {
                return $content;
            }

            if (isset($content['must_choice_language']) && $content['must_choice_language']) {
                return $content;
            }

            $log = [];
            $log['data'] = [];

            if ($this->writeOnDatabase) {
                $writer = new DatabaseWriter();
                $writer->setContent($content['data']);
                $writer->setOverwriteById($this->ovewriteById);
                $writer->setDeleteOldContent($this->deleteOldContent);
                $writer->setDeleteOldCssFiles($this->deleteOldCssFiles);
                $writer->setLogger($this->logger);

                if ($this->batchImporting) {
                    $data = $writer->runWriterWithBatch();
                } else {
                    $data = $writer->runWriter();
                }

                $log = $writer->getImportLog();
                $log['data'] = $data;
            }

            return $log;

        } catch (\Exception $e) {
            $errorMessage = array("file" => $e->getFile(), "line" => $e->getLine(), "error" => $e->getMessage());
          //  Log::error($errorMessage);
            return $errorMessage;
        }
    }

    /**
     * Import data as type
     *
     * @param array $data
     * @return array
     */
    public function importAsType($file)
    {
        $readedData = $this->_getReader($file);

        if ($readedData) {

            if (isset($readedData['must_choice_language']) && $readedData['must_choice_language']) {
                return $readedData;
            }

            $this->logger->setLogInfo('Reading data from file ' . basename($this->file));

            if (!empty($readedData)) {
                $successMessages = count($readedData, COUNT_RECURSIVE) . ' items are read.';
                $this->logger->setLogInfo($successMessages);
                return array(
                    'success' => $successMessages,
                    'imoport_type' => $this->type,
                    'data' => $readedData
                );
            }
        }

        $formatNotSupported = 'Import format not supported';
        $this->logger->setLogInfo($formatNotSupported);

        throw new \Exception($formatNotSupported);
    }

    public function readContent()
    {
        if (SessionStepper::isFirstStep()) {
            $this->logger->setLogInfo('Start importing session..');
        }

        return $this->importAsType($this->file);
    }

    private function _recognizeDataTableName($data)
    {
        $tables = $this->_getTableList();

        $filename = basename($this->file);
        $fileExtension = get_file_extension($this->file);

        if ($fileExtension == 'zip') {
            return $data;
        }

        $importToTable = str_replace('.' . $fileExtension, false, $filename);

        $foundedTable = false;
        foreach ($tables as $table) {
            if (strpos($importToTable, $table) !== false) {
                $foundedTable = $table;
                break;
            }
        }

        if ($foundedTable) {
            return array(
                $foundedTable => $data
            );
        }

        return $data;
    }

    private function _getTableList()
    {
        $readyTables = array();

        $tables = mw()->database_manager->get_tables_list();
        foreach ($tables as $table) {
            $readyTables[] = str_replace(mw()->database_manager->get_prefix(), false, $table);
        }

        return $readyTables;
    }

    /**
     * Get file reader by type
     *
     * @param array $data
     * @return boolean|\MicroweberPackages\Import\Formats\DefaultReader
     */
    private function _getReader($data = array())
    {
        if (!$this->type) {
            return;
        }
        switch ($this->type) {
            case 'json':
                $reader = new JsonReader($data);
                break;

            case 'csv':
                $reader = new CsvReader($data);
                break;

            case 'xml':
                $reader = new XmlReader($data);
                break;

            case 'xlsx':
                $reader = new XlsxReader($data);
                break;

            case 'zip':
                $reader = new ZipReader($data);
                $reader->setLanguage($this->language);
                break;

            default:
                throw new \Exception('Format not supported for importing. Trying to import as ' . $this->type);
                break;
        }

        $data = $reader->readData();

        return $this->_recognizeDataTableName($data);
    }
}
