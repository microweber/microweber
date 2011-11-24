<?php
/*
type: layout
name: kongresen layout
description: kongresen site layout




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
          <?php include (ACTIVE_TEMPLATE_DIR."offers_read_kongress.php"); ?>
          <?php else : ?>

          




 <?php if($view != false) : ?>
           <?php include (ACTIVE_TEMPLATE_DIR."offers_list_{$view}.php"); ?>
           <?php else: ?>
           <?php include (ACTIVE_TEMPLATE_DIR."offers_list_kongress.php"); ?>
          <?php endif; ?>
          
          
          
            
          <?php endif; ?>
          <?php include ACTIVE_TEMPLATE_DIR. "footer_nav.php" ; ?>
          <?php include "footer.php" ; ?>
