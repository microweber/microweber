<?php only_admin_access(); ?><?php 
$mw_help_path = mw_var('mw_help_path');	 
$mw_help_file = mw_var('mw_help_file');	

 
  $path = $mw_help_path;
  $file = $mw_help_file;
  
  if($path  != false){
 
$kw = false;  
if(isset($params['kw'])){
	$kw = $params['kw']; 
}
 // $dirs =  mw('Microweber\Utils\Files')->dir_tree($path,$kw);
 // $dirs = str_replace($base_path, '', $dirs);
  if(isset($params['ul_class'])){
   //  $dirs = str_replace("ul class='directory_tree'","ul class='directory_tree ".$params['ul_class']."'", $dirs);
  }
   // print $dirs; 
	
  }

$path = str_replace('..','',$path);
$file = str_replace('..','',$file);

if($path  != false and trim($path  != '') and $file  != false){
	$try_file = $path.$file;
	if(is_file($try_file)){
	 include($try_file);	
	}
	//d($try_file);
	
} else if($path  != false and trim($path  != '') and $file  == false){
	$try_file = $path.DS.'index.php';
	if(is_file($try_file)){
	 include($try_file);	
	}
}