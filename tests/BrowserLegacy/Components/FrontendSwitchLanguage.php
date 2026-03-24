<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

class FrontendSwitchLanguage extends BaseComponent
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

    public function switchLanguage(Browser $browser, $locale)
    {

        $browser->switchFrameDefault();
        if ($browser->element('#mw-page-set-preview-mode')) {
            $browser->click('#mw-page-set-preview-mode');
        }
        if ($browser->element('#live-editor-frame')) {
            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
            $browser->switchFrame($iframeElement);
        }



        //
        if (!$browser->element('#switch_language_ul')) {
            if ($browser->element('#header-layout')) {
                // must enable multilang in header-layout first

              //  $browser->script('location.reload();');

            }
        }

        $browser->switchFrameDefault();

        if ($browser->element('#live-editor-frame')) {
            $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
            $browser->switchFrame($iframeElement);
        }


        $browser->pause(300);
        $browser->click('.module-multilanguage > .mw-dropdown-default');
        $browser->pause(400);
        $browser->script('mw.askusertostay=false;');
        //$browser->script('$(\'li[data-value="'.$locale.'"]\', ".module-multilanguage").click()');
      //  $browser->script('$(\'li[data-value="'.$locale.'"]\', ".module-multilanguage").click()');
  //   $browser->script('$(\'li[data-value="'.$locale.'"]\', ".module-multilanguage").click()');
    $browser->click('.module-multilanguage li[data-value="'.$locale.'"]');
         $browser->pause(400);

        //   $browser->pause(10000);
      $browser->waitUntil('!$.active',15);
     //  $browser->waitForReload(false, 30);

        $metaLocale = str_replace('_', '-', $locale);
        // get fromn <link rel="alternate" href="http://127.0.0.1:8000/en_US/my-new-page-ml-1709641346" hreflang="en-US" />


        $output = $browser->script("
            var  getLink = function(hreflang) {
                var links = document.getElementsByTagName('link');
                for (var i = 0; i < links.length; i++) {
                    if (links[i].getAttribute('rel') == 'alternate' && links[i].getAttribute('hreflang') == hreflang) {
                        return links[i].getAttribute('href');
                    }
                }
                return null;
            }
            return getLink('".$metaLocale."');
            ");
        $contentLink = $output[0];

         $browser->waitForLocation($contentLink, 30);
    //    $browser->visit($contentLink);

        $browser->pause(2500);

        $browser->waitFor('.module-multilanguage', 30);

        $browser->switchFrameDefault();
        if ($browser->element('#mw-page-set-back-to-edit-mode')) {
            $browser->click('#mw-page-set-back-to-edit-mode');
            if ($browser->element('#live-editor-frame')) {
                $iframeElement = $browser->driver->findElement(WebDriverBy::id('live-editor-frame'));
                $browser->switchFrame($iframeElement);
            }
        }



    }
}
