<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\DuskTestCase;

class AdminAddCategoryTest extends DuskTestCase
{

    public function testAddPost()
    {
        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $categoryTitle = 'This is the category title'.time();
            $categoryDescription = 'This is the category description'.time();

            $browser->visit(route('admin.category.create'));

            $browser->type('#content-title-field', $categoryTitle);
            $browser->type('#description', $categoryDescription);

            $browser->click('@category-save');

        });

    }
}
