<?php

namespace Modules\Video\Tests\Unit;

use Tests\TestCase;
use Modules\Video\Support\VideoEmbed;

class VideoEmbedIntegrationTest extends TestCase
{
    public function test_youtube_embed_rendering()
    {
        $embed = new VideoEmbed();
        $embed->setEmbedCode('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        $embed->setPlayEmbedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function test_vimeo_embed_rendering()
    {
        $embed = new VideoEmbed();
        $embed->setEmbedCode('https://vimeo.com/123456789');
        $embed->setPlayEmbedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('player.vimeo.com/video/123456789', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function test_embed_options_handling()
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

    public function test_uploaded_video_rendering()
    {
        $embed = new VideoEmbed();
        $embed->setUploadedVideoUrl('https://example.com/video.mp4');
        $embed->setPlayUploadedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('video.mp4', $result);
        $this->assertStringContainsString('<video', $result);
    }

    public function test_invalid_url_handling()
    {
        $embed = new VideoEmbed();
        $embed->setEmbedCode('invalid-url');
        $embed->setPlayEmbedVideo(true);
        $result = $embed->render();
        
        $this->assertStringContainsString('Can\'t read video from this source url', $result);
    }
}