<?php

namespace Microweber\tests;

class LogTest extends TestCase
{
    private $data;

    public $value;

    public function set_values()
    {
        $now = date('YmdHis');
        $this->value = $now;
        $this->data = array (
            'value' => $this->value,
            'field' => 'log_test',
            'rel_type' => 'log_unit_test'
        );
    }

    public function testLogWrite()
    {
        $this->set_values();
        $data = array();
        $data['value'] = $this->value;
        $data['field'] = 'log_test';
        $data['rel_type'] = 'log_unit_test';
        $save = mw()->log_manager->save($data);

        $this->assertEquals(true, $save > 0);
    }

    public function testLogRead()
    {
        $this->set_values();
        mw()->log_manager->save($this->data);

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
        $this->set_values();
        mw()->log_manager->save($this->data);

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
