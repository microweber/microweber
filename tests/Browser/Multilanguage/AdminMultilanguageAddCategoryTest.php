<?php

namespace Tests\Browser\Multilanguage;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Post\Models\Post;
use Tests\Browser\Components\AdminCategoryMultilanguage;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminContentMultilanguage;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\FrontendSwitchLanguage;
use Tests\DuskTestCaseMultilanguage;
/**
 * @runTestsInSeparateProcesses
 */
class AdminMultilanguageAddCategoryTest extends DuskTestCaseMultilanguage
{

    public function testAddCategory()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });



            $browser->within(new AdminContentMultilanguage, function ($browser) {
                $browser->addLanguage('en_US');
                $browser->addLanguage('bg_BG');
                $browser->addLanguage('ar_SA');
                $browser->addLanguage('zh_CN');
            });

            $categoryDataMultilanguage = [];

            foreach(get_supported_languages(true) as $supportedLocale) {
                $locale = $supportedLocale['locale'];

                $faker = Factory::create($locale);
                $categoryTitle = $faker->name . ' on lang - ' .$locale . time().rand(1111,9999);

                $categoryDataMultilanguage[$locale] = [
                    'title'=> $categoryTitle,
                    'url'=> str_slug($categoryTitle),
                    'description'=> 'Category description ' . $locale . ' on lang' . time().rand(1111,9999),
                ];
            }

            $browser->visit(route('admin.category.create'));

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $browser->click('#category-create-in-blog-link');
            $browser->pause(400);
            $browser->waitForText('Category name');

            $browser->within(new AdminCategoryMultilanguage, function ($browser) use ($categoryDataMultilanguage) {
                foreach($categoryDataMultilanguage as $locale=>$categoryData) {
                    $browser->fillTitle($categoryData['title'], $locale);
                    $browser->fillDescription($categoryData['description'], $locale);
                }
            });

            $browser->click('#category-dropdown-holder');
            $browser->pause(300);

            $category = 'Blog';

            $script = '
            if ($(".category-parent-selector .mw-tree-item-title:contains(\''.$category.'\')").parent().parent().parent().hasClass(\'selected\') == false) {
                $(".category-parent-selector .mw-tree-item-title:contains(\''.$category.'\')").parent().click();
            }';

            $browser->script($script);
            $browser->pause(600);


            $browser->scrollTo('.js-edit-category-show-more');

            $browser->pause(1300);
            $browser->click('.js-edit-category-show-more');
            $browser->pause(1300);

            $browser->within(new AdminCategoryMultilanguage, function ($browser) use ($categoryDataMultilanguage) {
                foreach($categoryDataMultilanguage as $locale=>$categoryData) {
                    $browser->fillUrl($categoryData['url'], $locale);
                }
            });
            $browser->pause(200);



            $browser->scrollTo('.admin-thumbs-holder');
            // add images to gallery
            $browser->within(new AdminContentImageAdd, function ($browser) {
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img1.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img2.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img3.jpg');
            });



            $browser->scrollTo('.js-category-save');
            $browser->pause(500);
            $browser->click('.js-category-save');
            $browser->pause(3500);


            $findCategory = Category::where('title', $categoryDataMultilanguage['en_US']['title'])->first();
            $browser->visit(route('admin.category.edit', $findCategory->id));

            $this->assertEquals($categoryDataMultilanguage['en_US']['description'], $findCategory->description);

            foreach($categoryDataMultilanguage as $locale=>$fields) {
                foreach($fields as $fieldKey=>$fieldValue) {
                    $this->assertEquals($fieldValue, $findCategory->multilanguage_translatons[$locale][$fieldKey]);
                }
            }

            $browser->waitForLocation(route('admin.category.edit', $findCategory->id));
            $browser->pause(1000);

            $browser->waitForText('Category name');

            $browser->visit(category_link($findCategory->id));

             foreach($categoryDataMultilanguage as $locale=>$categoryData) {

                $browser->within(new FrontendSwitchLanguage, function ($browser) use($locale) {
                    $browser->switchLanguage($locale);
                });

                $browser->pause(3000);
                $currentUrl = $browser->driver->getCurrentURL();
                $this->assertTrue(strpos($currentUrl, $categoryData['url']) !== false);

                $browser->assertSee($categoryData['title']);

            }

        });

    }
}
