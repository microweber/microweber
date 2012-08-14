<?php
/*
 *
 * ================================================================================
 * Session class V2
 * ================================================================================
 * Filename :: /libs/sess.php
 * --------------------------------------------------------------------------------
 * Author :: 	Wieger Bontekoe <wieger_at_i-trends.nl> Website
 * ::					http://www.i-trends.nl (Information Trends)
 * --------------------------------------------------------------------------------
 * ================================================================================
 */
class session {
	var $sid;
	var $underline;
	var $started;
	var $initialised; // the initalised var
	var $rg;
	var $old;
	public static function start() {
		
		// $instance staticcen...
		static $instance;
		
		// check if we already done this.
		if (! isset ( $instance )) {
			$instance = new session ();
		} // end if
		  
		// Initilize
		if ($instance->initialised == FALSE)
			$instance->initialize ();
		
		return $instance;
	} // end function
	function initialize() {
		
		// Set var so we know we initialised
		$this->initialised = TRUE;
	} // end function
	function session() {
		$session_name = 'sid';
		if (session_name () != $session_name) {
			session_name ( $session_name );
		}
		
		if (! $this->started) {
			session_start ();
			$this->started = TRUE;
		}
	}
	// Setting a session var (like session_register())
	function set() {
		$v = func_get_args ();
		
		if (is_array ( $v [0] )) {
			foreach ( $v [0] as $k => $value ) {
				if ($this->check_var_syntax ( $k ))
					$this->reg_vars ( $k, $value );
			}
		} else {
			if (count ( $v ) % 2) {
				$this->errors [] = "Variable '" . $v [count ( $v ) - 1] . "' ignored";
				array_pop ( $v );
			}
			
			for($i = 0; $i < count ( $v ); $i += 2) {
				if ($this->check_var_syntax ( $v [$i] ))
					$this->reg_vars ( $v [$i], $v [$i + 1] );
			}
		}
	}
	function check_var_syntax($buff) {
		if (preg_match ( "!^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$!i", $buff ))
			return true;
		else {
			$this->errors [] = "Variable '<i>" . $buff . "</i>' ignored,<br>wrong syntax";
			return false;
		}
	}
	function reg_vars($n, $v) {
		if ($this->rg) {
			$GLOBALS [$n] = $v;
			session_register ( "$n" );
		} else {
			session_register ( "$n" );
			if ($this->old)
				$GLOBALS ['HTTP_SESSION_VARS'] [$n] = $v;
			else
				$GLOBALS ['_SESSION'] [$n] = $v;
		}
	}
	// Delete a session var
	function un_set($var) {
		return session_unregister ( $var );
	}
	
	// check if the var exists
	function is_set($var) {
		// instead of session_is_registered();
		if (isset ( $_SESSION [$var] )) {
			return $_SESSION [$var];
			// print("What you want if the session var is set");
		} else {
			return false;
		}
		// return(session_is_registered($var));
	}
	
	// Getting the var
	function get($var) {
		if ($this->is_set ( $var ))
			$this->$var = $_SESSION [$var];
		else
			$this->$var = 'Guest';
		
		return ($this->$var);
	}
	
	// Getting the current session id
	function sesid() {
		$this->$sid = session_id ();
		return (session_id ());
	}
	
	// fun, who am i ?
	function me() {
		return ($_SERVER ['PHP_SELF']);
	}
	
	// Drop everything
	function drop() {
		session_destroy ();
		$_COOKIE = array ();
	}
}

?>