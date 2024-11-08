<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Col;

class ColTest extends TestCase
{

    public function testRendersACol()
    {
        $bladeString ='<x-col></x-col>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12', $output);
    }


    public function testRendersAColWithCustomClasses()
    {
        $bladeString ='<x-col col="6" class="custom-class"></x-col>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6 custom-class', $output);
    }
}
