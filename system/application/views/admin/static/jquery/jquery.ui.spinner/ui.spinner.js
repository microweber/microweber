/** 
 * @license jQuery UI Spinner 1.20
 *
 * Copyright (c) 2009-2010 Brant Burnett
 * Dual licensed under the MIT or GPL Version 2 licenses.
 */
 (function($, undefined) {

var 
	// constants
	active = 'ui-state-active',
	hover = 'ui-state-hover',
	disabled = 'ui-state-disabled',
	
	keyCode = $.ui.keyCode,
	up = keyCode.UP,
	down = keyCode.DOWN,
	right = keyCode.RIGHT,
	left = keyCode.LEFT,
	pageUp = keyCode.PAGE_UP,
	pageDown = keyCode.PAGE_DOWN,
	home = keyCode.HOME,
	end = keyCode.END,
	
	msie = $.browser.msie,
	mouseWheelEventName = $.browser.mozilla ? 'DOMMouseScroll' : 'mousewheel',
	
	// namespace for events on input
	eventNamespace = '.uispinner', 
	
	// only these special keys will be accepted, all others will be ignored unless CTRL or ALT are pressed
	validKeys = [up, down, right, left, pageUp, pageDown, home, end, keyCode.BACKSPACE, keyCode.DELETE, keyCode.TAB],
	
	// stores the currently focused spinner
	// Note: due to oddities in the focus/blur events, this is part of a two-part system for confirming focus
	// this must set to the control, and the focus variable must be true
	// this is because hitting up/down arrows with mouse causes focus to change, but blur event for previous control doesn't fire
	focusCtrl;
	
$.widget('ui.spinner', {
	options: {
		min: null,
		max: null,
		allowNull: false,
		
		group: '',
		point: '.',
		prefix: '',
		suffix: '',
		places: null, // null causes it to detect the number of places in step
		
		defaultStep: 1, // real value is 'step', and should be passed as such.  This value is used to detect if passed value should override HTML5 attribute
		largeStep: 10,
		mouseWheel: true,
		increment: 'slow',		
		className: null,
		showOn: 'always',
		width: 16,
		upIconClass: "ui-icon-triangle-1-n",
		downIconClass: "ui-icon-triangle-1-s",
		
		format: function(num, places) {
			var options = this,
				regex = /(\d+)(\d{3})/,
				result = ((isNaN(num) ? 0 : Math.abs(num)).toFixed(places)) + '';
				
			for (result = result.replace('.', options.point); regex.test(result) && options.group; result=result.replace(regex, '$1'+options.group+'$2')) {};
			return (num < 0 ? '-' : '') + options.prefix + result + options.suffix;
		},
		
		parse: function(val) {
			var options = this;
			
			if (options.group == '.')
				val = val.replace('.', '');
			if (options.point != '.')
				val = val.replace(options.point, '.');
			return parseFloat(val.replace(/[^0-9\-\.]/g, ''));
		}
	},
	
	// * Widget fields *
	// curvalue - current value
	// places - currently effective number of decimal places
	// oWidth - original input width (used for destroy)
	// oMargin - original input right margin (used for destroy)
	// counter - number of spins at the current spin speed
	// incCounter - index within options.increment of the current spin speed
	// selfChange - indicates that change event is being fired by the widget, so don't reprocess input value
	// inputMaxLength - initial maxLength value on the input
	// focused - this spinner currently has the focus

	_create: function() {
		// shortcuts
		var self = this,
			input = self.element,
			type = input.attr('type');
			
		if (!input.is('input') || ((type != 'text') && (type != 'number'))) {
			console.error('Invalid target for ui.spinner');
			return;
		}
		
		self._procOptions(true);
		self._createButtons(input);

		if (!input.is(':enabled'))
			self.disable();
	},
	
	_createButtons: function(input) {
		function getMargin(margin) {
			// IE8 returns auto if no margin specified
			return margin == 'auto' ? 0 : parseInt(margin);
		}

		var self = this,
			options = self.options,
			className = options.className,
			buttonWidth = options.width,
			showOn = options.showOn,
			box = $.support.boxModel,
			height = input.outerHeight(),
			rightMargin = self.oMargin = getMargin(input.css('margin-right')), // store original width and right margin for later destroy
			wrapper = self.wrapper = input.css({ width: (self.oWidth = (box ? input.width() : input.outerWidth())) - buttonWidth, 
												 marginRight: rightMargin + buttonWidth, textAlign: 'right' })
				.after('<span class="ui-spinner ui-widget"></span>').next(),
			btnContainer = self.btnContainer = $(
				'<div class="ui-spinner-buttons">' + 
					'<div class="ui-spinner-up ui-spinner-button ui-state-default ui-corner-tr"><span class="ui-icon '+options.upIconClass+'">&nbsp;</span></div>' + 
					'<div class="ui-spinner-down ui-spinner-button ui-state-default ui-corner-br"><span class="ui-icon '+options.downIconClass+'">&nbsp;</span></div>' + 
				'</div>'),

			// object shortcuts
			upButton, downButton, buttons, icons,

			hoverDelay,
			hoverDelayCallback,
			
			// current state booleans
			hovered, inKeyDown, inSpecialKey, inMouseDown,
						
			// used to reverse left/right key directions
			rtl = input[0].dir == 'rtl';
		
		// apply className before doing any calculations because it could affect them
		if (className) wrapper.addClass(className);
		
		wrapper.append(btnContainer.css({ height: height, left: -buttonWidth-rightMargin,
			// use offset calculation to fix vertical position in Firefox
			top: (input.offset().top - wrapper.offset().top) + 'px' }));
		
		buttons = self.buttons = btnContainer.find('.ui-spinner-button');
		buttons.css({ width: buttonWidth - (box ? buttons.outerWidth() - buttons.width() : 0), height: height/2 - (box ? buttons.outerHeight() - buttons.height() : 0) });
		upButton = buttons[0];
		downButton = buttons[1];

		// fix icon centering
		icons = buttons.find('.ui-icon');
		icons.css({ marginLeft: (buttons.innerWidth() - icons.width()) / 2, marginTop:  (buttons.innerHeight() - icons.height()) / 2 });
		
		// set width of btnContainer to be the same as the buttons
		btnContainer.width(buttons.outerWidth());
		if (showOn != 'always')
			btnContainer.css('opacity', 0);
		
		/* Event Bindings */

		// bind hover events to show/hide buttons
		if (showOn == 'hover' || showOn == 'both')
			buttons.add(input)
				.bind('mouseenter' + eventNamespace, function() {
					setHoverDelay(function() {
						hovered = true;
						if (!self.focused || (showOn == 'hover')) // ignore focus flag if show on hover only
							self.showButtons();
					});
				})
				
				.bind('mouseleave' + eventNamespace, function hoverOut() {
					setHoverDelay(function() {
						hovered = false;
						if (!self.focused || (showOn == 'hover')) // ignore focus flag if show on hover only
							self.hideButtons();
					});
				});

	
		buttons.hover(function() {
					// ensure that both buttons have hover removed, sometimes they get left on
					self.buttons.removeClass(hover);
					
					if (!options.disabled)
						$(this).addClass(hover);
				}, function() {
					$(this).removeClass(hover);
				})
			.mousedown(mouseDown)
			.mouseup(mouseUp)
			.mouseout(mouseUp);
			
		if (msie)
			// fixes dbl click not firing second mouse down in IE
			buttons.dblclick(function() {
					if (!options.disabled) {
						// make sure any changes are posted
						self._change();
						self._doSpin((this === upButton ? 1 : -1) * options.step);
					}
					
					return false;
				}) 
				
				// fixes IE8 dbl click selection highlight
				.bind('selectstart', function() {return false;});
				
		input.bind('keydown' + eventNamespace, function(e) {
					var dir, large, limit,
						keyCode = e.keyCode; // shortcut for minimization
					if (e.ctrl || e.alt) return true; // ignore these events
					
					if (isSpecialKey(keyCode))
						inSpecialKey = true;
					
					if (inKeyDown) return false; // only one direction at a time, and suppress invalid keys
					
					switch (keyCode) {
						case up:
						case pageUp:
							dir = 1;
							large = keyCode == pageUp;
							break;
							
						case down:
						case pageDown:
							dir = -1;
							large = keyCode == pageDown;
							break;
							
						case right:
						case left:
							dir = (keyCode == right) ^ rtl ? 1 : -1;
							break;
							
						case home:
							limit = self.options.min;
							if (limit != null) self._setValue(limit);
							return false;
							
						case end:
							limit = self.options.max;
							limit = self.options.max;
							if (limit != null) self._setValue(limit);
							return false;
					}
					
					if (dir) { // only process if dir was set above
						if (!inKeyDown && !options.disabled) {
							keyDir = dir;
							
							$(dir > 0 ? upButton : downButton).addClass(active);
							inKeyDown = true;
							self._startSpin(dir, large);
						}
						
						return false;
					}
				})
				
			.bind('keyup' + eventNamespace, function(e) {
					if (e.ctrl || e.alt) return true; // ignore these events
					
					if (isSpecialKey(keyCode))
						inSpecialKey = false;
					
					switch (e.keyCode) {
						case up:
						case right:
						case pageUp:
						case down:
						case left:
						case pageDown:
							buttons.removeClass(active)
							self._stopSpin();
							inKeyDown = false;
							return false;
					}
				})
				
			.bind('keypress' + eventNamespace, function(e) {
					if (invalidKey(e.keyCode, e.charCode)) return false;
				})
				
			.bind('change' + eventNamespace, function() { self._change(); })
			
			.bind('focus' + eventNamespace, function() {
					function selectAll() {
						self.element.select();
					}

					msie ? selectAll() : setTimeout(selectAll, 0); // add delay for Chrome, but breaks IE8
					self.focused = true;
					focusCtrl = self;
					if (!hovered && (showOn == 'focus' || showOn == 'both')) // hovered will only be set if hover affects show
						self.showButtons();
				})
				
			.bind('blur' + eventNamespace, function() {
					self.focused = false;
					if (!hovered && (showOn == 'focus' || showOn == 'both')) // hovered will only be set if hover affects show
						self.hideButtons();
				});
				
		function isSpecialKey(keyCode) {
			for (var i=0; i<validKeys.length; i++) // predefined list of special keys
				if (validKeys[i] == keyCode) return true;
				
			return false;
		}
			
		function invalidKey(keyCode, charCode) {
			if (inSpecialKey) return false;				
			
			var ch = String.fromCharCode(charCode || keyCode),
				options = self.options;
				
			if ((ch >= '0') && (ch <= '9') || (ch == '-')) return false;
			if (((self.places > 0) && (ch == options.point))
				|| (ch == options.group)) return false;
						
			return true;
		}
		
		// used to delay start of hover show/hide by 100 milliseconds
		function setHoverDelay(callback) {
			if (hoverDelay) {
				// don't do anything if trying to set the same callback again
				if (callback === hoverDelayCallback) return;
				
				clearTimeout(hoverDelay);
			}
			
			hoverDelayCallback = callback;
			hoverDelay = setTimeout(execute, 100);
			
			function execute() {
				hoverDelay = 0;
				callback();
			}
		}
			
		function mouseDown() {
			if (!options.disabled) {
				var input = self.element[0],
					dir = (this === upButton ? 1 : -1);
					
				input.focus();
				input.select();
				$(this).addClass(active);
				
				inMouseDown = true;
				self._startSpin(dir);
			}

			return false;
		}
		
		function mouseUp() {
			if (inMouseDown) {
				$(this).removeClass(active);
				self._stopSpin();
				inMouseDown = false;
			}
			return false;
		}
	},
	
	_procOptions: function(init) {
		var self = this,
			input = self.element,
			options = self.options,
			min = options.min,
			max = options.max,
			step = options.step,
			places = options.places,
			maxlength = -1, temp;
			
		// setup increment based on speed string
		if (options.increment == 'slow')
			options.increment = [{count: 1, mult: 1, delay: 250},
								 {count: 3, mult: 1, delay: 100},
								 {count: 0, mult: 1, delay: 50}];
		else if (options.increment == 'fast')
			options.increment = [{count: 1, mult: 1, delay: 250},
								 {count: 19, mult: 1, delay: 100},
								 {count: 80, mult: 1, delay: 20},
								 {count: 100, mult: 10, delay: 20},
								 {count: 0, mult: 100, delay: 20}];

		if ((min == null) && ((temp = input.attr('min')) != null))
			min = parseFloat(temp);
		
		if ((max == null) && ((temp = input.attr('max')) != null))
			max = parseFloat(temp);
		
		if (!step && ((temp = input.attr('step')) != null))
			if (temp != 'any') {
				step = parseFloat(temp);
				options.largeStep *= step;
			}
		options.step = step = step || options.defaultStep;

		// Process step for decimal places if none are specified
		if ((places == null) && ((temp = step + '').indexOf('.') != -1))
			places = temp.length - temp.indexOf('.') - 1;
		self.places = places;
		
		if ((max != null) && (min != null)) {
			// ensure that min is less than or equal to max
			if (min > max) min = max;
			
			// set maxlength based on min/max
			maxlength = Math.max(Math.max(maxlength, options.format(max, places, input).length), options.format(min, places, input).length);
		}
		
		// only lookup input maxLength on init
		if (init) self.inputMaxLength = input[0].maxLength;
		temp = self.inputMaxLength;
			
		if (temp > 0) {
			maxlength = maxlength > 0 ? Math.min(temp, maxlength) : temp;
			temp = Math.pow(10, maxlength) - 1;
			if ((max == null) || (max > temp))
				max = temp;
			temp = -(temp + 1) / 10 + 1;
			if ((min == null) || (min < temp))
				min = temp;
		}
		
		if (maxlength > 0)
			input.attr('maxlength', maxlength);
					
		options.min = min;
		options.max = max;
		
		// ensures that current value meets constraints
		self._change();
		
		input.unbind(mouseWheelEventName + eventNamespace);
		if (options.mouseWheel)
			input.bind(mouseWheelEventName + eventNamespace, self._mouseWheel);
	},
		
	_mouseWheel: function(e) {
		var self = $.data(this, 'spinner');
		if (!self.options.disabled && self.focused && (focusCtrl === self)) {
			// make sure changes are posted
			self._change();
			self._doSpin(((e.wheelDelta || -e.detail) > 0 ? 1 : -1) * self.options.step);
			return false;
		}
	},
	
	// sets an interval to call the _spin function
	_setTimer: function(delay, dir, large) {
		var self = this;
		self._stopSpin();
		self.timer = setInterval(fire, delay);
		
		function fire() {
			self._spin(dir, large);
		}
	},
	
	// stops the spin timer
	_stopSpin: function() {
		if (this.timer) {
			clearInterval(this.timer);
			this.timer = 0;
		}
	},
	
	// performs first step, and starts the spin timer if increment is set
	_startSpin: function(dir, large) {
		// shortcuts
		var self = this,
			options = self.options,
			increment = options.increment;
			
		// make sure any changes are posted
		self._change();
		self._doSpin(dir * (large ? self.options.largeStep : self.options.step));
		
		if (increment && increment.length > 0) {		
			self.counter = 0;
			self.incCounter = 0;
			self._setTimer(increment[0].delay, dir, large);
		}
	},
	
	// called by timer for each step in the spin
	_spin: function(dir, large) {
		// shortcuts
		var self = this,
			increment = self.options.increment,
			curIncrement = increment[self.incCounter];
		
		self._doSpin(dir * curIncrement.mult * (large ? self.options.largeStep : self.options.step));
		self.counter++;

		if ((self.counter > curIncrement.count) && (self.incCounter < increment.length-1)) {
			self.counter = 0;
			curIncrement = increment[++self.incCounter];
			self._setTimer(curIncrement.delay, dir, large);
		}
	},
	
	// actually spins the timer by a step
	_doSpin: function(step) {
		// shortcut
		var self = this,
			value = self.curvalue;
			
		if (value == null)
			value = (step > 0 ? self.options.min : self.options.max) || 0;
		
		self._setValue(value + step);
	},
	
	// Parse the value currently in the field
	_parseValue: function() {
		var value = this.element.val();
		return value ? this.options.parse(value, this.element) : null;
	},
	
	_validate: function(value) {
		var options = this.options,
			min = options.min,
			max = options.max;

		if ((value == null) && !options.allowNull)
			value = this.curvalue != null ? this.curvalue : min || max || 0; // must confirm not null in case just initializing and had blank value

		if ((max != null) && (value > max))
			return max;
		else if ((min != null) && (value < min))
			return min;
		else
			return value;
	},
	
	_change: function() {
		var self = this, // shortcut
			value = self._parseValue(),
			min = self.options.min,
			max = self.options.max;
			
		// don't reprocess if change was self triggered
		if (!self.selfChange) {
			if (isNaN(value))
				value = self.curvalue;

			self._setValue(value, true);
		}
	},
	
	// overrides _setData to force option parsing
	_setOption: function(key, value) {
		$.Widget.prototype._setOption.call(this, key, value);
		this._procOptions();
	},
	
	increment: function() {
		this._doSpin(this.options.step);
	},
	
	decrement: function() {
		this._doSpin(-this.options.step);
	},
	
	showButtons: function(immediate) {
		var btnContainer = this.btnContainer.stop();
		if (immediate)
			btnContainer.css('opacity', 1);
		else
			btnContainer.fadeTo('fast', 1);
	},
	
	hideButtons: function(immediate) {
		var btnContainer = this.btnContainer.stop();
		if (immediate)
			btnContainer.css('opacity', 0);
		else
			btnContainer.fadeTo('fast', 0);
		this.buttons.removeClass(hover);
	},
	
	// Set the value directly
	_setValue: function(value, suppressFireEvent) {
		var self = this;
		
		self.curvalue = value = self._validate(value);
		self.element.val(value != null ? 
			self.options.format(value, self.places, self.element) :
			'');
		
		if (!suppressFireEvent) {
			self.selfChange = true;
			self.element.change();
			self.selfChange = false;
		}
	},

	// Set or retrieve the value
	value: function(newValue) {
		if (arguments.length) {
			this._setValue(newValue);
			
			// maintains chaining
			return this.element;
		}

		return this.curvalue;
	},

	enable: function() {
		this.buttons.removeClass(disabled);
		this.element[0].disabled = false;
		$.Widget.prototype.enable.call(this);
	},
	
	disable: function() {
		this.buttons.addClass(disabled)
			// in case hover class got left on
			.removeClass(hover);
			
		this.element[0].disabled = true;
		$.Widget.prototype.disable.call(this);
	},
	
	destroy: function(target) {
		this.wrapper.remove();
		this.element.unbind(eventNamespace).css({ width: this.oWidth, marginRight: this.oMargin });
		
		$.Widget.prototype.destroy.call(this);
	}	
});

})( jQuery );