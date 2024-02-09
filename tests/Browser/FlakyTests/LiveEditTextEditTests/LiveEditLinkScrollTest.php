<?php

namespace Tests\Browser\FlakyTests\LiveEditTextEditTests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\LiveEditSaveButton;
use Tests\Browser\Components\LiveEditWaitUntilLoaded;
use Tests\DuskTestCase;

class LiveEditLinkScrollTest extends DuskTestCase
{


    public function testLiveEditLinkScroll()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {
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

<a href="#mw@contact-form-to-scroll-to">
                   <b class="font-weight-normal" id="click-to-scroll-to">
Click to scroll to
</b>
 </a>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

<module type="contact_form" id="contact-form-to-scroll-to" />

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
            $browser->pause(1000);

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


            $browser->click('#click-to-scroll-to');
            $browser->pause(2000);
            $output = $browser->script("

            //is in viewport

            var el = document.getElementById('contact-form-to-scroll-to');
            var rect = el.getBoundingClientRect();
            var isVisible = (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
                rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
            );
            return isVisible;



        ");

            $this->assertTrue($output[0], 'Element is not in viewport');

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });

            $browser->switchFrameDefault();
            $browser->pause(1000);

            $browser->visit($link . '#mw@contact-form-to-scroll-to');
            $browser->pause(2000);


            $output = $browser->script("

            //is in viewport

            var el = document.getElementById('contact-form-to-scroll-to');
            var rect = el.getBoundingClientRect();
            var isVisible = (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /*or $(window).height() */
                rect.right <= (window.innerWidth || document.documentElement.clientWidth) /*or $(window).width() */
            );
            return isVisible;



        ");

            $this->assertTrue($output[0], 'Element is not in viewport');


        });
    }


}
