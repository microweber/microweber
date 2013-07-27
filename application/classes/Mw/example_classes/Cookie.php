<?php
namespace mw;
// Secure encryption/decryption, verification, and storage for cookies
class Cookie {
	static function encrypt($s, $k, $a = MCRYPT_RIJNDAEL_256, $m = MCRYPT_MODE_CBC) {
		$s = mcrypt_encrypt ( $a, hash ( 'sha256', $k, TRUE ), $s, $m, $iv = mcrypt_create_iv ( mcrypt_get_iv_size ( $a, $m ), MCRYPT_RAND ) ) . $iv;
		return hash ( 'sha256', $k . $s ) . $s;
	}
	static function decrypt($s, $k, $a = MCRYPT_RIJNDAEL_256, $m = MCRYPT_MODE_CBC) {
		$h = substr ( $s, 0, 64 );
		$t = substr ( $s, 64 );
		if (hash ( 'sha256', $k . $t ) != $h)
			return;
		$iv = substr ( $t, - mcrypt_get_iv_size ( $a, $m ) );
		return rtrim ( mcrypt_decrypt ( $a, hash ( 'sha256', $k, TRUE ), substr ( $t, 0, - strlen ( $iv ) ), $m, $iv ), "\x0" );
	}
	static function get($k, $c = 0) {
		$c = $c ?  : c ( 'cookie' );
		if ($v = v ( $_COOKIE [$k] ))
			if ($v = json_decode ( self::decrypt ( $v, $c ['key'] ) ))
				if ($v [0] < $c ['expires'])
					return ( array ) $v [1];
	}
	static function set($k, $v, $c = 0) {
		extract ( $c ?  : c ( 'cookie' ) );
		setcookie ( $k, ($v ? self::encrypt ( json_encode ( array (
				time (),
				$v 
		) ), $key ) : ''), $expires, $path, $domain, $secure, $httponly );
	}
	static function token() {
		return md5 ( str_shuffle ( chr ( mt_rand ( 32, 126 ) ) . uniqid () . microtime ( TRUE ) ) );
	}
}