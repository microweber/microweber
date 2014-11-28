<?php namespace Microweber\Install\Schema;

class Options 
{

    public function get()
    {        
        return [
            'options' => [
                'updated_on' => 'dateTime',
                'created_on' => 'dateTime',

                'option_key' => 'string',
                'option_value' => 'text',
                'option_key2' => 'string',
                'option_value2' => 'text',
                'position' => 'integer',
                'option_group' => 'string',
                'name' => 'string',
                'help' => 'string',
                'field_type' => 'string',
                'field_values' => 'string',
                'module' => 'string',
                'is_system' => 'integer'
            ]
        ];
    }

}