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
	public $totalSteps = 20;
	
	/**
	 * The type of export
	 * @var string
	 */
	public $type = 'zip';
	
	/**
	 * Files in zip
	 * @var array
	 */
	public $files = array();
	
	/**
	 * Export media
	 * @var string
	 */
	public $exportMedia = false;
	
	/**
	 * The name of cache group for backup file.
	 * @var string
	 */
	private $_cacheGroupName = 'BackupExporting';

	public function getCurrentStep() {
		
		$this->currentStep = (int) cache_get('ExportCurrentStepZip', $this->_cacheGroupName);
		
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
	
	public function setExportMedia($bool) {
		$this->exportMedia = $bool;
	}
	
	public function addFile($file) {
		$this->files[] = $file;
	}
	
	public function start() {
		
		$filesForZip = array();
		
		if ($this->getCurrentStep() == 0) {
			// Clear old log file
			BackupExportLogger::clearLog();
			BackupExportLogger::setLogInfo('Start new exporting..');
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
		
		if (!empty($this->files))  {
			/* foreach($this->files as $file) {
			 BackupExportLogger::setLogInfo('Archiving file <b>'. $file['filename'] . '</b>');
			 $zip->addFile($file['filepath'], $file['filename']);
			 } */
			$filesForZip = array_merge($filesForZip, $this->files);
		}
		
		if ($this->exportMedia) {
			$userFiles = $this->_getUserFilesPaths();
			$filesForZip = array_merge($filesForZip, $userFiles);
		}
		
		/* 
		if (empty($filesForZip)) {
			
			$zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
			$zip->close();
			
			BackupExportLogger::setLogInfo('No userfiles..');
			$this->_finishUp();
			
			return $zipFileName;
		}
		*/
		
		$totalFilesForZip = sizeof($filesForZip);
		$totalFilesForBatch = round($totalFilesForZip / $this->totalSteps, 0);
		
		if ($totalFilesForBatch > 0) {
			$filesBatch = array_chunk($filesForZip, $totalFilesForBatch);
		} else {
			$filesBatch = array();
			$filesBatch[] = $filesForZip;
		}
		
		if (!isset($filesBatch[$this->getCurrentStep()])) {
			
			BackupExportLogger::setLogInfo('No files in batch for current step.');
			$this->_finishUp();
			
			return $zipFileName;
		}
		
		foreach($filesBatch[$this->getCurrentStep()] as $file) {
			BackupExportLogger::setLogInfo('Archiving file <b>'. $file['filename'] . '</b>');
			$zip->addFile($file['filepath'], $file['filename']);
		}
        
		if (method_exists($zip, 'setCompressionIndex')) {
		    $zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
        }
        
		$zip->close();
		
		cache_save($this->getCurrentStep() + 1, 'ExportCurrentStepZip', $this->_cacheGroupName, 60 * 10);
		
		return $this->getExportLog();
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
				'filename'=>$dataFile,
				'filepath'=>$filePath
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
