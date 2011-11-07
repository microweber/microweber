/*!
* This file is part of Aloha Editor Project http://aloha-editor.org
* Copyright (c) 2010-2011 Gentics Software GmbH, aloha@gentics.com
* Contributors http://aloha-editor.org/contribution.php 
* Licensed unter the terms of http://www.aloha-editor.org/license.html
*
* Aloha Editor is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.*
*
* Aloha Editor is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
(function(){

	// load jQuery with noConflict (true ) to remove anyway from global namespace
	// a user may add it by its own with var jQuery = $ = Aloha.jQuery;
	var 
		jQuery = window.jQuery.noConflict( true ),
		deferredReady,
		alohaRequire;
	
	// Ensure Aloha settings namespace and default
	window.Aloha = window.Aloha || {};

	// reset defaults. Users should use settings. 
	Aloha.defaults = {};

	// guarantee the settings namespace even if not set by user
	Aloha.settings = Aloha.settings || {};

	// set jQuery to buildin of not otherwise set
	// From here on Aloha.jQuery is always available
	Aloha.jQuery = Aloha.settings.jQuery || Aloha.jQuery || jQuery|| null;

	// Aloha define, require, preserve original require
	Aloha._require = require;
	Aloha.define = define;
	
	// Determs the base path of Aloha Editor which is supposed to be the path of aloha.js (this file)
	Aloha.settings.baseUrl = Aloha.settings.baseUrl || getBaseUrl();
	
	// aloha base path is defined by a script tag with the data attribute 
	// data-aloha-plugins and the filename aloha.js
	// no jQuery at this stage...
	function getBaseUrl() {
		
		var
			baseUrl = './',
			i,
			script,
			scripts = document.getElementsByTagName("script"),
			regexAlohaJs = /\/aloha.js$/,
			regexJs = /[^\/]*\.js$/;
		
        for ( i = 0; i < scripts.length && ( script = scripts[i] ); ++i ) {
        	
            // take aloha.js or first ocurrency of data-aloha-plugins 
        	// and script ends with .js
        	if ( regexAlohaJs.test( script.src ) ) {
        		baseUrl = script.src.replace( regexAlohaJs , '' );
        		break;
        	}            
            if ( baseUrl === './' && script.getAttribute( 'data-aloha-plugins')
            		&& regexJs.test(script.src ) ) {
            	baseUrl = script.src.replace( regexJs , '' );
            }
        }
        
		return baseUrl;
	};

	// prepare the require config object and remember it
	Aloha.settings.requireConfig = {
			context: 'aloha',
			baseUrl: Aloha.settings.baseUrl,
			locale: Aloha.settings.locale
	};
	
	// configure require and expose the Aloha.require function
	alohaRequire = require.config( Aloha.settings.requireConfig );
	Aloha.require = function( callback ) {
		
		// passes the Aloha object to the passed callback function
		if ( arguments.length == 1 && typeof callback === 'function' ) {
			return alohaRequire( ['aloha'], callback );
		}
		return alohaRequire.apply( this, arguments );
	};
	
	// create promise for 'aloha-ready' when Aloha is not yet ready
    // and fire later when 'aloha-ready' is triggered all other events bind
	deferredReady = Aloha.jQuery.Deferred();
    Aloha.bind = function( type, fn ) {
    	if ( type == 'aloha-ready' ) {
    		if ( Aloha.stage != 'alohaReady' ) {
    			deferredReady.done( fn );
    		} else {
    			fn();
    		}
    	} else {
    		Aloha.jQuery( Aloha, 'body' ).bind( type, fn );
    	}
    	return this;
    };
	
    Aloha.trigger = function( type, data ) {
    	if ( type == 'aloha-ready' ) {
    		// resolve all deferred events on dom ready and delete local var
    		Aloha.jQuery( deferredReady.resolve );
    	}
    	Aloha.jQuery( Aloha, 'body' ).trigger( type, data );
    	return this;
    };
    
	Aloha.ready = function( fn ) {
    	this.bind('aloha-ready', fn);
    	return this;
	};
}());

// define aloha object
define( 'aloha', [], function(){
	return Aloha;
});

//load Aloha dependencies
require( 
	Aloha.settings.requireConfig, 
	[
		'aloha/jquery',
		'aloha/ext',
	],
	function() {
		
		// load Aloha core files
		require(
			Aloha.settings.requireConfig, 
			[
				'vendor/jquery.json-2.2.min',
				'vendor/jquery.store',
				'aloha/rangy-core',
				'util/json2',
				'util/class',
				'util/lang',
				'util/range',
				'util/dom',
				'aloha/core',
				'aloha/editable',
				'aloha/console',
				'aloha/markup',
				'aloha/message',
				'aloha/plugin',
				'aloha/selection',
				'aloha/command',
				'aloha/jquery.patch',
				'aloha/jquery.aloha',
				'aloha/sidebar',
				'util/position',
				'aloha/ext-alohaproxy',
				'aloha/ext-alohareader',
				'aloha/ext-alohatreeloader',
				'aloha/ui',
				'aloha/ui-attributefield',
				'aloha/ui-browser',
				'aloha/floatingmenu',
				'aloha/repositorymanager',
				'aloha/repository',
				'aloha/repositoryobjects',
				'aloha/contenthandlermanager'
			],
			function() {
				
				// jQuery calls the init method when the dom is ready
				Aloha.jQuery( Aloha.init );
			}
		);
	}
);
