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
        <?php $module_info = module_info($params['module']); ?>
        <h5>
            <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/> <strong><?php echo $module_info['name']; ?></strong>
        </h5>
    </div>

    <div class="card-body pt-3">
        <script>
            mw.require("<?php  print  modules_url() ?>calendar/calendar_admin.js");
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

        <?php $calendar_group_id = get_option('calendar_group_id', $params['id']); ?>

        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-outline-secondary justify-content-center active" data-toggle="tab" href="#list"><i class="mdi mdi-format-list-bulleted-square mr-1"></i> Events</a>
            <?php if (!$from_live_edit): ?>
                <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#groups"><i class="mdi mdi-cog-outline mr-1"></i> <?php print _e('Groups'); ?></a>
            <?php endif; ?>
            <?php if ($from_live_edit): ?>
                <a class="btn btn-outline-secondary justify-content-center" data-toggle="tab" href="#templates"><i class="mdi mdi-pencil-ruler mr-1"></i> <?php print _e('Templates'); ?></a>
            <?php endif; ?>
        </nav>

        <div class="tab-content py-3">

            <div class="tab-pane fade show active" id="list">
                <!-- Settings Content -->
                <div class="module-live-edit-settings module-calendar-settings">
                    <module type="calendar/edit_events"/>
                </div>
                <!-- Settings Content - End -->
            </div>
            <?php if (!$from_live_edit): ?>
                <div class="tab-pane fade" id="groups">
                    <module type="calendar/edit_groups"/>
                </div>
            <?php endif; ?>

            <?php if ($from_live_edit): ?>
                <div class="tab-pane fade" id="templates">
                    <module type="admin/modules/templates"/>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!$from_live_edit): ?>
            <small><a href="javascript:openEventsImportModal()">Import / Export</a></small>
        <?php endif; ?>
    </div>
</div>
