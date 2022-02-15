<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use MicroweberPackages\Config\ConfigSave;
use PHPUnit\Framework\Assert as PHPUnit;

class AdminMakeInstall extends BaseComponent
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

    public function makeInstallation(Browser $browser)
    {
        $siteUrl = 'http://127.0.0.1:8000/';

        /* $deleteDbFiles = [];
         $deleteDbFiles[] = dirname(dirname(__DIR__)) . DS . 'config/microweber.php';
         $deleteDbFiles[] = dirname(dirname(__DIR__)) . DS . 'storage/127_0_0_1.sqlite';
         foreach ($deleteDbFiles as $file) {
             if (is_file($file)) {
                 unlink($file);
             }
         }*/

        $browser->visit($siteUrl);
        $browser->pause(700);

        if (count($browser->driver->findElements(WebDriverBy::xpath('//*[@name="admin_username"]'))) > 0) {

            $browser->waitForText('install');
            $browser->assertSee('install');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });


            // Fill the install fields
            $browser->type('admin_username', '1');
            $browser->type('admin_password', '1');
            $browser->type('admin_password2', '1');
            $browser->type('admin_email', 'bobi@microweber.com');

            $browser->pause(300);
            $browser->select('#default_template', 'new-world');

            $browser->pause(100);
            $browser->click('@install-button');

            $browser->pause(20000);

            Artisan::call('config:cache');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            clearcache();
        }

    }
}
