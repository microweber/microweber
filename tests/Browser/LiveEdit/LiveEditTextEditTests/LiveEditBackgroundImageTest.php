<?php

namespace Tests\Browser\LiveEdit\LiveEditTextEditTests;


use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminContentImageAdd;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\LiveEditSaveButton;
use Tests\Browser\Components\LiveEditWaitUntilLoaded;
use Tests\DuskTestCase;

class LiveEditBackgroundImageTest extends DuskTestCase
{
    public $template_name = 'big';

    public function testBackgroundImageChange()
    {


        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $moduleIdRandom = 'header-skin-1' . rand(1, 1000) . time();
            $params = array(
                'title' => 'My new page for typing ' . time(),
                'content_type' => 'page',
                'content' => '<module type="layouts" template="header/skin-1" id="' . $moduleIdRandom . '"/>
    <module type="layouts" template="features/skin-2" id="features-skin-2"/>
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

            $browser->switchFrame($iframeElement);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();


            });
            $browser->click('#' . $moduleIdRandom);
            $browser->doubleClick('#' . $moduleIdRandom . ' .module-background');

            $browser->switchFrameDefault();
            $browser->waitFor('#module-quick-setting-dialog-' . $moduleIdRandom . '-content-window', 30)
                ->withinFrame('#module-quick-setting-dialog-' . $moduleIdRandom . '-content-window', function ($browser) {
                    $browser->pause(1000);
                });


            $iframeElement = $browser->driver->findElement(WebDriverBy::id('module-quick-setting-dialog-' . $moduleIdRandom . '-content-window'));

            $browser->switchFrame($iframeElement);
            //bg--image-picker img

            $browser->waitFor('#bg--image-picker img', 30);
            $browser->click('#bg--image-picker img');


            $browser->switchFrameDefault();

            $browser->within(new AdminContentImageAdd, function ($browser) {
                $browser->addImage(userfiles_path() . '/templates/default/img/patterns/img1.jpg');
            });
            $browser->keys('', [WebDriverKeys::ESCAPE]);

