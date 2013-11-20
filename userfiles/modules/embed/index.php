<?php

$source_code =  get_option('source_code', $params['id']) ;
 
if(strval($source_code) == ''){
	$source_code = lnotif('Click to insert Embed Code');
}
  
if($source_code != false and $source_code != ''){
    print "<div class='mwembed'>" . $source_code . '</div>';
} else {

}