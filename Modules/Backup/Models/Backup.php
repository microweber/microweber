<?php

namespace Modules\Backup\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Export\SessionStepper;
use Modules\Backup\Support\GenerateBackup;
use Modules\Backup\Support\Restore;

class Backup extends Model
{
    protected $fillable = [
        'filename',
        'filepath',
        'size'
    ];

    public function backup_location()
    {
        $backupPath = backup_location();

        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        return $backupPath;
    }

    public static function refreshFromDisk()
    {
        $model = new static;
        $backupLocation = $model->backup_location();

        // Clear existing records
        static::truncate();

        if (is_dir($backupLocation)) {
            $files = scandir($backupLocation);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $filePath = $backupLocation . DIRECTORY_SEPARATOR . $file;
                    if (is_file($filePath)) {
                        static::create([
                            'filename' => $file,
                            'filepath' => $filePath,
                            'size' => filesize($filePath)
                        ]);
                    }
                }
            }
        }
    }

    public function generateBackup($data = [])
    {
        $backup = new GenerateBackup();

        // Set session ID for progress tracking
        if (!isset($data['session_id'])) {
            $data['session_id'] = SessionStepper::generateSessionId();
        }
        $backup->setSessionId($data['session_id']);

        if (isset($data['format'])) {
            $backup->type = $data['format'];
        }

        if (isset($data['exclude_tables']) && !empty($data['exclude_tables'])) {
            $backup->excludeTables = $data['exclude_tables'];
        }

        if (isset($data['exclude_folders']) && !empty($data['exclude_folders'])) {
            $backup->excludeFolders = $data['exclude_folders'];
        }

        // Set export data with proper structure
        if (isset($data['tables'])) {
            $backup->setExportData('tables', $data['tables']);
        }

        if (isset($data['content_ids'])) {
            $backup->setExportData('contentIds', $data['content_ids']);
        }

        if (isset($data['category_ids'])) {
            $backup->setExportData('categoryIds', $data['category_ids']);
        }

        // Initialize progress tracking
        SessionStepper::setSessionId($data['session_id']);

        $result = $backup->start();

        // Refresh backups from disk after creation
        if (!isset($result['error'])) {
            static::refreshFromDisk();
        }

        return $result;
    }

    public function restore($file)
    {
        $restore = new Restore();
        $restore->setFile($file);
        $restore->setOvewriteById(true);
        $restore->setToDeleteOldContent(false);

        return $restore->start();
    }

    public function delete()
    {
        if ($this->filepath && file_exists($this->filepath)) {
            unlink($this->filepath);
        }

        return parent::delete();
    }
}
