<?php
/*
type: layout
name: Blog layout
description: Blog site layout




*/

?>
<?php include "header.php" ; ?>
      <?php if(!empty($post)) : ?>
      <?php $read_file = ACTIVE_TEMPLATE_DIR."blog_read.php"; ?>
      <?php include $read_file; ?>
      <?php else : ?>
      <?php include "blog_list.php" ?>
      <?php endif; ?>
      <?php include "footer.php" ; ?>
