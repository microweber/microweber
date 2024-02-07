<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

class WysiwygSmallEditorButtonClick extends BaseComponent
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

    public function clickEditorButton(Browser $browser, $button = 'Bold')
    {
        // Escape any special characters in the button name
        $button = addslashes($button);

        $selector = ".mw-small-editor [data-tooltip='{$button}'] + .mw-editor-group-button-caret";

        $randId = 'id-rand-dusk-'.rand(1, 1000).uniqid();

        $browser->driver->executeScript(
            <<<JS
        //var elEditor = document.querySelector(".mw-small-editor [data-tooltip='{$button}'] + .mw-editor-group-button-caret").id = '{$randId}'

        var checkIfinDD = document.querySelector(".mw-small-editor [data-tooltip='Align center']").parentElement
        if(checkIfinDD){
            //check for class mw-bar-control-item-group-contents
            var isinDD = checkIfinDD.closest(".mw-bar-control-item-group-contents").parentElement;
            if (isinDD) {
               //chec if is caret
            var isinDDWithCaret = checkIfinDD.closest(".mw-bar-control-item-group-contents.mw-editor-group-button-caret");
            if(isinDDWithCaret){
                isinDDWithCaret.id = '{$randId}';

             }

            }
        }

JS
        );



        if ($browser->element('#'.$randId)) {
            $browser->click('#'.$randId);
        }

        $browser->pause(10000);

        $selector = ".mw-small-editor .mw-editor-controller-button[data-tooltip='{$button}']";
        if ($browser->element($selector)) {
            $browser->click($selector);
        }



//        $browser->driver->executeScript(
//            <<<JS
//        var elEditor = document.querySelector(".mw-small-editor [data-tooltip='{$button}']");
//        if (elEditor) {
//            var isinDD = elEditor.closest(".mw-editor-group-button").nextSibling;
//            if (isinDD) {
//                isinDD.click();
//            }
//        }
//        return true;
//JS
//        );
    }

}
