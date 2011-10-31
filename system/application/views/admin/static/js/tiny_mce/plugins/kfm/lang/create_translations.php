<?php
require_once "../initialise.php";
$h = opendir('./');
print "<pre>";
$kfm->db->query("delete from kfm_translations");
while($file=readdir($h)){
	if(substr($file,-3)!='.js')continue;
	$lang=substr($file,0,2);
	require "$lang.php";
	$js=file_get_contents("$lang.js");
	$js=mb_convert_encoding($js, 'UTF-8', $js);
	$js=trim(preg_replace('/\/\*[^q]*kfm.lang=/','',$js));
	$js=preg_replace('/\/\/.*/','',$js);
	$js=preg_replace('/\s*\n\s*/','',$js);
	// corrections
	$js=str_replace("\'","'",$js);
	$js=str_replace('\.','\"',$js);
	$js=preg_replace('/\s*:\s*/',':',$js);
	$js=preg_replace('/,([^"]+):/',',"\1":',$js);
	$js=preg_replace('/{([^"]+):/','{"\1":',$js);
	$ob=json_decode($js, true);
	if(gettype($ob)!='array'){
		$js=utf8_decode($js);
		$ob=json_decode($js, true);
	}
	if(gettype($ob)!='array'){
		print "lang $lang.js cannot be interpreted\n";
		var_dump($js);
		continue;
	}
	$f=$kfm_langStrings;
	foreach($ob as $key=>$value)if(trim($key)!='')$f[trim($key)]=addslashes(trim($value));
	$locale=$lang.'_'.strtoupper($lang);
	print "lang $lang has:".count($f)."\n";
	if($lang=='en')$en=$f;
	foreach($f as $name=>$text){
		$orig=sql_escape($name);
		$tran=sql_escape($text);
		$kfm->db->query("INSERT INTO kfm_translations(original,translation,language,context,found) VALUES ('$orig','$tran','$lang','kfm',1)");
	}
}
foreach($en as $name=>$text){
	$orig=sql_escape($name);
	$tran=sql_escape($text);
	$kfm->db->query("update kfm_translations set original='$tran' where original='$orig'");
}
