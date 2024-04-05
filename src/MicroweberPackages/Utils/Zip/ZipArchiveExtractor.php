<?php

namespace MicroweberPackages\Utils\Zip;

use Illuminate\Support\Str;
use MicroweberPackages\Utils\System\Files;

class ZipArchiveExtractor
{
    public $allowedFilesCheck = false;
    public $zipInstance = false;
    public $logger = false;

    public function __construct($zipFile)
    {
        $this->zipInstance = new \ZipArchive();
        $this->zipInstance->open($zipFile);
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function setAllowedFilesCheck(bool $check)
    {
        $this->allowedFilesCheck = $check;
    }

    public function extractTo($path)
    {

        $selectedFilesForUnzip = [];
        for ($i = 0; $i < $this->zipInstance->numFiles; $i++) {
            $stat = $this->zipInstance->statIndex($i);

            $zipFileBasename = normalize_path($stat['name'], false);

            $canIUnzipTheFile = false;

            $isFilenameInvalid = false;
            if (strpos($zipFileBasename, '..') !== false) {
                $isFilenameInvalid = true;
            }
            //also :*?"<>| are not allowed in filenames
            if (Str::contains($zipFileBasename, ['*', ':', '?', '"', '<', '>', '|'])) {
                $isFilenameInvalid = true;
            }
            if (!$isFilenameInvalid) {
                if ($this->allowedFilesCheck) {
                    $filesUtils = new Files();
                    $isAllowed = $filesUtils->is_allowed_file($zipFileBasename);
                    if ($isAllowed) {
                        $canIUnzipTheFile = true;
                        $selectedFilesForUnzip[] = $zipFileBasename;
                        if ($this->logger) {
                            $this->logger::setLogInfo('Unzipping queue ' . $zipFileBasename . '...');
                        }
                    }
                } else {
                    $canIUnzipTheFile = true;
                    $selectedFilesForUnzip[] = $zipFileBasename;
                    if ($this->logger) {
                        $this->logger::setLogInfo('Unzipping queue ' . $zipFileBasename . '...');
                    }
                }
            }
            if (!$isFilenameInvalid and $canIUnzipTheFile) {
                $targetFileSave = $path . $zipFileBasename;

                if (!is_dir(dirname($targetFileSave))) {
                    mkdir_recursive(dirname($targetFileSave));
                }

                @file_put_contents($targetFileSave, $this->zipInstance->getFromIndex($i));
            }
        }

        if (empty($selectedFilesForUnzip)) {
            if ($this->logger) {
                $this->logger::setLogInfo('The zip file has no files.');
            }
            return false;
        }

        $this->zipInstance->close();

        return true;
    }
}
