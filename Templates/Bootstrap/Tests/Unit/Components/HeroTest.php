<?php

namespace Templates\Bootstrap\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;
use Templates\Bootstrap\View\Components\Hero;

class HeroTest extends TestCase
{
    public function testRendersAHero()
    {
        $bladeString = '<x-bootstrap-hero></x-bootstrap-hero>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="row align-items-center', $output);
    }

    public function testRendersAHeroWithImage()
    {
        $bladeString = '<x-bootstrap-hero image="path/to/image.jpg"></x-bootstrap-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('src="path/to/image.jpg"', $output);
    }

    public function testRendersAHeroWithTitle()
    {
        $bladeString = '<x-bootstrap-hero><x-slot name="title">Hero Title</x-slot></x-bootstrap-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Hero Title', $output);
    }

    public function testRendersAHeroWithContent()
    {
        $bladeString = '<x-bootstrap-hero><x-slot name="content">Hero Content</x-slot></x-bootstrap-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Hero Content', $output);
    }

    public function testRendersAHeroWithActions()
    {
        $bladeString = '<x-bootstrap-hero><x-slot name="actions"><a href="#" class="btn btn-primary">Action</a></x-slot></x-bootstrap-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Action', $output);
    }
}
