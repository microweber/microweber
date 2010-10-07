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
      <?php if($type != false) : ?>
      <?php // include "trainings_list.php" ?>
      <?php if($type != false) {
		  $possible_type_view_file = ACTIVE_TEMPLATE_DIR."articles_list_{$type}.php";
		  if(is_file($possible_type_view_file)){
			  $read_file = $possible_type_view_file;
		  } else {
			   $read_file = ACTIVE_TEMPLATE_DIR."articles_list.php";
		  }
	  }
	   ?>
      <?php else : ?>
      <?php if($view == 'list') : ?>
      <?php include "articles_list_inner.php" ?>
      <?php else : ?>
      <?php include "articles_list.php" ?>
      <?php endif; ?>
      <?php endif; ?>
      <?php endif; ?>
      <?php //include "footer.php" ?>
       
       
       
      <?php include "footer.php" ; ?>
 