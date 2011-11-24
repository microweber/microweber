 
var CategoriesTree = new function() {

	this.servicesUrl = '/ajax_helpers/';

	this.loadArticles = function (authorId, categoryId) {
		$.post(
     		this.servicesUrl + 'categoty_get_articles_list',
     		{ 
     			categoryId: categoryId,
     			authorId: authorId
     		},
     		function(response){
     			$('#sidebar_recent_posts_list').html(response);
     		});
	};
	

}
 

 