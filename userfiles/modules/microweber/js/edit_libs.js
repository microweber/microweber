(function($) {
	$.fn.putPlaceholdersInEmptyColumns = function(options) {
		return this.each(function() {
			var o = $.extend({}, $.fn.equalHeights.defaults, options), $this = $(this);
            if ($("div.element", this).size() == 0) {
                $(this).html(mw.settings.empty_column_placeholder2);
            } else {
                $(this).children('.empty-column').remove()
            }
		});
	};//End of Plugin
})(jQuery);

/**
 * jQuery.equalHeights - Making an equal height panels.
 * @copyright (c) 2010-2011 Aamir Afridi - http://www.aamirafridi.com/blog
 * Dual licensed under MIT and GPL.
 * Date: 25/11/2010
 * @author Aamir Afridi - aamirafridi(at)gmail(dot)com | http://www.aamirafridi.com
 * @version 1.0
 */

(function($) {
	$.fn.equalHeights = function(options) {
		return this.each(function() {
			// Extend the options if any provided
			var o = $.extend({}, $.fn.equalHeights.defaults, options), $this = $(this);

			 var shortestHeight = 10000;
			 var biggestHeight = 0;
			$this.children('.mw-col' ).each(function(){
				shortestHeight = $(this).height() < shortestHeight ? $(this).height() : shortestHeight;
				biggestHeight = $(this).outerHeight() > biggestHeight ? $(this).outerHeight() : biggestHeight;

			});
			$this.children('.mw-col' ).height(biggestHeight);
		});
	};//End of Plugin
	$.fn.equalHeights.defaults = {
		itemsToEqualize : '.mw-col'
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
			var child_count = $(this).children('.mw-col').size();
			var last_col = $(this).children('.mw-col:last-child');
	        var parent_row = $(this);

			 // mw.$('.also-resize').removeClass('also-resize');
			// last_col.addClass('also-resize');

				if (child_count == 1) {



				$(this).children('.mw-col').each(function(index) {
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
								$(this).children('.mw-col').each(function(index) {


									if(mw.settings.empty_column_placeholder != undefined){
											var col_size = $(this).children().size();

											if(col_size == 0){
												$(this).html(mw.settings.empty_column_placeholder);

											}
									}
									  var parent = parent_row;
									//   var parent_column = parent.parent('.mw-col');
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





									  }




									  //$w1 = Math.ceil($w1); // -1% padding
					   $(this).css({
							width:  $w1+"%",
							//height: ui.element.height()/parent.height()*100+"%"
						});


	 				$a++;
					});



			} else {




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



// **************************************************************************
// Copyright 2007 - 2009 Tavs Dokkedahl
// Contact: http://www.jslab.dk/contact.php
//
// This file is part of the JSLab Standard Library (JSL) Program.
//
// JSL is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 3 of the License, or
// any later version.
//
// JSL is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program. If not, see <http://www.gnu.org/licenses/>.
// ***************************************************************************

// Return new array with duplicate values removed
Array.prototype.unique =
  function() {
    var a = [];
    var l = this.length;
    for(var i=0; i<l; i++) {
      for(var j=i+1; j<l; j++) {
        // If this[i] is found later in the array
        if (this[i] === this[j])
          j = ++i;
      }
      a.push(this[i]);
    }
    return a;
  };