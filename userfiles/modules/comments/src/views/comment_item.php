
<div class="comment-wrapper" id="comment-<?php print $comment['id'] ?>">
    <div class="comment_heading">
        <div class="comment-image">
            <?php
            $image = get_user_by_id($comment['created_by']);
            $image = $image['thumbnail'];
            ?>

            <?php if (isset($image) and $image != ''): ?>
                <span class="comment-thumbnail-tooltip"
                      style="background-image: url('<?php print thumbnail($image, 120, 120); ?>')"></span>
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
            <?php


            ?>
            <a href="#" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline m-l-10 js-edit-comment-btn"
               data-id="<?php print $comment['id'] ?>"><?php print _e('Edit'); ?></a>
            <a href="#" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info mw-ui-btn-outline m-l-10 js-save-comment-btn"
               data-id="<?php print $comment['id'] ?>" style="display: none;"><?php print _e('Save'); ?></a>
            <a  href="#" data-id="<?php print $comment['id'] ?>"  class="js-mark-spam-comment-btn  mw-ui-link mw-ui-btn-small m-l-10 mw-btn-spam"><i
                        class="mai-warn"></i> <?php print _e('Spam'); ?></a>
            <a href="#" data-id="<?php print $comment['id'] ?>"  class="js-delete-comment-btn    mw-ui-link mw-ui-btn-small m-l-10 mw-btn-remove"><i
                        class="mai-bin"></i> <?php print _e('Delete'); ?></a>

            <span class="date"><?php print mw()->format->ago($comment['created_at']); ?></span>
        </div>

        <div class="clearfix"></div>
    </div>


    <div class="author-name">

<?php

d($comment);
?>


        <span><?php print $comment['comment_website']; ?></span>




        <?php if ($comment['comment_email']) { ?>

            <span> | <a href="mailto:<?php print $comment['comment_email']; ?>">Email</a></span>

        <?php } ?>

        <?php if ($comment['comment_website']) { ?>
            <span> | <a href="<?php print mw()->format->prep_url($comment['comment_website']); ?>">Website</a></span>

        <?php } ?>



    </div>

    <form id="comment-form-<?php print $comment['id'] ?>">
        <input type="hidden" name="id" value="<?php print $comment['id'] ?>">
        <input type="text" name="action" class="comment_state semi_hidden"/>
        <input type="hidden" name="connected_id" value="<?php print $comment['rel_id'] ?>">


        <div class="js-comment-edit-details-toggle" style="display: none;">
        comment_name:
        <input type="text" name="comment_name" value="<?php print $comment['comment_name']; ?>" />
            <br>

        comment_email:
        <input type="text" name="comment_email" value="<?php print $comment['comment_email']; ?>" />
            <br>

            comment_website:
        <input type="text" name="comment_website" value="<?php print $comment['comment_website']; ?>" />

            <br>

        </div>




        <div class="comment_body">
            <p class=" js-comment-edit-details-toggle"><?php print $comment['comment_body']; ?></p>
            <textarea   name="comment_body" style="display: none;"><?php print $comment['comment_body']; ?></textarea>



            <a href="#" class="js-save-comment-btn mw-ui-btn"
               data-id="<?php print $comment['id'] ?>"  style="display: none;"><?php print _e('Save'); ?></a>


        </div>







    </form>



<?php if(isset($params['show-reply-form'])){ ?>

    <div class="reply-holder" >

        <div class="reply-form" >



            <?php
            $image = get_user_by_id($comment['created_by']);
            if (!isset($image['thumbnail'])) {
                $image = '';

            } else {
                $image = $image['thumbnail'];

            }
            ?>
            <div class="comment-image">
                <?php if (isset($image) and $image != ''): ?>
                    <span class="comment-thumbnail-tooltip"
                          style="background-image: url(<?php print thumbnail($image, 120, 120); ?>)"></span>
                <?php else: ?>
                    <span class="comment-thumbnail-tooltip mw-user-thumb mw-user-thumb-small mai-user3"></span>
                <?php endif; ?>
            </div>
            <form  id="comment-form-reply-<?php print $comment['id'] ?>" class="js-reply-comment-form">
                <input type="hidden" name="reply_to_comment_id" value="<?php print $comment['id'] ?>">

                <textarea
                        placeholder="<?php print _e('Reply to'); ?> <?php print $comment['comment_name']; ?>" name="comment_body"></textarea>
                <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small pull-right"
                        style="margin-top:6px;"><?php print _e('Send'); ?></button>
            </form>
        </div>

        <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small mw-reply-btn"><i
                    class="mw-icon-reply"></i> <?php print _e('Reply'); ?>
        </button>
    </div>
<?php } else { ?>


    <?php } ?>
</div>
