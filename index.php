<?php

/* if (file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
  //   return false; // serve the requested resource as-is.
  } */


// Setup system and load controller
define('T', microtime());
define('M', memory_get_usage());
define('AJAX', strtolower(getenv('HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest');

require ('bootstrap.php');
$c_file = APPPATH . 'config.php';
$go_to_install = false;
if (!is_file($c_file)) {

    $default_config = INCLUDES_PATH . DS . 'install' . DS . 'config.base.php';
    copy($default_config, $c_file);
    $go_to_install = true;
}


require (APPPATH . 'functions.php');
$installed = c('installed');
if (strval($installed) != 'yes') {
    define('MW_IS_INSTALLED', false);
} else {
    define('MW_IS_INSTALLED', true);
}



// require ('appication/functions.php');

require (APPPATH . 'functions/mw_functions.php');

//set_error_handler('error');

function error($e, $f = false, $l = false) {
    $v = new View(ADMIN_VIEWS_PATH . 'error.php');
    $v->e = $e;
    $v->f = $f;
    $v->l = $l;
    // _log($e -> getMessage() . ' ' . $e -> getFile());
    die($v);
}

//$m = url(0) ? : 'index';

/*
 * if (!is_file(p("classes/$c")) || !($c = new $c) || $m == 'render' ||
 * !in_array($m, get_class_methods($c))) { } if($m == 'api'){ $m = url ( 1 ) ? :
 * 'index'; $c = new api (); } else { }
 */


$default_timezone = c('default_timezone');
if ($default_timezone == false) {
    date_default_timezone_set('UTC');
} else {
    date_default_timezone_set($default_timezone);
}



if (!defined('MW_BARE_BONES')) {


    $c = new controller ();


    if (MW_IS_INSTALLED != true or $go_to_install == true) {
        $c->install();
        exit();
    }


    $m1 = url_segment(0);
 
    if ($m1) {
        $m = $m1;
    } else {
        $m = 'index';
    }

    $admin_url = c('admin_url');
    if ($m == 'admin' or $m == $admin_url) {
        if ($admin_url == $m) {
            $c->admin();
            exit();
        } else {
            error('No access allowed to admin');
            exit();
        }
    }

    if ($m == 'api.js') {
        $m = 'apijs';
    }


    

    if (method_exists($c, $m)) {
 

        $c->$m();
    } else {
        
        
        $c->index();
        exit();
    }
    exit('No method');
}



/*call_user_func_array(array(
    $c,
    $m
        ), array_slice(url(), 2));*/
//$c -> render();
