@if(method_exists($contents,'count') && $contents->count() == 0)
    <div class="no-items-found comments">
        <div class="icon-title">
            <i class="mdi mdi-comment"></i> <h5><?php _e("You don't have any comments yet."); ?></h5>
        </div>
    </div>
@endif

@if (isset($contents)  and !empty($contents))
    @foreach ($contents as $i=>$content)

        <div
            class="card mb-2 not-collapsed-border collapsed bg-silver"
            data-bs-toggle="collapse-mw"
            data-bs-target="#comments-<?php print $content['id'] ?>"
            id="comment-<?php print $content['id'] ?>"
            aria-expanded="false"
            aria-controls="collapseExample">
            <div class="card-body">

                <?php
                $content_picture = get_picture($content['rel_id']);
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
                        <h5 class="text-primary text-break-line-2"><?php print content_title($content['rel_id']); ?></h5>
                    </div>

                    <div class="col-12 col-sm text-end text-right"><?php print mw()->format->ago($content['created_at']); ?></div>
                </div>
                <div class="collapse" id="comments-<?php print $content['id'] ?>">
                    <div class="row mt-3">
                        <div class="col-12">
                            <a href="<?php print content_link($content['rel_id']); ?>" class="btn btn-primary btn-sm btn-rounded" target="_blank">View article</a>
                        </div>
                    </div>

                    <hr class="thin"/>

                    <div class="row">
                        <div class="col-md-12">


                            @foreach($content->allComments as $i=>$comment)


                                <?php

                                $image = '';
                                if ($comment['created_by']) {
                                    $image = get_user_by_id($content['created_by']);
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

                                $last_item_param = '';
                                if (!isset($comments[$i + 1])) {
                                    $last_item_param = ' show-reply-form=true ';
                                }
                                ?>


                                @include('comment::admin.comments.comment_item')


                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endforeach
@endif
