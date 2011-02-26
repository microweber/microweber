<?php

/**
 * 
 */

/**
 *
 */
class FormbuilderTest extends PHPUnit_Framework_TestCase {

	private $_container = array(
							'form_hash'=>'bb7d5c1fa425235aa666c1a78b3873e7732709af',
							'form_structure'=>'a:4:{i:0;a:3:{s:5:"class";s:10:"input_text";s:8:"required";s:5:"false";s:6:"values";s:4:"Name";}i:1;a:3:{s:5:"class";s:10:"input_text";s:8:"required";s:4:"true";s:6:"values";s:15:"E-mail Address?";}i:2;a:4:{s:5:"class";s:8:"checkbox";s:8:"required";s:5:"false";s:5:"title";s:11:"Choose any:";s:6:"values";a:4:{i:2;a:2:{s:5:"value";s:3:"PHP";s:7:"default";s:4:"true";}i:3;a:2:{s:5:"value";s:6:"jQuery";s:7:"default";s:4:"true";}i:4;a:2:{s:5:"value";s:3:"XML";s:7:"default";s:4:"true";}i:5;a:2:{s:5:"value";s:5:"Aspen";s:7:"default";s:5:"false";}}}i:3;a:4:{s:5:"class";s:5:"radio";s:8:"required";s:4:"true";s:5:"title";s:11:"Choose one:";s:6:"values";a:2:{i:2;a:2:{s:5:"value";s:3:"Yes";s:7:"default";s:4:"true";}i:3;a:2:{s:5:"value";s:2:"No";s:7:"default";s:5:"false";}}}}');


	private $_form_array = array
							(
								'0' =>
								array
								(
									'class' => 'input_text',
									'required' => 'false',
									'values' => 'Name'
								),
								'1' =>
								array
								(
									'class' => 'input_text',
									'required' => 'true',
									'values' => 'E-mail Address?'
								),
								'2' =>
								array
								(
									'class' => 'checkbox',
									'required' => 'false',
									'title' => 'Choose any:',
									'values' =>
									array
									(
										'2' =>
										array
										(
											'value' => 'PHP',
											'default' => 'true'
										),
										'3' =>
										array
										(
											'value' => 'jQuery',
											'default' => 'true'
										),
										'4' =>
										array
										(
											'value' => 'XML',
											'default' => 'true'
										),
										'5' =>
										array
										(
											'value' => 'Aspen',
											'default' => 'false'
										)
									)
								),
								'3' =>
								array
								(
									'class' => 'radio',
									'required' => 'true',
									'title' => 'Choose one:',
									'values' =>
									array
									(
										'2' =>
										array
										(
											'value' => 'Yes',
											'default' => 'true'
										),
										'3' =>
										array
										(
											'value' => 'No',
											'default' => 'false'
										)
									)
								)
							);

	private $_form_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<form>
<field type="input_text" required="false">Name</field>
<field type="input_text" required="true">E-mail Address?</field>
<field type="checkbox" required="false" title="Choose any:">
<checkbox checked="true">PHP</checkbox>
<checkbox checked="true">jQuery</checkbox>
<checkbox checked="true">XML</checkbox>
<checkbox checked="false">Aspen</checkbox>
</field>
<field type="radio" required="true" title="Choose one:">
<radio checked="true">Yes</radio>
<radio checked="false">No</radio>
</field>
</form>
';


	private $_form_html = '<form class="frm-bldr" method="post" action="%s">
<ol>
<li class="input_text" id="fld-name">
<label for="name">Name</label>
<input type="text" id="name" name="name" value="" />
</li>
<li class="input_text required" id="fld-email_address">
<label for="email_address">E-mail Address?</label>
<input type="text" id="email_address" name="email_address" value="" />
</li>
<li class="checkbox" id="fld-choose_any">
<span class="false_label">Choose any:</span>
<span class="multi-row clearfix">
<span class="row clearfix"><input type="checkbox" id="choose_any-php" name="choose_any-php" value="PHP" /><label for="choose_any-php">PHP</label></span>
<span class="row clearfix"><input type="checkbox" id="choose_any-jquery" name="choose_any-jquery" value="jQuery" /><label for="choose_any-jquery">jQuery</label></span>
<span class="row clearfix"><input type="checkbox" id="choose_any-xml" name="choose_any-xml" value="XML" /><label for="choose_any-xml">XML</label></span>
<span class="row clearfix"><input type="checkbox" id="choose_any-aspen" name="choose_any-aspen" value="Aspen" /><label for="choose_any-aspen">Aspen</label></span>
</span>
</li>
<li class="radio required" id="fld-choose_one">
<span class="false_label">Choose one:</span>
<span class="multi-row">
<span class="row clearfix"><input type="radio" id="choose_one-yes" name="choose_one" value="Yes" /><label for="choose_one-yes">Yes</label></span>
<span class="row clearfix"><input type="radio" id="choose_one-no" name="choose_one" value="No" /><label for="choose_one-no">No</label></span>
</span>
</li>
<li class="btn-submit"><input type="submit" name="submit" value="Submit" /></li>
</ol>
</form>
';


//	public function test_First() {
//		$this->markTestIncomplete ( "test_First test not implemented" );
//		include('arrayConstruct.php');
//		$dump = new DumpToArray;
//		print $dump->dumpArray($form->retrieve());
//	}

	public function test_loadedHash() {
		$form = new Formbuilder($this->_container);
		$this->assertEquals($this->_container['form_hash'], $form->hash());
	}


	public function test_Store() {
		$form = new Formbuilder($this->_form_array);
		$this->assertEquals($this->_container, $form->store());
	}


	public function test_Retrieve() {
		$form = new Formbuilder($this->_container);
		$this->assertEquals($this->_form_array, $form->retrieve());
	}


	public function test_GenerateXml() {
		$form = new Formbuilder($this->_container);
		$this->assertEquals($this->_form_xml, $form->generate_xml());
	}


	public function test_GenerateHtml() {
		$form = new Formbuilder($this->_container);
		$this->_form_html = sprintf($this->_form_html, $_SERVER['PHP_SELF']);
		$this->assertEquals($this->_form_html, $form->generate_html());
	}

	public function test_GenerateHtml_customAction() {
		$form = new Formbuilder($this->_container);
		$this->_form_html = sprintf($this->_form_html, 'fake/url/to/post/to');
		$this->assertEquals($this->_form_html, $form->generate_html('fake/url/to/post/to'));
	}

}
?>