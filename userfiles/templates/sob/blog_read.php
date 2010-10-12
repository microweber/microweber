<?php dbg(__FILE__); ?>


<?php include "blog_side_nav.php" ?>


<div class="main-inner" style="padding-top: 0">
<?php include "comunity_nav.php" ?>  
  <!--  <h2 class="in-content-title in-content-title-ico"> <span>Find <a href="#">John Charter</a> at:</span> <a href="#" class="youtube-ico profile-ico">Watch in YouTube </a> <a href="#" class="twitter-ico profile-ico">Follow me	on Twitter </a> <a href="#" class="facebook-ico profile-ico">Become a Fan</a> <b class="titleleft"></b> </h2>-->
  <div class="pad">
    <!--  <h2 class="title">From My Blog</h2>-->

    <div class="article-info">
    <?php /*
    <a href="#" class="article-back" onclick="history.back();">Back</a>
    */ ?>
    <a href="#" onclick="scrollto('#comments');" class="ainfo"> <b class="cmm">&nbsp;</b> <?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?> <span>see all</span> </a> <a href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');" class="ainfo"> <b class="voteUp">&nbsp;</b> <strong class="content-votes-count-<?php print $post['id'] ?>"><?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?></strong> <span>like</span> </a> </div>
    <h2 class="post-title" style="width:490px"><?php print $post['content_title']; ?></h2>
    <div class="richtext"><?php print ($post['the_content_body']); ?><br />
      <!--<span class="uppercase">Please vote for this post</span> <span class="voteUp">2,470</span>--> </div>
    <br />
    <a name="status-nav"><!--  --></a>
    <div class="status-nav">
      <ul>
        <li><span class="date"><?php print date(DATETIME_FORMAT, strtotime($post['created_on'])); ?></span></li>
        <li><a title="<?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?> Votes" id="content-votes-<?php print $post['id'] ?>" class="voteUp title-tip content-votes-count-<?php print $post['id'] ?>" href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');">
          <?php print $this->votes_model->votesGetCount('table_content', $post['id'], '1 year'); ?>
          </a></li>
        <?php /*
        <li><a  href="javascript:mw.content.Vote('<?php print $this->core_model->securityEncryptString('table_content') ?>', '<?php print $this->core_model->securityEncryptString($post['id']) ?>', '.content-votes-count-<?php print $post['id'] ?>');">Like</a></li>
        */ ?>
        <li><a title="<?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?> Comments" id="content-comments-<?php print $post['id'] ?>" class="cmm title-tip"  onclick="scrollto('#comments');" href="javascript:void(0)"><?php print $this->comments_model->commentsGetCountForContentId($post['id']); ?></a></li>
        <li>
          <?php require (ACTIVE_TEMPLATE_DIR.'share.php') ?>
        </li>
      </ul>
    </div>
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_comments_list.php') ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_comments.php') ?>
    <?php require (ACTIVE_TEMPLATE_DIR.'articles_related.php') ?>

  </div>
</div>

<?php dbg(__FILE__, 1); ?>
