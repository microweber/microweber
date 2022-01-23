<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use MicroweberPackages\Multilanguage\MultilanguagePermalinkManager;

abstract class DuskTestCaseMultilanguage extends DuskTestCase
{
    protected function assertPostConditions(): void
    {
        \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);

        \DB::table('options')
            ->where('option_group', 'multilanguage_settings')
            ->delete();

    }

    protected function assertPreConditions(): void
    {

        if (!is_module('multilanguage')) {
            $this->markTestSkipped(
                'The Multilanguage module is not available.'
            );
        } else {
            \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(true);

            app()->bind('permalink_manager', function () {
                return new MultilanguagePermalinkManager();
            });

            $option = array();
            $option['option_value'] = 'y';
            $option['option_key'] = 'is_active';
            $option['option_group'] = 'multilanguage_settings';
            save_option($option);
        }
    }
}
