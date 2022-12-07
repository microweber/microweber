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


         PHPUnit::assertFalse(!is_null($browser->element('body')));
         PHPUnit::assertFalse(!is_null($browser->element('html')));
         PHPUnit::assertFalse(!is_null($browser->element('head')));




        $url = $browser->driver->getCurrentURL();
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
                PHPUnit::assertFalse($output[0], 'The module should not contain edit field with field="content" and rel="content" on url: '.$url);
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
            PHPUnit::assertTrue($output[0],'Edit fields must have rel and field attributes on url: '.$url);

        }

        $elements = $browser->elements('script');

        foreach ($elements as $key => $elem) {
            $output = $browser->script("
            var scriptElement = $('script').eq(" . $key . ")[0];

                var scriptElementValidation = false;
                 if (!mw.tools.isEditable(scriptElement))
                  {
                     scriptElementValidation = true;
                 } else {
                    $('script').eq(" . $key . ").css('background', 'red');
                    console.log($('script').eq(" . $key . "));
                }

               return scriptElementValidation;
            ");



            PHPUnit::assertTrue($output[0],'script elements should be outside .edit fields on url: '.$url);

        }



        $elements = $browser->elements('.allow-drop');
        foreach ($elements as $key => $elem) {
            $output = $browser->script("
            var allowDropElement = $('.allow-drop').eq(" . $key . ");

                var dropElementValidation = false;
                if (!$(allowDropElement).hasClass('nodrop')) {
                    dropElementValidation = true;
                } else {
                    $('.allow-drop').eq(" . $key . ").css('background', 'red');
                    console.log($('.allow-drop').eq(" . $key . "));
                }

               return dropElementValidation;
            ");



            PHPUnit::assertTrue($output[0],'Edit field with allow-drop must not have nodrop class on url: '.$url);

        }

        $elements = $browser->elements('.module');
        foreach ($elements as $key => $elem) {
            $output = $browser->script("
            var moduleElement = $('.module').eq(" . $key . ").hasClass('edit');
            if (moduleElement) {
                $('.module').eq(" . $key . ").css('background', 'red');
                console.log($('.module').eq(" . $key . "));
            }
            return moduleElement;
            ");
           /* if ($output[0]) {
                die();
            }*/
            PHPUnit::assertFalse($output[0],'Module should not have .edit class on url: '.$url);
        }


        // this will catch broken javascripts on page
        $consoleLog = $browser->driver->manage()->getLog('browser');
        $errorStrings = ['Uncaught SyntaxError'];
        $skipErrorStrings = [
            'Blocked attempt to show',
            'userfiles/install_log.txt?',
            'https://platform.twitter.com',
            'https://fonts.cdnfonts.com',
            'https://www.googletagmanager.com/a?id=UA-12345678',
            'https://www.google-analytics.com',
            'https://fonts.cdnfonts.com',
            'Failed to load resource: net::ERR_CONTENT_LENGTH_MISMATCH',
            'log.txt - Failed to load resource: the server responded with a status of 404 (Not Found)',
            'Cannot read properties of undefined (reading \'done\')',
            'chromewebdata',
        ];
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

            $html = $browser->script("return document.documentElement.outerHTML");
            file_put_contents(storage_path('logs/error.html'), $html);

            throw new \Exception(print_r($findedErrors, true));
        }

        PHPUnit::assertEmpty($findedErrors);

        // Check for dump and prints
        $printStrings = [
            'array (',
            'Array (',
            'stdClass Object',
            'stdClass',
            'Sfdump',
            'sf-dump',
            'xdebug-var-dump',
            'sf-dump-public',
        ];
        $html = $browser->script("if (typeof $ == 'function') { return $('body').html() }");
        if (!empty($html)) {
            foreach ($html as $htmlString) {
                foreach ($printStrings as $printString) {
                    PHPUnit::assertFalse(str_contains($htmlString, $printString), 'DUMP found. It should not have print_r/var_dump or dump: ' . $printString. ' on url: ' . $url);
                }
            }
        }

        // Check for parser errors
        $errorStrings = ['mw_replace_back','tag-comment','mw-unprocessed-module-tag','parser_','inner-edit-tag'];
        $html = $browser->script("if (typeof $ == 'function') { return $('body').html() }");
        if (!empty($html)) {
            foreach ($html as $htmlString) {
                foreach ($errorStrings as $errorString) {
                    PHPUnit::assertFalse(str_contains($htmlString, $errorString), 'Parser error found. It should not have tag: ' . $errorString. ' on url: ' . $url);
                }
            }
        }
    }
}
