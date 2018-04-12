<?php
only_admin_access();


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
    window.commentToggle = window.commentToggle || function (item) {
            var curr = $('.order-data-more', item);
            $('.order-data-more').not(curr).stop().slideUp();
            $('.order-holder').not(item).removeClass('active');
            $(curr).stop().slideToggle();
            $(item).toggleClass('active');
            $('#mw-order-table-holder').toggleClass('has-active');
        }
</script>

<div class="dashboard-recent">
    <div class="dr-head">
        <span class="drh-activity-name"><i class="mai-thunder"></i> <?php _e("Last activity") ?></span>
        <a href="<?php print admin_url('view:content/action:posts'); ?>" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline"><?php _e("Go to posts"); ?></a>
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


                    <div class="comment-holder" id="comment-n-<?php print $comment_for_content['id'] ?>" onclick="commentToggle(this);">
                        <div class="order-data">
                            <div class="comment-image">
                                <?php $image = get_picture($comment_for_content['rel_id']); ?>

                                <?php if (isset($image) and $image != ''): ?>
                                    <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail($image, 120, 120); ?>)"></span>
                                <?php else: ?>
                                    <span class="comment-thumbnail-tooltip" style="background-image: url(<?php print thumbnail('', 120, 120); ?>)"></span>
                                <?php endif; ?>
                            </div>

                            <div class="post-name">
                                <span class="author-name"><?php print $comment_for_content['created_by']; ?></span>
                                <a href="<?php print $post['url']; ?>"><?php print $post['title']; ?></a>
                            </div>

                            <div><?php print mw()->format->ago($post['created_at']); ?></div>
                        </div>

                        <div class="order-data-more mw-accordion-content">
                            <a class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info view-order-button" id="vieorder-<?php print $post['id']; ?>" href="<?php print admin_url('view:shop/action:orders#vieworder=' . $post['id']); ?>">
                                <?php _e("View order"); ?>
                            </a>

                            <hr/>
                            <div class="pull-left">
                                <p class="title"><?php print _e('Comments:'); ?></p>
                                <?php
                                if (is_array($postComments)) {
                                    foreach ($postComments as $comment) { ?>
                                        <hr/>
                                        <p><?php print $comment['comment_body']; ?></p>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                <?php } ?>
            <?php endif; ?>
        </div>
    </div>
</div>