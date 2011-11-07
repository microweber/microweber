/**
 * $Id: editor_plugin_src.js 677 2008-03-07 13:52:41Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2008, Moxiecode Systems AB, All rights reserved.
 */
(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('phpimage');

	tinymce.create('tinymce.plugins.PhpImagePlugin', {
		init : function(ed, url) {
			// Register commands
			ed.addCommand('mcePhpImage', function() {
				// Internal image object like a flash placeholder
				if (ed.dom.getAttrib(ed.selection.getNode(), 'class').indexOf('mceItem') != -1)
					return;

				ed.windowManager.open({
					file : url + '/image.php',
					width : 480 + parseInt(ed.getLang('advimage.delta_width', 0)),
					height : 385 + parseInt(ed.getLang('advimage.delta_height', 0)),
					inline : 1
				}, {
					plugin_url : url
				});
			});

			// Register buttons
			ed.addButton('phpimage', {
				title : 'advimage.image_desc',
				cmd : 'mcePhpImage',
				image : url + '/img/image.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('phpimage', n.nodeName === 'IMG');
			});

		},

		getInfo : function() {
			return {
				longname : 'PHP image',
				author : 'James Luckhurst',
				authorurl : '',
				infourl : '',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('phpimage', tinymce.plugins.PhpImagePlugin);
})();