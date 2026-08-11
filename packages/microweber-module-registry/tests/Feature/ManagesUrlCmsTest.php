<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Feature;

use PHPUnit\Framework\Attributes\Test;

/**
 * CMS integration tests for the optional ManagesUrl trait.
 *
 * @deprecated URL helpers on the registry are deprecated.
 */
if (class_exists(\Tests\TestCase::class) && class_exists(\MicroweberPackages\Url\Facades\UrlManager::class)) {
    class ManagesUrlCmsTest extends \Tests\TestCase
    {
        #[Test]
        public function it_site_url(): void
        {
            $url = app()->microweber->siteUrl();
            $this->assertEquals($url, \MicroweberPackages\Url\Facades\UrlManager::site());
        }

        #[Test]
        public function it_site_hostname(): void
        {
            $hostname = app()->microweber->siteHostname();
            $this->assertEquals($hostname, \MicroweberPackages\Url\Facades\UrlManager::hostname());
        }
    }
} else {
    class ManagesUrlCmsTest extends \PHPUnit\Framework\TestCase
    {
        #[Test]
        public function skipped_without_cms(): void
        {
            $this->markTestSkipped('CMS TestCase and UrlManager are required');
        }
    }
}
