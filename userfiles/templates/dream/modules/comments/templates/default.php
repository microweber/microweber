<?php

/*

type: layout

name: Default

description: Default comments template

*/

?>


<?php $rand = rand(); ?>

<?php if (is_array($comments)): ?>
    <div class="row">
        <div class="comments" id="comments-list-<?php print $data['id'] ?>">
            <ul>
                <?php foreach ($comments as $comment) : ?>
                    <?php
                    $required_moderation = get_option('require_moderation', 'comments') == 'y';
                    if (!$required_moderation or $comment['is_moderated'] == 1 or (!(mw()->user_manager->session_all() == false) and $comment['session_id'] == mw()->user_manager->session_id())) {
                        ?>

                        <li id="comment-<?php print $comment['id'] ?>">
                            <div class="comment">
                                <?php
                                $avatars_enabled = get_option('avatar_enabled', 'comments') == 'y';
                                $comment_author = get_user_by_id($comment['created_by']);
                                $my_comment = false;
                                if ($cur_user != false and $comment['created_by'] == $cur_user) {
                                    $my_comment = true;
                                }
                                ?>

                                <?php if ($avatars_enabled) { ?>
                                    <div class="comment__image">
                                        <?php if ($avatarStyle = 2): ?>
                                            <span class="comment-image random-color"> <span style="background-color: <?php print  $avatarPath; ?>"> </span> </span>
                                        <?php else: ?>
                                            <img src="<?php print $avatarPath; ?>" width="60" height="60" class="comment-image" alt="<?php print addslashes($comment['comment_name']) ?>"/>
                                        <?php endif; ?>
                                    </div>
                                <?php } ?>
                                <?php event_trigger('module.comments.item.before', $comment); ?>

                                <div class="comment__text">
                                    <h5>
                                        <?php if (isset($comment['comment_website'])){ ?>
                                        <a href="<?php print mw('format')->prep_url($comment['comment_website']); ?>">
                                            <?php } ?>
                                            <?php print $comment['comment_name'] ?>
                                            <?php if (isset($comment['comment_website'])){ ?>
                                        </a>
                                    <?php } ?>
                                    </h5>

                                    <?php if (isset($comment['updated_at'])): ?>
                                        <span><em><?php print date('M d, Y', strtotime($comment['updated_at'])); ?></em></span>
                                    <?php endif; ?>
                                    <?php event_trigger('module.comments.item.info', $comment); ?>

                                    <?php if ($required_moderation != false and $comment['is_moderated'] == 0): ?>
                                        <em class="comment-require-moderation">
                                            <?php _e("Your comment requires moderation"); ?>
                                        </em><br/>
                                    <?php endif; ?>
                                    <p><?php print nl2br($comment['comment_body'], 1); ?></p>
                                    <?php if ($my_comment == true): ?>
                                    <?php endif; ?>
                                    <?php event_trigger('module.comments.item.body', $comment); ?>
                                </div>
                                <?php event_trigger('module.comments.item.body.after', $comment); ?>
                                <hr>
                            </div>
                        </li>
                    <?php } ?>
                <?php endforeach; ?>
            </ul>
            <?php if ($paging != false and intval($paging) > 1 and isset($paging_param)): ?>
                <?php print paging("num={$paging}&paging_param={$paging_param}") ?>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <h4>
        <?php _e("No comments"); ?>
    </h4>
<?php endif; ?>

<?php if ($are_disabled == false) : ?>
    <?php if (!$login_required or $cur_user != false): ?>
        <div id="comments-<?php print $params['id'] ?>">
            <?php event_trigger('module.comments.form.before', $data); ?>
            <form autocomplete="on" id="comments-form-<?php print $data['id'] ?>" class="comment__form form--square">
                <?php event_trigger('module.comments.form.start', $data); ?>
                <input type="hidden" name="rel_id" value="<?php print $data['rel_id'] ?>">
                <?php print csrf_form(); ?>
                <input type="hidden" name="rel" value="<?php print $data['rel_type'] ?>">
                <input type="hidden" name="module_id" value="<?php print $params['id'] ?>">
                <?php if ($form_title != false): ?>
                    <input type="hidden" name="comment_subject" value="<?php print $form_title ?>">
                <?php endif; ?>

                <h6><?php _e("Leave a comment"); ?></h6>

                <?php if ($cur_user == false) : ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <input placeholder="<?php _e("Your name"); ?>" required type="text" name="comment_name">
                        </div>
                        <div class="col-sm-4">
                            <input placeholder="<?php _e("Website"); ?>" type="text" name="comment_website">
                        </div>
                        <div class="col-sm-4">
                            <input placeholder="<?php _e("Your email"); ?>" required type="email" name="comment_email">
                        </div>
                    </div>
                <?php else: ?>
                    <div class="comments-user-profile col-xs-12" style="margin-bottom: 5px;">
                        <?php _e("You are commenting as"); ?>:
                        <?php if (isset($cur_user_data['thumbnail']) and trim($cur_user_data['thumbnail']) != ''): ?>
                            <span class="mw-user-thumb mw-user-thumb-small"> <img style="vertical-align:middle" src="<?php print $cur_user_data['thumbnail'] ?>" height="24" width="24"/> </span>
                        <?php endif; ?>
                        <span class="comments-user-profile-username"> <?php print user_name($cur_user_data['id']); ?> </span>
                        <small><a href="<?php print api_link('logout') ?>">(<?php _e("Logout"); ?>)</a></small>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-xs-12">
                        <textarea required placeholder="<?php _e("Comment"); ?>" name="comment_body" style="height: 150px"></textarea>
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
                        <button type="submit" class="btn btn--primary"><?php _e("Add comment"); ?></button>
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
