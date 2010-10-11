<?php dbg(__FILE__); ?>
<?php $user_profile_active = true; ?>
<?php //require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box.php') ?>
<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_navigation.php') ?>
<?php $form_values = $user_data; ?>
<?php $action = $this->core_model->getParamFromURL ( 'action' ); ?>
<?php require (ACTIVE_TEMPLATE_DIR.'users/userbase/profile_sidebar.php') ?>

<div class="main" style="padding-top: 20px">
  <?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_inner.php') ?>
  <div class="pad2">
    <div class="status" style="padding-right: 5px">
      <p>
        <?php $status= ($this->users_model->statusesLastByUserId(  $author['id'])); ?>
        <?php print $status['status']; ?></p>
    </div>
  </div>
  <div class="c border-top">&nbsp;</div>
  <div class="pad2">
    <h2 class="title" style="padding: 0 0 10px">Welcome Video</h2>
    <?php $media  = $this->core_model->mediaGetAndCache($to_table = 'table_users', $to_table_id = $author['id'], $media_type = 'video', 'DESC');
		//p($media);
		?>
    <?php if(!empty($media['videos'])): ?>
    <div class="profile-video-object">
      <object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="480" height="385">
        <param name="movie" value="<?php print TEMPLATE_URL ?>js/mediaplayer-5.2-viral/player.swf" />
        <param name="allowfullscreen" value="true" />
        <param name="allowscriptaccess" value="always" />
        <param name="flashvars" value="file=<?php print $media['videos'][0]['url']; ?>&image=<?php print TEMPLATE_URL ?>js/mediaplayer-5.2-viral/play.jpg" />
        <embed
			type="application/x-shockwave-flash"
			id="player2"
			name="player2"
			src="<?php print TEMPLATE_URL; ?>js/mediaplayer-5.2-viral/player.swf" 
			width="480" 
			height="385"
			allowscriptaccess="always" 
			allowfullscreen="true"
			flashvars="file=<?php print $media['videos'][0]['url']; ?>&image=<?php print TEMPLATE_URL; ?>js/mediaplayer-5.2-viral/play.jpg" 		/>
      </object>
    </div>
    <?php else : ?>
    <?php if( ($author['video_embed']) != ''): ?>
    <div class="profile-video-object"> <?php print html_entity_decode( $author['video_embed']);  ?> </div>
    <?php else : ?>
    Sorry, I don't have welcome video yet :(
    <?php endif; ?>
    <?php endif; ?>
  </div>
  <div class="tabs-holder profile-blue-tabs border-bottom">
    <?php $related = array();
if(empty($active_categories)){
$active_categories =  $this->taxonomy_model->getMasterCategories(false);
$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
}
$related['selected_categories'] = $active_categories;
$related['created_by'] = $author['id'];
$related['content_subtype'] = 'trainings';
$limit[0] = 0;
$limit[1] = 5;
$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) );

if(empty($related_posts)){
	$related['created_by'] = false;
	$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) );

}

?>
    <?php if(!empty($related_posts)): ?>
    <h2 class="title left">Learning Center</h2>
    <ul class="blue-tabs-nav right">
      <li><a style="width: 60px;" class="btn btnhover btnactive" href="#new-learning">New</a></li>
      <li><a style="width: 60px;" class="btn btnhover" href="#hot-learning">Hot</a></li>
      <!--  <li><a style="width: 60px;" class="btn btnhover" href="#classic-learning">Classic</a></li>-->
    </ul>
    <div class="tab" id="new-learning">
      <?php foreach($related_posts as $the_post): ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 80, 80);  ?>
      <div class="post"> <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="eimg"> <img src="<?php print $thumb ?>" alt="" /> </a>
        <div class="post-side-only">
          <h2 class="post-title"><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
          <p>
            <?php if($the_post['content_description'] != ''): ?>
            <?php print (character_limiter($the_post['content_description'], 130, '...')); ?>
            <?php else: ?>
            <?php print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
            <?php endif; ?>
          </p>
        </div>
      </div>
      <?php endforeach; ?>
      <a href="<?php print site_url('userbase/action:trainings/username:'. $author['username']); ?>" class="btn right" style="margin-top: 20px;">See All</a> <br class="c" />
    </div>
    <?php $related = array();
if(empty($active_categories)){
$active_categories =  $this->taxonomy_model->getMasterCategories(false);
$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
}
$related['selected_categories'] = $active_categories;
$related['created_by'] = $author['id'];
$related['content_subtype'] = 'trainings';
$related['voted'] = '1 year';
$limit[0] = 0;
$limit[1] = 5;
$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) );

 
 
