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

    public function addCustomField(Browser $browser, $key, $value) {

        $browser->script("$('html, body').animate({ scrollTop: $('.js-custom-fields-card-tab').offset().top -80 }, 0);");
        $browser->pause(1000);

        if(!$browser->driver->findElement(WebDriverBy::cssSelector('#custom-fields-settings'))->isDisplayed()) {
             $browser->script("document.querySelector('.js-show-custom-fields').scrollIntoView({block: 'start', inline: 'nearest',behavior :'auto'});");


            $browser->script("$('.js-show-custom-fields').click()");
            $browser->pause(3000);
        }

        // add custom field price
        $browser->waitForText('Add new field');
         $browser->script("document.querySelector('.js-add-custom-field').scrollIntoView({block: 'start', inline: 'nearest',behavior :'auto'});");

        $browser->click('.js-add-custom-field');
        $browser->pause(3000);
        $browser->waitForText('Add new fields');
        $browser->waitForText($value);
        $browser->click('.js-add-custom-field-'.$key);
        $browser->pause(4000);

    }
}
