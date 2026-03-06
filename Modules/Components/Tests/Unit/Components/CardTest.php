<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class CardTest extends TestCase
{

    #[Test]

    public function it_renders_a_card(): void {
        $bladeString = '<x-card></x-card>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="card', $output);
    }


    #[Test]


    public function it_renders_a_card_with_dark_theme(): void {
        $bladeString = '<x-card theme="dark"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-dark text-white', $output);
    }


    #[Test]


    public function it_renders_a_card_with_success_theme(): void {
        $bladeString = '<x-card theme="success"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-success text-white', $output);
    }


    #[Test]


    public function it_renders_a_card_with_custom_classes(): void {
        $bladeString = '<x-card class="custom-class"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
