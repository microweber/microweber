<script>
    $(document).ready(function () {
        mw.tabs(
            {
                nav: '#newsletter-admin .mw-ui-btn-nav-tabs a',
                tabs: '#newsletter-admin .mw-ui-box-content'
            }
        );
    });
</script>
<?php
$mod_action = '';
$load_mod_action = false;
if ((url_param('mod_action') != false)) {
    $mod_action = url_param('mod_action');
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>

    <div class="mw-module-admin-wrap">
        <div class="admin-side-content" style="max-width:100%;">
            <div id="newsletter-admin">
                <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
                    <a class="mw-ui-btn active" href="javascript:;"><?php _e('Subscribers'); ?></a>
                    <a class="mw-ui-btn" href="javascript:;"><?php _e('Lists'); ?></a>
                    <a class="mw-ui-btn" href="javascript:;"><?php _e('Campaigns'); ?></a>
                    <a class="mw-ui-btn" href="javascript:;"><?php _e('Templates'); ?></a>
                    <a class="mw-ui-btn" href="javascript:;"><?php _e('Sending Accounts'); ?></a>
                    <a class="mw-ui-btn pull-right" href="javascript:;"><?php _e('Settings'); ?></a>
                </div>
                <div class="mw-ui-box">
                    <div class="mw-ui-box-content">
                        <module type="newsletter/subscribers"/>
                    </div>
                    <div style="display: none;" class="mw-ui-box-content">
                        <module type="newsletter/lists"/>
                    </div>
                    <div style="display: none;" class="mw-ui-box-content">
                        <module type="newsletter/campaigns"/>
                    </div>
                    <div style="display: none;" class="mw-ui-box-content">
                        <module type="newsletter/templates"/>
                    </div>
                    <div style="display: none;" class="mw-ui-box-content">
                        <module type="newsletter/sender_accounts"/>
                    </div>
                    <div style="display: none;" class="mw-ui-box-content">
                        <module type="newsletter/privacy_settings" for_module_id="<?php print $params['id'] ?>"/>
                        <hr/>
                        <module type="newsletter/settings" for_module_id="<?php print $params['id'] ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="mw-live-edit-settings text-center">
        <a href="<?php echo admin_url(); ?>view:modules/load_module:newsletter" class="mw-ui-btn mw-ui-btn-info" target="_blank">Go to Newsletter in Admin panel</a>
    </div>
<?php endif; ?>