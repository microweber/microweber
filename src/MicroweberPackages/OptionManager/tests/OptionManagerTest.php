<?php
namespace MicroweberPackages\OptionManager\tests;

class OptionManagerTest extends BaseTest
{
    public function testMigration()
    {
        $this->assertEquals([
            'id',
            'updated_at',
            'created_at',
            'option_key',
            'option_value',
            'option_key2',
            'option_value2',
            'position',
            'option_group',
            'name',
            'help',
            'field_type',
            'field_values',
            'module',
            'is_system',
            'option_value_prev',
        ], \Schema::getColumnListing('options'));
    }

    public function testOption()
    {
        $option = array();
        $option['option_value'] = '6 tochki';
        $option['option_key'] = 'BobiBobi';
        $option['option_group'] = 'kak_go_pravish_taka';

        $save = save_option($option);

        $this->assertNotEmpty($save);

        $get = get_option('BobiBobi', 'kak_go_pravish_taka');

        $this->assertEquals('6 tochki', $get);

    }

}