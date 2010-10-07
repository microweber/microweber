mw.content = {};
mw.content.CategoriesTree = new function() {

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

mw.content.Vote = function(toTable, toTableId, updateElementSelector) {
	$.post('{SITEURL}api/content/vote', {
		t : toTable,
		tt : toTableId
	}, function(response) {
		
		
		if (response == 'yes') {
			$(updateElementSelector).each(function() {
				$(this).html(parseFloat($(this).html()) + 1);
			});
			return;
		}
		

		if ((response.valueOf())== 'no') {
		//	mw.box.notification('You have already voted!')
		//	return 'You have already voted!';
			//alert('asd');
			mw.box.alert('You have already liked this!');
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
		if (response == 'yes') {

			$(updateElementSelector).each(function() {
			    var curr = parseFloat($(this).html());
                if(!isNaN(curr)){
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