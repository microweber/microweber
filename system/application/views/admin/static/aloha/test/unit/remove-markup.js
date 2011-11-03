/*!
 * This file is part of Aloha Editor
 * Author & Copyright (c) 2010 Gentics Software GmbH, aloha@gentics.com
 * Licensed unter the terms of http://www.aloha-editor.com/license.html
 */

define(
['testutils'],
function( TestUtils, undefined) {
	


/**
 * Do a "remove markup" test
 * @param editable the editable
 * @param startContainer
 * @param startOffset
 * @param endContainer
 * @param endOffset
 * @param markup markup to be removed
 * @param original reference
 * @param reference result selector
 */
function doRemoveMarkupTest(editable, startContainer, startOffset, endContainer, endOffset, markup, original, reference) {
	// generate the range
	var range = TestUtils.generateRange(startContainer, startOffset, endContainer, endOffset);

	// apply the markup
	TestUtils.removeMarkup(editable, range, markup);

	// get the result
	var result = Aloha.editables[0].getContents(true);

	// get the expected results
	var expected = $(reference).contents();

	// compare the result with the expected result
	deepEqual(result.extractHTML(), expected.extractHTML(), 'Check Operation Result');
}

	// Prepare
	var	$ = window.jQuery,
		$body = $('body');

	// Test whether Aloha is properly initialized
	asyncTest('Aloha Startup Test', function() {
		Aloha.ready( function() {
			ok(true, 'Aloha Event was fired');
			start();
		});
		setTimeout(function() {
			ok(false, 'Aloha was not initialized within 60 seconds');
			start();
		}, 60000);
	});

	// All other tests are done when Aloha is ready
	Aloha.ready( function() {
		module('Remove Simple Markup', {
			setup: function() {
				// get the editable area and the reference
				this.edit = Aloha.jQuery('#edit');
				this.ref = Aloha.jQuery('#ref-remove-simple');
				// fill the editable area with the reference
				this.edit.html(this.ref.html());
				// aloha'fy the editable
				this.edit.aloha();

				// find the text node before the bold node
				this.beforeBold = this.edit.contents().get(0);
				// find the text node in the bold node
				this.bold = this.edit.find('b').eq(0).contents().get(0);
				// find the text node after the bold node
				this.afterBold = this.edit.contents().get(2);
			},

			teardown: function() {
				// de-aloha'fy the editable
				this.edit.mahalo();
			}
		});

		// Test removing markup before occurrance
		test('Before Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 0, this.beforeBold, 4, jQuery('<b></b>'), '#ref-remove-simple', '#ref-remove-simple-before');
		});

		// Test removing markup into occurrance
		test('Into Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 0, this.bold, 2, jQuery('<b></b>'), '#ref-remove-simple', '#ref-remove-simple-into');
		});

		// Test removing markup exactly from occurrance
		test('Exact Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.bold, 0, this.bold, 4, jQuery('<b></b>'), '#ref-remove-simple', '#ref-remove-simple-exact');
		});

		// Test removing markup across occurrance
		test('Across Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 2, this.afterBold, 3, jQuery('<b></b>'), '#ref-remove-simple', '#ref-remove-simple-across');
		});

		// Test removing markup out of occurrance
		test('Out of Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.bold, 2, this.afterBold, 3, jQuery('<b></b>'), '#ref-remove-simple', '#ref-remove-simple-out');
		});

		// Test removing markup after of occurrance
		test('After Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.afterBold, 3, this.afterBold, 5, jQuery('<b></b>'), '#ref-remove-simple', '#ref-remove-simple-after');
		});

        module('Remove Markup in Paragraph', {
			setup: function() {
				// get the editable area and the reference
				this.edit = Aloha.jQuery('#edit');
				this.ref = Aloha.jQuery('#ref-remove-para');
				// fill the editable area with the reference
				this.edit.html(this.ref.html());
				// aloha'fy the editable
				this.edit.aloha();

				// find the text node before the bold node
				this.beforeBold = this.edit.find('p').eq(0).contents().get(0);
				// find the text node in the bold node
				this.bold = this.edit.find('b').eq(0).contents().get(0);
				// find the text node after the bold node
				this.afterBold = this.edit.find('p').eq(0).contents().get(2);
			},

			teardown: function() {
				// de-aloha'fy the editable
				this.edit.mahalo();
			}
		});

		// Test removing markup before occurrance
		test('Before Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 0, this.beforeBold, 4, jQuery('<b></b>'), '#ref-remove-para', '#ref-remove-para-before');
		});

		// Test removing markup into occurrance
		test('Into Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 0, this.bold, 2, jQuery('<b></b>'), '#ref-remove-para', '#ref-remove-para-into');
		});

		// Test removing markup exactly from occurrance
		test('Exact Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.bold, 0, this.bold, 4, jQuery('<b></b>'), '#ref-remove-para', '#ref-remove-para-exact');
		});

		// Test removing markup across occurrance
		test('Across Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 2, this.afterBold, 3, jQuery('<b></b>'), '#ref-remove-para', '#ref-remove-para-across');
		});

		// Test removing markup out of occurrance
		test('Out of Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.bold, 2, this.afterBold, 3, jQuery('<b></b>'), '#ref-remove-para', '#ref-remove-para-out');
		});

		// Test removing markup after of occurrance
		test('After Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.afterBold, 3, this.afterBold, 5, jQuery('<b></b>'), '#ref-remove-para', '#ref-remove-para-after');
		});

        module('Remove Multiple Markup', {
			setup: function() {
				// get the editable area and the reference
				this.edit = Aloha.jQuery('#edit');
				this.ref = Aloha.jQuery('#ref-remove-multi');
				// fill the editable area with the reference
				this.edit.html(this.ref.html());
				// aloha'fy the editable
				this.edit.aloha();

				// find the text node before the bold nodes
				this.beforeBold = this.edit.contents().get(0);
				// find the text node in the first bold node
				this.firstBold = this.edit.find('b').eq(0).contents().get(0);
				// find the text node between the bold nodes
				this.betweenBold = this.edit.contents().get(2);
				// find the text node in the second bold node
				this.secondBold = this.edit.find('b').eq(1).contents().get(0);
				// find the text node after the bold nodes
				this.afterBold = this.edit.contents().get(4);
			},

			teardown: function() {
				// de-aloha'fy the editable
				this.edit.mahalo();
			}
		});

		// Test removing markup across occurrances
		test('Across Occurrances', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 2, this.afterBold, 3, jQuery('<b></b>'), '#ref-remove-multi', '#ref-remove-multi-across');
		});

		// Test removing from occurrance into another
		test('From Occurrance into another', function() {
			doRemoveMarkupTest(this.edit, this.firstBold, 2, this.secondBold, 2, jQuery('<b></b>'), '#ref-remove-multi', '#ref-remove-multi-bold2bold');
		});

        module('Remove Nested Markup', {
			setup: function() {
				// get the editable area and the reference
				this.edit = Aloha.jQuery('#edit');
				this.ref = Aloha.jQuery('#ref-remove-nested');
				// fill the editable area with the reference
				this.edit.html(this.ref.html());
				// aloha'fy the editable
				this.edit.aloha();

				// find the text node before the bold node
				this.beforeBold = this.edit.contents().get(0);
				// find the first text node in the bold node
				this.startBold = this.edit.find('b').eq(0).contents().get(0);
				// find the last text node in the bold node
				this.endBold = this.edit.find('b').eq(0).contents().get(2);
				// find the text node in the italic node
				this.italic = this.edit.find('i').eq(0).contents().get(0);
				// find the text node after the bold node
				this.afterBold = this.edit.contents().get(2);
			},

			teardown: function() {
				// de-aloha'fy the editable
				this.edit.mahalo();
			}
		});

		// Test removing markup into inner occurrance
		test('Into inner Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 5, this.italic, 3, jQuery('<i></i>'), '#ref-remove-nested', '#ref-remove-nested-inner-into');
		});

		// Test removing exactly inner occurrance
		test('Exact inner Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.italic, 0, this.italic, 6, jQuery('<i></i>'), '#ref-remove-nested', '#ref-remove-nested-inner-exact');
		});

		// Test removing markup across inner occurrance
		test('Across inner Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 5, this.afterBold, 4, jQuery('<i></i>'), '#ref-remove-nested', '#ref-remove-nested-inner-across');
		});

		// Test removing markup out of inner occurrance
		test('Out of inner Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.italic, 3, this.afterBold, 4, jQuery('<i></i>'), '#ref-remove-nested', '#ref-remove-nested-inner-out');
		});

		// Test removing markup into outer occurrance
		test('Into outer Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 5, this.italic, 3, jQuery('<b></b>'), '#ref-remove-nested', '#ref-remove-nested-outer-into');
		});

		// Test removing exactly outer occurrance
		test('Exactly outer Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.startBold, 0, this.endBold, 5, jQuery('<b></b>'), '#ref-remove-nested', '#ref-remove-nested-outer-exact');
		});

		// Test removing markup across outer occurrance
		test('Across outer Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.beforeBold, 5, this.afterBold, 4, jQuery('<b></b>'), '#ref-remove-nested', '#ref-remove-nested-outer-across');
		});

		// Test removing markup out of outer occurrance
		test('Out of outer Occurrance', function() {
			doRemoveMarkupTest(this.edit, this.italic, 3, this.afterBold, 4, jQuery('<b></b>'), '#ref-remove-nested', '#ref-remove-nested-outer-out');
		});
	});
});
