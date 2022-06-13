<div
    class="card mb-2 not-collapsed-border collapsed bg-silver"

    id="comment-<?php print $comment['id'] ?>"
    aria-expanded="false"
    aria-controls="collapseExample">
    <span class="mw-bs-card-collpase card-collapse"     data-bs-toggle="collapse"
          data-bs-target="#comments-<?php print $comment['id'] ?>"></span>
    <div class="card-body">
        <script>mw.lib.require('mwui_init');</script>

        <?php
        $image = '';
        if ($comment['created_by']) {
            $image = get_user_by_id($comment['created_by']);
            if (isset($image['thumbnail'])) {
                $image = $image['thumbnail'];
            } else {
                $image = false;
            }

            if (!$comment['comment_email']) {
                $comment['comment_email'] = user_email($comment['created_by']);
            }
            if (!$comment['comment_name']) {
                $comment['comment_name'] = user_name($comment['created_by']);
            }
        }

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

        $content_picture = get_picture($comment['rel_id']);
        ?>

        <div class="row align-items-center">
            <div class="col" style="max-width: 100px;">
                <div class="img-circle-holder img-absolute border-radius-0 border-0">
                    <?php if (isset($content_picture) and $content_picture != ''): ?>
                        <img src="<?php print thumbnail($content_picture, 120, 120); ?>"/>
                    <?php else: ?>
                        <img src="<?php print thumbnail(''); ?>"/>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col text-start text-left">
                <h5 class="text-primary text-break-line-2"><?php print content_title($comment['rel_id']); ?></h5>
            </div>

            <div class="col-12 col-sm text-end text-right"><?php _e(mw()->format->ago($comment['created_at'])); ?></div>
        </div>
        <div class="collapse"  id="comments-<?php print $comment['id'] ?>">
            <div class="row mt-3">
                <div class="col-12">
                    <a href="<?php print content_link($comment['rel_id']); ?>" class="btn btn-primary btn-sm btn-rounded" target="_blank"><?php _e("View article"); ?></a>
                </div>
            </div>

            <hr class="thin"/>

            <div class="row">
                <div class="col-md-12">


                    <div class="row mb-3">
                        <div class="col" style="max-width: 80px;">
                            <div class="img-circle-holder w-60 border-0 border-radius-10">
                                <?php if (isset($image) and $image != ''): ?>
                                    <img src="<?php print thumbnail($image, 120, 120); ?>"/>
                                <?php else: ?>
                                    <img src="<?php print thumbnail(''); ?>"/>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-1">
                                <small class="text-muted"><?php _e("Status"); ?></small>
                            </div>
                            <script>
                                $(document).ready(function () {
                                    $(".js-modify-comment", '#comments-<?php print $comment['id'] ?>').change(function () {
                                        $(this).find('option:selected').trigger('click');
                                    });
                                })
                            </script>
                            <div>
                                <select class="selectpicker js-change-color js-modify-comment d-inline-block" data-style="btn-success btn-sm" data-width="fit" data-title="<?php _e($status); ?>">
                                    <option class="js-comment-approved-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-success"><?php _e('Published'); ?></option>
                                    <option class="js-comment-unpublished-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-warning"><?php _e('Unpublish'); ?></option>
                                    <option class="js-mark-spam-comment-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-secondary"><?php _e('Mark as Spam'); ?></option>
                                    <option class="js-delete-comment-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-danger"><?php _e('Delete'); ?></option>
                                </select>

                                <button class="js-edit-comment-btn btn btn-outline-secondary btn-sm" data-id="<?php print $comment['id'] ?>"><?php _e('Edit'); ?></button>
                                <button class="js-save-comment-btn btn btn-success btn-sm" data-id="<?php print $comment['id'] ?>" style="display: none;"><i class="mw-icon-pen"></i><?php _e('Save'); ?></button>
                            </div>
                        </div>
                    </div>

                    <form id="comment-form-<?php print $comment['id'] ?>">
                        <input type="hidden" name="id" value="<?php print $comment['id'] ?>">
                        <input type="text" name="action" class="comment_state semi_hidden"/>
                        <input type="hidden" name="connected_id" value="<?php print $comment['rel_id'] ?>">

                        <h6>
                            <strong class="js-comment-name-text">
                                <?php if (isset($comment['comment_name'])): ?>
                                    <?php print $comment['comment_name']; ?>
                                <?php else: ?>
                                    <?php _e("Guest"); ?>
                                <?php endif; ?>
                            </strong>

                            <span class="js-comment-name-input" style="display: none;">
                                <input type="text" name="comment_name" class="mw_option_field form-control form-control-sm d-inline-block w-auto" value="<?php print $comment['comment_name']; ?>"/>
                            </span>
                            <small class="text-muted"><?php _e("says"); ?>:</small>
                        </h6>

                    <div class="mb-3">
                        <div class="js-comment-body-text"><?php print $comment['comment_body']; ?></div>
                        <span class="js-comment-body-textarea" style="display: none;">
                            <small class="text-muted"><?php _e("Comment"); ?>:</small>
                            <textarea name="comment_body" class="form-control"><?php print $comment['comment_body']; ?></textarea>
                        </span>
                    </div>

                    <div class="mb-3">
                        <?php if ($comment['comment_email']) { ?>
                            <small class="text-muted"><?php _e("E-mail"); ?>:</small>
                            <span class="js-comment-email-text"><a href="mailto:<?php print $comment['comment_email']; ?>"><?php print $comment['comment_email']; ?></a></span>

                            <span class="js-comment-email-input" style="display: none;">
                                    <input type="text" name="comment_email" class="mw_option_field form-control form-control-sm d-inline-block w-auto" value="<?php print $comment['comment_email']; ?>"/>
                                </span>
                        <?php } ?>

                        <?php if ($comment['comment_website']) { ?>
                            <span class="js-comment-website-text"> | <a href="<?php print mw()->format->prep_url($comment['comment_website']); ?>" target="_blank"><?php _e("Website"); ?></a></span>

                            <span class="js-comment-website-input" style="display: none;">
                                    <input type="text" name="comment_website" class="mw_option_field form-control form-control-sm d-inline-block w-auto" value="<?php print $comment['comment_website']; ?>"/>
                                </span>
                        <?php } ?>
                    </div>
                    </form>

                    <?php if (isset($params['show-reply-form'])) { ?>
                        <?php /*<a href="#reply-comment-id-<?php print $comment['id'] ?>" data-bs-target="#reply-comment-id-<?php print $comment['id'] ?>" class="btn btn-outline-secondary btn-sm icon-left js-show-more"><i class="mdi mdi-comment-account text-primary"></i> Reply</a>*/ ?>

                        <div class="collapse" id="reply-comment-id-<?php print $comment['id'] ?>">
                            <hr class="thin"/>

                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <h5><strong><?php _e("Add a new comment"); ?></strong></h5>
                                </div>
                                <div class="col" style="max-width: 80px;">
                                    <?php
                                    $image = get_user_by_id($comment['created_by']);
                                    if (!isset($image['thumbnail'])) {
                                        $image = '';
                                    } else {
                                        $image = $image['thumbnail'];
                                    }
                                    ?>

                                    <div class="img-circle-holder w-60 border-0 border-radius-10">
                                        <?php if (isset($image) and $image != ''): ?>
                                            <img src="<?php print thumbnail($image, 120, 120); ?>"/>
                                        <?php else: ?>
                                            <img src="<?php print thumbnail('', 120, 120); ?>"/>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-sm mt-3 mt-sm-0">
                                    <form id="comment-form-reply-<?php print $comment['id'] ?>" class="js-reply-comment-form">
                                        <div class="form-group">
                                            <input type="hidden" name="reply_to_comment_id" value="<?php print $comment['id'] ?>">
                                            <textarea placeholder="<?php _e('Reply to'); ?> <?php print $comment['comment_name']; ?>" name="comment_body"></textarea>
                                        </div>
                                        <div class="text-end text-right">
                                            <button class="btn btn-outline-secondary btn-sm" type="submit"><?php _e('Post Comment'); ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

