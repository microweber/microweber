<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Navbar;

class NavbarTest extends TestCase
{
    public function testRendersANavbar()
    {
        $bladeString = '<x-navbar></x-navbar>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="navbar', $output);
    }

    public function testRendersANavbarWithBrand()
    {
        $bladeString = '<x-navbar brand="My App"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('My App', $output);
    }

    public function testRendersANavbarWithBrandUrl()
    {
        $bladeString = '<x-navbar brand="My App" brandUrl="/"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('href="/"', $output);
    }

    public function testRendersANavbarWithExpand()
    {
        $bladeString = '<x-navbar expand="lg"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('navbar-expand-lg', $output);
    }

    public function testRendersANavbarWithDarkTheme()
    {
        $bladeString = '<x-navbar dark="true"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('navbar-dark', $output);
    }

    public function testRendersANavbarWithFixedPosition()
    {
        $bladeString = '<x-navbar fixed="top"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('fixed-top', $output);
    }

    public function testRendersANavbarWithCustomClasses()
    {
        $bladeString = '<x-navbar class="custom-class"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
