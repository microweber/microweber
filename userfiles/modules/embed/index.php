<?php

$source_code =  get_option('source_code', $params['id']) ;
if(strval($source_code) == ''){
	$source_code = print lnotif('Click to insert Embed Code');
}
$source_code = html_entity_decode($source_code, ENT_COMPAT, 'UTF-8');

if($source_code != false and $source_code != ''){
    print "<div class='mwembed'>" . $source_code . '</div>';
} else {

}