<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class FrontendSwitchLanguage extends BaseComponent
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

    public function switchLanguage(Browser $browser, $locale)
    {

        //
        if (!$browser->element('#switch_language_ul')) {
            if ($browser->element('#header-layout')) {
                // must enable multilang in header-layout first
                $option = array();
                $option['option_value'] = '1';
                $option['option_key'] = 'header_top_menu';
                $option['option_group'] = 'header-layout';
                save_option($option);

                $option = array();
                $option['option_value'] = '1';
                $option['option_key'] = 'multilanguage';
                $option['option_group'] = 'header-layout';
                save_option($option);
                $browser->script('location.reload();');

            }
        }


        $browser->pause(300);
        $browser->click('.module-multilanguage .mw-dropdown-default');
        $browser->pause(400);
        $browser->script('$(\'li[data-value="'.$locale.'"]\', ".module-multilanguage").click()');
        $browser->waitForReload(false, 6000);

    }
}
