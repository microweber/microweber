<?php

namespace Modules\Backup\Models;

use Sushi\Sushi;
use Illuminate\Database\Eloquent\Model;
class Backup extends Model
{
    use Sushi;

    protected array $schema = [
        'id' => 'string',
        'filename' => 'string',
        'date' => 'string',
        'time' => 'string',
        'size' => 'string',
    ];

    public function getRows()
    {

        $backupLocation = backup_location();

        $backupFiles = [];


        $files = preg_grep('~\.(sql|zip|json|xml|xlsx|csv|xls)$~', scandir($backupLocation));
        if ($files) {
            foreach ($files as $file) {
                $backupFiles[] = normalize_path($backupLocation . $file, false);
            }
        }

        if (!empty($backupFiles)) {
            usort($backupFiles, function ($a, $b) {
                return filemtime($a) < filemtime($b);
            });
        }
        $backups = array();
        if (!empty($backupFiles)) {
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

}
