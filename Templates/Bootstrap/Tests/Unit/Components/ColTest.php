<?php

namespace Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Col;

class ColTest extends TestCase
{
    /** @test */
    public function test_renders_a_col()
    {
        $bladeString ='<x-bootstrap-col></x-bootstrap-col>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12', $output);

    }

    /** @test */
    public function test_renders_a_col_with_custom_classes()
    {

        $bladeString ='<x-bootstrap-col col="6" class="custom-class"></x-bootstrap-col>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6 custom-class', $output);

      }
}
