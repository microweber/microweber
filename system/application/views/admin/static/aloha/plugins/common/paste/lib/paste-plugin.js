/*!
* Aloha Editor
* Author & Copyright (c) 2010 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

define(
['aloha/core', 'aloha/plugin', 'aloha/jquery', 'aloha/command', 'aloha/console'],
function(Aloha, Plugin, jQuery, Commands, console) {
	"use strict";

	// Private Vars and Methods
	var	GENTICS = window.GENTICS,
		$window = jQuery(window),
		$document = jQuery(document);
		
	// We need to hide the editable div. We'll use clip:rect for chrome and IE, and width/height for FF
	var	$pasteDiv = jQuery('<div id="pasteContainer" style="position:absolute; clip:rect(0px, 0px, 0px, 0px);  width: 1px; height: 1px;"></div>')
			.contentEditable(true);
	
	var	pasteHandlers = [],
		pasteRange = null,
		pasteEditable = null,
		scrollTop = 0,
		scrollLeft = 0,
		height = 0;
	

	/**
	 * This method redirects the paste into the pasteDiv. After the paste occurred,
	 * the content in the pasteDiv will be modified by the pastehandlers and will
	 * then be copied into the editable.
	 */
	function redirectPaste() {

		// store the current range
		//pasteRange = new GENTICS.Utils.RangeObject(true);
		pasteRange = Aloha.getSelection().getRangeAt( 0 );
		pasteEditable = Aloha.activeEditable;

		// store the current scroll position
		scrollTop = $window.scrollTop();
		scrollLeft = $window.scrollLeft();
		height = $document.height();

		// Reposition paste container to avoid scrolling
		jQuery('#pasteContainer').css('top',scrollTop);
		jQuery('#pasteContainer').css('left',scrollLeft-200);

		// empty the pasteDiv
		$pasteDiv.contents().remove();

		// blur the active editable
		if (pasteEditable) {
			// TODO test in IE!
			//pasteEditable.blur();
			// alternative:
			pasteEditable.obj.blur();
		}

		// set the cursor into the paste div
		GENTICS.Utils.Dom.setCursorInto($pasteDiv.get(0));

		// focus the pasteDiv
		$pasteDiv.focus();
	};

	/**
	 * Get the pasted content and insert into the current editable
	 */
	function getPastedContent() {
		var that = this,
			pasteDivContents;

		// insert the content into the editable at the current range
		if (pasteRange && pasteEditable) {
			
			// activate and focus the editable
			// @todo test in IE
			//pasteEditable.activate();
			jQuery(pasteEditable.obj).click();

			pasteDivContents = $pasteDiv.html();
			if (jQuery.browser.msie && /^&nbsp;/.test(pasteDivContents)) {
				pasteDivContents = pasteDivContents.substring(6);
			}
			pasteDivContents = jQuery.trim(pasteDivContents);

			if ( Aloha.queryCommandSupported('insertHTML') ) {
				Aloha.execCommand('insertHTML', false, pasteDivContents, pasteRange);
			} else {
				Aloha.Log.error('Common.Paste', 'Command "insertHTML" not available. Enable the plugin "common/commands".');
			}

		}
		
		// unset temporary values
		pasteRange = null;
		pasteEditable = null;
		scrollTop = 0;
		scrollLeft = 0;
		height = 0;

		// empty the pasteDiv
		$pasteDiv.contents().remove();
	};


	// Public Methods
	return Plugin.create( 'paste', {
		settings: {},
//		dependencies: [ 'contenthandler' ],

		/**
		 * Initialize the PastePlugin
		 */
		init: function() {
			var that = this;

			// append the div into which we past to the document
			jQuery('body').append($pasteDiv);

			// subscribe to the event editableCreated to redirect paste events into our pasteDiv
			// TODO: move to paste command
			// http://support.mozilla.com/en-US/kb/Granting%20JavaScript%20access%20to%20the%20clipboard
			// https://code.google.com/p/zeroclipboard/
			Aloha.bind('aloha-editable-created', function(event, editable) {
				
				// the events depend on the browser
				if ( jQuery.browser.msie ) {
					// only do the ugly beforepaste hack, if we shall not access the clipboard
					if ( that.settings.noclipboardaccess ) {
						editable.obj.bind( 'beforepaste', function( event ) {
							redirectPaste();
							event.stopPropagation();
						} );
					} else {
						// this is the version using the execCommand for IE
						editable.obj.bind( 'paste', function( event ) {
							redirectPaste();
							var range = document.selection.createRange();
							range.execCommand( 'paste' );

							getPastedContent();
							// This feels rather hackish. We manually unset
							// the metaKey property because the
							// smartContentChange method will not process
							// this event if the metaKey property is set.
							event.metaKey = void 0;
							Aloha.activeEditable.smartContentChange( event );
							event.stopPropagation();
							return false;
						} );
					}
				} else {
					editable.obj.bind( 'paste', function( event ) {
						redirectPaste();
						// We need to accomodate a small amount of execution
						// window until the pasted content is actually in
						// inserted
						window.setTimeout( function() {
							getPastedContent();
							Aloha.activeEditable.smartContentChange( event );
							event.stopPropagation();
						}, 10 );
					} );
				}
			});

			// bind a handler to the paste event of the pasteDiv to get the
			// pasted content (but do this only once, not for every editable)
			if ( jQuery.browser.msie && that.settings.noclipboardaccess ) {
				$pasteDiv.bind( 'paste', function( event ) {
					window.setTimeout( function() {
						getPastedContent();
						Aloha.activeEditable.smartContentChange( event );
						event.stopPropagation();
					}, 10 );
				} );
			}
		},

		/**
		 * Register the given paste handler
		 * @param pasteHandler paste handler to be registered
		 */
		register: function( pasteHandler ) {
			console.deprecated( 'Plugins.Paste', 'register() for pasteHandler is deprecated. Use the ContentHandler Plugin instead.' );
		}
	});
});
