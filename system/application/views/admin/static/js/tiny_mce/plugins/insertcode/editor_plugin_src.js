/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('insertcode', 'en,fr,he,nb,ru,ru_KOI8-R,ru_UTF-8,nn,fi,cy,es,is,pl');

/**
 * Information about the plugin.
 */
function TinyMCE_insertcode_getInfo() {
	return {
		longname : 'insertcode plugin',
		author : 'Maxime Lardenois',
		authorurl : 'http://www.jpnp.org',
		infourl : 'http://www.jpnp.org',
		version : "1.0"
	};
};

/**
 * Gets executed when a editor needs to generate a button.
 */
function TinyMCE_insertcode_getControlHTML(control_name) {
	switch (control_name) {
		case "insertcode":
			var cmd = 'tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceinsertcode\', true);return false;';
			return '<a href="javascript:' + cmd + '" onclick="' + cmd + '" target="_self" onmousedown="return false;"><img id="{$editor_id}_insertcode" src="{$pluginurl}/images/insertcode.gif" title="{$lang_insertcode_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" /></a>';
	}

	return "";
}

/**
 * Gets executed when a command is called.
 */
function TinyMCE_insertcode_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		// Remember to have the "mce" prefix for commands so they don't intersect with built in ones in the browser.
		case "mceinsertcode":
			// Show UI/Popup
			if (user_interface) {
				// Open a popup window
				var insertcode = new Array();

				insertcode['file'] = '../../plugins/insertcode/insertcode.htm'; // Relative to theme
				insertcode['width'] = 550;
				insertcode['height'] = 500;

				tinyMCE.openWindow(insertcode, {editor_id : editor_id, resizable : "yes", scrollbars : "no"});

				// Let TinyMCE know that something was modified
				tinyMCE.triggerNodeChange(false);
			} else {
				// Do a command this gets called from the insertcode popup
				alert("execCommand: mceinsertcode gets called from popup.");
			}

			return true;
	}

	// Pass to next handler in chain
	return false;
}

/**
 * Gets executed when the selection/cursor position was changed.
 */
function TinyMCE_insertcode_handleNodeChange(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
	// Deselect insertcode button
	tinyMCE.switchClassSticky(editor_id + '_insertcode', 'mceButtonNormal');

	// Select insertcode button if parent node is a pre
	if (tinyMCE.getParentElement(node, "pre", "class"))
		tinyMCE.switchClassSticky(editor_id + '_insertcode', 'mceButtonSelected');

	return true;
}