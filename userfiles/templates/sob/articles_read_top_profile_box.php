<?php dbg(__FILE__); ?>
<?php if(  !empty($post) ): ?>
<?php $created_by = $this->core_model->getParamFromURL ( 'author' ); ?>
<?php if(intval($created_by) != 0) : ?>
<?php $author = $this->users_model->getUserById($created_by); ?>
<?php else : ?>
<?php $author = $this->users_model->getUserById($post['created_by']); ?>
<?php endif; ?>
<?php $type = $post['content_subtype']; ?>
<?php else : ?>
<?php $type = $this->core_model->getParamFromURL ( 'type' ); ?>
<?php $created_by = $this->core_model->getParamFromURL ( 'author' ); ?>
<?php if(intval($created_by) != 0) : ?>
<?php $author = $this->users_model->getUserById( $created_by); ?>
<?php else : ?>
<?php $author = $user_data ; ?>
<?php endif; ?>
<?php endif; ?>
<?php if(!empty($author)): ?>
<?php if(!empty($post) ): ?>
<?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_navigation.php') ?>
<?php if($short_profile == false): ?>
<?php require (ACTIVE_TEMPLATE_DIR.'users/userbase/sidebar_extended.php') ?>
<?php endif; ?>
<?php if(($short_sidebar == true) and ($sidebar_file == false)): ?>
<div class="sidebar">
  <?php if(!empty( $author)): ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_widgets.php') ?>
  <?php endif; ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_recent_posts.php') ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_products.php') ?>
  <?php require (ACTIVE_TEMPLATE_DIR.'sidebar_trainings.php') ?>
  <a class="ask-a-question" href="javascript:void(0)">Ask a Question. Feel free to ask us</a>
  <div class="tweet-wrap border-top"> <a class="tweet facebook-sidebar" href="#">Become a fan</a> <a class="tweet twitter-sidebar" href="#">Follow us</a> </div>
</div>
<!-- /.sidebar -->
<?php endif; ?>

<?php if(($sidebar_file != false)): ?>
  <?php require ($sidebar_file) ?>
<?php endif; ?>
<div class="main" style="padding-top: 20px">
  <?php require (ACTIVE_TEMPLATE_DIR.'articles_read_top_profile_box_inner.php') ?>
</div>

<!-- tuka -->
<?php endif; ?>

<!-- /#profile-main -->
<?php endif; ?>
<?php dbg(__FILE__, 1); ?>
