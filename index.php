<?php
// Setup system and load controller
define('T', microtime());
define('M', memory_get_usage());
define('AJAX', strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');
  require ('bootstrap.php');
 require (APPPATH.'functions.php');
 //require ('appication/functions.php');
 require (APPPATH.'functions/mw_functions.php');
set_error_handler(function($c, $e, $f = 0, $l = 0) {
 	$v = new View(APPPATH_FULL.'views'.DS.'error.php');
	$v -> e = $e;
	$v -> f = $f;
	$v -> l = $l;
	echo $v;
	//_log("$e [$f:$l]"); 
});


function exception($e) {$v = new View('exception');
	$v -> e = $e;
	//_log($e -> getMessage() . ' ' . $e -> getFile());
	die($v);
}

set_exception_handler('exception');
register_shutdown_function(function() {
	if ($e == error_get_last())
		exception(new ErrorException($e['message'], $e['type'], 0, v($e['file']), $e['line']));
});



$c = 'controller_' . (url(0) ? : 'home');
$m = url(1) ? : 'index';

/* if (!is_file(p("classes/$c")) || !($c = new $c) || $m == 'render' || !in_array($m, get_class_methods($c))) {
	
	
	
} */

$c = new controller;
$m = 'index';

call_user_func_array(array($c, $m), array_slice(url(), 2));
//$c -> render();
