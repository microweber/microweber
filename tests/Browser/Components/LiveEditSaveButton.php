<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;

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
      $browser->waitForText('Page saved successfully.',60);


        $browser->pause(1000);

    }


}
