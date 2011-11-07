<?php
{ # variables
	$kfm_kaejax_js_has_been_shown=0;
	$kfm_kaejax_export_list=array();
	$kfm_kaejax_is_loaded=1;#strstr($_SERVER['REQUEST_URI'],'kfm_kaejax_is_loaded');
}	
function kfm_kaejax_handle_client_request(){
	if(!isset($_POST['kaejax']))return;
	$unmangled=kfm_decode_unicode_url(str_replace(array('%2B',"\r","\n","\t"),array('+','\r','\n','\t'),$_POST['kaejax']));
	$obj=json_decode($unmangled);
	$fs=$obj->c;
	$ret=array();
	$ret['results']=array();
	foreach($fs as $f)$ret['results'][]=call_user_func_array($f->f,$f->v);
	$ret['errors']=kfm_getErrors();
	$ret['messages']=kfm_getMessages();
	header('Content-type: text/javascript; charset=UTF-8');
	echo json_encode($ret);
	exit;
}
function kfm_kaejax_get_one_stub($func_name){
	$a='function x_'.$func_name.'(){kfm_kaejax_do_call("'.$func_name.'",arguments);}function_urls.'.$func_name."='".$_SERVER['PHP_SELF'].'?'.GET_PARAMS."';";
	if(!$GLOBALS['kfm_kaejax_is_loaded'])$a.='kfm_kaejax_is_loaded=1;';
	$GLOBALS['kfm_kaejax_is_loaded']=1;
	return $a;
}
function kfm_kaejax_export(){
	global $kfm_kaejax_export_list;
	$n=func_num_args();
	for($i=0;$i<$n;$i++)$kfm_kaejax_export_list[]=func_get_arg($i);
}
function kfm_kaejax_get_javascript(){
	$html='';
	if(!$GLOBALS['kfm_kaejax_js_has_been_shown']&&!$GLOBALS['kfm_kaejax_is_loaded'])$GLOBALS['kfm_kaejax_js_has_been_shown']=1;
	foreach($GLOBALS['kfm_kaejax_export_list'] as $func)$html.=kfm_kaejax_get_one_stub($func);
	return $html;
}
function kfm_decode_unicode_url($str){
	# this code taken from here: http://php.net/urldecode
	$res='';
	$i=0;
	$max=strlen($str)-6;
	while($i<=$max){
		$character=$str[$i];
		if($character=='%'&&$str[$i+1]=='u'){
			$value=hexdec(substr($str,$i+2,4));
			$i+=6;
			if($value<0x0080)$character=chr($value);
			else if($value<0x0800)$character=chr((($value&0x07c0)>>6)|0xc0).chr(($value&0x3f)|0x80);
			else $character=chr((($value&0xf000)>>12)|0xe0).chr((($value&0x0fc0)>>6)|0x80).chr(($value&0x3f)|0x80);
		}
		else ++$i;
		$res.=$character;
	}
	return $res.substr($str, $i);
}
