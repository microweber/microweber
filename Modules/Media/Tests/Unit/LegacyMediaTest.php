<?php

namespace Modules\Media\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use MicroweberPackages\User\Models\User;

class LegacyMediaTest extends TestCase
{
    #[Test]
    public function it_save_media(): void {

        $this->loginAsAdmin();

        $picture = array(
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
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

    #[Test]

    public function it_delete_media(): void {
        $this->loginAsAdmin();
        $picture = array(
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
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

    #[Test]

    public function it_save_media_array_in_filename(): void {
        $this->loginAsAdmin();
        $picture = array(
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
            'rel_id' => 3,
            'title' => 'My new pic',
            'media_type' => 'picture',
            'filename' => ['http://lorempixel.com/400/200/', 'http://lorempixel.com/400/200/'],
        );
        $saved_pic_id = save_media($picture);

        $this->assertFalse($saved_pic_id);
    }

    #[Test]

    public function it_save_media_xss_filename(): void {
        $this->loginAsAdmin();
        $xss = '<style>@keyframes x{}</style><xss style="animation-name:x" onanimationend="alert(document.cookie)"></xss>';

        $picture = array(
            'rel_type' => morph_name(\Modules\Content\Models\Content::class),
            'rel_id' => 3,
            'title' => 'My new pic to xss' . $xss,
            'description' => 'My new pic description xss' . $xss,
            'media_type' => 'picture',
            'filename' => 'http://lorempixel.com/400/200/' . $xss,
        );
        $saved_pic_id = save_media($picture);
        $picture_data = get_media_by_id($saved_pic_id);

        $this->assertNotEquals($picture_data['title'], $picture['title']);
        $this->assertNotEquals($picture_data['description'], $picture['description']);
        $this->assertNotEquals($picture_data['filename'], $picture['filename']);


    }
}
