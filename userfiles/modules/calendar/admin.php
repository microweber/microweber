<?php only_admin_access(); ?>

<?php $calendar_group_id = get_option('calendar_group_id', $params['id']); ?>

<div class="module-live-edit-settings">

    <script>
        mw.require('ui.css');
        mw.require("<?php print $config['url_to_module'];?>css/admin.css");
        mw.lib.require('jqueryui');

        mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/fullcalendar.min.css");
        mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/lib/moment.min.js");
        mw.require("<?php print $config['url_to_module'];?>/lib/fullcalendar/fullcalendar.min.js");
    </script>

    <div id="tabsnav">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a href="javascript:;" class="mw-ui-btn active tabnav">Events</a>
            <a href="javascript:;" class="mw-ui-btn tabnav">Groups</a>
            <a href="javascript:;" class="mw-ui-btn tabnav">Skin/Template</a>
        </div>
        <div class="mw-ui-box">
            <div class="mw-ui-box-content tabitem">

                <div>
                    <a class="mw-ui-btn mw-ui-btn-normal mw-ui-btn-info mw-ui-btn-outline"
                       href="javascript:editEventId(0)"><span> Add new event </span></a>
                </div>
                <hr>


                <div>
                    <module type="calendar/edit_events"/>
                </div>
            </div>
            <div class="mw-ui-box-content tabitem" style="display: none">
                <module type="calendar/edit_groups"/>
            </div>
            <div class="mw-ui-box-content tabitem" style="display: none">
                <module type="admin/modules/templates" simple="true"/>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            mw.tabs({
                nav: '#tabsnav  .tabnav',
                tabs: '#tabsnav .tabitem'
            });
        });
    </script>
    <div>
        <small>  <a href="javascript:openEventsImportModal()">Import / Export</a></small>

    </div>
</div>


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