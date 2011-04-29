<?php
// example of how to modify HTML contents
include('../simple_html_dom.php');

// get DOM from URL or file
$html = file_get_html('http://www.google.com/');

// remove all image
foreach($html->find('img') as $e)
    $e->outertext = '';

// replace all input
foreach($html->find('input') as $e)
    $e->outertext = '[INPUT]';

// dump contents
echo $html;
?>