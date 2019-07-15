<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Loggers\BackupExportLogger;

class CsvExport extends DefaultExport
{
	
	/**
	 * The type of export
	 * @var string
	 */
	public $type = 'csv';

	public function start()
	{
		
		// $export = array();
		
		/* if (!empty($this->data)) {
			foreach ($this->data as $tableName=>$tableData) {
				foreach($tableData as $item) {
					$export[$tableName][] = $item;
				}
			}
		} */

		$exportedFiles = array();
		
		if (!empty($this->data)) {
			foreach($this->data as $tableName=>$exportData) {
				
				if (empty($exportData)) {
					continue;
				}
				
				$generateFileName = $this->_generateFilename($tableName);
		 		
				$csv = \League\Csv\Writer::createFromPath($generateFileName['filepath'], 'w');
				$csv->setEncodingFrom('UTF-8');
				$csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8);
				$csv->insertOne(array_keys(reset($exportData)));
				$csv->insertAll($exportData);
				
				$exportedFiles[] = $generateFileName;
			}
		}

		return array("files"=>$exportedFiles);
	}

	public function array2csv($data, $delimiter = ',', $enclosure = '"', $escape_char = "\\")
	{
		
		$f = fopen('php://memory', 'r+');
		foreach ($data as $item) {
			fputcsv($f, $item, $delimiter, $enclosure, $escape_char);
		}
		rewind($f);
		return stream_get_contents($f);
		
	}
}

class CsvZipExport {
	
	protected $_exportMedia = false;
	protected $_files = array();
	
	public function setExportMedia($active) {
		$this->_exportMedia[] = $active;
	}
	
	public function addFile($file) {
		$this->_files[] = $file;
	}
	
	public function start() {
		
		// Clear cache
		mw()->cache_manager->clear();
		
		// Get zip filename
		$zipFileName = $this->_getZipFileName();
		
		// Generate zip file
		$zip = new \ZipArchive();
		$zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
		$zip->setArchiveComment("Microweber backup of the userfiles folder and db.
                \nThe Microweber version at the time of backup was ".MW_VERSION."
                \nCreated on " . date('l jS \of F Y h:i:s A'));
		
		foreach ($this->_files as $file) {
			BackupExportLogger::setLogInfo('Archiving file <b>'. $file['filename'] . '</b>');
			$zip->addFile($file['filepath'], $file['filename']);
		}
		
		if ($this->_exportMedia) {
			$userFiles = $this->_getUserFilesPaths();
			
			if (!empty($userFiles)) {
				foreach($userFiles as $file) {
					BackupExportLogger::setLogInfo('Archiving file <b>'. $file['dataFile'] . '</b>');
					$zip->addFile($file['filePath'], $file['dataFile']);
				}
			}
		}
		
		$zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
		$zip->close();

		BackupExportLogger::setLogInfo('All files are ziped.');
		
		$this->_finishUp();
		
		return $zipFileName;
	}
}