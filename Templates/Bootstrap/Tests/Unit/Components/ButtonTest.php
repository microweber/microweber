<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Button;

class ButtonTest extends TestCase
{
    public function testRendersAButton()
    {
        $bladeString = '<x-bootstrap-button></x-bootstrap-button>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="btn', $output);
    }

    public function testRendersAButtonWithType()
    {
        $bladeString = '<x-bootstrap-button type="success"></x-bootstrap-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-success', $output);
    }

    public function testRendersAButtonWithSize()
    {
        $bladeString = '<x-bootstrap-button size="lg"></x-bootstrap-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-lg', $output);
    }

    public function testRendersAButtonAsOutline()
    {
        $bladeString = '<x-bootstrap-button outline="true"></x-bootstrap-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('btn-outline-', $output);
    }

    public function testRendersADisabledButton()
    {
        $bladeString = '<x-bootstrap-button disabled="true"></x-bootstrap-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    public function testRendersABlockButton()
    {
        $bladeString = '<x-bootstrap-button block="true"></x-bootstrap-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('d-block', $output);
    }

    public function testRendersAButtonWithCustomClasses()
    {
        $bladeString = '<x-bootstrap-button class="custom-class"></x-bootstrap-button>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
