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

        $response = $this->call('GET', route('api.file-manager.list'),[]);
        $this->assertEquals(200, $response->status());

        $content = $response->getContent();

        $this->assertNotEmpty(json_decode($content));

    }

}
