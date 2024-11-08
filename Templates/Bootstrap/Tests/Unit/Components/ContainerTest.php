<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Container;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
