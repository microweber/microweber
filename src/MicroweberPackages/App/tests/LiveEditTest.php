<?php

namespace MicroweberPackages\App\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;


class LiveEditTest extends TestCase
{

    public function testIndex()
    {

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

        $newCleanPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'LiveEditPage',
            'url' => 'liveedit',
            'preview_layout_file' => 'clean.php',
            'active_site_template' => 'default',
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

        $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
        $this->assertEquals($fieldSaved[0]['field'], 'content');

        $saved_id = $findPage->id;
        $saved_content = $contentFieldHtml;

        $params = [];
        $params['content_id'] = $saved_id;

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
        $this->assertArrayHasKey( 'error',$fieldSaved);


    }
}
