<?php

namespace Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Container;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContainerTest extends TestCase
{

    public function test_renders_a_container()
    {

        $bladeString ="<x-bootstrap-container></x-bootstrap-container>";

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="container', $output);
    }

    public function test_renders_a_fluid_container()
    {

        $bladeString ="<x-bootstrap-container fluid></x-bootstrap-container>";

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="container-fluid', $output);
    }
}
