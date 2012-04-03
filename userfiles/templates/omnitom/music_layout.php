<?php
/*
type: layout
name: Music layout full
description: Music site layout full




*/

?>
<?php include "header.php" ?>
      <?php if(!empty($post)) : ?>
      <?php include "articles_read.php" ?>
      <?php else : ?>

      <?php
	  $full_pic = true;
	  include "music_gallery.php" ?>
      <?php endif; ?>
      <?php include "footer.php" ?>
