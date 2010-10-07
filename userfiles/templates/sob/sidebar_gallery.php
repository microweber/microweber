<?php dbg(__FILE__); ?>

<div class="sidebar">

  <?php if(!$post): ?>
  <?php $who = $this->core_model->getParamFromURL ( 'username' );  $who = $this->users_model->getIdByUsername($who); ?>
  <?php $who = $this->users_model->getUserById($who); ?>
  <?php else: ?>
  <?php $who = $this->users_model->getUserById( $post ['created_by']); ?>
  <?php endif; ?>
  <?php if(empty($active_categories)){
		$active_categories =  $this->content_model->taxonomyGetMasterCategories(false);
		$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
	 }

      $related = array();
	   if($use_master_categories_for_products  == false){
	  $related['selected_categories'] = $active_categories;
	   }
	  $related ['content_type'] = 'post';
	  $related ['content_subtype'] = 'gallery';
	 // $related ['is_special'] = 'y';
	 if(!empty($who)){
	  $related ['created_by'] = $who['id'];
	 }

	  $limit[0] = 0;
	  $limit[1] = 5;
	  $related = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by', 'is_special' ) );

	@shuffle($related);
	@array_slice($related , 0 , 5);
	  ?>
  <?php if(!empty($related)): ?>
  <?php if(!empty($who)): ?>
  <h2 class="title" style="padding: 10px 0 0 0"><?php print $this->users_model->getPrintableName (  $who['id'], 'full' ); ?>'s galleries</h2>
  <?php else: ?>
  <h2 class="title" style="padding: 10px 0 0 0">Galleries from The School</h2>
  <?php endif; ?>
  <ul class="profile-products profile-products-border" style="padding-top: 0">
    <?php foreach($related as $the_post): ?>
    <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
    <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 89, 89);  ?>
    <li>
    <a class="img" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">
    <img src="<?php print $thumb ?>" alt="" />
    </a>
    <div class="sidebar-list-side">
      <h3><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print (character_limiter($the_post['content_title'], 30, '...')); ?>

      </a></h3>
      <p>
        <?php if($the_post['content_description'] != ''): ?>
        <?php print (character_limiter($the_post['content_description'], 50, '...')); ?>
        <?php else: ?>
        <?php print character_limiter($the_post['content_body_nohtml'], 50, '...'); ?>
        <?php endif; ?>
      </p>
      <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="more">see photos</a></div></li>
    <?php endforeach; ?>
  </ul>
  <?php if(empty($who)): ?>
  <a href="<?php print site_url('browse/type:gallery'); ?>" class="btn right hmarg">See All Galleries</a>
  <?php else: ?>
  <a href="<?php print site_url('userbase/action:gallery/username:'.$who['username']); ?>" class="btn right hmarg"><?php print $this->users_model->getPrintableName (  $who['id'], 'first' ); ?>'s galleries</a>
  <?php endif; ?>
  <?php else: ?>
  <?php if(!empty($who)): ?>
  <!--<?php print $this->users_model->getPrintableName (  $who['id'], 'full' ); ?> doesn't have any products.-->
  <?php endif; ?>
  <?php endif;  ?>


     <br />
<br />

  <?php $use_master_categories_for_products = true;
   $use_master_categories_for_trainings = true;
   require (ACTIVE_TEMPLATE_DIR.'sidebar_products.php') ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_trainings.php') ?>
    <?php if(!empty( $author)): ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_widgets.php') ?>
  <?php endif; ?>

  <span class="c"></span> <br />
</div>
<?php dbg(__FILE__, 1); ?>
