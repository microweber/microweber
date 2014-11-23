<?php

if(!defined('MW_BARE_BONES')){
define('MW_BARE_BONES', 1);
}
require_once (dirname(__FILE__).'/../index.php');



class CacheTest extends PHPUnit_Framework_TestCase
{
     
	 public function testCacheGroup()
    {
         
		 
		$cache_group = guess_cache_group(MW_TABLE_PREFIX.'media');
		  
 
 		//PHPUnit
        $this->assertEquals(true, $cache_group == 'media');
    }
	 
	 
	 
	  public function testCacheReadWrite()
    {
         
		 
		
		  $data = array('something' => 'some_value');
		 $cache_id = 'my_cache_id';
		$cache_content = cache_save($data, $cache_id, 'my_cache_group');
		$this->assertEquals(true, $cache_content);
  
  $cache_content = cache_get_content($cache_id, 'my_cache_group');
  	d($cache_content);
 		//PHPUnit
        $this->assertEquals(true, isset($cache_content['something']));
    }
	
	 
}