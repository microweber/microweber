<?php

namespace Tests\Browser;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Post\Models\Post;
use Tests\Browser\Components\AdminCategoryMultilanguage;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminContentMultilanguage;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class AdminMultilanguageAddCategoryTest extends DuskTestCase
{

    public function testAddCategory()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new AdminContentMultilanguage, function ($browser) {
                $browser->addLanguage('bg_BG');
                $browser->addLanguage('en_US');
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


            // add images to gallery
            $browser->within(new AdminContentImageAdd, function ($browser) {
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img1.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img2.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img3.jpg');
            });

            $browser->click('@category-save');
            $browser->pause(2000);

            $findCategory = Category::where('title', $categoryDataMultilanguage['en_US']['title'])->first();

            $this->assertEquals($findCategory['en_US']['description'], $findCategory->description);

            foreach($categoryDataMultilanguage as $locale=>$categoryData) {
                foreach($categoryData as $dataKey=>$dataValue) {
                    $this->assertEquals($dataValue, $findCategory->multilanguage[$locale][$dataKey]);
                }
            }

            $browser->waitForLocation(route('admin.category.edit', $findCategory->id));
            $browser->pause(1000);

            $browser->waitForText('Edit category');
            $browser->assertSee('Edit category');

        });

    }
}
