<?php
class session {
	
	// start session
	public static function start() {
		if (! isset ( $_SESSION )) {
			session_start ();
		}
	}
	public static function set($name, $val) {
		if (! isset ( $_SESSION )) {
			session_start ();
		}
		
		$_SESSION [$name] = $val;
	}
	public static function get($name) {
		if (isset ( $_SESSION [$name] )) {
			return $_SESSION [$name];
		} else {
			return false;
		}
	}
	public static function del($name) {
		if (isset ( $_SESSION [$name] )) {
			unset ( $_SESSION [$name] );
		}
	}
	public static function end() {
		$_SESSION = array ();
		session_destroy ();
	}
}