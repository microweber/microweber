<?php dbg(__FILE__); ?>

<?php include "comunity_nav.php" ?>
<div class="learn" id="learn-tabs">
  <div class="mw-tabs" id="connect">
    <h2 class="blue-title clear">Learn from our members!</h2>
    <div id="learn-video">
      <object width="610" height="276">
        <param name="movie" value="http://www.youtube.com/v/ufTtT1rKUAk&hl=en_US&fs=1&">
        </param>
        <param name="allowFullScreen" value="true">
        </param>
        <param name="wmode" value="transparent">
        </param>
        <param name="allowscriptaccess" value="always">
        </param>
        <embed src="http://www.youtube.com/v/ufTtT1rKUAk&hl=en_US&fs=1&" wmode="transparent" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="610" height="276"></embed>
      </object>
    </div>
  </div>
  <div class="mw-tabs" id="learn">
    <h2 class="blue-title clear">Lorem ipsum dolor sit amet</h2>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sagittis imperdiet viverra. Sed eget erat eros. Suspendisse ut mauris eu eros condimentum egestas. Quisque vel eros sit amet mauris vulputate cursus. Vestibulum vel tortor et metus porttitor aliquam eu non mi. Nunc cursus turpis nec odio laoreet pharetra. Sed placerat enim in augue elementum egestas. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Vestibulum orci nisi, adipiscing in tempus in, rutrum vel diam. Donec eget lorem sed quam commodo eleifend eu eget leo. Vivamus feugiat, tellus non ornare faucibus, eros mauris iaculis odio, eget consectetur nisl diam non urna. Suspendisse hendrerit malesuada sapien, et ullamcorper diam viverra a. Nullam et dui eu elit sagittis viverra in quis enim. In vitae quam quis est scelerisque interdum eu eu ipsum. Nullam tempus dapibus mauris tempus hendrerit. Quisque volutpat molestie ultricies. </div>
  <div class="mw-tabs" id="teach">
    <h2 class="blue-title clear">Duis rhoncus ultrices dolor</h2>
    Duis rhoncus ultrices dolor, et volutpat erat aliquet eget. Nunc eleifend rutrum dictum. Fusce congue varius auctor. Cras pellentesque diam quis nulla consequat sed porta nunc porta. Aliquam adipiscing tincidunt magna a fringilla. Proin eu tortor massa, eget luctus mauris. Nullam eleifend bibendum arcu, non rutrum nisi tincidunt quis. Pellentesque odio elit, commodo ac semper nec, pellentesque sit amet lorem. Aenean in lectus non nulla vestibulum luctus. Phasellus ornare enim non nisi tincidunt vel euismod dui pharetra. Aenean non venenatis nibh. Curabitur convallis, sapien convallis semper feugiat, augue libero sodales lorem, sit amet sollicitudin est dolor eget enim. </div>
  <div class="mw-tabs" id="grow">
    <h2 class="blue-title clear">Nullam lectus lacus, commodo eget</h2>
    Nullam lectus lacus, commodo eget faucibus fermentum, eleifend sit amet dolor. Fusce id purus sed metus egestas eleifend at sed nisl. Vestibulum posuere scelerisque lacus, non tempus lorem lobortis eget. Nulla sit amet tortor eros, a mattis ligula. Sed eu dolor tellus. Sed id turpis nisl, a condimentum dui. Quisque nulla arcu, vulputate vitae consequat ut, commodo ut quam. Ut a erat erat, pretium tincidunt velit. Aenean lobortis aliquet pellentesque. Mauris at massa nibh, elementum bibendum ligula. Quisque facilisis, risus vitae faucibus vestibulum, nisi nibh sollicitudin ligula, vitae elementum augue turpis vel ante. Cras magna metus, elementum a porttitor sit amet, scelerisque lobortis eros. Integer ultrices nisl sed lectus aliquam porttitor. Duis ut felis augue, eget placerat tellus. Fusce nulla leo, fringilla nec ullamcorper eu, cursus sed mauris. Nullam felis justo, porttitor quis scelerisque at, elementum eu tellus. Maecenas feugiat congue sagittis. Quisque rhoncus nisi quis mauris sodales bibendum sit amet quis leo. </div>
  <div id="learn-side">
    <ul class="learn-nav">
      <li><a href="#connect" class="Connect-btn active">Connect</a></li>
      <li><a href="#learn" class="Learn-btn">Learn</a></li>
      <li><a href="#teach" class="Teach-btn">Teach</a></li>
      <li><a href="#grow" class="Grow-btn">Grow</a></li>
    </ul>
    <br class="c" />
    <br />
    <div class="mw-tab-text" id="connect-text"> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sagittis imperdiet viverra. </div>
    <div class="mw-tab-text" id="learn-text"> Sed eget erat eros. Suspendisse ut mauris eu eros condimentum egestas. </div>
    <div class="mw-tab-text" id="teach-text"> Quisque vel eros sit amet mauris vulputate cursus. </div>
    <div class="mw-tab-text" id="grow-text"> estibulum vel tortor et metus porttitor aliquam eu non mi. </div>
  </div>
