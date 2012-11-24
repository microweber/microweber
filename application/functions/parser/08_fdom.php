<?php



require_once (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'parser' . DIRECTORY_SEPARATOR . 'fdom' . DIRECTORY_SEPARATOR . 'autoload.php');


$dom = new TheSeer\fDOM\fDOMDOcument();
try {
    $dom->loadXML('<?xml version="1.0" ?>'.$layout);
} catch (fDOMException $e) {

}


$child = $dom->queryOne('//div');
print_r($child->getAttribute('name'));
print_r($child->getAttribute('missing','DefaultValue'));

