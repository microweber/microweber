/****************************************************************
 *                                                              *
 *  JQuery Curvy Corners by Mike Jolley                         *
 *  http://blue-anvil.com                                       *
 *  http://code.google.com/p/jquerycurvycorners/                *
 *  ==========================================================  *
 *                                                              *
 *  Version 2.1.1 (Based on CC 2.1 beta)                          *
 *                                                              *
 *  Original by: Terry Riegel, Cameron Cooke and Tim Hutchison  *
 *  Website: http://www.curvycorners.net                        *
 *                                                              *
 *  This library is free software; you can redistribute         *
 *  it and/or modify it under the terms of the GNU              *
 *  Lesser General Public License as published by the           *
 *  Free Software Foundation; either version 2.1 of the         *
 *  License, or (at your option) any later version.             *
 *                                                              *
 *  This library is distributed in the hope that it will        *
 *  be useful, but WITHOUT ANY WARRANTY; without even the       *
 *  implied warranty of MERCHANTABILITY or FITNESS FOR A        *
 *  PARTICULAR PURPOSE. See the GNU Lesser General Public       *
 *  License for more details.                                   *
 *                                                              *
 *  You should have received a copy of the GNU Lesser           *
 *  General Public License along with this library;             *
 *  Inc., 59 Temple Place, Suite 330, Boston,                   *
 *  MA 02111-1307 USA                                           *
 *                                                              *
 ****************************************************************/

