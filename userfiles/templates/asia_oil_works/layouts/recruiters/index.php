<?php

/*

type: layout

name: Home layout

description: Home site layout









*/



?>
<? include TEMPLATE_DIR. "header.php"; ?>
<? $id = url_param('id'); ?>

<div id="content" class="TheContent">
  <div class="TheContent1">
    <? if(intval( $id) == 0): ?>
    <? include TEMPLATE_DIR.'layouts/recruiters/list.php'; ?>
    <? else: ?>
    <? include TEMPLATE_DIR.'layouts/recruiters/view.php'; ?>
    <? endif; ?>
    
    
    <? include   TEMPLATE_DIR.  "footer_upper.php"; ?>
   
  </div>
  <div class="TheContentTop">&nbsp;</div>
  <div class="TheContentBottom">&nbsp;</div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
