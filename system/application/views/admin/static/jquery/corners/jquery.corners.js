/**
*
* jquery.corners.js
*
* v1.1
* Contact: Christian Haack Arnaboldi (ragamo@gmail.com)
* http://ragamo.medioclick.com
*
* Create rounded corners using canvas element.
* 
* Tested in Internet Explorer 7 and Mozilla Firefox 3
*
* License: GPL
* 
* Copyright (c) 2008, Christian Haack Arnaboldi
* All rights reserved.
* 
* Redistribution and use in source and binary forms, with or without modification, 
* are permitted provided that the following conditions are met:
* 
*     * Redistributions of source code must retain the above copyright notice, 
*       this list of conditions and the following disclaimer.
*     * Redistributions in binary form must reproduce the above copyright notice, 
*       this list of conditions and the following disclaimer in the documentation 
*       and/or other materials provided with the distribution.
*     * Neither the name of Splunk Inc nor the names of its contributors may 
*       be used to endorse or promote products derived from this software without 
*       specific prior written permission.
* 
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY 
* EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES 
* OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT 
* SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, 
* SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT 
* OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) 
* HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, 
* OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS 
* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
* 
*
* Usage: 
*  $(selector).corners([options])
*
* Supported options:
*   radio - (int) radius size of corners
*   inColor - (color) inside color of element
*   outColor - (color) outside color of corners  
*	borderSize - (int) border width 
*	borderColor - (color) color of borders 
*
*       
* Examples
*	$('#example_1').corners();
*	$('#example_2').corners({ inColor: '#8FC7FF' });
*	$('#example_3').corners({ inColor: '#FEFF8F', outColor: '#000' });
*	$('#example_4').corners({ radio: 30, borderSize: 2 });
*	$('#example_5').corners({ radio: 40, inColor: '#FFCC66', outColor: '#D2FBAE', borderSize: 2, borderColor: '#0000FF' });
*/

