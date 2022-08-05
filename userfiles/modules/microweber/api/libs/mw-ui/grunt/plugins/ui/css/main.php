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


$cont = file_get_contents($file);
if (isset($vars['color_scheme']) and $vars['color_scheme']) {


    $theme_vars = 'https://bootswatch.com/4/' . $vars['color_scheme'] . '/_variables.scss';
    $theme_structure = 'https://bootswatch.com/4/' . $vars['color_scheme'] . '/_bootswatch.scss';


    $cont1 = file_get_contents($theme_vars);
    $cont2 = file_get_contents($theme_structure);

    file_put_contents(__DIR__ . '/bootswatch/_variables.scss', $cont1);
    file_put_contents(__DIR__ . '/bootswatch/_bootswatch.scss', $cont2);

    $cont = "
    //Bootswatch variables
@import 'bootswatch/_variables';
 
//UI Variables
@import 'bootstrap_variables';

//Bootstrap
@import '../../bootstrap/scss/bootstrap';

//Bootswatch structure
@import 'bootswatch/bootswatch';
 
//UI
@import '_ui';
    
    
    ";
    $vars = false;
    //$cont  = $cont1 . "\n\n" .$cont . "\n\n" .  $cont2;
}
if ($vars) {
    $scss->setVariables($vars);
}

header("Content-type: text/css", true);
echo $scss->compile($cont, __DIR__ . '__compiled_main.css');
