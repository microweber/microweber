<?php
namespace MicroweberPackages\Multilanguage\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;

class MultilanguageLiveEditTest extends MultilanguageTestBase
{
    public function testSaveContentOnPage()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $newCleanMlPage = save_content([
           'subtype' => 'static',
           'content_type' => 'page',
           'layout_file' => 'clean.php',
           'title' => 'LiveEditMultilanguagePage',
           'url' => 'liveeditmultilanguagepage',
           'preview_layout_file' => 'clean.php',
           'active_site_template'=> 'default',
           'is_active' => 1,
        ]);

        $fingPage = Page::whereId($newCleanMlPage)->first();
        $this->assertEquals($fingPage->id, $newCleanMlPage);

        // save on default lang
        $fieldsData = [
            'field_data_0'=>[
                'attributes'=>[
                    'class'=>'container edit',
                    'rel'=>'module',
                    'field'=>'content',
                ],
                'html'=>'Example content saved from live edit api'
            ]
        ];

        $encoded = base64_encode(json_encode($fieldsData));

        $_SERVER['HTTP_REFERER'] = content_link($fingPage->id);

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

        $this->assertEquals($fieldSaved[0]['rel_type'], 'module');
        $this->assertEquals($fieldSaved[0]['field'], 'content');

        $getEditedPageSource = $this->call(
            'GET',
            content_link($fingPage->id)
        );

      //  dd($getEditedPageSource);



    }
}
