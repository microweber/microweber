/*
 * Accordion 1.5 - jQuery menu widget
 *
 * Copyright (c) 2007 Jörn Zaefferer, Frank Marcia
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-accordion/
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * Revision: $Id: jquery.accordion.js 2951 2007-08-28 07:21:13Z joern.zaefferer $
 *
 */

(function($) {

$.ui = $.ui || {}

$.ui.accordion = {};
$.extend($.ui.accordion, {
	defaults: {
		selectedClass: "selected",
		alwaysOpen: true,
		animated: 'slide',
		event: "click",
		header: "a",
		autoheight: true
	},
	animations: {
		slide: function(settings, additions) {
			settings = $.extend({
				easing: "swing",
				duration: 300
			}, settings, additions);
			if ( !settings.toHide.size() ) {
				settings.toShow.animate({height: "show"}, {
					duration: settings.duration,
					easing: settings.easing,
					complete: settings.finished
				});
				return;
			}
			var hideHeight = settings.toHide.height(),
				showHeight = settings.toShow.height(),
				difference = showHeight / hideHeight;
			settings.toShow.css({ height: 0, overflow: 'hidden' }).show();
			settings.toHide.filter(":hidden").each(settings.finished).end().filter(":visible").animate({height:"hide"},{
				step: function(now){
					settings.toShow.height((hideHeight - (now)) * difference );
				},
				duration: settings.duration,
				easing: settings.easing,
				complete: settings.finished
			});
		},
		bounceslide: function(settings) {
			this.slide(settings, {
				easing: settings.down ? "bounceout" : "swing",
				duration: settings.down ? 1000 : 200
			});
		},
		easeslide: function(settings) {
			this.slide(settings, {
				easing: "easeinout",
				duration: 700
			})
		}
	}
});

$.fn.extend({
	nextUntil: function(expr) {
	    var match = [];
	
	    // We need to figure out which elements to push onto the array
	    this.each(function(){
	        // Traverse through the sibling nodes
	        for( var i = this.nextSibling; i; i = i.nextSibling ) {
	            // Make sure that we're only dealing with elements
	            if ( i.nodeType != 1 ) continue;
	
	            // If we find a match then we need to stop
	            if ( $.filter( expr, [i] ).r.length ) break;
	
	            // Otherwise, add it on to the stack
	            match.push( i );
	        }
	    });
	
	    return this.pushStack( match );
	},
	// the plugin method itself
	accordion: function(settings) {
		if ( !this.length )
			return this;
	
		// setup configuration
		settings = $.extend({}, $.ui.accordion.defaults, settings);
		
		if ( settings.navigation ) {
			var current = this.find("a").filter(function() { return this.href == location.href; });
			if ( current.length ) {
				if ( current.filter(settings.header).length ) {
					settings.active = current;
				} else {
					settings.active = current.parent().parent().prev();
					current.addClass("current");
				}
			}
		}
		
		// calculate active if not specified, using the first header
		var container = this,
			headers = container.find(settings.header),
			active = findActive(settings.active),
			running = 0;

		if ( settings.fillSpace ) {
			var maxHeight = this.parent().height();
			headers.each(function() {
				maxHeight -= $(this).outerHeight();
			});
			var maxPadding = 0;
			headers.nextUntil(settings.header).each(function() {
				maxPadding = Math.max(maxPadding, $(this).innerHeight() - $(this).height());
			}).height(maxHeight - maxPadding);
		} else if ( settings.autoheight ) {
			var maxHeight = 0;
			headers.nextUntil(settings.header).each(function() {
				maxHeight = Math.max(maxHeight, $(this).height());
			}).height(maxHeight);
		}

		headers
			.not(active || "")
			.nextUntil(settings.header)
			.hide();
		active.parent().andSelf().addClass(settings.selectedClass);
		
		
		function findActive(selector) {
			return selector != undefined
				? typeof selector == "number"
					? headers.filter(":eq(" + selector + ")")
					: headers.not(headers.not(selector))
				: selector === false
					? $("<div>")
					: headers.filter(":eq(0)");
		}
		
		function toggle(toShow, toHide, data, clickedActive, down) {
			var finished = function(cancel) {
				running = cancel ? 0 : --running;
				if ( running )
					return;
				// trigger custom change event
				container.trigger("change", data);
			};
			
			// count elements to animate
			running = toHide.size() == 0 ? toShow.size() : toHide.size();
			
			if ( settings.animated ) {
				if ( !settings.alwaysOpen && clickedActive ) {
					toShow.slideToggle(settings.animated);
					finished(true);
				} else {
					$.ui.accordion.animations[settings.animated]({
						toShow: toShow,
						toHide: toHide,
						finished: finished,
						down: down
					});
				}
			} else {
				if ( !settings.alwaysOpen && clickedActive ) {
					toShow.toggle();
				} else {
					toHide.hide();
					toShow.show();
				}
				finished(true);
			}
		}
		
		function clickHandler(event) {
			// called only when using activate(false) to close all parts programmatically
			if ( !event.target && !settings.alwaysOpen ) {
				active.toggleClass(settings.selectedClass);
				var toHide = active.nextUntil(settings.header);
				var toShow = active = $([]);
				toggle( toShow, toHide );
				return;
			}
			// get the click target
			var clicked = $(event.target);
			
			// due to the event delegation model, we have to check if one
			// of the parent elements is our actual header, and find that
			if ( clicked.parents(settings.header).length )
				while ( !clicked.is(settings.header) )
					clicked = clicked.parent();
			
			var clickedActive = clicked[0] == active[0];
			
			// if animations are still active, or the active header is the target, ignore click
			if(running || (settings.alwaysOpen && clickedActive) || !clicked.is(settings.header))
				return;

			// switch classes
			active.parent().andSelf().toggleClass(settings.selectedClass);
			if ( !clickedActive ) {
				clicked.parent().andSelf().addClass(settings.selectedClass);
			}

			// find elements to show and hide
			var toShow = clicked.nextUntil(settings.header),
				toHide = active.nextUntil(settings.header),
				data = [clicked, active, toShow, toHide],
				down = headers.index( active[0] ) > headers.index( clicked[0] );
			
			active = clickedActive ? $([]) : clicked;
			toggle( toShow, toHide, data, clickedActive, down );

			return !toShow.length;
		};
		function activateHandler(event, index) {
			// IE manages to call activateHandler on normal clicks
			if ( arguments.length == 1 )
				return;
			// call clickHandler with custom event
			clickHandler({
				target: findActive(index)[0]
			});
		};

		return container
			.bind(settings.event, clickHandler)
			.bind("activate", activateHandler);
	},
	activate: function(index) {
		return this.trigger('activate', [index]);
	}
});

})(jQuery);