<?php

namespace Tests\Browser\Admin;

use Laravel\Dusk\Browser;
use Tests\Browser\Components\AdminLogin;
use Tests\Browser\Components\ChekForJavascriptErrors;
use Tests\DuskTestCase;

class AdminMarketplaceTest extends DuskTestCase
{
    public function testModuleInstall()
    {
        // Remove old module
        mw()->module_manager->uninstall(array('for_module' => 'browser_redirect'));
        rmdir_recursive(userfiles_path() . 'modules/browser_redirect',false);

        \DB::table('modules')
            ->where('module', 'browser_redirect')
            ->delete();

        app()->update->post_update();



        $this->browse(function (Browser $browser) {

            $browser->within(new AdminLogin, function ($browser) {
                $browser->fillForm();
            });

            $browser->waitForText('Marketplace');
            $browser->clickLink('Marketplace');

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->pause(3000);

            $browser->waitForText('Template');
            $browser->waitForText('Module');

            $browser->click('#js-packages-tab-module');
            $browser->pause(3000);
            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });

            $browser->waitForText('Module');

            $browser->waitForText('Browser Redirect');
            $browser->click('#js-install-package-browser_redirect');
            $browser->pause(3000);
            $browser->click('#js-install-package-action');

            $browser->waitForText('Please confirm the installation');
            $browser->waitForText('browser_redirect');
          //  $browser->waitForText('files will be installed');


            $browser->waitForText('Confirm');
            $browser->click('#js-buttons-confirm-install-link');

            //$browser->waitForText('Success',30);

            $browser->within(new ChekForJavascriptErrors(), function ($browser) {
                $browser->validate();
            });
            $browser->waitFor('.js-install-package-is-installed-badge-browser_redirect', 30);

            //

        });


    }
}
