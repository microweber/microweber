<?php  


$source_code =  option_get('source_code', $params['module_id']) ;

$source_code_language =  option_get('source_code_language', $params['module_id']) ;



if(strval($source_code_language) == ''){
	$source_code_language = 'php';
}

if(strval($source_code) == ''){
	$source_code = '// source code here';
}

$source_code_id = md5($source_code );
?>
<script type="text/javascript" src="<? print $config['url_to_module'] ?>source_code/jq_sh/jquery.snippet.js"></script>
<link rel="stylesheet" type="text/css" href="<? print $config['url_to_module'] ?>source_code/jq_sh/jquery.snippet.css" />
<script type="text/javascript">

$(document).ready(function(){
    $("pre#source_code_<? print $source_code_id  ?>").snippet("<? print $source_code_language ?>",{style:"kwrite",clipboard:"<? print $config['url_to_module'] ?>source_code/jq_sh/ZeroClipboard.swf",showNum:true});
    
});
</script>
<pre id="source_code_<? print $source_code_id  ?>"> <? print htmlentities($source_code) ; ?> </pre>