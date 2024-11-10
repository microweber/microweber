<?php

namespace Modules\Components\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class InputTest extends TestCase
{
    public function testRendersAnInput()
    {
        $bladeString = '<x-input name="email"></x-input>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="form-control"', $output);
    }

    public function testRendersAnInputWithLabel()
    {
        $bladeString = '<x-input name="email" label="Email"></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('label for="email">Email</label>', $output);
    }

    public function testRendersAnInputWithPlaceholder()
    {
        $bladeString = '<x-input name="email" placeholder="Enter your email"></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('placeholder="Enter your email"', $output);
    }

    public function testRendersAnInputAsRequired()
    {
        $bladeString = '<x-input name="email" required></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('required', $output);
    }

    public function testRendersAnInputAsDisabled()
    {
        $bladeString = '<x-input name="email" disabled></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    public function testRendersAnInputWithHelpText()
    {
        $bladeString = '<x-input name="email" help="This is help text"></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="form-text text-muted">This is help text</small>', $output);
    }
}
