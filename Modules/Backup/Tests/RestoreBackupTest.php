<?php

namespace Modules\Backup\tests;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Backup\GenerateBackup;
use Modules\Content\Models\Content;
use Modules\Export\Export;
use Modules\Export\SessionStepper;
use Modules\Import\DatabaseSave;
use Modules\Multilanguage\tests\MultilanguageTest;
use Modules\Post\Models\Post;
use Modules\User\Models\User;


/**
 * Run test
 * @author bobi@microweber.com
 * @command php artisan test --filter RestoreBackupTest
 */
class RestoreBackupTest extends TestCase
{

    public function testRestoreDataBackup()
    {

//        $getUsers = User::all();
//        $getUsers->each(function ($user) {
//            $user->delete();
//        });
//
//        $getAllContent = Content::all();
//        $getAllContent->each(function ($content) {
//            $content->delete();
//        });

        $randUserneme = 'unitTestUser' . rand(1, 9999);
        $randemail = 'unitTestUser' . rand(1, 9999) . '@email.com';
        $createUser = new User();
        $createUser->username = $randUserneme;
        $createUser->email = $randemail;
        $createUser->password = 'insecurePassword';
        $createUser->is_admin = 1;
        $createUser->is_active = 1;
        $createUser->save();

        $findUser = User::where('username', $randUserneme)->first();
        $userOriginalPassword = $findUser->password;

        $post = new Post();
        $post->url = 'test-post';
        $post->title = 'Test post';
        $post->save();


        $exportTableData = [

            'users',
            'content',
            'categories',
            'media',
            'custom_fields',
            'custom_fields_values',
            'custom_fields_data'

        ];

        $sessionId = SessionStepper::generateSessionId(4);

        for ($i = 1; $i <= 3; $i++) {

          //  echo "Step: #" . $i . PHP_EOL;

            $backup = new GenerateBackup();
            $backup->setSessionId($sessionId);
            //  $backup->setExportAllData(true);
            $backup->setExportData('tables', $exportTableData);
            $backup->setExportMedia(false);
            $backup->setExportWithZip(true);
            $backup->setAllowSkipTables(false);

            $backupStart = $backup->start();
            if (isset($backupStart['success'])) {
                break;
            }
        }

        $findUser = User::where('username', $randUserneme)->first();
        $findUser->password = 'newPasswordStrong1234';
        $findUser->save();

        $newUserPassword = $findUser->password;

        $exportedFile = $backupStart['data']['filepath'];

        $zip = new \ZipArchive;
        $res = $zip->open($exportedFile);
        $this->assertTrue(!empty($res));

        $jsonFileInsideZip = str_replace('.zip', '.json', $exportedFile);
        $jsonFileInsideZip = basename($jsonFileInsideZip);
        $jsonFileContent = $zip->getFromName($jsonFileInsideZip);
        $jsonExportTest = json_decode($jsonFileContent, true);


        // Restore
        $sessionId = SessionStepper::generateSessionId(1);

        $restore = new \Modules\Backup\Restore();
        $restore->setFile($exportedFile);
        $restore->setOvewriteById(true);
        $restore->setWriteOnDatabase(true);
        $restore->setSessionId($sessionId);
        $restoreStatus = $restore->start();


        $this->assertTrue($restoreStatus['done']);


        $findUser = User::where('username', $randUserneme)->first();
        $this->assertEquals($newUserPassword, $findUser->password);
    }
}