(function($) {		
	$.fn.drawCorners = function(id, radio, inColor, outColor, bSize, bColor) {
		if($.browser.msie) {
			var bC = bColor;
			var iC = inColor;
		} else {
			var bC = inColor;
			var iC = bColor;
		}
		
		var UpLeftCanvas = excanvas(document.getElementById(id +'_UpLeftCorner'));
      	if (UpLeftCanvas.getContext) {
        	var ctx = UpLeftCanvas.getContext("2d");
			
			ctx.fillStyle = outColor;
      		ctx.fillRect(0,0,radio,radio);

			ctx.fillStyle = bC;
			ctx.arc(radio, radio, radio, (Math.PI/2), 0, 0);
			ctx.fill();
			
			ctx.fillStyle = iC;
			ctx.arc(radio, radio, (radio-bSize), 0, (Math.PI/2), 1);
			ctx.fill();	
			
      	}
		
		var UpRightCanvas = excanvas(document.getElementById(id +'_UpRightCorner'));
      	if (UpRightCanvas.getContext) {
        	var ctx = UpRightCanvas.getContext("2d");
			
			ctx.fillStyle = outColor;
      		ctx.fillRect(0,0,radio,radio);
			
			ctx.fillStyle = bC;
			ctx.arc(0, radio, radio, (Math.PI), (Math.PI/2), 0);
			ctx.fill();
			
			ctx.fillStyle = iC;
			ctx.arc(0, radio, (radio-bSize), (Math.PI/2), (Math.PI), 1);
			ctx.fill();
      	}
		
		var DownLeftCanvas = excanvas(document.getElementById(id +'_DownLeftCorner'));
      	if (DownLeftCanvas.getContext) {
        	var ctx = DownLeftCanvas.getContext("2d");
			
			ctx.fillStyle = outColor;
      		ctx.fillRect(0,0,radio,radio);
			
			ctx.fillStyle = bC;
			ctx.arc(radio, 0, radio, 0, (3*Math.PI/2), 0);
			ctx.fill();
			
			ctx.fillStyle = iC;
			ctx.arc(radio, 0, (radio-bSize), (3*Math.PI/2), 0, 1);
			ctx.fill();
      	}
		
		var DownRightCanvas = excanvas(document.getElementById(id +'_DownRightCorner'));
      	if (DownRightCanvas.getContext) {
        	var ctx = DownRightCanvas.getContext("2d");
			
			ctx.fillStyle = outColor;
      		ctx.fillRect(0,0,radio,radio);
			
			ctx.fillStyle = bC;
			ctx.arc(0, 0, radio, (3*Math.PI/2), (Math.PI), 0);
			ctx.fill();
			
			ctx.fillStyle = iC;
			ctx.arc(0, 0, (radio-bSize), (Math.PI), (3*Math.PI/2), 1);
			ctx.fill();
      	}
	};
	
	$.fn.corners = function(options) {
		
		var options = $.extend({
			radio: 10,
			outColor: '#FFF',
			inColor: '#CDCDCD',
			borderSize: 0,
			borderColor: '#000000'
		}, options ? options : {});	
		
		return this.each(function() { 			
			if(options.borderSize > options.radio) {
				options.radio = options.borderSize;
			}
								  
			if($(this).css('backgroundColor')!='transparent') {
				options.inColor = $(this).css('backgroundColor');
			}
			
			if(options.borderSize == 0) {
				options.borderColor = $(this).css('border-left-color') || $(this).css('border-top-color') || $(this).css('border-bottom-color') || $(this).css('border-right-color');
				if(($(this).css('borderWidth')!='medium') || ($(this).css('borderWidth')!='')) {
					options.borderSize = ($(this).outerWidth()-$(this).innerWidth())/2 || ($(this).outerHeight()-$(this).innerHeight())/2;
				}
			}
								  
			var id = $(this).attr('id');
			var cont = $(this).html();
			
			$(this).css({ 
				position: 'relative', 
				backgroundColor: options.inColor,
				borderStyle: 'solid',
				borderWidth: options.borderSize+'px', 
				borderColor: options.borderColor
			}).html('');
			
			$(this).append('<canvas id=\''+ id +'_UpLeftCorner\' width='+ options.radio +' height='+ options.radio +'></canvas>');
			$(this).append('<canvas id=\''+ id +'_UpRightCorner\' width='+ options.radio +' height='+ options.radio +'></canvas>');
			$(this).append('<canvas id=\''+ id +'_DownLeftCorner\' width='+ options.radio +' height='+ options.radio +'></canvas>');
			$(this).append('<canvas id=\''+ id +'_DownRightCorner\' width='+ options.radio +' height='+ options.radio +'></canvas>');
			$(this).append('<div id=\''+ id +'_main\'>'+ cont +'</div>');
			//$(this).append('<span id=\''+ id +'_main\'>'+ cont +'</span>');
			
			$('#'+ id +'_main').css({ 
				position: 'relative', 
				zIndex: 1
			});
			
			$('#'+ id +'_UpLeftCorner, #'+ id +'_UpRightCorner, #'+ id +'_DownLeftCorner, #'+ id +'_DownRightCorner').css({ 
				position: 'absolute', 
				zIndex: 0,
				width: options.radio+'px', 
				height: options.radio+'px',
				backgroundColor: options.inColor 
			});
			
			if(options.borderSize != 0) {
				var B = -(options.borderSize);
				$('#'+ id +'_UpLeftCorner').css({ top: B+'px', left: B+'px' });
				$('#'+ id +'_UpRightCorner').css({ top: B+'px', right: B+'px' });
				$('#'+ id +'_DownLeftCorner').css({ bottom: B+'px', left: B+'px' });
				$('#'+ id +'_DownRightCorner').css({ bottom: B+'px', right: B+'px' });

			} else {
				$('#'+ id +'_UpLeftCorner').css({ top: '0px', left: '0px' });
				$('#'+ id +'_UpRightCorner').css({ top: '0px', right: '0px' });
				$('#'+ id +'_DownLeftCorner').css({ bottom: '0px', left: '0px' });
				$('#'+ id +'_DownRightCorner').css({ bottom: '0px', right: '0px' });
				
				options.borderColor = options.inColor;
			}
			
			$(this).drawCorners(id, options.radio, options.inColor, options.outColor, options.borderSize, options.borderColor);
		});
	};
})(jQuery)