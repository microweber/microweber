<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminTemplate extends BaseComponent
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

    public function changeTemplate(Browser $browser, $name = false)
    {

        $browser->visit(admin_url() . 'settings?group=template');
        $browser->pause(1000);
        $browser->waitForText('Template name');
        $browser->pause(1000);

        $browser->select('.mw-edit-page-template-selector', $name);

        $browser->pause(2000);
        $browser->click('.mw-action-change-template');

        $browser->pause(2000);
        $browser->assertVisible('#js-template-import-type-delete');
        $browser->click('#js-template-import-type-full');
        $browser->pause(1000);
        $browser->click('.js-button-change-template');

        $browser->waitForText('Template has been changed.',60);
     //   $browser->assertSee('Template settings are saved');

    }
}
