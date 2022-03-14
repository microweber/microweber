<?php
namespace MicroweberPackages\Multilanguage\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Multilanguage\MultilanguageApi;

class MultilanguageCategoryTest extends MultilanguageTestBase
{

    public function testSaveCategoryFromApiController()
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

        // Set default lang
        $option = array();
        $option['option_value'] = 'en_US';
        $option['option_key'] = 'language';
        $option['option_group'] = 'website';
        save_option($option);

        $option = array();
        $option['option_value'] = 'y';
        $option['option_key'] = 'is_active';
        $option['option_group'] = 'multilanguage_settings';
        save_option($option);

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $response = $this->call(
            'POST',
            route('api.multilanguage.change_language'),
            [
                'locale' => app()->lang_helper->default_lang(),
            ]
        );

        $rand = time().rand(111,999);

        $apiCategoryStore = [];
        $apiCategoryStore['title'] = 'Category ' . $rand;
        $apiCategoryStore['description'] = 'Category description' .$rand;


        $apiCategoryStore['multilanguage']['title']['en_US'] = 'Category'.$rand;
        $apiCategoryStore['multilanguage']['description']['en_US'] = 'Category description'.$rand;


        $apiCategoryStore['multilanguage']['title']['bg_BG'] = 'Категория'.$rand;
        $apiCategoryStore['multilanguage']['description']['bg_BG'] = 'Категория описание'.$rand;

        $apiCategoryStore['multilanguage']['title']['ar_SA'] = 'فئة'.$rand;
        $apiCategoryStore['multilanguage']['description']['ar_SA'] = 'وصف التصنيف'.$rand;

        $apiCategoryStore['multilanguage']['title']['ru_RU'] = 'Категории'.$rand;
        $apiCategoryStore['multilanguage']['description']['ru_RU'] = 'Описание категории'.$rand;

        $response = $this->call(
            'POST',
            route('api.category.store', $apiCategoryStore)
        );
        $this->assertEquals(201, $response->status());
        $categorySaved = $response->getData()->data;
        $getCategory = Category::where('id', $categorySaved->id)->first();

        $this->assertEquals($getCategory->title, $apiCategoryStore['title']);
        $this->assertEquals($getCategory->description, $apiCategoryStore['description']);

        $this->assertEquals($getCategory->multilanguage['bg_BG']['title'], $apiCategoryStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getCategory->multilanguage['bg_BG']['description'], $apiCategoryStore['multilanguage']['description']['bg_BG']);

        $this->assertEquals($getCategory->multilanguage['ar_SA']['title'], $apiCategoryStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getCategory->multilanguage['ar_SA']['description'], $apiCategoryStore['multilanguage']['description']['ar_SA']);

        $this->assertEquals($getCategory->multilanguage['ru_RU']['title'], $apiCategoryStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getCategory->multilanguage['ru_RU']['description'], $apiCategoryStore['multilanguage']['description']['ru_RU']);

        // TEST BULGARIAN
        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'bg_BG'
        ]);

        $getCategory = Category::where('id', $categorySaved->id)->first();
        $this->assertEquals($getCategory->title, $apiCategoryStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getCategory->description, $apiCategoryStore['multilanguage']['description']['bg_BG']);


        // TEST ARABIC
        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'ar_SA'
        ]);

        $getCategory = Category::where('id', $categorySaved->id)->first();
        $this->assertEquals($getCategory->title, $apiCategoryStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getCategory->description, $apiCategoryStore['multilanguage']['description']['ar_SA']);


        // TEST RUSSIAN
        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'ru_RU'
        ]);

        $getCategory = Category::where('id', $categorySaved->id)->first();
        $this->assertEquals($getCategory->title, $apiCategoryStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getCategory->description, $apiCategoryStore['multilanguage']['description']['ru_RU']);


    }

}
