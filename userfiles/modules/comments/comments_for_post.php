<?php only_admin_access() ;
 $data = array(
        'content_id' => $params['content_id']
    );

    $comments = get_comments($data);

	$item = get_content_by_id($params['content_id']);

    $content_id =  $params['content_id'];


    $moderation_is_required =  get_option('require_moderation', 'comments')=='y';

?>



<div class="comment-post">
<?php if(!isset($params['no_post_head'])): ?>
  <div class="comment-info-holder" content-id="<?php print $item['id']; ?>" onclick="mw.adminComments.toggleMaster(this, event)"> <span class="img"> <img src="<?php print thumbnail(get_picture($content_id),67,67); ?>" alt="" />
    <?php // $new = get_comments('count=1&is_moderated=n&rel=content&rel_id='.$content_id);

	 $new = get_comments('count=1&is_new=y&rel=content&rel_id='.$content_id);
	 ?>
    <?php if($new > 0){ ?>
    <span class="comments_number"><?php print $new; ?></span>
    <?php } ?>
    </span>
    <div class="comment-post-content-side">
      <h3><a href="javascript:;" class="mw-ui-link"><?php print $item['title'] ?></a></h3>
      <a class="comment-post-url" href="<?php print content_link($item['id']) ?>?editmode=y"> <?php print content_link($item['id']) ?></a> <br>
    </div>
  </div>
  <?php endif; ?>
  <div class="comments-holder">
    <?php include($config["path_to_module"].'admin_items.php'); ?>
  </div>
  <?php if(!empty($comments)): ?>
  <div class="comments-show-btns"> <span class="mw-ui-btn comments-show-all" onclick="mw.adminComments.display(event,this, 'all');"><?php print ($count_old+$count_new); ?> All</span>
    <?php if( $count_new > 0 ): ?>
    <span class="mw-ui-btn mw-ui-btn-green comments-show-new" onclick="mw.adminComments.display(event,this, 'new');"><?php print $count_new; ?>
    <?php _e("New"); ?>
    </span>
    <?php endif; ?>
  </div>
  <?php endif; ?>
</div>
