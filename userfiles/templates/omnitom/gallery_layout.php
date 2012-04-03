<?php
/*
type: layout
name: Gallery layout full
description: Gallery site layout full




*/

?>
<?php include "header.php" ?>
      <?php if(!empty($post)) : ?>
      <?php include "articles_read.php" ?>
      <?php else : ?>

      <?php 
	  $full_pic = true;
	  include "articles_list_gallery.php" ?>
      <?php endif; ?>
      <?php include "footer.php" ?>
