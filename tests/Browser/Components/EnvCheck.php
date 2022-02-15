<?php

namespace Tests\Browser\Components;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Component as BaseComponent;
use PHPUnit\Framework\Assert as PHPUnit;

class EnvCheck extends BaseComponent
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

    public function checkEnvName(Browser $browser)
    {
        $environment = app()->environment();
        $browserEnvironment = $browser->visit(route('l5-swagger.dusk.env'))
            ->element('')->getText();

        PHPUnit::assertEquals($environment, $browserEnvironment,
            "Browser environment [{$browserEnvironment}]
            diverge from the given environment [{$environment}]");
    }


}
