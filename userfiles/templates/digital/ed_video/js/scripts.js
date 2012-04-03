var site = function() {
	this.navLi = $('#nav li').children('ul').hide().end();
	this.init();
};

site.prototype = {
 	
 	init : function() {
 		this.setMenu();
 	},
 	
 	// Enables the slidedown menu, and adds support for IE6
 	
 	setMenu : function() {
 	
 	$.each(this.navLi, function() {
 		if ( $(this).children('ul')[0] ) {
 			$(this)
 				.append('<span />')
 				.children('span')
 					.addClass('hasChildren')
 		}
 	});
 	
 		this.navLi.hover(function() {
 			// mouseover
			$(this).find('> ul').stop(true, true).slideDown('slow', 'easeOutBounce');
 		}, function() {
 			// mouseout
 			$(this).find('> ul').stop(true, true).hide(); 		
		});
 		
 	}
 
}


new site();