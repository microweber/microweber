<?php

require('../Formbuilder/Formbuilder.php');
require('FormbuilderTest.php');

/**
 * Static test suite.
 */
class testsSuite extends PHPUnit_Framework_TestSuite {


	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {}


	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {}


	/**
	 * Constructs the test suite handler.
	 */
	public function __construct() {
		$this->setName ( 'testsSuite' );
		$this->addTestSuite( 'FormbuilderTest' );
	}


	/**
	 * Creates the suite.
	 */
	public static function suite() {
		return new self ( );
	}
}


