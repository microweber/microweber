 <?php dbg(__FILE__); ?>
<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_navigation.php') ?>
<?php $action = $this->core_model->getParamFromURL ( 'action' ); ?>




  <?php require (ACTIVE_TEMPLATE_DIR.'users/userbase/profile_sidebar.php') ?>






<div class="main" style="padding-top: 20px;">
  <?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_inner.php') ?>
  <div class="posts">
    <div class="content-title">
      <h2 class="title left"><?php print $action  ?></h2>
    </div>
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
    <?php if($action != false) : ?>
    <?php // include "trainings_list.php" ?>
    <?php if($action != false) {
		  $possible_type_view_file = ACTIVE_TEMPLATE_DIR."articles_list_inner_posts_list_{$action}.php";
		  if(is_file($possible_type_view_file)){
			  $read_file = $possible_type_view_file;
		  } else {
			   $read_file = ACTIVE_TEMPLATE_DIR."articles_list_inner_posts_list.php";
		  }
		  
		   
		   include $read_file; 
	  }
	   ?>
    <?php else : ?>
    <?php if($view == 'list') : ?>
    <?php include ACTIVE_TEMPLATE_DIR."articles_list_inner_posts_list.php" ?>
    <?php else : ?>
    <?php include ACTIVE_TEMPLATE_DIR."articles_list_inner_posts_list.php" ?>
    <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
 <?php dbg(__FILE__); ?>