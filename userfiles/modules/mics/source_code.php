<?php  


$source_code =  option_get('source_code', $params['module_id']) ;

$source_code_language =  option_get('source_code_language', $params['module_id']) ;



if(strval($source_code_language) == ''){
	$source_code_language = 'php';
}

if(strval($source_code) == ''){
	$source_code = '// source code here';
}
//p($config);
 
$source_code_id = md5($source_code );
require_once($config['path_to_module'].DIRECTORY_SEPARATOR.'source_code'.DIRECTORY_SEPARATOR.'geshi'.DIRECTORY_SEPARATOR.'geshi.php'); 
require_once($config['path_to_module'].DIRECTORY_SEPARATOR.'source_code'.DIRECTORY_SEPARATOR.'geshi'.DIRECTORY_SEPARATOR.'functions.geshi.php'); 

 
$geshi = new GeSHi($source_code, $source_code_language);

//$code = $geshi->parse_code();

// Highlight GeSHi's output
$geshi->set_source($source_code);
 
$geshi->enable_classes(true);
echo ($geshi->parse_code());
//echo ($geshi->parse_code());


 
//$result = geshi_highlight($source_code, $source_code_language);
 
?>
 <style type="text/css"> 
 <? print  $geshi->get_stylesheet(); ?>
 
</style>
 