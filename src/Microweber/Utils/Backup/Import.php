<?php
namespace Microweber\Utils\Backup;

use Microweber\Utils\Backup\Readers\ZipReader;
use Microweber\Utils\Backup\Readers\JsonReader;
use Microweber\Utils\Backup\Readers\CsvReader;
use Microweber\Utils\Backup\Readers\XmlReader;
use Microweber\App\Providers\Illuminate\Support\Facades\Cache;
use Microweber\Utils\Backup\Traits\BackupLogger;

class Import
{
	use BackupLogger;

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

	/**
	 * Import data as type
	 *
	 * @param array $data
	 * @return array
	 */
	public function importAsType($data)
	{
		$reader = $this->_getReader($data);

		if ($reader) {
			$this->setLogInfo('Reading data from file ' . basename($this->file));
			$readedData = $reader->readData();

			if (! empty($readedData)) {
				$successMessages = count($readedData, COUNT_RECURSIVE) . ' items are readed.';
				$this->setLogInfo($successMessages);
				return array(
					'success' => $successMessages,
					'imoport_type' => $this->type,
					'data' => $readedData
				);
			}
		}

		$formatNotSupported = 'Import format not supported.';
		$this->setLogInfo($formatNotSupported);
		return array(
			'error' => $formatNotSupported
		);
	}

	/**
	 * Get readed content from import file.
	 *
	 * @return array
	 */
	public function readContentWithCache()
	{
		return Cache::rememberForever(md5($this->file . $this->type), function () {
			$this->setLogInfo('Start importing session..');
			return $this->importAsType($this->file);
		});
	}

	/**
	 * Get file reader by type
	 *
	 * @param array $data
	 * @return boolean|\Microweber\Utils\Backup\Readers\DefaultReader
	 */
	private function _getReader($data = array())
	{
		$reader = false;

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
			case 'zip':
				$reader = new ZipReader($data);
				break;
			// Don't forget a break
		}

		return $reader;
	}
}
