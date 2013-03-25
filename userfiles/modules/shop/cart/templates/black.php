<?php

/*

type: layout

name: Black

description: Black cart template

*/

$template_file = false; ?>
<style>
.mw-cart.black {
	background-color:black;
	color: #FFF;
}
.mw-cart.black a {
 color:red;	
}
.mw-cart.black .mw-cart-title {
	color: #0C0;
	font-size: 16px;
	font-weight: bold;
}
.mw-cart.black .mw-cart-item-title {
	background-color:black;
	color: #FF6;
	font-size: 14px;
	font-weight: normal;
	text-decoration: none;
	text-transform: none;
}
</style>

<? $template_file = module_templates($params['type'], 'default');

if(is_file($template_file)){
 include($template_file);	
}
 ?>