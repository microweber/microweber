/*!
* Aloha Editor
* Author & Copyright (c) 2010 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/
define(
['aloha/core', 'aloha/plugin', 'aloha/jquery', 'i18n!plugintest1/nls/i18n' ],
function(Aloha ,Plugin, jQuery, i18n ) {
	"use strict";

     return Plugin.create('plugintest1', {
    	 defaults: {
    		 value1: 1,
    		 value2: 2,
    		 value3: {
    			 valueA: 'A',
    			 valueB: 'B'
    		 }
    	 },
    	 init: function() {
    		 window.AlohaPlugin1 = 'called';
    	 }
     } );
     
});