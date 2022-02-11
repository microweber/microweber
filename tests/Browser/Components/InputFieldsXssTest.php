<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use PHPUnit\Framework\Assert as PHPUnit;

class InputFieldsXssTest extends BaseComponent
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

    public function fill(Browser $browser)
    {

        $browser->pause(1000);

        $jquery = $browser->script("return (typeof $ !== 'undefined')");
        if (isset($jquery[0]) && $jquery[0] == false) {
            return;
        }

       // $browser->script('$( "[data-toggle=\'collapse\']").each(function() { if ($(this).hasClass(\'active\') == false) {$(this).click()} });');

        $browser->script('$("input[type=text]:visible").addClass("js-input-type-fields")');
        $browser->script('$("input[type=search]:visible").addClass("js-input-type-fields")');
        $browser->script('$("textarea:visible").addClass("js-input-type-fields")');
        $browser->pause(1000);

        $elements = $browser->elements('.js-input-type-fields');
        foreach ($elements as $key=> $element) {

            $browser->script('$(".js-input-type-fields").eq('.$key.').addClass("js-input-type-fields-'.$key.'")');
            $browser->pause(1000);

            $elementClass = '.js-input-type-fields-'.$key;

            $browser->pause(1000);
            $browser->script("$('html, body').animate({ scrollTop: $('$elementClass').first().offset().top - 50 }, 0);");
            $browser->pause(1000);

            echo  'page url: ' . $browser->driver->getCurrentURL() . PHP_EOL;
            $browser->type($elementClass, '"><img src=x onerror=confirm(document.domain)>', '{enter}');

            $browser->pause(3000);
        }

        $browser->script('$("input:visible").submit()');
        $browser->pause(3000);

    }
}