            $browser->waitFor('#live-editor-frame', 30)
                ->withinFrame('#live-editor-frame', function ($browser) {
                    $browser->pause(1000);
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->click('#' . $moduleIdRandom);

            $output = $browser->script('
            var HasBg = document.querySelectorAll("#' . $moduleIdRandom . ' .mw-layout-background-node")[0].style.backgroundImage.indexOf("url") > -1;
            return HasBg;
            ');

            $this->assertTrue($output[0]);

            $browser->switchFrameDefault();

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });



            $browser->switchFrameDefault();

            $browser->visit($link . '?editmode=n');

            $output = $browser->script('
            var HasBg = document.querySelectorAll("#' . $moduleIdRandom . ' .mw-layout-background-node")[0].style.backgroundImage.indexOf("url") > -1;
            return HasBg;
            ');

            $this->assertTrue($output[0]);

        });


    }
    public function testBackgroundVideoChange()
    {


        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $moduleIdRandom = 'header-skin-1' . rand(1, 1000) . time();
            $params = array(
                'title' => 'My new page for typing ' . time(),
                'content_type' => 'page',
                'content' => '<module type="layouts" template="header/skin-1" id="' . $moduleIdRandom . '"/>
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
            $browser->click('#' . $moduleIdRandom);
            $browser->doubleClick('#' . $moduleIdRandom . ' .module-background');

            $browser->switchFrameDefault();
            $browser->waitFor('#module-quick-setting-dialog-' . $moduleIdRandom . '-content-window', 30)
                ->withinFrame('#module-quick-setting-dialog-' . $moduleIdRandom . '-content-window', function ($browser) {
                    $browser->pause(1000);
                });


            $iframeElement = $browser->driver->findElement(WebDriverBy::id('module-quick-setting-dialog-' . $moduleIdRandom . '-content-window'));

            $browser->switchFrame($iframeElement);


            $browser->pause(500);
            $browser->click('.js-filepicker-pick-type-tab-video');


            $browser->waitFor('#bg--video-picker', 30);
            $browser->click('#bg--video-picker .mw-dropzone');


            $browser->switchFrameDefault();

            $browser->within(new AdminContentImageAdd, function ($browser) {
                $browser->addImage(userfiles_path() . '/templates/default/img/videos/example.mp4');
            });
            $browser->pause(1000);
            $browser->keys('', [WebDriverKeys::ESCAPE]);


            $browser->waitFor('#live-editor-frame', 30)
                ->withinFrame('#live-editor-frame', function ($browser) {
                    $browser->pause(1000);
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->click('#' . $moduleIdRandom);


            $output = $browser->script('
            var HasNoBg = document.querySelectorAll("#' . $moduleIdRandom . ' .mw-layout-background-node")[0].style.backgroundImage === "";
            return HasNoBg;
            ');

            $this->assertTrue($output[0]);


            $output = $browser->script('
            var HasVideo = document.querySelectorAll("#' . $moduleIdRandom . ' .mw-layout-background-node video")[0].src.indexOf("mp4") > -1;
            return HasVideo;
            ');

            $this->assertTrue($output[0]);



            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();


            $browser->visit($link . '?editmode=n');

            $output = $browser->script('
            var HasNoBg = document.querySelectorAll("#' . $moduleIdRandom . ' .mw-layout-background-node")[0].style.backgroundImage === "";
            return HasNoBg;
            ');

            $this->assertTrue($output[0]);


            $output = $browser->script('
            var HasVideo = document.querySelectorAll("#' . $moduleIdRandom . ' .mw-layout-background-node video")[0].src.indexOf("mp4") > -1;
            return HasVideo;
            ');

            $this->assertTrue($output[0]);


        });


    }


    public function testBackgroundOverlayChange()
    {


        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $moduleIdRandom = 'header-skin-1' . rand(1, 1000) . time();
            $params = array(
                'title' => 'My new page for typing ' . time(),
                'content_type' => 'page',
                'content' => '
                <module type="layouts" template="header/skin-1" id="' . $moduleIdRandom . '"/>
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
            $browser->click('#' . $moduleIdRandom);
            $browser->doubleClick('#' . $moduleIdRandom . ' .module-background');

            $browser->switchFrameDefault();
            $browser->waitFor('#module-quick-setting-dialog-' . $moduleIdRandom . '-content-window', 30)
                ->withinFrame('#module-quick-setting-dialog-' . $moduleIdRandom . '-content-window', function ($browser) {
                    $browser->pause(1000);
                });


            $iframeElement = $browser->driver->findElement(WebDriverBy::id('module-quick-setting-dialog-' . $moduleIdRandom . '-content-window'));

            $browser->switchFrame($iframeElement);

            $browser->waitFor('.js-filepicker-pick-type-tab-color', 30);
            $browser->click('.js-filepicker-pick-type-tab-color');
            $browser->click('.a-color-picker-single-input');
            $browser->typeSlowly('.a-color-picker-single-input input', '#efecec');
            $browser->keys('.a-color-picker-single-input input', [WebDriverKeys::ENTER]);

            $browser->pause(300);

            $browser->switchFrame($iframeElement);
            $browser->click('.js-filepicker-pick-type-tab-color');
            $browser->click('.js-filepicker-pick-type-tab-video');
          //  $browser->keys('.js-filepicker-pick-type-tab-video', [WebDriverKeys::ESCAPE]);
            $browser->switchFrameDefault();
            $browser->pause(300);
          $browser->click('.mw-dialog.active .mw-dialog-close');
  //        $browser->script("$('.mw-dialog-close').click()");
////
//         //   $browser->keys('.a-color-picker-single-input input', [WebDriverKeys::ESCAPE]);
//
//
//
            $browser->waitFor('#live-editor-frame', 30)
                ->withinFrame('#live-editor-frame', function ($browser) {
                    $browser->pause(100);
                });

            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));

            $browser->switchFrame($iframeElement);

            $browser->click('#' . $moduleIdRandom);




            $output = $browser->script('
            var HasBgColor = document.querySelectorAll("#' . $moduleIdRandom . ' .mw-layout-background-overlay")[0].style.backgroundColor !== null;
            return HasBgColor;
            ');

            $this->assertTrue($output[0]);




        });




    }
}
