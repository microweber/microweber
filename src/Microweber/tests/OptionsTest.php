<?php

class OptionsTest extends TestCase
{


    public function testOptions()
    {
        $data = array();
        $data['option_value'] = date("Y-m-d");
        $data['option_key'] = 'a_test';
        $data['option_group'] = 'test';
        //   $data = json_encode($data);
        $save = save_option($data);
        d($data);
        $get = get_option('a_test', 'test');
        d($get);

    }


}