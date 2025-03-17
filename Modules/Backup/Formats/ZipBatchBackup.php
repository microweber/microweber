<?php

namespace Modules\Backup\Formats;

use Modules\Backup\SessionStepper;
use Modules\Backup\Traits\BackupFileNameGetSet;
use Modules\Backup\Traits\BackupGetSet;

class ZipBatchBackup extends DefaultBackup
{
    use BackupGetSet;
    use BackupFileNameGetSet;

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
     * The name of cache group for backup file.
     * @var string
     */
    private $_cacheGroupName = 'BackupExporting';

    public $logger;

    /**
     * Set logger
     * @param class $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    protected function _getZipFileName()
    {
        $cacheKey = 'backup_zipfilename_' . SessionStepper::$sessionId;
        $zipFileName = cache_get($cacheKey, $this->_cacheGroupName);

        if (empty($zipFileName)) {

            $customZipFileName = false;
            if ($this->backupOnlyTemplate) {
                $customZipFileName = 'backup_' . str_slug($this->backupOnlyTemplate) . date("Y-m-d-his");
            }

            if (!empty($this->backupFileName)) {
                $customZipFileName = $this->backupFileName;
            }

            $generateFileName = $this->_generateFilename($customZipFileName);

            cache_save($generateFileName, $cacheKey, $this->_cacheGroupName, 60 * 10);
            return $generateFileName;
        }

        return $zipFileName;
    }

    public function addFile($file)
    {
        $this->files[] = $file;
    }

    public function start()
    {
        $filesForZip = array();

        $currentStep = SessionStepper::currentStep();
        $totalSteps = SessionStepper::totalSteps();

        if ($currentStep == 1) {
            // Clear old log file
            $this->logger->clearLog();
            $this->logger->setLogInfo('Start new exporting..');
        }

        // Get zip filename
        $zipFileName = $this->_getZipFileName();

        $this->logger->setLogInfo('Archiving files batch: ' . $currentStep . '/' . $totalSteps);

        // Generate zip file
        $zip = new \ZipArchive();

        if ($currentStep == 1) {
            $zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
            $zip->setArchiveComment("Microweber backup of the userfiles folder and db.
                \nThe Microweber version at the time of backup was " . MW_VERSION . "
                \nCreated on " . date('l jS \of F Y h:i:s A'));
        } else {
            $zip->open($zipFileName['filepath']);
        }

        if (!empty($this->files)) {
            $filesForZip = array_merge($filesForZip, $this->files);
        }

        if ($this->backupMedia) {
            $userFiles = $this->_getUserFilesPaths();
            $filesForZip = array_merge($filesForZip, $userFiles);
        }

        if ($this->backupModules) {
            $userFilesModules = $this->_getUserFilesModulesPaths();
            $filesForZip = array_merge($filesForZip, $userFilesModules);
        }

        if ($this->backupTemplates) {
            $userFilesTemplates = $this->_getUserFilesTemplatesPaths();
            $filesForZip = array_merge($filesForZip, $userFilesTemplates);
        }

        if ($this->backupOnlyTemplate) {
            $currentTemplateFiles = $this->_getTempalteFilesPaths();
            $filesForZip = array_merge($filesForZip, $currentTemplateFiles);
        }

        $totalFilesForZip = sizeof($filesForZip);
        
        // For single-step backups, process all files at once
        if ($totalSteps == 1) {
            $filesBatch = array();
            $filesBatch[0] = $filesForZip;
        } else {
        // For multi-step backups, we need to save the file list in the first step 
        // and retrieve it in subsequent steps to ensure consistency

        $filesForBatchCacheKey = 'files_for_batch_' . SessionStepper::$sessionId;
        
        // In the first step, gather all files and store them in cache
        if ($currentStep == 1) {
            // Store all files in a cache
            cache_save($filesForZip, $filesForBatchCacheKey, $this->_cacheGroupName);
            $this->logger->setLogInfo('Collected ' . count($filesForZip) . ' files on first step');
        } else {
            // For subsequent steps, retrieve files from cache
            $cachedFiles = cache_get($filesForBatchCacheKey, $this->_cacheGroupName);
            if ($cachedFiles !== false) {
                $filesForZip = $cachedFiles;
                $this->logger->setLogInfo('Retrieved ' . count($filesForZip) . ' files from cache on step ' . $currentStep);
            }
        }
        
        $totalFilesForZip = count($filesForZip);
        
        // Calculate how many files to process per step
        $totalFilesForBatch = (int) ceil($totalFilesForZip / $totalSteps);
        $totalFilesForBatch = max(1, $totalFilesForBatch); // Ensure at least 1 file per batch
        
        $this->logger->setLogInfo('Processing batch with ' . $totalFilesForBatch . ' files per step');
        
        // Create file batches
        $filesBatch = array_chunk($filesForZip, $totalFilesForBatch);
        
        // Make sure there's a batch for each step (even if empty)
        while (count($filesBatch) < $totalSteps) {
            $filesBatch[] = [];
        }
        }

        $selectBatch = ($currentStep - 1);

        // If the current batch doesn't exist, we're done
        if (!isset($filesBatch[$selectBatch])) {
            SessionStepper::finish();
        }

        // For single-step operations, process all files
        if ($totalSteps == 1) {
            $this->logger->setLogInfo('Processing single-step backup with ' . count($filesBatch[0]) . ' files');
            
            // Process all files
            foreach ($filesBatch[0] as $file) {
                $ext = get_file_extension($file['filepath']);
                $file['filename'] = str_replace('\\', '/', $file['filename']);
                $file['filepath'] = str_replace('\\', '/', $file['filepath']);

                if ($ext == 'css') {
                    $this->logger->setLogInfo('Archiving CSS file <b>' . $file['filename'] . '</b>');
                    $csscont = file_get_contents($file['filepath']);
                    $csscont = app()->url_manager->replace_site_url($csscont);
                    $zip->addFromString($file['filename'], $csscont);
                } else {
                    $this->logger->setLogInfo('Archiving file <b>' . $file['filename'] . '</b>');
                    $zip->addFile($file['filepath'], $file['filename']);
                }
            }
            
            $this->logger->setLogInfo('Finishing single-step backup');
            $this->_finishUp();

            if (method_exists($zip, 'setCompressionIndex')) {
                $zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
            }

            $zip->close();
            
            // Add an empty file to make sure the zip is not empty
            if (count($filesBatch[0]) === 0) {
                $zip = new \ZipArchive();
                $zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
                $zip->addFromString('README.txt', 'Microweber backup file');
                $zip->close();
            }

            // VALIDATE ZIP
            $validateZip = new \ZipArchive();
            $validateZipOpen = $validateZip->open($zipFileName['filepath'], \ZipArchive::CHECKCONS);
            if ($validateZipOpen !== true) {
                $this->logger->setLogInfo('Error validating zip file: ' . $zipFileName['filepath']);
                return $this->getExportLog();
            }
            
            $this->logger->setLogInfo('Successfully created zip file at: ' . $zipFileName['filepath']);

            // Return with data structure for consistency
            return [
                'success' => 'Items are exported',
                'data' => $zipFileName
            ];
        }
        
        // For finished multi-step operations - this check must come after the single step check
        if (SessionStepper::isFinished()) {
            $this->logger->setLogInfo('Finishing multi-step backup. Current step: ' . SessionStepper::currentStep() . ' of ' . SessionStepper::totalSteps());
            
            // Close the zip file properly
            $this->_finishUp();

            if (method_exists($zip, 'setCompressionIndex')) {
                $zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
            }

            $zip->close();

            // Add an empty file to ensure the zip is not empty
            if (!is_file($zipFileName['filepath']) || filesize($zipFileName['filepath']) < 100) {
                $zip = new \ZipArchive();
                $zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
                $zip->addFromString('README.txt', 'Microweber backup file');
                $zip->close();
                $this->logger->setLogInfo('Added empty README.txt file to ensure zip is not empty');
            }

            // VALIDATE ZIP
            $validateZip = new \ZipArchive();
            $validateZipOpen = $validateZip->open($zipFileName['filepath'], \ZipArchive::CHECKCONS);
            if ($validateZipOpen !== true) {
                $this->logger->setLogInfo('Error validating zip file: ' . $zipFileName['filepath']);
                return $this->getExportLog();
            }
            
            $this->logger->setLogInfo('Successfully created zip file at: ' . $zipFileName['filepath']);

            // Return with data structure for consistency
            return [
                'success' => 'Items are exported',
                'data' => $zipFileName
            ];
        }

        foreach ($filesBatch[$selectBatch] as $file) {
            $ext = get_file_extension($file['filepath']);
            $file['filename'] = str_replace('\\', '/', $file['filename']);
            $file['filepath'] = str_replace('\\', '/', $file['filepath']);

            if ($ext == 'css') {
                $this->logger->setLogInfo('Archiving CSS file <b>' . $file['filename'] . '</b>');
                $csscont = file_get_contents($file['filepath']);
                $csscont = app()->url_manager->replace_site_url($csscont);
                $zip->addFromString($file['filename'], $csscont);

            } else {
                $this->logger->setLogInfo('Archiving file <b>' . $file['filename'] . '</b>');
                $zip->addFile($file['filepath'], $file['filename']);
            }
        }

        if (method_exists($zip, 'setCompressionIndex')) {
            $zip->setCompressionIndex(0, \ZipArchive::CM_STORE);
        }

        $zip->close();

        return $this->getExportLog();
    }

    public function getExportLog()
    {
        $log = array();
        $log['current_step'] = SessionStepper::currentStep();
        $log['total_steps'] = SessionStepper::totalSteps();
        $log['percentage'] = SessionStepper::percentage();
        $log['session_id'] = SessionStepper::$sessionId;

        // For single-step operations, always provide the filepath
        if (SessionStepper::totalSteps() == 1) {
            $zipFileName = $this->_getZipFileName();
            $log['data'] = [
                'filepath' => $zipFileName['filepath'],
                'filename' => $zipFileName['filename'],
                'download' => $zipFileName['downloadUrl']
            ];
            $log['done'] = true;
            $log['success'] = 'Items are exported';
        }
        // For multi-step operations, only provide filepath on the last step
        else if (SessionStepper::isFinished()) {
            $zipFileName = $this->_getZipFileName();
            $log['data'] = [
                'filepath' => $zipFileName['filepath'],
                'filename' => $zipFileName['filename'],
                'download' => $zipFileName['downloadUrl']
            ];
            $log['done'] = true;
        } else {
            $log['data'] = false;
        }

        if (SessionStepper::isFirstStep()) {
            $log['started'] = true;
        }

        return $log;
    }


    protected function _getTempalteFilesPaths()
    {
        $templatesFilesReady = array();

        $userFilesPathTemplates = userfiles_path() . DIRECTORY_SEPARATOR . 'templates';
        $templateDir = $userFilesPathTemplates . DIRECTORY_SEPARATOR . $this->backupOnlyTemplate;
        if (!is_dir($templateDir)) {
            return [];
        }

        $templateFiles = $this->_getDirContents($templateDir);

        foreach ($templateFiles as $filePath) {

            $dataFile = str_replace(userfiles_path() . DIRECTORY_SEPARATOR, false, $filePath);

            if ((strpos($dataFile, '.git') !== false) ||
                (strpos($dataFile, '.zip') !== false) ||
                (strpos($dataFile, '.DS_Store') !== false) ||
                (strpos($dataFile, '.json') !== false) ||
                (strpos($dataFile, '\\gulp\\') !== false) ||
                (strpos($dataFile, '.gitignore') !== false) ||
                (strpos($dataFile, '.sh') !== false)) {
                continue;
            }

            $dataFile = normalize_path($dataFile, false);
            $filePath = normalize_path($filePath, false);

            // make files from template to the index on zip
            $dataFile = str_replace('templates\\' . $this->backupOnlyTemplate . '\\', '', $dataFile);

            $templatesFilesReady[] = array(
                'filename' => $dataFile,
                'filepath' => $filePath
            );
        }

        return $templatesFilesReady;
    }

    protected function _getUserFilesTemplatesPaths()
    {
        $allTemplatesFiles = array();
        $templatesFilesReady = array();

        $userFilesPathTemplates = userfiles_path() . DIRECTORY_SEPARATOR . 'templates';

        foreach ($this->backupTemplates as $template) {
            $templateDir = $userFilesPathTemplates . DIRECTORY_SEPARATOR . $template;
            $templateFiles = $this->_getDirContents($templateDir);

            $allTemplatesFiles = array_merge($allTemplatesFiles, $templateFiles);
        }

        foreach ($allTemplatesFiles as $filePath) {

            $dataFile = str_replace(userfiles_path() . DIRECTORY_SEPARATOR, false, $filePath);

            if (strpos($dataFile, '.git') !== false) {
                continue;
            }

            $dataFile = normalize_path($dataFile, false);
            $filePath = normalize_path($filePath, false);

            $templatesFilesReady[] = array(
                'filename' => $dataFile,
                'filepath' => $filePath
            );
        }

        return $templatesFilesReady;
    }

    protected function _getUserFilesModulesPaths()
    {

        $allModulesFiles = array();
        $modulesFilesReady = array();

   //     $userFilesPathModules = userfiles_path() . DIRECTORY_SEPARATOR . 'modules';
        $userFilesPathModules = modules_path();

        foreach ($this->backupModules as $module) {
            $moduleDir = $userFilesPathModules .  $module;
            $moduleFiles = $this->_getDirContents($moduleDir);

            $allModulesFiles = array_merge($allModulesFiles, $moduleFiles);
        }

        foreach ($allModulesFiles as $filePath) {

           // $dataFile = str_replace(userfiles_path() . DIRECTORY_SEPARATOR, false, $filePath);
            $dataFile = str_replace(modules_path(), 'Modules/', $filePath);

            $dataFile = normalize_path($dataFile, false);
            $filePath = normalize_path($filePath, false);

            $modulesFilesReady[] = array(
                'filename' => $dataFile,
                'filepath' => $filePath
            );
        }

        return $modulesFilesReady;
    }

    protected function _getUserFilesPaths()
    {
        $userFilesPath = userfiles_path();
        $userFilesScanned = $this->_getDirContents($userFilesPath);

        $userFilesReady = array();

        foreach ($userFilesScanned as $filePath) {
            $dataFile = str_replace($userFilesPath, 'userfiles', $filePath);
            $userFilesReady[] = array(
                'filename' => $dataFile,
                'filepath' => $filePath
            );
        }

        return $userFilesReady;
    }

    protected function _getDirContents($path)
    {
        if (!is_dir($path)) {
            return array();
        }

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, 
            \RecursiveDirectoryIterator::SKIP_DOTS));

        $files = array();
        foreach ($rii as $file) {
            if (!$file->isDir()) {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }

    /**
     * Clear all cache
     */
    protected function _finishUp()
    {
        SessionStepper::clearSteps();

        $this->logger->setLogInfo('Done!');
    }
}
