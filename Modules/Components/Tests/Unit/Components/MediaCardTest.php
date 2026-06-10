<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class MediaCardTest extends TestCase
{
    #[Test]
    public function it_renders_title_and_description(): void
    {
        $output = Blade::render(
            '<x-media-card title="Creative Design" description="Explore new ideas." />'
        );

        $this->assertStringContainsString('class="card', $output);
        $this->assertStringContainsString('card-title', $output);
        $this->assertStringContainsString('Creative Design', $output);
        $this->assertStringContainsString('Explore new ideas.', $output);
    }

    #[Test]
    public function it_renders_a_play_overlay_for_video_media(): void
    {
        $output = Blade::render(
            '<x-media-card title="Video Showcase" image="/thumb.jpg" link="/watch" media-type="video" />'
        );

        $this->assertStringContainsString('mdi-play', $output);
        $this->assertStringContainsString('/thumb.jpg', $output);
    }

    #[Test]
    public function it_omits_the_play_overlay_for_image_media(): void
    {
        $output = Blade::render(
            '<x-media-card title="Photo" image="/thumb.jpg" link="/view" media-type="image" />'
        );

        $this->assertStringNotContainsString('mdi-play', $output);
    }
}
