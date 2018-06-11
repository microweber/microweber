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


<div class="section-header">
    <h2 class="pull-left"><span class="mai-mail"></span> <?php _e('Newsletter'); ?></h2>
</div>

<div class="admin-side-content">
    <div class="module-live-edit-settings" id="newsletter-admin">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a class="mw-ui-btn active" href="javascript:;"><?php _e('Subscribers'); ?></a>
            <a class="mw-ui-btn" style="display: none;" href="javascript:;"><?php _e('Campaigns'); ?></a>
            <a class="mw-ui-btn" href="javascript:;"><?php _e('Settings'); ?></a>
        </div>
        <div class="mw-ui-box">
            <div class="mw-ui-box-content" style="display: block;">
                <module type="newsletter/subscribers"/>
            </div>
            <div style="display: none;" class="mw-ui-box-content">
                <module type="newsletter/campaigns"/>
            </div>
            <div style="display: none;" class="mw-ui-box-content">
                <module type="newsletter/privacy_settings"/>
                <hr />
                <module type="newsletter/settings"/>
            </div>
        </div>
    </div>

</div>
