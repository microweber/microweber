<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminContentCategorySelect extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '#mw-admin-container';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertVisible($this->selector());
    }

    /**
     * Get the element shortcuts for the component.
     *
     * @return array
     */
    public function elements()
    {
        return [];
    }

    public function selectCategory(Browser $browser, $category)
    {
        $browser->pause(1000);

        $browser->script("document.querySelector('#show-categories-tree-wrapper').scrollIntoView({block: 'end', inline: 'nearest',behavior :'auto'});");
        $browser->pause(1000);

        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#show-categories-tree'))->isDisplayed()) {
            $browser->click('button.js-show-categories-tree-btn');
        }


         $browser->pause(1000);

        $script = '
            if ($("#show-categories-tree .mw-tree-item-title:contains(\''.$category.'\')").parent().parent().parent().hasClass(\'selected\') == false) {
                $("#show-categories-tree .mw-tree-item-title:contains(\''.$category.'\')").parent().click();
            }
        ';

        $browser->script($script);

        $browser->pause(1000);

    }

    public function selectSubCategory(Browser $browser, $category, $subCategory)
    {
        $browser->pause(1000);
        $browser->script("$('html, body').animate({ scrollTop: $('.js-sidebar-categories-card').offset().top -80 }, 0);");

        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#show-categories-tree'))->isDisplayed()) {
            $browser->click('.js-show-categories-tree-btn');
        }

        $browser->pause(1000);
        $browser->script('$("#show-categories-tree .mw-tree-item-title:contains(\''.$category.'\')").parent().parent().find(\'.mw-tree-toggler\').click();');
        $browser->pause(1000);
        $browser->script('$("#show-categories-tree li:contains(\''.$category.'\')").find("li:contains(\''.$subCategory.'\')").find(\'.mw-tree-item-content\').click();');
    }
}
