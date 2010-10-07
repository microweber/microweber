 
  <?php //$menu_items = $this->content_model->getMenuItemsByMenuUnuqueId('footer_menu'); ?>

  <div id="footernav">
  <?php $related = array();
	  $related['id'] = 724;

	  $limit[0] = 0;
	  $limit[1] = 1;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
  <?php if(!empty($related)): ?>
  
  <?php print  ( $related[0]['content_body'] ) ?>
    <?php endif;  ?>
  
  <?php /*    <ul>
     <?php foreach($menu_items as $item): ?>
      <li><a href="<?php print $item['the_url'] ?>"><?php print strtoupper( $item['item_title'] ) ?></a>/</li>
        <?php endforeach ;  ?>
     
    </ul>
    */
  ?>   
    
    
  </div>