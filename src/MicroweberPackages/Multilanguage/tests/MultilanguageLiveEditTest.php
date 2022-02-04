<?php
namespace MicroweberPackages\Multilanguage\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;

class MultilanguageLiveEditTest extends MultilanguageTestBase
{
    private function _refreshServerConstantsByPageId($pageId) {

        $pageLink = content_link($pageId);
        $pageLink = '/' . str_replace(site_url(),'', $pageLink);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQUEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] = content_link($pageId);
    }

    public function testSaveContentOnPage()
    {
        MultilanguageHelpers::setMultilanguageEnabled(1);

        $params = [
            'for_module' => 'multilanguage'
        ];
        app()->module_manager->set_installed($params);
        $test = app()->module_manager->is_installed($params['for_module']);
        $this->assertEquals(true, $test);

        add_supported_language('en_US', 'English');
        add_supported_language('bg_BG', 'Bulgarian');
        add_supported_language('ar_SA', 'Arabic');
        add_supported_language('ru_RU', 'Russian');

        save_option('language','en_US', 'website');

        $activeLanguages = get_supported_languages(true);
        $this->assertNotEmpty($activeLanguages);

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $unique = uniqid();

        $newCleanMlPage = save_content([
           'subtype' => 'static',
           'content_type' => 'page',
           'layout_file' => 'clean.php',
           'title' => 'LiveEditMultilanguagePage'.$unique,
           'url' => 'liveeditmultilanguagepage'.$unique,
           'preview_layout_file' => 'clean.php',
           'active_site_template'=> 'new-world',
           'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanMlPage)->first();
        $this->assertEquals($findPage->id, $newCleanMlPage);

        $this->_refreshServerConstantsByPageId($findPage->id);

        // Save on default lang
        $contentFieldHtmlDefaultLanguage = 'Example default lang content saved from live edit api'. uniqid('_unit');
        $fieldsData = [
            'field_data_0'=>[
                'attributes'=>[
                    'class'=>'container edit',
                    'rel'=>'content',
                    'rel_id'=>$findPage->id,
                    'field'=>'content',
                ],
                'html'=>$contentFieldHtmlDefaultLanguage
            ]
        ];

        $encoded = base64_encode(json_encode($fieldsData));

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

        $this->assertEquals($fieldSaved[0]['content'], $contentFieldHtmlDefaultLanguage);
        $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
        $this->assertEquals($fieldSaved[0]['field'], 'content');

        $frontRender = new FrontendController();
        $html = $frontRender->frontend([
            'content_id'=>$findPage->id
        ]);
        $this->assertTrue(str_contains($html->getContent(), $contentFieldHtmlDefaultLanguage));


        /**
         * SWITCH BACK TO BULGARIAN TO CHECK ////////////////////////////////////////////////
         */
        $switchedLangAbr = 'bg_BG';
        $response = $this->call(
            'POST',
            route('api.multilanguage.change_language'),
            [
                'locale' => $switchedLangAbr,
            ]
        );
        $switchedLang = app()->lang_helper->current_lang();
        $this->assertEquals($switchedLangAbr, $switchedLang);

        $response = $response->decodeResponseJson();
        $this->assertEquals($response['refresh'], true);

        $this->_refreshServerConstantsByPageId($findPage->id);

        // Save on BULGARIAN lang
        $contentFieldHtmlBulgarianLang = 'Example content saved from live edit for BG language '. uniqid('_unit');
        $fieldsData = [
            'field_data_0'=>[
                'attributes'=>[
                    'class'=>'container edit',
                    'rel'=>'content',
                    'rel_id'=>$findPage->id,
                    'field'=>'content',
                ],
                'html'=>$contentFieldHtmlBulgarianLang
            ]
        ];

        $encoded = base64_encode(json_encode($fieldsData));

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

        $this->assertEquals($fieldSaved[0]['content'], $contentFieldHtmlBulgarianLang);
        $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
        $this->assertEquals($fieldSaved[0]['field'], 'content');

        $bgResponse = $this->call(
            'GET',
            content_link($findPage->id),
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );


        return; 
        dd($bgResponse->getContent());

        $this->assertTrue(str_contains($bgResponse->getContent(), $contentFieldHtmlBulgarianLang));
        return;

        return;

        // Switch back to english to check
        $switchedLangAbr = default_lang();
        $response = $this->call(
            'POST',
            route('api.multilanguage.change_language'),
            [
                'locale' => $switchedLangAbr,
            ]
        );
        $switchedLang = app()->lang_helper->current_lang();
        $this->assertEquals($switchedLangAbr, $switchedLang);

        $response = $response->decodeResponseJson();
        $this->assertEquals($response['refresh'], true);

        // Get content again from switched english
        $frontRender = new FrontendController();
        $html = $frontRender->frontend([
            'content_id'=>$findPage->id
        ]);
        $this->assertTrue(str_contains($html->getContent(), $contentFieldHtmlDefaultLanguage));

    }
}
