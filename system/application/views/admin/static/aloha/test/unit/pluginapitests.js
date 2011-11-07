/*!
 * This file is part of Aloha Editor
 * Author & Copyright (c) 2010 Gentics Software GmbH, aloha@gentics.com
 * Licensed unter the terms of http://www.aloha-editor.com/license.html
 */

define(
[],
function() {
	"use strict";
	
	Aloha.ready( function() {
	
		var testOrder = [
		     		settingsTest,
		     		pathsTest,
		     		relativResourceTest,
		     		absoluteResourceTest,
		     		defaultLocaleTest,
		     		germanLocaleTest,
		     		asyncModuleTest,
		     		crossAsyncModuleTest
		     	];
		
		function runNextTest () {
			if ( testOrder.length ) {
				var test = testOrder.shift();
				if ( typeof test === 'function' ) {
					test();
				}
			}
		};
		
		module('Plugin API test', {});
		
		test('Aloha plugin invocation Test', function() {
			equal(window.AlohaPlugin1, 'called', 'Checking window.AlohaPlugin1.');
		});
		
		runNextTest();

		function settingsTest() {
			asyncTest( 'Aloha plugin defaults and settings', function() {
				var plugin = Aloha.require( 'plugintest1/plugintest1-plugin');
				ok(plugin.settings.value2 == 2, 'defaults');
				ok(plugin.settings.value1 == -1, 'settings');
				ok(plugin.settings.value3.valueB == 'B', 'nested defaults');
				ok(plugin.settings.value3.valueA == 'Z', 'nested settings');
				start();
				runNextTest();
			});
		};
		
		function pathsTest() {
			asyncTest('Aloha plugin paths [lib, vendor, nls, res, css]', function() {
				
				Aloha.require( ['plugintest1/test', 'plugintest1/vendor/test', 'i18n!plugintest1/nls/i18n',
			               'plugintest1/test', 'plugintest1/css/test'],	function( lib, vendor, i18n, res, css ) {
					ok(true, 'Plugin loaded with all path');
					ok(lib.status == 'ok', 'lib ok');
					ok(vendor.status == 'ok', 'vendor ok');
					ok(typeof i18n.t == 'function', 'nls ok');
					ok(res.status == 'ok', 'res ok');
					ok(css.status == 'ok', 'css ok');
					start();
					clearTimeout( t );
					runNextTest();
				});
	
				var t = setTimeout(function() {
					ok(false, 'Aloha plugin localization did not return in 5 seconds');
					start();
					runNextTest();
				}, 
				5000);
			});
		};

		function relativResourceTest() {
			asyncTest('Aloha relative bundle plugin resource loading', function() {
				var url = Aloha.getPluginUrl('plugintest1') + '/res/test.json';
				jQuery.ajax({
					url: url,
					dataType: 'json',		
					success: function( data ) {
						ok(true, 'Ressource1 loaded from ' + url);
						ok(data.data == 'ok', 'Loaded data is correct');
						start();
						runNextTest();
					},
					error: function( error ) {
						ok(false, 'Error: '+ error.statusText + '. URL was ' + url );
						start();
						runNextTest();
					}
				});
			});
		}

		
		function absoluteResourceTest() {
			asyncTest('Aloha absolute bundle plugin resource loading', function() {
				var url = Aloha.getPluginUrl('plugintest2') + '/res/test.json';
				jQuery.ajax({
					url: url,
					dataType: 'json',		
					success: function( data ) {
						ok(true, 'Ressource2 loaded from ' + url);
						ok(data.data == 'ok', 'Loaded data is correct');
						start();
						runNextTest();
					},
					error: function( error ) {
						ok(false, 'Failure loading plugin resource. URL was ' + url);
						start();
						runNextTest();
					}
				});
		
			});
		}

		function defaultLocaleTest() {
			asyncTest('Aloha plugin default localization (fallback)', function() {
				Aloha.require( ['i18n!plugintest2/nls/i18n'],
					function( i18n ) {
						var key = i18n.t('plugin2.test1');
						equal( key, 'fallback', 'Fallback key was loaded for plugintest2, key plugin2.test1.');
						start();
						clearTimeout( t );
						runNextTest();
					}
				);
				var t = setTimeout(function() {
					ok(false, 'Aloha plugin localization did not return in 5 seconds');
					start();
					runNextTest();
				}, 
				5000);
			});
		}		

		function germanLocaleTest() {
			asyncTest('Aloha plugin german localization', function() {
				Aloha.require( ['i18n!plugintest1/nls/i18n'],
					function( i18n ) {
						var key = i18n.t('plugin1.test1');
						equal( key, 'german', 'German key was loaded for plugintest1, key plugin1.test1.');
						start();
						clearTimeout( t );
						runNextTest();
					}
				);
				var t = setTimeout(function() {
					ok(false, 'Aloha plugin localization did not return in 5 seconds');
					start();
					runNextTest();
				}, 
				5000);
			});
			
		}
		
		function asyncModuleTest() {
			asyncTest('Aloha plugin async dynamic module loading', function() {
				Aloha.require( ['plugintest1/component'],
					function( component ) {
						ok(true, 'module loaded.');
						ok(component.doOther() == 'didOther', 'module function present.');
						ok(component.doSome() == 'didSome', 'function from dependend module present.');
						start();
						clearTimeout( t );
						runNextTest();
					}
				);
				var t = setTimeout(function() {
					ok(false, 'Aloha plugin dynamically async module loading did not return in 5 seconds');
					start();
					runNextTest();
				}, 
				5000);
			});
		}

		function crossAsyncModuleTest() {
			asyncTest('Aloha cross plugin async dynamic module loading', function() {
				Aloha.require( ['plugintest2/component'],
					function( component ) {
						ok(component.doSome() == 'didSome', 'Sucessfully dynamically async loaded cross plugin module dependency.');
						start();
						clearTimeout( t );
						runNextTest();
					}
				);
				var t = setTimeout(function() {
					ok(false, 'Aloha plugin dynamically async module loading did not return in 5 seconds');
					start();
					runNextTest();
				}, 
				5000);
			});
		}		
		
	});
});