<?php only_admin_access() ;
 $data = array(
        'content_id' => $params['content_id'],
        'nolimit' => true,
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



<script type="text/javascript">




    $(document).ready(function () {
        $('.new-close', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var item = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['comment-holder', 'message-holder', 'order-holder']);
            $(item).removeClass('active')
            $('.mw-accordion-content', item).stop().slideUp(function () {

            });
        });


        $('.mw-reply-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            $(this).prev().show();
            $(this).hide();
        });

        $('.js-edit-comment-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var commentID = $(this).data('id');
            $(this).hide();
            $('#comment-' + commentID + ' .js-save-comment-btn').show();
            //  $('#comment-' + commentID + ' .comment_body .js-comment').hide();
            $('#comment-' + commentID + ' .comment_body textarea').show();
            $('#comment-' + commentID + ' .js-comment-edit-details-toggle').toggle();
        });

        $('.js-save-comment-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var commentID = $(this).data('id');
            mw.edit_comments.save_form('#comment-form-'+commentID)
            mw.reload_module('#<?php print $params['id'] ?>');
        });



        $('.js-mark-spam-comment-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var commentID = $(this).data('id');
            mw.edit_comments.mark_as_spam(commentID);
            mw.reload_module('#<?php print $params['id'] ?>');
        });


        $('.js-delete-comment-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var commentID = $(this).data('id');
            mw.edit_comments.delete(commentID);
            mw.reload_module('#<?php print $params['id'] ?>');
        });






        $('.js-reply-comment-form', '#<?php print $params['id'] ?>').on('submit', function(e){
            e.preventDefault();
            e.stopPropagation();
            var form = $(this);
            if (form) {
                mw.edit_comments.save_form(form);
                mw.reload_module('#<?php print $params['id'] ?>');
            }
        });



        $('.js-reply-comment-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

        });
    });

</script>

'#<?php print $params['id'] ?>'

<div class="comment-item-holder-inner" id="comment-item-inner-<?php print $content['id'] ?>"  >
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
                foreach ($postComments as $i=>$comment) { ?>

            <?php
            $last_item_param = '';
            if(!isset($postComments[$i+1])){
                $last_item_param = ' show-reply-form=true ';

            } ?>

            <module type="comments/comment_item" id="mw_comments_item_<?php print $comment['id'] ?>" comment_id="<?php print $comment['id'] ?>" <?php print $last_item_param ?> >


                <?php } ?>
            <?php } ?>
        </div>


        <div class="clearfix"></div>
    </div>

    <span class="mw-icon-close new-close tip" data-tip="<?php _e("Close"); ?>" data-tipposition="top-center"></span>
    <div class="clearfix"></div>
</div>






