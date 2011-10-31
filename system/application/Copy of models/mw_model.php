<?php

if (! defined ( 'BASEPATH' ))
	
	exit ( 'No direct script access allowed' );

/**
 * Microweber
 *
 * An open source CMS and application development framework for PHP 5.1 or newer
 *
 * @package     Microweber
 * @author      Peter Ivanov
 * @copyright   Copyright (c), Mass Media Group, LTD.
 * @license     http://ooyes.net
 * @link        http://ooyes.net
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------


/**
 * Core Class
 *
 * @desc mw class 
 * @access      public
 * @category    mw API
 * @subpackage      Core
 * @author      Peter Ivanov
 * @link        http://ooyes.net
 */

class Mw_model extends Model {
	
	function __construct() {
		
		parent::Model ();
	
	}
	
	function test() {
		print ('test') ;
	}

}



?>