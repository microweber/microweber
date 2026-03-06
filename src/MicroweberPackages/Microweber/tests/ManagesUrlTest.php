<?php

namespace MicroweberPackages\Microweber\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

/**
 * @deprecated
 */
class ManagesUrlTest extends TestCase
{

    #[Test]

    public function it_site_url(): void {
        $url = app()->microweber->siteUrl();
        $this->assertEquals($url, app()->url_manager->site());
    }

    #[Test]

    public function it_site_hostname(): void {
        $hostname = app()->microweber->siteHostname();
        $this->assertEquals($hostname, app()->url_manager->hostname());
    }
}
