<?php
/*
type: layout
name: Articles layout full
description: Articles site layout full




*/

?>
<?php include "header.php" ?>
      <?php if(!empty($post)) : ?>
      <?php include "articles_read.php" ?>
      <?php else : ?>
      
      <?php 
	  $full_pic = true;
	  include "articles_list.php" ?>
      <?php endif; ?>
      <?php include "footer.php" ?>
