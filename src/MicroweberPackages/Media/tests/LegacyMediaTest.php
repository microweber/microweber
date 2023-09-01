<?php

namespace MicroweberPackages\Media\tests;

use Illuminate\Support\Facades\Auth;
use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\User\Models\User;

class LegacyMediaTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

    }

    public function testSaveMedia()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        $picture = array(
            'rel_type' => 'content',
            'rel_id' => 3,
            'title' => 'My new pic',
            'media_type' => 'picture',
            'filename' => 'http://lorempixel.com/400/200/',
        );
        $saved_pic_id = save_media($picture);

        $picture_data = get_media_by_id($saved_pic_id);

        $src = $picture_data['filename'];
        $title = $picture_data['title'];

        $this->assertEquals(intval($saved_pic_id) > 0, true);
        $this->assertEquals(is_array($picture_data), true);
        $this->assertEquals($title, 'My new pic');
        $this->assertEquals($src, 'http://lorempixel.com/400/200/');
    }

    public function testDeleteMedia()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        $picture = array(
            'rel_type' => 'content',
            'rel_id' => 3,
            'title' => 'My new pic to del',
            'media_type' => 'picture',
            'filename' => 'http://lorempixel.com/400/200/',
        );
        $saved_pic_id = save_media($picture);
        $picture_data = get_media_by_id($saved_pic_id);
        $to_delete = array('id' => $saved_pic_id);
        $delete = delete_media($to_delete);
        $title = $picture_data['title'];
        $picture_null = get_media_by_id($saved_pic_id);

        $this->assertEquals($picture_null, false);
        $this->assertEquals(is_array($picture_data), true);
        $this->assertEquals($title, 'My new pic to del');
        $this->assertEquals(!($delete), false);
    }

    public function testSaveMediaArrayInFilename()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        $picture = array(
            'rel_type' => 'content',
            'rel_id' => 3,
            'title' => 'My new pic',
            'media_type' => 'picture',
            'filename' => ['http://lorempixel.com/400/200/', 'http://lorempixel.com/400/200/'],
        );
        $saved_pic_id = save_media($picture);

        $this->assertFalse($saved_pic_id);
    }
    public function testSaveMediaXssFilename()
    {
        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);
        $xss = '<style>@keyframes x{}</style><xss style="animation-name:x" onanimationend="alert(document.cookie)"></xss>';

        $picture = array(
            'rel_type' => 'content',
            'rel_id' => 3,
            'title' => 'My new pic to xss'.$xss,
            'description' => 'My new pic description xss'.$xss,
            'media_type' => 'picture',
            'filename' => 'http://lorempixel.com/400/200/'.$xss,
        );
        $saved_pic_id = save_media($picture);
        $picture_data = get_media_by_id($saved_pic_id);

        $this->assertNotEquals($picture_data['title'], $picture['title']);
        $this->assertNotEquals($picture_data['description'], $picture['description']);
        $this->assertNotEquals($picture_data['filename'], $picture['filename']);




    }
}
