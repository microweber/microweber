<?php
/**
 * Users Online class
 *
 * Manages active users
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Add-Ons
 * @author		Leonardo Monteiro Bersan de Araújo
 * @link		hhttp://codeigniter.com/wiki/Library: Online_Users
 */

//error_reporting(E_ALL);

class OnlineUsers{
	var $file="usersonline.tmp";
	var $data;
	var $ip;
	
	function OnlineUsers(){
		$this->ip=$_SERVER['REMOTE_ADDR'];
		$this->data = @unserialize(file_get_contents($this->file));
		if(!$this->data) $this->data=array();
		
		$timeout = time()-120;
		
		//If it's the first hit, add the information to database
		if(!isset($this->data[$this->ip])){
			$this->data[$this->ip]['time'] = time();
			$this->data[$this->ip]['uri'] = $_SERVER['REQUEST_URI'];
			$CI =& get_instance();
			//Loads the USER_AGENT class if it's not loaded yet
			if(!isset($CI->agent)) { $CI->load->library('user_agent'); $class_loaded = true; }
			if($CI->agent->is_robot())
				$this->data[$this->ip]['bot'] = $CI->agent->robot();
			else
				$this->data[$this->ip]['bot'] = false;
			
			//Destroys the USER_AGENT class so it can be loaded again on the controller
			if($class_loaded) unset($class_loaded, $CI->agent);
		}
		else
		{
			$this->data[$this->ip]['time'] = time();
			$this->data[$this->ip]['uri'] = $_SERVER['REQUEST_URI'];
		}
		
		//Removes expired data			
		foreach($this->data as $key => $value){
			if($value['time'] <= $timeout) unset($this->data[$key]);
		}
		
		$this->_save();
		
	}
	
	//this function return the total number of online users
	function total_users(){
		return count($this->data);
	}
	//this function return the total number of online robots
	function total_robots(){
		$i=0;
		foreach($this->data as $value)
		{
			if($value['is_robot']) $i++;
		}
		return $i;
	}
	
	//Used to set custom data
	function set_data($data=false, $force_update=false){
		if(!is_array($data)){ return false;}

		$tmp=false; //Used to control if there are changes

		foreach($data as $key => $value)
		{
			if(!isset($this->data[$this->ip]['data'][$key]) || $force_update)
			{
				$this->data[$this->ip]['data'][$key] = $value; 
				$tmp=true;
			}
		}
		
		//Check if the user's already have this setting and skips the wiriting file process (saves CPU)
		if(!$tmp) return false;
		return $this->_save();
	}	
	//
	function get_info(){
		return @$this->data;
	}	
	
	//Save current data into file
	function _save()
	{
		$fp = fopen($this->file,'w');
		flock($fp, LOCK_EX);
		$write = fwrite($fp, serialize($this->data));
		fclose($fp);
		//var_dump($this->data);
		return $write;
	}
}