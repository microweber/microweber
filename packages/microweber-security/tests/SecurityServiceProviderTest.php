<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\HtmlClean;
use MicroweberPackages\Security\XSSClean;
use MicroweberPackages\Security\XSSSecurity;
use PHPUnit\Framework\Attributes\Test;

class SecurityServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_html_clean_binding(): void
    {
        $this->assertInstanceOf(HtmlClean::class, app('html_clean'));
        $this->assertInstanceOf(HtmlClean::class, app('html_clean'));
        $this->assertInstanceOf(HtmlClean::class, app(HtmlClean::class));
    }

    #[Test]
    public function it_registers_xss_security_binding(): void
    {
        $this->assertInstanceOf(XSSSecurity::class, app('xss_security'));
        $this->assertInstanceOf(XSSSecurity::class, app(XSSSecurity::class));
    }

    #[Test]
    public function it_registers_xss_clean_binding(): void
    {
        $this->assertInstanceOf(XSSClean::class, app('xss_clean'));
        $this->assertInstanceOf(XSSClean::class, app(XSSClean::class));
    }
}
