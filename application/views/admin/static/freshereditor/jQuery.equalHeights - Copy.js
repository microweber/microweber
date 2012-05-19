/**
 * jQuery.equalHeights - Making an equal height panels.
 * @copyright (c) 2010-2011 Aamir Afridi - http://www.aamirafridi.com/blog
 * Dual licensed under MIT and GPL.
 * Date: 25/11/2010
 * @author Aamir Afridi - aamirafridi(at)gmail(dot)com | http://www.aamirafridi.com
 * @version 1.0
 */

;(function($) {
	$.fn.equalHeights = function(options) {
		return this.each(function() {
			// Extend the options if any provided
			var o = $.extend({}, $.fn.equalHeights.defaults, options), $this = $(this);
			//Find the shortest height item
			var shortestHeight = 10000;
			$this.find(o.itemsToEqualize).each(function(){
				shortestHeight = $(this).height() < shortestHeight ? $(this).height() : shortestHeight;
			});
			//Now loop through the each element and keep removing word by word untill its height reaches to the minimal
			$this.find(o.itemsToEqualize).each(function(){
				//If height is already equal to the minimum than return
				if($(this).height()==shortestHeight) return;
				//Now loop until the heights is equal to the minimum height by removing word by word
				 
				//Now check if the last char of the last word is not a full stop than remove the last word and put three dots with the last word
				 
			});
		});
	};//End of Plugin
	
	// Public: plugin defaults options
	$.fn.equalHeights.defaults = {
		itemsToEqualize : '.column' 
 	};
})(jQuery);







$.ui.plugin.add("resizable", "alsoResizeReverse", {

    start: function(event, ui) {

        var self = $(this).data("resizable"), o = self.options;

        var _store = function(exp) {
            $(exp).each(function() {
                $(this).data("resizable-alsoresize-reverse", {
                    width: parseInt($(this).width(), 10), height: parseInt($(this).height(), 10),
                    left: parseInt($(this).css('left'), 10), top: parseInt($(this).css('top'), 10)
                });
            });
        };

        if (typeof(o.alsoResizeReverse) == 'object' && !o.alsoResizeReverse.parentNode) {
            if (o.alsoResizeReverse.length) { o.alsoResize = o.alsoResizeReverse[0];    _store(o.alsoResizeReverse); }
            else { $.each(o.alsoResizeReverse, function(exp, c) { _store(exp); }); }
        }else{
            _store(o.alsoResizeReverse);
        }
    },

    resize: function(event, ui){
        var self = $(this).data("resizable"), o = self.options, os = self.originalSize, op = self.originalPosition;

        var delta = {
            height: (self.size.height - os.height) || 0, width: (self.size.width - os.width) || 0,
            top: (self.position.top - op.top) || 0, left: (self.position.left - op.left) || 0
        },

        _alsoResizeReverse = function(exp, c) {
            $(exp).each(function() {
                var el = $(this), start = $(this).data("resizable-alsoresize-reverse"), style = {}, css = c && c.length ? c : ['width', 'height', 'top', 'left'];

                $.each(css || ['width', 'height', 'top', 'left'], function(i, prop) {
                    var sum = (start[prop]||0) - (delta[prop]||0); // subtracting instead of adding
                    if (sum && sum >= 0)
                        style[prop] = sum || null;
                });

                //Opera fixing relative position
                if (/relative/.test(el.css('position')) && $.browser.opera) {
                    self._revertToRelativePosition = true;
                    el.css({ position: 'absolute', top: 'auto', left: 'auto' });
                }

                el.css(style);
            });
        };

        if (typeof(o.alsoResizeReverse) == 'object' && !o.alsoResizeReverse.nodeType) {
            $.each(o.alsoResizeReverse, function(exp, c) { _alsoResizeReverse(exp, c); });
        }else{
            _alsoResizeReverse(o.alsoResizeReverse);
        }
    },

    stop: function(event, ui){
        var self = $(this).data("resizable");

        //Opera fixing relative position
        if (self._revertToRelativePosition && $.browser.opera) {
            self._revertToRelativePosition = false;
            el.css({ position: 'relative' });
        }

        $(this).removeData("resizable-alsoresize-reverse");
    }
});