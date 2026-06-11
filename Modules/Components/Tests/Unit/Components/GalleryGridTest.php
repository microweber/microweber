<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class GalleryGridTest extends TestCase
{
    #[Test]
    public function it_renders_a_row_with_the_default_gap_and_slot(): void
    {
        $output = Blade::render('<x-gallery-grid>tiles</x-gallery-grid>');

        $this->assertStringContainsString('row', $output);
        $this->assertStringContainsString('g-3', $output);
        $this->assertStringContainsString('tiles', $output);
    }

    #[Test]
    public function it_honours_a_custom_gap(): void
    {
        $output = Blade::render('<x-gallery-grid gap="g-5">x</x-gallery-grid>');

        $this->assertStringContainsString('g-5', $output);
    }

    #[Test]
    public function it_merges_custom_classes(): void
    {
        $output = Blade::render('<x-gallery-grid class="my-gallery">x</x-gallery-grid>');

        $this->assertStringContainsString('my-gallery', $output);
    }
}
