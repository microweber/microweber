<?php

namespace Tests\Browser\Components;

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
        $this->assertVisible($this->selector());
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
        $browser->click('.js-show-categories-tree-btn');

        $browser->pause(1000);
        $browser->script('$("#show-categories-tree .mw-tree-item-title:contains(\''.$category.'\')").parent().click();');
        $browser->pause(1000);

    }

    public function selectSubCategory(Browser $browser, $category, $subCategory)
    {
        $browser->pause(1000);
        $browser->click('.js-show-categories-tree-btn');
        $browser->pause(1000);
        $browser->script('$("#show-categories-tree .mw-tree-item-title:contains(\''.$category.'\')").parent().click();');
        $browser->pause(1000);
        $browser->script('$("#show-categories-tree .mw-tree-item-title:contains(\''.$category.'\')").parent().parent().find(\'.mw-tree-toggler\').click();');
        $browser->pause(1000);
        $browser->script('$("#show-categories-tree li:contains(\''.$category.'\')").find("li:contains(\''.$subCategory.'\')").find(\'.mw-tree-item-content\').click();');
    }
}
