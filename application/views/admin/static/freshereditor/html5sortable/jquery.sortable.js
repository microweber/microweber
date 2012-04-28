/*
 * HTML5 Sortable jQuery Plugin
 * http://farhadi.ir/projects/html5sortable
 * 
 * Copyright 2012, Ali Farhadi
 * Released under the MIT license.
 */
(function($) {
var dragging, placeholders = $();
$.fn.sortable = function(options) {
	options = options || {};
	return this.each(function() {
		if (/^enable|disable|destroy$/.test(options)) {
			var items = $(this).children($(this).data('items')).attr('draggable', options == 'enable');
			options == 'destroy' &&	items.add(this)
				.removeData('connectWith').removeData('items')
				.unbind('dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s');
			return;
		}
		var index, items = $(this).children(options.items), connectWith = options.connectWith || false, forcePlaceholderSize  = options.forcePlaceholderSize  || false;
		var placeholder = $('<' + items[0].tagName + ' class="sortable-placeholder">');
		$(this).data('items', options.items)
		placeholders = placeholders.add(placeholder);
		connectWith && $(connectWith).add(this).data('connectWith', connectWith);
		items.attr('draggable', 'true').bind('dragstart.h5s', function(e) {
			var dt = e.originalEvent.dataTransfer;
			dt.effectAllowed = 'move';
			dt.setData('Text', 'dummy');
			dragging = $(this).addClass('sortable-dragging');
			index = dragging.index();
		}).bind('dragend.h5s', function() {
			dragging.removeClass('sortable-dragging').fadeIn();
			placeholders.detach();
			if (index != dragging.index()) {
				items.parent().trigger('sortupdate');
			}
			dragging = null;
		}).not('a[href], img').bind('selectstart.h5s', function() {
			this.dragDrop && this.dragDrop();
			return false;
		}).end().add([this, placeholder]).bind('dragover.h5s dragenter.h5s drop.h5s', function(e) {
			if (!items.is(dragging) && connectWith !== $(dragging).parent().data('connectWith')) {
				return true;
			}
			if (e.type == 'drop') {
				e.stopPropagation();
				placeholders.filter(':visible').after(dragging);
				return false;
			}
			e.preventDefault();
			e.originalEvent.dataTransfer.dropEffect = 'move';
			if (items.is(this)) {
				if (forcePlaceholderSize != false) {
				 placeholder.height(dragging.height())
				}
				dragging.hide();
				$(this)[placeholder.index() < $(this).index() ? 'after' : 'before'](placeholder);
				placeholders.not(placeholder).detach();
			}
			return false;
		});
	});
};
})(jQuery);