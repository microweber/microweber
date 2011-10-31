/**
 * jquery.treeview.js
 * JQuery Treeview Plugin
 *
 * Copyright (c) 2010 Guido Neele <http://www.neele.name>
 * Dual licensed under the MIT and GPL licenses. 
 * 
 * ---- Converts unordered lists into a treeview ----
 * 
 * <ul class="treeview">
 *   <li>
 *     <a href class="folder">Item 1</a>
 *       <ul>
 *         <li><a href class="folder">Item 1.1</a></li>
 *         <li><a href class="folder">Item 1.2</a></li>
 *         <li><a href class="folder">Item 1.3</a></li>
 *       </ul>
 *   </li>
 *   <li><a href class="folder">Item 2</a></li>
 * </ul>
 * 
**/

(function($){
	$.fn.TreeView = function() {
    
		return this.each(function() {
			obj = $(this); 
			
			$('a', obj)
				//Add end cap
				.append('<span class="endcap"><img src="../img/spacer.gif" width="6" height="22" /></span>')
				//Add icon based on class name
				.each(function(){
					$(this).prepend('<span class="icon"><img src="../img/spacer.gif" width="26" height="22" /></span>');							 
				})
				//Add the class link to every hyperlink
				.addClass('link')	
			.end();
			
			//Add Spacer before link
			$('li', obj).each(function(){
				depth = parseInt($(this).parents('ul').length);
				width = (depth - 1) * 12;
				$(this).prepend('<a class="handler"><img src="../img/spacer.gif" width="16" height="22" /></a>');
				$(this).prepend('<a class="spacer"><img src="../img/spacer.gif" width="' + width + '" height="22" /></a>');
			});
			
			//If list item has children then add class 'children'
			$('li:has(ul) > a.handler', obj).addClass('children');
			
			// Show or hide children when clicked on the handler
			$('a.children', obj).bind('click', function(){
				$(this).toggleClass('open');
				$(this).next().next().toggle();	
				return false;
			});
		});
	};
})(jQuery);