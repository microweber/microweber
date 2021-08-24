<?php
must_have_access();
$data = [];
if (isset($params['content_id'])) {
    $data = array(
        'content_id' => $params['content_id'],
        'nolimit' => true,
    );

} elseif (isset($params['rel_id']) and isset($params['rel_type'])) {
    $data = array(
        'rel_id' => $params['rel_id'],
        'rel_type' => $params['rel_type'],
        'nolimit' => true,
    );
}

if (isset($params['search-keyword']) and $params['search-keyword']) {
    $kw = $data['keyword'] = $params['search-keyword'];
    $data['search_in_fields'] = 'comment_name,comment_body,comment_email,comment_website,from_url,comment_subject';
}

$content = false;
$content_id = false;

$comments = $postComments = get_comments($data);

if (isset($params['content_id'])) {
    $content = get_content_by_id($params['content_id']);
    if (!$content) {
        return;
    }

    $content_id = $params['content_id'];
}

if (!isset($content['id'])) {
    return;
}

$moderation_is_required = get_option('require_moderation', 'comments') == 'y';
?>




<script>
    mw.lib.require('mwui_init');
</script>
<div class="comment-item-holder-inner" id="comment-item-inner-<?php print $content['id'] ?>">
    <?php
    if (is_array($postComments)): ?>
        <?php foreach ($postComments as $i => $comment) : ?>

            <?php
            $last_item_param = '';
            if (!isset($postComments[$i + 1])) {
                $last_item_param = ' show-reply-form=true ';
            }
            ?>

            <module type="comments/comment_item" id="mw_comments_item_<?php print $comment['id'] ?>" comment_id="<?php print $comment['id'] ?>" <?php print $last_item_param ?> />

        <?php endforeach; ?>
    <?php else: ?>
        <div class="icon-title">
            <i class="mdi mdi-comment-account"></i> <h5><?php _e('You don\'t have any comments'); ?></h5>
        </div>
    <?php endif; ?>
</div>

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

            $('#comment-' + commentID + ' .js-comment-name-text').hide();
            $('#comment-' + commentID + ' .js-comment-name-input').show();

            $('#comment-' + commentID + ' .js-comment-email-text').hide();
            $('#comment-' + commentID + ' .js-comment-email-input').show();

            $('#comment-' + commentID + ' .js-comment-website-text').hide();
            $('#comment-' + commentID + ' .js-comment-website-input').show();

            $('#comment-' + commentID + ' .js-comment-body-text').hide();
            $('#comment-' + commentID + ' .js-comment-body-textarea').show();

            //  $('#comment-' + commentID + ' .comment_body .js-comment').hide();
            $('#comment-' + commentID + ' .comment_body textarea').show();
            $('#comment-' + commentID + ' .js-comment-edit-details-toggle').toggle();
        });

        $('.js-save-comment-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var commentID = $(this).data('id');
            mw.edit_comments.save_form('#comment-form-' + commentID)
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


        $('.js-comment-approved-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var commentID = $(this).data('id');
            mw.edit_comments.publish(commentID);
            mw.reload_module('#<?php print $params['id'] ?>');
        });


        $('.js-comment-unpublished-btn', '#<?php print $params['id'] ?>').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var commentID = $(this).data('id');
            mw.edit_comments.unpublish(commentID);
            mw.reload_module('#<?php print $params['id'] ?>');
        });


        $('.js-reply-comment-form', '#<?php print $params['id'] ?>').on('submit', function (e) {
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

        mw.dropdown();
    });

</script>




