<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use PHPUnit\Framework\Assert as PHPUnit;

class LiveEditModuleAdd extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '';
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {

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

    public function addModule(Browser $browser, $name)
    {
        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#mw-sidebar-modules-list'))->isDisplayed()) {
            $browser->script("$('.mw-lsmodules-tab').trigger('mousedown').trigger('mouseup').click()");
            $browser->pause(500);
        }

        $randClassFoundBeForeSearch = 'js-rand-liveeditrtest-randClassFoundBeForeSearch-' . time() . rand(1111, 9999);
        $browser->script("$('#mw-sidebar-modules-list').find('li.module-item:visible').addClass('$randClassFoundBeForeSearch')");


        $randClass = 'js-rand-liveeditrtest-' . time() . rand(1111, 9999);
        $browser->script("$('#mw-sidebar-search-input-for-modules').addClass('$randClass')");

        $browser->keys('.' . $randClass, $name);

        $browser->click('.'.$randClassFoundBeForeSearch);


    }
}
