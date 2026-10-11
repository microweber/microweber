<?php

namespace MicroweberPackages\Option\tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class OptionApiControllerTest extends TestCase
{
    #[Test]
    public function it_save_option(): void {
        $this->loginAsAdmin();

        $response = $this->post(
            route('api.option.save'),
            [
                'option_key' => 'website_name',
                'option_group' => 'website',
                'option_value' => 'test<script>alert(2)</script>',
            ]
        );
        $response->assertSuccessful();

        $savedOption = get_option('website_name', 'website');
        $this->assertEquals('test', $savedOption);

    }

    #[Test]

    public function it_save_option_with_html(): void {
        $this->loginAsAdmin();

        $response = $this->post(
            route('api.option.save'),
            [
                'option_key' => 'website_footer',
                'option_group' => 'website',
                'option_value' => '<h1>test</h1><script>alert(2)</script>',
            ]
        );

        $savedOption = get_option('website_footer', 'website');
        $this->assertEquals('<h1>test</h1><script>alert(2)</script>', $savedOption);

        //remove the tag

        $response = $this->post(
            route('api.option.save'),
            [
                'option_key' => 'website_footer',
                'option_group' => 'website',
                'option_value' => '',
            ]
        );
        $savedOption = get_option('website_footer', 'website');
        $this->assertEquals(null, $savedOption);

    }

    #[Test]

    public function it_save_option_with_module_html(): void {
        $this->loginAsAdmin();

        $response = $this->post(
            route('api.option.save'),
            [
                'module' => 'video',
                'option_key' => 'embed_url',
                'option_group' => 'video-20220314102431',
                'option_value' => '<iframe src="https://microweber.com/video.mp4" width="560" height="315"></iframe>',
            ]
        );

        $savedOption = get_option('embed_url', 'video-20220314102431');
        $this->assertEquals('<iframe src="https://microweber.com/video.mp4" width="560" height="315"></iframe>', $savedOption);

    }

    #[Test]

    public function it_save_option_with_json_value(): void {
        $this->loginAsAdmin();
        $json = '[{"selector":"#mw-element-1700735216825","id":"animation1702634924706","animation":"rollIn","speed":1,"when":"onAppear"}]';
        $response = $this->post(
            route('api.option.save'),
            [
                'option_key' => 'animations-global',
                'option_group' => 'template',
                'option_value' => $json,
            ]
        );

        $savedOption = get_option('animations-global', 'template');
        $this->assertEquals($json, $savedOption);

    }
}
