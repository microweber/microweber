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
                margin: 0 10px 0 5px;
                width: 20px;
                text-align: center;
            }

            html[dir="rtl"] .module-social-links-settings [class*='mw-icon-'],
            html[dir="rtl"] .module-social-links-settings [class*='fa-'] {
                margin: 0 5px 0 10px;
            }

        </style>

        <script>
            $(document).ready(function () {
                $('.module-social-links-settings input[type="checkbox"]:checked').each(function () {
                    $(this).parent().parent().parent().addClass('bg-primary-opacity-1');
                });

                $('.module-social-links-settings input[type="checkbox"]').on('change', function () {
                    if ($(this).is(':checked')) {
                        $(this).parent().parent().parent().addClass('bg-primary-opacity-1');
                    } else {
                        $(this).parent().parent().parent().removeClass('bg-primary-opacity-1');
                    }
                });
            })
        </script>

        <div class="module-live-edit-settings module-social-links-settings">
            <div class="form-group">
                <label class="control-label"><?php _e("Select socials networks you want to share"); ?></label>
            </div>

            <table class="table">
                <tr>
                    <td>
                        <div class="form-group m-0">
                            <div class="custom-control custom-checkbox d-flex align-items-center m-0">
                                <input type="checkbox" class="mw_option_field custom-control-input" id="facebook_enabled" name="facebook_enabled" value="y" <?php if ($facebook_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="facebook_enabled"><i class="mdi mdi-facebook mdi-22px lh-1_0 mr-2 mw-socials-facebook-color"></i> Facebook</label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group m-0">
                            <div class="custom-control custom-checkbox d-flex align-items-center m-0">
                                <input type="checkbox" class="mw_option_field custom-control-input" id="twitter_enabled" name="twitter_enabled" value="y" <?php if ($twitter_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="twitter_enabled"><i class="mdi mdi-twitter mdi-22px lh-1_0 mr-2 mw-socials-twitter-color"></i> Twitter</label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr style="display: none;">
                    <td>
                        <div class="form-group m-0">
                            <div class="custom-control custom-checkbox d-flex align-items-center m-0">
                                <input type="checkbox" class="mw_option_field custom-control-input" id="googleplus_enabled" name="googleplus_enabled" value="y" <?php if ($googleplus_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="googleplus_enabled"><i class="mdi mdi-google-plus mdi-22px lh-1_0 mr-2 mw-socials-youtube-color"></i> Google+</label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group m-0">
                            <div class="custom-control custom-checkbox d-flex align-items-center m-0">
                                <input type="checkbox" class="mw_option_field custom-control-input" id="pinterest_enabled" name="pinterest_enabled" value="y" <?php if ($pinterest_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="pinterest_enabled"><i class="mdi mdi-pinterest mdi-22px lh-1_0 mr-2 mw-socials-pinterest-color"></i> Pinterest</label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group m-0">
                            <div class="custom-control custom-checkbox d-flex align-items-center m-0">
                                <input type="checkbox" class="mw_option_field custom-control-input" id="viber_enabled" name="viber_enabled" value="y" <?php if ($viber_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="viber_enabled"><i class="fab fa-viber mdi-22px lh-1_0 mr-2 mw-socials-viber-color"></i> Viber</label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group m-0">
                            <div class="custom-control custom-checkbox d-flex align-items-center m-0">
                                <input type="checkbox" class="mw_option_field custom-control-input" id="whatsapp_enabled" name="whatsapp_enabled" value="y" <?php if ($whatsapp_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="whatsapp_enabled"><i class="mdi mdi-whatsapp mdi-22px lh-1_0 mr-2 mw-socials-whatsapp-color"></i> WhatsApp</label>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="form-group m-0">
                            <div class="custom-control custom-checkbox d-flex align-items-center m-0">
                                <input type="checkbox" class="mw_option_field custom-control-input" id="linkedin_enabled" name="linkedin_enabled" value="y" <?php if ($linkedin_enabled) print 'checked="checked"'; ?>>
                                <label class="custom-control-label mr-2 d-flex" for="linkedin_enabled"><i class="mdi mdi-linkedin mdi-22px lh-1_0 mr-2 mw-socials-linkedin-color"></i> LinkedIn</label>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>
