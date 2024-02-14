<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;

class AdminContentImageAdd extends BaseComponent
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

    public function addImage(Browser $browser, $image)
    {

        $isliveed = false;
        if ($browser->element('.admin-thumb-item-uploader-holder')) {
            $isliveed = true;
        }

        if ($browser->element('.admin-thumb-item-uploader-holder')) {
            if ($browser->driver->findElement(WebDriverBy::cssSelector('.admin-thumb-item-uploader-holder'))) {
                if (!$browser->driver->findElement(WebDriverBy::cssSelector('.admin-thumb-item-uploader-holder'))->isDisplayed()) {
                    //  $browser->script('$(".js-card-search-engine a.btn").click();');
                    if ($browser->element('.js-default-card-tab')) {
                        $browser->click('.js-default-card-tab');
                    }

                }
            }
        }
        if ($browser->element('.admin-thumb-item-uploader-holder')) {

            if ($browser->driver->findElement(WebDriverBy::cssSelector('.admin-thumbs-holder'))) {

                $browser->pause(1000);
                $browser->scrollTo('.admin-thumbs-holder');
                $browser->pause(1000);
                // $browser->click('.admin-thumb-item-uploader-holder');
                if ($browser->element('#post-file-picker')) {
                    $browser->click('#post-file-picker');
                } else {
                    $browser->click('#post-file-picker-small');

                }

            }
        }
        $browser->pause(100);
        $browser->attach('input.mw-uploader-input', $image);
        if (!$browser->element('.admin-thumb-item-uploader-holder')) {
            $browser->switchFrameDefault();
            if ($browser->element('#mw-file-picker-dialog')) {
                $browser->waitUntilMissing('#mw-file-picker-dialog .mw-filepicker-component-section', 30);
            }
        }
  //      $browser->waitUntilMissing('.mw-uploader-type-holder', 30);
        $browser->pause(1000);
     //   $browser->attach('.mw-filepicker-desktop-type-big input.mw-uploader-input', $image);
       // $browser->waitForText('Pictures settings are saved',30);


        if ($browser->element('.admin-thumb-item-uploader-holder')) {
            if (!$browser->element('.admin-thumb-item')) {
                $browser->waitFor('.admin-thumb-item', 30);
            }
        } else {

            $browser->switchFrameDefault();
            if ($browser->element('.mw-dialog-overlay')) {
                if ($browser->element('.mw-dialog-iframe.active .mw-dialog-close')
                    and $browser->element('.mw-dialog-iframe.active .mw-dialog-close')->isDisplayed()) {
                   $browser->click('.mw-dialog-iframe.active .mw-dialog-close');
                  //  $browser->script('$(".mw-dialog-iframe.active .mw-dialog-close").click();');
                    $browser->pause(200);
                } else if ($browser->element('.mw-dialog-overlay')->isDisplayed()) {
                    $browser->waitUntilMissing('.mw-dialog-overlay', 30);
                }

            }
        }
        $browser->pause(1000);


    }



}
