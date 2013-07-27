<?php

$source_code =  mw('option')->get('source_code', $params['id']) ;
if(strval($source_code) == ''){
	$source_code = mw('format')->lnotif('Click to insert Embed Code');
}
if($source_code != false and $source_code != ''){
    print "<div class='mwembed'>" . $source_code . '</div>';
} else {

}