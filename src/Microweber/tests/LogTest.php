<?php

class LogTest extends TestCase
{


    public function testLogWrite()
    {


        $data = array();
        $now = date("YmdHis");
        $data['value'] = $now;
        $data['field'] = 'log_test';
        $data['rel_type'] = 'log_unit_test';

        $save = mw()->log_manager->save($data);

        $this->assertEquals(true, $save > 1);
       // $get = get_option('a_test', 'test');

       // $this->assertEquals($now, $get);


    }


}