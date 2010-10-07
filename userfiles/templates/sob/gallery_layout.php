<?php
/*
type: layout
name: Gallery layout
description: Gallery layout




*/

?>
<?php include "header.php" ; ?>
      <?php if(!empty($post)) : ?>
      





 
      <?php if($type == false) $type = $post['content_subtype'];   //p($type);  ?>
      <?php if($type != false) {
		  $possible_type_view_file = ACTIVE_TEMPLATE_DIR."articles_read_{$type}.php";
		  if(is_file($possible_type_view_file)){
			  $read_file = $possible_type_view_file;
		  } else {
			   $read_file = ACTIVE_TEMPLATE_DIR."articles_read.php";
		  }
	  }
	   ?>
      <?php include $read_file; ?>






      
      <?php else : ?>
      <?php include "gallery_list.php" ?>
      <?php endif; ?>
      <?php include "footer.php" ; ?>
