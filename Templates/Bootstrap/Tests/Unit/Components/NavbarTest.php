<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Navbar;

class NavbarTest extends TestCase
{
    public function testRendersANavbar()
    {
        $bladeString = '<x-bootstrap-navbar></x-bootstrap-navbar>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="navbar', $output);
    }

    public function testRendersANavbarWithBrand()
    {
        $bladeString = '<x-bootstrap-navbar brand="My App"></x-bootstrap-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('My App', $output);
    }

    public function testRendersANavbarWithBrandUrl()
    {
        $bladeString = '<x-bootstrap-navbar brand="My App" brandUrl="/"></x-bootstrap-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('href="/"', $output);
    }

    public function testRendersANavbarWithExpand()
    {
        $bladeString = '<x-bootstrap-navbar expand="lg"></x-bootstrap-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('navbar-expand-lg', $output);
    }

    public function testRendersANavbarWithDarkTheme()
    {
        $bladeString = '<x-bootstrap-navbar dark="true"></x-bootstrap-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('navbar-dark', $output);
    }

    public function testRendersANavbarWithFixedPosition()
    {
        $bladeString = '<x-bootstrap-navbar fixed="top"></x-bootstrap-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('fixed-top', $output);
    }

    public function testRendersANavbarWithCustomClasses()
    {
        $bladeString = '<x-bootstrap-navbar class="custom-class"></x-bootstrap-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
