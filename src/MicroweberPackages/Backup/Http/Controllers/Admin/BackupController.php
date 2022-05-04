<?php

namespace MicroweberPackages\Backup\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MicroweberPackages\Backup\Backup;
use MicroweberPackages\Backup\Export;
use MicroweberPackages\Backup\GenerateBackup;
use MicroweberPackages\Backup\Restore;
use MicroweberPackages\Export\SessionStepper;

class BackupController
{
    public function get()
    {
        $backupLocation = backup_location();

        $backupFiles = [];


        $files = preg_grep('~\.(sql|zip|json|xml|xlsx|csv|xls)$~', scandir($backupLocation));
        if ($files) {
            foreach ($files as $file) {
                $backupFiles[] = normalize_path($backupLocation. $file,false);
            }
        }

        if (! empty($backupFiles)) {
            usort($backupFiles, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });
        }
        $backups = array();
        if (! empty($backupFiles)) {
            foreach ($backupFiles as $file) {

                if (is_file($file)) {
                    $mtime = filemtime($file);

                    $backup = array();
                    $backup['filename'] = basename($file);
                    $backup['date'] = date('F d Y', $mtime);
                    $backup['time'] = str_replace('_', ':', date('H:i:s', $mtime));
                    $backup['size'] = filesize($file);

                    $backups[] = $backup;
                }
            }
        }

        return $backups;
    }

    public function restore(Request $request)
    {
        $fileId = $request->get('id', false);
        $step = (int) $request->get('step', false);

        $restore = new Restore();
        $restore->setSessionId($request->get('session_id'));

        if (!$fileId) {
            return array('error' => 'You have not provided a file to import.');
        }

        $fileId = str_replace('..', '', $fileId);

        $backupLocation = backup_location();
        $filePath = $backupLocation . $fileId;

        if (!is_file($filePath)) {
            return array('error' => 'You have not provided a existing backup to import.');
        } else {

            $restore->setFile($filePath);
            $importLog = $restore->start();

            return json_encode($importLog, JSON_PRETTY_PRINT);
        }

        return $query;
    }

    public function download(Request $request)
    {
        $fileId = $request->get('file');

        $fileId = str_replace('..', '', $fileId);

        // Check if the file has needed args
        if (! $fileId) {
            return array(
                'error' => 'You have not provided filename to download.'
            );
        }

        $backupLocation = backup_location();

        // Generate filename and set error variables
        $filename = $backupLocation . $fileId;
        $filename = str_replace('..', '', $filename);

        $allowedExt = ['json','zip','xlsx'];
        $fileExt = get_file_extension($filename);

        if (!in_array($fileExt,$allowedExt)) {
            return array(
                'error' => 'Invalid file'
            );
        }

        if (! is_file($filename)) {
            return array(
                'error' => 'You have not provided a existing filename to download.'
            );
        }

        // Check if the file exist.
        if (file_exists($filename)) {

            // Add headers
            header('Cache-Control: public');
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . basename($filename));
            header('Content-Length: ' . filesize($filename));

            // Read file
            $this->_readfileChunked($filename);
        } else {
            return array(
                'error' => 'File does not exist.'
            );
        }
    }

    public function upload(Request $request)
    {
        $src = $request->post('src', false);

        if (!$src) {
            return array(
                'error' => 'You have not provided src to the file.'
            );
        }

        $src = str_replace('..','',$src);

        $checkFile = url2dir(trim($src));
        $checkFile = normalize_path($checkFile, false);
        $backupLocation = backup_location();

        $checkStartsWith = Str::startsWith($checkFile, userfiles_path());
        if (!$checkStartsWith) {
            return array(
                'error' => 'Invalid path.'
            );
        }

        if (is_file($checkFile)) {
            $file = basename($checkFile);

            if (copy($checkFile, $backupLocation . $file)) {
                @unlink($checkFile);

                return array(
                    'success' => "$file was moved!"
                );
            } else {
                return array(
                    'error' => 'Error moving uploaded file!'
                );
            }
        } else {
//			return array(
//				'error' => 'Uploaded file is not found!'
//			);
        }
    }

    public function start(Request $request)
    {
        $backup = new GenerateBackup();
        $backup->setSessionId($request->get('session_id'));

        if ($request->get('type') == 'custom') {

            $includeMedia = false;
            if ($request->get('include_media', false) == 1) {
                $includeMedia = true;
            }

            $backup->setAllowSkipTables(false);
            $backup->setExportTables($request->get('include_tables', []));
            $backup->setExportMedia($includeMedia);
            $backup->setExportModules($request->get('include_modules', []));
            $backup->setExportTemplates($request->get('include_templates', []));
        } else {
            $backup->setType('json');
            $backup->setAllowSkipTables(true); // skip sensitive tables
            $backup->setExportAllData(true);
            $backup->setExportMedia(true);
            $backup->setExportWithZip(true);
        }

        return $backup->start();
    }

    public function generateSessionId()
    {
        rmdir_recursive(backup_cache_location());
        clearcache();

        return ['session_id'=>SessionStepper::generateSessionId(20)];
    }

    public function delete(Request $request)
    {
        $fileId = $request->get('id');

        // Check if the file has needed args
        if (! $fileId) {
            return array(
                'error' => 'You have not provided filename to be deleted.'
            );
        }

        $backupLocation = backup_location();
        $filename = $backupLocation . $fileId;

        $fileId = str_replace('..', '', $fileId);
        $filename = str_replace('..', '', $filename);

        if (is_file($filename)) {
            unlink($filename);
            return array(
                'success' => "$fileId was deleted!"
            );
        } else {
            $filename = $backupLocation . $fileId . '.sql';
            if (is_file($filename)) {
                unlink($filename);

                return array(
                    'success' => "$fileId was deleted!"
                );
            }
        }
    }

    private function _readfileChunked($filename, $retbytes = true)
    {
        $filename = str_replace('..', '', $filename);

        $chunkSize = 1024 * 1024;
        $buffer = '';
        $cnt = 0;
        $handle = fopen($filename, 'rb');
        if ($handle === false) {
            return false;
        }

        while (! feof($handle)) {
            $buffer = fread($handle, $chunkSize);
            echo $buffer;
            if (ob_get_level() > 0) {
                ob_flush();
                flush();
            }
            if ($retbytes) {
                $cnt += strlen($buffer);
            }
        }
        $status = fclose($handle);
        if ($retbytes && $status) {
            return $cnt; // return num. bytes delivered like readfile() does.
        }

        return $status;
    }

}



class BackupV2Logger {

    public function log($log) {
        echo $log . '<br />';
    }

}

