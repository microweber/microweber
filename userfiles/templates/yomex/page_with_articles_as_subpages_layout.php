<?php
/*
type: layout
name: Page with articles as subpages
description: Page with category and the articles as subpages




*/

?>
<?php include "header.php" ?>
      <?php if(!empty($post)) : ?>
      <?php include "page_with_articles_as_subpages_read.php" ?>
      <?php else : ?>
      
      <?php //var_dump($active_categories); 
      $related = array();
	  $related['selected_categories'] = array($active_categories[0]);
	  $related['visible_on_frontend'] = 'y'; 
	  $limit[0] = 0;
	  $limit[1] = 1;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); 
	  $post = $related[0] ;
	  ?>
      
      
      <?php include "page_with_articles_as_subpages_read.php" ?>
      <?php endif; ?>
      <?php include "footer.php" ?>
