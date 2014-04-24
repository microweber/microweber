<?php

namespace FunctionsTest;


class OptionsTest extends \PHPUnit_Framework_TestCase
{

    public function testOptions()
    {

        $expected = "Option value " . rand();

        $option = array();
        $option['option_value'] = $expected;
        $option['option_key'] = 'my_unit_test';
        $option['option_group'] = 'unit_tests';

        $save_option = save_option($option);
        $get_option = get_option('my_unit_test', 'unit_tests');

        //PHPUnit
        $this->assertEquals(true, intval($save_option) > 0);

        $this->assertEquals($get_option, $expected);


    }

    public function testfileExt()
    {
        $fn = 'somefile.jpg.php.gif';
        $is_ext = get_file_extension($fn);

        $this->assertEquals($is_ext, 'gif');


    }


}