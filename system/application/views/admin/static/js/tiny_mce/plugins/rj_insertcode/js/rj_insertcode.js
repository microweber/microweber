/*******************************************************************************
  rj_insertcode.js
  Copyright (c) 2009 Ryan Juckett
  http://www.ryanjuckett.com/

  This file controls the code editing dialog window.
  
  The general logic stucture is as follows:
	On initialization:
		1) Use "rj_get_lang_select.php" to load in the language
		   selection control.
		2) Check if selected location in the main window is within a
		   highlighted code block. If it is, we strip away all of the
		   highlighting, and initialize the form with the text.
		   
	On save:
		1) Send the selected language along with the current textarea
		   to GeSHi by using "rj_get_highlighted_code.php"
		2) Output the result from GeSHi to the main window.  
*******************************************************************************/

//********************************************************************
// Provide the XMLHttpRequest class for IE 5.x-6.x
// This code is from http://en.wikipedia.org/wiki/XMLHttpRequest
//********************************************************************
if( typeof XMLHttpRequest == "undefined" ) XMLHttpRequest = function() {
  try { return new ActiveXObject("Msxml2.XMLHTTP.6.0"); } catch(e) {}
  try { return new ActiveXObject("Msxml2.XMLHTTP.3.0"); } catch(e) {}
  try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch(e) {}
  try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {}
  throw new Error( "This browser does not support XMLHttpRequest." );
};

//********************************************************************
// Define global variables.
//********************************************************************
var contentHeight	= 0; // dimensions of window content
var contentWidth	= 0;

var codeRootElement = null;	// the main window code block

var codeTypeContainerElement = null;	// the dialog codeTypeContainer element
var codeHeaderElement = null;			// the dialog codeHeader element
var codeContentElement = null;			// the dialog codeContent element
var lineNumbersElement = null;			// the dialog lineNumbers element
var startLineElement = null;			// the dialog startLine element
var useClassesElement = null;			// the dialog useClasses element

// Set the prefix applied to the language name when embedding it as a div class.
// For example, the 'cpp' language will be tagged with a div of class 'rj_insertcode_cpp'
var languageClassPrefix = 'rj_insertcode_';


//********************************************************************
// Load the dialog language pack "rj_insertcode_dlg"
//********************************************************************
tinyMCEPopup.requireLangPack();


//********************************************************************
// getElementsByClassName(element, string, string)
// Get an array of all child element of a given node that have a
// specified nodeName and className.
//********************************************************************
function getElementsByClassName(node, element_name, class_name)
{
	var elements = new Array();
	if (node && node.nodeName.toLowerCase() == element_name)
	{
		if (node.className && node.className == class_name)
		{
			elements[elements.length] = node;
		}
	}

	if (node.hasChildNodes)
	{
		for (var x=0, n=node.childNodes.length; x<n; x++)
		{
			var childElements = getElementsByClassName(node.childNodes[x], element_name, class_name);
			for (var i=0, m=childElements.length; i<m; i++)
			{
				elements[elements.length] = childElements[i];
			}
		}
	}

	return elements;
}

//********************************************************************
// getElementByClassName(element, string, string)
// Get the first child element of a given node that has a specified
// nodeName and className.
//********************************************************************
function getElementByClassName(node, element_name, class_name)
{
	var elements = getElementsByClassName(node, element_name, class_name);
	if (elements.length == 0)
	{
		return null;
	}
	
	return elements[0];
}

//********************************************************************
// captureTabs(event)
// This function captures the tab key for the content editor. It lets
// you insert a tab with the keyboard and not cause a change of focus.
// This function is based on code found at: 
// http://www.geekdaily.net/2007/06/07/javascript-enable-tabs-in-textareas/
//********************************************************************
function captureTabs(e)
{
	//get key pressed
	var key = null;
	if(window.event) key = event.keyCode;
	else if(e.which) key = e.which;

	//if tab pressed
	if(key != null && key == 9)
	{
		//IE
		if(document.selection)
		{
			//get focus
			this.focus();

			//get selection
			var sel = document.selection.createRange();

			//insert tab
			sel.text = "\t";
		}
		//Mozilla + Netscape
		else if(this.selectionStart || this.selectionStart == "0")
		{
			//save scrollbar positions
			var scrollY = this.scrollTop;
			var scrollX = this.scrollLeft;

			//get current selection
			var start = this.selectionStart;
			var end = this.selectionEnd;

			//insert tab
			this.value = this.value.substring(0,start) + "\t" + this.value.substring(end,this.value.length);

			//move cursor back to insert point
			this.focus();
			this.selectionStart = start+1;
			this.selectionEnd = start+1;

			//reset scrollbar position
			this.scrollTop = scrollY;
			this.scrollLeft = scrollX;
		}
		// Unknown browser.
		else
		{
			this.value += "\t";	
		}

		//stop the real tab press
		return false;
	}
}

