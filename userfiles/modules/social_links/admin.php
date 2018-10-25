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
$instagram_enabled = get_option('instagram_enabled', $option_group) == 'y';
$rss_enabled = get_option('rss_enabled', $option_group) == 'y';

$instagram_url = get_option('instagram_url', $option_group);
$facebook_url = get_option('facebook_url', $option_group);
$twitter_url = get_option('twitter_url', $option_group);
$googleplus_url = get_option('googleplus_url', $option_group);
$pinterest_url = get_option('pinterest_url', $option_group);
$youtube_url = get_option('youtube_url', $option_group);
$linkedin_url = get_option('linkedin_url', $option_group);
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
    </style>

    <script>mw.lib.require('font_awesome5');</script>

    <div class="module-live-edit-settings module-social-links-settings">
        <table class="mw-ui-table mw-full-width mw-ui-table-fixed mw-ui-table-basic">
            <colgroup>
                <col width="30"/>
                <col width="18"/>
                <col width="110"/>
            </colgroup>

            <tr>
                <td>
                    <label class="mw-ui-check"><input type="checkbox" class="mw_option_field" option-group="<?php print $option_group; ?>" name="facebook_enabled" value="y" <?php if ($facebook_enabled) print 'checked="checked"'; ?>><span></span></label>
                </td>
                <td><i class="fab fa-facebook"></i></td>
                <td>
                    <label class="mw-ui-inline-label">facebook.com/</label>
                </td>
                <td>
                    <div class="mw-ui-field-holder">
                        <input type="text" option-group="<?php print $option_group; ?>" class="mw_option_field mw-ui-field mw-ui-field-medium" name="facebook_url" value="<?php print $facebook_url; ?>">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <label class="mw-ui-check"><input type="checkbox" class="mw_option_field" name="twitter_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($twitter_enabled) print 'checked="checked"'; ?>><span></span></label>
                </td>

                <td><i class="fab fa-twitter"></i></td>

                <td>
                    <label class="mw-ui-inline-label">twitter.com/</label>
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
                        <input type="checkbox" class="mw_option_field" name="pinterest_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($pinterest_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-pinterest"></i></td>

                <td>
                    <label class="mw-ui-inline-label">pinterest.com/</label>
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
                        <input type="checkbox" class="mw_option_field" name="youtube_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($youtube_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-youtube"></i></td>

                <td>
                    <label class="mw-ui-inline-label">youtube.com/</label>
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
                        <input type="checkbox" class="mw_option_field" name="linkedin_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($linkedin_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-linkedin"></i></td>

                <td>
                    <label class="mw-ui-inline-label">linkedin.com/</label>
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
                        <input type="checkbox" class="mw_option_field" name="instagram_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($instagram_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><i class="fab fa-instagram"></i></td>

                <td>
                    <label class="mw-ui-inline-label">instagram.com/</label>
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
                        <input type="checkbox" class="mw_option_field" name="rss_enabled" option-group="<?php print $option_group; ?>" value="y" <?php if ($rss_enabled) print 'checked="checked"'; ?>><span></span>
                    </label>
                </td>

                <td><span class="mw-icon-social-rss"></span></td>

                <td><label class="mw-ui-inline-label">RSS</label></td>
                <td></td>
            </tr>
        </table>
    </div>

<?php if (isset($params['live_edit_sidebar'])): ?>

<?php else: ?>

<?php endif; ?>