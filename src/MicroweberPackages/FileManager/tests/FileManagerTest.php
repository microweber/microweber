<?php

namespace MicroweberPackages\FileManager\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class FileManagerTest extends TestCase
{

    public function testList()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Create new folder
        $lastCreatedFolderName = rand(111,999).'folder';
        $response = $this->call('POST', route('api.file-manager.create-folder'),[
            'name'=>$lastCreatedFolderName
        ]);
        $this->assertEquals(200, $response->status());
        $createFolder = $response->getContent();

        // List files
        $response = $this->call('GET', route('api.file-manager.list'),[]);
        $this->assertEquals(200, $response->status());

        $content = $response->getContent();
        $listedFiles = json_decode($content, true);

        // Find created folder
        $findCreatedFolder = false;
        foreach ($listedFiles['data'] as $files) {
            if ($files['type'] == 'folder') {
                if ($files['name'] == $lastCreatedFolderName) {
                    $findCreatedFolder = true;
                    break;
                }
            }
        }

        $this->assertTrue($findCreatedFolder);
        $this->assertNotEmpty($listedFiles);

    }

    public function testListWithManyFolderCreations()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Create new folder
        $lastCreatedFolderNames = [];

        for ($i = 1; $i <= 20; $i++) {

            $lastCreatedFolderName = rand(111, 999) . 'folder';
            $response = $this->call('POST', route('api.file-manager.create-folder'), [
                'name' => $lastCreatedFolderName
            ]);
            $this->assertEquals(200, $response->status());
            $createFolder = $response->getContent();

            $lastCreatedFolderNames[] = $lastCreatedFolderName;
        }

        // List files
        $response = $this->call('GET', route('api.file-manager.list'),[]);
        $this->assertEquals(200, $response->status());

        $content = $response->getContent();
        $listedFiles = json_decode($content, true);

        // Find created folder
        $findCreatedFolders = 0;
        foreach ($listedFiles['data'] as $files) {
            if ($files['type'] == 'folder') {
                foreach ($lastCreatedFolderNames as $lastCreatedFolderName) {
                    if ($files['name'] == $lastCreatedFolderName) {
                        $findCreatedFolders++;
                    }
                }
            }
        }

        $this->assertTrue($findCreatedFolders == count($lastCreatedFolderNames));
        $this->assertNotEmpty($listedFiles);

    }

    public function testListWithPagination()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Create new folders
        for ($i = 1; $i <= 50; $i++) {
            $randFileName = rand(111, 999) . 'randFileName.txt';
            $path = media_uploads_path();
            file_put_contents($path . $randFileName, time());
        }

        // List files
        $response = $this->call('GET', route('api.file-manager.list'),['limit'=>10]);
        $this->assertEquals(200, $response->status());

        $content = $response->getContent();
        $listedFiles = json_decode($content, true);

        $this->assertEquals( 10,$listedFiles['pagination']['limit']);

        $this->assertIsInt($listedFiles['pagination']['limit']);
        $this->assertIsInt($listedFiles['pagination']['total']);
        $this->assertIsInt($listedFiles['pagination']['count']);
        $this->assertIsInt($listedFiles['pagination']['perPage']);
        $this->assertIsInt($listedFiles['pagination']['currentPage']);
        $this->assertIsInt($listedFiles['pagination']['totalPages']);

    }

    public function testDeleteFile()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        // Create new file
        $randFileName = rand(111, 999) . 'randFileName.txt';
        $path = media_uploads_path();
        file_put_contents($path . $randFileName, time());

        // List files
        $response = $this->call('GET', route('api.file-manager.list'));
        $this->assertEquals(200, $response->status());

        $content = $response->getContent();
        $listedFiles = json_decode($content, true);

        $findCreatedFile = false;
        foreach ($listedFiles['data'] as $files) {
            if ($files['type'] == 'file') {
                if ($files['name'] == $randFileName) {
                    $findCreatedFile = true;
                    break;
                }
            }
        }
        $this->assertTrue($findCreatedFile);

        $envMediaPath = str_replace(media_base_path(), '', media_uploads_path());
        // Delete created file
        $response = $this->call('DELETE', route('api.file-manager.delete', [
            'paths'=>[
                $envMediaPath . $randFileName
            ]
        ]));
        $this->assertEquals(200, $response->status());
        $content = $response->getContent();
        $deleteFile = json_decode($content, true);
        $this->assertNotEmpty($deleteFile['success']);


        // List files after delete
        $response = $this->call('GET', route('api.file-manager.list'));
        $this->assertEquals(200, $response->status());

        $content = $response->getContent();
        $listedFiles = json_decode($content, true);

        $deletedFileNotFound = true;
        foreach ($listedFiles['data'] as $files) {
            if ($files['type'] == 'file') {
                if ($files['name'] == $randFileName) {
                    $deletedFileNotFound = false;
                    break;
                }
            }
        }
        $this->assertTrue($deletedFileNotFound);

    }

    public function testDeleteAllTestingFiles()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $path = media_uploads_path();
        rmdir_recursive($path, false);

        // List files
        $response = $this->call('GET', route('api.file-manager.list'));
        $this->assertEquals(200, $response->status());

        $content = $response->getContent();
        $listedFiles = json_decode($content, true);
        $this->assertEmpty($listedFiles['data']);

    }

}
