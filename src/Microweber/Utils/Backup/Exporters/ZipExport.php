<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Loggers\BackupExportLogger;

class ZipExport extends DefaultExport
{
	public function start() {
		
		$json = new JsonExport($this->data);
		$getJson = $json->start();
		
		// Get zip filename
		$zipFilename = $this->_generateFilename();
		
		// Generate zip file
		$zip = new \Microweber\Utils\Zip($zipFilename['filepath']);
		$zip->setZipFile($zipFilename['filepath']);
		$zip->setComment("Microweber backup of the userfiles folder and db.
                \n The Microweber version at the time of backup was {MW_VERSION}
                \nCreated on " . date('l jS \of F Y h:i:s A'));
		
		// Add json file
		if ($getJson['filepath']) {
			$zip->addLargeFile($getJson['filepath'], 'mw_content.json', filectime($getJson['filepath']), 'Json Restore file');
		}
		
		$userFiles = $this->_getUserFilesPaths();
		
		foreach($userFiles as $file) {
						
			BackupExportLogger::setLogInfo('Archiving file <b>'. $file['dataFile'] . '</b>');
			
			$zip->addLargeFile($file['filePath'], $file['dataFile']);
		}
		
		$zip->finalize();
		
		return $zipFilename;
	}
	
	private function _getUserFilesPaths() {
		
		$userFiles = array();
		$userFilesReady = array();
		
		$css = $this->_getDirContents(userfiles_path() . DIRECTORY_SEPARATOR . 'css');
		$media = $this->_getDirContents(userfiles_path() . DIRECTORY_SEPARATOR . 'media');
		
		$userFiles = array_merge($css, $media);
		
		foreach($userFiles as $filePath) {
			
			$dataFile = str_replace(userfiles_path() . DIRECTORY_SEPARATOR, false, $filePath);
			
			$dataFile = normalize_path($dataFile, false);
			$filePath =  normalize_path($filePath, false);
			
			$userFilesReady[] = array(
				'dataFile'=>$dataFile,
				'filePath'=>$filePath
			);
			
		}
		
		return $userFilesReady;
		
	}
	
	private function _getDirContents($path) {
		
		$rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

		$files = array();
		foreach ($rii as $file) {
			if (! $file->isDir()) {
				$files[] = $file->getPathname();
			}
		}
		return $files;
	}
}