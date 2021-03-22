<?php

namespace MicroweberPackages\Option\tests;

use MicroweberPackages\Core\tests\TestCase;
use MicroweberPackages\Option\Facades\Option as OptionFacade;
use MicroweberPackages\Option\Models\Option;

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

        $get = get_option('a_test', 'test'); // if this broke maybe you dont destroy MEMORY variable in Class when save OPTION

        $this->assertEquals($now, $get);

        $options = OptionFacade::getValue('a_test', 'test'); // static
        $this->assertEquals($now, $options);

        $option  = new Option();
        $options = $option->getValue('a_test', 'test'); //instance
        $this->assertEquals($now, $options);
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

        $get = mw()->option_manager->get('z_test', 'ztest'); // if this broke maybe you dont destroy MEMORY variable in Class when delete OPTION

        $this->assertTrue(in_array('website',$groups));
        $this->assertTrue(in_array('ztest',$groups2));
        $this->assertTrue($delete);
        $this->assertTrue(empty($get));


    }
}
