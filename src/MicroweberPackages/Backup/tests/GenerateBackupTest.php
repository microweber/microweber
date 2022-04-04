<?php
namespace MicroweberPackages\Backup\tests;

use MicroweberPackages\Backup\GenerateBackup;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Export\SessionStepper;


/**
 * Run test
 * @author bobi@microweber.com
 * @command php artisan test --filter GenerateBackupTest
 */

class GenerateBackupTest extends TestCase
{
    public function testSingleModuleBackup() {

        \Config::set('microweber.allow_php_files_upload', true);



        $sessionId = SessionStepper::generateSessionId(4);

        for ($i = 1; $i <= 4; $i++) {
            $backup = new GenerateBackup();
            $backup->setSessionId($sessionId);
            $backup->setExportModules(['categories/category_images']);
            $status = $backup->start();
        }

        $zip = new \ZipArchive();
        $zip->open($status['data']['filepath']);
        $moduleInZip = $zip->getFromName('modules/categories/category_images/index.php');
        $zip->close();

        $this->assertNotEmpty($moduleInZip);
    }

    public function testSingleTableBackup() {

        $sessionId = SessionStepper::generateSessionId(4);

        $backup = new GenerateBackup();
        $backup->setSessionId($sessionId);
        $backup->setAllowSkipTables(false);
        $backup->setExportTables(['content']);
        $backup->setExportMedia(false);
        $backup->setExportModules([]);
        $backup->setExportTemplates([]);

        $status = $backup->start();

        $content = file_get_contents($status['data']['filepath']);
        $content = json_decode($content, true);

        $this->assertNotEmpty($content['content'][0]['url']);
        $this->assertNotEmpty($content['__table_structures']['content']['url']);


    }
}
