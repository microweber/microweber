<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ButtonTest extends TestCase
{
    #[Test]
    public function it_renders_a_button(): void {
        $bladeString = '<x-button>Test</x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="btn', $output);
        $this->assertStringContainsString('Test', $output);
    }

    #[Test]

    public function it_renders_a_button_with_type(): void {
        $bladeString = '<x-button type="success"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-success', $output);
    }

    #[Test]

    public function it_renders_a_button_with_size(): void {
        $bladeString = '<x-button size="lg"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-lg', $output);
    }

    #[Test]

    public function it_renders_a_button_as_outline(): void {
        $bladeString = '<x-button outline="true"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-outline-', $output);
    }

    #[Test]

    public function it_renders_a_disabled_button(): void {
        $bladeString = '<x-button disabled="true"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    #[Test]

    public function it_renders_a_block_button(): void {
        $bladeString = '<x-button block="true"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('d-block', $output);
    }

    #[Test]

    public function it_renders_a_button_with_custom_classes(): void {
        $bladeString = '<x-button class="custom-class"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }

    #[Test]

    public function it_renders_a_button_with_additional_attributes(): void {
        $bladeString = '<x-button id="test-button" data-test="value"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('id="test-button"', $output);
        $this->assertStringContainsString('data-test="value"', $output);
    }
}
