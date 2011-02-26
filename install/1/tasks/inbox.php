<?php
include('../common.php');

$html = new HTML;
$project_names = array('0'=>t('Auto Select')) + $projects;
 
render();