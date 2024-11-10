<?php

namespace Modules\Components\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class RowTest extends TestCase
{

    public function testRendersARow()
    {
        $bladeString ='<x-row></x-row>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="row', $output);
    }

    public function testRendersAFlexRow()
    {
        $bladeString = '<x-row flex></x-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('d-flex', $output);
    }


    public function testRendersAFlexWrapRow()
    {
        $bladeString = '<x-row flex-wrap></x-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-wrap', $output);
    }


    public function testRendersAFlexNoWrapRow()
    {
        $bladeString = '<x-row flex-no-wrap></x-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-nowrap', $output);
    }
}
