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

        $selector = ".mw-small-editor [data-tooltip='{$button}'] + mw-editor-group-button-caret";

        if ($browser->element($selector)) {
            $browser->click($selector);
        }

        /*.mw-small-editor [data-tooltip='Align left'] + mw-editor-group-button-caret
        */


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
