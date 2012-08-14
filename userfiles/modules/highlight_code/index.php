<?php  
 


$source_code =  option_get('source_code', $params['id']) ;

$source_code_language =  option_get('source_code_language', $params['id']) ;



if(strval($source_code_language) == ''){
	$source_code_language = 'php';
}

if(strval($source_code) == ''){
	$source_code = '// source code here';
}
//p($config);
 
$source_code_id = md5($source_code );
 
?>
<script  type="text/javascript">
$(document).ready(function(){
			
			mw.require("<? print $config['url_to_module'] ?>jquery.snippet.css", function(){

 

			});
				
			 
			 
	mw.require("<? print $config['url_to_module'] ?>jquery.snippet.js", function(){

 $("#<? print $source_code_id ?>").snippet("<? print $source_code_language ?>");
 
			});
	
			
			
			
			
			
		 
			});

</script>

<pre id="<? print $source_code_id ?>">
<? print htmlentities($source_code ); ?>
</pre>
