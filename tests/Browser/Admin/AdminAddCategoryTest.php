<?php

namespace Tests\Browser\Admin;

use Laravel\Dusk\Browser;
use Modules\Category\Models\Category;
use Modules\Content\Tests\Unit\TestHelpers;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\EnvCheck;
use Tests\DuskTestCase;

class AdminAddCategoryTest extends DuskTestCase
{

    use TestHelpers;

    public function testAddCategory()
    {
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

        $environment = app()->environment();

        $title='Blog for cat '.uniqid();
        $pageId = $this->_generatePage($title, $title);

        $this->browse(function (Browser $browser) use ($environment,$title) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->pause(1000);
            $browser->within(new EnvCheck, function ($browser) {
                $browser->checkEnvName($browser);
            });
            $browser->pause(1000);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $categoryTitle = 'this is the category title' . time();
            $categoryDescription = 'this is the category description' . time();

            $browser->visit(route('admin.category.create'));

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            $browser->click('#category-create-in-blog-link');

            $browser->pause(1000);
            $browser->waitForText('Category name', 30);

            $browser->type('#content-title-field', $categoryTitle);

            $browser->click('#category-dropdown-holder');
            $browser->pause(300);

            $category = $title;

            $script = '
            if ($(".category-parent-selector .mw-tree-item-title:contains(\'' . $category . '\')").parent().parent().parent().hasClass(\'selected\') == false) {
                $(".category-parent-selector .mw-tree-item-title:contains(\'' . $category . '\')").parent().click();
            }';

            $browser->script($script);
            $browser->pause(600);

            $browser->type('#description', $categoryDescription);

            // add images to gallery
            $browser->within(new AdminContentImageAdd, function ($browser) {
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img1.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img2.jpg');
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img3.jpg');
            });


            $browser->scrollTo('#content-title-field');
            $browser->pause(2500);

            $browser->scrollTo('[dusk="category-save"]');
            $browser->pause(2500);

            $browser->click('@category-save');
            $browser->pause(4000);


            $findCategory = Category::where('title', $categoryTitle)->first();

            $this->assertEquals($categoryDescription, $findCategory->description);

            $browser->waitForLocation(route('admin.category.edit', $findCategory->id));
            $browser->pause(1000);

            $browser->assertSee($categoryTitle);
            $browser->waitForText('Category name');
            $browser->assertSee('Save');

        });

    }
}
