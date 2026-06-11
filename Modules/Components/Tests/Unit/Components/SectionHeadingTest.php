<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class SectionHeadingTest extends TestCase
{
    #[Test]
    public function it_renders_a_centered_h2_with_the_title_slot(): void
    {
        $output = Blade::render('<x-section-heading>My Title</x-section-heading>');

        $this->assertStringContainsString('text-center', $output);
        $this->assertStringContainsString('<h2', $output);
        $this->assertStringContainsString('My Title', $output);
        $this->assertStringContainsString('data-mwplaceholder', $output);
    }

    #[Test]
    public function it_renders_the_subtitle_when_provided(): void
    {
        $output = Blade::render('<x-section-heading subtitle="A subtitle">Title</x-section-heading>');

        $this->assertStringContainsString('A subtitle', $output);
        $this->assertStringContainsString('text-muted', $output);
    }

    #[Test]
    public function it_honours_the_tag_and_align_props(): void
    {
        $output = Blade::render('<x-section-heading tag="h1" align="start">Title</x-section-heading>');

        $this->assertStringContainsString('<h1', $output);
        $this->assertStringContainsString('text-start', $output);
    }
}
