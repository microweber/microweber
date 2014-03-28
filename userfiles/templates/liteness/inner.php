<?php

/*

  THIS FILE WILL LOAD WHEN YOU OPEN A POST IN A NON BLOG page
  
  Curently it loads the default layout for post, but you can use it for custom design.

*/

?>

 

<?php if(isset($content) and isset($content['subtype']) and $content['subtype'] == 'product'): ?>
<?php include TEMPLATE_DIR. "layouts/shop_inner.php"; ?>
<?php else: ?>
<?php include TEMPLATE_DIR. "layouts/blog_inner.php"; ?>
<?php endif; ?>


