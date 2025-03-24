<?php

namespace Modules\Backup\Tests;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Backup\Backup;
use Modules\Backup\SessionStepper;
use Modules\Content\Models\Content;
use Modules\Post\Models\Post;

/**
 * Run test
 * @author bobi@microweber.com
 * @command php artisan test --filter GenerateBackupTestUserfiles
 */
class GenerateBackupTestUserfiles extends TestCase
{
    public function testUserfilesBackup()
    {

        Config::set('microweber.allow_php_files_upload', true);
        // Use a much higher number of steps to ensure all files are processed
        $stepsNum = 5;
        $sessionId = SessionStepper::generateSessionId($stepsNum);


        //  $originalFilesPath = userfiles_path();
        $originalFilesPath = storage_path('app/public/');
        //count the files in path

        $originalFilesPathCount = 0;
        //recursive iterator with same settings as in ZipBatchBackup class
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($originalFilesPath, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $originalFilesPathCount++;
            }
        }


        for ($i = 0; $i <= $stepsNum; $i++) {
            $backup = new Backup();
            $backup->setSessionId($sessionId);
            $backup->setBackupWithZip(true);
            $backup->setBackupAllData(true);

            $backup->setBackupTables(['content']);


            $status = $backup->start();


            if (isset($status['success'])) {
                break;
            }

        }

        $filepath = $status['data']['filepath'];

        $this->assertNotNull($filepath, 'Filepath not found in status');
        $this->assertTrue(is_file($filepath), 'File not found');

        $zip = new \ZipArchive();
        $zip->open($filepath);

        //list

        $allFiles = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $allFiles[] = $zip->getNameIndex($i);
        }
        $this->assertNotEmpty($allFiles);

        // Check that we have files in the backup
        $this->assertGreaterThan(0, count($allFiles), 'Backup should contain files');

        $this->assertEquals($originalFilesPathCount + 1, count($allFiles));

    }

}
