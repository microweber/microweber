<?php dbg(__FILE__); ?>
<?php $type = CI::model('core')->getParamFromURL ( 'type' ); ?>

<div>
  <?php //require (ACTIVE_TEMPLATE_DIR.'users/posts_nav.php') ?>
  <?php //include ACTIVE_TEMPLATE_DIR. "articles_search_bar.php" ?>
  <div class="c" style="padding-bottom: 12px">&nbsp;</div>
  <?php if(!empty($posts_data['posts'])): ?>
  <a href="#" class="btn" onclick="$('#start-publish').slideToggle()">Start publishing +</a>
  <div class="c" style="padding-bottom: 10px;"></div>
  <div class="start-publish" id="start-publish"> <a href="<?php print site_url('users/user_action:post/type:none'); ?>">Add new post</a>| <a href="<?php print site_url('users/user_action:post/type:gallery'); ?>">Add new gallery</a> </div>
  <?php foreach ($posts_data['posts'] as $the_post): ?>
  <?php $show_edit_and_delete_buttons = true; ?>
  <?php $no_author_info = true; ?>
  <?php include ACTIVE_TEMPLATE_DIR."articles_list_single_post_item.php" ?>
  <?php endforeach; ?>
  <?php include ACTIVE_TEMPLATE_DIR."articles_paging.php" ?>
  <?php else : ?>
  <?php if(($type == 'none') or (!$type) or ($type == 'all') ): ?>
  <div class="nopost-block nopost-block-post">
    <div class="nopostnote"> <span>&nbsp;</span> </div>
    <h3>There are no posts created yet</h3>
    <a href="<?php print site_url('users/user_action:post/'); ?>" class="btn">Create your first post</a> </div>
  <?php endif; ?>
  <?php endif; ?>
</div>
<?php dbg(__FILE__,1); ?>
