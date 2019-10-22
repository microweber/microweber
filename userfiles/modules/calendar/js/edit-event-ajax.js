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

                mw.reload_module_everywhere('calendar/edit_events');
                mw.top().$(window.parent.document).trigger('calendar.update');

                mw.notification.success(mw.lang('All changes saved'));
                var dialog = mw.dialog.get(e.target);
                if(dialog){
                    dialog.remove();
                }
            },
            error: function (e) {
                alert('Error processing your request: ' + e.responseText);
            }
        });
    });

});
