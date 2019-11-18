<?php
only_admin_access();
/**
 * Dev: Bozhidar Slaveykov
 * Emai: bobi@microweber.com
 * Date: 11/18/2019
 * Time: 10:26 AM
 */
?>


<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {

        mw.options.form('.<?php echo $config['module_class'] ?>', function () {
            mw.notification.success("<?php _e("Saved"); ?>.");
        });
    });
</script>

<div id="mw-admin-content" class="admin-side-content">

    <div class="mw_edit_page_default" id="mw_edit_page_left">

        <div class="mw-ui-box mw-ui-box-content" data-view="">
            <div class="mw-flex-row">
                <div class="mw-flex-col-xs-6">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label">Redirect To URL Address</label>
                        <input type="text" option-group="redirect" value="<?php echo get_option('redirect_url', 'redirect');?>" name="redirect_url" class="mw_option_field mw-ui-field mw-full-width" placeholder="<?php echo url('not-supported-browser'); ?>">
                    </div>
                </div>
            </div>
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Error code</label>
                <ul class="mw-ui-inline-list">

                    <li>
                        <label class="mw-ui-check">
                            <input type="radio" class="mw_option_field" option-group="redirect" value="200" name="error_code" <?php if (get_option('error_code', 'redirect') == '200'): ?>checked=""<?php endif; ?>>
                            <span></span><span>200 OK</span>
                        </label>
                    </li>
                    <li>
                        <label class="mw-ui-check">
                            <input type="radio" class="mw_option_field" option-group="redirect" value="301" name="error_code" <?php if (get_option('error_code', 'redirect') == '301'): ?>checked=""<?php endif; ?>>
                            <span></span><span>301 Moved Permanently</span>
                        </label>
                    </li>
                </ul>
            </div>
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Browsers redirect</label>
                <div class="mw-ui-box mw-ui-box-content">
                    <ul class="mw-ui-inline-list">
                        <?php
                        $browsersRedirect = get_browsers_redirect();
                        ?>
                        <?php foreach (get_browsers_options() as $key=>$value): ?>
                        <li>
                            <label class="mw-ui-check">
                                <input type="checkbox" class="mw_option_field" option-group="redirect" name="browsers_redirect" value="<?php echo $key; ?>" <?php if(array_key_exists($key,$browsersRedirect)): ?>checked=""<?php endif;?>>
                                <span></span><span><?php echo $value; ?></span>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="mw-ui-field-holder">
                <label class="mw-ui-label">Is module enabled?</label>
                <label class="mw-ui-check">
                    <input type="checkbox" value="y" name="active" <?php if (get_option('active', 'redirect') == 'y'): ?><?php endif; ?> class="mw_option_field" option-group="redirect">
                    <span></span><span>Yes</span>
                </label>
            </div>
        </div>

    </div>

</div>
