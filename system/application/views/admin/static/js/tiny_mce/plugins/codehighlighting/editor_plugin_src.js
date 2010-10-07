/**
 * @author Nawaf M Al Badia
 * @version 1.0 April 2008
 */

(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('codehighlighting');

	tinymce.create('tinymce.plugins.codehighlighting', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mceaddcodehighlight', function() {
				ed.windowManager.open({
					file : url + '/codehighlighting.htm',
					width : 530 + ed.getLang('codehighlighting.delta_width', 0),
					height : 500 + ed.getLang('codehighlighting.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
					some_custom_arg : 'custom arg' // Custom argument
				});
			});

			// Register example button
			ed.addButton('codehighlighting', {
				title : 'codehighlighting.codehighlighting_button_desc',
				cmd : 'mceaddcodehighlight',
				image : url + '/img/codehighlight.gif'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('codehighlighting', n.nodeName == 'IMG');
			});
		},

		/**
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'codehighlighting',
				author : 'Nawaf M Al Badia',
				authorurl : 'http://weblogs.asp.net/nawaf/',
				infourl : 'http://weblogs.asp.net/nawaf/',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('codehighlighting', tinymce.plugins.codehighlighting);
})();