<?php

namespace Microweber\tests;


class DataFieldsTest extends TestCase {


    public function testSave() {

        mw()->database_manager->extended_save_set_permission(true);
        $has_permission = mw()->database_manager->extended_save_has_permission();
        $params = array(
            'title'         => 'My post with data fields',
            'content_type'  => 'post',
            'data_hi_there' => 'hello world',
            'is_active'     => 1);

        //saving
        $id = save_content($params);
        $data_fields = content_data($id);
        $this->assertEquals(intval($id) > 0, true);
        $this->assertEquals($data_fields['hi_there'], "hello world");
        $this->assertEquals(true, $has_permission);
    }


}