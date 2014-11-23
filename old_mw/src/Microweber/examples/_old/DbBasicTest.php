<?php
if ( !isset( $_SESSION ) ) $_SESSION = array();
if(!defined('MW_BARE_BONES')){
define('MW_BARE_BONES', 1);
}
require_once (dirname(__FILE__).'/../index.php');



class DbBasicTest extends PHPUnit_Framework_TestCase
{
    
	
	 public function testQuery()
    {
        
		//getting one row of content
		$data = get("one=true&table=content");
        $this->assertEquals(true, is_array($data));
		$table = MW_TABLE_PREFIX.'content';
		$rand = rand(0,1000);
		$sql = "update $table set title='Some title {$rand}' WHERE id=".$data['id'];
 		$q = db_q($sql);
		//PHPUnit
		$this->assertEquals(true, $q);
		 
		
		 $table = MW_TABLE_PREFIX.'content';
    	 $sql = "SELECT id FROM $table limit 10";
 		 $q = db_query($sql, $cache_id=crc32($sql),$cache_group= 'content/global');
		 
		 //PHPUnit
		 $this->assertEquals(true, is_array($q));
    }
	
	
	 public function testGet()
    {
        
		//getting content
		$data = get("table=content");
		//PHPUnit
        $this->assertEquals(true, is_array($data));
		
		
		//getting by id
		$data_by_id = db_get_id('content', $id=$data[0]['id']);
		//PHPUnit
		$this->assertEquals(true, is_array($data_by_id));
		$this->assertEquals($data[0]['id'], $data_by_id['id']);

		
		//testing getting users
		$data = get("table=users");
		//PHPUnit
        $this->assertEquals(true, is_array($data));

		
    }
	
	 public function testLogin(){
		 //you must be logged in in order to save
		$data = get("table=users&one=1&username=[not_null]");
		$this->assertEquals(true, is_array($data));
		$login = user_login('username='.$data['username'].'&password_hashed='.$data['password']);
		
		//PHPUnit
		$this->assertEquals(true, is_array($login));
 		
		$uid = user_id();
		//PHPUnit test if user is logged
		$this->assertEquals($data['id'], intval($uid));
		// 
		 
	 }
	public function testSave(){
		$table = MW_TABLE_PREFIX.'content';
		
		$data = get("one=true&table=content&limit=1");
		//PHPUnit 
		$this->assertEquals(true, is_array($data));
		
		
		
		
		$new_date = date('Y-m-d H:i:s');
		$data['updated_on'] = $new_date;
		$save = save_data($table, $data);
		
		//PHPUnit
		$this->assertEquals($save, $data['id']);
		
	}
	
	public function testMassSave(){
		$table = MW_TABLE_PREFIX.'content';
		$uid = user_id();
		$new_date = date('Y-m-d H:i:s');
		
		
		mw_var("FORCE_SAVE", $table); //forcing save 
		mw_var("FORCE_ANON_UPDATE", $table); //forcing save from user which is not the author
		
		$mass_save = mass_save("table=content&limit=5", 'updated_on='.$new_date); 
		//PHPUnit
		$this->assertEquals(true, is_array($mass_save));
		
		
		$data = get("table=content&updated_on=".$new_date); 
		$this->assertEquals(true, is_array($data));
	}
	 
}