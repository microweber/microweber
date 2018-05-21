<?php only_admin_access() ;
 $data = array(
        'content_id' => $params['content_id']
    );

if (isset($params['search-keyword']) and $params['search-keyword']) {
    $kw = $data['keyword'] = $params['search-keyword'];
    $data['search_in_fields'] = 'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';
}



    $comments  = $postComments = get_comments($data);

	$content = get_content_by_id($params['content_id']);

    $content_id =  $params['content_id'];


    $moderation_is_required =  get_option('require_moderation', 'comments')=='y';

?>


<div class="comment-holder" id="comment-n-<?php print $content['id'] ?>" onclick="commentToggle(event);">
    <?php if (!isset($params['no_post_head'])): ?>


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

    <?php endif; ?>

    <div class="order-data-more mw-accordion-content">
        <div>
            <p class="title"><?php print _e('Last comments:'); ?></p>
            <hr/>
            <?php
            if (is_array($postComments)) {
                foreach ($postComments as $comment) { ?>

            <module type="comments/comment_item" id="mw_comments_item_<?php print $comment['id'] ?>" comment_id="<?php print $comment['id'] ?>" >


                <?php } ?>
            <?php } ?>
        </div>


        <div class="clearfix"></div>
    </div>

    <span class="mw-icon-close new-close tip" data-tip="<?php _e("Close"); ?>" data-tipposition="top-center"></span>
    <div class="clearfix"></div>
</div>






