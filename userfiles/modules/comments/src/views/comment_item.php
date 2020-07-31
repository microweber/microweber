
<div class="card mb-2 not-collapsed-border collapsed bg-silver" data-toggle="collapse" data-target="#comments-<?php print $comment['id'] ?>" id="comment-<?php print $comment['id'] ?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body">
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
        ?>

        <div class="row align-items-center">
            <div class="col" style="max-width: 100px;">
                <div class="img-circle-holder img-absolute border-radius-0 border-0">
                    <?php if (isset($image) and $image != ''): ?>
                        <img src="<?php print thumbnail($image, 120, 120); ?>"/>
                    <?php else: ?>
                        <img src="<?php print thumbnail(''); ?>"/>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col text-left">
                <h5 class="text-primary text-break-line-2"><?php print content_title($comment['rel_id']); ?></h5>
            </div>

            <div class="col-12 col-sm text-right"><?php print mw()->format->ago($comment['created_at']); ?></div>
        </div>
        <div class="collapse" id="comments-<?php print $comment['id'] ?>">
            <div class="row mt-3">
                <div class="col-12">
                    <a href="<?php print content_link($comment['rel_id']); ?>" class="btn btn-primary btn-sm btn-rounded" target="_blank">View article</a>
                </div>
            </div>

            <hr class="thin"/>

            <div class="row">
                <div class="col-md-12">


                    <div class="row mb-3">
                        <div class="col" style="max-width: 80px;">
                            <div class="img-circle-holder w-60 border-0 border-radius-10">
                                <img src="https://d1qb2nb5cznatu.cloudfront.net/users/40837-medium_jpg?1405468137"/>
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-1">
                                <small class="text-muted">Status</small>
                            </div>
                            <div>
                                <select class="selectpicker js-change-color d-inline-block" data-style="btn-success btn-sm" data-width="fit" data-title="<?php _e($status); ?>">
                                    <option class="js-comment-approved-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-success"><?php print _e('Published'); ?></option>
                                    <option class="js-comment-unpublished-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-warning"><?php print _e('Unpublish'); ?></option>
                                    <option class="js-mark-spam-comment-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-secondary"><?php print _e('Mark as Spam'); ?></option>
                                    <option class="js-delete-comment-btn" data-id="<?php print $comment['id'] ?>" data-change-color="btn-danger"><?php print _e('Delete'); ?></option>
                                </select>

                                <a href="dashboard.html" class="btn btn-outline-secondary btn-sm">Edit</a>
                            </div>
                        </div>
                    </div>

                    <form id="comment-form-<?php print $comment['id'] ?>" style="display: none">
                        <input type="hidden" name="id" value="<?php print $comment['id'] ?>">
                        <input type="text" name="action" class="comment_state semi_hidden"/>
                        <input type="hidden" name="connected_id" value="<?php print $comment['rel_id'] ?>">
                        <h6>
                            <input type="text" name="comment_name" class="mw_option_field form-control" value="<?php print $comment['comment_name']; ?>"/>
                            <small class="text-muted">says:</small>

                            <?php if ($comment['comment_email']) { ?>
                                <input type="text" name="comment_email" class="mw_option_field form-control" value="<?php print $comment['comment_email']; ?>"/>
                            <?php } ?>

                            <?php if ($comment['comment_website']) { ?>
                                <input type="text" name="comment_website" class="mw_option_field form-control" value="<?php print $comment['comment_website']; ?>"/>
                            <?php } ?>
                        </h6>
                        <div>
                            <textarea name="comment_body" class="form-control"><?php print $comment['comment_body']; ?></textarea>
                            <a href="#" class="js-save-comment-btn mw-ui-btn mw-ui-btn-notification  mw-ui-btn-outline mw-ui-btn-small" data-id="<?php print $comment['id'] ?>" style="display: none;"><i class="mw-icon-pen"></i><?php print _e('Save'); ?></a>
                        </div>
                    </form>

                    <h6>
                        <strong><?php print $comment['comment_name']; ?></strong>
                        <small class="text-muted">says:</small>
                    </h6>

                    <?php if ($comment['comment_email']) { ?>
                        <span><a href="mailto:<?php print $comment['comment_email']; ?>"><?php print $comment['comment_email']; ?></a></span>
                    <?php } ?>

                    <?php if ($comment['comment_website']) { ?>
                        <span> | <a href="<?php print mw()->format->prep_url($comment['comment_website']); ?>" target="_blank">Website</a></span>
                    <?php } ?>

                    <div class="mb-3">
                        <?php print $comment['comment_body']; ?>
                    </div>

                    <?php if (isset($params['show-reply-form'])) { ?>
                        <a href="#reply-comment-id-<?php print $comment['id'] ?>" data-target="#reply-comment-id-<?php print $comment['id'] ?>" class="btn btn-outline-secondary btn-sm icon-left js-show-more"><i class="mdi mdi-comment-account text-primary"></i> Reply</a>

                        <div class="collapse" id="reply-comment-id-<?php print $comment['id'] ?>">
                            <hr class="thin"/>

                            <div class="row mb-3">
                                <div class="col-12 mb-3">
                                    <h5><strong>Add a new comment</strong></h5>
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
                                            <textarea placeholder="<?php print _e('Reply to'); ?> <?php print $comment['comment_name']; ?>" name="comment_body"></textarea>
                                        </div>
                                        <div class="text-right">
                                            <button class="btn btn-outline-secondary btn-sm" type="submit"><?php print _e('Post Comment'); ?></button>
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

