<?php

namespace Modules\Restore;

use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use Modules\Backup\SessionStepper;
use Modules\Restore\Formats\ZipReader;
use Modules\Restore\Loggers\RestoreLogger;

class Restore
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

    public $batchRestoring = true;
    public $ovewriteById = false;
    public $deleteOldContent = false;
    public $deleteOldCssFiles = false;
    public $sessionId;
    public $logger;
    public $writeOnDatabase = true;

    public function __construct()
    {
        $this->logger = new RestoreLogger();
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

    public function setBatchRestoring($batchRestoring)
    {
        $this->batchRestoring = $batchRestoring;
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
     * Start restoring
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

                if ($this->batchRestoring) {
                    $data = $writer->runWriterWithBatch();
                } else {
                    $data = $writer->runWriter();
                }

                $log = $writer->getRestoreLog();
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
     * Restore data as type
     *
     * @param array $data
     * @return array
     */
    public function restoreAsType($file)
    {
        $reader = new ZipReader($file);
        $reader->setLanguage($this->language);
        $data = $reader->readData();

        dd($data);

        $readedData = $this->_recognizeDataTableName($data);

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

        $formatNotSupported = 'Restore format not supported';
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

}
