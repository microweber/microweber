<?php

namespace Modules\Video\Tests\Unit;

use Tests\TestCase;
use Modules\Video\Support\VideoEmbed;

class VideoEmbedTest extends TestCase
{
    public function test_youtube_embed()
    {
        $embed = new VideoEmbed();
        $result = $embed->generate('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
        
        $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function test_vimeo_embed()
    {
        $embed = new VideoEmbed();
        $result = $embed->generate('https://vimeo.com/123456789');
        
        $this->assertStringContainsString('vimeo.com/123456789', $result);
        $this->assertStringContainsString('iframe', $result);
    }

    public function test_invalid_url_handling()
    {
        $embed = new VideoEmbed();
        $result = $embed->generate('invalid-url');
        
        $this->assertStringContainsString('Invalid video URL', $result);
    }

    public function test_embed_options()
    {
        $embed = new VideoEmbed();
        $result = $embed->generate(
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ['autoplay' => 1, 'controls' => 0]
        );
        
        $this->assertStringContainsString('autoplay=1', $result);
        $this->assertStringContainsString('controls=0', $result);
    }
}