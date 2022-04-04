<?php

namespace MicroweberPackages\Export\Formats;

use MicroweberPackages\Backup\Loggers\BackupLogger;
use MicroweberPackages\Backup\Loggers\DefaultLogger;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\Traits\ExportGetSet;
use function Symfony\Component\String\s;

class ZipBatchExport extends DefaultExport
{
    use ExportGetSet;

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
        $cacheKey = 'export_zipfilename_' . SessionStepper::$sessionId;
        $zipFileName = cache_get($cacheKey, $this->_cacheGroupName);

        if (empty($zipFileName)) {

            $customZipFileName = false;
            if ($this->exportOnlyTemplate) {
                $customZipFileName = 'export_' . str_slug($this->exportOnlyTemplate) . date("Y-m-d-his");
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
        $zip->open($zipFileName['filepath'], \ZipArchive::CREATE);
        $zip->setArchiveComment("Microweber backup of the userfiles folder and db.
                \nThe Microweber version at the time of backup was " . MW_VERSION . "
                \nCreated on " . date('l jS \of F Y h:i:s A'));

        if (!empty($this->files)) {
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

        if ($this->exportModules) {
            $userFilesModules = $this->_getUserFilesModulesPaths();
            $filesForZip = array_merge($filesForZip, $userFilesModules);
        }

        if ($this->exportTemplates) {
            $userFilesTemplates = $this->_getUserFilesTemplatesPaths();
            $filesForZip = array_merge($filesForZip, $userFilesTemplates);
        }

        if ($this->exportOnlyTemplate) {
            $currentTemplateFiles = $this->_getTempalteFilesPaths();
            $filesForZip = array_merge($filesForZip, $currentTemplateFiles);
        }

        $totalFilesForZip = sizeof($filesForZip);
        $totalFilesForBatch = (int) ceil($totalFilesForZip / $totalSteps);

        if ($totalFilesForBatch > 0) {
            $filesBatch = array_chunk($filesForZip, $totalFilesForBatch);
        } else {
            $filesBatch = array();
            $filesBatch[] = $filesForZip;
        }

        $selectBatch = ($currentStep - 1);

        if (!isset($filesBatch[$selectBatch])) {
            if(is_object($zip)) {
                $zip->close();
            }
           SessionStepper::finish();
        }

        if (SessionStepper::isFinished()) {
            $this->logger->setLogInfo('No files in batch for current step.');
            $this->_finishUp();
            if(is_object($zip)) {
                $zip->close();
            }
            return $zipFileName;
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
        $log['precentage'] = SessionStepper::percentage();
        $log['session_id'] = SessionStepper::$sessionId;

        $log['data'] = false;

        if (SessionStepper::isFirstStep()) {
            $log['started'] = true;
        }

        if (SessionStepper::isFinished()) {
            $log['done'] = true;
        }

        return $log;
    }


    protected function _getTempalteFilesPaths()
    {
        $templatesFilesReady = array();

        $userFilesPathTemplates = userfiles_path() . DIRECTORY_SEPARATOR . 'templates';
        $templateDir = $userFilesPathTemplates . DIRECTORY_SEPARATOR . $this->exportOnlyTemplate;
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
            $dataFile = str_replace('templates\\' . $this->exportOnlyTemplate . '\\', '', $dataFile);

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

        foreach ($this->exportTemplates as $template) {
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

        $userFilesPathModules = userfiles_path() . DIRECTORY_SEPARATOR . 'modules';

        foreach ($this->exportModules as $module) {
            $moduleDir = $userFilesPathModules . DIRECTORY_SEPARATOR . $module;
            $moduleFiles = $this->_getDirContents($moduleDir);

            $allModulesFiles = array_merge($allModulesFiles, $moduleFiles);
        }

        foreach ($allModulesFiles as $filePath) {

            $dataFile = str_replace(userfiles_path() . DIRECTORY_SEPARATOR, false, $filePath);

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

        foreach ($userFiles as $filePath) {

            $dataFile = str_replace(userfiles_path() . DIRECTORY_SEPARATOR, false, $filePath);

            $dataFile = normalize_path($dataFile, false);
            $filePath = normalize_path($filePath, false);

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

        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

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
