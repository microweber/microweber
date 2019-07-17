<?php
namespace Microweber\Utils\Backup\Exporters;

use Microweber\Utils\Backup\Loggers\BackupExportLogger;

class ZipExport extends DefaultExport
{
	/**
	 * The current batch step.
	 * @var integer
	 */
	public $currentStep = 0;
	
	/**
	 * The total steps for batch.
	 * @var integer
	 */
	public $totalSteps = 3;
	
	/**
	 * The type of export
	 * @var string
	 */
	public $type = 'zip';
	
	/**
	 * The name of cache group for backup file.
	 * @var string
	 */
	private $_cacheGroupName = 'BackupExporting';

	public function getCurrentStep() {
		
		$this->currentStep = (int) cache_get('ExportCurrentStep', $this->_cacheGroupName);
		
		return $this->currentStep;
	}
	
	protected function _getZipFileName() {
		
		$zipFileName = cache_get('ExportZipFileName', $this->_cacheGroupName);
		
		if (empty($zipFileName)) {
			$generateFileName = $this->_generateFilename();
			cache_save($generateFileName, 'ExportZipFileName', $this->_cacheGroupName, 60 * 10);
			return $generateFileName;
		}
		
		return $zipFileName;
	}
	
	public function start() {

		if ($this->getCurrentStep() == 0) {
			// Clear old log file
			BackupExportLogger::clearLog();
		}
		
		// Get zip filename
		$zipFileName = $this->_getZipFileName();
		
		BackupExportLogger::setLogInfo('Archiving files batch: ' . $this->getCurrentStep() . '/' . $this->totalSteps);
		
		// Generate zip file
		$zip = new \ZipArchive();
		$zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
		$zip->setArchiveComment("Microweber backup of the userfiles folder and db.
                \nThe Microweber version at the time of backup was ".MW_VERSION."
                \nCreated on " . date('l jS \of F Y h:i:s A'));
		
		if ($this->getCurrentStep() == 0) {
			
			BackupExportLogger::setLogInfo('Start new exporting..');
			
			// Encode db json
			$json = new JsonExport($this->data);
			$getJson = $json->start();
			
			// Add json file
			if ($getJson['filepath']) {
				$zip->addFile($getJson['filepath'], 'mw_content.json');
			}
			
		}
		
		$userFiles = $this->_getUserFilesPaths();
		
		if (empty($userFiles)) {
			
			$zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
			$zip->close();
			
			BackupExportLogger::setLogInfo('No userfiles..');
			$this->_finishUp();
			
			return $zipFileName;
		}
		
		$totalUserFilesForZip = sizeof($userFiles);
		$totalUserFilesForBatch = round($totalUserFilesForZip / $this->totalSteps, 0);
		
		if ($totalUserFilesForBatch > 0) {
			$userFilesBatch = array_chunk($userFiles, $totalUserFilesForBatch);
		} else {
			$userFilesBatch = array();
			$userFilesBatch[] = $userFiles;
		}
		
		if (!isset($userFilesBatch[$this->getCurrentStep()])) {
			
			BackupExportLogger::setLogInfo('No files in batch for current step.');
			$this->_finishUp();
			
			return $zipFileName;
		}
		
		foreach($userFilesBatch[$this->getCurrentStep()] as $file) {
			BackupExportLogger::setLogInfo('Archiving file <b>'. $file['dataFile'] . '</b>');
			$zip->addFile($file['filePath'], $file['dataFile']);
		}
        
		if (method_exists($zip, 'setCompressionIndex')) {
		    $zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
        }
        
		$zip->close();
		
		$exportLog = $this->getExportLog();
		
		cache_save($this->getCurrentStep() + 1, 'ExportCurrentStep', $this->_cacheGroupName, 60 * 10);
		
		return $exportLog;
	}
	
	public function getExportLog() {
		
		$log = array();
		$log['current_step'] = $this->getCurrentStep();
		$log['total_steps'] = $this->totalSteps;
		$log['precentage'] = number_format((($this->getCurrentStep() * 100) / $this->totalSteps), 2);
		$log['data'] = false;
		
		if ($this->getCurrentStep() >= $this->totalSteps) {
			$log['done'] = true;
		}
		
		return $log;
	}
	
	public function clearSteps() {
		cache_delete($this->_cacheGroupName);
	}
	
	protected function _getUserFilesPaths() {
		
		$userFiles = array();
		$userFilesReady = array();
		
		$userFilesPathCss = userfiles_path() . DIRECTORY_SEPARATOR . 'css';
		$userFilesPathMedia = userfiles_path() . DIRECTORY_SEPARATOR . 'media';
		
		if (!is_dir($userFilesPathCss)) {
			mkdir_recursive($userFilesPathCss);
		}
		
		if (!is_dir($userFilesPathMedia)) {
			mkdir_recursive($userFilesPathMedia);
		}
		
		$css = $this->_getDirContents($userFilesPathCss);
		$media = $this->_getDirContents($userFilesPathMedia);
		
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
	
	protected function _getDirContents($path) {
		
		if (!is_dir($path)) {
			return array();
		}
		
		$rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

		$files = array();
		foreach ($rii as $file) {
			if (! $file->isDir()) {
				$files[] = $file->getPathname();
			}
		}
		return $files;
	}
	
	/**
	 * Clear all cache 
	 */
	protected function _finishUp() {
		$this->clearSteps();
		BackupExportLogger::setLogInfo('Done!');
		
	}
	
}
