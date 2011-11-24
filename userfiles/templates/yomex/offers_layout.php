<?php
/*
type: layout
name: offers layout
description: offers site layout




*/

?>
<?php $created_by = $this->core_model->getParamFromURL ( 'author' ); ?>
<?php $type = $this->core_model->getParamFromURL ( 'type' ); ?>
<?php $view = $this->core_model->getParamFromURL ( 'view' ); ?>
<?php $special = $this->core_model->getParamFromURL ( 'is_special' ); ?>
<?php // var_dump($created_by); ?>
<?php if(!empty($post)) : ?>
<?php if($type == false) $type = $post['content_subtype'];   ?>
<?php include "header.php" ;

	  
	  ?>
<?php else : ?>
<?php include "header.php" ; ?>
          <?php endif; ?>
          <?php if(!empty($post)) : ?>
          <?php include (ACTIVE_TEMPLATE_DIR."offers_read.php"); ?>
          <?php else : ?>
          <?php if($type != false) : ?>
          <?php // include "trainings_list.php" ?>
          <?php else : ?>
          <?php if($view != false) : ?>
          <?php if($special == 'y') : ?>
          <?php //include (ACTIVE_TEMPLATE_DIR."offers_list_{$view}_special.php"); ?>
           <?php include (ACTIVE_TEMPLATE_DIR."offers_list_{$view}.php"); ?>
          <?php else : ?>
          <?php include (ACTIVE_TEMPLATE_DIR."offers_list_{$view}.php"); ?>
          <?php endif; ?>
          <?php //include (ACTIVE_TEMPLATE_DIR."offers_list_{$view}.php"); ?>
          <?php else : ?>
        
          <?php if($page['content_section_name'] != 'chosen'): ?>
          <?php //include (ACTIVE_TEMPLATE_DIR."offers_list.php"); ?>
          <?php if(count($active_categories)> 1) : ?>
            <?php include (ACTIVE_TEMPLATE_DIR."offers_list_all.php"); ?>
            <?php else: ?>
            <?php include (ACTIVE_TEMPLATE_DIR."offers_list.php"); ?>
            <?php endif; ?>
          <?php else : ?>
          <?php //include (ACTIVE_TEMPLATE_DIR."offers_list_all_special.php"); ?>
           <?php include (ACTIVE_TEMPLATE_DIR."offers_list_all.php"); ?>
          <?php endif; ?>
          <?php endif; ?>
          <?php //include (ACTIVE_TEMPLATE_DIR."offers_list.php"); ?>
          <?php endif; ?>
          <?php endif; ?>
          <?php include ACTIVE_TEMPLATE_DIR. "footer_nav.php" ; ?>
          <?php include "footer.php" ; ?>
