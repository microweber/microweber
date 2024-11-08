<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\NavItem;

class NavItemTest extends TestCase
{
    public function testRendersANavItem()
    {
        $bladeString = '<x-nav-item></x-nav-item>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="nav-item"', $output);
    }

    public function testRendersANavItemWithActiveState()
    {
        $bladeString = '<x-nav-item href="/" active></x-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="nav-link active"', $output);
    }

    public function testRendersANavItemWithHref()
    {
        $bladeString = '<x-nav-item href="/about">About</x-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('href="/about"', $output);
    }

    public function testRendersANavItemWithCustomClasses()
    {
        $bladeString = '<x-nav-item class="custom-class"></x-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
