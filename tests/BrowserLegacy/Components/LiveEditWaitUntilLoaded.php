<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

class LiveEditWaitUntilLoaded extends BaseComponent
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

    public function waitUntilLoaded(Browser $browser)
    {

        $browser->switchFrameDefault();
      //  $browser->waitForEvent('liveEditLoaded', 'document',30);
        $browser->waitFor('#live-edit-app.live-edit-loaded',30);

    }
}
