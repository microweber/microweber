<?php

namespace Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Row;

class RowTest extends TestCase
{

    public function testRendersARow()
    {
        $bladeString ='<x-bootstrap-row></x-bootstrap-row>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="row', $output);
    }

    public function testRendersAFlexRow()
    {
        $bladeString = '<x-bootstrap-row flex></x-bootstrap-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('d-flex', $output);
    }


    public function testRendersAFlexWrapRow()
    {
        $bladeString = '<x-bootstrap-row flex-wrap></x-bootstrap-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-wrap', $output);
    }


    public function testRendersAFlexNoWrapRow()
    {
        $bladeString = '<x-bootstrap-row flex-no-wrap></x-bootstrap-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-nowrap', $output);
    }
}
