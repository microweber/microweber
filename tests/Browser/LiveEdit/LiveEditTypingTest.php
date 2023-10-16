<?php

namespace Tests\Browser\LiveEdit;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\Browser\Components\LiveEditSaveButton;
use Tests\DuskTestCase;

class LiveEditTypingTest extends DuskTestCase
{
    public function testLiveEditTypingOnSafeModeText()
    {
        $siteUrl = site_url();

        $this->browse(function (Browser $browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $params = array(
                'title' => 'My new page ' . time(),
                'content_type' => 'page',
                'content' => '<div class="safe-mode"><h1 id="test-element-in-safe-mode">Must not be able to delete element with backspace</h1></div>
<div><h1 id="test-element-not-safe-mode">Be able to delete element with backspace</h1></div>',
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

            $browser->doubleClick('#test-element-in-safe-mode');
            $browser->pause(500);

            // backspace
            for ($i = 0; $i < 100; $i++) {
                $browser->keys('#test-element-in-safe-mode', '{backspace}');
            }
            // delete
            for ($i = 0; $i < 100; $i++) {
                $browser->keys('#test-element-in-safe-mode', '{delete}');
            }

            $browser->typeSlowly('#test-element-in-safe-mode', 'New text in safe mode element');
            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();

            $browser->visit($link );
            $browser->waitForText('New text in safe mode element', 30);
        });
    }

    public function testLiveEditTypingOnNonSafeModeText()
    {
        $siteUrl = site_url();

        $this->browse(function (Browser $browser) use ($siteUrl) {
            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $params = array(
                'title' => 'My new page ' . time(),
                'content_type' => 'page',
                'content' => '
<div><h1 id="test-element-not-safe-mode">Be able to delete element with backspace</h1></div>',
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

            $browser->click('#test-element-not-safe-mode');
            $browser->doubleClick('#test-element-not-safe-mode');
            $browser->pause(500);

            // backspace
            for ($i = 0; $i < 100; $i++) {
                if (count($browser->driver->findElements(WebDriverBy::cssSelector('#test-element-not-safe-mode'))) > 0) {
                    $browser->keys('#test-element-not-safe-mode', '{backspace}');
                }
            }
            // delete
            for ($i = 0; $i < 100; $i++) {
                if (count($browser->driver->findElements(WebDriverBy::cssSelector('#test-element-not-safe-mode'))) > 0) {
                    $browser->keys('#test-element-not-safe-mode', '{delete}');
                }
            }

            $browser->within(new LiveEditSaveButton(), function ($browser) {
                $browser->clickSaveButton($browser);
            });
            $browser->switchFrameDefault();

            $browser->visit($link );
            $browser->assertNotPresent('#test-element-not-safe-mode');
        });
    }
}
