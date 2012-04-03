<?php

/*

type: layout

name: home layout

description: home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? 
 
 $v = url_param('view');
  $vv =  $v ;
 if(  $v == false){
	 $v1 = "main.php";
 } else {
	  $v1 = $v .".php";
 }

?>

<div class="products_container">
  <? include  $v1; ?>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
