<?php must_have_access(); ?>

<div>
    <style  scoped="scoped">
        #other-settings {
            position: relative;
            overflow: hidden;
            padding-top: 10px;
        }

        .avatartype {
            display: inline-block;
            width: 32px;
            height: 32px;
            position: absolute;
            top: -10px;
            left: 0;
        }

        .avatars-holder .mw-ui-check input + span + span {
            position: relative;
            padding-left: 40px;
        }

        .avatartype-mysteryman {
            background: url(<?php print mw_includes_url(); ?>img/avatars.jpg) no-repeat;
        }

        .avatartype-randomcolor {
            background: #9F41AA;
            transition: background-color 0.2s;
            -moz-transition: background-color 0.2s;
            -webkit-transition: background-color 0.2s;
            -o-transition: background-color 0.2;
        }

        .avatartype-mwuser {
            background: url(<?php print mw_includes_url();?>img/avatars.jpg) no-repeat 0 -70px;
        }

        .avatartype-upload {
            width: 30px;
            height: 30px;
            border: 1px solid #CACACA;
            background-repeat: no-repeat;
            background-size: cover;
            background-image: url(<?php print get_option('avatartype_custom', 'comments'); ?>);
        }

        .avatars-holder {
            padding-top: 10px;
        }

        .comments-main-settings {
            padding-bottom: 10px;
        }

        .comments-main-settings .mw-ui-check + div {
            padding-top: 15px;
        }

        #receive_email_holder {
            padding-top: 5px;
        }

        #avatar_uploader  .mw-uploader-input{
            width: calc(100% - 30px);
            height: 30px;
            left: 30px;
            top: -6px;
            appearance: none;
        }
        #avatar_uploader {
            height: 30px;
            overflow: hidden;
            position: relative;
            overflow: hidden;
        }

    </style>

    <script>mw.require("files.js");</script>

    <script type="text/javascript">
        mw.require('options.js', true);
        mw.require('<?php print $config['url_to_module'] ?>style.css');
        mw.require('color.js', true);
        mw.require('files.js');
    </script>

    <script type="text/javascript">


        $(document).ready(function () {
            var uploader = mw.files.uploader({
                filetypes: "images",
                element: mw.$("#avatar_uploader")
            });
            mw.options.form('.<?php print $config['module_class'] ?>', function () {
                mw.notification.success("<?php _ejs("All changes are saved"); ?>.");
            });

            mw.$("[name='enable_comments']").commuter(function () {
                mw.$("#other-settings").removeClass("deactivated");
            }, function () {
                mw.$("#other-settings").addClass("deactivated");
            });

            mw.$("[name='email_on_new_comment']").commuter(function () {
                mw.$("#receive_email_holder").removeClass("deactivated");
            }, function () {
                mw.$("#receive_email_holder").addClass("deactivated");
            });

            mw.$("[name='avatar_enabled']").commuter(function () {
                mw.$(".avatars-holder").removeClass("deactivated");
            }, function () {
                mw.$(".avatars-holder").addClass("deactivated");
            });

            mw.$(".avatartype-randomcolor").parent().parent().hover(function () {
                mw.$(".avatartype-randomcolor").css("backgroundColor", mw.color.random());
            });

            $(uploader).on("FileUploaded", function (e, a) {

                mw.$(".avatartype-upload").css("backgroundImage", "url(" + a.src + ")");
                mw.$("[name='avatartype_custom']").val(a.src).trigger("change");
            });
        });
    </script>

    <div id="module-settings">
        <div class="<?php print $config['module_class'] ?>">
            <h4><?php _e("Settings"); ?><br/>
                <small class="text-muted"><?php _e("Define comments settings"); ?></small>
            </h4>

            <div class="form-group">
                <?php $are_enabled = get_option('enable_comments', 'comments') == 'y'; ?>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="mw_option_field custom-control-input" id="enable_comments" name="enable_comments" parent-reload="true" value="y" option-group="comments" <?php if ($are_enabled): ?>checked<?php endif; ?> />
                    <label class="custom-control-label" for="enable_comments"><?php _e("Allow people to post comments"); ?></label>
                </div>
            </div>

            <div id="other-settings" class="<?php if ($are_enabled == false): ?>deactivated<?php endif; ?>">
                <module type="comments/privacy_settings"/>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="comments-main-settings">
                            <div class="form-group">
                                <label class="mw-ui-check">
                                    <input type="checkbox" name="user_must_be_logged" value="y" parent-reload="true" class="mw_option_field" option-group="comments" <?php if (get_option('user_must_be_logged', 'comments') == 'y'): ?>checked<?php endif; ?> />
                                    <span></span><span><?php _e("Users must be registered and logged in to comment"); ?></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="mw-ui-check">
                                    <input type="checkbox" parent-reload="true" name="require_moderation" data-reload="comments/comments_for_post" value="y" class="mw_option_field" option-group="comments" <?php if (get_option('require_moderation', 'comments') == 'y'): ?>checked<?php endif; ?>/>
                                    <span></span><span><?php _e("New comments require moderation"); ?></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="mw-ui-check">
                                    <input type="checkbox" parent-reload="true" name="disable_captcha" data-reload="comments/comments_for_post" value="y" class="mw_option_field" option-group="comments" <?php if (get_option('disable_captcha', 'comments') == 'y'): ?>checked<?php endif; ?> />
                                    <span></span><span><?php _e("Disable Captcha?"); ?></span>
                                </label>
                            </div>


                            <?php

                            /*



                            <div class="form-group">
                                <label class="mw-ui-check">
                                    <input type="checkbox" parent-reload="true" name="disable_comments_reply_notifications" data-reload="comments/comments_for_post" value="y" class="mw_option_field" option-group="comments" <?php if (get_option('disable_comments_reply_notifications', 'comments') == 'y'): ?>checked<?php endif; ?>/>
                                    <span></span><span><?php _e("Disable comments reply email notifications"); ?></span> </label>
                            </div>



                             * */

                            ?>




                            <hr class="thin">

                            <div class="form-group">
                                <label class="mw-ui-check">
                                    <input type="checkbox" name="set_paging" parent-reload="true" value="y" class="mw_option_field" option-group="comments" <?php if (get_option('set_paging', 'comments') == 'y'): ?>checked<?php endif; ?>/>
                                    <span></span><span><?php _e("Enable paging for comments"); ?></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <div option-group="comments" name="comments_per_page" parent-reload="true">
                                    <label class="control-label"><?php _e("Comments per page"); ?></label>
                                    <select name="paging" parent-reload="true" option-group="comments" parent-reload="true" class="mw_option_field selectpicker" data-width="100%" data-size="5">
                                        <?php
                                        $per_page = get_option('paging', 'comments');
                                        $found = false;
                                        for ($i = 5; $i < 40; $i += 5) {
                                            if ($i == $per_page) {
                                                $found = true;
                                                print '<option selected="selected" value="' . $i . '">' . $i . '</option>';
                                            } else {
                                                print '<option value="' . $i . '">' . $i . '</option>';
                                            }
                                        }
                                        if ($found == false) {
                                            print '<option selected="selected" value="' . $per_page . '">' . $per_page . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>




                        <hr class="thin">



                        <?php

                        /*


                        <div class="row d-none">
                            <div class="col-md-6">
                                <div class="form-group email-on-new-comment-setting">
                                    <label class="control-label d-block"><?php _e("Send email me on"); ?></label>

                                    <div class="email-on-new-comment-holder">
                                        <label class="mw-ui-check">
                                            <?php $email_enabled = get_option('email_on_new_comment', 'comments') == 'y'; ?>
                                            <input type="checkbox" name="email_on_new_comment" value="y" parent-reload="true" class="mw_option_field" option-group="comments"<?php if ($email_enabled): ?>   checked="checked"  <?php endif; ?>/>
                                            <span></span><span><?php _e("New comment"); ?></span>
                                        </label>

                                        <div class="<?php if ($email_enabled == false): ?>deactivated<?php endif; ?> mb-3" id="receive_email_holder">
                                            <input type="text" name="email_on_new_comment_value" option-group="comments" placeholder="<?php _e("Type email here"); ?>" parent-reload="true" class="mw_option_field form-control" value="<?php print get_option('email_on_new_comment_value', 'comments'); ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group email-on-new-comment-setting email-on-reply-comment-setting">
                                    <label class="control-label d-block"><?php _e("Send email to user on"); ?></label>

                                    <div class="email-on-new-comment-holder">
                                        <label class="mw-ui-check">
                                            <?php $email_enabled = get_option('email_user_on_new_comment_reply', 'comments') == 'y'; ?>
                                            <input type="checkbox" name="email_user_on_new_comment_reply" value="y" parent-reload="true" class="mw_option_field" option-group="comments"<?php if ($email_enabled): ?>   checked="checked"  <?php endif; ?>/>
                                            <span></span><span><?php _e("New comment reply"); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="thin">

                        <div class="form-group">
                            <label class="control-label"><?php _e("Select new comment reply email template"); ?></label>

                            <module type="admin/mail_templates/select_template" option_group="comments" mail_template_type="new_comment_reply"/>
                        </div>


                        */

                        ?>


                    </div>

                    <div class="col-lg-6">
                        <module type="comments/settings_for_engine" id="mw-comments-engine-settings"/>

                        <div class="form-group">
                            <label class="control-label"><?php _e("Avatar Display"); ?></label>

                            <div class="form-group">
                                <label class="mw-ui-check">
                                    <?php $avatar_enabled = get_option('avatar_enabled', 'comments') == 'y'; ?>
                                    <input type="checkbox" name="avatar_enabled" value="y" parent-reload="true" class="mw_option_field" option-group="comments" <?php if ($avatar_enabled): ?>checked<?php endif; ?>/>
                                    <span></span><span><?php _e("Show Avatars"); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label"><?php _e("Default avatar style"); ?></label>

                            <div class="avatars-holder <?php if (!$avatar_enabled) { ?>deactivated<?php } ?>">
                                <div class="form-group">
                                    <label class="mw-ui-check">
                                        <input type="radio" name="avatar_style" value="1" class="mw_option_field" parent-reload="true" option-group="comments" <?php if (get_option('avatar_style', 'comments') == '1'): ?>checked<?php endif; ?>/>
                                        <span></span><span><i class="avatartype avatartype-mysteryman"></i><?php _e("Super User"); ?></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="mw-ui-check">
                                        <input type="radio" name="avatar_style" value="2" class="mw_option_field" parent-reload="true" option-group="comments" <?php if (get_option('avatar_style', 'comments') == '2'): ?>checked<?php endif; ?>/>
                                        <span></span><span><i class="avatartype avatartype-randomcolor"></i><?php _e("Random Color"); ?></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="mw-ui-check">
                                        <input type="radio" name="avatar_style" value="3" class="mw_option_field" parent-reload="true" option-group="comments" <?php if (get_option('avatar_style', 'comments') == '3'): ?>checked<?php endif; ?>/>
                                        <span></span><span><i class="avatartype mw-icon-mw" style="font-size: 37px;color:#C4C4C4"></i><?php _e("MW User Picture"); ?></span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="mw-ui-check relative" id="avatar_uploader">
                                        <input type="radio" name="avatar_style" value="4" class="mw_option_field" parent-reload="true" option-group="comments" <?php if (get_option('avatar_style', 'comments') == '4'): ?>checked<?php endif; ?>/>
                                        <span></span><span><input type="hidden" parent-reload="true" name="avatartype_custom" class="mw_option_field" option-group="comments" value="<?php print get_option('avatartype_custom', 'comments'); ?>"/><i class="avatartype avatartype-upload"></i> <?php _e("Upload Picture"); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
