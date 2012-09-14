<?php

defined('T') or die();
if (!defined('__DIR__'))
    define('__DIR__', dirname(__FILE__));
if (!defined('MW_VERSION')) {
    define('MW_VERSION', 0.508);
}

// Basic system functions
function p($f) {
    return __DIR__ . strtolower(str_replace('_', '/', "/$f.php"));
}

function load_file($f) {
    return ( str_replace('..', '', $f));
    //return  strtolower ( str_replace ( '_', '/', "/$f.php" ) );
}

function v(&$v, $d = NULL) {
    return isset($v) ? $v : $d;
}

function __autoload($c) {
    require p("classes/$c");
}

function c($k) {
    static $c;
    $c = $c ? $c :
            require p('config');
    if (isset($c[$k])) {
        return $c[$k];
    }
}

function d($v) {
    return dump($v);
}

function dump($v) {
    return '<pre>' . var_dump($v) . '</pre>';
}

function post($k, $d = '', $s = 1) {
    $v = v($_POST[$k], $d);
    return ($s && is_string($v)) ? $v : (!$s && is_array($v) ? $v : $d);
}

function _log($m) {
    file_put_contents(__DIR__ . '/log/.' . date('Y-m-d'), time() . ' ' . getenv('REMOTE_ADDR') . " $m\n", FILE_APPEND);
}

function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

function redirect($u = '', $r = 0, $c = 302) {
    header($r ? "Refresh:0;url=$u" : "Location: $u", TRUE, $c);
}

function registry($k, $v = null) {
    static $o;
    return (func_num_args() > 1 ? $o[$k] = $v : (isset($o[$k]) ? $o[$k] : NULL));
}

function utf8($s, $f = 'UTF-8') {
    return @iconv($f, $f, $s);
}
