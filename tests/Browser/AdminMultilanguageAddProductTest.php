<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use MicroweberPackages\Product\Models\Product;
use Tests\Browser\Components\AdminContentCategorySelect;
use Tests\Browser\Components\AdminContentCustomFieldAdd;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminContentMultilanguage;
use Tests\Browser\Components\AdminContentTagAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class AdminMultilanguageAddProductTest extends DuskTestCase
{
    public function testAddProduct()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new AdminContentMultilanguage, function ($browser) {
                $browser->addLanguage('Bulgarian');
                $browser->addLanguage('English');
                $browser->addLanguage('Arabic');
            });

            $browser->visit(route('admin.product.create'));

            $enTitle = 'English title' . time();
            $bgTitle = 'Bulgarian title' . time();
            $arTitle = 'Arabic title' . time();

            $browser->within(new AdminContentMultilanguage, function ($browser) use ($bgTitle, $enTitle, $arTitle) {
                $browser->fillTitle($bgTitle, 'bg_BG');
                $browser->fillTitle($enTitle, 'en_US');
                $browser->fillTitle($arTitle, 'ar_SA');
            });

            $enDescription = 'English description' . time();
            $bgDescription = 'Bulgarian description' . time();
            $arDescription = 'Arabic description' . time();
            $browser->within(new AdminContentMultilanguage, function ($browser) use ($bgDescription, $enDescription, $arDescription) {
                $browser->fillDescription($bgDescription, 'bg_BG');
                $browser->fillDescription($enDescription, 'en_US');
                $browser->fillDescription($arDescription, 'ar_SA');
            });

            $productPrice = rand(1111, 9999);
            $productSpecialPrice = $productPrice - rand(1, 9);
            $productSku = rand(1111, 9999);

            $productBarcode = rand(1111, 9999);
            $productQuantity = rand(11, 99);

            $browser->visit(route('admin.product.create'));

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


            $category4 = 'Shop';
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

            $browser->pause(1000);
            $browser->click('#js-admin-save-content-main-btn');

            $findProduct = Product::where('title', $enTitle)->first();

            $browser->waitForLocation(route('admin.product.edit', $findProduct->id));

            $this->assertEquals($enTitle, $findProduct->title);
            $this->assertEquals($enTitle, $findProduct->multilanguage['en_US']['title']);
            $this->assertEquals($enDescription, $findProduct->multilanguage['en_US']['content_body']);

            $this->assertEquals($bgTitle, $findProduct->multilanguage['bg_BG']['title']);
            $this->assertEquals($bgDescription, $findProduct->multilanguage['bg_BG']['content_body']);

            $this->assertEquals($arTitle, $findProduct->multilanguage['ar_SA']['title']);
            $this->assertEquals($arDescription, $findProduct->multilanguage['ar_SA']['content_body']);

            $this->assertEquals($productPrice, $findProduct->price);
            $this->assertEquals($productSpecialPrice, $findProduct->special_price);
            $this->assertEquals($productQuantity, $findProduct->qty);
            $this->assertEquals($productSku, $findProduct->sku);

            $this->assertEquals($findProduct->content_body, $enDescription);
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

            $description = content_description($findProduct->id);
            $this->assertEquals($description, $enDescription);

            $getPictures = get_pictures($findProduct->id);
            $this->assertEquals(3, count($getPictures));


        });

    }
}
