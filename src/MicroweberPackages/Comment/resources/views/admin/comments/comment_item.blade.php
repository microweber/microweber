
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
                                <small class="text-muted">Status</small>
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
                                    Guest
                                <?php endif; ?>
                            </strong>

                            <span class="js-comment-name-input" style="display: none;">
                                <input type="text" name="comment_name" class="mw_option_field form-control form-control-sm d-inline-block w-auto" value="<?php print $comment['comment_name']; ?>"/>
                            </span>
                            <small class="text-muted">says:</small>
                        </h6>

                    <div class="mb-3">
                        <div class="js-comment-body-text">{{ $comment['comment_body'] }}</div>
                        <span class="js-comment-body-textarea" style="display: none;">
                            <small class="text-muted">Comment:</small>
                            <textarea name="comment_body" class="form-control">{{ $comment['comment_body'] }}§</textarea>
                        </span>
                    </div>

                    <div class="mb-3">
                        <?php if ($comment['comment_email']) { ?>
                            <small class="text-muted">E-mail:</small>
                            <span class="js-comment-email-text"><a href="mailto:<?php print $comment['comment_email']; ?>"><?php print $comment['comment_email']; ?></a></span>

                            <span class="js-comment-email-input" style="display: none;">
                                    <input type="text" name="comment_email" class="mw_option_field form-control form-control-sm d-inline-block w-auto" value="<?php print $comment['comment_email']; ?>"/>
                                </span>
                        <?php } ?>

                        <?php if ($comment['comment_website']) { ?>
                            <span class="js-comment-website-text"> | <a href="<?php print mw()->format->prep_url($comment['comment_website']); ?>" target="_blank">Website</a></span>

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

