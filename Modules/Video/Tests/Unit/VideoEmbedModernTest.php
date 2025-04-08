<?php

namespace Modules\Video\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Modules\Video\Support\VideoEmbed;

class VideoEmbedModernTest extends TestCase
{
    #[Test]
    public function youtube_embed_rendering(): void
    {
        $embed = new VideoEmbed();
        $embed->setEmbedCode('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $embed->setPlayEmbedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    #[Test]
    public function vimeo_embed_rendering(): void
    {
        $embed = new VideoEmbed();
        $embed->setEmbedCode('https://vimeo.com/123456789');
        $embed->setPlayEmbedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('player.vimeo.com/video/123456789', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    #[Test]
    public function embed_options_handling(): void
    {
        $embed = new VideoEmbed();
        $embed->setEmbedCode('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $embed->setPlayEmbedVideo(true);
        $embed->setAutoplay(true);
        $embed->setHideControls(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('autoplay=1', $result);
        $this->assertStringContainsString('controls=0', $result);
    }

    #[Test]
    public function uploaded_video_rendering(): void
    {
        $embed = new VideoEmbed();
        $embed->setUploadedVideoUrl('https://example.com/video.mp4');
        $embed->setPlayUploadedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('video.mp4', $result);
        $this->assertStringContainsString('<video', $result);
    }

    #[Test]
    public function invalid_url_handling(): void
    {
        $embed = new VideoEmbed();
        $embed->setEmbedCode('invalid-url');
        $embed->setPlayEmbedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('Can\'t read video from this source url', $result);
    }
}