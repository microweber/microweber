<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Post\Models\Post;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminAddCategoryTest extends DuskTestCase
{

    public function testAddCategory()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $categoryTitle = 'This is the category title'.time();
            $categoryDescription = 'This is the category description'.time();

            $browser->visit(route('admin.category.create'));

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->type('#content-title-field', $categoryTitle);

            $browser->click('#category-dropdown-holder');
            $browser->pause(300);

            $category = 'Blog';

            $script = '
            if ($(".category-parent-selector .mw-tree-item-title:contains(\''.$category.'\')").parent().parent().parent().hasClass(\'selected\') == false) {
                $(".category-parent-selector .mw-tree-item-title:contains(\''.$category.'\')").parent().click();
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

            $browser->scrollTo('@category-save');
            $browser->click('@category-save');
            $browser->pause(2000);

            $findCategory = Category::where('title', $categoryTitle)->first();

            $this->assertEquals($categoryDescription,$findCategory->description);

            $browser->waitForLocation(route('admin.category.edit', $findCategory->id));
            $browser->pause(1000);

            $browser->waitForText('Edit category');
            $browser->assertSee('Edit category');

        });

    }
}
