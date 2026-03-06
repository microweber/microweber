<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    #[Test]
    public function it_renders_a_navbar(): void {
        $bladeString = '<x-navbar></x-navbar>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="navbar', $output);
    }

    #[Test]

    public function it_renders_a_navbar_with_brand(): void {
        $bladeString = '<x-navbar brand="My App"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('My App', $output);
    }

    #[Test]

    public function it_renders_a_navbar_with_brand_url(): void {
        $bladeString = '<x-navbar brand="My App" brandUrl="/"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('href="/"', $output);
    }

    #[Test]

    public function it_renders_a_navbar_with_expand(): void {
        $bladeString = '<x-navbar expand="lg"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('navbar-expand-lg', $output);
    }

    #[Test]

    public function it_renders_a_navbar_with_dark_theme(): void {
        $bladeString = '<x-navbar dark="true"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('navbar-dark', $output);
    }

    #[Test]

    public function it_renders_a_navbar_with_fixed_position(): void {
        $bladeString = '<x-navbar fixed="top"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('fixed-top', $output);
    }

    #[Test]

    public function it_renders_a_navbar_with_custom_classes(): void {
        $bladeString = '<x-navbar class="custom-class"></x-navbar>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
