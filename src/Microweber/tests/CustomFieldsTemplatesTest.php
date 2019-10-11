<?php

namespace Microweber\tests;

class CustomFieldsTemplatesTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        // set permission to save custom fields (normally available to admin users)
        mw()->database_manager->extended_save_set_permission(true);
    }

    public function testTempalte()
    {
        $rel = 'module';
        $rel_id = 'layouts-'.rand(1111,9999).'-contact-form';
        $fields_csv_str = 'text, email, message';
        $fields_csv_array = explode(',', $fields_csv_str);

        $fields = mw()->fields_manager->make_default($rel, $rel_id, $fields_csv_str);
        foreach ($fields as $key=>$field_id) {


            $option = array();
            $option['option_value'] = 'bootstrap3/index.php';
            $option['option_key'] = 'data-template';
            $option['option_group'] = $field_id;
            $save = save_option($option);


            $html_output = mw()->fields_manager->make($field_id);
            var_dump($html_output);

            die();
        }
    }
}