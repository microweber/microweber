<?php

namespace Tests\Browser\Components;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use PHPUnit\Framework\Assert as PHPUnit;

class ChekForJavascriptErrors extends BaseComponent
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

    public function validate(Browser $browser)
    {
        $elements = $browser->elements('.module');
        foreach ($elements as $key => $elem) {

            $randClass = 'js-rand-validation-element-'.time().rand(1111,9999);
            $browser->script("return $('.module').eq(" . $key . ").find('.edit').addClass('$randClass')");

            $moduleElements = $browser->elements('.'.$randClass);
            foreach ($moduleElements as $mKey => $mElem) {
                // The module should not contain edit field with field="content" and rel="content"
                $output = $browser->script("

                var editElementValidation = false;
                if ($('.$randClass').eq(" . $mKey . ").attr('rel')=='content' && $('.$randClass').eq(" . $mKey . ").attr('field')=='content') {
                    editElementValidation = true;
                     $('.$randClass').eq(" . $mKey . ").css('background', 'red');
                    console.log($('.$randClass').eq(" . $mKey . "));
                }

                return editElementValidation;
                ");
                PHPUnit::assertFalse($output[0]);
            }
        }

        $elements = $browser->elements('.edit');
        foreach ($elements as $key => $elem) {

            // Must have rel and field attribute
            $output = $browser->script("

            var editElementValidation = false;
            if ($('.edit').eq(" . $key . ").attr('rel') && $('.edit').eq(" . $key . ").attr('field')) {
                editElementValidation = true;
            } else {
                 $('.edit').eq(" . $key . ").css('background', 'red');
                console.log($('.edit').eq(" . $key . "));
            }

            return editElementValidation;
            ");
            PHPUnit::assertTrue($output[0]);

        }

        $elements = $browser->elements('.allow-drop');
        foreach ($elements as $key => $elem) {
            $output = $browser->script("
            var allowDropElement = $('.allow-drop').eq(" . $key . ");

                var dropElementValidation = false;
                if (mw.tools.parentsOrCurrentOrderMatchOrOnlyFirstOrNone(allowDropElement[0].parentNode, ['nodrop','allow-drop'])) {
                    dropElementValidation = true;
                } else {
                    $('.allow-drop').eq(" . $key . ").css('background', 'red');
                    console.log($('.allow-drop').eq(" . $key . "));
                }

               return dropElementValidation;
            ");

            if ($output[0] == false) {
                die();
            }

            PHPUnit::assertTrue($output[0]);

        }

        $elements = $browser->elements('.module');
        foreach ($elements as $key => $elem) {
            $output = $browser->script("return $('.module').eq(" . $key . ").hasClass('edit')");
            PHPUnit::assertFalse($output[0]);
        }

        // this will catch broken javascripts on page
        $consoleLog = $browser->driver->manage()->getLog('browser');
        $errorStrings = ['Uncaught SyntaxError'];
        $skipErrorStrings = ['Blocked attempt to show'];
        $findedErrors = [];
        if (!empty($consoleLog)) {
            foreach ($consoleLog as $log) {

                $skip = false;
                foreach ($skipErrorStrings as $errorString) {
                    if (strpos($log['message'], $errorString) !== false) {
                        $skip = true;
                    }
                }

                if ($skip) {
                    continue;
                }

                if ($log['level'] == 'SEVERE') {
                    $findedErrors[] = $log;
                }

                if ($log['level'] == 'INFO') {
                    foreach ($errorStrings as $errorString){
                        if (strpos($log['message'], $errorString) !== false) {
                            $findedErrors[] = $log;
                        }
                    }
                }

            }
        }

        if (!empty($findedErrors)) {
            $findedErrors[] = 'page url: ' . $browser->driver->getCurrentURL();
            throw new \Exception(print_r($findedErrors, true));
        }

        PHPUnit::assertEmpty($findedErrors);

        // Check for parser errors
        $errorStrings = ['mw_replace_back','tag-comment','mw-unprocessed-module-tag','parser_'];
        $html = $browser->script("if (typeof $ == 'function') { return $('body').html() }");
        if (!empty($html)) {
            foreach ($html as $htmlString) {
                foreach ($errorStrings as $errorString) {
                    PHPUnit::assertFalse(str_contains($htmlString, $errorString));
                }
            }
        }
    }
}
