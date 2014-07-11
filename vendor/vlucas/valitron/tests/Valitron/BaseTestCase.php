<?php

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->tearDown();
	}

	public function tearDown()
	{
		$this->resetProperty('_lang');
		$this->resetProperty('_langDir');
		$this->resetProperty('_rules', array());
		$this->resetProperty('_ruleMessages', array());
	}

	protected function resetProperty($name, $value = null)
	{
		$prop = new ReflectionProperty('Valitron\Validator', $name);
		$prop->setAccessible(true);
		$prop->setValue($value);
		$prop->setAccessible(false);
	}
}
