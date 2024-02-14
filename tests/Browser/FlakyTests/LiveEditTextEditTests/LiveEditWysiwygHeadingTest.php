<?php

namespace Tests\Browser\FlakyTests\LiveEditTextEditTests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
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
    }

    public function testLiveEditTypingMakeH2()
    {

        $this->performFormatOnElements('h2');
    }

    public function testLiveEditTypingMakeH3()
    {

        $this->performFormatOnElements('h3');
    }

    public function testLiveEditTypingMakeH4()
    {

        $this->performFormatOnElements('h4');
    }

    public function testLiveEditTypingMakeH5()
    {

        $this->performFormatOnElements('h5');
    }

    public function testLiveEditTypingMakeH6()
    {

        $this->performFormatOnElements('h6');
    }


    public function testLiveEditTypingMakeParagraph()
    {
        $this->performFormatOnElements('p');
    }

    public function testLiveEditTypingMakePreformatted()
    {
        $this->performFormatOnElements('pre');
    }
    public function testLiveEditTypingMakeDiv()
    {
        $this->performFormatOnElements('div');
    }
    private function performFormatOnElements($format)
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl, $format) {
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
                   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text for format</font></h6>
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
