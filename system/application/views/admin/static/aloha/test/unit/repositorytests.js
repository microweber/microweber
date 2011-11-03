/*!
 * This file is part of Aloha Editor
 * Author & Copyright (c) 2010 Gentics Software GmbH, aloha@gentics.com
 * Licensed unter the terms of http://www.aloha-editor.com/license.html
 */

define(
[],
function() {
	
	
	var repositoryId1 = 'testRepo1',
		repositoryId2 = 'testRepo2',
		timeout = 5000,
		manager,
		repository,
		testOrder = [
		runBasicTests,
		runGetChildrenTests,
		runQueryTests,
		runQueryResponseTests,
		runOverlappingQueryTests
	];
		
	function str (str) {
		return str.replace(/\s+/g, ' ');
	};
	
	// Test whether Aloha can load modules
	asyncTest('Aloha.require repository modules.', function() {
		var timeout = setTimeout(function() {
			ok(false, 'Aloha was not initialized within 60 seconds');
			start();
		}, 10000);
		// All other tests are done when Aloha is ready
		Aloha.require( ['aloha/repository', 'aloha/repositorymanager'],
				function ( Repository, Manager ) {
			manager = Manager;
			repository = Repository;
			Aloha.ready( runNextTest );
			clearTimeout(timeout);
			ok(true, 'Aloha Event was fired');
			start();
		});
	});
	

	
	function runNextTest () {
		if ( testOrder.length ) {
			var test = testOrder.shift();
			if ( typeof test === 'function' ) {
				test();
			}
		}
	};
	
	function runBasicTests (callbackNextTests) {
		module("Basic Tests");
		
		test(
			'Test Aloha.RepositoryManager startup state',
			function () {
				equal(
					manager.repositories.length, 0,
					'Check that repository manager contains 0 registered ' +
					'repository.'
				);
				
				// Create, and register a test repository only after we have
				// Done our first check
				new (repository.extend({
					_constructor: function () {
						this._super(repositoryId1, 'testRepository1Name');
					},
					query: function ( params, callback ) {
						var delay = params &&
									( typeof params.delay === 'number' )
										? params.delay : 0;
						
						setTimeout(function () {
							var results = [];
							
							if ( params && params.maxItems ) {
								var l = params.maxItems + 1;
								while ( --l ) {
									results.push( {} );
								}
							}
							
							callback( results );
						}, delay);
					},
					getChildren: function ( params, callback ) {
						var delay = params &&
									( typeof params.delay === 'number' )
										? params.delay : 0;
						
						setTimeout(function () {
							var results = [];
							
							if ( params && params.maxItems ) {
								var l = params.maxItems + 1;
								while ( --l ) {
									results.push( {} );
								}
							}
							
							callback( results );
						}, delay);
					}
				}))();
			}
		);
		
		test(
			'Test registering of repository',
			function () {
				equal(
					manager.repositories.length, 1,
					'Check that repository manager contains 1 registered ' +
					'repository.'
				);
				
				equal(
					manager.repositories[0] && manager.repositories[0].repositoryId, repositoryId1,
					'Check that the id of the first registered repository is "'
					+ repositoryId1 + '."'
				);
				
				runNextTest();
			}
		);
	};
	
	function runGetChildrenTests () {
		module("getChildren tests (single repository)");
		
		asyncTest(
			'Test timeouts for getChildren method',
			function () {
				var starttime = new Date;
				
				manager.getChildren({
					// Make the repository repsond 1 second too late
					delay : timeout + 1000
				}, function (response) {
					var elapsed = (new Date) - starttime;
					// We accept a slight delay to accomodate minor lags in
					// exection. We use (elapsed - 5000) and not
					// Math.abs(elapsed - 5000) however because we never
					// expect the callback to be invoked before the 5 second
					// timeout window
					var grace = 20; // ... it is *amazing*
					
					ok(
						(elapsed - timeout) < grace,
						str('												  \
							Check that the repository manager times-out on a  \
							repository that was taking more than the allowed  \
							' + timeout + ' +/= ' + grace + ' milliseconds to \
							fullfill a getChildren call.\nThis callback was	  \
							invoked after ' + elapsed + ' milliseconds.		  \
						')
					);
					
					ok(
						elapsed >= timeout,
						str('												  \
							Check that repository manager waited at least the \
							expected ' + timeout + ' milliseconds for		  \
							repositories to respond before automatically	  \
							invoking the callback function.\nThe manager	  \
							waited ' + elapsed + ' milliseconds.			  \
						')
					);
					
					starttime = new Date;
					
					manager.getChildren({
						// Make sure the repository finish before the timeout
						delay : timeout / 2
					}, function (response) {
						var elapsed = ((new Date ) - starttime);
						ok(
							elapsed < 5000,
							str(
								'Check that the repository manager invoked	  \
								this callback before the timeout was reached. \
								\nThis callback was invoked after ' + elapsed +
								' milliseconds.'
							)
						);
						
						start();
						
						runNextTest();
					});
				});
			}
		);
	};
	
	function runQueryTests () {
		module("Query timeout tests (single repository)");
		
		asyncTest(
			'Test timeouts for Aloha.RepositoryManager.query method',
			function () {
				var starttime = new Date;
				
				manager.query({
					delay : timeout + 1000
				}, function (response) {
					var elapsed = (new Date) - starttime;
					var grace = 20;
					
					ok(
						(elapsed - timeout) < grace,
						str('												  \
							Check that the repository manager times-out on a  \
							repository that was taking more than the allowed  \
							' + timeout + ' +/= ' + grace + ' milliseconds to \
							fulfill the query method.\nThis callback was	  \
							invoked after ' + elapsed + ' milliseconds.		  \
						')
					);
					
					ok(
						elapsed >= timeout,
						str('												  \
							Check that repository manager waited at least the \
							expected ' + timeout + ' milliseconds for		  \
							repositories to respond before automatically	  \
							invoking the callback function.\nThe manager	  \
							waited ' + elapsed + ' milliseconds.			  \
						')
					);
					
					starttime = new Date;
					
					manager.query({
						delay : timeout / 2
					}, function (response) {
						var elapsed = ((new Date ) - starttime);
						ok(
							elapsed < 5000,
							str(
								'Check that the repository manager invoked	  \
								this callback before the timeout was reached. \
								\nThis callback was invoked after ' + elapsed +
								' milliseconds.'
							)
						);
						
						start();
						
						runNextTest();
					});
				});
			}
		);
	};
	
	function runQueryResponseTests () {
		module("Query response tests (single repository)");
		
		asyncTest(
			'Test response object for Aloha.RepositoryManager.query method',
			function () {
				manager.query({
					maxItems : 0
				}, function (response) {
					ok(
						response && (typeof response === 'object'),
						'Check that repository manager returns a response ' +
						'object.'
					);
					
					equal(
						response.results, 0,
						'Check that the response object contains 0 results.'
					);
					
					equal(
						response.items.length, 0,
						'Check that the response object\'s "items" property ' +
						'is an empty array.'
					);
					
					var numItemsToFetch = Math.round(Math.random() * 100);
					
					manager.query({
						maxItems: numItemsToFetch
					}, function (response) {
						equal(
							response.results, numItemsToFetch,
							'Check that response object contains "' +
							numItemsToFetch + '" results.'
						);
						
						equal(
							response.results, response.items.length,
							'Check that the "results" property matches the ' +
							'length of the "items" array in the response object.'
						);
						
						start();
						
						runNextTest();
					});
				});
			}
		);
	};
	
	function runOverlappingQueryTests () {
		asyncTest(
			'Tests for overlapping queries (single repository)',
			function () {
				var starttime = new Date;
				var numOpenQueries = 0;
				
				++numOpenQueries;
				stop();
				manager.query({
					delay    : timeout + 2000,
					maxItems : 3
				}, function (response) {
					equal(
						response.results, 0,
						'Check that response object contains 0 results ' +
						'because it timed-out @ ' + ((new Date) - starttime) +
						'.'
					);
					
					--numOpenQueries;
					equal(
						numOpenQueries, 1,
						'Check that there is still 1 more query open.'
					);
					
					start();
				});
				
				// This next query should callback just before the previous
				// query times-out, and should finish after the previous has
				// timed-out
				++numOpenQueries;
				stop();
				setTimeout(function () {
					manager.query({
						delay  : timeout - 100,
						maxItems : 2
					}, function (response) {
						equal(
							response.results, 2,
							'Check that the response object contains 2 ' +
							'results @' + ((new Date) - starttime) + '.'
						);
						
						--numOpenQueries;
						equal(
							numOpenQueries, 0,
							'Check that this is the last query to be closed ' +
							'(ie: there are 0 opened queries).'
						);
						
						start();
						
						runNextTest();
					});
				}, timeout - 500); 
				
				// Before the previous query is complete, start another query
				++numOpenQueries;
				stop();
				manager.query({
					maxItems : 4
				}, function (response) {
					equal(
						response.results, 4,
						'Check that response object returns the results for ' +
						'the correct callback @ ' + ((new Date) - starttime) +
						'.'
					);
					
					--numOpenQueries;
					equal(
						numOpenQueries, 2,
						'Check that there are 2 more queries still open.'
					);
					
					start();
				});
				
				start();
			}		
		);
	};
	
	//-------------------------------------------------------------------------
	// Tests for managing multiple repositories
	//-------------------------------------------------------------------------
	
	function runQueryTestMultipleRepos () {
		
		// Create, a second repository
		new (repository.extend({
			_constructor: function() {
				this._super(repositoryId2, 'testRepository2Name');
			},
			// Will always immediately return one object
			query: function (params, callback) {
				callback([{}]);
			}
		}))();
		
		module("Query TESTS: MULTIPLE REPOSITORIES");
		
		test(
			'Test that we have 2 repositories registered',
			function () {
				equal(
					manager.repositories.length, 2,
					'Check that repository manager contains 2 registered ' +
					'repositories.'
				);
				
				equal(
					manager.repositories[1] && manager.repositories[1].repositoryId, repositoryId2,
					'Check that the id of the second registered repository ' +
					'is "' + repositoryId2 + '."'
				);
				
				equal(
					manager.repositories[0] && manager.repositories[0].repositoryId, repositoryId1,
					'Check that the id of the first registered repository is' +
					' still "' + repositoryId1 + '."'
				);
				
				start();
			}
		);
		
		asyncTest(
			'Test queries to multiple repositories through the repository manager',
			function () {
				stop();
				manager.query({
					maxItems : 2
				}, function (response) {
					equal(
						response.results, 3,
						'Check that a total of 2 + 1 results are returned ' +
						'from the 2 registered repositories'
					);
					
					start();
				});
				
				stop();
				manager.query({
					delay    : timeout + 500,
					maxItems : 5
				}, function (response) {
					equal(
						response.results, 1,
						'Check that only 1 result is returned because 1 of ' +
						'the 2 repos timed-out.'
					);
					
					equal(
						response.items[0].repositoryId,
						'testRepo2',
						'Check that the results only include those from the ' +
						'second repository returned results.'
					);
					
					start();
				});
				
				start();
			}
		);
	};
	
});