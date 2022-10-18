<?php

namespace MicroweberPackages\Content\tests;

use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Core\tests\TestCase;

class DataFieldsTest extends TestCase
{
    public function testSave()
    {
        mw()->database_manager->extended_save_set_permission(true);
        $has_permission = mw()->database_manager->extended_save_has_permission();
        $params = array(
            'title' => 'My post with data fields',
            'content_type' => 'post',
            'data_hi_there' => 'hello world',
            'is_active' => 1, );

        //saving
        $id = save_content($params);
        $data_fields = content_data($id);
        $this->assertEquals(intval($id) > 0, true);
        $this->assertEquals($data_fields['hi_there'], 'hello world');
        $this->assertEquals(true, $has_permission);
    }

    public function testSaveDataFields()
    {
        mw()->database_manager->extended_save_set_permission(true);
        $val = 'hello there custom 1-'.rand();
        $val2 = 'hello there custom 2-'.rand();
        $params = array(
            'title' => 'My post with data attributes 1',
            'content_type' => 'post',
            'data_fields_something_custom' => $val,
            'data_fields_something_else_custom' => $val2,
            'is_active' => 1, );

        $id = save_content($params);
        $attributes = content_data($id);

        $this->assertEquals(intval($id) > 0, true);
        $this->assertEquals($attributes['something_custom'], $val);
        $this->assertEquals($attributes['something_else_custom'], $val2);
    }


    public function testDataFieldsDeletedOnContentDelete()
    {
        mw()->database_manager->extended_save_set_permission(true);
        $val = 'hello there custom 1-'.rand();
        $val2 = 'hello there custom 2-'.rand();
        $params = array(
            'title' => 'My post with data attributes 1',
            'content_type' => 'post',
            'data_fields_something_custom' => $val,
            'data_fields_something_else_custom' => $val2,
            'is_active' => 1, );

        $id = save_content($params);
        $attributes = content_data($id);

        $this->assertEquals(intval($id) > 0, true);
        $this->assertEquals($attributes['something_custom'], $val);
        $this->assertEquals($attributes['something_else_custom'], $val2);


        \MicroweberPackages\Content\Content::where('id', $id)->first()->delete();

        $attributes = content_data($id);
        $this->assertTrue(empty($attributes));

    }
}