//********************************************************************
// selectOptionByValue(elemet,string)
// This function sets the selected option of a select box to the
// first option with a supplied value. If there is no match, the
// slected option is not modified.
//********************************************************************
function selectOptionByValue(element,value)
{
	// set the option in the select box
	for( var optionIdx = 0; optionIdx < element.options.length; optionIdx++ )
	{
		if(	element.options[optionIdx].value == value )
		{
			element.selectedIndex = optionIdx;
			break;
		}
	}
}

//********************************************************************
// RJ_InsertCodeDialogue
// This is the dialog control for the window.
//********************************************************************
var RJ_InsertCodeDialogue =
{
	//********************************************************************
	// parseHighlightedData(string)
	// This function parses the plain code text from the highlighted
	// code text by stripping out all of the tags.
	//********************************************************************
	parseHighlightedData : function(highlightedText)
	{
		// remove carriage returns (leave newlines)
		var stripped = highlightedText.replace(/\r/gi, "");
	    
	    // replace line break tags with newlines
		stripped = stripped.replace(/<br[\s+]?\/?\>/gi,'\n'); // '<br>' or '<br\>' or '<br \>' -> newline

		// Remove reamining tags (note: tag elements in the user text
		// will be encoded with &lt; and &gt; instead of < and > so this
		// is safe
		stripped = stripped.replace(/\<[^\>]*\>/gi, "");
	    
		// convert encoded elements back to actual characters
		stripped = stripped.replace(/\&nbsp\;/gi, " ");
		stripped = stripped.replace(/\&amp\;/gi, "&");
		stripped = stripped.replace(/\&lt\;/gi, "<");
		stripped = stripped.replace(/\&gt\;/gi, ">");
		stripped = stripped.replace(/\&quot\;/gi, "\"");
	    
		// output the result
		return stripped;
	},

	//********************************************************************
	// parseInitialData()
	// This function parses the initial data for the form from a
	// previously generated code element. The "rj_get_lang_select.php"
	// file is used to generate the language selection box.
	//********************************************************************
	parseInitialData : function()
	{
		// build the params for "rj_get_lang_select.php"
		var urlParams =	'';
		if (codeRootElement != null)
		{
			// We need to remove the 'rj_insertcode_' prefix to extract the language name.
			// If no prefix is found, we will try to use the value as is (there was no
			// prefix in version 1.0.0 of the plugin).
			var hasPrefix = (codeRootElement.className.indexOf(languageClassPrefix) == 0);
			var languageName = hasPrefix ? codeRootElement.className.substring(languageClassPrefix.length) : codeRootElement.className;
		
			urlParams += 'selectedValue=' + encodeURIComponent(languageName);
		}

		// request the selection list
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.open('POST', tinymce.baseURL+'/plugins/rj_insertcode/php/rj_get_lang_select.php', false);
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlhttp.send(urlParams);

		// check for success
		if (xmlhttp.status==200)
		{
			codeTypeContainerElement.innerHTML = xmlhttp.responseText;
		}            	      
		else
		{
			alert("Problem retrieving data:" + xmlhttp.statusText);
		}
		
		// if there was a code root element, we can parse the data from it
		// Note: This assumes a specific output from GeSHi. If a new version
		//       of GeSHi changes the layout or we tell GeSHi to output in
		//       a different format, this will need to be adjusted.
		if (codeRootElement != null)
		{
			//===
			// Extract if we are using line numbers based on if the output is table based
			var	tableElements = codeRootElement.getElementsByTagName("TABLE");
			var wasUsingLineNumbers = ( tableElements.length > 0 );

			// set the option in the select box
			selectOptionByValue( lineNumbersElement, wasUsingLineNumbers ? "true" : "false" );
			
			//===
			// Extract remaining data for line number mode
			if( wasUsingLineNumbers )
			{	
				// process the table header
				var	tableHeaderElements = tableElements[0].getElementsByTagName("THEAD");
				if( tableHeaderElements.length > 0 )
				{
					var	tableDataElements = tableHeaderElements[0].getElementsByTagName("TD");
					
					// Extract the header text from the first table data cell. 
					if( tableDataElements.length > 0 )
					{
						codeHeaderElement.value = tableDataElements[0].innerHTML;
					}
				}		

				// process the table body
				var	tableBodyElements	= tableElements[0].getElementsByTagName("TBODY");
				if( tableBodyElements.length >= 1 )
				{
					var	tableDataElements = tableBodyElements[0].getElementsByTagName("TD");

					//---
					// Extract if we are using class based styles by checking if the first
					// table data cell has an assigned class.
					if( tableDataElements.length > 0 )
					{				
						var wasUsingClasses = ( tableDataElements[0].className != null && tableDataElements[0].className != "" );
							
						// set the option in the select box
						selectOptionByValue( useClassesElement, wasUsingClasses ? "true" : "false" );						
					}

					//---
					// Extract the starting line number from the first pre tag in the line number cell
					if( tableDataElements.length > 0 )
					{				
						var	preElements = tableDataElements[0].getElementsByTagName("PRE");
						if( preElements.length > 0 )
						{
							var lineNumbers = preElements[0].innerHTML.split(/(\<br[\s+]?\/?\>|\n)/i); // split on <br> or \n
							if( lineNumbers.length > 0 )
							{
								startLineElement.value = lineNumbers[0];
							}
						}
					}

					//---
					// Extract the code content from the first pre tag in code data cell
					if( tableDataElements.length > 1 )
					{				
						var	preElements = tableDataElements[1].getElementsByTagName("PRE");
						if( preElements.length > 0 )
						{
							codeContentElement.value = this.parseHighlightedData( preElements[0].innerHTML );
						}
					}
				}
			}
			// else extract data from no line numbers mode
			else
			{
				// find the div wrapping the whole code block
				var	divElements = codeRootElement.getElementsByTagName("DIV");
				if( divElements.length > 0 )
				{
					//---
					// Extract the header text from the child div.
					var	childDivElements = divElements[0].getElementsByTagName("DIV");
					if( childDivElements.length > 0 )
					{
						codeHeaderElement.value = childDivElements[0].innerHTML;
					}		

					//---
					// Extract if we are using class based styles by checking if the first 
					// child div has an assigned class.
					if( childDivElements.length > 0 )
					{				
						var wasUsingClasses = ( childDivElements[0].className != null && childDivElements[0].className != "" );
							
						// set the option in the select box
						selectOptionByValue( useClassesElement, wasUsingClasses ? "true" : "false" );						
					}
					
					//---
					// Extract the code content from the first child pre tag
					var	preElements = divElements[0].getElementsByTagName("PRE");
					if( preElements.length > 0 )
					{
						codeContentElement.value = this.parseHighlightedData( preElements[0].innerHTML );
					}
				}
			}
		}
	},

	//********************************************************************
	// insert()
	// This function will insert the code from the dialog into the main
	// document after converting it to highlighted form. Highlighting
	// is performed through 'rj_get_highlighted_code.php'. The
	// highlighted GeSHi output is then wrapped in a set of div tags
	// which we use to flag the boundaries of the rj_insert code block
	// and any other parameters that will assist in parsing the data.
	//********************************************************************
	insert : function()
	{
		// extract data from the form
		var codeTypeElement = document.getElementById('codeType');
		var codeType	= codeTypeElement.options[ codeTypeElement.selectedIndex ].value;
		var codeHeader	= codeHeaderElement.value;
		var codeContent	= codeContentElement.value;
		var lineNumbers	= lineNumbersElement.value;
		var startLine	= startLineElement.value;
		var useClasses	= useClassesElement.value;
			
		// construct paramter string for 'rj_get_highlighted_code.php'
		var urlParams =	'codeType=' + encodeURIComponent(codeType) +
						'&codeHeader=' + encodeURIComponent(codeHeader) +
						'&codeContent=' + encodeURIComponent(codeContent) +
						'&lineNumbers=' + encodeURIComponent(lineNumbers) +
						'&startLine=' + encodeURIComponent(startLine) +
						'&useClasses=' + encodeURIComponent(useClasses);

		// sent xmlhttp request
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.open('POST', tinymce.baseURL+'/plugins/rj_insertcode/php/rj_get_highlighted_code.php', false);
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlhttp.send(urlParams);
		
		// check for success
		if (xmlhttp.status==200)
		{
			// store aa reference to the editor		
			var ed = tinyMCEPopup.editor;
			
			// mark the start of a set of changes that are a single "undo" level
			tinyMCEPopup.execCommand("mceBeginUndoLevel");

			// if there is no existing root element, we need to create a new one
			if ( codeRootElement == null )
			{
				// collapse the selection
				// TODO: This is legacy from the initial InsertCode plugin. Investigate if it is needed.
				var rng = ed.selection.getRng();
				if (rng.collapse)
				{
					rng.collapse(false);
				}

				// insert the root data at the current cursor position
				tinyMCEPopup.execCommand("mceInsertContent", false, '<div class=\"rj_insertcode\"><div class=\"' + languageClassPrefix + codeType + '\">' + xmlhttp.responseText + '</div></div><p></p>');
				
				// update visual aids in the editor
				ed.addVisual(ed.getBody());
			}
			else
			{
				// update the existing root element
				codeRootElement.className = languageClassPrefix + codeType;
				codeRootElement.innerHTML = '';
				codeRootElement.innerHTML = xmlhttp.responseText;
			}

			// mark the the end of our changes
			// TODO: This is legacy from the initial InsertCode plugin. Investigate if it is needed.
			tinyMCEPopup.execCommand("mceEndUndoLevel");
			
			// dispatch the onNodeChange event
			// TODO: This is legacy from the initial InsertCode plugin. Investigate if it is needed.
			ed.nodeChanged();
		}
		else
		{
			alert("Problem retrieving data:" + xmlhttp.statusText);
		}
	},

	//********************************************************************
	// insertAndClose()
	// This function will insert the code from the dialog into the main
	// document and close the dialog
	//********************************************************************
	insertAndClose : function()
	{
		this.insert();
		tinyMCEPopup.close();   
	},

	//********************************************************************
	// resizeInputs()
	// This function resizes our document elements to the current window
	// size
	//********************************************************************
	resizeInputs : function()
	{
		if (!tinymce.isIE)
		{
			 contentHeight	= self.innerHeight - (210);
		}
		else
		{
			 contentHeight	= document.body.clientHeight - (210);
		}

		codeContentElement.style.height = Math.abs(contentHeight) + 'px';
	},
	
	//********************************************************************
	// init()
	// This function is called when the dialog initializes. It fills in
	// all of the elements with the appropriate data and sizes the
	// window.
	//********************************************************************
	init : function()
	{
		//===
		// Get a reference to the editor
		var ed = tinyMCEPopup.editor;

		//===
		// initialize the window size		
		tinyMCEPopup.resizeToInnerSize();
		
		//===
		// initialize element references
		codeTypeContainerElement	= document.getElementById('codeTypeContainer');
		codeHeaderElement			= document.getElementById('codeHeader');
		codeContentElement			= document.getElementById('codeContent');		
		lineNumbersElement			= document.getElementById('lineNumbers');		
		startLineElement			= document.getElementById('startLine');		
		useClassesElement			= document.getElementById('useClasses');		
		
		//===
		// Allow tab input in the content window
		codeContentElement.onkeydown = captureTabs;
		
		//===
		// Try to find an existing code element in the main document that we can extract from.
		// If we don't find one to update, we will create a new code element in the document
		// on submission.
	
		// search for the tag under the <div class="rj_insertcode"> tag
		if( codeRootElement == null )
		{
			codeRootElement = ed.dom.getParent(	ed.selection.getNode(),
								function(n) { return n.parentNode && n.parentNode.tagName == "DIV" && n.parentNode.className == "rj_insertcode"; } );
		}
		
		//===
		// Initialize the data of the form
		this.parseInitialData();
		
		//===
		// Resize our control elements to the window size
		this.resizeInputs();
    },
    
    //********************************************************************
	// onResize()
	// This function is called every time the dialog window is resized
	//********************************************************************
	onResize : function()
	{
		this.resizeInputs();
	}
};

//********************************************************************
// Execute the init method on page load using the
// RJ_InsertCodeDialogue scope.
//********************************************************************
tinyMCEPopup.onInit.add(RJ_InsertCodeDialogue.init, RJ_InsertCodeDialogue);