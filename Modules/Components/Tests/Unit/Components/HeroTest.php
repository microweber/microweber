<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class HeroTest extends TestCase
{
    #[Test]
    public function it_renders_a_hero(): void {
        $bladeString = '<x-hero></x-hero>';

        $output = Blade::render($bladeString);

        $this->assertStringContainsString('class="row align-items-center', $output);
    }

    #[Test]

    public function it_renders_a_hero_with_image(): void {
        $bladeString = '<x-hero image="path/to/image.jpg"></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('src="path/to/image.jpg"', $output);
    }

    #[Test]

    public function it_renders_a_hero_with_title(): void {
        $bladeString = '<x-hero><x-slot name="title">Hero Title</x-slot></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Hero Title', $output);
    }

    #[Test]

    public function it_renders_a_hero_with_content(): void {
        $bladeString = '<x-hero><x-slot name="content">Hero Content</x-slot></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Hero Content', $output);
    }

    #[Test]

    public function it_renders_a_hero_with_actions(): void {
        $bladeString = '<x-hero><x-slot name="actions"><a href="#" class="btn btn-primary">Action</a></x-slot></x-hero>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('Action', $output);
    }
}
