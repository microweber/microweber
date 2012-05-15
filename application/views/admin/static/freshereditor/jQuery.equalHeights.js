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
			 	var shortestW = 10000;
			$this.find(o.itemsToEqualize).each(function(){
				shortestHeight = $(this).height() < shortestHeight ? $(this).height() : shortestHeight;
		 				// shortestW = $(this).width() < shortestW ? $(this).width() : shortestW;

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



/*!
 * equalWidths jQuery Plugin
 * Examples and documentation at: http://fordinteractive.com/tools/jquery/equalwidths/
 * Copyright (c) 2010 Andy Ford
 * Version: 0.1 (2010-04-13)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Requires: jQuery v1.2.6+
 */
(function($){
	$.fn.equalWidths = function(options) {
		var w_parent,
			w_child,
			w_child_last,
			opts = $.extend({
				stripPadding: 'none' // options: 'child', 'grand-child', 'both'
			},options);
		return this.each(function(){
			var child_count = $(this).children('.column').size();
			var last_col = $(this).children('.column:last-child');
	        var parent_row = $(this);

			  $('.also-resize').removeClass('also-resize');
			 last_col.addClass('also-resize');
			
				if (child_count == 1) {
					
					
					
				$(this).children('.column').each(function(index) {
				   $(this).css({
						width:  (100 )+"%",
					 });
					// $(this).addClass('also-resize');
					 });
					 
					 
					 
					 
					 
				} else if (child_count > 0) { // only proceed if we've found any children
								w_parent1 = $(this).width();
								w_parent = 100;
								//  width: $(this).width()/parent.width()*100+"%",
								
								
								w_ch = 0;
								w_parent_diff = w_parent;
								$a = 1;
								$(this).children('.column').each(function(index) {
									
									
									if(window.mw_empty_column_placeholder != undefined){
											var col_size = $(this).children().size();
											
											if(col_size == 0){
												$(this).html(window.mw_empty_column_placeholder);
												
											}
									}
									  var parent = parent_row;
									//   var parent_column = parent.parent('.column');
									//  if(parent_column != undefined ){
									//	  parent = parent_column
									//  }
									  
									   var parent_w = parent.width();
									
									
									
									  $w1 = $(this).width()/w_parent1*100;
									 
									  if($a < child_count){
									  w_ch = w_ch+$w1;
									  }
									  
									  if($a == child_count){
										 //$w1 = $w1 -1; 
										  if(w_ch < 100){
											  $t1 = 100 - w_ch - (child_count * 1) ;
										 $w1 = Math.ceil($t1-1); // -1% padding
									  }
										 
										 
										 
										   $('#ContentSave').html(w_ch+'   |   '+ $w1);
										 
									  }
									  
									  
									  
									  
									  //$w1 = Math.ceil($w1); // -1% padding
					   $(this).css({
							width:  $w1+"%",
							//height: ui.element.height()/parent.height()*100+"%"
						});
	 
	 
	 				$a++;
					}); 
				
			//	last_col_w  =last_col.width()/w_parent1*100;
				
				//last_col_w1 = last_col_w - child_count;
				// last_col.css({
						 	//width:  last_col_w1+"%",
							//height: ui.element.height()/parent.height()*100+"%"
						//});
				
				//$('#ContentSave').html(last_col_w1);
				
				
				 
			} else {
				
					
						/*			if(window.mw_empty_column_placeholder != undefined){
											 $(this).children('.column').each(function(index) {
												$(this).html(window.mw_empty_column_placeholder);
											});	
											 
									}*/
						
									
				//if no cols add placeholder
				
				
				
				
				
			}
		});
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