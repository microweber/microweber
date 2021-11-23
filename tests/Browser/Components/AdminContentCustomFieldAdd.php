<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminContentCustomFieldAdd extends BaseComponent
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

    public function addCustomField(Browser $browser, $name) {

        $browser->scrollTo('@show-custom-fields');
        $browser->pause(1000);

        if(!$browser->driver->findElement(WebDriverBy::cssSelector('@add-custom-field'))->isDisplayed()) {
            $browser->click('@show-custom-fields');
        }

        // add custom field price
        $browser->waitForText('Add new field');
        $browser->click('@add-custom-field');
        $browser->pause(3000);
        $browser->waitForText($name);
        $browser->click('@add-custom-field-'.strtolower($name));
        $browser->pause(2000);

    }
}
