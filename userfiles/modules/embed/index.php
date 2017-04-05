<?php

$source_code =  get_option('source_code', $params['id']) ;
 
if(strval($source_code) == ''){
	$source_code = lnotif(_e('Click to insert Embed Code', true));
}
  
if($source_code != false and $source_code != ''){
    print "<div class='mwembed'>" . $source_code . '</div>';
} else {

}