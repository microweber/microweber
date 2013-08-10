<?php
if ( !isset( $_SESSION ) ) $_SESSION = array();
if(!defined('MW_BARE_BONES')){
define('MW_BARE_BONES', 1);
}
require_once (dirname(__FILE__).'/../index.php');



class DbExtendedTest extends PHPUnit_Framework_TestCase
{
    
	
	 public function testAdvanced()
    {
        
		$table = MW_TABLE_PREFIX.'content';
		$data = array();
		$data['id'] = 'anything';
		$data['non_ex'] = 'i do not exist and will be removed';
		$criteria = map_array_to_database_table($table, $data);
		
		
		
		 $diff = array_diff($data,$criteria);
		 
		 //PHPUnit
		 $this->assertEquals(true, count($diff) >0);
    }
	
	 
	 
}