?>
    
    <!-- /.tab -->
    <div class="tab" id="hot-learning">
    <?php if(!empty($related_posts)): ?>
      <?php foreach($related_posts as $the_post): ?>
      <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 80, 80);  ?>
      <div class="post"> <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="eimg"> <img src="<?php print $thumb ?>" alt="" /> </a>
        <div class="post-side-only">
          <h2 class="post-title"><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
          <p>
            <?php if($the_post['content_description'] != ''): ?>
            <?php print (character_limiter($the_post['content_description'], 130, '...')); ?>
            <?php else: ?>
            <?php print character_limiter($the_post['content_body_nohtml'], 130, '...'); ?>
            <?php endif; ?>
          </p>
        </div>
      </div>
       
      <?php endforeach; ?>
      <?php else: ?>
       <div class="post"> 
       Sorry, I don't have anything hot right now :)
        </div>
      <?php endif; ?>
      <a href="<?php print site_url('userbase/action:trainings/username:'. $author['username']); ?>/voted:1 year" class="btn right" style="margin-top: 20px;">See All</a> <br class="c" />
      
      
    </div>
    <!-- <div class="tab" id="classic-learning"> 3 </div>-->
    
    <?php else: ?>
    <!-- Sorry, I don't have any traininigs published yet!-->
    <?php endif; ?>
  </div>
  <div class="pad2">
    <div class="tabs-holder profile-blue-tabs" style="padding: 0;background: none">
      <h2 class="title left">Recent Posts</h2>
      <ul class="blue-tabs-nav right">
        <li><a href="#recent-blog" style="width: 60px;" class="btn btnhover btnactive">Blog</a></li>
        <li><a href="#recent-forum" style="width: 60px;" class="btn btnhover">Forum</a></li>
      </ul>
      <div class="tab" id="recent-blog">
        <?php $related = array();
if(empty($active_categories)){
$active_categories =  $this->taxonomy_model->getMasterCategories(false);
$active_categories = $this->core_model->dbExtractIdsFromArray($active_categories);
}


$related['selected_categories'] = $active_categories;
$related['created_by'] = $author['id'];
$related['content_subtype'] = 'none';

$limit[0] = 0;
$limit[1] = 5;
$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) );

if(empty($related_posts)){
	$related['created_by'] = false;
	$related_posts = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by' ) );

}

?>
        <?php if(!empty($related_posts)): ?>
        <?php foreach($related_posts as $the_post): ?>
        <?php $no_author_info = true; ?>
        <?php include ACTIVE_TEMPLATE_DIR."articles_list_single_post_item.php" ?>
        <?php endforeach; ?>
        <?php else: ?>
        Sorry, I don't have anything in my blog yet!
        <?php endif; ?>
      </div>
      <div class="tab" id="recent-forum">
        <?php include ACTIVE_TEMPLATE_DIR . 'forum_user_posts.php' ?>
      </div>
    </div>
    <a href="<?php print site_url('userbase/action:articles/username:'. $author['username']); ?>" class="btn right" style="margin-top: 20px;">See All</a> <br class="c" />
    <br />
    <div class="tabs-holder profile-products" id="recent-posts" style="display: none">
      <h2 class="title left">Products</h2>
      <ul class="tabs-nav right">
        <li><a href="#products-new">New</a></li>
        <li><a href="#products-featured">Featured</a></li>
      </ul>
      <div class="tab" id="products-new">
        <ul>
          <li> <a href="#" class="img" style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg)"></a>
            <h3><a href="#">Product title</a></h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply </p>
            <a href="#" class="more">Read more</a> </li>
          <li> <a href="#" class="img" style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_like_profile_products_3.jpg)"></a>
            <h3><a href="#">Product title</a></h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply </p>
            <a href="#" class="more">Read more</a> </li>
          <li> <a href="#" class="img" style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg)"></a>
            <h3><a href="#">Product title</a></h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply </p>
            <a href="#" class="more">Read more</a> </li>
          <li> <a href="#" class="img" style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_like_profile_products_3.jpg)"></a>
            <h3><a href="#">Product title</a></h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply dummy text of the printing and typesetting industry. the industry'...Lorem Ipsum is simply </p>
            <a href="#" class="more">Read more</a> </li>
        </ul>
      </div>
      <div class="tab" id="products-featured"> </div>
    </div>
    <a href="#"> <img alt="Learn from the best." src="<?php print TEMPLATE_URL; ?>img/learn_from_the_best.jpg" /> </a> 
    <!-- /.pad --> 
    
  </div>
</div>
<?php //var_dump($user_data);

 //   print $form_values['first_name'];  ?>
<?php // print($form_values['user_information']); ?>

<!-- /.main-inner -->
<?php dbg(__FILE__, 1); ?>
