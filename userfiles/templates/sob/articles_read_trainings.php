<?php dbg(__FILE__); ?>
<?php // require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box.php') ?>
<?php $more = $this->core_model->getCustomFields('table_content', $post['id']); 
	 if(!empty($more)){
		ksort($more); 
	 }  ?>
<?php $image_ids = $this->content_model->mediaGetIdsForContentId($post['id'], $media_type = 'picture'); ?>
<?php // ($images);?>

<div class="main-inner clear">
  <div class="article-info"> <a href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');" class="ainfo"> <b class="voteUp">&nbsp;</b> <strong class="content-votes-count-<?php print $post['id'] ?>"><?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?></strong> <span>like</span> </a> </div>
  <h2 class="training-title"><?php print $post['content_title']; ?></h2>
  
  <!--  <h2 class="in-content-title in-content-title-ico"> <span>Find <a href="#">John Charter</a> at:</span> <a href="#" class="youtube-ico profile-ico">Watch in YouTube </a> <a href="#" class="twitter-ico profile-ico">Follow me	on Twitter </a> <a href="#" class="facebook-ico profile-ico">Become a Fan</a> <b class="titleleft"></b> </h2>-->
  <div class="pad"> 
    <!--  <h2 class="title">From My Blog</h2>--> 
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("a[href*='http://www.google.com/reader/link?url=http://mashable']").remove();
    });
    </script>
    <div class="hr" style="padding-top: 3px;">&nbsp;</div>
    <div class="c" style="padding-bottom: 10px;">&nbsp;</div>
    <div id="training-image">
      <?php if(!empty($image_ids  )) :?>
      <?php $thumb = $this->core_model->mediaGetThumbnailForMediaId($image_ids[0], $size = 600); ?>
      <img src="<?php print $thumb; ?>" alt="" />
      <?php endif; ?>
    </div>

    <div class="trainings-inner-list-wrap">
      <ul class="trainings-inner-list" style="left:0">
        <li class="chapter-item" id="first-chapter"><span class="chapter-title">Introduction</span>
          <div class="fragment-content" id="fragment-0" style="display: block"> <?php print ($post['the_content_body']); ?> </div>
        </li>
        <?php if(!empty($more)): ?>
        <?php $i = 1;
	  foreach($more as $k => $v): ?>
        <?php if(stristr($k, 'content_title_') == true) : ?>
        <li class="chapter-item"><span class="chapter-title"><?php print $v ?></span>
          <div class="chapter-content" id="chapter-<?php print $i ?>"><?php print html_entity_decode( $more['content_body_'.$i]);  ?></div>
        </li>
        <?php $i++; endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>

    <a href="#" class="btn right next-chapter" style="margin-left: 12px;">NEXT CHAPTER</a> <a href="#" class="btn left prev-chapter">PREVIOUS CHAPTER</a>

        <div class="status-nav left">
      <ul>
        <li><span class="date"><?php print date(DATETIME_FORMAT, strtotime($post['created_on'])); ?></span></li>
        <li><a title="<?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?> Votes"  id="content-votes-<?php print $post['id'] ?>" class="title-tip voteUp content-votes-count-<?php print $post['id'] ?>" href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');">
          <?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?>
          </a></li>
        <?php /*
        <li><a href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');">Like</a></li>
        */ ?>
        <?php /*
        <li><a id="content-comments-<?php print $post['id'] ?>" class="cmm title-tip" title="<?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?> Comments"  onclick="scrollto('#comments');" href="javascript:void(0)"><?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?></a></li>
        */ ?>
        <li><a title="Report for spam. <?php print $this->reports_model->reportsGetCount('table_content', $post['id'], '1 year'); ?> reports so far"  id="content-reports-<?php print $post['id'] ?>" class="title-tip reportUp content-reports-count-<?php print $post['id'] ?>" href="javascript:mw.content.report('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-reports-count-<?php print $post['id'] ?>');">
          <?php print $this->reports_model->reportsGetCount('table_content', $post['id'], '1 year'); ?>
          </a></li>
        <li>
          <?php require (ACTIVE_TEMPLATE_DIR.'share.php') ?>
        </li>
      </ul>

    </div>
    <div class="c" style="padding-bottom: 15px;">&nbsp;</div>

    <h2 class="post-title">More from this training</h2>
    <div class="hr">&nbsp;</div>
    <div class="chapter-contents">
      <ul>
        <li><a href="#"><strong>Part 1 - Introduction</strong></a></li>
        <?php if(!empty($more)): ?>
        <?php $i = 1;
	  foreach($more as $k => $v): ?>
        <?php if(stristr($k, 'content_title_') == true) : ?>
        <li><a href="#"><strong>Part <?php print $i+1 ?> - <?php print $v ?></strong></a></li>
        <?php $i++; endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
    
    

    
      <?php require (ACTIVE_TEMPLATE_DIR.'article_gallery.php');  ?>

  </div>
</div>
<div class="sidebar-training"> <img src="<?php print TEMPLATE_URL; ?>img/demo_training_form.jpg" alt="" />
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_trainings.php') ?>
  <a class="ask-a-question" href="javascript:void(0)">Ask a Question. Feel free to ask us</a>
  <div class="tweet-wrap border-top"> <a class="tweet facebook-sidebar" href="#">Become a fan</a> <a class="tweet twitter-sidebar" href="#">Follow us</a> </div>
</div>

<!-- /.sidebar -->
<?php dbg(__FILE__ , 1); ?>
