<?php



$source_code =  get_option('source_code', $params['id']) ;

$source_code_language =  get_option('source_code_language', $params['id']) ;



if(strval($source_code_language) == ''){
	$source_code_language = 'php';
}

if(strval($source_code) == ''){
	$source_code = '// source code here';
}
$source_code =  ($source_code);
//p($config);



?>


<script>
 mw.require("<?php print $config['url_to_module']; ?>snippet/jquery.snippet.min.js", true);
 mw.require("<?php print $config['url_to_module']; ?>snippet/jquery.snippet.min.css", true);

//mw.require("<?php print $config['url_to_module']; ?>prism/prism.css", true);
//mw.require("<?php print $config['url_to_module']; ?>prism/prism.js", true);

</script>
 <?php $theme_sel =  get_option('source_theme', $params['id']);
if($theme_sel == false or trim($theme_sel) == 'zellner'){

	$theme_sel  = 'emacs';
}
  $source_show_lines =  get_option('source_show_lines', $params['id']) == 'y';

$source_code_id = md5($source_code.$theme_sel.$source_code_language.$source_show_lines );



  ?>
<script>
$(document).ready(function() {





	 var srccode = $('#src<?php  print $source_code_id; ?>').val();
	 $('#pre<?php  print $source_code_id; ?>').text(srccode);
var thepre = document.getElementById('pre<?php  print $source_code_id; ?>');

 //$("#pre<?php  print $source_code_id; ?>").snippet("<?php  print $source_code_language; ?>");
    $("#pre<?php  print $source_code_id; ?>").snippet("<?php  print $source_code_language; ?>",{style:"<?php  print $theme_sel; ?>",clipboard:"<?php print $config['url_to_module']; ?>snippet/zc.swf",showNum:<?php  print intval($source_show_lines); ?>});


	//Prism.highlightElement(srccode, true);
	//
	//
	//
	//
});

</script>
<div class="mw-code-hl">
	<textarea class="language-<?php  print $source_code_language; ?>" id="src<?php  print $source_code_id; ?>" lang="<?php  print $source_code_language; ?>" style="display:none;">
				<?php  //echo nl2br($source_code,1); ?>
				<?php   print ($source_code); ?>
	</textarea>



		<pre id="pre<?php  print $source_code_id; ?>" >

		</pre>

</div>
