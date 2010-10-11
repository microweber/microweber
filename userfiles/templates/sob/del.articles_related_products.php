<?php dbg(__FILE__); ?>
<?php $cats = $this->taxonomy_model->getTaxonomiesForContent($post['id']);
	  $related = array();
	  $related['selected_categories'] = $cats;
	  $related['created_by'] = $post['created_by'];
	  $related['content_subtype'] = 'products';
	  
	  $limit[0] = 0;
	  $limit[1] = 10;
	  $related_content_comes_from_this_user = true;
	  $related = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id',  'content_title',  'content_description') );

	 if(count($related) < 1){
		 $related = array();
	  $related['selected_categories'] = $cats;
	  $related['content_subtype'] = 'products';
	   $related_content_comes_from_this_user = false;
	  //$related['created_by'] = $post['created_by'];
	  $limit[0] = 0;
	  $limit[1] = 10;
	  $related = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id',  'content_title',  'content_description' ) );
		 
	 }
	 

	 
	  ?>
<?php if(!empty($related)): ?>

<h2 class="in-content-title clear border-bottom" style="margin:25px 0 5px">Related products from <?php if(($related_content_comes_from_this_user == true) and !empty($author)) : ?><?php print $this->users_model->getPrintableName($author['id'], $mode = 'first') ?><?php else: ?>The School<?php endif;  ?></h2>
<div class="related-posts">
  <?php foreach($related as $rel): ?>
  <?php $more_rel = false;
 $more_rel = $this->core_model->getCustomFields('table_content', $rel['id']);
	 if(!empty($more_rel)){
		ksort($more_rel);
	 }  ?>
  <h2>
  
  
  
   <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $rel['id'], 180, 150);  ?>
 
  
    <a href="<?php print $this->content_model->contentGetHrefForPostId($rel['id']) ; ?>" class="img" style="background-image:url(<?php print $thumb; ?>); background-position:center center;"></a>
    <span class="related-post-content">
        <a href="<?php print $this->content_model->contentGetHrefForPostId($rel['id']); ?>"><?php print $rel['content_title_nohtml'] ?></a>
        <span class="c" style="padding-bottom: 10px;">&nbsp;</span>
        
         <?php if(($more_rel['product_buy_link'])): ?>
    <a href="<?php print prep_url($more_rel['product_buy_link']); ?>" target="_blank" class="order left">Order</a> <?php if(($more_rel['product_price'])): ?> for $<?php print floatval($more_rel['product_price']); ?><?php endif; ?>
    <?php endif; ?>
        
 
    </span>

  </h2>



  <!--<p><?php print character_limiter($rel['content_description'], 100, '...') ?></p>-->

  <?php endforeach; ?>
</div>
<br />
<?php endif;  ?>
<?php dbg(__FILE__, 1); ?>