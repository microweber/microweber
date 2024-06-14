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

class LiveEditWysiwygHeadingTest extends DuskTestCase
{
    public function testLiveEditTypingMakeH1()
    {

        $this->performFormatOnElements('h1');
        $this->performFormatOnElements('h1' , true);
    }

    public function testLiveEditTypingMakeH2()
    {

        $this->performFormatOnElements('h2');
        $this->performFormatOnElements('h2' , true);
    }

    public function testLiveEditTypingMakeH3()
    {

        $this->performFormatOnElements('h3');
        $this->performFormatOnElements('h3' , true);
    }

    public function testLiveEditTypingMakeH4()
    {

        $this->performFormatOnElements('h4');
        $this->performFormatOnElements('h4' , true);
    }

    public function testLiveEditTypingMakeH5()
    {

        $this->performFormatOnElements('h5');
        $this->performFormatOnElements('h5' , true);
    }

    public function testLiveEditTypingMakeH6()
    {

        $this->performFormatOnElements('h6');
        $this->performFormatOnElements('h6' , true);
    }


    public function testLiveEditTypingMakeParagraph()
    {
        $this->performFormatOnElements('p');
        $this->performFormatOnElements('p' , true);
    }

    public function testLiveEditTypingMakePreformatted()
    {
        $this->performFormatOnElements('pre');
        $this->performFormatOnElements('pre' , true);

    }
    public function testLiveEditTypingMakeDiv()
    {
        $this->performFormatOnElements('div');
        $this->performFormatOnElements('div',true);
    }
    private function performFormatOnElements($format,$onSelectedText = false)
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl, $format,$onSelectedText) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });


            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $params = array(
                'title' => 'My new page for typing ' . time(),
                'content_type' => 'page',
                'content' => '
                <div class="container-fluid col-sm-12 mx-auto mx-lg-0  ">
                   <h6 class="font-weight-normal" id="my-text-parent">
                   <span id="my-text-here" style="color:#ff0000">Enter text for format</span>
                   </h6>
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
            $editorComponent->clickEditorFormatButton($browser, $format);


            // get tag name of my-text-parent

            $output = $browser->script("
            var  tagName = document.getElementById('my-text-parent').tagName;
            return tagName;
            ");
            $this->assertEquals(strtolower($format), strtolower($output[0]));

            $browser->pause(100);


            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();

            $browser->visit($link . '?editmode=n');
            $browser->pause(1000);
            $browser->assertSeeIn('#my-text-parent', 'Enter text for format');
            $output = $browser->script("
            var  tagName = document.getElementById('my-text-parent').tagName;
            return tagName;
            ");
            $this->assertEquals(strtolower($format), strtolower($output[0]));

        });

    }

}
