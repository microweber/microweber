<script type="text/javascript">
    mw.require('<?php print modules_url() ?>comments/edit_comments.js');
</script>
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



    $(mwd).ready(function () {

        mw.dropdown();
        $(mwd.body).ajaxStop(function () {
            setTimeout(function () {
                mw.dropdown();
            }, 1222);
        });
    });
</script>




<script type="text/javascript">
/*



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
            //  $('#comment-' + commentID + ' .comment_body .js-comment').hide();
            $('#comment-' + commentID + ' .comment_body textarea').show();
            $('#comment-' + commentID + ' .js-comment-edit-details-toggle').toggle();
        });

        $('.js-save-comment-btn').on('click', function (e) {
            e.preventDefault();
            var commentID = $(this).data('id');
            $(this).hide();
            $('#comment-' + commentID + ' .js-edit-comment-btn').show();
            // $('#comment-' + commentID + ' .comment_body .js-comment').show();
            $('#comment-' + commentID + ' .comment_body textarea').hide();
            $('#comment-' + commentID + ' .js-comment-edit-details-toggle').toggle();

            $('#comment-' + commentID + ' .comment_body .js-comment').text($('#comment-' + commentID + ' .comment_body textarea').val());

        });


        $('.js-reply-comment-form').on('submit', function(e){

            e.preventDefault();
            var form = $(this);
            if (form) {
                mw.edit_comments.save_form(form);
            }
        });



        $('.js-reply-comment-btn').on('click', function (e) {
            e.preventDefault();


        });
    });*/

</script>


<div class="comments-holder">
    <?php if (is_array($data) and !empty($data)): ?>

        <div class="mw-admin-comments-search-holder">
            <?php foreach ($data as $item){ ?>


            <div class="comment-holder" id="comment-n-<?php print $item['id'] ?>" onclick="commentToggle(event);">


            <?php if (isset($item['rel_type']) and $item['rel_type'] == 'content'): ?>
            <module type="comments/comments_list" id="mw_comments_for_post_<?php print $item['rel_id'] ?><?php print $item['id'] ?>"
                    content_id="<?php print $item['rel_id'] ?>" search-keyword="<?php print $kw ?>">
                <?php endif; ?>
                <?php if (isset($item['rel_type']) and $item['rel_type'] == 'modules'): ?>
                <module type="comments/comments_list" id="mw_comments_for_post_<?php print $item['rel_id'] ?><?php print $item['id'] ?>"
                        rel_id="<?php print $item['rel_id'] ?>" rel="<?php print $item['rel_type'] ?>">
                    <?php endif; ?>
                    <?php // _d($item);  break;  ?>

            </div>

                    <?php }; ?>
        </div>
    <?php else: ?>
        <h5><?php _e('There are not comments here.'); ?></h5>
        <br/>
        <a href="#content_id=0" class="mw-ui-btn">
            <?php _e("See all comments"); ?>
        </a>
    <?php endif; ?>
</div>
