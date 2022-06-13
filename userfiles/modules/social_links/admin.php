<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card style-1 mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="<?php print $params['module'] ?>"/>
    </div>

    <div class="card-body pt-3">
        <?php
        $option_group = $params['id'];

        if (isset($params['option-group'])) {
            $option_group = $params['option-group'];
        }

        $facebook_enabled = get_option('facebook_enabled', $option_group) == 'y';
        $twitter_enabled = get_option('twitter_enabled', $option_group) == 'y';
        $googleplus_enabled = get_option('googleplus_enabled', $option_group) == 'y';
        $pinterest_enabled = get_option('pinterest_enabled', $option_group) == 'y';
        $youtube_enabled = get_option('youtube_enabled', $option_group) == 'y';
        $linkedin_enabled = get_option('linkedin_enabled', $option_group) == 'y';
        $github_enabled = get_option('github_enabled', $option_group) == 'y';
        $instagram_enabled = get_option('instagram_enabled', $option_group) == 'y';
        $rss_enabled = get_option('rss_enabled', $option_group) == 'y';
        $soundcloud_enabled = get_option('soundcloud_enabled', $option_group) == 'y';
        $mixcloud_enabled = get_option('mixcloud_enabled', $option_group) == 'y';
        $medium_enabled = get_option('medium_enabled', $option_group) == 'y';
        $discord_enabled = get_option('discord_enabled', $option_group) == 'y';

        $instagram_url = get_option('instagram_url', $option_group);
        $facebook_url = get_option('facebook_url', $option_group);
        $twitter_url = get_option('twitter_url', $option_group);
        $googleplus_url = get_option('googleplus_url', $option_group);
        $pinterest_url = get_option('pinterest_url', $option_group);
        $youtube_url = get_option('youtube_url', $option_group);
        $linkedin_url = get_option('linkedin_url', $option_group);
        $github_url = get_option('github_url', $option_group);
        $soundcloud_url = get_option('soundcloud_url', $option_group);
        $mixcloud_url = get_option('mixcloud_url', $option_group);
        $medium_url = get_option('medium_url', $option_group);
        $discord_url = get_option('discord_url', $option_group);
        ?>

        <style scoped="scoped">
            .module-social-links-settings .active {
            }
        </style>

        <script>
            $(document).ready(function () {
                $('.module-social-links-settings input[type="checkbox"]').each(function () {
                    $(this).parent().parent().find('input[type="text"]').prop('readonly', true);
                });

                $('.module-social-links-settings input[type="checkbox"]:checked').each(function () {
                    $(this).parent().parent().addClass('active');
                    $(this).parent().parent().find('input[type="text"]').prop('readonly', false);
                });

                $('.module-social-links-settings input[type="checkbox"]').on('change', function () {
                    if ($(this).is(':checked')) {
                        $(this).parent().parent().addClass('active');
                        $(this).parent().parent().find('input[type="text"]').prop('readonly', false);
                    } else {
                        $(this).parent().parent().removeClass('active');
                        $(this).parent().parent().find('input[type="text"]').prop('readonly', true);
                    }
                });
            })
        </script>

        <div class="module-live-edit-settings module-social-links-settings">
            <div class="row">
                <div class="col-12 socials-settings">
                    <div class="form-group mb-4">
                        <label class="control-label"><?php _e("Select and type socials links you want to show"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Select the social networks you want to see on your site, blog and store"); ?></small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="mw_option_field custom-control-input" id="facebook_enabled" option-group="<?php print $option_group; ?>" name="facebook_enabled" id="facebook_enabled" value="y" <?php if ($facebook_enabled) print 'checked="checked"'; ?>>
                            <label class="custom-control-label mr-2 d-flex" for="facebook_enabled"><i class="mdi mdi-facebook mdi-20px lh-1_0 mr-2"></i> facebook.com/</label>
                            <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="facebook_url" value="<?php print $facebook_url; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="mw_option_field custom-control-input" name="twitter_enabled" id="twitter_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($twitter_enabled) print 'checked="checked"'; ?>>
                            <label class="custom-control-label mr-2 d-flex" for="twitter_enabled"><i class="mdi mdi-twitter mdi-20px lh-1_0 mr-2"></i> twitter.com/</label>
                            <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="twitter_url" value="<?php print $twitter_url; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="mw_option_field custom-control-input" name="youtube_enabled" id="youtube_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($youtube_enabled) print 'checked="checked"'; ?>>
                            <label class="custom-control-label mr-2 d-flex" for="youtube_enabled"><i class="mdi mdi-youtube mdi-20px lh-1_0 mr-2"></i> youtube.com/</label>
                            <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="youtube_url" value="<?php print $youtube_url; ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="mw_option_field custom-control-input" name="instagram_enabled" id="instagram_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($instagram_enabled) print 'checked="checked"'; ?>>
                            <label class="custom-control-label mr-2 d-flex" for="instagram_enabled"><i class="mdi mdi-instagram mdi-20px lh-1_0 mr-2"></i> instagram.com/</label>
                            <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="instagram_url" value="<?php print $instagram_url; ?>"/>
                        </div>
                    </div>


                    <a href="javascript:;" class="btn btn-outline-primary btn-sm mb-3" data-bs-toggle="collapse" data-bs-target="#more-socials-settings" aria-expanded="true"><?php _e("Show more"); ?></a>

                    <div class="collapse" id="more-socials-settings">

                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="pinterest_enabled" id="pinterest_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($pinterest_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="pinterest_enabled"><i class="mdi mdi-pinterest mdi-20px lh-1_0 mr-2"></i> pinterest.com/</label>
                                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="pinterest_url" value="<?php print $pinterest_url; ?>"/><span></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="linkedin_enabled" id="linkedin_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($linkedin_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="linkedin_enabled"><i class="mdi mdi-linkedin mdi-20px lh-1_0 mr-2"></i> linkedin.com/</label>
                                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="linkedin_url" value="<?php print $linkedin_url; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="github_enabled" id="github_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($github_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="github_enabled"><i class="mdi mdi-github mdi-20px lh-1_0 mr-2"></i> github.com/</label>
                                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="github_url" value="<?php print $github_url; ?>"/>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="soundcloud_enabled" id="soundcloud_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($soundcloud_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="soundcloud_enabled"><i class="mdi mdi-soundcloud mdi-20px lh-1_0 mr-2"></i> soundcloud.com/</label>
                                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="soundcloud_url" value="<?php print $soundcloud_url; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="mixcloud_enabled" id="mixcloud_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($mixcloud_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="mixcloud_enabled"><i class="mdi mdi-mixdcloud mdi-20px lh-1_0 mr-2"></i> mixdcloud.com/</label>
                                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="mixcloud_url" value="<?php print $mixcloud_url; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="medium_enabled" id="medium_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($medium_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="medium_enabled"><i class="mdi mdi-medium mdi-20px lh-1_0 mr-2"></i> medium.com/</label>
                                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="medium_url" value="<?php print $medium_url; ?>"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="rss_enabled" id="rss_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($rss_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="rss_enabled"><i class="mdi mdi-rss mdi-20px lh-1_0 mr-2"></i> RSS</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox d-flex align-items-center">
                                <input type="checkbox" class="mw_option_field custom-control-input" name="discord_enabled" id="discord_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($discord_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="discord_enabled"><i class="mdi mdi-discord mdi-20px lh-1_0 mr-2"></i> discord.gg/</label>
                                <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field form-control" name="discord_url" value="<?php print $discord_url; ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
