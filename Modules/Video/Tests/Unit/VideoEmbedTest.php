<?php

namespace Modules\Video\Tests\Unit;

use Tests\TestCase;
use Modules\Video\Support\VideoEmbed;

class VideoEmbedTest extends TestCase
{
   public function test_youtube_embed()
   {
       $embed = new VideoEmbed();
       $embed->setEmbedCode('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
       $embed->setPlayEmbedVideo(true);
       $result = $embed->render();

       $this->assertStringContainsString('youtube.com/embed/dQw4w9WgXcQ', $result);
       $this->assertStringContainsString('iframe', $result);
   }

   public function test_vimeo_embed()
   {
       $embed = new VideoEmbed();
       $embed->setEmbedCode('https://vimeo.com/123456789');
       $embed->setPlayEmbedVideo(true);
       $result = $embed->render();

       $this->assertStringContainsString('vimeo.com/video/123456789', $result);
       $this->assertStringContainsString('iframe', $result);
   }

   public function test_invalid_url_handling()
   {
       $embed = new VideoEmbed();
       $embed->setEmbedCode('invalid-url');
       $embed->setPlayEmbedVideo(true);
       $result = $embed->render();

       $this->assertStringContainsString('Can\'t read video from this source url', $result);
   }

   public function test_embed_options()
   {
       $embed = new VideoEmbed();
       $embed->setEmbedCode('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
       $embed->setPlayEmbedVideo(true);
       $embed->setAutoplay(true);
       $embed->setHideControls(true);
       $result = $embed->render();

       $this->assertStringContainsString('autoplay=1', $result);
       $this->assertStringContainsString('controls=0', $result);
       $this->assertStringContainsString('mute=1', $result);
   }
}
