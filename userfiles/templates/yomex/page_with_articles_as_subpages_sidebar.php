<div class="hright">
  <h2 class="gtitle"><?php print $page['content_title'] ?></h2>
  <?php //var_dump($active_categories);
      $related = array();
	  $related['selected_categories'] = array($active_categories[0]);
	   $related['visible_on_frontend'] = 'y'; 
	  $limit[0] = 0;
	  $limit[1] = 300;
	  $related = $this->content_model->getContentAndCache($related, false,$limit ); ?>
  <?php if(!empty($related)): ?>
  <ul class="sec-nav">
    <?php foreach($related as $item): ?>
    <li><a <?php if($post['id'] == $item['id']):  ?> class="active" <?php endif; ?> href="<?php print $this->content_model->contentGetHrefForPostId($item['id']) ; ?>"><?php print $item['content_title'] ?></a></li>
    <?php endforeach; ?>
    <?php /*
    <li><a href="<?php print $this->content_model->getContentURLByIdAndCache($page['id']); ?>">Въпроси</a></li>
    */ ?>
  </ul>
  <?php endif;  ?>

   <?php include (ACTIVE_TEMPLATE_DIR."sidebar_contacts.php"); ?>
</div>
