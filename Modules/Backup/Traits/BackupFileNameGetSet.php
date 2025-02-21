<?php
namespace Modules\Backup\Traits;

trait BackupFileNameGetSet
{
    /**
     * @var array
     */
    public $backupFileName;

    /**
     * @param $filename
     * @return void
     */
    public function setBackupFileName($filename)
    {
        $filename = trim($filename);
        $filename = str_slug($filename);
        $filename = str_replace('-', '_', $filename);

        $this->backupFileName = $filename;
    }

}
