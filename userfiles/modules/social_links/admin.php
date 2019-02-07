<?php only_admin_access(); ?>

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

$instagram_url = get_option('instagram_url', $option_group);
$facebook_url = get_option('facebook_url', $option_group);
$twitter_url = get_option('twitter_url', $option_group);
$googleplus_url = get_option('googleplus_url', $option_group);
$pinterest_url = get_option('pinterest_url', $option_group);
$youtube_url = get_option('youtube_url', $option_group);
$linkedin_url = get_option('linkedin_url', $option_group);
$github_url = get_option('github_url', $option_group);
?>

    <style scoped="scoped">
        .module-social-links-settings [class*='mw-icon-'],
        .module-social-links-settings [class*='fa-'] {
            font-size: 18px;
            display: inline-block;
            margin: 0;
            width: 14px;
            text-align: center;
        }

        .module-social-links-settings tr.active {
            background: #FFF;
        }

    </style>

    <script>
        $(document).ready(function () {
            $()
            $('.module-social-links-settings input[type="checkbox"]:checked').each(function () {
                $(this).parent().parent().parent().addClass('active');
            });


            $('.module-social-links-settings input[type="checkbox"]').on('change', function () {
                if ($(this).is(':checked')) {
                    $(this).parent().parent().parent().addClass('active');
                } else {
                    $(this).parent().parent().parent().removeClass('active');
                }
            });
        })
    </script>


    <script>mw.lib.require('font_awesome5');</script>

    <div class="module-live-edit-settings module-social-links-settings">
        <div class="mw-ui-field-holder">
            <label class="mw-ui-label">Select and type socials links you want to show</label>
        </div>

        <table class="mw-ui-table mw-full-width mw-ui-table-fixed mw-ui-table-basic">
            <colgroup>
                <col width="30"/>
                <col width="18"/>
                <col width="110"/>
            </colgroup>

            <tr>
                <td>
                    <label class="mw-ui-check"><input type="checkbox" class="mw_option_field" option-group="<?php print $option_group; ?>" name="facebook_enabled" id="facebook_enabled" value="y" <?php if ($facebook_enabled) print 'checked="checked"'; ?>><span></span></label>
                </td>
                <td><i class="fab fa-facebook mw-socials-facebook-color"></i></td>
                <td>
                    <label class="mw-ui-inline-label" for="facebook_enabled">facebook.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="facebook_url" value="<?php print $facebook_url; ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check"><input type="checkbox" class="mw_option_field" name="twitter_enabled" id="twitter_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($twitter_enabled) print 'checked="checked"'; ?>><span></span></label>
                </td>

                <td><i class="fab fa-twitter mw-socials-twitter-color"></i></td>

                <td>
                    <label class="mw-ui-inline-label" for="twitter_enabled">twitter.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="twitter_url" value="<?php print $twitter_url; ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check">
                        <input type="checkbox" class="mw_option_field" name="pinterest_enabled" id="pinterest_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($pinterest_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-pinterest mw-socials-pinterest-color"></i></td>

                <td>
                    <label class="mw-ui-inline-label" for="pinterest_enabled">pinterest.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="pinterest_url" value="<?php print $pinterest_url; ?>"/><span></span>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check">
                        <input type="checkbox" class="mw_option_field" name="youtube_enabled" id="youtube_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($youtube_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-youtube mw-socials-youtube-color"></i></td>

                <td>
                    <label class="mw-ui-inline-label" for="youtube_enabled">youtube.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="youtube_url" value="<?php print $youtube_url; ?>"/>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check">
                        <input type="checkbox" class="mw_option_field" name="googleplus_enabled" id="googleplus_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($googleplus_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-youtube mw-socials-youtube-color"></i></td>

                <td>
                    <label class="mw-ui-inline-label" for="googleplus_enabled">plus.google.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="googleplus_url" value="<?php print $googleplus_url; ?>"/>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check">
                        <input type="checkbox" class="mw_option_field" name="linkedin_enabled" id="linkedin_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($linkedin_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-linkedin  mw-socials-linkedin-color"></i></td>

                <td>
                    <label class="mw-ui-inline-label" for="linkedin_enabled">linkedin.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="linkedin_url" value="<?php print $linkedin_url; ?>"/>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check">
                        <input type="checkbox" class="mw_option_field" name="github_enabled" id="github_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($github_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-github  mw-socials-github-color"></i></td>

                <td>
                    <label class="mw-ui-inline-label" for="github_enabled">github.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="github_url" value="<?php print $github_url; ?>"/>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check">
                        <input type="checkbox" class="mw_option_field" name="instagram_enabled" id="instagram_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($instagram_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-instagram  mw-socials-instagram-color"></i></td>

                <td>
                    <label class="mw-ui-inline-label" for="instagram_enabled">instagram.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="instagram_url" value="<?php print $instagram_url; ?>"/>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check">
                        <input type="checkbox" class="mw_option_field" name="rss_enabled" id="rss_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($rss_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><span class="mw-icon-social-rss"></span></td>

                <td><label class="mw-ui-inline-label" for="rss_enabled">RSS</label></td>
                <td></td>
            </tr>
        </table>
    </div>

<?php if (isset($params['live_edit_sidebar'])): ?>

<?php else: ?>

<?php endif; ?>