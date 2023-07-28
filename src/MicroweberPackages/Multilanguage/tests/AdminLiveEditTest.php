<?php
namespace MicroweberPackages\App\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;
/**
 * @runTestsInSeparateProcesses
 */
class AdminLiveEditTest extends TestCase
{
    protected $preserveGlobalState = FALSE;
    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSaveCustomFieldOnPage()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);



        $newCleanPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'LiveEditContactUsPage',
            'url' => 'liveedit-contact-us-url',
            'preview_layout_file' => 'clean.php',
            'active_site_template'=> 'default',
            //'content'=> $contentFieldHtml,
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanPage)->first();
        $this->assertEquals($findPage->id, $newCleanPage);


        $contentFieldHtml = '<module type="custom_fields" template="bootstrap4" default-fields="Your Name[type=text,field_size=6,show_placeholder=true],Voornaamm[type=text,field_size=6,show_placeholder=true],Your.email@domain.com[type=email,field_size=6,show_placeholder=true],Telefonnummer[type=phone, field_size=6, show_placeholder=true],Naam club[type=number,field_size=6,show_placeholder=true],Aantal leden[type=number,field_size=6,show_placeholder=true],Beoefende sporttakken[type=textarea,field_size=12,show_placeholder=true],Ja ik wil op de hoogte gehouden worden via de nieuwsbrief[type=checkbox,field_size=12,show_placeholder=true]"/>';

        $fieldsData = [
            'field_data_0'=>[
                'attributes'=>[
                    'class'=>'container edit',
                    'rel'=>'content',
                    'rel_id'=>$findPage->id,
                    'field'=>'content',
                ],
                'html'=>$contentFieldHtml
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

        $_REQUEST['content_id'] = $findPage->id;
        $params = [];
        $params['content_id'] = $findPage->id;
        $frontRender = new FrontendController();
        $html = $frontRender->frontend($params);

        $get_cont = get_content_by_id($findPage->id);

        $this->assertTrue(str_contains($get_cont['content'], 'Voornaamm'));

        $this->assertTrue(str_contains($html, 'Voornaamm'));
        $this->assertTrue(str_contains($html, 'Your.email@domain.com'));
        $this->assertTrue(str_contains($html, 'Telefonnummer'));
        $this->assertTrue(str_contains($html, 'Naam club'));
        $this->assertTrue(str_contains($html, 'Aantal leden'));
        $this->assertTrue(str_contains($html, 'Beoefende sporttakken'));
        $this->assertTrue(str_contains($html, 'Ja ik wil op de hoogte gehouden worden via de nieuwsbrief'));

    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function testSaveContentOnPage()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $newCleanPage = save_content([
            'subtype' => 'static',
            'content_type' => 'page',
            'layout_file' => 'clean.php',
            'title' => 'LiveEditPage',
            'url' => 'liveedit-url',
            'preview_layout_file' => 'clean.php',
            'active_site_template'=> 'default',
            'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanPage)->first();
        $this->assertEquals($findPage->id, $newCleanPage);

        // Save on default lang
        $contentFieldHtml = 'Example content saved from live edit api'. uniqid('_unit');
        $fieldsData = [
            'field_data_0'=>[
                'attributes'=>[
                    'class'=>'container edit',
                    'rel'=>'content',
                    'rel_id'=>$findPage->id,
                    'field'=>'content',

                ],
                'html'=>$contentFieldHtml
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

        $_REQUEST['content_id'] = $findPage->id;

        $frontRender = new FrontendController();
        $html = $frontRender->index();

        $this->assertTrue(str_contains($html, $contentFieldHtml));

    }
}
