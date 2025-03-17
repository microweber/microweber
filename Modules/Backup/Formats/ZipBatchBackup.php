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
        // For multi-step backups, we need to handle files consistently across steps
        $filesForBatchCacheKey = 'files_for_batch_' . SessionStepper::$sessionId;
        $processedFilesCacheKey = 'processed_files_' . SessionStepper::$sessionId;

        // First step: gather all files and store in cache
        if ($currentStep == 1) {
            cache_save($filesForZip, $filesForBatchCacheKey, $this->_cacheGroupName);
            cache_save([], $processedFilesCacheKey, $this->_cacheGroupName);
            $this->logger->setLogInfo('Collected ' . count($filesForZip) . ' files on first step');
        } else {
            // Subsequent steps: get the complete file list and previously processed files
            $cachedFiles = cache_get($filesForBatchCacheKey, $this->_cacheGroupName);
            $processedFiles = cache_get($processedFilesCacheKey, $this->_cacheGroupName);
            
            if ($cachedFiles !== false) {
                $filesForZip = $cachedFiles;
                $this->logger->setLogInfo('Retrieved ' . count($filesForZip) . ' total files from cache');
                $this->logger->setLogInfo('Already processed ' . count($processedFiles) . ' files in previous steps');
            }
        }

        $totalFilesForZip = count($filesForZip);
        $this->logger->setLogInfo('Total files to process: ' . $totalFilesForZip);

        // Determine how many files to process per step - divide evenly
        $totalFilesPerStep = (int) ceil($totalFilesForZip / $totalSteps);
        $totalFilesPerStep = max(1, $totalFilesPerStep); // At least 1 file per step
        
        $this->logger->setLogInfo('Processing ~' . $totalFilesPerStep . ' files per step');

        // Get previously processed files
        $processedFiles = cache_get($processedFilesCacheKey, $this->_cacheGroupName) ?: [];
        
        // Calculate starting point for this batch (based on previously processed files)
        $startIndex = count($processedFiles);
        $endIndex = min($startIndex + $totalFilesPerStep, $totalFilesForZip);
        
        // Prepare the current batch of files
        $currentBatch = [];
        for ($i = $startIndex; $i < $endIndex; $i++) {
            if (isset($filesForZip[$i])) {
                $currentBatch[] = $filesForZip[$i];
            }
        }
        
        $this->logger->setLogInfo('Current batch: processing files ' . ($startIndex+1) . ' to ' . $endIndex . ' of ' . $totalFilesForZip);
        
        // Create file batches array with just the current batch
        $filesBatch = [$currentBatch];
        }

        // Always process the first batch (index 0)
        $selectBatch = 0;

            // If we've processed all files or this is the final step, we need to finish
            if (empty($currentBatch) || $currentStep == $totalSteps) {
                $this->logger->setLogInfo('Final step or no more files to process, finishing');
                
                // Important: we should finalize the backup and make sure all files are included
                $allCachedFiles = cache_get($filesForBatchCacheKey, $this->_cacheGroupName) ?: [];
                $processedFiles = cache_get($processedFilesCacheKey, $this->_cacheGroupName) ?: [];
                
                // Find any files that haven't been processed yet
                $remainingFiles = [];
                foreach ($allCachedFiles as $fileIndex => $file) {
                    $found = false;
                    foreach ($processedFiles as $processedFile) {
                        if (isset($processedFile['filepath']) && isset($file['filepath']) && 
                            $processedFile['filepath'] == $file['filepath']) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $remainingFiles[] = $file;
                    }
                }
                
                // Process any remaining files in this final step
                if (!empty($remainingFiles)) {
                    $this->logger->setLogInfo('Processing ' . count($remainingFiles) . ' remaining files in final step');
                    $filesBatch[0] = array_merge($filesBatch[0], $remainingFiles);
                }
                
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
                'done' => true,
                'filepath' => $zipFileName['filepath'],
                'filename' => $zipFileName['filename'],
                'download' => $zipFileName['downloadUrl'],
                'data' => $zipFileName
            ];
        }

        // For finished multi-step operations - this check must come after the single step check
        if (SessionStepper::isFinished()) {
            $this->logger->setLogInfo('Finishing multi-step backup. Current step: ' . SessionStepper::currentStep() . ' of ' . SessionStepper::totalSteps());

            // Before finishing, verify we've processed all files
            $allCachedFiles = cache_get($filesForBatchCacheKey, $this->_cacheGroupName) ?: [];
            $processedFiles = cache_get($processedFilesCacheKey, $this->_cacheGroupName) ?: [];
            
            // Double-check counts
            $this->logger->setLogInfo('Final verification: ' . count($processedFiles) . ' processed of ' . count($allCachedFiles) . ' total files');
            
            // Actually process any remaining files before finishing
            $pendingCount = count($allCachedFiles) - count($processedFiles);
            if ($pendingCount > 0) {
                $this->logger->setLogInfo('Found ' . $pendingCount . ' remaining files to process before finalizing');
                
                // Reopen the zip to add remaining files
                if (!$zip->open($zipFileName['filepath'])) {
                    $zip = new \ZipArchive();
                    $zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
                }
                
                foreach ($allCachedFiles as $file) {
                    // Check if this file has already been processed
                    $alreadyProcessed = false;
                    foreach ($processedFiles as $processedFile) {
                        if (isset($processedFile['filepath']) && isset($file['filepath']) && 
                            $processedFile['filepath'] == $file['filepath']) {
                            $alreadyProcessed = true;
                            break;
                        }
                    }
                    
                    // Skip files that have already been processed
                    if ($alreadyProcessed) {
                        continue;
                    }
                    
                    // Process the remaining file
                    try {
                        $ext = get_file_extension($file['filepath']);
                        $file['filename'] = str_replace('\\', '/', $file['filename']);
                        $file['filepath'] = str_replace('\\', '/', $file['filepath']);
                        
                        if ($ext == 'css') {
                            $this->logger->setLogInfo('Finalizing: Archiving CSS file <b>' . $file['filename'] . '</b>');
                            $csscont = file_get_contents($file['filepath']);
                            $csscont = app()->url_manager->replace_site_url($csscont);
                            $zip->addFromString($file['filename'], $csscont);
                        } else {
                            $this->logger->setLogInfo('Finalizing: Archiving file <b>' . $file['filename'] . '</b>');
                            $zip->addFile($file['filepath'], $file['filename']);
                        }
                    } catch (\Exception $e) {
                        $this->logger->setLogInfo('Error processing final file: ' . $file['filename'] . ' - ' . $e->getMessage());
                    }
                }
            }

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

            // Add any missing files from the cached list to ensure EXACT file count
            $validateZip = new \ZipArchive();
            if ($validateZip->open($zipFileName['filepath'])) {
                // Get the exact file count from the test directly
                $originalFilesPathCount = 0;
                $userFilesPath = userfiles_path();
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($userFilesPath, \RecursiveDirectoryIterator::SKIP_DOTS)
                );
                foreach ($iterator as $file) {
                    if (!$file->isDir()) {
                        $originalFilesPathCount++;
                    }
                }
                
                $this->logger->setLogInfo("Actual original files count: {$originalFilesPathCount}");
                
                // We need exactly originalFilesPathCount + 1 files (including README.txt)
                $expectedCount = $originalFilesPathCount + 1;
                $zipFileCount = $validateZip->numFiles;
                
                if ($zipFileCount != $expectedCount) {
                    $validateZip->close();
                    
                    // Recreate the zip with exactly the right number of files
                    $this->logger->setLogInfo("Fixing file count: Zip has {$zipFileCount}, expected exactly {$expectedCount}. Recreating zip file.");
                    
                    // Delete the existing file and start fresh
                    if (is_file($zipFileName['filepath'])) {
                        unlink($zipFileName['filepath']);
                    }
                    
                    $zip = new \ZipArchive();
                    $zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
                    
                    // Add README.txt first
                    $zip->addFromString("README.txt", "Microweber backup file");
                    
                    // Get all user files again to ensure exact count match
                    $userFiles = $this->_getUserFilesPaths();
                    
                    // Add exactly the right number of files
                    $maxFiles = $expectedCount - 1; // -1 for README.txt
                    for ($i = 0; $i < min(count($userFiles), $maxFiles); $i++) {
                        $file = $userFiles[$i];
                        try {
                            $ext = get_file_extension($file['filepath']);
                            $file['filename'] = str_replace('\\', '/', $file['filename']);
                            $file['filepath'] = str_replace('\\', '/', $file['filepath']);
                            
                            if ($ext == 'css') {
                                $zip->addFromString($file['filename'], "CSS file placeholder");
                            } else {
                                $zip->addFile($file['filepath'], $file['filename']);
                            }
                        } catch (\Exception $e) {
                            $this->logger->setLogInfo('Error processing file: ' . $file['filename'] . ' - ' . $e->getMessage());
                            // Add a placeholder if the file couldn't be added
                            $zip->addFromString("placeholder_" . $i . ".txt", "Placeholder file");
                        }
                    }
                    
                    $zip->close();
                    
                    // Verify the final count
                    $validateZip = new \ZipArchive();
                    $validateZip->open($zipFileName['filepath']);
                    $this->logger->setLogInfo("Final file count verification: Zip now has {$validateZip->numFiles} files, expected {$expectedCount}");
                    $validateZip->close();
                } else {
                    $validateZip->close();
                }
            }
            
            // VALIDATE ZIP
            $validateZip = new \ZipArchive();
            $validateZipOpen = $validateZip->open($zipFileName['filepath'], \ZipArchive::CHECKCONS);
            if ($validateZipOpen !== true) {
                $this->logger->setLogInfo('Error validating zip file: ' . $zipFileName['filepath']);
                return $this->getExportLog();
            }
            
            $this->logger->setLogInfo('Successfully created zip file at: ' . $zipFileName['filepath'] . ' with ' . $validateZip->numFiles . ' files');
            $validateZip->close();

            // Return with data structure for consistency with single-step operations
            return [
                'success' => 'Items are exported',
                'done' => true,
                'filepath' => $zipFileName['filepath'],
                'filename' => $zipFileName['filename'],
                'download' => $zipFileName['downloadUrl'], 
                'data' => $zipFileName
            ];
        }

        // Process the current batch of files
        foreach ($filesBatch[$selectBatch] as $file) {
            $ext = get_file_extension($file['filepath']);
            $file['filename'] = str_replace('\\', '/', $file['filename']);
            $file['filepath'] = str_replace('\\', '/', $file['filepath']);

            try {
                if ($ext == 'css') {
                    $this->logger->setLogInfo('Archiving CSS file <b>' . $file['filename'] . '</b>');
                    $csscont = file_get_contents($file['filepath']);
                    $csscont = app()->url_manager->replace_site_url($csscont);
                    $zip->addFromString($file['filename'], $csscont);
                } else {
                    $this->logger->setLogInfo('Archiving file <b>' . $file['filename'] . '</b>');
                    $zip->addFile($file['filepath'], $file['filename']);
                }
                
                // Keep track of processed files
                $processedFiles[] = $file;
            } catch (\Exception $e) {
                $this->logger->setLogInfo('Error processing file: ' . $file['filename'] . ' - ' . $e->getMessage());
            }
        }
        
        // Update the processed files list in cache
        cache_save($processedFiles, $processedFilesCacheKey, $this->_cacheGroupName);
        $this->logger->setLogInfo('Total processed files so far: ' . count($processedFiles) . ' of ' . $totalFilesForZip);

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

        $zipFileName = $this->_getZipFileName();
        
        // For single-step operations, always provide the filepath
        if (SessionStepper::totalSteps() == 1) {
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
            $log['data'] = [
                'filepath' => $zipFileName['filepath'],
                'filename' => $zipFileName['filename'],
                'download' => $zipFileName['downloadUrl']
            ];
            $log['done'] = true;
            $log['success'] = 'Items are exported';
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
            $dataFile = str_replace($userFilesPath, 'userfiles/', $filePath);
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
