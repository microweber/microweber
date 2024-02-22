<?php

namespace Tests\Browser\LiveEdit\LiveEditTextEditTests;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\LiveEditSaveButton;
use Tests\Browser\Components\LiveEditWaitUntilLoaded;
use Tests\Browser\Components\WysiwygSmallEditorButtonClick;
use Tests\DuskTestCase;

class LiveEditWysiwygAlignTest extends DuskTestCase
{


    public function testLiveEditAlignCenter()
    {
        $this->performAlignOnElements('center');
        $this->performAlignOnElements('center',true);
    }

    public function testLiveEditAlignLeft()
    {
        $this->performAlignOnElements('left');
        $this->performAlignOnElements('left',true);
    }

    public function testLiveEditAlignRight()
    {
        $this->performAlignOnElements('right');
        $this->performAlignOnElements('right',true);
    }

    private function performAlignOnElements($alignType,$onSelectedText = false)
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl, $alignType,$onSelectedText) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $buttonText = 'Align center';
            $expectedTextAlign = 'center';
            if ($alignType == 'left') {
                $buttonText = 'Align left';
                $expectedTextAlign = 'left';
            }
            if ($alignType == 'right') {
                $buttonText = 'Align right';
                $expectedTextAlign = 'right';
            }

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $params = array(
                'title' => 'My new page for typing ' . time(),
                'content_type' => 'page',
                'content' => '
                <div class="container-fluid col-sm-12 mx-auto mx-lg-0  ">
                   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text for align</font></h6>
                </div>
            ',
                'subtype' => 'static',
                'is_active' => 1,
            );

            $saved_id = save_content($params);
            $link = content_link($saved_id);

            $browser->visit($link . '?editmode=y');

            $browser->within(new LiveEditWaitUntilLoaded(), function ($browser) {
                $browser->waitUntilLoaded();
            });


            $browser->waitFor('#live-editor-frame', 30)
                ->withinFrame('#live-editor-frame', function ($browser) {
                    $browser->pause(1000);
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
            $browser->pause(1000);
            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#my-text-here');

            if ($onSelectedText ) {
                $browser->script("var myTextElement = document.getElementById('my-text-here');
                      var range = document.createRange();
                      range.selectNodeContents(myTextElement);
                      window.getSelection().removeAllRanges();
                      window.getSelection().addRange(range);");
            }
            $editorComponent = new WysiwygSmallEditorButtonClick();
            $editorComponent->clickEditorButton($browser, $buttonText);

            $browser->pause(500);

            //check if aligned
            $output = $browser->script("
            var  isTrue = window.getComputedStyle(document.getElementById('my-text-parent')).textAlign
 === '" . $expectedTextAlign . "';
            return isTrue;
        ");
            $this->assertEquals(true, $output[0], 'The element must be aligned: ' . $expectedTextAlign);

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();

            $browser->visit($link . '?editmode=n');
            $browser->pause(1000);

            $output = $browser->script("
            var  isTrue = window.getComputedStyle(document.getElementById('my-text-parent')).textAlign
 === '" . $expectedTextAlign . "';
            return isTrue;
        ");
            $this->assertEquals(true, $output[0], 'The element must be aligned center');


        });

    }


}
