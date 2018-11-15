$(document).ready(function () {

    // PROCESS FORM
    $("#editEventForm").submit(function (e) {


        var calendar_api_save_event = mw.settings.api_url + 'calendar_save_event';


        e.preventDefault(e);
        var form_data = '?' + $(this).serialize();
        form_data = mw.url.set_param('content_id', content_id, form_data).replace('?', '');
        $.ajax({
            url: calendar_api_save_event,
            data: form_data,
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                if (typeof(reload_calendar_after_save) != 'undefined') {
                    reload_calendar_after_save();
                }
                if (typeof(window.parent.reload_calendar_after_save) != 'undefined') {
                    window.parent.reload_calendar_after_save();
                }
                if (typeof(editEventModal) != 'undefined') {
                    editEventModal.modal.remove()
                }
            },
            error: function (e) {
                alert('Error processing your request: ' + e.responseText);
            }
        });
    });

});