<?php only_admin_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<script>
    //mw.require('ui.css');
    //mw.lib.require('jqueryui');

    mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/fullcalendar.min.css");
    mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/lib/moment.min.js");
    mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/fullcalendar.min.js");
</script>

<script>
    function editEventId(event_id) {
        var data = {};
        data.event_id = event_id;
        editModal = mw.tools.open_module_modal('calendar/edit_event', data, {overlay: true, skin: 'simple'})
    }

    function deleteEvent(event_id) {
        var con = confirm('<?php _e('Are you sure to delete this event permanently?'); ?>');
        if (con == true) {
            $.ajax({
                    url: '<?php print api_url('calendar_remove_event');?>',
                    data: 'eventid=' + event_id,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        if (typeof(reload_calendar_after_save) != 'undefined') {
                            reload_calendar_after_save();
                        }
                    }
                }
            );
        }
    }

    function reload_calendar_after_save() {
        mw.reload_module_parent('#<?php print $params['id'] ?>');
        mw.reload_module('calendar/edit_events');
        window.parent.$(window.parent.document).trigger('calendar.update');
        if (typeof(editModal) != 'undefined' && editModal.modal) {
            editModal.modal.remove();
        }

    }

    function openEventsImportModal() {

        var data = {};

        importModal = mw.tools.open_module_modal('calendar/import_events', data, {overlay: true, skin: 'simple'})

    }
</script>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="admin-side-content">
    <?php $calendar_group_id = get_option('calendar_group_id', $params['id']); ?>

    <div class="<?php if ($from_live_edit): ?>mw-accordion<?php else: ?>mw-tab-accordion<?php endif; ?>">
        <?php if ($from_live_edit): ?>
            <div class="mw-accordion-item-block mw-live-edit-module-manage-and-list-top">
                <a href="#" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info mw-ui-btn-rounded">
                    <span class="fas fa-list"></span> &nbsp;Manage
                </a>

                <a href="#" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded">
                    <span class="fas fa-plus-circle"></span> &nbsp;Add new
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
                        <div class="mw-ui-field-holder p-t-0 p-b-20 text-right">
                            <a href="javascript:editEventId(0)" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification mw-ui-btn-rounded">
                                <span class="fas fa-plus-circle"></span> &nbsp;<?php print _e('Add new event'); ?>
                            </a>
                        </div>

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
                        <i class="mw-icon-beaker"></i> Templates
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