jQuery.ui.spinner 1.20
jQuery.ui extension for a numeric spinner

Licensing
----------

Written by Brant Burnett <http://btburnett.com/> <mailto:btburnett3@gmail.com>
Copyright (c) 2009-2010 Brant Burnett
Dual licensed under the MIT or GPL Version 2 licenses.

jQuery.ui.spinner is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

Source Control
---------------

The root branch for jQuery.ui.spinner is hosted on GitHub at
<http://github.com/btburnett3/jquery.ui.spinner/>.

Overview
---------

jQuery.ui.spinner is based upon jQuery UI 1.8 <http://ui.jquery.com>, and provides a new
spinner widget that can be added to any text box.  It fully uses the theme settings created by the
ThemeRoller, and has been tested for compatibility with IE 6/7/8, Firefox 2/3, Safari 3.1, and
Opera 9.  Supports both mouse and keyboard input, and validates input directly into the text box.
It requires only minimal CSS to be supplied, mostly relying on the CSS built into the jQuery UI
framework.

I did a lot of experimenting to try to find the best method that would work across a variety of
web browswers while still using the jQuery UI theme framework rather than images.  I finally
settled on this method of spans with display: inline-block set on them, which is then
positioned next to the text box using absolute positioning, relative to an outer wrapper that
is relative positioned.  The only restriction I know of with this system is that if you put
a right margin on the text box, you must specify it in pixels.

To use, add the CSS found in ui.spinner.css to your jQuery UI CSS files, or link to it
separately.  Then include the ui.spinner.js or ui.spinner.min.js script file beneath your
include for jQuery UI.  Finally, during or after $(document).ready call $("#myinput").spinner()
to add the spinner to your text box.

Options
--------

The spinner also support a variety of options which can be passed in an object to the
constructor.  Page wide defaults can be assigned to $.ui.spinner.defaults.

	min (float)
		Minimum allowed value of the spinner.  If left with the default, null, there will be no
		minimum unless the input has a maximum length.  Then the minimum will be calculated.  For
		a textbox with a maximum length of 3 characters, the minimum will be -99, etc.
					
	max (float)
		Maximum allowed value of the spinner.  If left with the default, null, there will be no
		maximum unless the input has a maximum length.  Then the maximum will be calculated.  For
		a textbox with a maximum length of 3 characters, the maximum will be 999, etc.
		
	places (integer)
		Number of decimal places to display.  Defaults to use the number of places found in step.
				
	step (float)
		A positive number that the spinner should be incremented by when up is clicked or pressed.
		The spinner will be decremented by this amount for down.  Defaults to 1.
		
	largeStep (float)
		Used like step, but for when page up or page down are pressed to jump in larger
		amounts.  Defaults to 10.
		
	group (string)
		Grouping separator.  Would commonly be set to ',', defaults to ''.
	
	point (string)
		Decimal point character.  Defaults to '.'.
		
	prefix (string)
		Character prefix before the number.  Commonly used for currency symbols.  Defaults to ''.
		
	suffix (string)
		Character suffix after the number.  Commonly used for percentage signs.  Defaults to ''.
		
	className (string)
		Optional class name that should be applied to the container span that is created around
		the input and the buttons
		
	showOn (string)
		Defines when the spin buttons are visible
		'always': Spin buttons are always visible
		'focus': Spin buttons are only visible when the input has focus
		'hover': Spin buttons are only visible when the mouse is hovering over the input
		'both': Spin buttons are visible when the input has focus or is being hovered over
		
	width (integer)
		Width in pixels of the spinner.  Defaults to 16.
		
	increment (array or string)
		Controls the speed of the incremental spin when you hold a button or key down.  Can be set
		to 'fast' or 'slow' to use predefined speeds.  However, you can also supply an array of
		objects to control the speed increments, or null to stop continuous spinning.  The default
		is 'slow'. The objects in the array must have the following properties:
		
		count (integer)
			Number of times to increment at this speed before moving to the next speed.
		
		mult (integer)
			Number to multiply the current step by.  This increases the amount of each increment.
		
		delay (integer)
			Number of milliseconds to delay between each increment.
		
	mouseWheel (boolean)
		If true then mouse wheel events will be attached.  Defaults to true.
		
	allowNull (boolean)
		If true then the control will allow a blank (null) value.  Defaults to false.
	
	format ( function(num, places, element) )
		Function that returns a formatted number.  By default formats using the prefix, suffix,
		places, and point options.  Note that this may be called during initialization before
		the spinner is fully constructed.
		
		this
			options object, should be treated as read-only
		
		num (float)
			Number to be formatted
		
		places (integer)
			Number of decimal places to display
		
		element (jQuery)
			input element
			
		
	parse ( function(num, element) )
		Function that returns a number parsed from an input string.  Will only be called if the
		input is non-null.  By default parses using the prefix, suffix, and point options.
		
		this
			options object, should be treated as read-only
		
		num (string)
			Number to be parsed
		
		element (jQuery)
			input element
		
Example Option Usage
---------------------

0 - 100 incrementing by 2:
	$("#myinput").spinner({min: 0, max: 100, increment: 2});
	
Show on hover/focus only:
	$("#myinput").spinner({showOn: 'both'});
	
Other Commands
---------------

The spinner also support commands after they are created, using the standard UI widget method
of passing strings.

$("#myinput").spinner("value")
	Returns the integer value of the input.  Returns null if the input is blank.
	
$("#myinput").spinner("value", value)
	Sets the integer value of the input.  Still validates against min and max, null blanks the
	input if nulls are allowed.
	
$("#myinput").spinner("enable")
	Enables the spinner and the input
	
$("#myinput").spinner("disable")
	Disables the spinner and the input
	
$("#myinput").spinner("destroy")
	Destroys the spinner, restoring the input to its previous state
	
$("#myinput").spinner("increment")
	Increments the spinner, just like you hit the button
	
$("#myinput").spinner("decrement")
	Decrements the spinner, just like you hit the button

$("#myinput").spinner("showButtons", [immediate])
	Shows the buttons if they are hidden.  If immediate is passed as true, the show won't be
	animated.
	
$("#myinput").spinner("hideButtons", [immediate])
	Hides the buttons if they are visible.  If immediate is passed as true, the hide won't be
	animated.
	
Version History
----------------

1.20
	Updated for the UI 1.8 widget factory

1.10
	Added support for mouse wheel
	Added keypress filtering for numbers, special keys only
	Fixed input always focusing on spinner init (caused error in IE)
	Added event namespacing to improve destroy
	Added allowNull option
	Renamed increment option to step
	Will now load HTML5 min, max, and step attributes
	Now sets the maxlength of the input based on min and max
	Changed incremental speed increase to use an array, or be passed 'slow' and 'fast' options
	Changing options after the spinner is created now works
	Added support for decimal places and currency prefix/suffixes
	Added support for custom formatting

1.01
	Renamed from spinbuttons to spinner
	Implemented right/left and home/end keys per the DHTML Style Guide <http://dev.aol.com/dhtml_style_guide>
	Fixed destroy implementation
	Made setValue call change event on input

1.0
	Initial release