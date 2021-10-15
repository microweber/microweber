<?php

namespace MicroweberPackages\Content\tests;

use content\controllers\Edit;
use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class ContentEditAdminTest extends TestCase
{
    public function testEdit()
    {
        $user = User::where('is_admin','=', '1')->first();
        Auth::login($user);

        $params = [];
        $params['content_type'] = 'page';
        $params['id'] = '0';
        $params['page-id'] = '0';
        $params['module'] = 'content/edit';

        $editController = new Edit();
        $html = $editController->index($params);

        $this->assertTrue(str_contains($html,'Page title'));

    }
}
