<div class="comment-wrapper" id="comment-<?php print $comment['id'] ?>">
    <div class="comment_heading">
        <div class="comment-image">
            <?php
            $image = '';
            if ($comment['created_by']) {
                $image = get_user_by_id($comment['created_by']);
                $image = $image['thumbnail'];

                if (!$comment['comment_email']) {
                    $comment['comment_email'] = user_email($comment['created_by']);
                }
                if (!$comment['comment_name']) {
                    $comment['comment_name'] = user_name($comment['created_by']);
                }
            }


            ?>

            <?php if (isset($image) and $image != ''): ?>
                <span class="comment-thumbnail-tooltip"
                      style="background-image: url('<?php print thumbnail($image, 120, 120); ?>')"></span>
            <?php else: ?>
                <span class="comment-thumbnail-tooltip mw-user-thumb mw-user-thumb-small mai-user3"></span>
            <?php endif; ?>
        </div>

        <?php

        ?>

        <div class="actions-holder">
            <div class="mw-dropdown mw-dropdown-default">
                <?php
                $required_moderation = get_option('require_moderation', 'comments') == 'y';

                $status = 'Published';
                $class = 'mw-ui-btn-info';

                if ($required_moderation and (!isset($comment['is_moderated']) or intval($comment['is_moderated']) == 0)) {
                    $comment['is_moderated'] = 2;
                }

                if (isset($comment['is_moderated'])) {
                    if (intval($comment['is_moderated']) == 2) {
                        $status = 'Awaiting approval';
                        $class = 'mw-ui-btn-warn';
                    } else if (intval($comment['is_moderated']) == 1) {
                        $status = 'Published';
                    } else {
                        $status = 'Unpublished';
                        $class = 'mw-ui-btn-warn';
                    }
                }
                if (isset($comment['is_spam']) and intval($comment['is_spam']) == 1) {
                    $status = 'Marked as spam';
                    $class = 'mw-ui-btn-important';
                }
                ?>


                <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-medium mw-dropdown-val <?php print($class); ?>  view-order-button"><?php _e($status); ?></span>

                <div class="mw-dropdown-content" style="display: none;">
                    <ul>
                        <li class="js-comment-approved-btn" data-id="<?php print $comment['id'] ?>"><?php print _e('Published'); ?></li>
                        <li class="js-comment-unpublished-btn" data-id="<?php print $comment['id'] ?>"><?php print _e('Unpublish'); ?></li>
                        <li class="js-mark-spam-comment-btn" data-id="<?php print $comment['id'] ?>"><?php print _e('Spam'); ?></li>
                        <li class="js-delete-comment-btn" data-id="<?php print $comment['id'] ?>"><?php print _e('Delete'); ?></li>
                    </ul>
                </div>
            </div>

            <a href="#" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline m-l-10 js-edit-comment-btn" data-id="<?php print $comment['id'] ?>">
                <?php print _e('Edit'); ?>
            </a>
            <a href="#" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-outline m-l-10 js-save-comment-btn" data-id="<?php print $comment['id'] ?>" style="display: none;">
                <?php print _e('Save'); ?>
            </a>
            <a href="#" data-id="<?php print $comment['id'] ?>" class="js-mark-spam-comment-btn  mw-ui-link mw-ui-btn-medium m-l-10 mw-btn-spam">
                <i class="mai-warn"></i>&nbsp; <?php print _e('Spam'); ?>
            </a>
            <a href="#" data-id="<?php print $comment['id'] ?>" class="js-delete-comment-btn    mw-ui-link mw-ui-btn-medium m-l-10 mw-btn-remove">
                <i class="mai-bin"></i>&nbsp; <?php print _e('Delete'); ?>
            </a>

            <span class="date"><?php print mw()->format->ago($comment['created_at']); ?></span>
        </div>

        <div class="clearfix"></div>
    </div>


    <div class="author-name">
        <span><?php print $comment['comment_name']; ?> says:</span>
        <br/>

        <?php if ($comment['comment_email']) { ?>
            <span><a href="mailto:<?php print $comment['comment_email']; ?>"><?php print $comment['comment_email']; ?></a></span>
        <?php } ?>

        <?php if ($comment['comment_website']) { ?>
            <span> | <a href="<?php print mw()->format->prep_url($comment['comment_website']); ?>" target="_blank">Website</a></span>
        <?php } ?>

    </div>

    <form id="comment-form-<?php print $comment['id'] ?>">
        <input type="hidden" name="id" value="<?php print $comment['id'] ?>">
        <input type="text" name="action" class="comment_state semi_hidden"/>
        <input type="hidden" name="connected_id" value="<?php print $comment['rel_id'] ?>">


        <div class="js-comment-edit-details-toggle" style="display: none;">
            <div class="mw-ui-row">
                <div class="mw-ui-col" style="padding-right: 10px;">
                    <div class="m-b-10">
                        <label class="mw-ui-label bold p-b-10">Comment name:</label>
                        <input type="text" name="comment_name" class="mw-ui-field mw_option_field block-field"
                               value="<?php print $comment['comment_name']; ?>"/>
                    </div>
                </div>

                <div class="mw-ui-col" style="padding-right: 10px;">
                    <div class="m-b-10">
                        <label class="mw-ui-label bold p-b-10">Comment email:</label>
                        <input type="text" name="comment_email" class="mw-ui-field mw_option_field block-field"
                               value="<?php print $comment['comment_email']; ?>"/>
                    </div>
                </div>

                <div class="mw-ui-col">
                    <div class="m-b-10">
                        <label class="mw-ui-label bold p-b-10">Comment website:</label>
                        <input type="text" name="comment_website" class="mw-ui-field mw_option_field block-field"
                               value="<?php print $comment['comment_website']; ?>"/>
                    </div>
                </div>
            </div>
        </div>


        <div class="comment_body">
            <p class=" js-comment-edit-details-toggle"><?php print $comment['comment_body']; ?></p>
            <textarea name="comment_body" style="display: none;"><?php print $comment['comment_body']; ?></textarea>
            <a href="#" class="js-save-comment-btn mw-ui-btn mw-ui-btn-notification  mw-ui-btn-outline mw-ui-btn-small" data-id="<?php print $comment['id'] ?>" style="display: none;"><i class="mw-icon-pen"></i><?php print _e('Save'); ?></a>
        </div>
    </form>

    <?php if (isset($params['show-reply-form'])) { ?>
        <div class="reply-holder">
            <div class="reply-form">
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

                <form id="comment-form-reply-<?php print $comment['id'] ?>" class="js-reply-comment-form">
                    <input type="hidden" name="reply_to_comment_id" value="<?php print $comment['id'] ?>">

                    <textarea placeholder="<?php print _e('Reply to'); ?> <?php print $comment['comment_name']; ?>" name="comment_body"></textarea>
                    <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small pull-right" style="margin-top:6px;"><?php print _e('Send'); ?></button>
                </form>
            </div>

            <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small mw-reply-btn"><i class="mw-icon-reply"></i> <?php print _e('Reply'); ?></button>
        </div>
    <?php } ?>
</div>
