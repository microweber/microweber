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
        width: 20px;
        text-align: center;
    }

    html[dir="rtl"] .module-social-links-settings [class*='mw-icon-'],
    html[dir="rtl"] .module-social-links-settings [class*='fa-'] {
        margin: 0 5px 0 10px;
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
        <label class="mw-ui-label">Select socials networks you want to share</label>
    </div>
    <table class="mw-ui-table mw-full-width mw-ui-table-fixed mw-ui-table-basic">
        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="facebook_enabled" value="y" <?php if ($facebook_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-facebook mw-socials-facebook-color"></i> Facebook</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="twitter_enabled" value="y" <?php if ($twitter_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-twitter mw-socials-twitter-color"></i> Twitter</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="googleplus_enabled" value="y" <?php if ($googleplus_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-google-plus-g mw-socials-youtube-color"></i> Google+</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="pinterest_enabled" value="y" <?php if ($pinterest_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-pinterest mw-socials-pinterest-color"></i> Pinterest</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="viber_enabled" value="y" <?php if ($viber_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-viber mw-socials-viber-color"></i> Viber</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="whatsapp_enabled" value="y" <?php if ($whatsapp_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-whatsapp mw-socials-whatsapp-color"></i> WhatsApp</span>
                </label>
            </td>
        </tr>

        <tr>
            <td>
                <label class="mw-ui-check">
                    <input type="checkbox" class="mw_option_field" name="linkedin_enabled" value="y" <?php if ($linkedin_enabled) print 'checked="checked"'; ?>><span></span><span><i class="fab fa-linkedin mw-socials-linkedin-color"></i> LinkedIn</span>
                </label>
            </td>
        </tr>
    </table>
</div>
