<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\XSSClean;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class XSSCleanTest extends TestCase
{
    #[Test]
    public function it_cleans_xss_from_string(): void
    {
        $xss = new XSSClean();
        $result = $xss->clean("<script>alert('xss')</script>");
        $this->assertStringNotContainsString('<script>', $result);
    }

    #[Test]
    public function it_cleans_event_handlers(): void
    {
        $xss = new XSSClean();
        $result = $xss->clean("<div onmousedown='alert(1)'>test</div>");
        $this->assertStringNotContainsString('onmousedown', $result);
    }

    #[Test]
    public function it_cleans_arrays(): void
    {
        $xss = new XSSClean();
        $result = $xss->cleanArray([
            'safe' => 'hello',
            'xss' => "<script>alert('xss')</script>",
        ]);
        $this->assertIsArray($result);
        $this->assertSame('hello', $result['safe']);
        $this->assertStringNotContainsString('<script>', $result['xss']);
    }

    #[Test]
    public function it_preserves_site_url_placeholder(): void
    {
        $xss = new XSSClean();
        $result = $xss->clean('Visit {SITE_URL} for more');
        $this->assertStringContainsString('{SITE_URL}', $result);
    }
}