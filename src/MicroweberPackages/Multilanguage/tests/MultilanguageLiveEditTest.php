<?php
namespace MicroweberPackages\Multilanguage\tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\User\Models\User;
use Modules\Page\Models\Page;
use PHPUnit\Framework\Attributes\PreserveGlobalState;

 class MultilanguageLiveEditTest extends MultilanguageTestBase
{

    #[Test]

    public function it_save_content_on_page(): void {
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

        $getSupportedLocalesQuery = DB::table('multilanguage_supported_locales')->get();

        $activeLanguages = get_supported_languages(true);
        $this->assertNotEmpty($activeLanguages);
        $this->assertNotEmpty($getSupportedLocalesQuery);

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $unique = uniqid();

        $newCleanMlPage = save_content([
           'subtype' => 'static',
           'content_type' => 'page',
           'title' => 'LiveEditMultilanguagePage'.$unique,
           'url' => 'liveeditmultilanguagepage'.$unique,

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
            [],    // cookies
            [],    // files
            $_SERVER // server (contains HTTP_REFERER)
        );
        $fieldSaved = $response->decodeResponseJson();

        $this->assertEquals($fieldSaved[0]['content'], $contentFieldHtmlDefaultLanguage);
        $this->assertEquals($fieldSaved[0]['rel_type'], 'content');
        $this->assertEquals($fieldSaved[0]['field'], 'content');

    }

    private function _refreshServerConstantsByPageId($pageId) {

        $pageLink = content_link($pageId);
        $pageLink = '/' . str_replace(site_url(),'', $pageLink);

        $_SERVER['PHP_SELF'] = '/index.php';
        $_SERVER['REQUEST_URI'] = $pageLink;
        $_SERVER['REDIRECT_URL'] = $pageLink;
        $_SERVER['HTTP_REFERER'] = content_link($pageId);
    }
}
