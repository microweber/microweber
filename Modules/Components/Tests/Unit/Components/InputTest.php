<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class InputTest extends TestCase
{
    #[Test]
    public function it_renders_an_input(): void {
        $bladeString = '<x-input name="email"></x-input>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="form-control"', $output);
    }

    #[Test]

    public function it_renders_an_input_with_label(): void {
        $bladeString = '<x-input name="email" label="Email"></x-input>';
        $output = Blade::render($bladeString);

        $this->assertStringContainsString('for="email"', $output);
        $this->assertStringContainsString('class="form-label"', $output);
        $this->assertStringContainsString('>Email</label>', $output);
    }

    #[Test]

    public function it_renders_an_input_with_placeholder(): void {
        $bladeString = '<x-input name="email" placeholder="Enter your email"></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('placeholder="Enter your email"', $output);
    }

    #[Test]

    public function it_renders_an_input_as_required(): void {
        $bladeString = '<x-input name="email" required></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('required', $output);
    }

    #[Test]

    public function it_renders_an_input_as_disabled(): void {
        $bladeString = '<x-input name="email" disabled></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    #[Test]

    public function it_renders_an_input_with_help_text(): void {
        $bladeString = '<x-input name="email" help="This is help text"></x-input>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('This is help text', $output);
    }
}
