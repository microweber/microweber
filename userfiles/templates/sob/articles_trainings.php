<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box.php') ?>


<div class="main-inner clear">
<!--  <h2 class="in-content-title in-content-title-ico"> <span>Find <a href="#">John Charter</a> at:</span> <a href="#" class="youtube-ico profile-ico">Watch in YouTube </a> <a href="#" class="twitter-ico profile-ico">Follow me	on Twitter </a> <a href="#" class="facebook-ico profile-ico">Become a Fan</a> <b class="titleleft"></b> </h2>-->
  <div class="pad">
  <!--  <h2 class="title">From My Blog</h2>-->

    <a href="#" onclick="scrollto('#comments');" class="comments-link-big right"><?php print $this->content_model->commentsGetCountForContentId($post['id']); ?> comments</a>
    <h2 class="post-title" style="width:490px"><?php print $post['content_title']; ?></h2>
    <div class="richtext"><?php print ($post['the_content_body']); ?><br />
      <!--<span class="uppercase">Please vote for this post</span> <span class="voteUp">2,470</span>--> </div>
    <br />

	<a name="status-nav"><!--  --></a>
    <div class="status-nav">
              <ul>
                <li><span class="date"><?php print date(DATETIME_FORMAT, strtotime($post['created_on'])); ?></span></li>



                <li><a id="content-votes-<?php print $post['id'] ?>" class="voteUp" href="javascript:voteUp('<?php print base64_encode('table_content') ?>', '<?php print base64_encode($post['id']) ?>', 'content-votes-<?php print $post['id'] ?>');"><?php print $this->content_model->votesGetCount('table_content', $post['id'], '1 year'); ?> </a></li>





                 <li><a href="javascript:voteUp('<?php print base64_encode('table_content') ?>', '<?php print base64_encode($post['id']) ?>', 'content-votes-<?php print $post['id'] ?>');">Like</a></li>



                <li><a id="content-comments-<?php print $post['id'] ?>" class="cmm"  onclick="scrollto('#comments');" href="javascript:void(0)"><?php print $this->content_model->commentsGetCountForContentId($post['id']); ?></a></li>

                <li><a href="http://www.addthis.com/bookmark.php?v=20&amp;username=xa-4b86539e06631c47" class="addthis_button_compact">Share</a></li>

              </ul>
          </div>

    <?php require (ACTIVE_TEMPLATE_DIR.'articles_comments_list.php') ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_comments.php') ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_related.php') ?>

    <br />
    <br />
    <a href="javascript:void(0)"><img alt="Learn from the best." src="img/learn_from_the_best.jpg" /></a> <br />
  </div>
</div>
<div class="sidebar">
<h1>This is treaining view page in articles_trainings.php</h1>






   <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_recent_posts.php') ?>






  <br />
  <h2 class="title" style="padding: 10px 0 0 0">John Charter's Products</h2>
  <ul class="profile-products profile-products-border" style="padding-top: 0">
    <li> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg);" class="img" href="#"></a>
      <h3>Product title</h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
      <a href="#" class="more">Read more</a> </li>
    <li> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg);" class="img" href="#"></a>
      <h3>Product title</h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
      <a href="#" class="more">Read more</a> </li>
    <li> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg);" class="img" href="#"></a>
      <h3>Product title</h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
      <a href="#" class="more">Read more</a> </li>
    <li> <a style="background-image: url(<?php print TEMPLATE_URL; ?>img/demo_profile_products.jpg);" class="img" href="#"></a>
      <h3>Product title</h3>
      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
      <a href="#" class="more">Read more</a> </li>
  </ul>
  <a href="#" class="btn right hmarg">See All Products</a> <span class="c"></span> <br />





  <a class="ask-a-question" href="javascript:void(0)">Ask a Question. Feel free to ask us</a>

  <div class="tweet-wrap border-top">
        <a class="tweet facebook-sidebar" href="#">Become a fan</a>
        <a class="tweet twitter-sidebar" href="#">Follow us</a>
    </div>

</div>
<!-- /.sidebar -->