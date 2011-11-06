/**
 * PHP plugin for TinyMCE
 *
 * File: editor_plugin.js
 * Version: 1.00 (BETA)
 * Date: 2006/05/31
 *
 * @author Brian Guilfoil
 * @copyright Copyright © 2006, Brian Guilfoil, All rights reserved.
 
 Auhors Note:
 	The source code for this plugin and its associated files is based on the source code for the flash plugin
 	which was distributed with TinyMCE Version: 2.0.6.1 (2006-05-04).

 	That plugin was written and copyrighted by Moxiecode Systems AB.
 	The copyright of the origninal source is reproduced here:
		 * $RCSfile: editor_plugin_src.js,v $
		 * $Revision: 1.34 $
		 * $Date: 2006/02/10 16:29:39 $
		 *
		 * @author Moxiecode
		 * @copyright Copyright © 2004-2006, Moxiecode Systems AB, All rights reserved.

 	Some of the source code in this plugin is identical or virtually identical
 	to the original source code and has been reproduced by permission of the authors.
 	
 	I would like to thank the authors of TinyMCE and Moxiecode Systems for allowing me to 
 	duplicate some of their hard work.  I would also like to thank any and all contributors 
 	to the TinyMCE project.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('php', 'en,tr,de,sv,zh_cn,cs,fa,fr_ca,fr,pl,pt_br,nl,da,he,nb,hu,ru,ru_KOI8-R,ru_UTF-8,nn,es,cy,is,zh_tw,zh_tw_utf8,sk,pt_br');

var TinyMCE_PHPPlugin = {
	getInfo : function() {
		return {
			longname : 'PHP',
			author : 'Brian Guilfoil',
			authorurl : 'Unavailable',
			infourl : 'http://tinymce.moxiecode.com/punbb/viewtopic.php?pid=12080#p12080',
			version : "1.00 (BETA)"
		};
	},

	initInstance : function(inst) {
		if (!tinyMCE.settings['php_skip_plugin_css'])
			tinyMCE.importCSS(inst.getDoc(), tinyMCE.baseURL + "/plugins/php/css/content.css");
	},

	getControlHTML : function(cn) {
		switch (cn) {
			case "php":
				return tinyMCE.getButtonHTML(cn, 'lang_php_desc', '{$pluginurl}/images/php.gif', 'mcePHP');
		}

		return "";
	},

	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mcePHP":
				var name = "", php_code = "", php_width = "", php_height = "", action = "insert";
				var template = new Array();
				var inst = tinyMCE.getInstanceById(editor_id);
				var focusElm = inst.getFocusElement();

				template['file']   = '../../plugins/php/php.htm'; // Relative to theme
				template['width']  = 555;
				template['height'] = 430;

				template['width'] += tinyMCE.getLang('lang_php_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_php_delta_height', 0);

				// Is selection a image
				if (focusElm != null && focusElm.nodeName.toLowerCase() == "img") {
					name = tinyMCE.getAttrib(focusElm, 'class');

					if (name.indexOf('mceItemPHP') == -1) // Not a Flash
						return true;

					// Get rest of Flash items
					php_code = tinyMCE.getAttrib(focusElm, 'alt');

					if (tinyMCE.getParam('convert_urls'))
						php_code = eval(tinyMCE.settings['urlconverter_callback'] + "(php_code, null, true);");

					php_width = tinyMCE.getAttrib(focusElm, 'width');
					php_height = tinyMCE.getAttrib(focusElm, 'height');
					action = "update";
				}

				tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes", php_code : php_code, php_width : php_width, php_height : php_height, action : action});
			return true;
	   }

	   // Pass to next handler in chain
	   return false;
	},

	cleanup : function(type, content) {
		switch (type) {
			case "insert_to_editor_dom":
				// Force relative/absolute
				if (tinyMCE.getParam('convert_urls')) {
					var imgs = content.getElementsByTagName("img");
					for (var i=0; i<imgs.length; i++) {
						if (tinyMCE.getAttrib(imgs[i], "class") == "mceItemPHP") {
							var src = tinyMCE.getAttrib(imgs[i], "alt");

							if (tinyMCE.getParam('convert_urls'))
								src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, null, true);");

							imgs[i].setAttribute('alt', src);
						}
					}
				}
				break;

			case "get_from_editor_dom":
				var imgs = content.getElementsByTagName("img");
				for (var i=0; i<imgs.length; i++) {
					if (tinyMCE.getAttrib(imgs[i], "class") == "mceItemPHP") {
						var src = tinyMCE.getAttrib(imgs[i], "alt");

						if (tinyMCE.getParam('convert_urls'))
							src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, null, true);");

						imgs[i].setAttribute('alt', src);
					}
				}
				break;

        case "insert_to_editor":

				var start_php = 0;
				var element_start = 0;
				for(var i = -1; ((start_php != -1) && (element_start != -1));)
				{
					element_start = content.indexOf('<', i + 1);
					element_end = content.indexOf('>', element_start);
					start_php = content.indexOf('<?',element_start);
					end_php = content.indexOf('?>', start_php);

					// mixed php/html element CASE #3 OR #5
					if((element_start < start_php) && (start_php < element_end) && (start_php != -1) && (element_start != -1))
					{
						mixed_html = true;
						mixed_php = false;
						html_element = false;
						php_element = false;

						var num_of_opens = 1;
						var parse_pointer = element_start + 1;
						while (num_of_opens > 0)
						{
							next_open_pointer = content.indexOf('<', parse_pointer);
							next_close_pointer = content.indexOf('>', parse_pointer);
							if((next_open_pointer < next_close_pointer) && (next_open_pointer != -1))
							{
								num_of_opens += 1;
								parse_pointer = next_open_pointer + 1;
							}
							else
							{
								num_of_opens -= 1;
								parse_pointer = next_close_pointer + 1;
							}
						}
						element_end = parse_pointer - 1;
						
						// insert check for xhtml empty tag style here 
						
						// determine the tag ignoring mixed case
						parse_pointer = content.indexOf(' ', element_start);
						open_tag = content.substring(element_start + 1, parse_pointer);
						open_tag = open_tag.toLowerCase();
						
						// find the matching html close tag ignoring mixed case
						parse_pointer = content.indexOf('</', element_end);
						close_tag = content.substring(parse_pointer + 2, parse_pointer + 2 + open_tag.length);
						close_tag = close_tag.toLowerCase();
						if((open_tag == close_tag) && (parse_pointer != -1))
						{
							// move the element end pointer to the end of the mixed tag
							// and capture the code
							element_end = content.indexOf('>', parse_pointer);
							code = content.substring(element_start, element_end + 1);
						}
						else
						{
							// this mixed tag has no matching end tag so fall back to last element end pointer
							// and capture the code
							code = content.substring(element_start, element_end + 1);
						}
						
						// do the transform
						code = code.replace(/"/gi, "&quot;");
						replace_code = '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + 
											(tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '	+ 'width="20" height="20" ' + 
											'border="0" class="mceItemPHP" alt="' + code + '" />';

						// Insert fixed double quote code
						content_before = content.substring(0, element_start);
						content_after = content.substring(element_end + 1);
						content = content_before + replace_code + content_after;
						element_start = content.indexOf('<img', element_start);
						element_end = content.indexOf('/>', element_start);

						i = element_end + 2;

					}

					// standard php element CASE #2 OR #4
					if((element_start == start_php) && (start_php != -1) && (element_start != -1))
					{
						mixed_html = false;
						mixed_php = false;
						html_element = false;
						php_element = true;

						code = content.substring(start_php + 2, end_php);
						end_php += 2;
						code = code.replace(/"/gi, "&quot;");
						replace_code = '<img src="' + (tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" mce_src="' + 
											(tinyMCE.getParam("theme_href") + "/images/spacer.gif") + '" '	+ 'width="20" height="20" ' + 
											'border="0" class="mceItemPHP" alt="<?' + code + '?>" />';

						// Insert fixed double quote code
						content_before = content.substring(0, start_php);
						content_after = content.substring(end_php);
						content = content_before + replace_code + content_after;
						start_php = content.indexOf('<img', start_php);
						end_php = content.indexOf('/>', start_php);

						i = end_php + 2;
					}

					// standard html element CASE #1
					else if((start_php != -1) && (element_start != -1))
					{
						mixed_html = false;
						mixed_php = false;
						html_element = true;
						php_element = false;

						i = element_end;
					}
				}
            break;

			case "get_from_editor":
				// Parse all img tags and replace them with object+embed
				var startPos = -1;

				while ((startPos = content.indexOf('<img', startPos+1)) != -1) {
					var endPos = content.indexOf('/>', startPos);
					var attribs = TinyMCE_PHPPlugin._parseAttributes(content.substring(startPos + 4, endPos));

					// Is not flash, skip it
					if (attribs['class'] != "mceItemPHP")
						continue;

					endPos += 2;

					var embedHTML = '';

					// Insert object + embed
					embedHTML += attribs["alt"];
					embedHTML = embedHTML.replace(/&#39;/gi, "'");
					embedHTML = embedHTML.replace(/&quot;/gi, '"');
					embedHTML = embedHTML.replace(/&lt;/gi, "<");
					embedHTML = embedHTML.replace(/&gt;/gi, ">");

					// Insert embed/object chunk
					chunkBefore = content.substring(0, startPos);
					chunkAfter = content.substring(endPos);
					content = chunkBefore + embedHTML + chunkAfter;
				}
				break;
		}

		// Pass through to next handler in chain
		return content;
	},

	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
		if (node == null)
			return;

		do {
			if (node.nodeName == "IMG" && tinyMCE.getAttrib(node, 'class').indexOf('mceItemPHP') == 0) {
				tinyMCE.switchClass(editor_id + '_php', 'mceButtonSelected');
				return true;
			}
		} while ((node = node.parentNode));

		tinyMCE.switchClass(editor_id + '_php', 'mceButtonNormal');

		return true;
	},

	// Private plugin internal functions

	_parseAttributes : function(attribute_string) {
		var attributeName = "";
		var attributeValue = "";
		var withInName;
		var withInValue;
		var attributes = new Array();
		var whiteSpaceRegExp = new RegExp('^[ \n\r\t]+', 'g');

		if (attribute_string == null || attribute_string.length < 2)
			return null;

		withInName = withInValue = false;

		for (var i=0; i<attribute_string.length; i++) {
			var chr = attribute_string.charAt(i);

			if ((chr == '"' || chr == "'") && !withInValue)
				withInValue = true;
			else if ((chr == '"' || chr == "'") && withInValue) {
				withInValue = false;

				var pos = attributeName.lastIndexOf(' ');
				if (pos != -1)
					attributeName = attributeName.substring(pos+1);

				attributes[attributeName.toLowerCase()] = attributeValue.substring(1);

				attributeName = "";
				attributeValue = "";
			} else if (!whiteSpaceRegExp.test(chr) && !withInName && !withInValue)
				withInName = true;

			if (chr == '=' && withInName)
				withInName = false;

			if (withInName)
				attributeName += chr;

			if (withInValue)
				attributeValue += chr;
		}

		return attributes;
	}
};

tinyMCE.addPlugin("php", TinyMCE_PHPPlugin);
