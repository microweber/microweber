<?php

namespace MicroweberPackages\Option\tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;

#[RunTestsInSeparateProcesses]
class OptionApiControllerTest extends TestCase
{
    #[Test]
    public function it_save_option(): void {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $response = $this->call(
            'POST',
            route('api.option.save'),
            [
                'option_key' => 'website_name',
                'option_group' => 'website',
                'option_value' => 'test<script>alert(2)</script>',
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );

        $savedOption = get_option('website_name', 'website');
        $this->assertEquals($savedOption, 'test');

    }

    #[Test]

    public function it_save_option_with_html(): void {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $response = $this->call(
            'POST',
            route('api.option.save'),
            [
                'option_key' => 'website_footer',
                'option_group' => 'website',
                'option_value' => '<h1>test</h1><script>alert(2)</script>',
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );

        $savedOption = get_option('website_footer', 'website');
        $this->assertEquals($savedOption, '<h1>test</h1><script>alert(2)</script>');

        //remove the tag

        $response = $this->call(
            'POST',
            route('api.option.save'),
            [
                'option_key' => 'website_footer',
                'option_group' => 'website',
                'option_value' => '',
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );
        $savedOption = get_option('website_footer', 'website');
        $this->assertEquals($savedOption, null);

    }

    #[Test]

    public function it_save_option_with_module_html(): void {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $response = $this->call(
            'POST',
            route('api.option.save'),
            [
                'module' => 'video',
                'option_key' => 'embed_url',
                'option_group' => 'video-20220314102431',
                'option_value' => '<iframe src="https://microweber.com/video.mp4" width="560" height="315"></iframe>',
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );

        $savedOption = get_option('embed_url', 'video-20220314102431');
        $this->assertEquals($savedOption, '<iframe src="https://microweber.com/video.mp4" width="560" height="315"></iframe>');

    }

    #[Test]

    public function it_save_option_with_json_value(): void {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        $json = '[{"selector":"#mw-element-1700735216825","id":"animation1702634924706","animation":"rollIn","speed":1,"when":"onAppear"}]';
        $response = $this->call(
            'POST',
            route('api.option.save'),
            [
                'option_key' => 'animations-global',
                'option_group' => 'template',
                'option_value' => $json,
            ],
            [],//params
            $_COOKIE,//cookie
            [],//files
            $_SERVER //server
        );

        $savedOption = get_option('animations-global', 'template');
        $this->assertEquals($savedOption, $json);

    }
}
