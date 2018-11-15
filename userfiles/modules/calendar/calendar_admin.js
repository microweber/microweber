function editEventId(event_id) {
    var data = {};
    data.event_id = event_id;




    if (event_id) {
        var modaltitle = 'Edit event';
    } else {
        var modaltitle = 'Add event';

    }

    editEventModal =  mw.tools.open_module_modal('calendar/edit_event', data, {
        overlay: true,
      //  iframe: true,

        title: modaltitle,
        skin: 'simple'
    })


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
                    if (typeof(reload_calendar_after_save) != 'undefined') {
                        reload_calendar_after_save();
                    }
                }
            }
        );
    }
}

function openEventsImportModal() {

    var data = {};

    importEventsModal = mw.tools.open_module_modal('calendar/import_events', data, {overlay: true,   skin: 'simple'})

}