<?php

namespace Modules\Log\Tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

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

    #[Test]

    public function it_log_write(): void {
        $this->set_values();
        $data = array();
        $data['value'] = $this->value;
        $data['field'] = 'log_test';
        $data['rel_type'] = 'log_unit_test';
        $save = app()->log_manager->save($data);

        $this->assertEquals(true, $save > 0);
    }

    #[Test]

    public function it_log_read(): void {
        $this->set_values();
        app()->log_manager->save($this->data);

        $data = array();
        $data['field'] = 'log_test';
        $data['value'] = $this->value;
        $get = app()->log_manager->get($data);
        foreach ($get as $item) {
            $this->assertEquals($this->value, $item['value']);
        }
    }

    #[Test]

    public function it_delete(): void {
        $this->set_values();
        app()->log_manager->save($this->data);

        $data = array();
        $data['field'] = 'log_test';
        $get = app()->log_manager->get($data);
        $deleted = array();
        foreach ($get as $item) {
            $deleted[] = $item['id'];
            $del = app()->log_manager->delete_entry($item);
            $this->assertEquals($del, $item['id']);
        }
        $data = array();
        $data['ids'] = $deleted;
        $get = app()->log_manager->get($data);
        $this->assertEquals(false, $get);
    }
}
