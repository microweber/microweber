<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Radio;

class RadioTest extends TestCase
{
    use InteractsWithViews;

    public function testRendersARadio()
    {
        $bladeString = '<x-radio name="options"></x-radio>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="form-check-input"', $output);
    }

    public function testRendersARadioWithLabel()
    {
        $bladeString = '<x-radio name="options" label="Option 1"></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('for="option-1">Option 1</label>', $output);
    }

    public function testRendersARadioAsChecked()
    {
        $bladeString = '<x-radio name="options" checked></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('checked', $output);
    }

    public function testRendersARadioAsDisabled()
    {
        $bladeString = '<x-radio name="options" disabled></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('disabled', $output);
    }

    public function testRendersARadioWithError()
    {
        $bladeString = '<x-radio name="options" :errors="$errors"></x-radio>';

        $view = $this->withViewErrors(['options' => 'The options field is required'])
            ->blade($bladeString);

        $view->assertSee('The options field is required');
        $view->assertSee('invalid-feedback');
    }

    public function testRendersARadioWithValue()
    {
        $bladeString = '<x-radio name="options" value="1"></x-radio>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('value="1"', $output);
    }
}
