<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Checkbox;

class CheckboxTest extends TestCase
{
    use InteractsWithViews;

    public function testRendersACheckbox()
    {
        $bladeString = '<x-checkbox name="terms"></x-checkbox>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="form-check-input"', $output);
    }

    public function testRendersACheckboxWithLabel()
    {
        $bladeString = '<x-checkbox name="terms" label="I agree to the terms"></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('for="terms">I agree to the terms</label>', $output);
    }

    public function testRendersACheckboxAsChecked()
    {
        $bladeString = '<x-checkbox name="terms" checked></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('checked', $output);
    }

    public function testRendersACheckboxAsDisabled()
    {
        $bladeString = '<x-checkbox name="terms" disabled></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    public function testRendersACheckboxWithError()
    {
        $bladeString = '<x-checkbox name="terms" :errors="$errors"></x-checkbox>';

        $view = $this->withViewErrors(['terms' => 'The terms field is required'])
            ->blade($bladeString);

        $view->assertSee('The terms field is required');
        $view->assertSee('invalid-feedback');
    }

    public function testRendersACheckboxWithValue()
    {
        $bladeString = '<x-checkbox name="terms" value="1"></x-checkbox>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('value="1"', $output);
    }
}
