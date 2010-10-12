<?php dbg(__FILE__); ?>
<h2 class="title" style="padding: 20px 0 9px 17px;">Learning Center</h2>
<div class="tabs-holder side-tabs">
  <ul class="tabs-nav" id="Learning-Center-Tabs">
    <li class="active active-first"><a href="#Hot">Hot</a></li>
    <li><a href="#Classic">Classic</a></li>
    <li><a href="#New">New</a></li>
  </ul>
  <div class="tab" id="Hot">
    <?php if(empty($active_categories)){
		$active_categories =  $this->taxonomy_model->getMasterCategories(false);
		$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
		
	 }
      $related_params = array();
	  $related_params['selected_categories'] = $active_categories;
	  $related_params['content_subtype'] = 'trainings';
	  $related_params['voted'] = '7 days';
	  $limit[0] = 0;
	  $limit[1] = 8;
	  $related = $this->content_model->getContentAndCache($related_params, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) ); 
	  ?>
    <ul>
      <?php if(!empty($related)): 
	shuffle($related);
	array_slice($related , 0 , 5);
	?>
      <?php foreach($related as $the_post): ?>
      <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
      <li> <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="author">by <?php print $this->users_model->getPrintableName (  $author['id'], 'full' ); ?></a> <span class="voteUp">
        <?php print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?>
        </span>
        <h2><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
      </li>
      <?php endforeach; ?>
      <?php else: ?>
      <li>No hot trainings in this category. </li>
      <?php endif; ?>
    </ul>
  </div>
  <div class="tab" id="Classic">
    <ul>
      <?php //
		 $related_params['is_special'] = 'y';
		   $related_params['voted'] = false;
		// var_dump($related_params);
		   
		$related = $this->content_model->getContentAndCache($related_params, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) ); 
		 if(!empty($related)): 
	shuffle($related);
	array_slice($related , 0 , 5);
	?>
      <?php foreach($related as $the_post): ?>
      <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
      <li> <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="author">by <?php print $this->users_model->getPrintableName (  $author['id'], 'full' ); ?></a> <span class="voteUp">
        <?php print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?>
        </span>
        <h2><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
      </li>
      <?php endforeach; ?>
      <?php else: ?>
      <li>No trainings in this category. </li>
      <?php endif; ?>
    </ul>
  </div>
  <div class="tab" id="New">
    <ul>
      <?php unset( $related_params['is_special'] ) ;
		unset( $related_params['voted'] ) ;
		$related = $this->content_model->getContentAndCache($related_params, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) ); 
		 if(!empty($related)): 
	shuffle($related);
	array_slice($related , 0 , 5);
	?>
      <?php foreach($related as $the_post): ?>
      <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
      <li> <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>" class="author">by <?php print $this->users_model->getPrintableName (  $author['id'], 'full' ); ?></a> <span class="voteUp">
        <?php print $this->votes_model->votesGetCount('table_content', $the_post['id'], '1 year'); ?>
        </span>
        <h2><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
      </li>
      <?php endforeach; ?>
      <?php else: ?>
      <li>No trainings in this category. </li>
      <?php endif; ?>
    </ul>
  </div>
</div>
<a href="<?php print site_url('browse/type:trainings') ?>" class="btn right" id="see-all-trainings">See all traning</a>
<?php dbg(__FILE__, 1); ?>
