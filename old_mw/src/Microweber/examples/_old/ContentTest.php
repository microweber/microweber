<?php

if(!defined('MW_BARE_BONES')){
define('MW_BARE_BONES', 1);
}
require_once (dirname(__FILE__).'/../index.php');



class ContentTest extends PHPUnit_Framework_TestCase
{
     
	 public function testGetContent()
    {
         
		//getting content
		$data = get_content();
		
		//PHPUnit
        $this->assertEquals(true, is_array($data));
		$this->assertEquals(false, isset($data['error']));
		
		
		//testing limit and count
		$rand_limit = rand(1,100);
		$data = get_content('limit='.$rand_limit); 
		
		
		//PHPUnit
		$this->assertEquals(true, is_array($data));
		$this->assertEquals(false, isset($data['error']));
		
		
		$data = get_content('count=1');
 
 		//PHPUnit
        $this->assertEquals(true, is_int($data));
    }
	
	 public function testSaveContent(){
		 $table = MW_TABLE_PREFIX.'content';
		 $new_date = date('Y-m-d H:i:s');
		 $data = get_content('limit=10'); 
		 
		  //PHPUnit 
		 $this->assertEquals(true, is_array($data));
		 $this->assertEquals(false, isset($data['error']));
		 
		 mw_var("FORCE_SAVE", $table); //forcing save 
		 mw_var("FORCE_ANON_UPDATE", $table); //forcing save from user which is not the author
		 mw_var("FORCE_SAVE_CONTENT", $table); //forcing save  on the content
		 
		 if(is_array($data)){
			 foreach($data as $item){
				 $item['updated_on'] = $new_date;
				//  $item['debug'] = $new_date;
					$upd = save_content($item);
					
					//PHPUnit
				   $this->assertEquals($upd, $item['id']);
			 }
		 } 
		 
		 $data = get_content('updated_on='.$new_date); 
		 
		 //PHPUnit
		 $this->assertEquals(true, is_array($data));
		 $this->assertEquals(false, isset($data['error']));

	 }
	
	 public function testPublishAndUbpublished(){
		 
		 $table = MW_TABLE_PREFIX.'content';
		 $new_date = date('Y-m-d H:i:s');
		 $data = get_content('limit=10'); 
		  //PHPUnit
		 $this->assertEquals(true, is_array($data));
		    $this->assertEquals(false, isset($data['error']));

		 
		 
		 
		 
		 mw_var("FORCE_SAVE", $table); //forcing save 
		 mw_var("FORCE_ANON_UPDATE", $table); //forcing save from user which is not the author
		 mw_var("FORCE_SAVE_CONTENT", $table); //forcing save on the content
		  $unpublished_ids = array();
		  if(is_array($data)){
			 foreach($data as $item){
				$set_published =  content_set_unpublished($item['id']);
				
				 //PHPUnit
				$this->assertEquals(true, is_int($set_published));
				$unpublished_ids[]  = $item['id'];
			 }
		  }
		  $this->assertEquals(true, !empty($unpublished_ids));
		 
		   $data = get_content('ids='.implode(',',$unpublished_ids)); 
		   
		   //PHPUnit
		   $this->assertEquals(true, is_array($data));
		   $this->assertEquals(false, isset($data['error']));
		   
		   if(is_array($data)){
			 foreach($data as $item){
			    $set_published = api/content/set_published($item['id']);
				
				
				
				 //PHPUnit	 
   				$this->assertEquals(true, is_int($set_published));
			 }
		   }
	 }
	
	
	 
}