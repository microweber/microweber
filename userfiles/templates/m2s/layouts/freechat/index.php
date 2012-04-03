<?php

/*

type: layout

name: home layout

description: home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<div class="products_container">

<? $user_id = user_id(); ?>

<? if( $user_id == 0): ?>


<? include TEMPLATE_DIR. "must_login.php"; ?>

<? else: ?>

<editable rel="page" field="content_body">
  <h1>Live chat</h1>
 
  <p>Text here</p>
</editable>
 


<iframe src="<? print site_url('chat'); ?>" width="950" frameborder="0" height="700"></iframe>

<? endif; ?>

</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
 