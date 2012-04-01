mw.content = {};
mw.content.CategoriesTree = function() {

	this.servicesUrl = '{SITEURL}ajax_helpers/';

	this.loadArticles = function(authorId, categoryId) {
		$.post(this.servicesUrl + 'categoty_get_articles_list', {
			categoryId : categoryId,
			authorId : authorId
		}, function(response) {
			// $('#sidebar_recent_posts_list').html(response);
				return response;
			});
	};

}

mw.content.del = function(post_id, hide_element_or_callback) {
	var answer = confirm("Are you sure you want to delete this?");

	if (answer) {

		$.post('{SITEURL}api/content/delete', {
			id : post_id
		}, function(response) {
			if (response.indexOf('yes')!=-1) {
				if (typeof (hide_element_or_callback) == 'function') {
					hide_element_or_callback.call(this);
				} else {
					$(hide_element_or_callback).fadeOut();
				}
			} else {
				alert(response);
			}
		});

	}

}

mw.content.save = function($form_selector, $callback) {
	$data = ($($form_selector).serialize());

	$.ajax( {
		type : "POST",
		url : "{SITE_URL}api/content/save_post",
		data : $data,
		dataType: 'json',
		success : function(msg) {
			//alert("Data: " + msg);
		 // $('.cart_items_qty').html(msg);
 
		if (typeof  $callback == 'function') {
			$callback.call(this, msg);
		} else {
			$($callback).fadeOut();
		}
		
		
		
		
			
		}
	});

}


mw.content.Vote = function(toTable, toTableId, updateElementSelector) {

	$.post('{SITEURL}api/content/vote', {
		t : toTable,
		tt : toTableId
	}, function(response) {

		if (response.indexOf("yes")!= -1) {
			$(updateElementSelector).each(function() {
				$(this).html(parseFloat($(this).html()) + 1);
			});

			eventlistener = 'onAfterVote';

			return;
		}

		if ((response.valueOf()) == 'no') {
			// mw.box.notification('You have already voted!')
			// return 'You have already voted!';
			// alert('asd');
			// mw.box.alert('You have already liked this!');
		}

		if (response == 'login required') {
			if ((response.valueOf()) == 'login required') {
				mw.users.AjaxLogin();
			} else {
				mw.box.notification(response)
			}
		}

	});
}

mw.content.report = function(toTable, toTableId, updateElementSelector) {
	$.post('{SITEURL}api/content/report', {
		t : toTable,
		tt : toTableId
	}, function(response) {
		if (response.indexOf("yes")== true) {

			$(updateElementSelector).each(function() {
				var curr = parseFloat($(this).html());
				if (!isNaN(curr)) {
					$(this).html(curr + 1);
				}

			});

		}

		if (response == 'login required') {

			if ((response.valueOf()) == 'login required') {

				mw.users.AjaxLogin();

			} else {

				mw.box.notification(response)
			}
		}

	});
}