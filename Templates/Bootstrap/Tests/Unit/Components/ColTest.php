<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Col;

class ColTest extends TestCase
{

    public function testRendersACol()
    {
        $bladeString = '<x-col size="6"></x-col>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6', $output);
    }

    public function testRendersAColWithDifferentSizes()
    {
        $bladeString = '<x-col size="4" sizeLg="8" sizeSm="6" sizeXxl="12"></x-col>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="col-sm-6 col-md-4 col-lg-8 col-xl-4 col-xxl-12', $output);
    }

    public function testRendersAColWithCustomClasses()
    {
        $bladeString = '<x-col class="custom-class"></x-col>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('custom-class', $output);
    }

    public function testRendersAColWithFullWidth()
    {
        $bladeString = '<x-col size="12"></x-col>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12', $output);
    }

    public function testRendersAColWithResponsiveSizes()
    {
        $bladeString = '<x-col size="6" sizeSm="4" sizeLg="2" sizeXl="1"></x-col>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('class="col-sm-4 col-md-6 col-lg-2 col-xl-1 col-xxl-6', $output);
    }
}
