<?php

/*

type: layout

name: blog layout

description: blog site layout









*/

 

?>
<? include TEMPLATE_DIR. "header.php"; ?>
<div class="wide_stripe"></div>
<div class="content_wide_holder_white2 blog_page">
  <div class="content_center" style="padding-top:20px;" >
  
    <?  if(empty($post)): ?>
    <h1>Who are we</h1>
<p>This little book is deceptive in its brevity for it contains powerful instructions for the achievement of personal mastery. Put it at the top of your shopping list! Follow its simple instructions and experience</p>
<br /><br />
<hr />
<br /><br />

    
    <microweber module="posts/list" tn_size="250" file="layouts/blog/list.php" />
     
    <? else : ?>
    <?  include TEMPLATE_DIR. "layouts/blog/inner.php"; ?>
    <? endif; ?>
    <?  // include TEMPLATE_DIR. "layouts/blog/sidebar.php"; ?>
  </div>
</div>
<? include   TEMPLATE_DIR.  "footer.php"; ?>
