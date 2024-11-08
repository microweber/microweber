<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Button;

class ButtonTest extends TestCase
{
    public function testRendersAButton()
    {
        $bladeString = '<x-button>Test</x-button>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="btn', $output);
        $this->assertStringContainsString('Test', $output);
    }

    public function testRendersAButtonWithType()
    {
        $bladeString = '<x-button type="success"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-success', $output);
    }

    public function testRendersAButtonWithSize()
    {
        $bladeString = '<x-button size="lg"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-lg', $output);
    }

    public function testRendersAButtonAsOutline()
    {
        $bladeString = '<x-button outline="true"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-outline-', $output);
    }

    public function testRendersADisabledButton()
    {
        $bladeString = '<x-button disabled="true"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    public function testRendersABlockButton()
    {
        $bladeString = '<x-button block="true"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('d-block', $output);
    }

    public function testRendersAButtonWithCustomClasses()
    {
        $bladeString = '<x-button class="custom-class"></x-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
