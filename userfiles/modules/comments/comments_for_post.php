<?php only_admin_access() ;
 $data = array(
        'content_id' => $params['content_id']
    );

    $comments  = $postComments = get_comments($data);

	$content = get_content_by_id($params['content_id']);

    $content_id =  $params['content_id'];


    $moderation_is_required =  get_option('require_moderation', 'comments')=='y';

?>


<div class="comment-holder" id="comment-n-<?php print $content['id'] ?>" onclick="commentToggle(event);">
    <div class="order-data">

        <div class="article-image">
            <?php $image = get_picture($content['id']); ?>

            <?php if (isset($image) and $image != ''): ?>
                <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($image, 120, 120); ?>)"></span>
            <?php else: ?>
                <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail('', 120, 120); ?>)"></span>
            <?php endif; ?>
        </div>

        <div class="post-name">
            <a href="<?php print content_link($content['id']); ?>"><?php print content_title($content['id']); ?></a>
        </div>

        <div class="last-comment-date"><?php print mw()->format->ago($comments[0]['created_at']); ?></div>
    </div>

    <div class="order-data-more mw-accordion-content">
        <div>
            <p class="title"><?php print _e('Last comments:'); ?></p>
            <hr/>
            <?php
            if (is_array($postComments)) {
                foreach ($postComments as $comment) { ?>


            <module type="comments/views/comment_item_edit" id="mw_comments_item_<?php print $comment['id'] ?>" comment_id="<?php print $comment['id'] ?>" >



            <?php

                    /*<div class="comment-wrapper">
                        <div class="comment_heading">
                            <div class="comment-image">
                                <?php
                                $image = get_user_by_id($comment['created_by']);
                                $image = $image['thumbnail'];
                                ?>

                                <?php if (isset($image) and $image != ''): ?>
                                    <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($image, 120, 120); ?>)"></span>
                                <?php else: ?>
                                    <span class="comment-thumbnail-tooltip mw-user-thumb mw-user-thumb-small mai-user3"></span>
                                <?php endif; ?>
                            </div>

                            <div class="actions-holder">
                                <div class="mw-dropdown mw-dropdown-default">
                                                        <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-small mw-dropdown-val mw-ui-btn-info view-order-button">
                                                            <i class="mai-idea"></i> <?php _e("Approved"); ?>
                                                        </span>
                                    <div class="mw-dropdown-content" style="display: none;">
                                        <ul>
                                            <li value="1">Option 1</li>
                                            <li value="2">Option 2 !!!</li>
                                            <li value="3">Option 3</li>
                                        </ul>
                                    </div>
                                </div>

                                <a href="#" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline m-l-10"><?php print _e('Edit'); ?></a>
                                <a href="#" class="mw-ui-link mw-ui-btn-small m-l-10 mw-btn-spam"><i class="mai-warn"></i> <?php print _e('Spam'); ?></a>
                                <a href="#" class="mw-ui-link mw-ui-btn-small m-l-10 mw-btn-remove"><i class="mai-bin"></i> <?php print _e('Delete'); ?></a>

                                <span class="date"><?php print mw()->format->ago($comment['created_at']); ?></span>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="author-name">
                            <span><?php print $comment['comment_name']; ?></span> <?php print _e('says'); ?>:
                        </div>

                        <div class="comment_body">
                            <p><?php print $comment['comment_body']; ?></p>
                        </div>

                        <div class="reply-holder">
                            <?php
                            $image = get_user_by_id($comment['created_by']);
                            $image = $image['thumbnail'];
                            ?>
                            <div class="reply-form">
                                <div class="comment-image">
                                    <?php if (isset($image) and $image != ''): ?>
                                        <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($image, 120, 120); ?>)"></span>
                                    <?php else: ?>
                                        <span class="comment-thumbnail-tooltip mw-user-thumb mw-user-thumb-small mai-user3"></span>
                                    <?php endif; ?>
                                </div>
                                <form>
                                    <textarea><?php print _e('Reply to'); ?> <?php print $comment['comment_name']; ?></textarea>
                                    <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small pull-right" style="margin-top:6px;"><?php print _e('Send'); ?></button>
                                </form>
                            </div>

                            <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small mw-reply-btn"><i class="mw-icon-reply"></i> <?php print _e('Reply to'); ?> <?php print $comment['comment_name']; ?></button>
                        </div>
                    </div>*/

                    ?>



                <?php } ?>
            <?php } ?>
        </div>


        <div class="clearfix"></div>
    </div>

    <span class="mw-icon-close new-close tip" data-tip="<?php _e("Close"); ?>" data-tipposition="top-center"></span>
    <div class="clearfix"></div>
</div>




<?php

/*<div class="comment-post">
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
*/

?>



