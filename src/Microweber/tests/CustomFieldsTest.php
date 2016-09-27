<?php

namespace Microweber\tests;

class CustomFieldsTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        // set permission to save custom fields (normally available to admin users)
        mw()->database_manager->extended_save_set_permission(true);
    }

    public function testSaveCustomFields()
    {
        $my_product_id = 3;

        $custom_field = array(
            'field_name' => 'My test price',
            'field_value' => 10,
            'field_type' => 'price',
            'content_id' => $my_product_id,);

        // adding a custom field "price" to product
        $new_id = save_custom_field($custom_field);

        $are_values_here = db_get('table=custom_fields_values&custom_field_id=' . $new_id);

        $field = get_custom_field_by_id($new_id);

        $to_delete = array('id' => $new_id);
        $delete = delete_custom_field($to_delete);
        $field2 = get_custom_field_by_id($new_id);

        //check if values are deleted
        $are_values_gone = db_get('table=custom_fields_values&custom_field_id=' . $new_id);

        $this->assertEquals($field['name'], 'My test price');
        $this->assertEquals($field['value'], 10);
        $this->assertEquals(intval($new_id) > 0, true);
        $this->assertEquals(intval($delete) > 0, true);
        $this->assertEquals($field2, false);
        $this->assertEquals(is_array($are_values_here), true);
        $this->assertEquals(is_array($are_values_gone), false);
    }

    public function testSaveCustomFieldsArray()
    {
        $my_product_id = 21;
        $vals = array('Red', 'Blue', 'Green');
        $custom_field = array(
            'field_name' => 'Color',
            'field_value' => $vals,
            'field_type' => 'dropdown',
            'content_id' => $my_product_id,);

        //adding a custom field "Color" to product
        $new_id = save_custom_field($custom_field);
        $field = get_custom_field_by_id($new_id);

        $to_delete = array('id' => $new_id);
        $delete = delete_custom_field($to_delete);

        $this->assertEquals($field['name'], 'Color');
        $this->assertEquals($field['type'], 'dropdown');
        $this->assertEquals($field['value'], $vals);
        $this->assertEquals(intval($delete) > 0, true);
    }



    public function testProductWithCustomFields()
    {
        $params = array(
            'title' => 'My new product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields' => array(
                array('type'=>'dropdown','name'=>'Color', 'value' => array('Purple','Blue')),
                array('type'=>'price','name'=>'Price', 'value' => '9.99'),

            ),
            'is_active' => 1,);



        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);

        $this->assertEquals($saved_id, ($get['id']));


        $get_custom_fields = content_custom_fields($saved_id);



        // ....




    }
}
