<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class RadioTest extends TestCase
{
    use InteractsWithViews;

    #[Test]

    public function it_renders_a_radio(): void {
        $bladeString = '<x-radio name="options"></x-radio>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="form-check-input"', $output);
    }

    #[Test]

    public function it_renders_a_radio_with_label(): void {
        $bladeString = '<x-radio name="options" label="Option 1"></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('for="option-1">Option 1</label>', $output);
    }

    #[Test]

    public function it_renders_a_radio_as_checked(): void {
        $bladeString = '<x-radio name="options" checked></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('checked', $output);
    }

    #[Test]

    public function it_renders_a_radio_as_disabled(): void {
        $bladeString = '<x-radio name="options" disabled></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    #[Test]

    public function it_renders_a_radio_with_error(): void {
        $bladeString = '<x-radio name="options" :errors="$errors"></x-radio>';

        $view = $this->withViewErrors(['options' => 'The options field is required'])
            ->blade($bladeString);

        $view->assertSee('The options field is required');
        $view->assertSee('invalid-feedback');
    }

    #[Test]

    public function it_renders_a_radio_with_value(): void {
        $bladeString = '<x-radio name="options" value="1"></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('value="1"', $output);
    }
}
