<?php
// Setup system and load controller
define ( 'T', microtime () );
define ( 'M', memory_get_usage () );
define ( 'AJAX', strtolower ( getenv ( 'HTTP_X_REQUESTED_WITH' ) ) === 'xmlhttprequest' );
require ('bootstrap.php');
require (APPPATH . 'functions.php');
// require ('appication/functions.php');
require (APPPATH . 'functions/mw_functions.php');
set_error_handler ( function ($c, $e, $f = 0, $l = 0) {
	$v = new View ( ADMIN_VIEWS_PATH . 'error.php' );
	$v->e = $e;
	$v->f = $f;
	$v->l = $l;
	echo $v;
	// _log("$e [$f:$l]");
} );


function exception($e) {
	$v = new View ( ADMIN_VIEWS_PATH . 'exception.php' );
	$v->e = $e;
	// _log($e -> getMessage() . ' ' . $e -> getFile());
	die ( $v );
}
function error($e, $f = false, $l = false) {
	$v = new View ( ADMIN_VIEWS_PATH . 'error.php' );
	$v->e = $e;
	$v->f = $f;
	$v->l = $l;
	// _log($e -> getMessage() . ' ' . $e -> getFile());
	die ( $v );
}

set_exception_handler ( 'exception' );
register_shutdown_function ( function () {
	$e = error_get_last ();
	
	if (isset ( $e )) {
		exception ( new ErrorException ( $e ['message'], $e ['type'], 0, v ( $e ['file'] ), $e ['line'] ) );
	}
} );

$m = url ( 0 ) ?  : 'index';
 
/*
 * if (!is_file(p("classes/$c")) || !($c = new $c) || $m == 'render' ||
 * !in_array($m, get_class_methods($c))) { } if($m == 'api'){ $m = url ( 1 ) ? :
 * 'index'; $c = new api (); } else { }
 */

$c = new controller ();
$admin_url = c ( 'admin_url' );
if ($m == 'admin' or $m == $admin_url) {
 	if ($admin_url == $m) {
		$c->admin ();
		exit ();
	} else {
		error ( 'No access allowed to admin' );
		exit ();
	}
}
if (method_exists ( $c, $m )) {
	
	
	$c->$m ();
} else {
	$c->index ();
}
exit ();
call_user_func_array ( array (
		$c,
		$m 
), array_slice ( url (), 2 ) );
//$c -> render();
