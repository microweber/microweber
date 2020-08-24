function editEventId(event_id) {
    var data = {};
    data.event_id = event_id;
    var module_id = 'edit-event-' + event_id;

    if (event_id) {
        var modaltitle = 'Edit event';
    } else {
        var modaltitle = 'Add event';

    }

    var opts = {};
    opts.width = '800';
    opts.height = '600';
    var editEventModal = mw.top().tools.open_global_module_settings_modal('calendar/edit_event', module_id, opts, data);
}

function reload_calendar_after_save($module_id) {
    if(typeof $module_id != 'undefined'){
    mw.reload_module_everywhere($module_id);
    }
    mw.reload_module_everywhere('calendar/edit_events');
    mw.top().$(window.parent.document).trigger('calendar.update');
}

function deleteEvent(event_id) {
    var con = confirm('Are you sure to delete this event?');
    if (con == true) {
        $.ajax({
                url: mw.settings.api_url + 'calendar_remove_event',
                data: 'eventid=' + event_id,
                type: 'POST',
                dataType: 'json',
                success: function (response) {
                    mw.reload_module_everywhere('calendar/edit_events');
                    mw.top().$(window.parent.document).trigger('calendar.update');
                        if (typeof(mw.top().reload_calendar_after_save) != 'undefined') {
                        reload_calendar_after_save();
                    }
                }
            }
        );
    }
}

function openEventsImportModal() {
    var data = {};
    importEventsModal = mw.top().tools.open_global_module_settings_modal('calendar/import_events', 'import-events')
}
