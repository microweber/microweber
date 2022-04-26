<?php

namespace MicroweberPackages\Form\tests;

use MicroweberPackages\Core\tests\TestCase;

class CustomFieldsTest extends TestCase
{
    public $template_name = 'default';

    public function setUp():void
    {
        if (!defined('TEMPLATE_NAME')) {
            define('TEMPLATE_NAME', $this->template_name);
        }
        parent::setUp();

        app()->content_manager->define_constants(['active_site_template' => $this->template_name]);

        // set permission to save custom fields (normally available to admin users)
        mw()->database_manager->extended_save_set_permission(true);

        if (!defined('ACTIVE_TEMPLATE_DIR')) {
            $this->app->content_manager->define_constants();
        }

    }

    public function testMakeDefaultFields() {


        for ($i = 1; $i <= 10; $i++) {

            $rel = 'module';
            $rel_id = 'layouts-'.rand(1111,9999).$i.'-contact-form';
            $fields_csv_str = 'PersonName[type=text,field_size=6,show_placeholder=true],';
            $fields_csv_str .= 'PersonTelephone[type=phone,field_size=6,show_placeholder=true],';
            $fields_csv_str .= 'PersonMessage[type=textarea,field_size=12,show_placeholder=true]';

            $fields = mw()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);

            $this->assertTrue((count($fields) == 3), true);

            $field1 = mw()->fields_manager->make($fields[0]);
            $field2 = mw()->fields_manager->make($fields[1]);
            $field3 = mw()->fields_manager->make($fields[2]);

            // Check person name
            $check_input_if_exists = false;
            if (strpos($field1, 'placeholder="PersonName"') !== false) {
                $check_input_if_exists = true;
            }
            $this->assertEquals($check_input_if_exists, true);

            $check_input_if_exists = false;
            if (strpos($field1, 'class="mw-flex-col-md-6"') !== false) {
                $check_input_if_exists = true;
            }
            $this->assertEquals($check_input_if_exists, true);

            // Check person telephone
            $check_input_if_exists = false;

            if (strpos($field2, 'name="persontelephone"') !== false) {
                $check_input_if_exists = true;
            }
            $this->assertEquals($check_input_if_exists, true);

            $check_input_if_exists = false;
            if (strpos($field2, 'class="mw-flex-col-md-6"') !== false) {
                $check_input_if_exists = true;
            }
            $this->assertEquals($check_input_if_exists, true);

            // Check person message
            $check_input_if_exists = false;
            if (strpos($field3, 'placeholder="PersonMessage"') !== false) {
                $check_input_if_exists = true;
            }
            $this->assertEquals($check_input_if_exists, true);

            $check_input_if_exists = false;
            if (strpos($field3, 'class="mw-flex-col-md-12"') !== false) {
                $check_input_if_exists = true;
            }
            $this->assertEquals($check_input_if_exists, true);

        }
    }

    public function testCustomFieldsPost() {

    	$rel = 'module';
    	$rel_id = 'layouts-'.rand(1111,9999).'-contact-form';

    	$params = array();
    	$params['for_id'] = $rel_id;
    	$params['for'] = $rel;
    	$params['message'] = 'This is my message.';
    	$params['email'] = 'bobi@microweber.com';
    	$params['second_email'] = 'bobi@microweber.com';
    	$params['website'] = 'bobi.microweber.com';
    	$params['phone'] = '0885451012';
    	$params['number'] = '123456789';
    	$params['select'] = array('1123', '213213');

    	// Disable captcha
    	save_option(array(
    		'option_group'=>$params['for_id'],
    		'option_key'=> 'disable_captcha',
    		'option_value'=> 'y'
    	));

    	$response = mw()->forms_manager->post($params);

    	$entry = mw()->forms_manager->get_entires('single=1&id=' . $response['id']);

    	$this->assertEquals($entry['custom_fields']['message'], $params['message']);
    	$this->assertEquals($entry['custom_fields']['email'], $params['email']);
    	$this->assertEquals($entry['custom_fields']['second_email'], $params['second_email']);
    	$this->assertEquals($entry['custom_fields']['website'], $params['website']);
    	$this->assertEquals($entry['custom_fields']['phone'], $params['phone']);
    	$this->assertEquals($entry['custom_fields']['number'], $params['number']);

    }

    public function testCustomFieldHtmlOutput() {

    	$rel = 'module';
    	$rel_id = 'layouts-'.rand(1111,9999).'-contact-form';
    	$fields_csv_str = 'price, text, radio, select, checkbox, number, phone, website, email, address, date, time, fileupload, property, hidden, message';
    	$fields_csv_array = explode(',', $fields_csv_str);

    	$fields = mw()->fields_manager->makeDefault($rel, $rel_id, $fields_csv_str);

    	foreach ($fields as $key=>$field_id) {

    		$html_output = mw()->fields_manager->make($field_id);

    		$field_name = trim($fields_csv_array[$key]);

    		if ($field_name == 'price') {

    			$check_input_if_exists = false;
    			if ((strpos($html_output, 'name="price"')) !== false && (strpos($html_output, '<input') !== false) && (strpos($html_output, 'type="hidden"') !== false)) {
    				$check_input_if_exists = true;
    			}

    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'phone') {

    			$check_input_if_exists = false;
    			if (strpos($html_output, 'name="phone"') !== false) {
    				$check_input_if_exists = true;
    			}

    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'date') {

    			$check_input_if_exists = false;
    			if (strpos($html_output, 'name="date"') !== false) {
    				$check_input_if_exists = true;
    			}

    			$this->assertEquals($check_input_if_exists, true);
    		}


    		if ($field_name == 'fileupload') {

    			$check_input_if_exists = false;
    			if (strpos($html_output, 'name="fileupload"') !== false) {
    				$check_input_if_exists = true;
    			}

    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'hidden') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, 'type="hidden"') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'checkbox') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, 'type="checkbox"') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'radio') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, 'type="radio"') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'time') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, 'type="time"') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'number') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, 'type="number"') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'name') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, 'type="text"') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'email') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, 'type="email"') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    		if ($field_name == 'message') {
    			$check_input_if_exists = false;
    			if (strpos($html_output, '<textarea') !== false) {
    				$check_input_if_exists = true;
    			}
    			$this->assertEquals($check_input_if_exists, true);
    		}

    	}
    }

    public function testSaveCustomFields()
    {
        $my_product_id = 3;

        $custom_field = array(
            'name' => 'My test price',
            'value' => 10,
            'type' => 'price',
            'content_id' => $my_product_id,
        );

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
            'name' => 'color',
            'value' => $vals,
            'type' => 'dropdown',
            'content_id' => $my_product_id,);

        //adding a custom field "Color" to product
        $new_id = save_custom_field($custom_field);
        $field = get_custom_field_by_id($new_id);

        $to_delete = array('id' => $new_id);
        $delete = delete_custom_field($to_delete);

        $this->assertEquals($field['name'], 'color');
        $this->assertEquals($field['type'], 'dropdown');

        $this->assertEquals($field['value'], 'Red');
        $this->assertEquals($field['values'], $vals);
        $this->assertEquals(intval($delete) > 0, true);
    }



    public function testProductWithCustomFields()
    {
        $params = array(
            'title' => 'My new product',
            'content_type' => 'product',
            'subtype' => 'product',
            'custom_fields_advanced' => array(
                array('type'=>'dropdown','name'=>'Color', 'value' => array('Purple','Blue')),
                array('type'=>'price','name'=>'Price', 'value' => '9.99'),

            ),
            'is_active' => 1,);

        $saved_id = save_content($params);
        $get = get_content_by_id($saved_id);

        $this->assertEquals($saved_id, $get['id']);

        $get_custom_fields = content_custom_fields($saved_id);


        $this->assertEquals(count($get_custom_fields), 2);



    }

}
