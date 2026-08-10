<?php

namespace MicroweberPackages\Microweber\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use MicroweberPackages\Url\Facades\UrlManager;

/**
 * @deprecated
 */
class ManagesUrlTest extends TestCase
{

    #[Test]

    public function it_site_url(): void {
        $url = app()->microweber->siteUrl();
        $this->assertEquals($url, UrlManager::site());
    }

    #[Test]

    public function it_site_hostname(): void {
        $hostname = app()->microweber->siteHostname();
        $this->assertEquals($hostname, UrlManager::hostname());
    }
}
