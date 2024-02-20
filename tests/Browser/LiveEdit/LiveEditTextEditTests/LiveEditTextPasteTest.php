<?php

namespace Tests\Browser\LiveEdit\LiveEditTextEditTests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\LiveEditSaveButton;
use Tests\DuskTestCase;

class LiveEditTextPasteTest extends DuskTestCase
{


    public function testLiveEditPasteInHeading()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            $this->browse(function (Browser $browser) {
                $this->grantPermission($browser, ["clipboardReadWrite", "clipboardSanitizedWrite"]);
             });

            $params = array(
                'title' => 'My new page for typing ' . time(),
                'content_type' => 'page',
                'content' => '
                <div class="container-fluid col-sm-12 mx-auto mx-lg-0  ">
                   <h6 class="font-weight-normal" id="my-text-to-copy-from">Copy text from here</h6>
                   <h6 class="font-weight-normal" id="my-text-to-paste-to">Paste text here</h6>
                </div>
            ',
                'subtype' => 'static',
                'is_active' => 1,
            );

            $saved_id = save_content($params);
            $link = content_link($saved_id);

            $browser->visit($link . '?editmode=y');
            $browser->pause(4000);

            $browser->waitFor('#live-editor-frame', 30)
                ->withinFrame('#live-editor-frame', function ($browser) {
                    $browser->pause(1000);
                    $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                        $browser->validate();
                    });

                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            $browser->doubleClick('#my-text-to-copy-from');
            $browser->pause(100);
            $browser->driver->executeScript('
    var div = document.getElementById("my-text-to-copy-from");
    var tempInput = document.createElement("input");
    tempInput.value = div.innerText;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
');
             $browser->pause(400);
            $output = $browser->script("

            var myTextElement = navigator.clipboard.readText();

            return myTextElement;

        ");


            $this->assertEquals( $output[0], 'Copy text from here');

            $browser->click('#my-text-to-paste-to');
            $browser->pause(100);
            $browser->keys('#my-text-to-paste-to', [WebDriverKeys::CONTROL, 'v']);

            $innerText = $browser->element('#my-text-to-paste-to')->getText();

            $this->assertEquals( $innerText, 'Paste text hereCopy text from here');

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });

            $browser->switchFrameDefault();
            $browser->pause(1000);

            $browser->visit($link . '?editmode=n');
            $browser->pause(1000);
            $browser->waitForText('Paste text hereCopy text from here', 30);

        });
    }


}
