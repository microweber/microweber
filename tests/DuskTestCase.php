<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Browser;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {

        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {

            $arguments = [];
            $arguments[] = '--disable-gpu';

            if (getenv('GITHUB_RUN_NUMBER')) {
                $arguments[] = '--headless';
            }
            return $items->merge($arguments);

        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    protected function assertPreConditions(): void
    {
        if (mw_is_installed()) {
            \MicroweberPackages\Multilanguage\MultilanguageHelpers::setMultilanguageEnabled(false);
            \DB::table('options')
                ->where('option_group', 'multilanguage_settings')
                ->delete();
        }

    }

    public function checkBrowserHmlForErrors($browser)
    {
        $error_strings = ['mw_replace_back'];

        $html = $browser->script("return $('body').html()");

        if ($html) {
            foreach ($html as $html_string) {
                foreach ($error_strings as $error_string) {
                    $this->assertFalse(str_contains($html_string, $error_string));
                }
            }
        }

    }
}
