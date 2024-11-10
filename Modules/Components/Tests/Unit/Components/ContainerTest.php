<?php

namespace Modules\Components\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ContainerTest extends TestCase
{
    public function testRendersAContainer()
    {
        $bladeString ="<x-container></x-container>";

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="container', $output);
    }

    public function testRendersAFluidContainer()
    {
        $bladeString ="<x-container fluid></x-container>";

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="container-fluid', $output);
    }
}
