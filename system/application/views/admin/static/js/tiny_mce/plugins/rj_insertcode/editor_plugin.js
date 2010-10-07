/*******************************************************************************
  editor_plugin.js
  Copyright (c) 2009 Ryan Juckett
  http://www.ryanjuckett.com/

  This file defines the RJ_InsertCode plugin and registers it with TinyMCE.
*******************************************************************************/
(function() {
	// load the language pack
	tinymce.PluginManager.requireLangPack('rj_insertcode');

	// create the plugin
	tinymce.create('tinymce.plugins.RJ_InsertCodePlugin', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {

			// Register the command that will open the code edit window.
			ed.addCommand('RJ_InsertCode_OpenWnd', function() {
				ed.windowManager.open({
					file : url + '/rj_insertcode.html',
					width : 580 + parseInt(ed.getLang('rj_insertcode.delta_width', 0)),
					height : 500 + parseInt(ed.getLang('rj_insertcode.delta_height', 0)),
					inline : true
				}, {
					plugin_url : url // this parameter will allow the popup to load in the '_dlg' language pack
				});
			});

			// Register the button to call the open window command.
			ed.addButton('rj_insertcode', {
					title : 'rj_insertcode.desc',
					cmd : 'RJ_InsertCode_OpenWnd',
					image : url + '/img/rj_insertcode.gif'
			});
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'RJ_InsertCode',
				author : 'Ryan Juckett',
				authorurl : 'http://www.ryanjuckett.com',
				infourl : 'http://www.ryanjuckett.com',
				version : "1.1.1"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('rj_insertcode', tinymce.plugins.RJ_InsertCodePlugin);
})();