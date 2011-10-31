<?php

/*

type: layout

name: docs layout

description: docs site layout









*/

 

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="content_wide_holder_white2 blog_page">
  <div class="content_center" style="margin-top:60px; margin-bottom:30px;">
    <?  if(empty($post)): ?>
    <microweber module="posts/list"  file="<? print "layouts/docs/list.php" ?>" />
 
    <? else : ?>
    <?  include TEMPLATE_DIR. "layouts/docs/inner.php"; ?>
    <? endif; ?>
    
    
    
      <?  include TEMPLATE_DIR. "layouts/docs/sidebar.php"; ?>
    
    
    
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
