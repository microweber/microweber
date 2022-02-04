<?php
namespace MicroweberPackages\Multilanguage\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\App\Http\Controllers\FrontendController;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\User\Models\User;

class MultilanguageLiveEditTest extends MultilanguageTestBase
{
    public static $saved_id;
    public static $saved_content;
    public function testSaveContentOnPage()
    {
        MultilanguageHelpers::setMultilanguageEnabled(1);

        save_option('current_template','new-world', 'template');
        $currentTheme = mw()->template->name();
        $this->assertEquals('new-world',$currentTheme);

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

        $currentLang = app()->lang_helper->current_lang();
        $defaultLang = app()->lang_helper->default_lang();
        $activeLanguages = get_supported_languages(true);

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
           'active_site_template'=> 'default',
           'is_active' => 1,
        ]);

        $findPage = Page::whereId($newCleanMlPage)->first();
        $this->assertEquals($findPage->id, $newCleanMlPage);

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

        $this->assertEquals($fieldSaved[0]['content'], $contentFieldHtml);
        $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
        $this->assertEquals($fieldSaved[0]['field'], 'content');

        self::$saved_id=$findPage->id;
        self::$saved_content=$contentFieldHtml;

        $params = [];
        $params['content_id'] = self::$saved_id;

        $frontRender = new FrontendController();
        $html = $frontRender->frontend($params);
        $contentFieldHtml = self::$saved_content;

        $this->assertTrue(str_contains($html, $contentFieldHtml));

        // Now we switch on another language
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

    }
}
