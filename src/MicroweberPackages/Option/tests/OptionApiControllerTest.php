<?php
namespace MicroweberPackages\Option\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class OptionApiControllerTest extends TestCase
{
    public function testSaveOption()
    {
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

        $savedOption = get_option('website_name','website');
        $this->assertEquals($savedOption, 'test');

    }

    public function testSaveOptionWithHtml()
    {
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

        $savedOption = get_option('website_footer','website');
        $this->assertEquals($savedOption, '<h1>test</h1><script>alert(2)</script>');

    }

    public function testSaveOptionWithModuleHtml()
    {
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

        $savedOption = get_option('embed_url','video-20220314102431');
        $this->assertEquals($savedOption, '<iframe src="https://microweber.com/video.mp4" width="560" height="315"></iframe>');

    }
}
