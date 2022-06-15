<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class AdminMultilanguageFields extends BaseComponent
{
    /**
     * Get the root selector for the component.
     *
     * @return string
     */
    public function selector()
    {
        return false;
    }

    /**
     * Assert that the browser page contains the component.
     *
     * @param Browser $browser
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

    public function fillInput(Browser $browser, $key, $value, $locale)
    {
        $browser->pause(1000);
        $browser->select('#ml-input-' . $key . '-change', $locale);
        $browser->pause(1000);
        $browser->script("$('.js-input-group-" . $key . " .form-control:visible').val('" . $value . "')");
    }

    public function fillTextarea(Browser $browser, $key, $value, $locale)
    {
       // $randClass = $this->getRandClass();

     //   $browser->script('$(".js-ml-btn-tab-' . $key . '[lang=\'' . $locale . '\']").click();');
        //$browser->script('$(".js-ml-btn-tab-' . $key . '[lang=\'' . $locale . '\']").addClass(\'' . $randClass . '\');');
        //$browser->click('.'.$randClass);

        $browser->click('.js-ml-btn-tab-' . $key . '[lang="' . $locale . '"]');


        $browser->pause(2000);

        $randClass = $this->getRandClass();
        $browser->script("$('#ml-tab-content-" . $key . " .tab-pane:visible textarea').addClass('$randClass')");
        $browser->pause(2000);
        $browser->keys('.' . $randClass, $value);
        $browser->pause(1000);
    }

    public function fillMwEditor(Browser $browser, $key, $value, $locale)
    {
//        $randClass = $this->getRandClass();
//        $browser->script('$(".js-ml-btn-tab-' . $key . '[lang=\'' . $locale . '\']").addClass(\'' . $randClass . '\');');
//        $browser->click('.'.$randClass);

        $browser->click('.js-ml-btn-tab-' . $key . '[lang="' . $locale . '"]');


        //   $browser->script('$(".js-ml-btn-tab-' . $key . '[lang=\'' . $locale . '\']").click();');
        $browser->pause(2000);

        $randClass = $this->getRandClass();
        $browser->script("$('#ml-tab-content-" . $key . " .tab-pane:visible .mw-editor-area').addClass('$randClass')");
        $browser->pause(2000);
        $browser->keys('.' . $randClass, $value);
        $browser->pause(1000);
    }

    public $randIncrement = 1;

    public function getRandClass()
    {
        $this->randIncrement++;
        $randClass = 'js-rand-ml-' . time() . rand(1111, 9999) . $this->randIncrement;
        return $randClass;
    }
}
