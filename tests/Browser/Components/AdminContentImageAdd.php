<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminContentImageAdd extends BaseComponent
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

    public function addImage(Browser $browser, $image)
    {
        $browser->pause(1000);
        $browser->scrollTo('.mw-uploader-input');
        $browser->attach('.mw-filepicker-desktop-type-big input.mw-uploader-input', $image);
        $browser->waitForText('Pictures settings are saved',30);
        $browser->pause(3000);


    }
}
