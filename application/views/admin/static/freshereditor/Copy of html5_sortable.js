/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.github.com/html5sortable
 * 
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
jQuery.fn.sortable = function() {
	return this.each(function() {
		var $ = jQuery, index, dragging, items = $(this).children();
		var placeholder = $('<' + items[0].tagName + '>, .edit').addClass('sortable-placeholder');
 //var placeholder = $('div').addClass('sortable-placeholder');
		items.attr('draggable', 'true').bind('dragstart', function(e) {
			var dt = e.originalEvent.dataTransfer;
			dt.effectAllowed = 'move';
			dt.setData('Text', 'dummy');
			dragging = $(this).addClass('sortable-dragging');
			index = dragging.index();
		}).bind('dragend', function() {
			dragging.removeClass('sortable-dragging').fadeIn();
			placeholder.detach();
			if (index != dragging.index()) {
				items.parent().trigger('sortupdate');
			}
			dragging = null;
		}).not('a[href], img').bind('selectstart', function() {
			this.dragDrop && this.dragDrop();
			return false;
		}).end().add([this, placeholder]).bind('dragover dragenter', function(e) {
			if (!dragging) return true;
			e.preventDefault();
			e.originalEvent.dataTransfer.dropEffect = 'move';
			if (items.is(this)) {
				dragging.hide();
				//$(this).before(placeholder);
				$(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
			}
			return false;
		}).bind('drop', function(e) {
			if (!dragging) return true;
			e.stopPropagation();
			placeholder.after(dragging);
			return false;
		});
	});
};