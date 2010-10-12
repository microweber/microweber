<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box.php') ?>
<?php $created_by = $this->core_model->getParamFromURL ( 'author' ); ?>
<?php if(intval($created_by) != 0) : ?>
<?php $author = $this->users_model->getUserById( $created_by); ?>
<?php endif; ?>

<div class="main">
  <div id="home-title" class="border-bottom" style="padding-bottom: 3px;">
    <h2><?php print $author['first_name']; ?> <?php print $author['last_name']; ?>'s <?php print strtolower($page['content_title']); ?></h2>
    <!--  <p>Obstacles are things a person sees when he takes his eyes off his goal</p>-->
  </div>
  <div class="posts border-right">
    <?php if(!empty($posts)): ?>
    <?php foreach ($posts as $the_post): ?>
    <?php // p($the_post); ?>
    <div class="post">
      <?php $thumb = $this->users_model->getUserThumbnail( $the_post['created_by'], 50); ?>
      <?php $author = $this->users_model->getUserById($the_post['created_by']); ?>
      <img src="<?php print $thumb; ?>" alt="" class="img" />
      <h2 class="post-title"><a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>"><?php print $the_post['content_title']; ?></a></h2>
      <span class="date">by <a href="<?php print site_url('userbase/action:profile/username:'); ?><?php print $author['username']; ?>"><?php print $author['first_name']; ?> <?php print $author['last_name']; ?> <small>(<?php print $author['username']; ?>)</small></a> | <?php print $the_post['created_on']; ?> | <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>#the_comments_anchor" class="comments-date"><?php print $this->comments_model->commentsGetCountForContentId($the_post['id']); ?> Comments</a></span> <br />
      <p>
        <?php if($the_post['content_description'] != ''): ?>
        <?php print (character_limiter($the_post['content_description'], 200, '...')); ?>
        <?php else: ?>
        <?php print character_limiter($the_post['content_body_nohtml'], 200, '...'); ?>
        <?php endif; ?>
        <a href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" class="read-more">Read More</a> </p>
    </div>
    <?php endforeach; ?>
    <?php else : ?>
    <div class="post">
      <p> There are no posts here. Try again later. </p>
    </div>
    <?php endif; ?>
  </div>
   
</div>
<!-- /.main -->

