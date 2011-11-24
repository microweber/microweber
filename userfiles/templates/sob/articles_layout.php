<?php
/*
type: layout
name: Articles layout
description: Articles site layout




*/

?>
<?php $created_by = $this->core_model->getParamFromURL ( 'author' ); ?>
<?php $type = $this->core_model->getParamFromURL ( 'type' ); ?>
<?php $view = $this->core_model->getParamFromURL ( 'view' ); ?>
<?php // var_dump($created_by); ?>
<?php if(!empty($post)) : ?>
<?php if($type == false) $type = $post['content_subtype'];   ?>
<?php if($type == 'trainings') {
           include "header_trainings.php" ;
      }  else {
		 include "header.php" ;
	  }
	  
	  
	  ?>
<?php else : ?>
<?php include "header.php" ; ?>
      <?php endif; ?>
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

      <?php if($view == 'list') : ?>

      <?php if($type != false) {
		 $possible_type_view_file = ACTIVE_TEMPLATE_DIR."articles_list_{$type}.php";
             //   var_dump($possible_type_view_file);
		  if(is_file($possible_type_view_file)){
			  include $possible_type_view_file;
			//  $read_file = $possible_type_view_file;
			   //include $read_file;
		  } else {
			 //  $read_file = ACTIVE_TEMPLATE_DIR."articles_list.php";
			  include "articles_list_inner.php";
		  }
	  }  else {
                          include "articles_list_inner.php";

	  }

	   ?>




      <?php // include "articles_list_inner.php" ?>
      <?php else : ?>
      <?php include "articles_list.php" ?>
      <?php endif; ?>

      <?php endif; ?>
      <?php //include "footer.php" ?>
      <?php if(!empty($post)) : ?>
      <?php if($type == false)  {$type = $post['content_subtype'];}   ?>
      <?php if($type == 'trainings') {
           include "footer_trainings.php" ;
      }  else {
		 include "footer.php" ;
	  } ?>
      <?php else : ?>
      <?php include "footer.php" ; ?>
<?php endif; ?>
