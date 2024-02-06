<?php

namespace Tests\Browser\FlakyTests\LiveEditTextEditTests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\LiveEditSaveButton;
use Tests\DuskTestCase;

class LiveEditWysysgTest extends DuskTestCase
{
    public function testLiveEditTypingInTextWithFont()
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
   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text here</font></h6>
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
                    $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                        $browser->validate();
                    });

                    $browser->pause(1000);
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#my-text-here');
            $browser->pause(1500);

            $browser->keys('#my-text-here', 'New text in my element');
            $browser->pause(200);

            $output = $browser->script("
            var  isTrue = document.querySelectorAll('#my-text-parent font').length === 1;
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'The element is not font');

            $output = $browser->script("
            var  isTrue = document.querySelectorAll('#my-text-parent').firstChild === document.querySelectorAll('#my-text-parent').lastChild;
            return isTrue;
            ");

            $this->assertEquals($output[0], true, 'The element must have one font child');

            $browser->pause(100);

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();

            $browser->visit($link . '?editmode=n');
            $browser->pause(1000);

        });
    }

    public function testLiveEditTypingInTextWithFontAndEnterKey()
    {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $params = array(
                'title' => 'My new page for typing ' . time(),
                'content_type' => 'page',
                'content' => '

<div class="container-fluid col-sm-12 mx-auto mx-lg-0  ">
   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text here and hit enter</font></h6>
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
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#my-text-here');
            $browser->pause(1500);

            $browser->keys('#my-text-here', ['New text in my element and hit enter', WebDriverKeys::ENTER]);


            $output = $browser->script("
            var  isTrue = document.querySelectorAll('[id=\"my-text-parent\"]').length === 1;
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'The element has only 1 id');
            $output = $browser->script("
            var  isTrue = document.querySelectorAll('[id=\"my-text-here\"]').length === 1;
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'The element must only have 1 id');


            $output = $browser->script("
            var  isTrue = document.getElementById('my-text-parent').nextElementSibling.nodeName === document.getElementById('my-text-parent').nodeName;
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'Next element is not the same as the parent');


            $browser->pause(100);


            $this->assertEquals($output[0], true, 'The element must have one font child');

            $browser->pause(100);

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();

            $browser->visit($link . '?editmode=n');
            $browser->pause(1000);


            $output = $browser->script("
            var  isTrue = document.querySelectorAll('[id=\"my-text-parent\"]').length === 1;
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'The element has only 1 id');
            $output = $browser->script("
            var  isTrue = document.querySelectorAll('[id=\"my-text-here\"]').length === 1;
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'The element must only have 1 id');


            $output = $browser->script("
            var  isTrue = document.getElementById('my-text-parent').nextElementSibling.nodeName === document.getElementById('my-text-parent').nodeName;
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'Next element is not the same as the parent');


        });
    }


    public function testLiveEditTypingInTextWithFontAndShiftEnterKeys()
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
                   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text here and hit enter</font></h6>
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
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#my-text-here');
            $browser->pause(1500);

            $browser->keys('#my-text-here', [WebDriverKeys::SHIFT, WebDriverKeys::ENTER]);

            $output = $browser->script("
            var isTrue = document.querySelectorAll('[id=\"my-text-parent\"]').length === 1;
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The element has only 1 id');

            $output = $browser->script("
            var isTrue = document.querySelectorAll('[id=\"my-text-here\"]').length === 1;
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The element must only have 1 id');

            $output = $browser->script("

            var myTextElement = document.getElementById('my-text-here');
            var containsBR = myTextElement.querySelector('br') !== null;

            return containsBR;
        ");


            $this->assertEquals($output[0], true, 'Next element must be BR');

            $this->assertEquals($output[0], true, 'The element must have one font child');


            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();

            $browser->visit($link . '?editmode=n');
            $browser->pause(1000);

            $output = $browser->script("
            var isTrue = document.querySelectorAll('[id=\"my-text-parent\"]').length === 1;
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The element has only 1 id');

            $output = $browser->script("
            var isTrue = document.querySelectorAll('[id=\"my-text-here\"]').length === 1;
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The element must only have 1 id');

            $output = $browser->script("

            var myTextElement = document.getElementById('my-text-here');
            var containsBR = myTextElement.querySelector('br') !== null;

            return containsBR;
        ");
            $this->assertEquals($output[0], true, 'Next element is not the same as the parent');
        });
    }

    public function testLiveEditTypingTestBackspaceKey()
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
                   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text here and hit enter</font></h6>

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
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#my-text-here');
            $browser->pause(1500);

            //   $browser->keys('#my-text-here', 'New text in my element');
            $browser->pause(100);
            //  $browser->keys('#my-text-here', [ WebDriverKeys::ENTER, WebDriverKeys::BACKSPACE]);
            $browser->keys('#my-text-here', ['New text in my element', WebDriverKeys::ENTER]);
            $browser->pause(400);

            $browser->keys('#my-text-here', [WebDriverKeys::BACKSPACE]);


            $output = $browser->script("

            var myTextElementHtml = document.getElementById('my-text-here').innerHTML
            return myTextElementHtml;
        ");

            $browser->pause(400);
            $this->assertEquals('Enter text hereNew text in my element and hit enter', $output[0], 'The text must be the same');





        });
    }
    public function testLiveEditTypingTestBackspaceKeyWithDiferentFonts()
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



                   <div id="another-text">

                   <h5>
                        <font color="red">Red text</font>
                   </h5>

                   <h4>
                       <font color="green">Hit backspace Green text must be green</font>
                   </h4>

                   </div>


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
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#another-text h4');
            // move the custros to the start
            $browser->script("var myTextElement = document.getElementById('another-text').children[1].firstChild;
                      var range = document.createRange();
                      range.setStart(myTextElement, 0);
                      range.setEnd(myTextElement, 0);
                      window.getSelection().removeAllRanges();
                      window.getSelection().addRange(range);" );


            $browser->pause(1500);

            //   $browser->keys('#my-text-here', 'New text in my element');
            $browser->pause(100);
            //  $browser->keys('#my-text-here', [ WebDriverKeys::ENTER, WebDriverKeys::BACKSPACE]);
            $this->expectException(\Facebook\WebDriver\Exception\StaleElementReferenceException::class);
            $browser->keys('#another-text h4', [WebDriverKeys::BACKSPACE]);
            $browser->pause(1000);

            //check if another-text has 2 font children red and green

            $output = $browser->script("
            var  isTrue = document.getElementById('another-text').querySelectorAll('font').length === 2;
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The element must have 2 font children');

            $output = $browser->script("
            var  isTrue = document.getElementById('another-text').querySelectorAll('font')[0].getAttribute('color') === 'red';
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The first font must be red');

            $output = $browser->script("
            var  isTrue = document.getElementById('another-text').querySelectorAll('font')[1].getAttribute('color') === 'green';
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The second font must be green');

            $output = $browser->script("
            var  isTrue = document.getElementById('another-text').querySelectorAll('font')[1].innerText === 'Hit backspace Green text must be green';
            return isTrue;
        ");
            $this->assertEquals($output[0], true, 'The second font must contain the text');

        });


    }


    public function testLiveEditTypingTestBackspaceKeyOnSelection()
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
                   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text here and hit enter</font></h6>
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
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#my-text-here');


            $browser->script("var myTextElement = document.getElementById('my-text-here');
                      var range = document.createRange();
                      range.selectNodeContents(myTextElement);
                      window.getSelection().removeAllRanges();
                      window.getSelection().addRange(range);");
            $browser->keys('#my-text-here', [WebDriverKeys::ENTER]);
            $browser->pause(100);

            $output = $browser->script("

            var myTextElementHtmlDoesNotExists = document.getElementById('my-text-here') === null;
            return myTextElementHtmlDoesNotExists;
        ");

            $this->assertEquals($output[0], true, 'The element must not exists');


            $output = $browser->script("
            //check if its  font
            var  isTrue = document.getElementById('my-text-parent').nextElementSibling.children[0].nodeName === 'FONT';
            return isTrue;
            ");
            $this->assertEquals($output[0], true, 'Next element must contain font');


        });
    }


    public function testLiveEditAlignOnElements()
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
                   <h6 class="font-weight-normal" id="my-text-parent"><font id="my-text-here" color="#ff0000">Enter text for align</font></h6>
                </div>
            ',
                'subtype' => 'static',
                'is_active' => 1,
            );

            $saved_id = save_content($params);
            $link = content_link($saved_id);

            $browser->visit($link . '?editmode=y');
            $browser->pause(3000);

            $browser->waitFor('#live-editor-frame', 30)
                ->withinFrame('#live-editor-frame', function ($browser) {
                    $browser->pause(1000);
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->doubleClick('#my-text-here');




        });

    }


}
