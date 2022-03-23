<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class LiveEditSwitchLanguage extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return '.module-multilanguage';
      //  return '.module-multilanguage-change-language';
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

    public function switchLanguage(Browser $browser, $locale)
    {
        $browser->pause(300);
        $browser->click('.mw-dropdown-default');
        $browser->pause(400);
        $browser->script('$(\'li[data-value="'.$locale.'"]\').click()');
        $browser->waitForReload(false, 6000);
    }
}
