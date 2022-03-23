<?php
namespace MicroweberPackages\Multilanguage\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Multilanguage\MultilanguageApi;

class MultilanguagePostTest extends MultilanguageTestBase
{

    public function testSavePostFromApiController()
    {

        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(1);

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

        $rand = time().rand(111,999);

        $apiPostStore = [];
        $apiPostStore['title'] = 'Post ' . $rand;
        $apiPostStore['content'] = 'Post description' .$rand;

        $apiPostStore['multilanguage']['title']['en_US'] = 'Post'.$rand;
        $apiPostStore['multilanguage']['content']['en_US'] = 'Post description'.$rand;

        $apiPostStore['multilanguage']['title']['bg_BG'] = 'Съдържание'.$rand;
        $apiPostStore['multilanguage']['content']['bg_BG'] = 'Съдържание описание'.$rand;

        $apiPostStore['multilanguage']['title']['ar_SA'] = 'فئة'.$rand;
        $apiPostStore['multilanguage']['content']['ar_SA'] = 'وصف التصنيف'.$rand;


        $apiPostStore['multilanguage']['title']['ru_RU'] = 'Съдържание'.$rand;
        $apiPostStore['multilanguage']['content']['ru_RU'] = 'Описание Съдържание'.$rand;

        $response = $this->call(
            'POST',
            route('api.post.store', $apiPostStore)
        );

        $this->assertEquals(201, $response->status());
        $PostSaved = $response->getData()->data;

        $getPost = Post::where('id', $PostSaved->id)->first();

        $this->assertEquals($getPost->multilanguage['bg_BG']['title'], $apiPostStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getPost->multilanguage['bg_BG']['content'], $apiPostStore['multilanguage']['content']['bg_BG']);

        $this->assertEquals($getPost->multilanguage['ar_SA']['title'], $apiPostStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getPost->multilanguage['ar_SA']['content'], $apiPostStore['multilanguage']['content']['ar_SA']);

        $this->assertEquals($getPost->multilanguage['ru_RU']['title'], $apiPostStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getPost->multilanguage['ru_RU']['content'], $apiPostStore['multilanguage']['content']['ru_RU']);

        // TEST BULGARIAN

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'bg_BG'
        ]);

        $getPost = Post::where('id', $PostSaved->id)->first();
        $this->assertEquals($getPost->title, $apiPostStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getPost->content, $apiPostStore['multilanguage']['content']['bg_BG']);


        // TEST ARABIC

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'ar_SA'
        ]);

        $getPost = Post::where('id', $PostSaved->id)->first();
        $this->assertEquals($getPost->title, $apiPostStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getPost->content, $apiPostStore['multilanguage']['content']['ar_SA']);


        // TEST RUSSIAN
        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale'=> 'ru_RU'
        ]);

        $getPost = Post::where('id', $PostSaved->id)->first();
        $this->assertEquals($getPost->title, $apiPostStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getPost->content, $apiPostStore['multilanguage']['content']['ru_RU']);


    }

}
