<?php only_admin_access(); ?>

<?php
$username = get_option('username', $params['id']);
if (!isset($username) or $username == false or $username == '') {
    $username = 'bummer.frenchie.wild';
}

$number_of_items = get_option('number_of_items', $params['id']);
if (!isset($number_of_items) or $number_of_items == false or $number_of_items == '') {
    $number_of_items = 3;
}
?>

<script type="text/javascript">mw.require('options.js');</script>

<script type="text/javascript">
    $(window).ready(function () {
        mw.options.form('.module-instagram-feed-settings', function () {
            if (mw.notification != undefined) {
                mw.notification.success('Instagram feed settings saved');
            }
            mw.reload_module_parent('instagram_feed'); //reload the module in live edit
        });
    });
</script>


<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <!-- Settings Content -->
            <div class="module-live-edit-settings module-instagram-feed-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Instagram Username'); ?></label>
                    <input type="text" class="mw_option_field mw-ui-field mw-full-width" placeholder="Example: microweber" name="username" value="<?php print $username; ?>">
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e('Number of items'); ?></label>
                    <input type="number" class="mw_option_field mw-ui-field mw-full-width" name="number_of_items" value="<?php print $number_of_items; ?>">
                </div>
            </div>
            <!-- Settings Content - End -->
        </div>
    </div>

    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates"/>
        </div>
    </div>
</div>