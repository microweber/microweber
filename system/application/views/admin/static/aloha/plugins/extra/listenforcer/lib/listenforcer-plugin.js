/*!
* Aloha Editor
* Author & Copyright (c) 2010 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/
define(
['aloha/plugin', 'aloha/floatingmenu'],
function(Plugin, FloatingMenu, i18n, i18nCore) {
	"use strict";

	var
		jQuery = window.alohaQuery || window.jQuery, $ = jQuery,
		GENTICS = window.GENTICS,
		Aloha = window.Aloha;

     return Plugin.create('listenforcer', {
		_constructor: function(){
			this._super('listenforcer');
		},
		
		config: ['false'],
		/**
		 * Configure the available languages
		 */
		languages: ['en', 'de'],

		
		/**
		 * Initialize the plugin
		 */
		init: function () {
			var that = this;
	
			// mark active Editable with a css class
			Aloha.bind("aloha-editable-activated", function(jEvent, params) { that.enter(aEvent.editable.obj); });
			Aloha.bind("aloha-editable-deactivated", function(jEvent, params) { that.leave(aEvent.editable.obj); });
		},
		/**
		 * checks the passed editable
		 */
		enter: function (editable) {
				var that = this;
				that.check(	editable, '<ul><li><br class="GENTICS_temporary"/></li></ul>');	
		},


		/**
		 * checks the passed editable
		 */
		leave: function (editable) {
				var that = this;
				that.check(	editable, '');	
		},
				
		check: function (editable, insert) {
			var that = this;
			var config = this.getEditableConfig(editable);
			//console.log("config", config);
			if(jQuery.inArray('true',config) == -1) {
				// Return if the plugin should do nothing in this editable
				return;
			}		
			//check for the presence of at least one list
			var foundlist = false;
			jQuery(editable).find('.GENTICS_temporary').each(function(){
				jQuery(this).remove();
			});
			jQuery(editable).find('li').each(function(){
				var text = jQuery(this).text();
				var html = jQuery(this).html();
				if((text && text != "") || (html && html!="" && html.toLowerCase() !="<br/>" && html.toLowerCase() != "<br>")) {
					foundlist = true;
				}
			});
			if(!foundlist) {
				jQuery(editable).html(insert);
			}
			//if there are more lists present, merge them into one
			//and make sure that there is only a list in the editable
			var firstlist = jQuery(editable).find('ul').first();
			var htmlbuffer ="";
			jQuery(editable).find('ul').each(function(){
				if(!jQuery(this).is('ul ul')) {
					htmlbuffer = htmlbuffer + jQuery(this).html();
				}
			});
			firstlist.html(htmlbuffer);
			jQuery(editable).html(firstlist);
			/*jQuery(editable).append(firstlist);*/
		}
	});
});