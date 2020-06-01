<?php

require_once dirname(__DIR__) . '/../../../vendor/autoload.php';


$file = __DIR__ . '/main.scss';

$scss = new \ScssPhp\ScssPhp\Compiler();


$scss->setSourceMapOptions([
    // absolute path to write .map file
    'sourceMapWriteTo' => __DIR__ . '__compiled_main.scss.map',

    // relative or full url to the above .map file
    'sourceMapURL' => '__compiled_main.scss.map',

    // (optional) relative or full url to the .css file
    // 'sourceMapFilename' => 'grunt/plugins/ui/css/__compiled_main.css',

    // partial path (server root) removed (normalized) to create a relative url
    //'sourceMapBasepath' => 'grunt/plugins/ui/css/',

    // (optional) prepended to 'source' field entries for relocating source files
    // 'sourceRoot' => '/grunt/plugins/ui/css/',
]);


$cookies = $_COOKIE;

$vars = [];

if ($cookies) {
    foreach ($cookies as $key => $cookie) {
        if ($cookie and $key and strstr($key, '__var_')) {
            $k = str_replace('__var_', '', $key);
            $vars[$k] = $cookie;
        }
    }
}
if ($vars) {
    $scss->setVariables($vars);
}

header("Content-type: text/css", true);
echo $scss->compile(file_get_contents($file), __DIR__ . '__compiled_main.css');
