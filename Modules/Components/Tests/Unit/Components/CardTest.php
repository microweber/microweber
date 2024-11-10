<?php

namespace Modules\Components\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class CardTest extends TestCase
{

    public function testRendersACard()
    {
        $bladeString = '<x-card></x-card>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="card', $output);
    }


    public function testRendersACardWithDarkTheme()
    {
        $bladeString = '<x-card theme="dark"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-dark text-white', $output);
    }


    public function testRendersACardWithSuccessTheme()
    {
        $bladeString = '<x-card theme="success"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-success text-white', $output);
    }


    public function testRendersACardWithCustomClasses()
    {
        $bladeString = '<x-card class="custom-class"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
