<?php

/*

type: layout

name: blog layout

description: blog site layout









*/

 

?>
<? include TEMPLATE_DIR. "header.php"; ?>

<div class="content_wide_holder_white2 blog_page">
  <div class="content_center" style="margin-top:60px; margin-bottom:30px;">
    <?  if(empty($post)): ?>
    <?  include TEMPLATE_DIR. "layouts/blog/list.php"; ?>
    <? else : ?>
    <?  include TEMPLATE_DIR. "layouts/blog/inner.php"; ?>
    <? endif; ?>
    
    
    
      <?  include TEMPLATE_DIR. "layouts/blog/sidebar.php"; ?>
    
    
    
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
