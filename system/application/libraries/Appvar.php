<?php
if (! defined ( 'BASEPATH' )) {
	exit ( 'No direct script access allowed' );
}

//http://codeigniter.com/wiki/Application_Variables/


class Appvar {
	private $appVar = array ();
	private $appVarPath = 'applicationVariables.data';
	private $accessed = false;
	private $changed = false;
	
	function __construct() {
		//$this->load->library ( 'session' );
		//global $CI;
		$CI = & get_instance ();
		$the_pass = $CI->session->userdata ( 'session_id' );
		$the_pass = md5 ( $the_pass );
		//@touch ( CACHEDIR . 'index.html' );
		

		$dir = CACHEDIR . "appvar/";
		if (is_dir ( $dir ) == false) {
			mkdir ( $dir );
		}
		$this->appVarPath = $dir . $the_pass . '.php';
		
		if (file_exists ( $this->appVarPath ) == false) {
			touch ( $this->appVarPath );
		}
	
	}
	
	function _firstAccess() {
		
		if (file_exists ( $this->appVarPath ) == true) {
			
			$data = file_get_contents ( $file );
			$this->appVar = unserialize ( $data );
		}
	}
	
	function set($key, $val) {
		$this->changed = true;
		$this->appVar [$key] = $val;
	}
	
	function get($key) {
		if ($this->accessed == false) {
			$this->_firstAccess ();
		}
		
		$this->accessed = true;
		if (isset ( $this->appVar [$key] ) == true) {
			return $this->appVar [$key];
		} else {
			return '';
		}
	}
	
	function __destruct() {
		if ($this->changed == true) {
			$data = serialize ( $this->appVar );
			
			$data = file_put_contents ( $this->appVarPath );
		
		}
	}
}
 