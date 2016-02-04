<?php

namespace Microweber\tests;


class DataAttributesTest extends TestCase {


    public function testSave() {


        $params = array(
            'title'         => 'My post with data attributes',
            'content_type'  => 'post',
            'attribute_something' => 'hello there',
            'attribute_something_else' => 'hello there 2',
            'is_active'     => 1);


        $id = save_content($params);
        $attributes = content_attributes($id);
        $this->assertEquals(intval($id) > 0, true);
        $this->assertEquals($attributes['something'], "hello there");
        $this->assertEquals($attributes['something_else'], "hello there 2");
    }

    public function testSaveCustomFields() {


        $params = array(
            'title'         => 'My post with data attributes 1',
            'content_type'  => 'post',
            'custom_field_something_custom' => 'hello there custom',
            'custom_field_something_else_custom' => 'hello there custom 2',
            'is_active'     => 1);


        $id = save_content($params);
        $attributes = content_custom_fields($id);
        dd($attributes);
        $this->assertEquals(intval($id) > 0, true);
        $this->assertEquals($attributes['custom_field_something_custom'], "hello there custom");
        $this->assertEquals($attributes['custom_field_something_else_custom'], "hello there custom 2");
    }

}