<?php
exit('To run this example remove line ' . __LINE__ . ' in file ' . basename(__FILE__));

$here = dirname(__FILE__).DIRECTORY_SEPARATOR;
$my_classes_dir = $here . 'classes' . DIRECTORY_SEPARATOR;
$my_views_dir = $here . 'views' . DIRECTORY_SEPARATOR;


require_once ('../bootstrap.php');


$application = new \Microweber\Application();
//var_dump($application);
// get stuff
$pages = $application->content->get('content_type=page');

$layout = new \Microweber\View($my_views_dir.'default.php');
$layout->content = 'I assigned variable to a view!';
$layout->pages = $pages;

$layout->display();
