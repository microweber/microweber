/*!
 * This file is part of Aloha Editor
 * Author & Copyright (c) 2010 Gentics Software GmbH, aloha@gentics.com
 * Licensed unter the terms of http://www.aloha-editor.com/license.html
 */

define( [], function() {
	
	
	// Test whether Aloha is properly initialized
	asyncTest('Aloha trigger event "aloha-ready".', function() {
		var timeout = setTimeout(function() {
			ok(false, 'Aloha did not trigger event "aloha-ready" within 60 seconds');
			start();
		}, 60000);
		Aloha.bind('aloha-ready',function() {
			clearTimeout(timeout);
			ok(true, 'Event "aloha-ready" was fired');
			start();
		});
	});

	// Test whether Aloha is properly initialized
	asyncTest( 'Aloha.ready( callback ).', function() {
		var timeout = setTimeout( function() {
			ok( false, 'Aloha did not callback Aloha.ready() within 60 seconds' );
			start();
		}, 20000);
		Aloha.ready( function() {
			clearTimeout( timeout );
			ok( true, 'Aloha.ready() was called' );
			start();
		});
	});

	// Test whether Aloha is properly initialized
	asyncTest( 'Aloha.bind(\'aloha-ready\'. callback ).', function() {
		var timeout = setTimeout( function() {
			ok( false, 'Aloha did not callback Aloha.bind( \'aloha-ready\', cb ) within 60 seconds' );
			start();
		}, 20000 );
		Aloha.bind( 'aloha-ready', function() {
			clearTimeout( timeout );
			ok( true, 'Aloha.bind( \'aloha-ready\', cb ) was called' );
			start();
		});
	});

	// Test whether Aloha is properly initialized
	asyncTest( 'Aloha.bind(\'test\').trigger(\'test\'. callback ).', function() {
		var timeout = setTimeout( function() {
			ok( false, 'Aloha.trigger(test) did not call Aloha.bind( \'test\', cb ) within 60 seconds' );
			start();
		}, 20000 );
		Aloha.bind( 'test', function() {
			clearTimeout( timeout );
			ok( true, 'Aloha.bind( \'aloha-ready\', cb ) was called' );
			start();
		})
		Aloha.trigger( 'test' );
	});
	
	// Test whether Aloha is properly initialized
//	asyncTest( '$(body).bind(\'aloha-ready\'. callback ).', function() {
//		var timeout = setTimeout( function() {
//			ok( false, 'Aloha did not callback $(body).bind( \'aloha-ready\', cb ) within 60 seconds' );
//			start();
//		}, 20000 );
//		Aloha.jQuery('body').bind( 'aloha-ready', function() {
//			clearTimeout( timeout );
//			ok( true, '$(body).bind( \'aloha-ready\', cb ) was called' );
//			start();
//		});
//	});
	
	// All other tests are done when Aloha is ready
	Aloha.ready( function() {
		
		var 
			editable = Aloha.jQuery('#edit'),
			logHistory = Aloha.Log.getLogHistory();
		
		// check whether error or warn messages were logged during startup		
		test('Aloha Error Log Test', function() {
			equal(logHistory.length, 0, 'Check number of logged messages');
		});
		
		test( 'Aloha.jQuery test', function() {
			equals( Aloha.jQuery.fn.jquery, '1.6.1', 'Delivered jQuery version is correct' );
		});

		test( 'Aloha.require() test', function() {
			equals( typeof Aloha.require, 'function', 'Aloha.require() is available' );
		});

		test( 'Aloha.define() test', function() {
			equals( typeof Aloha.define, 'function', 'Aloha.define() is available' );
		});

		test( 'Aloha.bind() test', function() {
			equals( typeof Aloha.bind, 'function', 'Aloha.bind() is available' );
		});

		test( 'Aloha.trigger() test', function() {
			equals( typeof Aloha.trigger, 'function', 'Aloha.trigger() is available' );
		});
		
		asyncTest('Aloha.settings.baseUrl', function() {
			var url = Aloha.settings.baseUrl + '/aloha.js';
			jQuery.ajax({
				url: url,
				success: function( data ) {
					ok(true, 'aloha.js can be loaded from ' + Aloha.settings.baseUrl);
					start();
				},
				error: function( error ) {
					ok(false, 'Error: '+ error.statusText + '. URL was ' + url );
					start();
				}
			});
		});
		
		// check whether alohafying of divs works
		test('Aloha Editable Test', function() {
			// in chrome and safari this test only works with every second reload
			editable.aloha();
			equals(editable.contentEditable(), true, 'Check whether div is contenteditable after .aloha()');
			editable.mahalo();
			equals(editable.contentEditable(), false, 'Check whether div is not contenteditable after .mahalo()');
		});
		
	});
});