/*
Usage:
	To use this plugin just apply borders via CSS Rules and include this plugin - it will automatically detect styles and apply corners.
	
	Opera and Chrome support rounded corners via border-radius
	
	Safari and Mozilla support rounded borders via -webkit-border-radius and -moz-border-radius
		
	IE (any version) does not support border-radius - this is all we need to support.
		
	So to make curvycorners work with any major browser simply add the following CSS declarations:
	
	.round { 
		border-radius: 3px;
		-webkit-border-radius: 3px;
		-moz-border-radius: 3px;
	}
	
	----
	
	If you don't want to use the above method, you can still use the direct syntax if you want:

		$('.myBox').corner();
		
	The script will still use border-radius for those which support it.
*/  
(function($) { 

	// object that parses border-radius properties for a box
	function curvyCnrSpec(selText) {
		this.selectorText = selText;
		this.tlR = this.trR = this.blR = this.brR = 0;
		this.tlu = this.tru = this.blu = this.bru = "";
		this.antiAlias = true; // default true
	};
	curvyCnrSpec.prototype.setcorner = function(tb, lr, radius, unit) {
		if (!tb) { // no corner specified
			this.tlR = this.trR = this.blR = this.brR = parseInt(radius);
			this.tlu = this.tru = this.blu = this.bru = unit;
		} else { // corner specified
			propname = tb.charAt(0) + lr.charAt(0);
			this[propname + 'R'] = parseInt(radius);
			this[propname + 'u'] = unit;
		}
	};
	curvyCnrSpec.prototype.get = function(prop) {
		if (/^(t|b)(l|r)(R|u)$/.test(prop)) return this[prop];
		if (/^(t|b)(l|r)Ru$/.test(prop)) {
			var pname = prop.charAt(0) + prop.charAt(1);
			return this[pname + 'R'] + this[pname + 'u'];
		}
		if (/^(t|b)Ru?$/.test(prop)) {
			var tb = prop.charAt(0);
			tb += this[tb + 'lR'] > this[tb + 'rR'] ? 'l' : 'r';
			var retval = this[tb + 'R'];
			if (prop.length === 3 && prop.charAt(2) === 'u')
		  		retval += this[tb = 'u'];
			return retval;
		}
		throw new Error('Don\'t recognize property ' + prop);
	};
	curvyCnrSpec.prototype.radiusdiff = function(tb) {
		if (tb !== 't' && tb !== 'b') throw new Error("Param must be 't' or 'b'");
		return Math.abs(this[tb + 'lR'] - this[tb + 'rR']);
	};
	curvyCnrSpec.prototype.setfrom = function(obj) {
		this.tlu = this.tru = this.blu = this.bru = 'px'; // default to px
		if ('tl' in obj) this.tlR = obj.tl.radius;
		if ('tr' in obj) this.trR = obj.tr.radius;
		if ('bl' in obj) this.blR = obj.bl.radius;
		if ('br' in obj) this.brR = obj.br.radius;
		if ('antiAlias' in obj) this.antiAlias = obj.antiAlias;
	};
	curvyCnrSpec.prototype.cloneOn = function(box) { // not needed by IE
		var props = ['tl', 'tr', 'bl', 'br'];
		var converted = 0;
		var i, propu;	
		for (i in props) if (!isNaN(i)) {
			propu = this[props[i] + 'u'];
			if (propu !== '' && propu !== 'px') {
				converted = new curvyCnrSpec;
				break;
			}
		}
		if (!converted)
			converted = this; // no need to clone
		else {
			var propi, propR, save = curvyBrowser.get_style(box, 'left');
			for (i in props) if (!isNaN(i)) {
				propi = props[i];
				propu = this[propi + 'u'];
				propR = this[propi + 'R'];
				if (propu !== 'px') {
					var save = box.style.left;
					box.style.left = propR + propu;
					propR = box.style.pixelLeft;
					box.style.left = save;
				}
				converted[propi + 'R'] = propR;
				converted[propi + 'u'] = 'px';
			}
			box.style.left = save;
		}
		return converted;
	};
	curvyCnrSpec.prototype.radiusSum = function(tb) {
		if (tb !== 't' && tb !== 'b') throw new Error("Param must be 't' or 'b'");
		return this[tb + 'lR'] + this[tb + 'rR'];
	};
	curvyCnrSpec.prototype.radiusCount = function(tb) {
		var count = 0;
		if (this[tb + 'lR']) ++count;
		if (this[tb + 'rR']) ++count;
		return count;
	};
	curvyCnrSpec.prototype.cornerNames = function() {
		var ret = [];
		if (this.tlR) ret.push('tl');
		if (this.trR) ret.push('tr');
		if (this.blR) ret.push('bl');
		if (this.brR) ret.push('br');
		return ret;
	};
	
	if (typeof redrawList === 'undefined') redrawList = new Array;
	
	$.fn.corner = function(options) {
		
		// Check for Native Round Corners
		var nativeCornersSupported = false;
		var checkWebkit, checkMozilla, checkStandard;
		try {	checkWebkit = (document.body.style.WebkitBorderRadius !== undefined);	} catch(err) {}
		try {	checkMozilla = (document.body.style.MozBorderRadius !== undefined);	} catch(err) {}
		try {	checkStandard = (document.body.style.BorderRadius !== undefined);	} catch(err) {}		
		if (checkWebkit || checkMozilla || checkStandard) nativeCornersSupported = true;
		
		if (options instanceof curvyCnrSpec) {
			settings = options;
		}
		else {
		
			var options = jQuery.extend({
				tl: { radius: 8 },
				tr: { radius: 8 },
				bl: { radius: 8 },
				br: { radius: 8 },
				antiAlias: true
			}, options);
			
			var settings = new curvyCnrSpec(this);
			settings.setfrom(options);
		
		}
		
  		// Apply the corners to the passed object!
		function curvyObject()
		{				
			// Setup Globals
			this.box              = arguments[1];
			this.settings         = arguments[0];
			var $$ 						= $(this.box);
			var boxDisp;
			
			this.masterCorners 			= new Array();
			//this.contentDIV 				= null;			
			this.topContainer = this.bottomContainer = this.shell = boxDisp = null;
		
			// Get CSS of box and define vars
			var boxWidth = $$.innerWidth(); // Does not include border width

			if ($$.is('table'))
				throw new Error("You cannot apply corners to " + this.box.tagName + " elements.", "Error");
			
			// try to handle attempts to style inline elements
			if ($$.css('display') === 'inline') {
				$$.css('display', 'inline-block');
			}
			
			// all attempts have failed
			
			if (!boxWidth) {
				this.applyCorners = function() {}; // make the error harmless
				return;
			}
			if (arguments[0] instanceof curvyCnrSpec) {
				this.spec = arguments[0].cloneOn(this.box); // convert non-pixel units
			} else {
				this.spec = new curvyCnrSpec('');
				this.spec.setfrom(this.settings); // no need for unit conversion, use settings param. directly
			}
			
			// Get box formatting details
			var borderWidth     = $$.css("borderTopWidth") ? $$.css("borderTopWidth") : 0;
			var borderWidthB    = $$.css("borderBottomWidth") ? $$.css("borderBottomWidth") : 0;
			var borderWidthL    = $$.css("borderLeftWidth") ? $$.css("borderLeftWidth") : 0;
			var borderWidthR    = $$.css("borderRightWidth") ? $$.css("borderRightWidth") : 0;
			var borderColour    = $$.css("borderTopColor");
			var borderColourB   = $$.css("borderBottomColor"); 
			var borderColourL   = $$.css("borderLeftColor"); 
			var borderColourR   = $$.css("borderRightColor"); 
			var borderStyle     = $$.css("borderTopStyle");
			var borderStyleB    = $$.css("borderBottomStyle");
			var borderStyleL    = $$.css("borderLeftStyle");
			var borderStyleR    = $$.css("borderRightStyle");
			
			var boxColour       = $$.css("backgroundColor");
			var backgroundImage = $$.css("backgroundImage");		
			var backgroundRepeat= $$.css("backgroundRepeat");
				
			var backgroundPosX, backgroundPosY;
			
			backgroundPosX  = $$.css("backgroundPositionX") ? $$.css("backgroundPositionX") : 0;
			backgroundPosY  = $$.css("backgroundPositionY") ? $$.css("backgroundPositionY") : 0;

			var boxPosition     = $$.css("position");
			var topPadding      = $$.css("paddingTop");
			var bottomPadding   = $$.css("paddingBottom");
			var leftPadding     = $$.css("paddingLeft");
			var rightPadding    = $$.css("paddingRight");
			var border          = $$.css("border");
			var filter = jQuery.browser.version > 7 && $.browser.msie ? $$.css("filter") : null; // IE8 bug fix
			
			var topMaxRadius    = this.spec.get('tR');
			var botMaxRadius    = this.spec.get('bR');
			
			var styleToNPx = function(val) {
				if (typeof val === 'number') return val;
				if (typeof val !== 'string') throw new Error('unexpected styleToNPx type ' + typeof val);
				var matches = /^[-\d.]([a-z]+)$/.exec(val);
				if (matches && matches[1] != 'px') throw new Error('Unexpected unit ' + matches[1]);
				if (isNaN(val = parseInt(val))) val = 0;
				return val;
			};
			var min0Px = function(val) {
				return val <= 0 ? "0" : val + "px";
			};
			
			// Set formatting properties
			try {
				this.borderWidth     = styleToNPx(borderWidth);
				this.borderWidthB    = styleToNPx(borderWidthB);
				this.borderWidthL    = styleToNPx(borderWidthL);
				this.borderWidthR    = styleToNPx(borderWidthR);
				this.boxColour       = curvyObject.format_colour(boxColour);
				this.topPadding      = styleToNPx(topPadding);
				this.bottomPadding   = styleToNPx(bottomPadding);
				this.leftPadding     = styleToNPx(leftPadding);
				this.rightPadding    = styleToNPx(rightPadding);
				this.boxWidth        = boxWidth;
				this.boxHeight       = $$.innerHeight(); // No border
				this.borderColour    = curvyObject.format_colour(borderColour);
				this.borderColourB   = curvyObject.format_colour(borderColourB);
				this.borderColourL   = curvyObject.format_colour(borderColourL);
				this.borderColourR   = curvyObject.format_colour(borderColourR);
				this.borderString    = this.borderWidth + "px" + " " + borderStyle + " " + this.borderColour;
				this.borderStringB   = this.borderWidthB + "px" + " " + borderStyleB + " " + this.borderColourB;
				this.borderStringL   = this.borderWidthL + "px" + " " + borderStyleL + " " + this.borderColourL;
				this.borderStringR   = this.borderWidthR + "px" + " " + borderStyleR + " " + this.borderColourR;
				this.backgroundImage = (backgroundImage != "none" && backgroundImage!="initial") ? backgroundImage : "";
				this.backgroundRepeat= backgroundRepeat;
			}
			catch(e) {}
			
			var clientHeight = this.boxHeight;
			var clientWidth = boxWidth; // save it as it gets trampled on later
			if ($.browser.opera) {
				backgroundPosX = styleToNPx(backgroundPosX);
				backgroundPosY = styleToNPx(backgroundPosY);
				if (backgroundPosX) {
					var t = clientWidth + this.borderWidthL + this.borderWidthR;
					if (backgroundPosX > t) backgroundPosX = t;
					backgroundPosX = (t / backgroundPosX * 100) + '%'; // convert to percentage
				}
				if (backgroundPosY) {
					var t = clientHeight + this.borderWidth + this.borderWidthB;
					if (backgroundPosY > t) backgroundPosY = t;
					backgroundPosY = (t / backgroundPosY * 100) + '%'; // convert to percentage
				}
			}

			// Create content container
			this.contentContainer = document.createElement("div");
			if (filter) this.contentContainer.style.filter = filter; // IE8 bug fix
			while (this.box.firstChild) this.contentContainer.appendChild(this.box.removeChild(this.box.firstChild));
			
			if (boxPosition != "absolute") $$.css("position", "relative");
			this.box.style.padding = '0';
			this.box.style.border = this.box.style.backgroundImage = 'none';
			this.box.style.backgroundColor = 'transparent';
			
			this.box.style.width   = (clientWidth + this.borderWidthL + this.borderWidthR) + 'px';
			this.box.style.height  = (clientHeight + this.borderWidth + this.borderWidthB) + 'px';
			
			// Ok we add an inner div to actually put things into this will allow us to keep the height
			
			var newMainContainer = document.createElement("div");
			$(newMainContainer).css({
				width: clientWidth + 'px',
				'padding':			"0",
				position:			"absolute", 
				height:				min0Px(clientHeight + this.borderWidth + this.borderWidthB - topMaxRadius - botMaxRadius),
				top:				topMaxRadius + "px",
				left:				"0",
				'backgroundColor':	boxColour,
				'backgroundImage':	this.backgroundImage,
				'backgroundRepeat':	this.backgroundRepeat,
				'direction':		'ltr'
			});
			
			if (filter) $(newMainContainer).css('filter', 'filter'); // IE8 bug fix

			if (this.borderWidthL)
				$(newMainContainer).css('borderLeft', this.borderStringL);
			if (this.borderWidth && !topMaxRadius)
				$(newMainContainer).css('borderTop', this.borderString);
			if (this.borderWidthR)
				$(newMainContainer).css('borderRight', this.borderStringR);
			if (this.borderWidthB && !botMaxRadius)
				$(newMainContainer).css('borderBottom', this.borderStringB);
				
			this.shell = this.box.appendChild(newMainContainer);
			
			boxWidth = $(this.shell).css("width");
			
			if (boxWidth === "" || boxWidth === "auto" || boxWidth.indexOf("%") !== -1) throw Error('Shell width is ' + boxWidth);
			
			this.boxWidth = (boxWidth != "" && boxWidth != "auto" && boxWidth.indexOf("%") == -1) ? parseInt(boxWidth) : $(this.shell).width();
			
			this.applyCorners = function() {
				/*
				Set up background offsets. This may need to be delayed until
				the background image is loaded.
				*/
				this.backgroundPosX = this.backgroundPosY = 0;
				if (this.backgroundObject) {
					var bgOffset = function(style, imglen, boxlen) {
						if (style === 0) return 0;
						var retval;
						if (style === 'right' || style === 'bottom') return boxlen - imglen;
						if (style === 'center') return (boxlen - imglen) / 2;
						if (style.indexOf('%') > 0) return (boxlen - imglen) / (100 / parseInt(style));
						return styleToNPx(style);
					};
					this.backgroundPosX  = bgOffset(backgroundPosX, this.backgroundObject.width, clientWidth);
					this.backgroundPosY  = bgOffset(backgroundPosY, this.backgroundObject.height, clientHeight);
				}
				else if (this.backgroundImage) {
					this.backgroundPosX = styleToNPx(backgroundPosX);
					this.backgroundPosY = styleToNPx(backgroundPosY);
				}
				/*
				Create top and bottom containers.
				These will be used as a parent for the corners and bars.
				*/
				// Build top bar only if a top corner is to be drawn
				if (topMaxRadius) {
					newMainContainer = document.createElement("div");
					
					$(newMainContainer).css({
						width: 				this.boxWidth + "px",
						'fontSize':			"1px",
						overflow:			"hidden", 
						position:			"absolute", 
						'paddingLeft':		this.borderWidth + "px",
						'paddingRight':		this.borderWidth + "px",						
						height:				topMaxRadius + "px",
						top:				-topMaxRadius + "px",
						left:				-this.borderWidthL + "px"
					});					
					this.topContainer = this.shell.appendChild(newMainContainer);
				}
				// Build bottom bar only if a bottom corner is to be drawn
				if (botMaxRadius) {
					var newMainContainer = document.createElement("div");
					
					$(newMainContainer).css({
						width: 				this.boxWidth + "px",
						'fontSize':			"1px",
						overflow:			"hidden", 
						position:			"absolute", 
						'paddingLeft':		this.borderWidthB + "px",
						'paddingRight':		this.borderWidthB + "px",					
						height:				botMaxRadius + "px",
						bottom:				-botMaxRadius + "px",
						left:				-this.borderWidthL + "px"
					});
					this.bottomContainer = this.shell.appendChild(newMainContainer);
				}
			
				var corners = this.spec.cornerNames();  // array of available corners
			
				/*
				Loop for each corner
				*/
				for (var i in corners) if (!isNaN(i)) {
					// Get current corner type from array
					var cc = corners[i];
					var specRadius = this.spec[cc + 'R'];
					// Has the user requested the currentCorner be round?
					// Code to apply correct color to top or bottom
					var bwidth, bcolor, borderRadius, borderWidthTB;
					if (cc == "tr" || cc == "tl") {
						bwidth = this.borderWidth;
						bcolor = this.borderColour;
						borderWidthTB = this.borderWidth;
					} else {
						bwidth = this.borderWidthB;
						bcolor = this.borderColourB;
						borderWidthTB = this.borderWidthB;
					}
					borderRadius = specRadius - borderWidthTB;
					
					var newCorner = document.createElement("div");
					
					$(newCorner).css({
						position:"absolute",
						"font-size":"1px", 
						overflow:"hidden"
					}).height(this.spec.get(cc + 'Ru')).width(this.spec.get(cc + 'Ru'));
					
					// THE FOLLOWING BLOCK OF CODE CREATES A ROUNDED CORNER
					// ---------------------------------------------------- TOP
					var intx, inty, outsideColour;
					var trans = filter ? parseInt(/alpha\(opacity.(\d+)\)/.exec(filter)[1]) : 100; // IE8 bug fix
					// Cycle the x-axis
					for (intx = 0; intx < specRadius; ++intx) {
						// Calculate the value of y1 which identifies the pixels inside the border
						var y1 = (intx + 1 >= borderRadius) ? -1 : Math.floor(Math.sqrt(Math.pow(borderRadius, 2) - Math.pow(intx + 1, 2))) - 1;
						// Calculate y2 and y3 only if there is a border defined
						if (borderRadius != specRadius) {
							var y2 = (intx >= borderRadius) ? -1 : Math.ceil(Math.sqrt(Math.pow(borderRadius, 2) - Math.pow(intx, 2)));
							var y3 = (intx + 1 >= specRadius) ? -1 : Math.floor(Math.sqrt(Math.pow(specRadius, 2) - Math.pow((intx+1), 2))) - 1;
						}
						// Calculate y4
						var y4 = (intx >= specRadius) ? -1 : Math.ceil(Math.sqrt(Math.pow(specRadius, 2) - Math.pow(intx, 2)));
						// Draw bar on inside of the border with foreground colour
						if (y1 > -1) this.drawPixel(intx, 0, this.boxColour, trans, (y1 + 1), newCorner, true, specRadius);
						// Draw border/foreground antialiased pixels and border only if there is a border defined
						if (borderRadius != specRadius) {
							// Cycle the y-axis
							if (this.spec.antiAlias) {
								for (inty = y1 + 1; inty < y2; ++inty) {
									// For each of the pixels that need anti aliasing between the foreground and border colour draw single pixel divs
									if (this.backgroundImage != "") {
										var borderFract = curvyObject.pixelFraction(intx, inty, borderRadius) * 100;
										this.drawPixel(intx, inty, bcolor, trans, 1, newCorner, borderFract >= 30, specRadius);
									}
									else if (this.boxColour !== 'transparent') {
										var pixelcolour = curvyObject.BlendColour(this.boxColour, bcolor, curvyObject.pixelFraction(intx, inty, borderRadius));
										this.drawPixel(intx, inty, pixelcolour, trans, 1, newCorner, false, specRadius);
									}
									else this.drawPixel(intx, inty, bcolor, trans >> 1, 1, newCorner, false, specRadius);
								}
								// Draw bar for the border
								if (y3 >= y2) {
									if (y2 == -1) y2 = 0;
									this.drawPixel(intx, y2, bcolor, trans, (y3 - y2 + 1), newCorner, false, 0);
								}
								outsideColour = bcolor;  // Set the colour for the outside AA curve
								inty = y3;               // start_pos - 1 for y-axis AA pixels
							}
							else { // no antiAlias
								if (y3 > y1) { // NB condition was >=, changed to avoid zero-height divs
									this.drawPixel(intx, (y1 + 1), bcolor, trans, (y3 - y1), newCorner, false, 0);
								}
							}
						}
						else {
							outsideColour = this.boxColour;  // Set the colour for the outside curve
							inty = y1;               // start_pos - 1 for y-axis AA pixels
						}
						// Draw aa pixels?
						if (this.spec.antiAlias && this.boxColour !== 'transparent') {
							// Cycle the y-axis and draw the anti aliased pixels on the outside of the curve
							while (++inty < y4) {
								// For each of the pixels that need anti aliasing between the foreground/border colour & background draw single pixel divs
								this.drawPixel(intx, inty, outsideColour, (curvyObject.pixelFraction(intx, inty , specRadius) * trans), 1, newCorner, borderWidthTB <= 0, specRadius);
							}
						}
					}
					// END OF CORNER CREATION
					// ---------------------------------------------------- END
				
					/*
					Now we have a new corner we need to reposition all the pixels unless
					the current corner is the bottom right.
					*/
					// Loop through all children (pixel bars)
					for (var t = 0, k = newCorner.childNodes.length; t < k; ++t) {
						// Get current pixel bar
						var pixelBar = newCorner.childNodes[t];
						// Get current top and left properties
						var pixelBarTop    = parseInt($(pixelBar).css('top'));
						var pixelBarLeft   = parseInt($(pixelBar).css('left'));
						var pixelBarHeight = parseInt($(pixelBar).css('height'));
						// Reposition pixels
						if (cc == "tl" || cc == "bl") {
							$(pixelBar).css('left', (specRadius - pixelBarLeft - 1) + "px"); // Left
						}
						if (cc == "tr" || cc == "tl"){
							$(pixelBar).css('top', (specRadius - pixelBarHeight - pixelBarTop) + "px"); // Top
						}
						$(pixelBar).css('backgroundRepeat', this.backgroundRepeat);
	
						if (this.backgroundImage) switch(cc) {
							case "tr":
								$(pixelBar).css('backgroundPosition',(this.backgroundPosX - this.borderWidthL + specRadius - clientWidth - pixelBarLeft) + "px " + (this.backgroundPosY + pixelBarHeight + pixelBarTop + this.borderWidth - specRadius) + "px");
							break;
							case "tl":
								$(pixelBar).css('backgroundPosition',(this.backgroundPosX - specRadius + pixelBarLeft + 1 + this.borderWidthL) + "px " + (this.backgroundPosY - specRadius + pixelBarHeight + pixelBarTop + this.borderWidth) + "px");
							break;
							case "bl":
								$(pixelBar).css('backgroundPosition',(this.backgroundPosX - specRadius + pixelBarLeft + 1 + this.borderWidthL) + "px " + (this.backgroundPosY - clientHeight - this.borderWidth + (!jQuery.support.boxModel ? pixelBarTop : -pixelBarTop) + specRadius) + "px");
							break;
							case "br":
								// Quirks mode on?
								if (!jQuery.support.boxModel) {
									$(pixelBar).css('backgroundPosition',(this.backgroundPosX - this.borderWidthL - clientWidth + specRadius - pixelBarLeft) + "px " + (this.backgroundPosY - clientHeight - this.borderWidth + pixelBarTop + specRadius) + "px");
								} else {
									$(pixelBar).css('backgroundPosition',(this.backgroundPosX - this.borderWidthL - clientWidth + specRadius - pixelBarLeft) + "px " + (this.backgroundPosY - clientHeight - this.borderWidth + specRadius - pixelBarTop) + "px");
								}
							//break;
						}
					}
				
					// Position the container
					switch (cc) {
						case "tl":
							$(newCorner).css('top', newCorner.style.left = "0");
							this.topContainer.appendChild(newCorner);
						break;
						case "tr":
							$(newCorner).css('top', newCorner.style.right = "0");
							this.topContainer.appendChild(newCorner);
						break;
						case "bl":
							$(newCorner).css('bottom', newCorner.style.left = "0");
							this.bottomContainer.appendChild(newCorner);
						break;
						case "br":
							$(newCorner).css('bottom', newCorner.style.right = "0");
							this.bottomContainer.appendChild(newCorner);
						//break;
					}
				}
			
				/*
				The last thing to do is draw the rest of the filler DIVs.
				*/
				
				// Find out which corner has the bigger radius and get the difference amount
				var radiusDiff = {
					t : this.spec.radiusdiff('t'),
					b : this.spec.radiusdiff('b')
				};
				
				for (z in radiusDiff) {
					if (typeof z === 'function') continue; // for prototype, mootools frameworks
					if (!this.spec.get(z + 'R')) continue; // no need if no corners
					if (radiusDiff[z]) {
						// Get the type of corner that is the smaller one
						var smallerCornerType = (this.spec[z + "lR"] < this.spec[z + "rR"]) ? z + "l" : z + "r";
				
						// First we need to create a DIV for the space under the smaller corner
						var newFiller = document.createElement("div");	
						
						$(newFiller).css({
							'height':			radiusDiff[z] + "px",
							'width':			this.spec.get(smallerCornerType + 'Ru'),
							'position':			"absolute",
							'fontSize':			"1px",
							'overflow':			"hidden",
							'backgroundColor':	this.boxColour,
							'backgroundImage':	this.backgroundImage,
							'backgroundRepeat':	this.backgroundRepeat
						});					
						
						if (filter) $(newFiller).css('filter', 'filter'); // IE8 bug fix

						// Position filler
						switch (smallerCornerType) {
							case "tl":
								$(newFiller).css({
									'bottom':				'',
									'left':					'0',
									'borderLeft':			this.borderStringL,
									'backgroundPosition':	this.backgroundPosX + "px " + (this.borderWidth + this.backgroundPosY - this.spec.tlR) + "px"
								});
								this.topContainer.appendChild(newFiller);
							break;
							case "tr":
								$(newFiller).css({
									'bottom':				'',
									'right':					'0',
									'borderRight':			this.borderStringR,
									'backgroundPosition':	(this.backgroundPosX - this.boxWidth + this.spec.trR) + "px " + (this.borderWidth + this.backgroundPosY - this.spec.trR) + "px"
								});
								this.topContainer.appendChild(newFiller);
							break;
							case "bl":
								$(newFiller).css({
									'top':					'',
									'left':					'0',
									'borderLeft':			this.borderStringL,
									'backgroundPosition':	this.backgroundPosX + "px " + (this.backgroundPosY - this.borderWidth - this.boxHeight + radiusDiff[z] + this.spec.blR) + "px"
								});
								this.bottomContainer.appendChild(newFiller);
							break;
							case "br":
								$(newFiller).css({
									'top':					'',
									'right':				'0',
									'borderRight':			this.borderStringR,
									'backgroundPosition':	(this.borderWidthL + this.backgroundPosX - this.boxWidth + this.spec.brR) + "px " + (this.backgroundPosY - this.borderWidth - this.boxHeight + radiusDiff[z] + this.spec.brR) + "px"
								});
								this.bottomContainer.appendChild(newFiller);
							//break;
						}
					}
				
					// Create the bar to fill the gap between each corner horizontally
					var newFillerBar = document.createElement("div");
					if (filter) $(newFillerBar).css('filter', 'filter'); // IE8 bug fix
					$(newFillerBar).css({
						'position':					"relative",
						'fontSize':					"1px",
						'overflow':					"hidden",
						'width':					this.fillerWidth(z),
						'backgroundColor':			this.boxColour,
						'backgroundImage':			this.backgroundImage,
						'backgroundRepeat':			this.backgroundRepeat
					});
				
					switch (z) {
						case "t":
							// Top Bar
							if (this.topContainer) {
								if (!jQuery.support.boxModel) {
									$(newFillerBar).css('height', 100 + topMaxRadius + "px");
								} else {
									$(newFillerBar).css('height', 100 + topMaxRadius - this.borderWidth + "px");
								}
								$(newFillerBar).css('marginLeft', this.spec.tlR ? (this.spec.tlR - this.borderWidthL) + "px" : "0");
								$(newFillerBar).css('borderTop', this.borderString);
								if (this.backgroundImage) {
									var x_offset = this.spec.tlR ?
										(this.borderWidthL + this.backgroundPosX - this.spec.tlR) + "px " : this.backgroundPosX + "px ";
									
									$(newFillerBar).css('backgroundPosition', x_offset + this.backgroundPosY + "px");
				
									// Reposition the box's background image
									$(this.shell).css('backgroundPosition', this.backgroundPosX + "px " + (this.backgroundPosY - topMaxRadius + this.borderWidthL) + "px");
								}
								this.topContainer.appendChild(newFillerBar);
							}
						break;
						case "b":
							if (this.bottomContainer) {
								// Bottom Bar
								if (!jQuery.support.boxModel) {
									$(newFillerBar).css('height', botMaxRadius + "px");
								} else {
									$(newFillerBar).css('height', botMaxRadius - this.borderWidthB + "px");
								}
								$(newFillerBar).css('marginLeft', this.spec.blR ? (this.spec.blR - this.borderWidthL) + "px" : "0");
								$(newFillerBar).css('borderBottom', this.borderStringB);
								if (this.backgroundImage) {
									var x_offset = this.spec.blR ?
										(this.backgroundPosX + this.borderWidthL - this.spec.blR) + "px " : this.backgroundPosX + "px ";
									$(newFillerBar).css('backgroundPosition', x_offset + (this.backgroundPosY - clientHeight - this.borderWidth + botMaxRadius) + "px");
								}
								this.bottomContainer.appendChild(newFillerBar);
							}
						//break;
					}
				}			
			
				// style content container
				z = clientWidth;				
				if (jQuery.support.boxModel) z -= this.leftPadding + this.rightPadding;
				
				$(this.contentContainer).css({
					'position':			'absolute',
					'left':				this.borderWidthL + "px",
					'paddingTop':		this.topPadding + "px",
					'top':				this.borderWidth + "px",
					'paddingLeft':		this.leftPadding + "px",
					'paddingRight':		this.rightPadding + "px",
					'width':			z + "px",
					'textAlign':		$$.css('textAlign')
				}).addClass('autoPadDiv');
				
				$$.css('textAlign', 'left').addClass('hasCorners');
	
				this.box.appendChild(this.contentContainer);
				if (boxDisp) $(boxDisp).css('display', boxDispSave);
			};
			
			if (this.backgroundImage) {				
				backgroundPosX = this.backgroundCheck(backgroundPosX);
				backgroundPosY = this.backgroundCheck(backgroundPosY);
				if (this.backgroundObject) {
					this.backgroundObject.holdingElement = this;
					this.dispatch = this.applyCorners;
					this.applyCorners = function() {
						if (this.backgroundObject.complete) this.dispatch();
						else this.backgroundObject.onload = new Function('$(this.holdingElement).dispatch();');
					};
				}
			}
		};
		
		curvyObject.prototype.backgroundCheck = function(style) {
		  if (style === 'top' || style === 'left' || parseInt(style) === 0) return 0;
		  if (!(/^[-\d.]+px$/.test(style))  && !this.backgroundObject) {
		    this.backgroundObject = new Image;
		    var imgName = function(str) {
		      var matches = /url\("?([^'"]+)"?\)/.exec(str);
		      return (matches ? matches[1] : str);
		    };
		    this.backgroundObject.src = imgName(this.backgroundImage);
		  }
		  return style;
		};		
		
		/*curvyObject.dispatch = function(obj) {
		  if ('dispatch' in obj) obj.dispatch();
		  else throw Error('No dispatch function');
		};*/
		
		/*
		This function draws the pixels
		*/	
		curvyObject.prototype.drawPixel = function( intx, inty, colour, transAmount, height, newCorner, image, cornerRadius ) {			
			//var $$ = $(box);			
		    var pixel = document.createElement("div");
		    
		    $(pixel).css({	
		    	"height" :			height + "px",
		    	"width" :			"1px", 
		    	"position" :		"absolute", 
		    	"font-size" :		"1px", 
		    	"overflow" :		"hidden",
		    	"top" :				inty + "px",
		    	"left" :			intx + "px",
		    	"background-color" :colour
		    });
		    
		    var topMaxRadius = this.spec.get('tR');
		    
		    // Dont apply background image to border pixels
			if(image && this.backgroundImage != "")
			{
				$(pixel).css({
					"background-position":"-" + (this.boxWidth - (cornerRadius - intx) + this.borderWidth) + "px -" + ((this.boxHeight + topMaxRadius + inty) - this.borderWidth) + "px",
					"background-image":this.backgroundImage				 
				});
			}		    
		    if (transAmount != 100)
		    	$(pixel).css({opacity: (transAmount/100) });

		    newCorner.appendChild(pixel);
		};
		
		curvyObject.prototype.fillerWidth = function(tb) {
			var b_width, f_width;
			b_width = !jQuery.support.boxModel ? 0 : this.spec.radiusCount(tb) * this.borderWidthL;
			
			if ((f_width = this.boxWidth - this.spec.radiusSum(tb) + b_width) < 0)
				throw Error("Radius exceeds box width");
			return f_width + 'px';
		};			
		
		// Gets the computed colour.
		curvyObject.getComputedColour = function(colour) {
		  var d = document.createElement('DIV');
		  d.style.backgroundColor = colour;
		  document.body.appendChild(d);
		
		  if (window.getComputedStyle) { // Mozilla, Opera, Chrome, Safari
		    var rtn = document.defaultView.getComputedStyle(d, null).getPropertyValue('background-color');
		    d.parentNode.removeChild(d);
		    if (rtn.substr(0, 3) === "rgb") rtn = curvyObject.rgb2Hex(rtn);
		    return rtn;
		  }
		  else { // IE
		    var rng = document.body.createTextRange();
		    rng.moveToElementText(d);
		    rng.execCommand('ForeColor', false, colour);
		    var iClr = rng.queryCommandValue('ForeColor');
		    var rgb = "rgb("+(iClr & 0xFF)+", "+((iClr & 0xFF00)>>8)+", "+((iClr & 0xFF0000)>>16)+")";
		    d.parentNode.removeChild(d);
		    rng = null;
		    return curvyObject.rgb2Hex(rgb);
		  }
		};
				
		curvyObject.BlendColour = function(Col1, Col2, Col1Fraction) 
		{
			
			if (Col1 === 'transparent' || Col2 === 'transparent') throw Error('Cannot blend with transparent');
			if (Col1.charAt(0) !== '#') {
				Col1 = curvyObject.format_colour(Col1);
			}
			if (Col2.charAt(0) !== '#') {
				Col2 = curvyObject.format_colour(Col2);
			}
			var red1 = parseInt(Col1.substr(1, 2), 16);
			var green1 = parseInt(Col1.substr(3, 2), 16);
			var blue1 = parseInt(Col1.substr(5, 2), 16);
			var red2 = parseInt(Col2.substr(1, 2), 16);
			var green2 = parseInt(Col2.substr(3, 2), 16);
			var blue2 = parseInt(Col2.substr(5, 2), 16);
			
			if (Col1Fraction > 1 || Col1Fraction < 0) Col1Fraction = 1;
			
			var endRed = Math.round((red1 * Col1Fraction) + (red2 * (1 - Col1Fraction)));
			if (endRed > 255) endRed = 255;
			if (endRed < 0) endRed = 0;
			
			var endGreen = Math.round((green1 * Col1Fraction) + (green2 * (1 - Col1Fraction)));
			if (endGreen > 255) endGreen = 255;
			if (endGreen < 0) endGreen = 0;
			
			var endBlue = Math.round((blue1 * Col1Fraction) + (blue2 * (1 - Col1Fraction)));
			if (endBlue > 255) endBlue = 255;
			if (endBlue < 0) endBlue = 0;
			
			return "#" + curvyObject.IntToHex(endRed) + curvyObject.IntToHex(endGreen)+ curvyObject.IntToHex(endBlue);
			
		};
	
		curvyObject.IntToHex = function(strNum)
		{			
			var hexdig = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F' ];
			return hexdig[strNum >>> 4] + '' + hexdig[strNum & 15];
		};
	
		/*
		For a pixel cut by the line determines the fraction of the pixel on the 'inside' of the
		line.  Returns a number between 0 and 1
		*/
		curvyObject.pixelFraction = function(x, y, r) 
		{
  			var fraction;
 			var rsquared = r * r;

			/*
			determine the co-ordinates of the two points on the perimeter of the pixel that the
			circle crosses
			*/
			var xvalues = new Array(2);
			var yvalues = new Array(2);
			var point = 0;
			var whatsides = "";

			// x + 0 = Left
			var intersect = Math.sqrt(rsquared - Math.pow(x, 2));
			
			if (intersect >= y && intersect < (y + 1)) {
				whatsides = "Left";
				xvalues[point] = 0;
				yvalues[point] = intersect - y;
				++point;
			}
			// y + 1 = Top
			intersect = Math.sqrt(rsquared - Math.pow(y + 1, 2));
			
			if (intersect >= x && intersect < (x + 1)) {
				whatsides += "Top";
				xvalues[point] = intersect - x;
				yvalues[point] = 1;
				++point;
			}
			// x + 1 = Right
			intersect = Math.sqrt(rsquared - Math.pow(x + 1, 2));
			
			if (intersect >= y && intersect < (y + 1)) {
				whatsides += "Right";
				xvalues[point] = 1;
				yvalues[point] = intersect - y;
				++point;
			}
			// y + 0 = Bottom
			intersect = Math.sqrt(rsquared - Math.pow(y, 2));
			
			if (intersect >= x && intersect < (x + 1)) {
				whatsides += "Bottom";
				xvalues[point] = intersect - x;
				yvalues[point] = 0;
			}

			/*
			depending on which sides of the perimeter of the pixel the circle crosses calculate the
			fraction of the pixel inside the circle
			*/
			switch (whatsides) {
				case "LeftRight":
					fraction = Math.min(yvalues[0], yvalues[1]) + ((Math.max(yvalues[0], yvalues[1]) - Math.min(yvalues[0], yvalues[1])) / 2);
				break;
				
				case "TopRight":
					fraction = 1 - (((1 - xvalues[0]) * (1 - yvalues[1])) / 2);
				break;
				
				case "TopBottom":
					fraction = Math.min(xvalues[0], xvalues[1]) + ((Math.max(xvalues[0], xvalues[1]) - Math.min(xvalues[0], xvalues[1])) / 2);
				break;
				
				case "LeftBottom":
					fraction = yvalues[0] * xvalues[1] / 2;
				break;
				
				default:
					fraction = 1;
			}			
			return fraction;
		};
  
  
		// This function converts CSS rgb(x, x, x) to hexadecimal
		curvyObject.rgb2Hex = function(rgbColour) 
		{
			try{
			
				// Get array of RGB values
				var rgbArray = curvyObject.rgb2Array(rgbColour);
				
				// Get RGB values
				var red   = parseInt(rgbArray[0]);
				var green = parseInt(rgbArray[1]);
				var blue  = parseInt(rgbArray[2]);
				
				// Build hex colour code
				var hexColour = "#" + curvyObject.IntToHex(red) + curvyObject.IntToHex(green) + curvyObject.IntToHex(blue);
			}
			catch(e){			
				alert("There was an error converting the RGB value to Hexadecimal in function rgb2Hex");
			}
			
			return hexColour;
		};
		
		// Returns an array of rbg values
		curvyObject.rgb2Array = function(rgbColour) 
		{
			// Remove rgb()
			var rgbValues = rgbColour.substring(4, rgbColour.indexOf(")"));

			// Split RGB into array
			return rgbValues.split(", ");
		};

		// Formats colours
		curvyObject.format_colour = function(colour) 
		{
			// Make sure colour is set and not transparent
			if (colour != "" && colour != "transparent") {
			  // RGB Value?
			  if (colour.substr(0, 3) === "rgb") {
			    // Get HEX aquiv.
			    colour = curvyObject.rgb2Hex(colour);
			  }
			  else if (colour.charAt(0) !== '#') {
			    // Convert colour name to hex value
			    colour = getComputedColour(colour);
			  }
			  else if (colour.length === 4) {
			    // 3 chr colour code add remainder
			    colour = "#" + colour.charAt(1) + colour.charAt(1) + colour.charAt(2) + colour.charAt(2) + colour.charAt(3) + colour.charAt(3);
			  }
			}
			return colour;
		};	
		  
		return this.each(function() {
			if (!$(this).is('.hasCorners')) {
				if (nativeCornersSupported) {
					if (settings.get('tlR')) {
						$(this).css({
							'border-top-left-radius' : settings.get('tlR') + 'px',
							'-moz-border-radius-topleft' : settings.get('tlR') + 'px',
							'-webkit-border-top-left-radius' : settings.get('tlR') + 'px'
						});
					}
					if (settings.get('trR')) {
						$(this).css({
							'border-top-right-radius' : settings.get('trR') + 'px',
							'-moz-border-radius-topright' : settings.get('trR') + 'px',
							'-webkit-border-top-right-radius' : settings.get('trR') + 'px'
						});
					}
					if (settings.get('blR')) {
						$(this).css({
							'border-bottom-left-radius' : settings.get('blR') + 'px',
							'-moz-border-radius-bottomleft' : settings.get('blR') + 'px',
							'-webkit-border-bottom-left-radius' : settings.get('blR') + 'px'
						});
					}
					if (settings.get('brR')) {
						$(this).css({
							'border-bottom-right-radius' : settings.get('brR') + 'px',
							'-moz-border-radius-bottomright' : settings.get('brR') + 'px',
							'-webkit-border-bottom-right-radius' : settings.get('brR') + 'px'
						});
					}
				} else {
					if (!$(this).is('.drawn')) {						
						$(this).addClass('drawn');
						
						thestyles = $(this).attr('style');
						if (thestyles == 'undefined') {
							thestyles = '';
						}
						
						redrawList.push({
						  node : this,
						  spec : settings,
						  style : thestyles,
						  copy : $(this).clone(true)
						});
					}
					var obj = new curvyObject(settings, this);
					obj.applyCorners();
				}			
			}			
		});
			
	};
	
	$.fn.removeCorners = function() { 
		return this.each(function(i, e) {
			thisdiv = e;
			$.each(
				redrawList,
				function( intIndex, list ){	
					if (list.node==thisdiv && $('.autoPadDiv', thisdiv).size()>0) {
						//$('div:not(.autoPadDiv)', thisdiv).remove();
						//$('.autoPadDiv', thisdiv).replaceWith( $('.autoPadDiv', thisdiv).contents() );							
						$(thisdiv).html($(thisdiv).children('.autoPadDiv:first').contents());						
						style = list.style == 'undefined' ? list.style : ''; 
						$(thisdiv).removeClass('hasCorners').attr('style', style );						
						return false;
					}
				}
			);
		});
	};
	
	$.fn.redrawCorners = function() { 
		return this.each(function(i, e) {
			thisdiv = e;
			$.each(
				redrawList,				
				function( intIndex, list ){	
					if (list.node==thisdiv) {
						//$('div:not(.autoPadDiv)', thisdiv).remove();
						//$('.autoPadDiv', thisdiv).replaceWith( $('.autoPadDiv', thisdiv).contents() );	
						//style = list.style == 'undefined' ? list.style : ''; 
						//$(thisdiv).removeClass('hasCorners').attr('style', style );	
						$(thisdiv).corner(list.spec);
						return false;
					}
				}
			);
		});
	};
	
	$.fn.dispatch = function() { 
		return this.each(function(i, e) {
			obj = e;
			if ('dispatch' in obj) obj.dispatch();
			else throw Error('No dispatch function')
		});			
	};
	
	$(function(){
		
		// Detect styles and apply corners in browsers with no native border-radius support
		if ($.browser.msie) {	
			/* Force caching of bg images in IE6 */
			try {	document.execCommand("BackgroundImageCache", false, true);	}	catch(e) {};
			
			function units(num) {
				if (!parseInt(num)) return 'px'; // '0' becomes '0px' for simplicity's sake
				var matches = /^[\d.]+(\w+)$/.exec(num);
				return matches[1];
			};
			
			/* Detect and Apply Corners */
			var t, i, j;
			
			function procIEStyles(rule) {
				var style = rule.style;
			
				if (jQuery.browser.version > 6.0) {
					var allR = style['-moz-border-radius'] || 0;
					var tR   = style['-moz-border-radius-topright'] || 0;
					var tL   = style['-moz-border-radius-topleft'] || 0;
					var bR   = style['-moz-border-radius-bottomright'] || 0;
					var bL   = style['-moz-border-radius-bottomleft'] || 0;
				}
				else {
					var allR = style['moz-border-radius'] || 0;
					var tR   = style['moz-border-radius-topright'] || 0;
					var tL   = style['moz-border-radius-topleft'] || 0;
					var bR   = style['moz-border-radius-bottomright'] || 0;
					var bL   = style['moz-border-radius-bottomleft'] || 0;
				}
				if (allR) {
					var t = allR.split('/'); // ignore elliptical spec.
					t = t[0].split(/\s+/);
					if (t[t.length - 1] === '') t.pop();
					switch (t.length) {
						case 3:
							tL = t[0];
							tR = bL = t[1];
							bR = t[2];
							allR = false;
						break;
						case 2:
							tL = bR = t[0];
							tR = bL = t[1];
							allR = false;
						case 1:
						break;
						case 4:
							tL = t[0];
							tR = t[1];
							bR = t[2];
							bL = t[3];
							allR = false;
						break;
						default:
							alert('Illegal corners specification: ' + allR);
					}
				}
				if (allR || tL || tR || bR || bL) {
					var settings = new curvyCnrSpec(rule.selectorText);
					if (allR)
						settings.setcorner(null, null, parseInt(allR), units(allR));
					else {
						if (tR) settings.setcorner('t', 'r', parseInt(tR), units(tR));
						if (tL) settings.setcorner('t', 'l', parseInt(tL), units(tL));
						if (bL) settings.setcorner('b', 'l', parseInt(bL), units(bL));
						if (bR) settings.setcorner('b', 'r', parseInt(bR), units(bR));
					}
					$(rule.selectorText).corner(settings);
				}
			}
			for (t = 0; t < document.styleSheets.length; ++t) {
				try {
					if (document.styleSheets[t].imports) {
						for (i = 0; i < document.styleSheets[t].imports.length; ++i) {
							for (j = 0; j < document.styleSheets[t].imports[i].rules.length; ++j) {
								procIEStyles(document.styleSheets[t].imports[i].rules[j]);
							}
						}
					}
					for (i = 0; i < document.styleSheets[t].rules.length; ++i)
						procIEStyles(document.styleSheets[t].rules[i]);
				}
				catch (e) {} 
			}
		} else if ($.browser.opera) {
			
			// Apply if border radius is not supported
			try {	checkStandard = (document.body.style.BorderRadius !== undefined);	} catch(err) {}
			
			if (!checkStandard) {
		
				function opera_contains_border_radius(sheetnumber) {
					return /border-((top|bottom)-(left|right)-)?radius/.test(document.styleSheets.item(sheetnumber).ownerNode.text);
				};
				
				rules = [];
			
				for (t = 0; t < document.styleSheets.length; ++t) {
					if (opera_contains_border_radius(t)) {
				   	
				   		var txt = document.styleSheets.item(sheetnumber).ownerNode.text;
				   		txt = txt.replace(/\/\*(\n|\r|.)*?\*\//g, ''); // strip comments
				   		
				   		var pat = new RegExp("^\\s*([\\w.#][-\\w.#, ]+)[\\n\\s]*\\{([^}]+border-((top|bottom)-(left|right)-)?radius[^}]*)\\}", "mg");
				   		var matches;				   		
				   		while ((matches = pat.exec(txt)) !== null) {
				   			var pat2 = new RegExp("(..)border-((top|bottom)-(left|right)-)?radius:\\s*([\\d.]+)(in|em|px|ex|pt)", "g");
				   			var submatches, cornerspec = new curvyCnrSpec(matches[1]);
				   			while ((submatches = pat2.exec(matches[2])) !== null) {
				   				if (submatches[1] !== "z-")
				   				    cornerspec.setcorner(submatches[3], submatches[4], submatches[5], submatches[6]);
				   				rules.push(cornerspec);
				   			}
				   		}
				   	}
				}				
				for (i in rules) if (!isNaN(i))
					$(rules[i].selectorText).corner(rules[i]);
					
					
			}
		}
	});		
	
})(jQuery);