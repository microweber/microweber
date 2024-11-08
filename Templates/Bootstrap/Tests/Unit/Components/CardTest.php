<?php

namespace Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Card;

class CardTest extends TestCase
{

    public function testRendersACard()
    {
        $bladeString = '<x-bootstrap-card></x-bootstrap-card>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="card', $output);
    }


    public function testRendersACardWithDarkTheme()
    {
        $bladeString = '<x-bootstrap-card theme="dark"></x-bootstrap-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-dark text-white', $output);
    }


    public function testRendersACardWithSuccessTheme()
    {
        $bladeString = '<x-bootstrap-card theme="success"></x-bootstrap-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-success text-white', $output);
    }


    public function testRendersACardWithCustomClasses()
    {
        $bladeString = '<x-bootstrap-card class="custom-class"></x-bootstrap-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }
}
