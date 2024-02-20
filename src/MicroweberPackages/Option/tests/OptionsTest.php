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
    public function testOptionsWithNumericKey()
    {
        $data = array();
        $now = rand(1, 9999999);
        $option_group = rand(1, 9999999);
        $option_key = rand(1, 9999999);
        $data['option_value'] = $now;
        $data['option_key'] =  $option_key;
        $data['option_group'] =  $option_group;
        $save = save_option($data);

        $get = get_option( $option_key, $option_group); // if this broke maybe you dont destroy MEMORY variable in Class when save OPTION

        $this->assertEquals($now, $get);

        $options = OptionFacade::getValue( $option_key, $option_group); // static
        $this->assertEquals($now, $options);

        $option  = new Option();
        $options = $option->getValue( $option_key, $option_group); //instance
        $this->assertEquals($now, $options);

    }

    public function testOptionsSaveWithParseString()
    {
        $now = date('YmdHis');
        $str = "option_key=".$now."&option_group=test&option_value=test2";
        $save = save_option($str);
        $get =  get_option($now, 'test');
        $this->assertEquals('test2', $get);


        //test numeric key
        $now = rand(1, 9999999);
        $str = "option_key=".$now."&option_group=test&option_value=test2";
        $save = save_option($str);
        $get =  get_option($now, 'test');
        $this->assertEquals('test2', $get);
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
