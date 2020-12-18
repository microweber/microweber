<?php only_admin_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<script>
    mw.require("<?php  print  modules_url() ?>calendar/calendar_admin.js");
</script>

<script>
    //mw.require('ui.css');
    //mw.lib.require('jqueryui');

    mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/fullcalendar.min.css");
    mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/lib/moment.min.js");
    mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/fullcalendar.min.js");
</script>

<script>

    //function reload_calendar_after_save() {
    //    mw.reload_module_everywhere('#<?php //print $params['id'] ?>//');
    //    mw.reload_module_everywhere('calendar/edit_events');
    //    window.parent.$(window.parent.document).trigger('calendar.update');
    //    mw.top().$(window.parent.document).trigger('calendar.update');
    //    alert(33333)
    //
    //}

</script>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>


<script>
    function manageCalendarEventsPopup(module_id) {

        var opts = {};
        opts.width = '800';
        opts.height = '90vh';

        opts.liveedit = true;
        opts.iframe = true;
        opts.mode = 'modal';
        opts.autosize = 'false';

        var additional_params = {};

        return window.parent.mw.tools.open_global_module_settings_modal('calendar/edit_events', module_id, opts, additional_params);

    }


</script>

<div class="admin-side-content">
    <?php $calendar_group_id = get_option('calendar_group_id', $params['id']); ?>

    <div class="mw-modules-tabs <?php if ($from_live_edit): ?><?php else: ?><?php endif; ?>">
        <?php if ($from_live_edit): ?>
            <div class="mw-accordion-item-block mw-live-edit-module-manage-and-list-top">
                <a href="javascript:manageCalendarEventsPopup('<?php print $params['id'] ?> ')" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded">
                    <span class="fas fa-list"></span> &nbsp;<?php print _e('Manage Calendar Events'); ?>


                </a>


            </div>
        <?php endif; ?>

        <?php if (!$from_live_edit): ?>
            <div class="mw-accordion-item">
                <div class="mw-ui-box-header mw-accordion-title">
                    <div class="header-holder">
                        <i class="mw-icon-navicon-round"></i> Events
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <!-- Settings Content -->
                    <div class="module-live-edit-settings module-calendar-settings">
                        <module type="calendar/edit_events"/>
                    </div>
                    <!-- Settings Content - End -->
                </div>
            </div>

            <div class="mw-accordion-item">
                <div class="mw-ui-box-header mw-accordion-title">
                    <div class="header-holder">
                        <i class="mw-icon-beaker"></i> Groups
                    </div>
                </div>
                <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                    <module type="calendar/edit_groups"/>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($from_live_edit): ?>
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
        <?php endif; ?>
    </div>

    <?php if (!$from_live_edit): ?>
        <br/>
        <small><a href="javascript:openEventsImportModal()">Import / Export</a></small>
    <?php endif; ?>

</div>
