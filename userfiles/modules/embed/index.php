<?php

$source_code =  get_option('source_code', $params['id']) ;
if(strval($source_code) == ''){
	$source_code = '// embed code here';
}
if($source_code != false and $source_code != ''){
print $source_code;
} else {
	
}