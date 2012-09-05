/* jQuery.contentEditable Plugin
Copyright © 2011 FreshCode
http://www.freshcode.co.za/

DHTML text editor jQuery plugin that uses contentEditable attribute in modern browsers for in-place editing.

Dependencies
------------
 - jQuery core
 - shortcut.js for keyboard hotkeys
 - farbtastic color picker plugin

License
-------
Let's keep it simple:
 1. You may use this code however you wish, including for commercial projects.
 2. You may not sell it or charge for it without my written permission.
 3. You muse retain the license information in this file.
 4. You are encouraged to contribute to the plugin on bitbucket (https://bitbucket.org/freshcode/jquery.contenteditable)
 5. You are encouraged to link back to www.freshcode.co.za if you publish something about it so everyone can benefit from future updates.

Best regards
Petrus Theron
contenteditable@freshcode.co.za
FreshCode Software Development
_____________________________________________
Improvments by Quan Nguyen (github.com/mquan):
- font size and name selections
- font color and background color with color picker plugin
- text-alignment
- plugin automatically builds toolbar, lets user specify which buttons to exclude

*/
;(function($) {
	var bgcolorpicker = false;
	var methods = {
		edit: function (isEditing) {
			return this.each(function () {
									   
									   
/*									   $(this).keydown(function(event) {
    if (event.keyCode == 13 || event.charCode == 13) {
        var execute = editor.insertHTML('<br />');
        eval(execute);
        return false;
    } 
});*/
									   
				$(this).attr("contentEditable", (isEditing === true) ? true : false);
				
				
				
			});
		},
		save: function (callback) {
			return this.each(function () {
				(callback)($(this).attr("id"), $(this).html());
			});
		},
		fontname: function() {
			var pos = $('.toolbar_fontname').offset();
			var height = $('.toolbar_fontname').height() + 5;
			$('#fontname-select').css({"left": (pos.left - $(document).scrollLeft()) + 'px', "top": (pos.top + height - $(document).scrollTop()) + 'px'});
			$('#fontname-select').toggle();
		},
		FontSize: function() {			
			var pos = $('.toolbar_fontsize').offset();
			var height = $('.toolbar_fontsize').height() + 5;
			$('#fontsize-select').css({"left": (pos.left - $(document).scrollLeft()) + 'px', "top": (pos.top + height - $(document).scrollTop()) + 'px'});
			$('#fontsize-select').toggle();
		},
		forecolor: function() {
			bgcolorpicker = false;
			var pos = $('.toolbar_forecolor').offset();
			var height = $('.toolbar_forecolor').height() + 5;
			$('#colorpanel').css({"left": (pos.left - $(document).scrollLeft()) + 'px', "top": (pos.top + height - $(document).scrollTop()) + 'px'});
			$('#colorpanel').show();
		},
		backcolor: function() {
			bgcolorpicker = true;
			var pos = $('.toolbar_bgcolor').offset();
			var height = $('.toolbar_bgcolor').height() + 5;
			$('#colorpanel').css({"left": (pos.left - $(document).scrollLeft()) + 'px', "top": (pos.top + height - $(document).scrollTop()) + 'px'});
			$('#colorpanel').show();
		},
		createlink: function () { /* This can be improved */
			var urlPrompt = prompt("Enter URL:", "http://");
			document.execCommand("createlink", false, urlPrompt);
		},
		insertimage: function () { /* This can be improved */
			var urlPrompt = prompt("Enter Image URL:", "http://");
			document.execCommand("insertimage", false, urlPrompt);
		},
		formatblock: function (block) {
			document.execCommand("formatblock", null, block);
		},
		init: function (options) {
			if(typeof options == 'undefined') {
				options = {};
			}
			var $toolbar;
			if(typeof options.toolbar_selector != 'undefined') {
				$toolbar = $(options.toolbar_selector);
			}
			else { //prepend toolbar if not specified
				$(this).before("<div id='editor-toolbar'></div>");
				$toolbar = $('#editor-toolbar');
			}
			$toolbar.addClass('fresheditor-toolbar');
			
			//add color picker panel
			$toolbar.append("<div id='colorpanel'><input type='text' id='colorinput' value='#000000' /><div id='colorpicker'></div></div>");
			$('#colorpanel').hide(); 
			$('#colorpicker').farbtastic(function (color) {
				$('#colorinput').val(color);
				if(bgcolorpicker) {
					document.execCommand("backcolor", false, color);
					$('.toolbar_bgcolor div').css({"background-color": color});
				} else {
					document.execCommand("forecolor", false, color);
					$('.toolbar_forecolor').css({"color": color});
				}
			});
			var mouse_on_colorpicker = false;
			$('#colorpanel').hover(
				function() {
					mouse_on_colorpicker = true;
				},
				function() {
					mouse_on_colorpicker = false;
				}
			);
			
			//build font name selection panel
			fontnames = ["Arial", "Arial Black", "Book Antiqua", "Comic Sans MS", "Courier New", "Georgia", "Helvetica",
						"Impact", "Symbol", "Tahoma", "Terminal", "Times New Roman", "Trebuchet MS", "Verdana", "Webdings", "Windings"];
			$toolbar.append("<div id='fontname-select'><div style='background-color:#ddd;'>Font Name</div></div>");
			$('#fontname-select').hide();
			$.each(fontnames, function(index, font) {
				$('#fontname-select').append("<a href='#' class='font-option fontname-option' style='font-family:" + font + ";'>" + font + "</a>")
			});			
			$('.fontname-option').click(function() {
				document.execCommand("fontname", false, $(this).text());
				$('#fontname-select').hide();
				return false;
			});
			var mouse_on_fontname=false;
			$('.fontname-option').hover(
				function() {
					$(this).css({"background-color": '#a7d6fb'});
					mouse_on_fontname = true;
				},
				function() {
					$(this).css({"background-color": '#ffffff'});
					mouse_on_fontname = false;
				}
			);
			
			//font size selection panel
			fontsizes = [{size: 1, point: 8}, {size:2, point:10}, {size:3, point:12}, {size:4, point:14},
						 {size:5, point:18}, {size:6, point:24}, {size:7, point:36}];
			$toolbar.append("<div id='fontsize-select'><div style='background-color:#ddd;'>Font Size</div></div>");
			$('#fontsize-select').hide();
			$.each(fontsizes, function (index, fontsize) {
				$('#fontsize-select', $toolbar).append("<a href='#' class='font-option fontsize-option' style='font-size:" + 
							fontsize.point + "px;' fontsize='" + fontsize.size + "'>" +fontsize.size +  " (" + fontsize.point + "pt)</a>");
			});

			$('a.fontsize-option').click(function() {
				document.execCommand("FontSize", false, $(this).attr('fontsize'));
				$('#fontsize-select').hide();
				return false;
			});
			var mouse_on_fontsize=false;
			$('.fontsize-option').hover(
				function() {
					$(this).css({"background-color": '#a7d6fb'});
					mouse_on_fontsize = true;
				},
				function() {
					$(this).css({"background-color": '#ffffff'});
					mouse_on_fontsize = false;
				}
			);
			
			//close userinput panels when click outside of them
			$("body").click(function(event) {
				var target = event.target;
				if(!mouse_on_colorpicker) {
					$('#colorpanel').hide();
				}
				if(!mouse_on_fontname) {
					$('#fontname-select').hide();
				}
				if(!mouse_on_fontsize) {
					$('#fontsize-select').hide();
				}
			});

			//build toolbar
			var groups = [
						  
				[
				 
				 
				{name: 'save', label: 'S', title: 'save', classname: 'save'}, 
				{name: 'bold', label: 'B', title: 'Bold (Ctrl+B)', classname: 'toolbar_bold'},
				{name: 'italic', label: 'I', title: 'Italic (Ctrl+I)', classname: 'toolbar_italic'},
				{name: 'underline', label: 'U', title: 'Underline (Ctrl+U)', classname: 'toolbar_underline'},
				{name: 'strikethrough', label: "<span style='text-shadow:none;text-decoration:line-through;'>ABC</span>", title: 'Strikethrough', classname: 'toolbar_strikethrough'},
				{name: 'removeFormat', label: '&minus;', title: 'Remove Formating (Ctrl+M)', classname: 'toolbar_remove'}],
				
				[{name: 'fontname', label: 'F', title: 'Select font name', classname: 'toolbar_fontname', userinput: 'yes'},
				{name: 'FontSize', label: "<span style='font:bold 16px;'>A</span><span style='font-size:8px;'>A</span>", title: 'Select font size', classname: 'toolbar_fontsize', userinput: 'yes'},
				{name: 'forecolor', label: 'A', title: 'Select font color', classname: 'toolbar_forecolor', userinput: 'yes'},
				{name: 'backcolor', label: "<div>&nbsp;</div>", title: 'Select background color', classname: 'toolbar_bgcolor', userinput: 'yes'}],
				
				[{name: 'justifyleft', label: '<div>&nbsp;</div>', title: 'Left justify', classname: 'toolbar_justifyleft'},
				{name: 'justifycenter', label: '<div>&nbsp;</div>', title: 'Center justify', classname: 'toolbar_justifycenter'},
				{name: 'justifyright', label: '<div>&nbsp;</div>', title: 'Right justify', classname: 'toolbar_justifyright'},
				{name: 'justifyfull', label: '<div>&nbsp;</div>', title: 'Full justify', classname: 'toolbar_justifyfull'}],
				
				[{name: 'createlink', label: '@', title: 'Link to a web page (Ctrl+L)', userinput: "yes", classname: 'toolbar_link'},
				{name: 'insertimage', label: '<div>&nbsp;</div>', title: 'Insert an image (Ctrl+G)', userinput: "yes", classname: 'toolbar_image'},
				{name: 'insertorderedlist', label: '<div>&nbsp;</div>', title: 'Insert ordered list', classname: 'toolbar_ol'},
				{name: 'insertunorderedlist', label: '<div>&nbsp;</div>', title: 'Insert unordered list', classname: 'toolbar_ul'}],
								
				[{name: 'insertparagraph', label: 'P', title: 'Insert a paragraph (Ctrl+Alt+0)', classname: 'toolbar_p', block:'p'},
				{name: 'insertheading1', label: 'H1', title: "Heading 1 (Ctrl+Alt+1)", classname: 'toolbar_h1', block: 'h1'},
				{name: 'insertheading2', label: 'H2', title: "Heading 2 (Ctrl+Alt+2)", classname: 'toolbar_h2',  block: 'h2'},
				{name: 'insertheading3', label: 'H3', title: "Heading 3 (Ctrl+Alt+3)", classname: 'toolbar_h3', block: 'h3'},
				{name: 'insertheading4', label: 'H4', title: "Heading 4 (Ctrl+Alt+4)", classname: 'toolbar_h4', block: 'h4'}],
				
				[{name: 'blockquote', label: '<div>&nbsp;</div>', title: 'Blockquote (Ctrl+Q)', classname: 'toolbar_blockquote', block:'blockquote'},
				{name: 'code', label: '{&nbsp;}', title: 'Code (Ctrl+Alt+K)', classname: 'toolbar_code', block:'pre'},
				{name: 'superscript', label: 'x<sup>2</sup>', title: 'Superscript', classname: 'toolbar_superscript'},
				{name: 'subscript', label: 'x<sub>2</sub>', title: 'Subscript', classname: 'toolbar_subscript'},
				{name: 'open_module_browser', label: 'Mod', title: 'Modules', classname: 'open_module_browser'}
				
				
				]
			];
			
			$toolbar.append("<div class='buttons'></div>");
			var excludes = options.excludes || [];
			$.each(groups, function (index, commands) {
				var group='';
				$.each(commands, function(i, command) {
					if(jQuery.inArray(command.name, excludes) == -1) { //lets developers exclude buttons
						var button = "<li><a href='javascript:void(0);' class='toolbar-cmd " + command.classname + "' title='" + command.title + "' command='" + command.name;
						if(typeof command.userinput != 'undefined') {
							button = button + "' userinput='" + command.userinput;
						}
						if(typeof command.block != 'undefined') {
							button = button + "' block='" + command.block;
						}
						button = button + "'>" + command.label + "</a></li>";
						group = group + button;
					}
				});
				$(".buttons", $toolbar).append("<ul class='toolbarSection'>" + group + "</u>");
			});
				
		   
			
			//one common click event for all command buttons
			$("a.toolbar-cmd").click(function() { 
				//first close userinput panels b/c this click event won't be propagated to body's click
				$('#colorpanel').hide();
				$('#fontname-select').hide();
				$('#fontsize-select').hide();
				
				var cmd = $(this).attr('command');
				if($(this).attr('userinput') === 'yes') {
					methods[cmd].apply(this);
				} else if ($(this).attr('block')) {
					
					
					methods['formatblock'].apply(this, ["<" + $(this).attr('block') + ">"]);
				} else {
					
				
					 if ((cmd == 'save')) {
					 mw.edit.save()
					 mw.edit.init_sortables();
					 } else if ((cmd == 'open_module_browser')) {
				 
					 }	 else {
					
                    // Firefox execCommand fix for justify (https://bugzilla.mozilla.org/show_bug.cgi?id=442186)
                    if ((cmd == 'justifyright') || (cmd == 'justifyleft') || (cmd == 'justifycenter') || (cmd == 'justifyfull')) {
                        try {
                            document.execCommand(cmd, false, null);
                        }
                        catch (e) {
                            //special case for Mozilla Bug #442186
                            if (e && e.result == 2147500037) {
                                //probably firefox bug 442186 - workaround
                                var range = window.getSelection().getRangeAt(0);
                                var dummy = document.createElement('div');
                                dummy.style.height="1px;";

                                //find node with contentEditable
                                var ceNode = range.startContainer.parentNode;
                                while (ceNode && ceNode.contentEditable != 'true')
                                   ceNode = ceNode.parentNode;

                                if (!ceNode) throw 'Selected node is not editable!';
                                    ceNode.insertBefore(dummy, ceNode.childNodes[0]);                    
                                    document.execCommand(cmd, false, null);
                                    dummy.parentNode.removeChild(dummy);
                                } else if (console && console.log) console.log(e);
                        }
                    } else {
						
					
						
						
                        document.execCommand(cmd, false, null);
                    }
					
					 }
					
					
				}
				return false;
			});
			
			var shortcuts = [
				{ keys: 'Ctrl+l', method: function () { methods.createlink.apply(this); } },
				 
				{ keys: 'Ctrl+g', method: function () { methods.insertimage.apply(this); } },
				{ keys: 'Ctrl+Alt+U', method: function () { document.execCommand('insertunorderedlist', false, null); } },
				{ keys: 'Ctrl+Alt+O', method: function () { document.execCommand('insertorderedlist', false, null); } },
				{ keys: 'Ctrl+q', method: function () { methods.formatblock.apply(this, ["<blockquote>"]); } },
				{ keys: 'Ctrl+Alt+k', method: function () { methods.formatblock.apply(this, ["<pre>"]); } },
				{ keys: 'Ctrl+.', method: function () { document.execCommand('superscript', false, null); } },
				{ keys: 'Ctrl+Shift+.', method: function () { document.execCommand('subscript', false, null); } },
				{ keys: 'Ctrl+Alt+0', method: function () { methods.formatblock.apply(this, ["p"]); } },
				{ keys: 'Ctrl+b', method: function () { document.execCommand('bold', false, null); } },
				{ keys: 'Ctrl+i', method: function () { document.execCommand('italic', false, null); } },
				{ keys: 'Ctrl+Alt+1', method: function () { methods.formatblock.apply(this, ["H1"]); } },
				{ keys: 'Ctrl+Alt+2', method: function () { methods.formatblock.apply(this, ["H2"]); } },
				{ keys: 'Ctrl+Alt+3', method: function () { methods.formatblock.apply(this, ["H3"]); } },
				{ keys: 'Ctrl+Alt+4', method: function () { methods.formatblock.apply(this, ["H4"]); } },
				{ keys: 'Ctrl+m', method: function () { document.execCommand("removeFormat", false, null); } },
				{ keys: 'Ctrl+u', method: function () { document.execCommand('underline', false, null); } },
				{ keys: 'tab', method: function () { 
				if(    mw.settings.text_edit_started == true){
				document.execCommand('indent', false, null); 
				}
				} },
				{ keys: 'Ctrl+tab', method: function () { document.execCommand('indent', false, null); } },
				{ keys: 'Shift+tab', method: function () { document.execCommand('outdent', false, null); } }
			];



			return this.each(function () {

				var $this = $(this), data = $this.data('fresheditor'),
					tooltip = $('<div />', {
						text: $this.attr('title')
					});

				// If the plugin hasn't been initialized yet
				if (!data) {
					/* Do more setup stuff here */

					$(this).data('fresheditor', {
						target: $this,
						tooltip: tooltip
					});
				}
			});
		}
	};

	$.fn.freshereditor = function (method) {
		// Method calling logic
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method ' + method + ' does not exist on jQuery.contentEditable');
		}
		return;
	};
})(jQuery);