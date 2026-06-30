<?php

namespace Modules\Content\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

class DataFieldsTest extends TestCase
{
    #[Test]
    public function it_save(): void {
        app()->database_manager->extended_save_set_permission(true);
        $has_permission = app()->database_manager->extended_save_has_permission();
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

    #[Test]

    public function it_save_data_fields(): void {
        app()->database_manager->extended_save_set_permission(true);
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


    #[Test]


    public function it_data_fields_deleted_on_content_delete(): void {
        app()->database_manager->extended_save_set_permission(true);
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


        \Modules\Content\Models\Content::where('id', $id)->first()->delete();

        $attributes = content_data($id);
        $this->assertTrue(empty($attributes));

    }
}
