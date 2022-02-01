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
