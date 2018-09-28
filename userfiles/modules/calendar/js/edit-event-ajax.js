$(document).ready(function() {
	
	// PROCESS FORM
	$("#editEventForm").submit(function(e) {
		e.preventDefault(e);
		var form_data = '?' + $(this).serialize();
		form_data = mw.url.set_param('content_id', content_id, form_data).replace('?', '');
		$.ajax({
			url: calendar_api_save_event,
			data: form_data,
			type: 'POST',
			dataType: 'json',
			success: function(response) {
				if (typeof(reload_calendar_after_save) != 'undefined') {
					reload_calendar_after_save();
				}
				editModal.modal.remove()
			},
			error: function(e) {
				alert('Error processing your request: ' + e.responseText);
			}
		});
	});
	
});