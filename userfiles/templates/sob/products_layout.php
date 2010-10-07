<?php
/*
type: layout
name: Products layout
description: Products site layout




*/

?>
<?php include "header.php" ?>
 
         <?php if(!empty($post)) : ?>
        <?php include "articles_read.php" ?>
        <?php else : ?>
        
        
        <?php $created_by = $this->core_model->getParamFromURL ( 'author' ); ?>
         <?php if(intval($created_by) != 0) : ?>
        <?php //include "articles_list_inner.php" ?>
        <?php include "products_list.php" ?>
        <?php else : ?>
        <?php // include "articles_list.php" ?>
        <?php include "products_list.php" ?>
         <?php endif; ?>
        
        
        <?php endif; ?>
        <?php include "footer.php" ?>
