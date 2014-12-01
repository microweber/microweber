<?php

class OptionsTest extends TestCase
{


    public function testOptions()
    {
        $data = array();
        $now = date("YmdHis");
        $data['option_value'] = $now;
        $data['option_key'] = 'a_test';
        $data['option_group'] = 'test';

        $save = save_option($data);

        $get = get_option('a_test', 'test');

        $this->assertEquals($now, $get);


    }


}