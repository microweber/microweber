<?php

namespace Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Row;

class RowTest extends TestCase
{
    /** @test */
    public function test_renders_a_row()
    {
        $bladeString ='<x-bootstrap-row></x-bootstrap-row>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="row', $output);
    }

    /** @test */
    public function test_renders_a_flex_row()
    {
        $bladeString = '<x-bootstrap-row flex></x-bootstrap-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('d-flex', $output);
    }

    /** @test */
    public function test_renders_a_flex_wrap_row()
    {
        $bladeString = '<x-bootstrap-row flex-wrap></x-bootstrap-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-wrap', $output);
    }

    /** @test */
    public function test_renders_a_flex_no_wrap_row()
    {
        $bladeString = '<x-bootstrap-row flex-no-wrap></x-bootstrap-row>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('flex-nowrap', $output);
    }
}
