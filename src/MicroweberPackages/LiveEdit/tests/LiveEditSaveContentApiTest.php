<?php

namespace MicroweberPackages\LiveEdit\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;
use Modules\Page\Models\Page;


class LiveEditSaveContentApiTest extends TestCase
{

    public function testIndex()
    {
        $user = User::where('is_admin', '=', '1')->first();
        if (!$user) {
            // mak user
            $user = new User();
            $user->username = 'admin';
            $user->email = 'info@example.com';
            $user->password = bcrypt('admin');
            $user->is_admin = 1;
            $user->is_active = 1;
            $user->save();
        }

        Auth::login($user);
        $user = User::where('is_admin', '=', '1')->first();

        Auth::login($user);


        $response = $this->call(
            'GET',
            route('home'),
            [
                'editmode' => 'y'
            ]
        );

        $this->assertEquals(200, $response->getStatusCode());

    }

    public function testSaveContentOnPage()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        Page::where('title', 'LiveEditPage')->delete();
        $newCleanPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
             'title' => 'LiveEditPage',
            'url' => 'liveedittestsavetest',

            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanPage)->first();
        $this->assertEquals($findPage->id, $newCleanPage);


        // Save on default lang
        $contentFieldHtml = 'Example content saved from live edit api' . uniqid('_unit');
        $fieldsData = [
            'field_data_0' => [
                'attributes' => [
                    'class' => 'container edit',
                    'rel' => 'content',
                    'rel_id' => $findPage->id,
                    'field' => 'content',
                ],
                'html' => $contentFieldHtml
            ]
        ];

        $encoded = base64_encode(json_encode($fieldsData));
        $_SERVER['HTTP_REFERER'] = content_link($findPage->id);

        $response = $this->call(
            'POST',
            route('api.content.save_edit'),
            [
                'data_base64' => $encoded,
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );

        $fieldSaved = $response->decodeResponseJson();

        //    $this->assertEquals($fieldSaved[0]['table'], 'content');
        $this->assertEquals($fieldSaved[0]['field'], 'content');

        $saved_id = $findPage->id;
        $saved_content = $contentFieldHtml;

        $params = [];
        $params['content_id'] = $saved_id;
        $page = app()->content_manager->get_by_id($saved_id);

        $this->assertNotEmpty($page);



        $render_file = app()->template_manager->get_layout($page);

        dd($render_file);


        $frontRender = new FrontendController();
        $html = $frontRender->frontend($params);
        $contentFieldHtml = $saved_content;

        $this->assertTrue(str_contains($html, $contentFieldHtml));


        $response = $this->call(
            'POST',
            route('api.content.save_edit'),
            [
                'data_base64' => 'somethingthatisnotbase64',
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );
        $fieldSaved = $response->decodeResponseJson();
        $this->assertArrayHasKey('error', $fieldSaved);


    }
}
