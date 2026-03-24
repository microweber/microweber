<?php

namespace Tests\Browser\Example;

use PHPUnit\Framework\Attributes\Test;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    public $siteUrl = 'http://127.0.0.1:8000/';

    /**
     * A basic browser test example.
     *
     * @return void
     */
    #[Test]
    public function it_basic_example(): void {
        $siteUrl = $this->siteUrl;

        $this->browse(function (Browser $browser) use($siteUrl) {
            $browser->visit($siteUrl)->waitForText('Microweber');
        });
    }
}
