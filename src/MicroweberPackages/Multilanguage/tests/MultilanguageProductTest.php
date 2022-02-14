<?php

namespace MicroweberPackages\Multilanguage\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Multilanguage\Observers\MultilanguageObserver;
use MicroweberPackages\User\Models\User;
use MicroweberPackages\Multilanguage\MultilanguageApi;

class MultilanguageProductTest extends MultilanguageTestBase
{

    public function testSaveProductFromApiController()
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

        $rand = time() . rand(111, 999);

        $apiProductStore = [];
        $apiProductStore['title'] = 'Product ' . $rand;
        $apiProductStore['price'] = rand(111,999);
        $apiProductStore['content'] = 'Product description' . $rand;

        $apiProductStore['multilanguage']['title']['bg_BG'] = 'Съдържание' . $rand;
        $apiProductStore['multilanguage']['content']['bg_BG'] = 'Съдържание описание' . $rand;

        $apiProductStore['multilanguage']['title']['ar_SA'] = 'فئة' . $rand;
        $apiProductStore['multilanguage']['content']['ar_SA'] = 'وصف التصنيف' . $rand;


        $apiProductStore['multilanguage']['title']['ru_RU'] = 'Съдържание' . $rand;
        $apiProductStore['multilanguage']['content']['ru_RU'] = 'Описание Съдържание' . $rand;

        $response = $this->call(
            'POST',
            route('api.product.store', $apiProductStore)
        );

        $this->assertEquals(201, $response->status());
        $ProductSaved = $response->getData()->data;

        $getProduct = Product::where('id', $ProductSaved->id)->first();

        $this->assertEquals($getProduct->multilanguage['bg_BG']['title'], $apiProductStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getProduct->multilanguage['bg_BG']['content'], $apiProductStore['multilanguage']['content']['bg_BG']);

        $this->assertEquals($getProduct->multilanguage['ar_SA']['title'], $apiProductStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getProduct->multilanguage['ar_SA']['content'], $apiProductStore['multilanguage']['content']['ar_SA']);

        $this->assertEquals($getProduct->multilanguage['ru_RU']['title'], $apiProductStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getProduct->multilanguage['ru_RU']['content'], $apiProductStore['multilanguage']['content']['ru_RU']);

        // TEST BULGARIAN

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale' => 'bg_BG'
        ]);

        $getProduct = Product::where('id', $ProductSaved->id)->first();
        $this->assertEquals($getProduct->title, $apiProductStore['multilanguage']['title']['bg_BG']);
        $this->assertEquals($getProduct->content, $apiProductStore['multilanguage']['content']['bg_BG']);


        // TEST ARABIC

        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale' => 'ar_SA'
        ]);

        $getProduct = Product::where('id', $ProductSaved->id)->first();
        $this->assertEquals($getProduct->title, $apiProductStore['multilanguage']['title']['ar_SA']);
        $this->assertEquals($getProduct->content, $apiProductStore['multilanguage']['content']['ar_SA']);


        // TEST RUSSIAN
        $api = new MultilanguageApi();
        $output = $api->changeLanguage([
            'locale' => 'ru_RU'
        ]);

        $getProduct = Product::where('id', $ProductSaved->id)->first();
        $this->assertEquals($getProduct->title, $apiProductStore['multilanguage']['title']['ru_RU']);
        $this->assertEquals($getProduct->content, $apiProductStore['multilanguage']['content']['ru_RU']);


    }

}
