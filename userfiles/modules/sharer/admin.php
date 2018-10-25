<?php only_admin_access(); ?>

<?php
$facebook_enabled = get_option('facebook_enabled', $params['id']) == 'y';
$twitter_enabled = get_option('twitter_enabled', $params['id']) == 'y';
$googleplus_enabled = get_option('googleplus_enabled', $params['id']) == 'y';
$pinterest_enabled = get_option('pinterest_enabled', $params['id']) == 'y';
$viber_enabled = get_option('viber_enabled', $params['id']) == 'y';
$whatsapp_enabled = get_option('whatsapp_enabled', $params['id']) == 'y';
$linkedin_enabled = get_option('linkedin_enabled', $params['id']) == 'y';
?>

<style scoped="scoped">
    .module-social-links-settings [class*='mw-icon-'],
    .module-social-links-settings [class*='fa-'] {
        font-size: 20px;
        display: inline-block;
        /* float: left; */
        margin: 0 10px 0 5px;
        width: 14px;
        text-align: center;
    }
</style>

<script>mw.lib.require('font_awesome5');</script>

<div class="module-live-edit-settings module-social-links-settings">
    <table class="mw-ui-table mw-full-width mw-ui-table-fixed mw-ui-table-basic">
        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="facebook_enabled" value="y" <?php if ($facebook_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-facebook"></i> Facebook</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="twitter_enabled" value="y" <?php if ($twitter_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-twitter"></i> Twitter</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="googleplus_enabled" value="y" <?php if ($googleplus_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-google-plus-g"></i> Google+</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="pinterest_enabled" value="y" <?php if ($pinterest_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-pinterest"></i> Pinterest</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="viber_enabled" value="y" <?php if ($viber_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-viber"></i> Viber</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="whatsapp_enabled" value="y" <?php if ($whatsapp_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-whatsapp"></i> WhatsApp</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="linkedin_enabled" value="y" <?php if ($linkedin_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-linkedin"></i> LinkedIn</span>
                </label>
            </td>
        </tr>
    </table>
</div>