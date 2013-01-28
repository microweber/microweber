<?php

/*

type: layout

name: Black

description: Black

*/

 ?>
 <style>
  .black {;
	background-color:black;
	font-family: Verdana, Geneva, sans-serif;
	text-decoration: none;  
  }
  
  
  
  
  .black .edit  {
	color: #FFF;  
  }
  
  
  .black h1, .black h2, .black h3, .black h4, .black h5, .black h6 {
	font-size:16px; 
	color: #FFF;  
  }
  
  .black .edit {
	color: #FFF;  
  }
  
   .black label {
	color: #FFF;  
  }
   .black input{;
	background-color:white;
	font-family: Verdana, Geneva, sans-serif;
	color: #333;
	text-decoration: none;
	  
  }
 </style>
 <? 

$template_file = module_templates( $config['module'], 'default');
 

  ?><div class="black"><?
if(isset($template_file) and is_file($template_file) != false){
 	include($template_file);
}
 ?></div>

