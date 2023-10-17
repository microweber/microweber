<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use PHPUnit\Framework\Assert as PHPUnit;

class LiveEditSaveButton extends BaseComponent
{
    public static $increment = 0;
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

    public function clickSaveButton(Browser $browser)
    {
        $browser->switchFrameDefault();
        $browser->click('#save-button');
    //    $browser->waitUntilMissing('.live-edit-toolbar-buttons.btn-loading',10);
      $browser->waitForText('Page saved successfully.',30);


        $browser->pause(4000);

    }


}
