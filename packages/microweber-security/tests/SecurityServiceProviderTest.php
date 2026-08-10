<?php

namespace MicroweberPackages\Security\Tests;

use MicroweberPackages\Security\HtmlClean;
use MicroweberPackages\Security\XSSClean;
use MicroweberPackages\Security\XSSSecurity;
use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Security\Facades\HtmlClean as HtmlCleanFacade;
use MicroweberPackages\Security\Facades\XSSSecurity as XSSSecurityFacade;
use MicroweberPackages\Security\Facades\XSSClean as XSSCleanFacade;

class SecurityServiceProviderTest extends TestCase
{
    #[Test]
    public function it_registers_html_clean_binding(): void
    {
        $this->assertInstanceOf(HtmlClean::class, HtmlCleanFacade::getFacadeRoot());
        $this->assertInstanceOf(HtmlClean::class, HtmlCleanFacade::getFacadeRoot());
        $this->assertInstanceOf(HtmlClean::class, app(HtmlClean::class));
    }

    #[Test]
    public function it_registers_xss_security_binding(): void
    {
        $this->assertInstanceOf(XSSSecurity::class, XSSSecurityFacade::getFacadeRoot());
        $this->assertInstanceOf(XSSSecurity::class, app(XSSSecurity::class));
    }

    #[Test]
    public function it_registers_xss_clean_binding(): void
    {
        $this->assertInstanceOf(XSSClean::class, XSSCleanFacade::getFacadeRoot());
        $this->assertInstanceOf(XSSClean::class, app(XSSClean::class));
    }
}
