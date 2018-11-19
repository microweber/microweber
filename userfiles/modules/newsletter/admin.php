<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="admin-side-content">

    <?php
    $mod_action = '';
    $load_mod_action = false;
    if ((url_param('mod_action') != false)) {
        $mod_action = url_param('mod_action');
    }
    ?>

    <div class="mw-modules-tabs <?php if ($from_live_edit): ?><?php else: ?><?php endif; ?>">
        <div class="mw-accordion-item js-subscribers-list">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-navicon-round"></i> <?php print _e('List of Subscribers'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <module type="newsletter/subscribers"/>
            </div>
        </div>

        <div class="mw-accordion-item js-add-new-subscriber">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-plus-round"></i> Add New
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <module type="newsletter/add_subscriber"/>
            </div>
        </div>

        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-newsletter-settings">
                    <module type="newsletter/privacy_settings"/>
                    <module type="newsletter/settings"/>
                    <?php /* <module type="newsletter/campaigns"/> */ ?>
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
</div>