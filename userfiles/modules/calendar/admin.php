<?php only_admin_access(); ?>


<?php $calendar_group_id = get_option('calendar_group_id', $params['id']); ?>

<div class="module-live-edit-settings">

    <style>
        .mw-mod-template-settings-holder {
            float: right
        }

        .mw-mod-template-settings-holder label, .mw-mod-template-settings-holder select {
            float: left
        }

        .mw-mod-template-settings-holder label {
            margin-top: 11px;
            margin-right: 10px
        }
    </style>

    <script>

        mw.require('ui.css');

        mw.lib.require('jqueryui');

        //        mw.require("<?php //print $config['url_to_module'];?>//fullcalendar-3.1.0/fullcalendar.min.css");
        //        mw.require("<?php //print $config['url_to_module'];?>//fullcalendar-3.1.0/lib/moment.min.js");
        //        mw.require("<?php //print $config['url_to_module'];?>//fullcalendar-3.1.0/fullcalendar.min.js");
    </script>

    <style>
        #newevent {
            cursor: move;
            width: 80px;
            text-align: center;
            margin-top: 10px
        }

        #external-events {
            margin-bottom: 10px
        }

        #trash {
            float: left;
            width: 80px;
            text-align: center
        }

        #eventContent {
            display: table;
            width: 100% !important;
        }

        #eventContent .row {
            width: 100%;
            clear: both;
            padding-bottom: 10px;
        }

        #eventContent .row label {
            width: 80px;
            vertical-align: top;
            float: left;
            display: table-column;
        }

        #eventContent .row col {
            width: auto;
            float: left;
            display: table-column;
        }

        #eventContent .row .colElement {
            width: 220px;
        }

        .ui-dialog-buttonpane .leftButton {
            float: left
        }
    </style>


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
        <small>  <a href="javascript:openEventsImportModal()">import/export</a></small>

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