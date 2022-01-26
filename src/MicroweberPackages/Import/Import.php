<?php
namespace MicroweberPackages\Import;

use MicroweberPackages\Backup\Loggers\BackupImportLogger;
use MicroweberPackages\Backup\Readers\CsvReader;
use MicroweberPackages\Backup\Readers\JsonReader;
use MicroweberPackages\Backup\Readers\XlsxReader;
use MicroweberPackages\Backup\Readers\XmlReader;
use MicroweberPackages\Backup\Readers\ZipReader;
use function get_file_extension;
use function mw;

class Import
{

    public $step = 0;

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

	public function setStep($step)
    {
        $this->step = intval($step);
    }

	/**
	 * Set file type
	 *
	 * @param string $file
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Set file path
	 *
	 * @param string $file
	 */
	public function setFile($file)
	{
		$this->file = $file;
	}

	public function setLanguage($abr) {
	    $this->language = $abr;
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

            BackupImportLogger::setLogInfo('Reading data from file ' . basename($this->file));

			if (! empty($readedData)) {
				$successMessages = count($readedData, COUNT_RECURSIVE) . ' items are readed.';
				BackupImportLogger::setLogInfo($successMessages);
				return array(
					'success' => $successMessages,
					'imoport_type' => $this->type,
					'data' => $readedData
				);
			}
		}

		$formatNotSupported = 'Import format not supported';
		BackupImportLogger::setLogInfo($formatNotSupported);

		throw new \Exception($formatNotSupported);
	}

	public function readContent()
	{
        if ($this->step == 0) {
            BackupImportLogger::setLogInfo('Start importing session..');
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
	 * @return boolean|\MicroweberPackages\Backup\Readers\DefaultReader
	 */
	private function _getReader($data = array())
	{
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
				throw new \Exception('Format not supported for importing.');
				break;
		}

		$data = $reader->readData();

		return $this->_recognizeDataTableName($data);
	}
}
