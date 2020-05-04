<?php

namespace Microweber\tests;

class OptionsTest extends TestCase
{
    public function testOptions()
    {
        $data = array();
        $now = date('YmdHis');
        $data['option_value'] = $now;
        $data['option_key'] = 'a_test';
        $data['option_group'] = 'test';
        $save = save_option($data);
        $get = get_option('a_test', 'test');
        $this->assertEquals($now, $get);
    }


    public function testOptionsManagerClass()
    {

        // test get and save
        $data = array();
        $now = date('YmdHis');
        $data['option_value'] = $now;
        $data['option_key'] = 'z_test';
        $data['option_group'] = 'ztest';
        $save = mw()->option_manager->save($data);
        $get = mw()->option_manager->get('z_test', 'ztest');
        $this->assertEquals($now, $get);


        // test other functions
        $groups = mw()->option_manager->get_groups(true);
        $groups2 = mw()->option_manager->get_groups();


        $delete = mw()->option_manager->delete('z_test', 'ztest');
        $get = mw()->option_manager->get('z_test', 'ztest');


        $this->assertTrue(in_array('website',$groups));
        $this->assertTrue(in_array('ztest',$groups2));
        $this->assertTrue($delete);
        $this->assertFalse($get);

 
    }
}
