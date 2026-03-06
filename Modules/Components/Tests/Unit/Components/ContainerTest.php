<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ContainerTest extends TestCase
{
    #[Test]
    public function it_renders_a_container(): void {
        $bladeString ="<x-container></x-container>";

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="container', $output);
    }

    #[Test]

    public function it_renders_a_fluid_container(): void {
        $bladeString ="<x-container fluid></x-container>";

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="container-fluid', $output);
    }
}
