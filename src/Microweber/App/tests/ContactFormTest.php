<?php

namespace Microweber\tests;

class ContactFormTest extends TestCase
{
	
	public function testFormSubmit() {
		
		$params = array();
		$params['for_id'] = '1234';
		$params['for'] = 'test';
		
		
		// Disable captcha
		save_option(array(
			'option_group'=>$params['for_id'],
			'option_key'=> 'disable_captcha',
			'option_value'=> 'y'
		));
		
		$response = mw()->forms_manager->post($params);
		
		$this->assertArrayHasKey('success', $response);
		
		/* var_dump($response);
		
		
		$response = mw()->forms_manager->get_entires();
		
		var_dump($response); */
		
	}
}