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

    public function clickEditorFormatButton(Browser $browser, $format = 'h1')
    {
        $selector = ".mw-editor-controller-component-format";
        $browser->click($selector);
        $browser->pause(100);

        $selector = ".mw-editor-controller-component-format [data-value='{$format}']";
        $browser->click($selector);
        $browser->pause(100);


    }

    public function clickEditorButton(Browser $browser, $button = 'Bold')
    {
        // Escape any special characters in the button name
        $button = addslashes($button);

        $selector = ".mw-small-editor [data-tooltip='{$button}'] + .mw-editor-group-button-caret";

        $randId = 'id-rand-dusk-click-element-'.rand(1, 1000).uniqid();

        $browser->driver->executeScript(
            <<<JS
         var elEditor = document.querySelector(".mw-small-editor [data-tooltip='{$button}']");

var getCaretForComponent = node => {
    if(typeof node === 'string') {
        node = document.querySelector(node);
    }

    if(!node) return null;

    let dd = node.closest('.mw-bar-control-item-group');
    if(!dd) return null;
    return {
       dropdown: dd,
       caret: dd.querySelector('.mw-editor-group-button-caret')
     }
}

var isCaret = getCaretForComponent(elEditor);
if(isCaret && isCaret.caret){
    if(isCaret && isCaret.dropdown && isCaret.dropdown.classList.contains('active')){
        //isCaret.dropdown.classList.remove('active');
    } else {
        //isCaret.dropdown.classList.add('active');
            isCaret.caret.id = '{$randId}';
    }
}

JS
        );



        if ($browser->element('#'.$randId)) {
            $browser->click('#'.$randId);
        }

        $browser->pause(100);

        $selector = ".mw-small-editor .mw-editor-controller-button[data-tooltip='{$button}']";
        if ($browser->element($selector)) {
            $browser->click($selector);
        }



    }

}
