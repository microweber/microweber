<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;

class TakeFullpageScreenshot extends BaseComponent
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

    public function generateScreenshot(Browser $browser, $screenshotName)
    {
        $browser->pause(2000);

        $browserSize = $browser->driver->manage()->window()->getSize();
        $browserHeight = $browserSize->getHeight();

        $body = $browser->driver->findElement(WebDriverBy::tagName('body'));
        $currentBodySizeWidth = $body->getSize()->getWidth();
        $currentBodySizeHeight = $body->getSize()->getHeight();

        $getClientHeight = $browser->driver->executeScript('return document.documentElement.clientHeight;');

        $browser->pause(2000);

        $screenshotParts = ceil($currentBodySizeHeight / $browserHeight);

        $screenshotPartsFiles = [];
        for ($i = 0; $i <= $screenshotParts; $i++) {

            $screenshotFilename = 'ss-part-' . $i;

            $browser->driver->executeScript('document.documentElement.scrollTop = '.$getClientHeight * $i.';');
            $browser->pause(2000);
            $browser->screenshot($screenshotFilename);
            $screenshotPartsFiles[] = dirname(__DIR__) .DS. 'screenshots' . DS . $screenshotFilename.'.png';
        }

        // Set memory limit
        ini_set("memory_limit", "-1");
        set_time_limit(0);

        mergeScreenshotParts($screenshotPartsFiles,  dirname(__DIR__) .DS. 'screenshots' . DS . $screenshotName.'.png');

        // Delete files
        foreach ($screenshotPartsFiles as $file) {
            @unlink($file);
        }
    }
}
