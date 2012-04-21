/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.github.com/html5sortable
 * 
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
 
 window.mw_last_enter = 0;
 window.dragging = false;
jQuery.fn.sortable = function() {
	return this.each(function() {
		
		
		
		if(window.mw_editables_created == false){	
		
		
		var $ = jQuery, index;
		dragging = window.dragging;
		//var items = $(this).children()
		//var items = $(this).find('*:not(.module > * ,.module *)');
		var items = $(this).find('*:not(.module > * ,.module *)');
		if (items[0] == undefined) {
		var placeholder = $(this).addClass('sortable-placeholder');
		} else {
		var placeholder = $('<' + items[0].tagName + '>:not(.module *)').addClass('sortable-placeholder');	
		}
		
		
		
 //var placeholder = $('div').addClass('sortable-placeholder');
		items.attr('draggable', 'true').live('dragstart', function(e) {
			var dt = e.originalEvent.dataTransfer;
			dt.effectAllowed = 'move';
			dt.setData('Text', 'dummy');
			dragging = $(this).addClass('sortable-dragging');
			
			$('.module').children('[draggable]').removeAttr('draggable')
			
			index = dragging.index();
			e.stopPropagation();
		}).bind('dragend', function() {
			if (dragging != null) { 
			dragging.removeClass('sortable-dragging').fadeIn();
			}
			
			
			
			$('.sortable-dragging').removeClass('sortable-dragging');
			placeholder.detach();
			
			
			
			
			$('.sortable-placeholder').filter(function() { return $.trim($(this).text()) === ''  })  .remove()
			if (dragging != null) { 
			if (index != dragging.index()) {
				items.parent().trigger('sortupdate');
			}
			}
			dragging = null;
		}).not('a[href], img').bind('selectstart', function() {
			this.dragDrop && this.dragDrop();
			return false;
		}).end().add([this, placeholder]).bind('dragover', function(e) {
			if (!dragging) { 
			return true
			
			} else {
				
			
				e.stopPropagation();	
			e.preventDefault();
			e.originalEvent.dataTransfer.dropEffect = 'move';
		
			if (items.is(this)) {
				
				
				 placeholder.height(dragging.height())
				
				
				 dragging.hide();
				//$(this).before(placeholder);
				$(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
			}
			return false;
			}
		}).bind('drop', function(e) {
			if (!dragging) return true;
			e.stopPropagation();
			placeholder.after(dragging);
			//placeholder.before(dragging);
			return false;
		}).bind('mouseenter', function(e) {
			
			$mod = $(this).parents().hasClass('module')
			
			if($mod  == false){
				if (!dragging) { 
				//$(this).attr('draggable', 'true')
				//e.stopPropagation();
				}
			} else {
				 $(this).parent().children('[draggable]').removeAttr('draggable')
			// alert($mod);	
			}
			if (!dragging) { 
			 
			return true
			
			} else {
				if($mod  == false){
				var items = $(this).children().not('.module');
				 } else {
					 	var items = $(this).parents().hasClass('module').children()
				 }
				//var	placeholder = $(this);
			}
		});
	
	}
	
	});
	
	
	
	
};