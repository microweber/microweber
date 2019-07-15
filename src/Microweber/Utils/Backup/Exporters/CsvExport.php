<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Loggers\BackupExportLogger;

class CsvExport extends DefaultExport
{

	public function start()
	{
		
		$export = array();
		
		if (!empty($this->data)) {
			foreach ($this->data as $tableName=>$tableData) {
				
				foreach($tableData as $item) {
					
					if ($tableName == 'content') {
						$readyItem = array();
						$readyItem['id'] = $item['id'];
						$readyItem['content_type'] = $item['content_type'];
						$readyItem['title'] = $item['title'];
						$readyItem['url'] = $item['url'];
						$readyItem['content_body'] = trim($item['content']);
						
						
						$export[$tableName][] = $readyItem;
					} else {
						$export[$tableName][] = $item;
					}
				}
			}
		}
		
		$exportedFiles = array();
		
		if (!empty($export)) {
			
			foreach($export as $tableName=>$exportData) {
			
				$generateFileName = $this->_generateFilename($tableName);
		 		
				$csv = \League\Csv\Writer::createFromPath($generateFileName['filepath'], 'w');
				$csv->setEncodingFrom('UTF-8');
				$csv->setOutputBOM(\League\Csv\Writer::BOM_UTF8);
				$csv->insertOne(array_keys(reset($exportData)));
				$csv->insertAll($exportData);
				
				$exportedFiles[] = $generateFileName;
			}
		}
		
		$zip = new CsvZipExport();
		foreach ($exportedFiles as $file) {
			$zip->addFile($file);
		}
		$zipFile = $zip->start();
		
		// Remove files
		foreach ($exportedFiles as $file) {
			unlink($file['filepath']);
		}
		
		return $zipFile;
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

class CsvZipExport extends ZipExport {
	
	protected $_files = array();
	
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
		
		
		$userFiles = $this->_getUserFilesPaths();
		
		if (!empty($userFiles)) {
			foreach($userFiles as $file) {
				BackupExportLogger::setLogInfo('Archiving file <b>'. $file['dataFile'] . '</b>');
				$zip->addFile($file['filePath'], $file['dataFile']);
			}
		}
		
		$zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
		$zip->close();

		BackupExportLogger::setLogInfo('All files are ziped.');
		
		$this->_finishUp();
		
		return $zipFileName;
	}
}