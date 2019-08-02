<?php
only_admin_access();

$notification_id = (int) $params['notification_id'];
$data = mw()->notifications_manager->get('single=1&id=' . $notification_id);
?>
<style>
.comments-holder .comment-holder .order-data .last-comment-date {
    padding: 0px;
    float: right;
	margin-right: 40px;
	margin-top:-30px;
}
.new-close.mw-icon-close:not(.module-uninstall-btn) {
    padding-top: 4px;
    top: 99px;
    right: 10px;
}
</style>
<script type="text/javascript">
    mw.require('<?php print modules_url() ?>comments/edit_comments.js');
</script>
<script>
    commentToggle = window.commentToggle || function (e,comment_id) {

            var item = mw.tools.firstParentOrCurrentWithAllClasses(e.target, ['comment-holder']);
            if (!mw.tools.hasClass(item, 'active')) {
                var curr = $('.order-data-more', item);
                $('.order-data-more').not(curr).stop().slideUp();
                $('.comment-holder').not(item).removeClass('active');
                $(curr).stop().slideToggle();
                $(item).toggleClass('active');


                $.ajax({
                    url: "<?php print api_url('mark_comment_post_notifications_as_read') ?>",
                    method: "POST", //First change type to method here

                    data: {
                        comment_id: comment_id, // Second add quotes on the value.
                    },
                    success: function (response) {

                    },
                    error: function () {

                    }


                });

            }
        }
</script>

<div class="comments-holder">
 <div class="mw-admin-comments-search-holder">
<div class="comment-holder active" style="border:0px;" id="comment-n-<?php echo $data['rel_id']; ?>" onclick="commentToggle(event, '<?php echo $data['rel_id']; ?>');">
<module content_id="<?php echo $data['rel_id']; ?>" type="comments/comments_list">
</div>
</div>
</div>