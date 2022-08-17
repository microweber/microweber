<?php

/*

type: layout

name: Bootstrap

description: Bootstrap comments template

*/

//$template_file = false;


?>


<?php $rand = rand(); ?>
<script>
    mw.moduleCSS("<?php print $config['url_to_module']; ?>comments.css", true);
    mw.lib.require('bootstrap3ns');
</script>

<div class="bootstrap3ns mwcommentsmodule">


    <?php if (is_array($comments)): ?>

        <h4><?php print $formTitle; ?></h4>

        <div class="comments-template-stylish comments" id="comments-list-<?php print $data['id'] ?>">
            <?php foreach ($comments as $comment) : ?>
                <?php
                $required_moderation = get_option('require_moderation', 'comments') == 'y';
                if (!$required_moderation or $comment['is_moderated'] == 1 or (!(mw()->user_manager->session_all() == false) and $comment['session_id'] == mw()->user_manager->session_id())) {
                    ?>
                    <div class="clearfix comment" id="comment-<?php print $comment['id'] ?>">
                        <div class="mw-ui-row">
                            <?php
                            $avatars_enabled = get_option('avatar_enabled', 'comments') == 'y';
                            $comment_author = get_user_by_id($comment['created_by']);
                            $my_comment = false;
                            if ($cur_user != false and $comment['created_by'] == $cur_user) {
                                $my_comment = true;
                            }

                            ?>

                            <?php if ($avatars_enabled) { ?>
                                <div class="mw-ui-col comment-image-holder">
                                    <div class="mw-ui-col-container">
                                        <?php if ($avatarStyle = 2): ?>
                                            <span class="comment-image random-color"> <span style="background-color: <?php print  $avatarPath; ?>"> </span> </span>
                                        <?php else: ?>
                                            <img src="<?php print $avatarPath; ?>" width="60" height="60" class="comment-image" alt="<?php print addslashes($comment['comment_name']) ?>"/>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="mw-ui-col">
                                <?php event_trigger('module.comments.item.before', $comment); ?>
                                <div class="mw-ui-col-container">
                                    <div class="comment-content">
                                        <div class="comment-author">
                                            <?php if (isset($comment['comment_website'])){ ?>
                                            <a href="<?php print mw('format')->prep_url($comment['comment_website']); ?>">
                                                <?php } ?>
                                                <?php print $comment['comment_name'] ?>
                                                <?php if (isset($comment['comment_website'])){ ?>
                                            </a>
                                        <?php } ?>
                                            <?php if (isset($comment['updated_at'])): ?>
                                                &nbsp;
                                                <small class="muted"> <?php print $comment['updated_at']; ?> </small>
                                            <?php endif; ?>
                                            <?php event_trigger('module.comments.item.info', $comment); ?>
                                        </div>
                                        <div class="comment-body">
                                            <?php if ($required_moderation != false and $comment['is_moderated'] == 0): ?>
                                                <em class="comment-require-moderation">
                                                    <?php _e("Your comment requires moderation"); ?>
                                                </em><br/>
                                            <?php endif; ?>
                                            <?php print nl2br($comment['comment_body'], 1); ?>
                                            <?php if ($my_comment == true): ?>
                                            <?php endif; ?>
                                            <?php event_trigger('module.comments.item.body', $comment); ?>
                                        </div>
                                        <?php event_trigger('module.comments.item.body.after', $comment); ?>
                                    </div>
                                </div>
                                <?php event_trigger('module.comments.item.after', $comment); ?>
                            </div>
                        </div>
                    </div>
                <?php } endforeach; ?>
            <?php if ($paging != false and intval($paging) > 1 and isset($paging_param)): ?>
                <?php print paging("num={$paging}&paging_param={$paging_param}") ?>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <h4>
            <?php _e("No comments"); ?>
        </h4>
    <?php endif; ?>

    <?php if ($are_disabled == false) : ?>
        <hr>
        <?php if (!$login_required or $cur_user != false): ?>
            <div id="comments-<?php print $params['id'] ?>">
                <?php event_trigger('module.comments.form.before', $data); ?>
                <form autocomplete="on" id="comments-form-<?php print $data['id'] ?>">
                    <?php event_trigger('module.comments.form.start', $data); ?>
                    <input type="hidden" name="rel_id" value="<?php print $data['rel_id'] ?>">
                    <?php print csrf_form(); ?>
                    <input type="hidden" name="rel_type" value="<?php print $data['rel_type'] ?>">
                    <input type="hidden" name="module_id" value="<?php print $params['id'] ?>">
                    <?php if ($form_title != false): ?>
                        <input type="hidden" name="comment_subject" value="<?php print $form_title ?>">
                    <?php endif; ?>

                    <h4>
                        <?php _e("Leave a comment"); ?>
                    </h4>

                    <?php if ($cur_user == false) : ?>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php _e("Your name"); ?>" required type="text" name="comment_name">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php _e("Website"); ?>" type="text" name="comment_website">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <input class="form-control" placeholder="<?php _e("Your email"); ?>" required type="email" name="comment_email">
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="comments-user-profile mb-3" style="margin-bottom: 5px;">
                            <?php _e("You are commenting as"); ?>:
                            <?php if (isset($cur_user_data['thumbnail']) and trim($cur_user_data['thumbnail']) != ''): ?>
                                <span class="mw-user-thumb mw-user-thumb-small"> <img style="vertical-align:middle" src="<?php print $cur_user_data['thumbnail'] ?>" height="24" width="24"/> </span>
                            <?php endif; ?>
                            <span class="comments-user-profile-username"> <?php print user_name($cur_user_data['id']); ?> </span>
                            <small><a href="<?php print logout_url() ?>">(<?php _e("Logout"); ?>)</a></small>
                        </div>
                    <?php endif; ?>
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <textarea required placeholder="<?php _e("Comment"); ?>" name="comment_body" class="form-control" style="height: 150px"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if (!$disable_captcha) { ?>
                            <div class="col-xs-12 col-md-8">
                                <module type="captcha"/>
                            </div>
                        <?php } ?>
                        <?php event_trigger('module.comments.form.end', $data); ?>
                        <div class="col-xs-12 col-md-4">
                            <button type="submit" class="mw-template-btn btn-primary btn continue-shop-button pull-right mt-3"><?php _e("Add comment"); ?></button>
                        </div>
                    </div>
                </form>
                <?php event_trigger('module.comments.form.after', $data); ?>
            </div>
        <?php else : ?>
            <div class="alert">
                <?php _e("You have to"); ?>
                <a href='<?php print login_url(); ?>' class="comments-login-link">
                    <?php _e("log in"); ?>
                </a>
                <?php _e("or"); ?>
                <a class="comments-register-link" href='<?php print register_url(); ?>'>
                    <?php _e("register"); ?>
                </a>
                <?php _e("to post a comment"); ?>
                .
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
