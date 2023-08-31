<?php
namespace MicroweberPackages\Multilanguage\tests;


use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\User\Models\User;

/**
 * @runTestsInSeparateProcesses
 */
class MultilanguageCategoryApiTest extends MultilanguageTestBase
{


    public function testSave()
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

        $currentLang = app()->lang_helper->current_lang();
        $defaultLang = app()->lang_helper->default_lang();
        $activeLanguages = get_supported_languages(true);

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $saveMultilanguage = [];
        foreach ($activeLanguages as $language) {
            $timeRand = time() . rand(111, 999);
            $saveMultilanguage['title'][$language['locale']] = $language['locale'] . $language['id'] . $timeRand;
            $saveMultilanguage['url'][$language['locale']] = $language['id'] . $timeRand;
            $saveMultilanguage['description'][$language['locale']] = $language['locale'] . $language['id'] . $timeRand;
        }

        $response = $this->postJson(

            route('api.category.store'),
            [
                'title' => 'TitleApiCategoryStore'.uniqid(), // this text must be overrwrite from multilanguage field
                'multilanguage' => $saveMultilanguage,
            ]
        );

        $categorySaved = $response->getData()->data;
        $findCategory = Category::where('id', $categorySaved->id)->first();

        $this->assertEquals($findCategory->title, $saveMultilanguage['title'][$currentLang]);
        $this->assertEquals($findCategory->url, $saveMultilanguage['url'][$currentLang]);
        $this->assertEquals($findCategory->description, $saveMultilanguage['description'][$currentLang]);

        foreach ($activeLanguages as $language) {
            $this->assertEquals($findCategory->multilanguage_translatons[$language['locale']]['url'], $saveMultilanguage['url'][$language['locale']]);
            $this->assertEquals($findCategory->multilanguage_translatons[$language['locale']]['title'], $saveMultilanguage['title'][$language['locale']]);
            $this->assertEquals($findCategory->multilanguage_translatons[$language['locale']]['description'], $saveMultilanguage['description'][$language['locale']]);
        }

        // Switch to another language
        $switchedLangAbr = 'bg_BG';
        $response = $this->postJson(

            route('api.multilanguage.change_language'),
            [
                'locale' => $switchedLangAbr,
            ]
        );

        $switchedLang = app()->lang_helper->current_lang();
        $this->assertEquals($switchedLangAbr, $switchedLang);
        $response = $response->decodeResponseJson();
        $this->assertEquals($response['refresh'], true);

        $getByCategory = get_category_by_id($findCategory->id);

        $this->assertEquals($getByCategory['title'], $saveMultilanguage['title'][$switchedLang]);
        $this->assertEquals($getByCategory['url'], $saveMultilanguage['url'][$switchedLang]);
        $this->assertEquals($getByCategory['description'], $saveMultilanguage['description'][$switchedLang]);

    }
}
