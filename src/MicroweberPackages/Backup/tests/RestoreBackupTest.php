<?php
namespace MicroweberPackages\Backup\tests;

use MicroweberPackages\Backup\GenerateBackup;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Export\Export;
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Import\DatabaseSave;
use MicroweberPackages\Multilanguage\tests\MultilanguageTest;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\User\Models\User;


/**
 * Run test
 * @author bobi@microweber.com
 * @command php artisan test --filter RestoreBackupTest
 */



class RestoreBackupTest extends TestCase
{

    public function testRestoreDataBackup() {

        $getUsers = User::all();
        $getUsers->each(function ($user) {
            $user->delete();
        });

        $getAllContent = Content::all();
        $getAllContent->each(function ($content) {
            $content->delete();
        });

        $createUser = new User();
        $createUser->username = 'unitTestUser';
        $createUser->email = 'unitTestUser@email.com';
        $createUser->password = 'insecurePassword';
        $createUser->is_admin = 1;
        $createUser->is_active = 1;
        $createUser->save();

        $findUser = User::where('username', 'unitTestUser')->first();
        $userOriginalPassword = $findUser->password;

        $post = new Post();
        $post->url = 'test-post';
        $post->title = 'Test post';
        $post->save();

        $sessionId = SessionStepper::generateSessionId(20);

        for ($i = 1; $i <= 20; $i++) {

            $backup = new GenerateBackup();
            $backup->setSessionId($sessionId);
            $backup->setExportAllData(true);
            $backup->setExportMedia(true);
            $backup->setExportWithZip(true);
            $backup->setAllowSkipTables(false);

            $backupStart = $backup->start();

            if (isset($status['success'])) {
                break;
            }
        }

        $findUser = User::where('username', 'unitTestUser')->first();
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
        $jsonExportTest = json_decode($jsonFileContent,true);


        // Restore
        $sessionId = SessionStepper::generateSessionId(1);
        $restore = new \MicroweberPackages\Backup\Restore();
        $restore->setFile($exportedFile);
        $restore->setOvewriteById(true);
        $restore->setWriteOnDatabase(true);
        $restore->setSessionId($sessionId);
        $restoreStatus = $restore->start();

        $findUser = User::where('username', 'unitTestUser')->first();

        $this->assertEquals($newUserPassword, $findUser->password);
    }
}
