/*!
 * This file is part of Aloha Editor
 * Author & Copyright (c) 2010 Gentics Software GmbH, aloha@gentics.com
 * Licensed unter the terms of http://www.aloha-editor.com/license.html
 */

define( ['testutils'], function( TestUtils ) {
	

	/**
	 * Do an markup test
	 * @param editable the editable
	 * @param startContainer
	 * @param startOffset
	 * @param endContainer
	 * @param endOffset
	 * @param markup markup to be applied
	 * @param original reference
	 * @param reference result selector
	 * @param nesting true if nesting of markup shall be allowed
	 */
	function doMarkupTest(editable, startContainer, startOffset, endContainer, endOffset, markup, original, reference, nesting) {
		// generate the range
		var range = TestUtils.generateRange(startContainer, startOffset, endContainer, endOffset);
	
		// apply the markup
		TestUtils.applyMarkup(editable, range, markup, nesting);
	
		// get the result
		var result = Aloha.editables[0].getContents(true);
	
		// get the expected results
		var expected = Aloha.jQuery(reference).contents();
	
		// compare the result with the expected result
		deepEqual(result.extractHTML(), expected.extractHTML(), 'Check Operation Result');
	
	/*
		// now apply the markup a second time
		TestUtils.applyMarkup(editable, range, markup, nesting);
	
		// get the expected results
		expected = Aloha.jQuery(original).contents();
	
		// compare the result with the expected result
		deepEqual(result.extractHTML(), expected.extractHTML(), 'Check Double Operation Result');
	*/
	};
	
	/**
	 * Do a block markup test
	 * @param editable the editable
	 * @param startContainer
	 * @param startOffset
	 * @param endContainer
	 * @param endOffset
	 * @param markup markup to be applied
	 * @param original reference
	 * @param reference result selector
	 */
	function doBlockTest(editable, startContainer, startOffset, endContainer, endOffset, markup, original, reference) {
		// generate the range
		var range = TestUtils.generateRange(startContainer, startOffset, endContainer, endOffset);
	
		// change the block markup
	    Aloha.Selection.changeMarkupOnSelection(markup);
	
		// get the result
		var result = Aloha.editables[0].getContents(true);
	
		// get the expected results
		var expected = Aloha.jQuery(reference).contents();
	
		// compare the result with the expected result
		deepEqual(result.extractHTML(), expected.extractHTML(), 'Check Operation Result');
	};

	// All other tests are done when Aloha is ready
	Aloha.ready( function() {
		module('Plaintext Markup Handling', {
			setup: function() {
				// get the editable area and the reference
				this.edit = Aloha.jQuery('#edit');
				this.ref = Aloha.jQuery('#ref-plaintext');
				// fill the editable area with the reference
				this.edit.html(this.ref.html());
				// aloha'fy the editable
				this.edit.aloha();
			},
			teardown: function() {
				// de-aloha'fy the editable
				this.edit.mahalo();
			}
		});

		// Test applying bold to the start of the text
		test('Bold at beginning', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 0, this.edit.contents().get(0), 4, jQuery('<b></b>'), '#ref-plaintext', '#ref-plaintext-start-bold');
		});

		// Test applying bold to the middle of the text
		test('Bold in the middle', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 5, this.edit.contents().get(0), 13, jQuery('<b></b>'), '#ref-plaintext', '#ref-plaintext-middle-bold');
		});

		// Test applying bold to the end of the text
		test('Bold at end', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 14, this.edit.contents().get(0), 18, jQuery('<b></b>'), '#ref-plaintext', '#ref-plaintext-end-bold');
		});

        module('Cross Markup Handling', {
			setup: function() {
				// get the editable area and the reference
				this.edit = Aloha.jQuery('#edit');
				this.ref = Aloha.jQuery('#ref-crossmarkup');
				// fill the editable area with the reference
				this.edit.html(this.ref.html());
				// aloha'fy the editable
				this.edit.aloha();
			},
			teardown: function() {
				// de-aloha'fy the editable
				this.edit.mahalo();
			}
		});

		// Test applying bold into the start of italic
		test('Bold into Italic start', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 0, this.edit.find('i').contents().get(0), 4, jQuery('<b></b>'), '#ref-crossmarkup', '#ref-crossmarkup-start-bold');
		});

		// Test applying bold within italic
		test('Bold in Italic', function() {
			doMarkupTest(this.edit, this.edit.find('i').contents().get(0), 1, this.edit.find('i').contents().get(0), 5, jQuery('<b></b>'), '#ref-crossmarkup', '#ref-crossmarkup-inner-bold');
		});

		// Test applying bold around italic
		test('Bold around Italic', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 5, this.edit.contents().get(2), 3, jQuery('<b></b>'), '#ref-crossmarkup', '#ref-crossmarkup-outer-bold');
		});

		// Test applying bold out of italic
		test('Bold out of Italic end', function() {
			doMarkupTest(this.edit, this.edit.find('i').contents().get(0), 4, this.edit.contents().get(2), 3, jQuery('<b></b>'), '#ref-crossmarkup', '#ref-crossmarkup-end-bold');
		});

		// Test applying italic into the start of italic (no nesting)
		test('Italic into Italic start', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 0, this.edit.find('i').contents().get(0), 4, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-start-italic');
		});

		// Test applying italic into the start of italic (with nesting)
		test('Italic into Italic start with nesting', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 0, this.edit.find('i').contents().get(0), 4, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-start-italic-nesting', true);
		});

		// Test applying italic within italic (no nesting)
		test('Italic in Italic', function() {
			doMarkupTest(this.edit, this.edit.find('i').contents().get(0), 1, this.edit.find('i').contents().get(0), 5, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-inner-italic');
		});

		// Test applying italic within italic (with nesting)
		test('Italic in Italic with nesting', function() {
			doMarkupTest(this.edit, this.edit.find('i').contents().get(0), 1, this.edit.find('i').contents().get(0), 5, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-inner-italic-nesting', true);
		});

		// Test applying italic around italic (no nesting)
		test('Italic around Italic', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 5, this.edit.contents().get(2), 3, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-outer-italic');
		});

		// Test applying italic around italic (with nesting)
		test('Italic around Italic with nesting', function() {
			doMarkupTest(this.edit, this.edit.contents().get(0), 5, this.edit.contents().get(2), 3, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-outer-italic-nesting', true);
		});

		// Test applying italic out of italic (no nesting)
		test('Italic out of Italic end', function() {
			doMarkupTest(this.edit, this.edit.find('i').contents().get(0), 4, this.edit.contents().get(2), 3, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-end-italic');
		});

		// Test applying italic out of italic (with nesting)
		test('Italic out of Italic end with nesting', function() {
			doMarkupTest(this.edit, this.edit.find('i').contents().get(0), 4, this.edit.contents().get(2), 3, jQuery('<i></i>'), '#ref-crossmarkup', '#ref-crossmarkup-end-italic-nesting', true);
		});

		module('Header Handling', {
			setup: function() {
				// get the editable area and the reference
				this.edit = Aloha.jQuery('#edit');
				this.ref = Aloha.jQuery('#ref-header');
				// fill the editable area with the reference
				this.edit.html(this.ref.html());
				// aloha'fy the editable
				this.edit.aloha();
			},
			teardown: function() {
				// de-aloha'fy the editable
				this.edit.mahalo();
			}
		});

		// Test applying p in first header
		test('Paragraph in first Header', function() {
			doBlockTest(this.edit, this.edit.find('h1').contents().get(0), 1, this.edit.find('h1').contents().get(0), 1, jQuery('<p></p>'), '#ref-header', '#ref-header-first-p');
		});

		// Test applying h2 in first header
		test('Header in first Header', function() {
			doBlockTest(this.edit, this.edit.find('h1').contents().get(0), 1, this.edit.find('h1').contents().get(0), 1, jQuery('<h2></h2>'), '#ref-header', '#ref-header-first-h2');
		});

		// Test applying h1 in first paragraph
		test('Header in first Paragraph', function() {
			doBlockTest(this.edit, this.edit.find('p').eq(0).contents().get(0), 1, this.edit.find('p').eq(0).contents().get(0), 1, jQuery('<h1></h1>'), '#ref-header', '#ref-header-second-h1');
		});

		// Test applying h1 in second paragraph
		test('Header in second Paragraph', function() {
			doBlockTest(this.edit, this.edit.find('p').eq(1).contents().get(0), 1, this.edit.find('p').eq(1).contents().get(0), 1, jQuery('<h1></h1>'), '#ref-header', '#ref-header-last-h1');
		});
	});
});