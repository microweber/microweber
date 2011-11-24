<?php dbg(__FILE__); ?>
<?php if(($post['created_by'])!= false){
	$author['id'] = $post['created_by'];
}

	 $cats = $this->taxonomy_model->getTaxonomiesForContent($post['id']);
	  $related = array();
	  $related['selected_categories'] = $cats;
	  $related['created_by'] = $post['created_by'];
	  $related['content_subtype'] =  $post['content_subtype'];
	  
	  $limit[0] = 0;
	  $limit[1] = 10;
	  $related_content_comes_from_this_user = true;
	  $related = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = false );
 
	 if(count($related) < 1){
		 $related = array();
	  $related['selected_categories'] = $cats;
	  $related['content_subtype'] = $post['content_subtype'];
	   $related_content_comes_from_this_user = false;
	  //$related['created_by'] = $post['created_by'];
	  $limit[0] = 0;
	  $limit[1] = 10;
	  $related = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = false );
		 
	 }
	 

	 
	  ?>
<?php if(!empty($related)): ?>
<?php $related_what = 'articles';
if($the_post['content_subtype'] == 'none'): ?>
<?php $related_what = 'articles'; ?>
<?php endif; ?>
<?php if($the_post['content_subtype'] == 'trainings'): ?>
<?php $related_what = 'trainings'; ?>
<?php endif; ?>
<?php if($the_post['content_subtype'] == 'products'): ?>
<?php $related_what = 'products'; ?>
<?php endif; ?>
<?php if($the_post['content_subtype'] == 'services'): ?>
<?php $related_what = 'services'; ?>
<?php endif; ?>

<h2 class="in-content-title clear border-bottom" style="margin:25px 0 5px">Related <?php print $related_what ; ?> from
  <?php if(($related_content_comes_from_this_user == true) and !empty($author)) : ?>
 
 
  <?php print $this->users_model->getPrintableName($author['id'], $mode = 'first') ?>
  
  
  <?php else: ?>
  The School
  <?php endif;  ?>
</h2>
<div class="related-posts">
  <?php foreach($related as $rel): ?>
  <?php $more_rel = false;
 $more_rel = $this->core_model->getCustomFields('table_content', $rel['id']);
	 if(!empty($more_rel)){
		ksort($more_rel);
	 }  ?>
  <div class="related-article">
    <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $rel['id'], 180, 150);  ?>
    <a href="<?php print $this->content_model->contentGetHrefForPostId($rel['id']) ; ?>" class="img"> <img src="<?php print $thumb; ?>" alt="" /> </a> <span class="related-post-content"> <a href="<?php print $this->content_model->contentGetHrefForPostId($rel['id']); ?>"><?php print $rel['content_title_nohtml'] ?></a>
    <p class="related-articles-description">
      <?php if($rel['content_description'] != ''): ?>
      <?php print (character_limiter($rel['content_description'], 130, '...')); ?>
      <?php else: ?>
      <?php print character_limiter($rel['content_body_nohtml'], 130, '...'); ?>
      <?php endif; ?>
    </p>
    <span class="c" style="padding-bottom: 10px;">&nbsp;</span>
    <?php if(($more_rel['product_buy_link'])): ?>
    <a href="<?php print prep_url($more_rel['product_buy_link']); ?>" target="_blank" class="order left">Order
    <?php if(($more_rel['product_price'])): ?>
    for $<?php print floatval($more_rel['product_price']); ?>
    <?php endif; ?>
    </a>
    <?php endif; ?>
    </span> </div>
  
  <!--<p><?php print character_limiter($rel['content_description'], 100, '...') ?></p>-->
  
  <?php endforeach; ?>
</div>
<br />
<?php endif;  ?>
<?php dbg(__FILE__, 1); ?>
