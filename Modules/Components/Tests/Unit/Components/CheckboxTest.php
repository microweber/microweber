<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class CheckboxTest extends TestCase
{
    use InteractsWithViews;

    #[Test]

    public function it_renders_a_checkbox(): void {
        $bladeString = '<x-checkbox name="terms"></x-checkbox>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="form-check-input"', $output);
    }

    #[Test]

    public function it_renders_a_checkbox_with_label(): void {
        $bladeString = '<x-checkbox name="terms" label="I agree to the terms"></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('for="terms">I agree to the terms</label>', $output);
    }

    #[Test]

    public function it_renders_a_checkbox_as_checked(): void {
        $bladeString = '<x-checkbox name="terms" checked></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('checked', $output);
    }

    #[Test]

    public function it_renders_a_checkbox_as_disabled(): void {
        $bladeString = '<x-checkbox name="terms" disabled></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    #[Test]

    public function it_renders_a_checkbox_with_error(): void {
        $bladeString = '<x-checkbox name="terms" :errors="$errors"></x-checkbox>';

        $view = $this->withViewErrors(['terms' => 'The terms field is required'])
            ->blade($bladeString);

        $view->assertSee('The terms field is required');
        $view->assertSee('invalid-feedback');
    }

    #[Test]

    public function it_renders_a_checkbox_with_value(): void {
        $bladeString = '<x-checkbox name="terms" value="1"></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('value="1"', $output);
    }
}
