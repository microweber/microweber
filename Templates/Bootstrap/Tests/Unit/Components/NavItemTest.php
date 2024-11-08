<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\NavItem;

class NavItemTest extends TestCase
{
    public function testRendersANavItem()
    {
        $bladeString = '<x-bootstrap-nav-item></x-bootstrap-nav-item>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="nav-item"', $output);
    }

    public function testRendersANavItemWithActiveState()
    {
        $bladeString = '<x-bootstrap-nav-item href="/" active></x-bootstrap-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="nav-link active"', $output);
    }

    public function testRendersANavItemWithHref()
    {
        $bladeString = '<x-bootstrap-nav-item href="/about">About</x-bootstrap-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('href="/about"', $output);
    }

    public function testRendersANavItemWithCustomClasses()
    {
        $bladeString = '<x-bootstrap-nav-item class="custom-class"></x-bootstrap-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
