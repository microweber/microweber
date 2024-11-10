<?php

namespace Modules\Components\Tests\Unit\Components;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class HeroTest extends TestCase
{
    public function testRendersAHero()
    {
        $bladeString = '<x-hero></x-hero>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="row align-items-center', $output);
    }

    public function testRendersAHeroWithImage()
    {
        $bladeString = '<x-hero image="path/to/image.jpg"></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('src="path/to/image.jpg"', $output);
    }

    public function testRendersAHeroWithTitle()
    {
        $bladeString = '<x-hero><x-slot name="title">Hero Title</x-slot></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Hero Title', $output);
    }

    public function testRendersAHeroWithContent()
    {
        $bladeString = '<x-hero><x-slot name="content">Hero Content</x-slot></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Hero Content', $output);
    }

    public function testRendersAHeroWithActions()
    {
        $bladeString = '<x-hero><x-slot name="actions"><a href="#" class="btn btn-primary">Action</a></x-slot></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Action', $output);
    }
}
