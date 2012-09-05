<?php
class menu extends orm {
	static $t = 'firecms_menus';
	static $table_name = 'firecms_menus';
	static $k = 'id';
	static $f = 'user_id';
	static $db;
	function __construct() {
		menu::$db = new db ( c ( 'db' ) );
	}
	
	
	
	/*
	 * static $r = array( 'posts' => 'Model_Post', // Has many posts 'roles' =>
	 * 'Model_Role', // Has many roles 'profile' => 'Model_Profile', // Has one
	 * profile ); static $b = array('roles' => 'Model_Role'); // Belongs to
	 * roles
	 */
}