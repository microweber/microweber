<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class NavItemTest extends TestCase
{
    #[Test]
    public function it_renders_a_nav_item(): void {
        $bladeString = '<x-nav-item></x-nav-item>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="nav-item"', $output);
    }

    #[Test]

    public function it_renders_a_nav_item_with_active_state(): void {
        $bladeString = '<x-nav-item href="/" active></x-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="nav-link active"', $output);
    }

    #[Test]

    public function it_renders_a_nav_item_with_href(): void {
        $bladeString = '<x-nav-item href="/about">About</x-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('href="/about"', $output);
    }

    #[Test]

    public function it_renders_a_nav_item_with_custom_classes(): void {
        $bladeString = '<x-nav-item class="custom-class"></x-nav-item>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
