<?php
namespace MicroweberPackages\Modules\Video\tests;

use Illuminate\Support\Str;
use MicroweberPackages\Core\tests\TestCase;

class VideoBackendTest extends TestCase
{
    public function testModule()
    {

        // Situation 1: $code is empty
        $params = [
            'id'=>time() . 'mod'
        ];
        $renderData = render_video_module($params);
        $this->assertIsArray($renderData);
        $this->assertArrayHasKey('code', $renderData);
        $this->assertTrue(Str::contains($renderData['code'], 'Can\'t read video from this source url.'));


        // Situation 2: $code is yutube url
        save_option('embed_url', 'https://www.youtube.com/watch?v=9bZkp7q19f0', $params['id']);
        $renderData = render_video_module($params);
        $this->assertIsArray($renderData);
        $this->assertArrayHasKey('code', $renderData);
        $this->assertTrue(Str::contains($renderData['code'], 'http://youtube.com/embed/9bZkp7q19f0'));
        $this->assertSame($renderData['provider'], 'youtube');


        // Situation 3: $code is vimeo url
        save_option('embed_url', 'https://vimeo.com/1084537', $params['id']);
        $renderData = render_video_module($params);
        $this->assertIsArray($renderData);
        $this->assertArrayHasKey('code', $renderData);
        $this->assertTrue(Str::contains($renderData['code'], 'https://player.vimeo.com/video/1084537'));
        $this->assertTrue(Str::contains($renderData['code'], 'https://player.vimeo.com/api/player.js'));
        $this->assertSame($renderData['provider'], 'vimeo');



        // Situation 4: $code is metacafe url
        save_option('embed_url', 'http://www.metacafe.com/watch/11419757/this-is-what-happens-when-you-try-to-rob-a-7-eleven-in-scotland/', $params['id']);
        $renderData = render_video_module($params);
        $this->assertIsArray($renderData);
        $this->assertArrayHasKey('code', $renderData);
        $this->assertTrue(Str::contains($renderData['code'], 'http://metacafe.com/embed/11419757'));
        $this->assertSame($renderData['provider'], 'metacafe');



        // Situation 5: $code is yutube url & settings
        save_option('embed_url', 'https://www.youtube.com/watch?v=9bZkp7q19f0', $params['id']);
        save_option('autoplay', 1, $params['id']);
        save_option('width', 560, $params['id']);
        save_option('height', 315, $params['id']);
        save_option('loop', 1, $params['id']);
        save_option('hide_controls', 1, $params['id']);
        save_option('muted', 1, $params['id']);
        save_option('lazyload', 1, $params['id']);

        $renderData = render_video_module($params);

        $this->assertIsArray($renderData);
        $this->assertArrayHasKey('code', $renderData);
        $this->assertTrue(Str::contains($renderData['code'], 'video_background_cover.svg'));
        $this->assertTrue(Str::contains($renderData['code'], 'http://youtube.com/embed/9bZkp7q19f0'));
        $this->assertTrue(Str::contains($renderData['code'], 'mute=1'));
        $this->assertTrue(Str::contains($renderData['code'], 'autoplay=1'));
        $this->assertTrue(Str::contains($renderData['code'], 'wmode=transparent'));
        $this->assertTrue(Str::contains($renderData['code'], 'allow="autoplay"'));
        $this->assertTrue(Str::contains($renderData['code'], 'width="560px"'));
        $this->assertTrue(Str::contains($renderData['code'], 'height="315px"'));
        $this->assertSame($renderData['provider'], 'youtube');

    }
}
