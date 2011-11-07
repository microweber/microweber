<?php
function json_encode($obj){
	$json=new Services_JSON();
	return $json->encode($obj);
}
function json_decode($js){
	$json=new Services_JSON();
	return $json->decode($js);
}
