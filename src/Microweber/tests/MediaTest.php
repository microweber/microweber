<?php

namespace Microweber\tests;


class MediaTest extends TestCase
{


    public function testSaveMedia()
    {

        $picture = array(
            'rel_type' => 'content',
            'rel_id' => 3,
            'title' => "My new pic",
            'media_type' => "picture",
            'src' => "http://lorempixel.com/400/200/"
        );
        $saved_pic_id = save_media($picture);

        $picture_data = get_media_by_id($saved_pic_id);

        $src = $picture_data['filename'];
        $title = $picture_data['title'];


        $this->assertEquals(intval($saved_pic_id) > 0, true);
        $this->assertEquals(is_array($picture_data), true);
        $this->assertEquals($title, "My new pic");
        $this->assertEquals($src, "http://lorempixel.com/400/200/");
    }

    public function testDeleteMedia()
    {
        $picture = array(
            'rel_type' => 'content',
            'rel_id' => 3,
            'title' => "My new pic to del",
            'media_type' => "picture",
            'src' => "http://lorempixel.com/400/200/"
        );
        $saved_pic_id = save_media($picture);
        $picture_data = get_media_by_id($saved_pic_id);
        $to_delete = array('id' => $saved_pic_id);
        $delete = delete_media($to_delete);
        $title = $picture_data['title'];
        $picture_null = get_media_by_id($saved_pic_id);

        $this->assertEquals($picture_null, false);
        $this->assertEquals(is_array($picture_data), true);
        $this->assertEquals($title, "My new pic to del");
        $this->assertEquals(!($delete), false);
    }


}