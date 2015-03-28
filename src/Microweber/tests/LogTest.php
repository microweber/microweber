<?php


namespace Microweber\tests;

class LogTest extends TestCase
{

    public $value;

    public function __construct()
    {
        $now = date("YmdHis");
        $this->value = $now;
    }

    public function testLogWrite()
    {
        $data = array();
        $data['value'] = $this->value;
        $data['field'] = 'log_test';
        $data['rel_type'] = 'log_unit_test';
        $save = mw()->log_manager->save($data);

        $this->assertEquals(true, $save > 0);
    }


    public function testLogRead()
    {

        $data = array();
        $data['field'] = 'log_test';
        $data['value'] = $this->value;
        $get = mw()->log_manager->get($data);
        foreach ($get as $item) {
            $this->assertEquals($this->value, $item['value']);
        }
    }

    public function testDelete()
    {


        $data = array();
        $data['field'] = 'log_test';
        $get = mw()->log_manager->get($data);
        $deleted = array();
        foreach ($get as $item) {
            $deleted[] = $item['id'];
            $del = mw()->log_manager->delete_entry($item);
            $this->assertEquals($del, $item['id']);
        }
        $data = array();
        $data['ids'] = $deleted;
        $get = mw()->log_manager->get($data);
        $this->assertEquals(false, $get);
    }

}