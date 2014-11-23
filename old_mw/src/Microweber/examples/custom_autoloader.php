<?php
exit('To run this example remove line ' . __LINE__ . ' in file ' . basename(__FILE__));

$here = dirname(__FILE__).DIRECTORY_SEPARATOR;
$my_classes_dir = $here . 'classes' . DIRECTORY_SEPARATOR;


require_once ('../bootstrap.php');
autoload_add($my_classes_dir);


$application = new \Microweber\Application();
//var_dump($application);
// get stuff
$content = $application->content->get('content_type=page');

print '<pre>';
var_dump($content);
print '</pre>';



$content = $application->media->get();

print '<pre>';
var_dump($content);
print '</pre>';

