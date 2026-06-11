<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class VideoEmbedTest extends TestCase
{
    #[Test]
    public function it_renders_a_responsive_ratio_wrapper_when_a_url_is_set(): void
    {
        $output = Blade::render('<x-video-embed url="https://www.youtube.com/watch?v=abc" ratio="16x9" />');

        $this->assertStringContainsString('video-embed', $output);
        $this->assertStringContainsString('ratio ratio-16x9', $output);
    }

    #[Test]
    public function it_honours_a_custom_ratio(): void
    {
        $output = Blade::render('<x-video-embed url="https://vimeo.com/123" ratio="4x3" />');

        $this->assertStringContainsString('ratio-4x3', $output);
    }

    #[Test]
    public function it_renders_only_the_empty_wrapper_when_no_url_and_no_slot(): void
    {
        $output = Blade::render('<x-video-embed />');

        $this->assertStringContainsString('video-embed', $output);
        $this->assertStringNotContainsString('ratio ratio-', $output);
    }
}
