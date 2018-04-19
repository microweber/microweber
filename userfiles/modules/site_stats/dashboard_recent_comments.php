<?php
only_admin_access();


// file moved to userfiles/modules/comments/dashboard_recent_comments.php
// file moved to userfiles/modules/comments/dashboard_recent_comments.php
// file moved to userfiles/modules/comments/dashboard_recent_comments.php
// file moved to userfiles/modules/comments/dashboard_recent_comments.php
// file moved to userfiles/modules/comments/dashboard_recent_comments.php
// file moved to userfiles/modules/comments/dashboard_recent_comments.php





return;




?>

<?php
$comments_data = array(
    'order_by' => 'created_at desc',
    'rel_type' => 'content',
    'group_by' => 'rel_id',

    'limit' => '5',
);
$comments_for_content = get_comments($comments_data);

if (is_array($comments_for_content)) {


    $ccount = count($comments_for_content);

} else {
    $ccount = 0;
}
?>

<script>
    commentToggle = window.commentToggle || function (e) {

            var item = mw.tools.firstParentOrCurrentWithAllClasses(e.target, ['comment-holder']);
            if (!mw.tools.hasClass(item, 'active')) {
                var curr = $('.order-data-more', item);
                $('.order-data-more').not(curr).stop().slideUp();
                $('.comment-holder').not(item).removeClass('active');
                $(curr).stop().slideToggle();
                $(item).toggleClass('active');
            }

        }

    $(document).ready(function () {
        $('.new-close').on('click', function (e) {
            e.stopPropagation();
            var item = mw.tools.firstParentOrCurrentWithAnyOfClasses(e.target, ['comment-holder', 'message-holder', 'order-holder']);
            $(item).removeClass('active')
            $('.mw-accordion-content', item).stop().slideUp(function () {

            });
        });


        $('.mw-reply-btn').on('click', function (e) {
            $(this).prev().show();
            $(this).hide();
        });

        $('.js-edit-comment-btn').on('click', function (e) {
            e.preventDefault();
            var commentID = $(this).data('id');
            $(this).hide();
            $('#comment-' + commentID + ' .js-save-comment-btn').show();
            $('#comment-' + commentID + ' .comment_body .js-comment').hide();
            $('#comment-' + commentID + ' .comment_body textarea').show();

        });

        $('.js-save-comment-btn').on('click', function (e) {
            e.preventDefault();
            var commentID = $(this).data('id');
            $(this).hide();
            $('#comment-' + commentID + ' .js-edit-comment-btn').show();
            $('#comment-' + commentID + ' .comment_body .js-comment').show();
            $('#comment-' + commentID + ' .comment_body textarea').hide();

            $('#comment-' + commentID + ' .comment_body .js-comment').text($('#comment-' + commentID + ' .comment_body textarea').val());

        });
    });


    save_comment_form = function (form_id) {
        mw.form.post(form_id, '<?php print api_link('post_comment'); ?>')
        mw.notification.success('Comment saved')
    }


</script>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-comment"></i> <?php _e("Last comments") ?></span>
        <a href="<?php print admin_url('view:content/action:posts'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline"><?php _e("Go to comments "); ?></a>
        <a href="#" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info"><strong><?php print $ccount; ?></strong> <?php print _e('New comments'); ?></a>
    </div>
    <div class="dr-list">
        <div class="comments-holder">

            <?php if (is_array($comments_for_content)): ?>
                <?php foreach ($comments_for_content as $comment_for_content) {
                    $params = array(
                        'id' => $comment_for_content['rel_id']
                    );
                    $post = get_content($params);

                    if (!$post) {
                        continue;
                    }
                    $post = $post[0];
                    $comments_data = array(
                        'order_by' => 'created_at desc',
                        'limit' => 5,
                        'rel_id' => $post['id']
                    );
                    $postComments = get_comments($comments_data);

                    ?>


                    <div class="comment-holder" id="comment-n-<?php print $comment_for_content['id'] ?>" onclick="commentToggle(event);">
                        <div class="order-data">

                            <div class="article-image">
                                <?php $image = get_picture($comment_for_content['rel_id']); ?>

                                <?php if (isset($image) and $image != ''): ?>
                                    <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($image, 120, 120); ?>)"></span>
                                <?php else: ?>
                                    <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail('', 120, 120); ?>)"></span>
                                <?php endif; ?>
                            </div>

                            <div class="post-name">
                                <a href="<?php print $post['url']; ?>"><?php print $post['title']; ?></a>
                            </div>

                            <div class="last-comment-date"><?php print mw()->format->ago($comment_for_content['created_at']); ?></div>
                        </div>

                        <div class="order-data-more mw-accordion-content">
                            <div>
                                <p class="title"><?php print _e('Last comments:'); ?></p>
                                <hr class="m-0"/>
                                <?php
                                if (is_array($postComments)) {
                                    foreach ($postComments as $comment) { ?>


                                        <div class="comment-wrapper" id="comment-<?php print $comment['id'] ?>">
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

                                                    <a href="#" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline m-l-10 js-edit-comment-btn" data-id="<?php print $comment['id'] ?>"><?php print _e('Edit'); ?></a>
                                                    <a href="#" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline m-l-10 js-save-comment-btn" data-id="<?php print $comment['id'] ?>" style="display: none;" onclick="save_comment_form('#comment-form-<?php print $comment['id'] ?>');return false;"><?php print _e('Save'); ?></a>
                                                    <a href="#" class="mw-ui-link mw-ui-btn-small m-l-10 mw-btn-spam"><i class="mai-warn"></i> <?php print _e('Spam'); ?></a>
                                                    <a href="#" class="mw-ui-link mw-ui-btn-small m-l-10 mw-btn-remove"><i class="mai-bin"></i> <?php print _e('Delete'); ?></a>

                                                    <span class="date"><?php print mw()->format->ago($comment['created_at']); ?></span>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>


                                            <div class="author-name">
                                                <span><?php print $comment['comment_name']; ?></span> <?php print _e('says'); ?>:
                                            </div>

                                            <form id="comment-form-<?php print $comment['id'] ?>">
                                                <input type="hidden" name="id" value="<?php print $comment['id'] ?>">
                                                <input type="text" name="action" class="comment_state semi_hidden"/>
                                                <input type="hidden" name="connected_id" value="<?php print $comment['rel_id'] ?>">

                                                <div class="comment_body">
                                                    <p class="js-comment"><?php print $comment['comment_body']; ?></p>
                                                    <textarea name="comment_body" style="display: none;"><?php print $comment['comment_body']; ?></textarea>
                                                </div>
                                            </form>

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
                                                        <textarea placeholder="<?php print _e('Reply to'); ?> <?php print $comment['comment_name']; ?>"></textarea>
                                                        <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small pull-right" style="margin-top:6px;"><?php print _e('Send'); ?></button>
                                                    </form>
                                                </div>

                                                <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small mw-reply-btn"><i class="mw-icon-reply"></i> <?php print _e('Reply to'); ?> <?php print $comment['comment_name']; ?></button>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>


                            <div class="clearfix"></div>
                        </div>

                        <span class="mw-icon-close new-close tip" data-tip="<?php _e("Close"); ?>" data-tipposition="top-center"></span>
                        <div class="clearfix"></div>
                    </div>
                <?php } ?>
            <?php else: ?>
                <div class="mw-ui-box">
                    <div class="mw-ui-box-content center p-40"><?php _e('You don\'t have any comments yet.'); ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>