mw.media = {};
 
mw.media.del = function(media_id, hide_element_or_callback) {
	var answer = confirm("Are you sure you want to delete this?");

	if (answer) {

		$.post('{SITEURL}api/media/delete', {
			id : media_id
		}, function(response) {
			if (response.indexOf('yes')!=-1) {
				if (typeof (hide_element_or_callback) == 'function') {
					hide_element_or_callback.call(this);
				} else {
					$(hide_element_or_callback).fadeOut().remove();
                    
				}
			} else {
				alert(response);
			}
		});

	}

}
 