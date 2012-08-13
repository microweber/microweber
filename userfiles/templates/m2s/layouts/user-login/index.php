<?php

/*

type: layout

name: home layout

description: home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>




 <? if(user_id() != 0) : ?>
 <? safe_redirect('dashboard') ?>
   <? else: ?>
 <? safe_redirect('home') ?>
<? endif; ?>

<? include   TEMPLATE_DIR.  "footer.php"; ?>
