<?php
use Valitron\Validator;

class StaticVsInstanceTest extends BaseTestCase
{
	public function testInstanceOverrideStaticLang()
	{
		Validator::lang('ar');
		new Validator(array(), array(), 'en');
		$this->assertEquals('ar', Validator::lang(),
							'instance defined lang should not replace static global lang');
	}

	/**
	 * Fix bug where rules messages added with Validator::addRule were replaced after creating validator instance
	 */
	public function testRuleMessagesReplacedAfterConstructor()
	{
		$customMessage = 'custom message';
		$ruleName = 'customRule';
		$fieldName = 'fieldName';
		Validator::addRule($ruleName, function() {}, $customMessage);
		$v = new Validator(array($fieldName => $fieldName));
		$v->rule($ruleName, $fieldName);
		$v->validate();
		$messages = $v->errors();
		$this->assertArrayHasKey($fieldName, $messages);
		$this->assertEquals(ucfirst("$fieldName $customMessage"), $messages[$fieldName][0]);
	}
}
