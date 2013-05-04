<?php  
 


$source_code =  get_option('source_code', $params['id']) ;

$source_code_language =  get_option('source_code_language', $params['id']) ;



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
/*	$(document).ready(function(){
		
			mw.require("<?php print $config['url_to_module'] ?>jquery.snippet.css", function(){

 

			});
				
			 
			 
	mw.require("<?php print $config['url_to_module'] ?>jquery.snippet.js", function(){

 $("#<?php print $source_code_id ?>").snippet("<?php print $source_code_language ?>");
 
			});
	
			
			
			
			
			
		 
			});*/

</script>
 
<pre id="<?php print $source_code_id ?>">
<?php print htmlentities($source_code ); ?>
</pre>
