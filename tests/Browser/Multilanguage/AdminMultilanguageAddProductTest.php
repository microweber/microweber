<?php
namespace Tests\Browser\Multilanguage;

use Faker\Factory;
use Laravel\Dusk\Browser;
use MicroweberPackages\Content\tests\TestHelpers;
use MicroweberPackages\Product\Models\Product;
use Tests\Browser\Components\AdminContentCategorySelect;
use Tests\Browser\Components\AdminContentCustomFieldAdd;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminContentMultilanguage;
use Tests\Browser\Components\AdminContentTagAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\FrontendSwitchLanguage;
use Tests\DuskTestCase;
use Tests\DuskTestCaseMultilanguage;
/**
 * @runTestsInSeparateProcesses
 */
class AdminMultilanguageAddProductTest extends DuskTestCaseMultilanguage
{

    use TestHelpers;
    public function testAddProduct()
    {

        $title='Shop ML '.uniqid();
        $pageId = $this->_generateShopPage($title, $title);
        $this->_generateCategory('clothes', 'Clothes', $pageId);
        $this->_generateCategory('t-shirts', 'T-shirts', $pageId);
        $this->_generateCategory('decor', 'Decor', $pageId);

        $this->browse(function (Browser $browser) use ($title,$pageId) {

            $quickTest = false;

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new AdminContentMultilanguage, function ($browser) {
                $browser->addLanguage('bg_BG');
                $browser->addLanguage('en_US');
                $browser->addLanguage('ar_SA');
                $browser->addLanguage('zh_CN');
            });

            $productDataMultilanguage = [];

            foreach(get_supported_languages(true) as $supportedLocale) {
                $locale = $supportedLocale['locale'];

                $faker = Factory::create($locale);
                $productTitle = $faker->name . ' on lang - ' .$locale . time().rand(1111,9999);

                $productDataMultilanguage[$locale] = [
                  'title'=> $productTitle,
                  'url'=> str_slug($productTitle),
                  'content_body'=> 'Product content body ' . $locale . ' on lang' . time().rand(1111,9999),
                  'content_meta_title'=> 'Product content meta title ' . $locale . ' on lang' . time().rand(1111,9999),
                  'description'=> 'Product description ' . $locale . ' on lang' . time().rand(1111,9999),
                  'content_meta_keywords'=> 'Product, content, meta, keywords, ' . $locale . ', on lang, ' . time().rand(1111,9999),
                ];
            }

            $browser->visit(route('admin.product.create'));

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->within(new AdminContentMultilanguage, function ($browser) use ($productDataMultilanguage) {
                foreach($productDataMultilanguage as $locale=>$productData) {
                    $browser->fillTitle($productData['title'], $locale);
                    $browser->fillUrl($productData['url'], $locale);
                    $browser->fillContentBody($productData['content_body'], $locale);
                }
            });

            $browser->within(new AdminContentMultilanguage, function ($browser) use ($productDataMultilanguage) {
                foreach($productDataMultilanguage as $locale=>$productData) {
                    $browser->fillContentMetaTitle($productData['content_meta_title'], $locale);
                    $browser->fillDescription($productData['description'], $locale);
                    $browser->fillContentMetaKeywords($productData['content_meta_keywords'], $locale);
                }
            });

            $browser->click('.js-default-card-tab');


            if ($quickTest == false) {
                $productPrice = rand(1111, 9999);
                $productSpecialPrice = $productPrice - rand(1, 9);
                $productSku = rand(1111, 9999);

                $productBarcode = rand(1111, 9999);
                $productQuantity = rand(11, 99);

                $browser->value('.js-product-price', $productPrice);
                $browser->pause(1000);

                $browser->script("$('html, body').animate({ scrollTop: $('.js-product-pricing-card').offset().top - 30 }, 0);");
                $browser->pause(3000);
                $browser->script("$('.js-toggle-offer-price-button').click()");
                $browser->pause(2000);
                $browser->value('.js-product-special-price', $productSpecialPrice);
                $browser->pause(1000);

                $browser->script("$('.js-invertory-sell-oos').click()");
                $browser->script("$('.js-track-quantity-check').click()");
                $browser->pause(1000);
                $browser->select('.js-track-quantity-select-qty', $productQuantity);


                $browser->value('.js-invertory-sku', $productSku);
                $browser->pause(1000);

                $browser->value('.js-invertory-barcode', $productBarcode);
                $browser->pause(1000);


                $category4 = $title;
                $category4_1 = 'Clothes';
                $category4_2 = 'T-shirts';
                $category4_3 = 'Decor';

                $browser->within(new AdminContentCategorySelect, function ($browser) use ($category4, $category4_1, $category4_2, $category4_3) {
                    $browser->selectCategory($category4);
                    $browser->selectSubCategory($category4, $category4_1);
                    $browser->selectSubCategory($category4, $category4_2);
                    $browser->selectSubCategory($category4, $category4_3);
                });

                $tag1 = 'Tagdusk-' . time() . rand(100, 200);
                $tag2 = 'Tagdusk-' . time() . rand(200, 300);
                $tag3 = 'Tagdusk-' . time() . rand(300, 400);
                $tag4 = 'Tagdusk-' . time() . rand(400, 500);

                $browser->within(new AdminContentTagAdd, function ($browser) use ($tag1, $tag2, $tag3, $tag4) {
                    $browser->addTag($tag1);
                    $browser->addTag($tag2);
                    $browser->addTag($tag3);
                    $browser->addTag($tag4);
                });

                // add images to gallery
                $browser->within(new AdminContentImageAdd, function ($browser) {
                    $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img1.jpg');
                    $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img2.jpg');
                    $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img3.jpg');
                });


                $browser->within(new AdminContentCustomFieldAdd, function ($browser) {
                    $browser->addCustomField('dropdown', 'Dropdown');
                    $browser->addCustomField('text', 'Text Field');
                });
            }

            $browser->pause(1000);
            $browser->click('#js-admin-save-content-main-btn');

            $browser->pause(3000);

            $findProduct = Product::where('title', $productDataMultilanguage['en_US']['title'])->first();

            $browser->waitForLocation(route('admin.product.edit', $findProduct->id));

            $browser->pause(3000);
            $browser->visit(content_link($findProduct->id));

            foreach($productDataMultilanguage as $locale=>$productData) {

                $browser->within(new FrontendSwitchLanguage, function ($browser) use($locale) {
                    $browser->switchLanguage($locale);
                });

                $browser->pause(3000);
                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertTrue(strpos($currentUrl, $productData['url']) !== false);

                $browser->waitForText($productData['title'],30);
                $browser->waitForText($productData['content_body'],30);
            }

            $this->assertEquals($productDataMultilanguage['en_US']['title'], $findProduct->title);

            foreach($productDataMultilanguage as $locale=>$productData) {
                foreach($productData as $dataKey=>$dataValue) {
                    $this->assertEquals($dataValue, $findProduct->multilanguage_translatons[$locale][$dataKey]);
                }
            }

            $this->assertEquals($productPrice, $findProduct->price);
            $this->assertEquals($productSpecialPrice, $findProduct->special_price);
            $this->assertEquals($productQuantity, $findProduct->qty);
            $this->assertEquals($productSku, $findProduct->sku);

            $this->assertEquals($findProduct->content_body, $productDataMultilanguage['en_US']['content_body']);
            $this->assertEquals($findProduct->content_type, 'product');
            $this->assertEquals($findProduct->subtype, 'product');

            $tags = content_tags($findProduct->id);
            $this->assertTrue(in_array($tag1, $tags));
            $this->assertTrue(in_array($tag2, $tags));
            $this->assertTrue(in_array($tag3, $tags));
            $this->assertTrue(in_array($tag4, $tags));

            $findedCategories = [];
            $categories = content_categories($findProduct->id);
            foreach ($categories as $category) {
                $findedCategories[] = $category['title'];
            }

            $this->assertTrue(in_array('Decor', $findedCategories));
            $this->assertTrue(in_array('Clothes', $findedCategories));
            $this->assertTrue(in_array('T-shirts', $findedCategories));

            $findedCustomFields = [];
            $customFields = content_custom_fields($findProduct->id);
            foreach ($customFields as $customField) {
                $findedCustomFields[] = $customField['name'];
            }
            $this->assertTrue(in_array('Dropdown', $findedCustomFields));
            $this->assertTrue(in_array('Text Field', $findedCustomFields));

            $getPictures = get_pictures($findProduct->id);
            $this->assertEquals(3, count($getPictures));


        });

    }

}
