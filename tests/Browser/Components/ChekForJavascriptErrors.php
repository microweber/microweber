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
       // $browser->pause(3000);

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