</div>
<div class="main">
  <div class="popular-discussions" style="border:none;padding-bottom:0;">
    <div class="pd-ctrl-wrapper">
      <h2 class="title">Community Announcements</h2>
      <div class="wrap-pd-ctrl"> <span class="pd-left">&laquo;</span> <span class="pd-ctrl active">1</span> <span class="pd-ctrl">2</span> <span class="pd-ctrl">3</span> <span class="pd-right">&raquo;</span> <span class="pd-dot"></span> </div>
    </div>
    <div class="popular-slider-holder" style="background:white;">
      <?php $related = array();
	  $related['selected_categories'] = array(1870);
	 // $related ['content_type'] = 'post';
	//  $related ['content_subtype'] = 'none';

	  
	

	  
	  $limit[0] = 0;
	  $limit[1] = 15;
	  $related = $this->content_model->getContentAndCache($related, false,$limit,  $count_only = false, $short = true, $only_fields = array ('id', 'content_type', 'content_url', 'content_title', 'content_description',  'content_body', 'created_by', 'is_special' ) ); 
	// p ( $related,1);

	  ?>
      <?php if(!empty($related)): ?>

      <div class="popular-slider">
        <?php foreach($related as $the_post): ?>
        <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
        <?php $thumb = $this->content_model->contentGetThumbnailForContentId( $the_post['id'], 250, 250);  ?>
        <div class="post"> <a class="eimg" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"> <span style="background-image:url('<?php print $thumb; ?>')"></span> </a>
          <h2 class="post-title"><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
          <span class="date">by <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"> <?php print $author['first_name']; ?> <?php print $author['last_name']; ?> </a> | <?php print date(DATETIME_FORMAT, strtotime($the_post['created_on'])); ?> | <a class="comments-date" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#the_comments_anchor"><?php print $this->content_model->commentsGetCountForContentId($the_post['id']); ?> Comments</a></span>
          <p>
            <?php if($the_post['content_description'] != ''): ?>
            <?php print (character_limiter($the_post['content_description'], 150, '...')); ?>
            <?php else: ?>
            <?php print character_limiter($the_post['content_body_nohtml'], 150, '...'); ?>
            <?php endif; ?>
          </p>
        </div>
        <?php endforeach; ?>

        </div>

        <?php endif;  ?>



    </div>
  </div>
  <!-- /#popular-discussions --> 
  
  <a href="<?php print $this->taxonomy_model->getUrlForIdAndCache(1870) ; ?>" class="btn right wmarg">All Announcements</a>
  <div style="height:20px;clear:both"> </div>
  <div id="home-tops-slider-wrapper" class="border-top hpad">
    <h2 class="title">Welcome to your <strong>online social business</strong> network.</h2>
    <?php require (ACTIVE_TEMPLATE_DIR.'home_slider.php') ?>
    <!-- /#home-slider --> 
  </div>
   <?php include ACTIVE_TEMPLATE_DIR."forum_discussions.php" ?>

  <br />
  <br />
  <br />
  <div align="center"><a href="#"><img align="center" src="<?php print TEMPLATE_URL; ?>img/learnfromthebestadd.jpg" alt=""  /></a></div>
  <br />
  <br />
  <div align="center"><a href="#"><img align="center" src="<?php print TEMPLATE_URL; ?>img/teachandgrowadd.jpg" alt=""  /></a></div>
  <br />
  <br />
</div>
<!-- /main -->
<div class="sidebar">
  <div class="pad border-bottom">
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_how_to_box.php') ?>
  </div>
  <div style="width:303px;margin:auto">
    <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_learning_center.php') ?>
  </div>
 
 <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_products.php') ?>
 
 </div>
<?php dbg(__FILE__, 1); ?>
