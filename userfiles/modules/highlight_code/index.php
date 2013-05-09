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
 
$source_code_id = md5($source_code );
 
?>

<div class="mw-code-hl">
  <?php //echo luminous::highlight($source_code_language,$source_code); ?>
  <code class="<?php  echo $source_code_language; ?>" lang="<?php  echo $source_code_language; ?>">
  <?php  echo nl2br($source_code,1); ?>
  </code> </div>
