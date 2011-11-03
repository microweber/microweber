require( [ '../unit/testutils' ], function ( TestUtils ) {



Aloha.ready( function() {

	var jQuery = Aloha.jQuery;

	// testmarkup area 
	var $fillArea = jQuery( '#aloha-fill' );

	// contenteditable area
	var $testArea = jQuery( '#aloha-test' );
	
	// testmarkup source area
	var $sourceArea = jQuery( '#aloha-source' );
	
	var $command = jQuery('#command');
	var $commandValue = jQuery('#command-value');
	
	var $fillButton = jQuery('#testbox-fill');
	
	var	applyMarkupOnNextSelection = true;
	var	engine = Aloha;
	var	selectionRange;
	var	selectionTimeout;
	var	supportedCommands = Aloha.querySupportedCommands().sort();
	var savedRange;
	init();
	
	/**
	 * Initalize the testbox
	 */
	function init() {

		// Populate the dropdown
		for ( var i=0; i < supportedCommands.length; i++ ) {
			$command.append('<option value="' + supportedCommands[i] +'">' + supportedCommands[i] + '</option>');
		}

		// we never want to see the floatingmenu here
		var floatingMenu = Aloha.require('aloha/floatingmenu');
		floatingMenu.doLayout = function() {
			this.hide();
		};

		// Enable aloha for testbox area
		$testArea.aloha();

		registerHandlers();
	}
	
	/**
	 * Registers all event handlers
	 */
	function registerHandlers() {
		
		$fillArea.change( function () {
			applyMarkupOnNextSelection = true;
		});

		// Set the initial selection when the document is ready
		jQuery( onSelectionChanged );
		
		// Handle selection events within the testarea. 
		// This also preserves the selection/ranges within aloha
		$testArea.contentEditableSelectionChange( function() {
			onSelectionChanged();
			
			// Load the current active range and store it in the savedRange property. 
			// We need to store it since we loose the range when a blur event occurs.
			var range = Aloha.getSelection().getRangeAt(0);
			savedRange = Aloha.createRange();
			savedRange.setStart( range.startContainer, range.startOffset) ;
			savedRange.setEnd( range.endContainer, range.endOffset);
			
		});

		// Handle click on Fill Testbox button
		$fillButton.click( function() {
	           $testArea[ 0 ].innerHTML = $fillArea.val();
	           Aloha.editables[0].obj.focus();
	           applySelection( $testArea );
		 });

		// Handle changes of engines
		jQuery('[name=engine]').change( function() {
			if ( jQuery(this).val() == 'aloha' ) {
				$testArea.aloha();
				engine = Aloha;
			} else {
				$testArea.mahalo().contentEditable(true);
				engine = document;
			}
			queryCommand();
		});

		$command.change( queryCommand );
			
		// Handle click on execute button
		jQuery('#command-execute').click( function() {
			var execCommand = $command.val();
			var execCommandValue = $commandValue.val();
			
			// Check whether the user has selected a valid command 
			if (!execCommand) {
				alert('Please select a valid command and try again.');
				return;
			}
			Aloha.editables[0].obj.focus();
			
			// Place the brackets according to the user specific selection
			TestUtils.addBrackets( selectionRange );
			
			// Convert the brackets and show the selection
			applySelection( $testArea );
			
			// Apply the command
			engine.execCommand( execCommand, false, execCommandValue );			

		});
		
	}
	
	function queryCommand( ) {
		
		var execCommand = $command.val();
		var	result;
		
		if ( !selectionRange ) {
			return;
		}
		
		if ( !execCommand ) {
			jQuery('#aloha-query').hide();
			return;
		}

		jQuery('#aloha-query').show();
		jQuery('#aloha-indeterm').show();
		jQuery('#aloha-state').show();
		jQuery('#aloha-value').show();
		
		// Select the last range before applying a command
		var range = Aloha.createRange();
		range.setStart( selectionRange.startContainer, selectionRange.startOffset) ;
		range.setEnd( selectionRange.endContainer, selectionRange.endOffset);
		Aloha.getSelection().removeAllRanges();
		Aloha.getSelection().addRange(range);
		
		// "If command has no indeterminacy, raise an INVALID_ACCESS_ERR
		try {
			result = engine.queryCommandIndeterm( execCommand );
		} catch (e)	{
			if ( e === "INVALID_ACCESS_ERR" ) {
				jQuery('#aloha-indeterm').hide();
			}
		}
		jQuery('#aloha-indeterm-result').html( (result ? 'true' : 'false') );

		// "If command has no state, raise an INVALID_ACCESS_ERR exception."
		try {
			result = engine.queryCommandState( execCommand );
		} catch (e)	{
			if ( e === "INVALID_ACCESS_ERR" ) {
				jQuery('#aloha-state').hide();
			}
		}
		jQuery('#aloha-state-result').html( (result ? 'true' : 'false') );
		
		// "If command has no value, raise an INVALID_ACCESS_ERR exception."
		try {
			result = engine.queryCommandValue( execCommand );
		} catch (e)	{
			if ( e === "INVALID_ACCESS_ERR" ) {
				jQuery('#aloha-value').hide();
			}
		}
		jQuery('#aloha-value-result').html( result );
		
	};
	
	/**
	 * If applyMarkupOnNextSelection is true: we will copy the value in
	 * fillArea into the testArea, and applySelection on it.
	 *
	 * Otherwise we will check to see if one of either the start of end
	 * container is within testArea. If so we will add markers on the current
	 * range object, 
	 */
	function onSelectionChanged ( e ) {
		
		clearTimeout( selectionTimeout );

		// Don't read selection if shift is pressed
		if ( e && e.shiftKey ) {
			return;
		}

		if ( applyMarkupOnNextSelection ) {
			$testArea[0].innerHTML = $fillArea.val();
			applySelection( $testArea );
			applyMarkupOnNextSelection = false;
		}
		
		var range = getSelectionRange();
		
		if ( range ) {
			// Check that at least one of either of the end or start containers
			// is within the "selectable" testArea
			var containers = jQuery( [ range.startContainer,
				range.endContainer ] );
			
			if ( containers.length == 0 ) {
				return;
			}
			
			if ( !containers.is( $testArea ) ) {
				var parent = containers.parent();
				
				if ( !parent.is( $testArea ) &&
					 parent.parents( '#' + $testArea.attr( 'id' ) )
						.length == 0 ) {
					return;
				}
			}
			
			var timeout = 0;

			// For ie wait for double and triple clicks
			if( jQuery.browser.msie ) {
				timeout = 200;
			}

			selectionTimeout = setTimeout( function() {
				TestUtils.addBrackets( range );
				applySelection( $testArea );
 			}, timeout );
		}
	};
	
	/**
	 * Catches exceptions caused when invoking getRangeAt, without any ranges
	 * available.
	 *
	 * @return {Object:range}
	 */
	function getSelectionRange () {
		try {
			return Aloha.getSelection().getRangeAt( 0 );
		} catch ( ex ) {
			return null;
		}
	};
	
	/**
	 * Remove all occurances of the following selection marker delimiters from
	 * the given element: {, }, [, ], data-start, data-end
	 *
	 * @param {DOMElement}
	 */
	function stripMarkers ( elem ) {
		if ( elem && elem.length ) {
			elem.html( elem.html().replace(
				/\{|\[|(data\-(start|end)\s*=\s*[\"\'][^\"\']*[\"\'])|\}|\]/g,
				''
			) );
		}
	};
	
	/**
	 * If the given element contains one start selection marker and one end
	 * selection marker, then we will attempt to apply the selection on the
	 * element.
	 *
	 * @param {DOMElement} elem - DOM Element whose innerHTML contains
	 *							  selection markers defining the selection that
	 *							  should be applied to it.
	 */
	 function applySelection ( elem ) {
		 
		// Display the current selection in the viewArea
		$sourceArea.val( $testArea.html() );
		
		// convert html for processing
		var html = jQuery('<div>').text( $testArea.html() ).html();

		html = elem.html();
		
		var startMarkers = html.match( /\{|\[|data\-start/g ),
		    endMarkers = html.match( /\}|\]|data\-end/g ),
			numMarkers = 0;
		
		if ( startMarkers && startMarkers.length ) {
			numMarkers += startMarkers.length;
		}
		
		if ( endMarkers && endMarkers.length ) {
			numMarkers += endMarkers.length;
		}
		
		if ( numMarkers == 1 ) {
			Aloha.Console.warn('Collapsed selection at end of node: ');
			Aloha.Console.warn( getRange() );
			stripMarkers( elem );
		} else if ( numMarkers == 2 ) {
			
			// Identify the markers and add the new range to the element 
			var range = TestUtils.addRange( elem );
			var selection = Aloha.getSelection();
			selection.removeAllRanges();
			
			// Add the identified range
			selection.addRange( range );
			selectionRange = range;
			queryCommand();

		} else {
			// if numMarkers is > 2 then we have a problem, and we will remove
			// all markers to create a "clean-sheet"
			stripMarkers( elem );
		}
	 };

} ); // Aloha.ready

} ); // require

