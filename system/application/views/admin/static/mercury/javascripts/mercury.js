/*!
 * Mercury Editor is a CoffeeScript and jQuery based WYSIWYG editor.  Documentation and other useful information can be
 * found at https://github.com/jejacks0n/mercury
 *
 * Supported browsers:
 *   - Firefox 4+
 *   - Chrome 10+
 *   - Safari 5+
 *
 * Copyright (c) 2011 Jeremy Jackson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 * Add all requires for the support libraries that integrate nicely with Mercury Editor.
 * require mercury/support/history
 *
 * Require Mercury Editor itself.
 *
 * Add all requires for plugins that extend or change the behavior of Mercury Editor.
 * require mercury/plugins/save_as_xml/plugin.js
 */

window.MercurySetup = {

  // # Mercury Configuration
  config: {
    // ## Toolbars
    //
    // This is where you can customize the toolbars by adding or removing buttons, or changing them and their
    // behaviors.  Any top level object put here will create a new toolbar.  Buttons are simply nested inside the
    // toolbars, along with button groups.
    //
    // Some toolbars are custom (the snippetable toolbar for instance), and to denote that use _custom: true.  You can
    // then build the toolbar yourself with it's own behavior.
    //
    // Buttons can be grouped, and a button group is simply a way to wrap buttons for styling -- they can also handle
    // enabling or disabling all the buttons within it by using a context.  The table button group is a good example
    // of this.
    //
    // It's important to note that each of the button names (keys), in each toolbar object must be unique, regardless
    // of if it's in a button group, or nested, etc.  This is because styling is applied to them by name, and because
    // their name is used in the event that's fired when you click on them.
    //
    // Button format: `[label, description, {type: action, type: action, etc}]`
    //
    // ### The available button types are:
    //
    // - toggle:  toggles on or off when clicked, otherwise behaves like a button
    // - modal:   opens a modal window, expects the action to be one of:
    //   1. a string url
    //   2. a function that returns a string url
    // - panel:   opens a panel dialog, expects the action to be one of:
    //   1. a string url
    //   2. a function that returns a string url
    // - palette: opens a palette window, expects the action to be one of:
    //   1. a string url
    //   2. a function that returns a string url
    // - select:  opens a pulldown style window, expects the action to be one of:
    //   1. a string url
    //   2. a function that returns a string url
    // - context: calls a callback function, expects the action to be:
    //   1. a function that returns a boolean to highlight the button
    //   note: if a function isn't provided, the key will be passed to the contextHandler, in which case a default
    //         context will be used (for more info read the Contexts section below)
    // - mode:    toggle a given mode in the editor, expects the action to be:
    //   1. a string, denoting the name of the mode
    //   note: it's assumed that when a specific mode is turned on, all other modes will be turned off, which happens
    //         automatically, thus putting the editor into a specific "state"
    // - regions: allows buttons to be enabled/disabled based on what region type has focus, expects the action to be:
    //   1. an array of region types (eg. ['editable', 'markupable'])
    // - preload: allows some dialog views to be loaded when the button is created instead of on first open, expects:
    //   1. a boolean true / false
    //   note: this is only used by panels, selects, and palettes
    //
    // Separators are any "button" that's not an array, and are expected to be a string.  You can use two different
    // separator styles: line ('-'), and spacer (' ').
    toolbars: {
      primary: {
        save:                  ['Save', 'Save this page',{    }],
        preview:               ['Preview', 'Preview this page', { toggle: true, mode: true }],
        sep1:                  ' ',
        undoredo:              {
          undo:                ['Undo', 'Undo your last action'],
          redo:                ['Redo', 'Redo your last action'],
          sep:                 ' '
          },
        insertLink:            ['Link', 'Insert Link', { modal: admin_panel+ '/mercury/view:link',  regions: ['editable', 'markupable'] }],
        insertMedia:           ['Media', 'Insert Media (images and videos)', {  preload: true, modal: admin_panel+ '/mercury/view:media', regions: ['editable', 'markupable'] }],
        insertTable:           ['Table', 'Insert Table', { modal: '/mercury/modals/table.html', regions: ['editable', 'markupable'] }],
        insertCharacter:       ['Character', 'Special Characters', { modal: '/mercury/modals/character.html', regions: ['editable', 'markupable'] }],
        snippetPanel:          ['Snippet', 'Snippet Panel', { panel: admin_panel+ '/mercury/view:snippets'  }],
        sep2:                  ' ',
        historyPanel:          ['History', 'Page Version History', { panel: admin_panel+ '/mercury/view:history', preload: false }],
        sep3:                  ' ',
        notesPanel:            ['Notes', 'Page Notes', { panel: '/mercury/panels/notes.html' }]
        },

      editable: {
        _regions:              ['editable', 'markupable'],
        predefined:            {
          style:               ['Style', null, { select: '/mercury/selects/style.html', preload: true }],
          sep1:                ' ',
          formatblock:         ['Block Format', null, { select: '/mercury/selects/formatblock.html', preload: true }],
          sep2:                '-'
          },
        colors:                {
          backColor:           ['Background Color', null, { palette: '/mercury/palettes/backcolor.html', context: true, preload: true, regions: ['editable'] }],
          sep1:                ' ',
          foreColor:           ['Text Color', null, { palette: '/mercury/palettes/forecolor.html', context: true, preload: true, regions: ['editable'] }],
          sep2:                '-'
          },
        decoration:            {
          bold:                ['Bold', null, { context: true }],
          italic:              ['Italicize', null, { context: true }],
          overline:            ['Overline', null, { context: true, regions: ['editable'] }],
          strikethrough:       ['Strikethrough', null, { context: true, regions: ['editable'] }],
          underline:           ['Underline', null, { context: true, regions: ['editable'] }],
          sep:                 '-'
          },
        script:                {
          subscript:           ['Subscript', null, { context: true }],
          superscript:         ['Superscript', null, { context: true }],
          sep: '-'
          },
        justify:               {
          justifyLeft:         ['Align Left', null, { context: true, regions: ['editable'] }],
          justifyCenter:       ['Center', null, { context: true, regions: ['editable'] }],
          justifyRight:        ['Align Right', null, { context: true, regions: ['editable'] }],
          justifyFull:         ['Justify Full', null, { context: true, regions: ['editable'] }],
          sep:                 '-'
          },
        list:                  {
          insertUnorderedList: ['Unordered List', null, { context: true }],
          insertOrderedList:   ['Numbered List', null, { context: true }],
          sep:                 '-'
          },
        indent:                {
          outdent:             ['Decrease Indentation', null],
          indent:              ['Increase Indentation', null],
          sep:                 '-'
          },
        table:                 {
          _context:            true,
          insertRowBefore:     ['Insert Table Row', 'Insert a table row before the cursor', { regions: ['editable'] }],
          insertRowAfter:      ['Insert Table Row', 'Insert a table row after the cursor', { regions: ['editable'] }],
          deleteRow:           ['Delete Table Row', 'Delete this table row', { regions: ['editable'] }],
          insertColumnBefore:  ['Insert Table Column', 'Insert a table column before the cursor', { regions: ['editable'] }],
          insertColumnAfter:   ['Insert Table Column', 'Insert a table column after the cursor', { regions: ['editable'] }],
          deleteColumn:        ['Delete Table Column', 'Delete this table column', { regions: ['editable'] }],
          sep1:                ' ',
          increaseColspan:     ['Increase Cell Columns', 'Increase the cells colspan'],
          decreaseColspan:     ['Decrease Cell Columns', 'Decrease the cells colspan and add a new cell'],
          increaseRowspan:     ['Increase Cell Rows', 'Increase the cells rowspan'],
          decreaseRowspan:     ['Decrease Cell Rows', 'Decrease the cells rowspan and add a new cell'],
          sep2:                '-'
          },
        rules:                 {
          horizontalRule:      ['Horizontal Rule', 'Insert a horizontal rule'],
          sep1:                '-'
          },
        formatting:            {
          removeFormatting:    ['Remove Formatting', 'Remove formatting for the selection', { regions: ['editable'] }],
          sep2:                ' '
          },
        editors:               {
          htmlEditor:          ['Edit HTML', 'Edit the HTML content', { regions: ['editable'] }]
          }
        },

      snippetable: {
        _custom:               true,
        actions:               {
          editSnippet:         ['Edit Snippet Settings', null],
          sep1:                ' ',
          removeSnippet:       ['Remove Snippet', null]
          }
        }
      },


    // ## Hijacking Links & Forms
    //
    // Mercury will hijack links and forms that don't have a target set, or the target is set to _self and will set it
    // to _top.  This is because the target must be set properly for Mercury to not get in the way of some
    // functionality, like proper page loads on form submissions etc.  Mercury doesn't do this to links or forms that
    // are within editable regions because it doesn't want to impact the html that's saved.  With that being explained,
    // you can add classes to links or forms that you don't want this behavior added to.  Let's say you have links that
    // open a lightbox style window, and you don't want the targets of these to be set to _top.  You can add classes to
    // this array, and they will be ignored when the hijacking is applied.
    nonHijackableClasses: [],


    // ## Ajax and CSRF Headers
    //
    // Some server frameworks require that you provide a specific header for Ajax requests.  The values for these CSRF
    // tokens are typically stored in the rendered DOM.  By default, Mercury will look for the Rails specific meta tag,
    // and provide the X-CSRF-Token header on Ajax requests, but you can modify this configuration if the system you're
    // using doesn't follow the same standard.
    csrfSelector: 'meta[name="csrf-token"]',
    csrfHeader: 'X-CSRF-Token',


    // ## Pasting & Sanitizing
    //
    // When pasting content into Mercury it may sometimes contain HTML tags and attributes.  This markup is used to
    // style the content and makes the pasted content look (and behave) the same as the original content.  This can be a
    // desired feature or an annoyance, so you can enable various sanitizing methods to clean the content when it's
    // pasted.
    //
    // ### Sanitizing options:
    // - false: no sanitizing is done, the content is pasted the exact same as it was copied by the user
    // - 'whitelist': content is cleaned using the settings specified in the tag white list (described below)
    // - 'text': all html is stripped before pasting, leaving only the raw text
    //
    // ### Using the whitelist configuration
    //
    // The white list allows you to specify tags and attributes that are allowed when pasting content.  Each item in
    // this object should contain the allowed tag, and an array of attributes that are allowed on that tag.  If the
    // allowed attributes array is empty, all attributes will be removed.  If a tag is not present in this list, it will
    // be removed, but without removing any of the text or tags inside it.
    //
    // **Note:** Content is *always* sanitized if looks like it's from MS Word or similar editors regardless of this
    // configuration.
    pasting: {
      sanitize: 'whitelist',
      whitelist: {
        h1:     [],
        h2:     [],
        h3:     [],
        h4:     [],
        h5:     [],
        h6:     [],
        table:  [],
        thead:  [],
        tbody:  [],
        tfoot:  [],
        tr:     [],
        th:     ['colspan', 'rowspan'],
        td:     ['colspan', 'rowspan'],
        div:    ['class'],
        span:   ['class'],
        ul:     [],
        ol:     [],
        li:     [],
        b:      [],
        strong: [],
        i:      [],
        em:     [],
        u:      [],
        strike: [],
        br:     [],
        p:      [],
        hr:     [],
        a:      ['href', 'target', 'title', 'name'],
        img:    ['src', 'title', 'alt']
        }
      },


    // ## Snippet Options and Preview
    //
    // When a user drags a snippet onto the page they'll be prompted to enter options for the given snippet.  The server
    // is expected to respond with a form.  Once the user submits this form, an Ajax request is sent to the server with
    // the options provided; this preview request is expected to respond with the rendered markup for the snippet.
    //
    // Name will be replaced with the snippet name (eg. example)
    snippets: {
      method: 'POST',
      optionsUrl: admin_panel+ '/mercury/view:snippets/name::name/action:options' ,
      previewUrl: admin_panel+ '/mercury/view:snippets/name::name/action:preview'  
      },


    // ## Image Uploading
    //
    // If you drag images (while pressing shift) from your desktop into regions that support it, it will be uploaded
    // to the server and inserted into the region.  This configuration allows you to specify if you want to
    // disable/enable this feature, the accepted mime-types, file size restrictions, and other things related to
    // uploading.  You can optionally provide a handler function that takes the response from the server and returns an
    // object: {image: {url: '[your provided url]'}
    //
    // **Note:** Image uploading is only supported in some region types, and some browsers.
    uploading: {
      enabled: true,
      allowedMimeTypes: ['image/jpeg', 'image/gif', 'image/png'],
      maxFileSize: 1235242880,
      inputName: 'image[image]',
      url: '/images',
      handler: false
      },


    // ## Behaviors
    //
    // Behaviors are used to change the default behaviors of a given region type when a given button is clicked.  For
    // example, you may prefer to add HR tags using an HR wrapped within a div with a classname (for styling).  You
    // can add your own complex behaviors here.
    //
    // You can see how the behavior matches up directly with the button names.  It's also important to note that the
    // callback functions are executed within the scope of the given region, so you have access to all it's methods.
    // Here's some examples to help you get started.
    behaviors: {
      //foreColor: function(selection, options) { selection.wrap('<span style="color:' + options.value.toHex() + '">', true) },
      htmlEditor: function() { Mercury.modal('/mercury/modals/htmleditor.html', { title: 'HTML Editor', fullHeight: true, handler: 'htmlEditor' }); }
      },


    // ## Contexts
    //
    // Contexts are used callback functions used for highlighting and disabling/enabling buttons and buttongroups.
    // When the cursor enters an element within an html region for instance we want to disable or highlight buttons
    // based on the properties of the given node.  You can see some examples of contexts in:
    //
    // Mercury.Toolbar.Button.contexts
    // and
    // Mercury.Toolbar.ButtonGroup.contexts

    
    // ## Region Class
    //
    // Mercury identifies editable regions by a region class.  This class has to be added in your HTML in advance, and
    // is the only real Mercury code/naming exposed in the implementation of Mercury.  To allow this to be as
    // configurable as possible, you can set the name of the class here.  When switching to preview mode, this
    // configuration is used to generate a class to indicate that Mercury is in preview mode -- which will be this
    // class with '-preview' appended (so, mercury-region-preview by default)
    regionClass: 'edit',

    
    // ## Styles
    //
    // Mercury tries to stay as much out of your code as possible, but because regions appear within your document we
    // need to include a few styles to indicate regions, as well as the different states of them (eg. focused).  These
    // styles are injected into your document, and as simple as they might be, you may want to change them.  You can do
    // so here.  {{regionClass}} will be automatically replaced with whatever you have set in the regionClass
    // configuration directive.
    injectedStyles: '' +
      '.{{regionClass}} { min-height: 10px; outline: 1px dotted #09F } ' +
      '.{{regionClass}}:focus, .{{regionClass}}.focus { outline: none; -webkit-box-shadow: 0 0 10px #09F, 0 0 1px #045; box-shadow: 0 0 10px #09F, 0 0 1px #045 }' +
      '.{{regionClass}}:after { content: "."; display: block; visibility: hidden; clear: both; height: 0; overflow: hidden; }' +
      '.{{regionClass}} table, .{{regionClass}} td, .{{regionClass}} th { border: 1px dotted red; min-width: 6px; }' +
      '.mercury-textarea { border: 0; box-sizing: border-box; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; resize: none; }' +
      '.mercury-textarea:focus { outline: none; }'
  },

  // ## Silent Mode
  //
  // Turning silent mode on will disable asking about unsaved changes before leaving the page.
  silent: false,

  // ## Debug Mode
  //
  // Turning debug mode on will log events and other various things (using console.debug if available).
  debug: false

};

if (!window.Mercury) window.Mercury = window.MercurySetup;
else if (typeof(jQuery) !== 'undefined') jQuery.extend(window.Mercury, window.MercurySetup);
/*!
 * jQuery JavaScript Library v1.6
 * http://jquery.com/
 *
 * Copyright 2011, John Resig
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * Includes Sizzle.js
 * http://sizzlejs.com/
 * Copyright 2011, The Dojo Foundation
 * Released under the MIT, BSD, and GPL Licenses.
 *
 * Date: Mon May 2 13:50:00 2011 -0400
 */

(function( window, undefined ) {

// Use the correct document accordingly with window argument (sandbox)
var document = window.document,
	navigator = window.navigator,
	location = window.location;
var jQuery = (function() {

// Define a local copy of jQuery
var jQuery = function( selector, context ) {
		// The jQuery object is actually just the init constructor 'enhanced'
		return new jQuery.fn.init( selector, context, rootjQuery );
	},

	// Map over jQuery in case of overwrite
	_jQuery = window.jQuery,

	// Map over the $ in case of overwrite
	_$ = window.$,

	// A central reference to the root jQuery(document)
	rootjQuery,

	// A simple way to check for HTML strings or ID strings
	// (both of which we optimize for)
	quickExpr = /^(?:[^<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,

	// Check if a string has a non-whitespace character in it
	rnotwhite = /\S/,

	// Used for trimming whitespace
	trimLeft = /^\s+/,
	trimRight = /\s+$/,

	// Check for digits
	rdigit = /\d/,

	// Match a standalone tag
	rsingleTag = /^<(\w+)\s*\/?>(?:<\/\1>)?$/,

	// JSON RegExp
	rvalidchars = /^[\],:{}\s]*$/,
	rvalidescape = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
	rvalidtokens = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
	rvalidbraces = /(?:^|:|,)(?:\s*\[)+/g,

	// Useragent RegExp
	rwebkit = /(webkit)[ \/]([\w.]+)/,
	ropera = /(opera)(?:.*version)?[ \/]([\w.]+)/,
	rmsie = /(msie) ([\w.]+)/,
	rmozilla = /(mozilla)(?:.*? rv:([\w.]+))?/,

	// Keep a UserAgent string for use with jQuery.browser
	userAgent = navigator.userAgent,

	// For matching the engine and version of the browser
	browserMatch,

	// The deferred used on DOM ready
	readyList,

	// The ready event handler
	DOMContentLoaded,

	// Save a reference to some core methods
	toString = Object.prototype.toString,
	hasOwn = Object.prototype.hasOwnProperty,
	push = Array.prototype.push,
	slice = Array.prototype.slice,
	trim = String.prototype.trim,
	indexOf = Array.prototype.indexOf,

	// [[Class]] -> type pairs
	class2type = {};

jQuery.fn = jQuery.prototype = {
	constructor: jQuery,
	init: function( selector, context, rootjQuery ) {
		var match, elem, ret, doc;

		// Handle $(""), $(null), or $(undefined)
		if ( !selector ) {
			return this;
		}

		// Handle $(DOMElement)
		if ( selector.nodeType ) {
			this.context = this[0] = selector;
			this.length = 1;
			return this;
		}

		// The body element only exists once, optimize finding it
		if ( selector === "body" && !context && document.body ) {
			this.context = document;
			this[0] = document.body;
			this.selector = selector;
			this.length = 1;
			return this;
		}

		// Handle HTML strings
		if ( typeof selector === "string" ) {
			// Are we dealing with HTML string or an ID?
			if ( selector.charAt(0) === "<" && selector.charAt( selector.length - 1 ) === ">" && selector.length >= 3 ) {
				// Assume that strings that start and end with <> are HTML and skip the regex check
				match = [ null, selector, null ];

			} else {
				match = quickExpr.exec( selector );
			}

			// Verify a match, and that no context was specified for #id
			if ( match && (match[1] || !context) ) {

				// HANDLE: $(html) -> $(array)
				if ( match[1] ) {
					context = context instanceof jQuery ? context[0] : context;
					doc = (context ? context.ownerDocument || context : document);

					// If a single string is passed in and it's a single tag
					// just do a createElement and skip the rest
					ret = rsingleTag.exec( selector );

					if ( ret ) {
						if ( jQuery.isPlainObject( context ) ) {
							selector = [ document.createElement( ret[1] ) ];
							jQuery.fn.attr.call( selector, context, true );

						} else {
							selector = [ doc.createElement( ret[1] ) ];
						}

					} else {
						ret = jQuery.buildFragment( [ match[1] ], [ doc ] );
						selector = (ret.cacheable ? jQuery.clone(ret.fragment) : ret.fragment).childNodes;
					}

					return jQuery.merge( this, selector );

				// HANDLE: $("#id")
				} else {
					elem = document.getElementById( match[2] );

					// Check parentNode to catch when Blackberry 4.6 returns
					// nodes that are no longer in the document #6963
					if ( elem && elem.parentNode ) {
						// Handle the case where IE and Opera return items
						// by name instead of ID
						if ( elem.id !== match[2] ) {
							return rootjQuery.find( selector );
						}

						// Otherwise, we inject the element directly into the jQuery object
						this.length = 1;
						this[0] = elem;
					}

					this.context = document;
					this.selector = selector;
					return this;
				}

			// HANDLE: $(expr, $(...))
			} else if ( !context || context.jquery ) {
				return (context || rootjQuery).find( selector );

			// HANDLE: $(expr, context)
			// (which is just equivalent to: $(context).find(expr)
			} else {
				return this.constructor( context ).find( selector );
			}

		// HANDLE: $(function)
		// Shortcut for document ready
		} else if ( jQuery.isFunction( selector ) ) {
			return rootjQuery.ready( selector );
		}

		if (selector.selector !== undefined) {
			this.selector = selector.selector;
			this.context = selector.context;
		}

		return jQuery.makeArray( selector, this );
	},

	// Start with an empty selector
	selector: "",

	// The current version of jQuery being used
	jquery: "1.6",

	// The default length of a jQuery object is 0
	length: 0,

	// The number of elements contained in the matched element set
	size: function() {
		return this.length;
	},

	toArray: function() {
		return slice.call( this, 0 );
	},

	// Get the Nth element in the matched element set OR
	// Get the whole matched element set as a clean array
	get: function( num ) {
		return num == null ?

			// Return a 'clean' array
			this.toArray() :

			// Return just the object
			( num < 0 ? this[ this.length + num ] : this[ num ] );
	},

	// Take an array of elements and push it onto the stack
	// (returning the new matched element set)
	pushStack: function( elems, name, selector ) {
		// Build a new jQuery matched element set
		var ret = this.constructor();

		if ( jQuery.isArray( elems ) ) {
			push.apply( ret, elems );

		} else {
			jQuery.merge( ret, elems );
		}

		// Add the old object onto the stack (as a reference)
		ret.prevObject = this;

		ret.context = this.context;

		if ( name === "find" ) {
			ret.selector = this.selector + (this.selector ? " " : "") + selector;
		} else if ( name ) {
			ret.selector = this.selector + "." + name + "(" + selector + ")";
		}

		// Return the newly-formed element set
		return ret;
	},

	// Execute a callback for every element in the matched set.
	// (You can seed the arguments with an array of args, but this is
	// only used internally.)
	each: function( callback, args ) {
		return jQuery.each( this, callback, args );
	},

	ready: function( fn ) {
		// Attach the listeners
		jQuery.bindReady();

		// Add the callback
		readyList.done( fn );

		return this;
	},

	eq: function( i ) {
		return i === -1 ?
			this.slice( i ) :
			this.slice( i, +i + 1 );
	},

	first: function() {
		return this.eq( 0 );
	},

	last: function() {
		return this.eq( -1 );
	},

	slice: function() {
		return this.pushStack( slice.apply( this, arguments ),
			"slice", slice.call(arguments).join(",") );
	},

	map: function( callback ) {
		return this.pushStack( jQuery.map(this, function( elem, i ) {
			return callback.call( elem, i, elem );
		}));
	},

	end: function() {
		return this.prevObject || this.constructor(null);
	},

	// For internal use only.
	// Behaves like an Array's method, not like a jQuery method.
	push: push,
	sort: [].sort,
	splice: [].splice
};

// Give the init function the jQuery prototype for later instantiation
jQuery.fn.init.prototype = jQuery.fn;

jQuery.extend = jQuery.fn.extend = function() {
	var options, name, src, copy, copyIsArray, clone,
		target = arguments[0] || {},
		i = 1,
		length = arguments.length,
		deep = false;

	// Handle a deep copy situation
	if ( typeof target === "boolean" ) {
		deep = target;
		target = arguments[1] || {};
		// skip the boolean and the target
		i = 2;
	}

	// Handle case when target is a string or something (possible in deep copy)
	if ( typeof target !== "object" && !jQuery.isFunction(target) ) {
		target = {};
	}

	// extend jQuery itself if only one argument is passed
	if ( length === i ) {
		target = this;
		--i;
	}

	for ( ; i < length; i++ ) {
		// Only deal with non-null/undefined values
		if ( (options = arguments[ i ]) != null ) {
			// Extend the base object
			for ( name in options ) {
				src = target[ name ];
				copy = options[ name ];

				// Prevent never-ending loop
				if ( target === copy ) {
					continue;
				}

				// Recurse if we're merging plain objects or arrays
				if ( deep && copy && ( jQuery.isPlainObject(copy) || (copyIsArray = jQuery.isArray(copy)) ) ) {
					if ( copyIsArray ) {
						copyIsArray = false;
						clone = src && jQuery.isArray(src) ? src : [];

					} else {
						clone = src && jQuery.isPlainObject(src) ? src : {};
					}

					// Never move original objects, clone them
					target[ name ] = jQuery.extend( deep, clone, copy );

				// Don't bring in undefined values
				} else if ( copy !== undefined ) {
					target[ name ] = copy;
				}
			}
		}
	}

	// Return the modified object
	return target;
};

jQuery.extend({
	noConflict: function( deep ) {
		if ( window.$ === jQuery ) {
			window.$ = _$;
		}

		if ( deep && window.jQuery === jQuery ) {
			window.jQuery = _jQuery;
		}

		return jQuery;
	},

	// Is the DOM ready to be used? Set to true once it occurs.
	isReady: false,

	// A counter to track how many items to wait for before
	// the ready event fires. See #6781
	readyWait: 1,

	// Hold (or release) the ready event
	holdReady: function( hold ) {
		if ( hold ) {
			jQuery.readyWait++;
		} else {
			jQuery.ready( true );
		}
	},

	// Handle when the DOM is ready
	ready: function( wait ) {
		// Either a released hold or an DOMready/load event and not yet ready
		if ( (wait === true && !--jQuery.readyWait) || (wait !== true && !jQuery.isReady) ) {
			// Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
			if ( !document.body ) {
				return setTimeout( jQuery.ready, 1 );
			}

			// Remember that the DOM is ready
			jQuery.isReady = true;

			// If a normal DOM Ready event fired, decrement, and wait if need be
			if ( wait !== true && --jQuery.readyWait > 0 ) {
				return;
			}

			// If there are functions bound, to execute
			readyList.resolveWith( document, [ jQuery ] );

			// Trigger any bound ready events
			if ( jQuery.fn.trigger ) {
				jQuery( document ).trigger( "ready" ).unbind( "ready" );
			}
		}
	},

	bindReady: function() {
		if ( readyList ) {
			return;
		}

		readyList = jQuery._Deferred();

		// Catch cases where $(document).ready() is called after the
		// browser event has already occurred.
		if ( document.readyState === "complete" ) {
			// Handle it asynchronously to allow scripts the opportunity to delay ready
			return setTimeout( jQuery.ready, 1 );
		}

		// Mozilla, Opera and webkit nightlies currently support this event
		if ( document.addEventListener ) {
			// Use the handy event callback
			document.addEventListener( "DOMContentLoaded", DOMContentLoaded, false );

			// A fallback to window.onload, that will always work
			window.addEventListener( "load", jQuery.ready, false );

		// If IE event model is used
		} else if ( document.attachEvent ) {
			// ensure firing before onload,
			// maybe late but safe also for iframes
			document.attachEvent( "onreadystatechange", DOMContentLoaded );

			// A fallback to window.onload, that will always work
			window.attachEvent( "onload", jQuery.ready );

			// If IE and not a frame
			// continually check to see if the document is ready
			var toplevel = false;

			try {
				toplevel = window.frameElement == null;
			} catch(e) {}

			if ( document.documentElement.doScroll && toplevel ) {
				doScrollCheck();
			}
		}
	},

	// See test/unit/core.js for details concerning isFunction.
	// Since version 1.3, DOM methods and functions like alert
	// aren't supported. They return false on IE (#2968).
	isFunction: function( obj ) {
		return jQuery.type(obj) === "function";
	},

	isArray: Array.isArray || function( obj ) {
		return jQuery.type(obj) === "array";
	},

	// A crude way of determining if an object is a window
	isWindow: function( obj ) {
		return obj && typeof obj === "object" && "setInterval" in obj;
	},

	isNaN: function( obj ) {
		return obj == null || !rdigit.test( obj ) || isNaN( obj );
	},

	type: function( obj ) {
		return obj == null ?
			String( obj ) :
			class2type[ toString.call(obj) ] || "object";
	},

	isPlainObject: function( obj ) {
		// Must be an Object.
		// Because of IE, we also have to check the presence of the constructor property.
		// Make sure that DOM nodes and window objects don't pass through, as well
		if ( !obj || jQuery.type(obj) !== "object" || obj.nodeType || jQuery.isWindow( obj ) ) {
			return false;
		}

		// Not own constructor property must be Object
		if ( obj.constructor &&
			!hasOwn.call(obj, "constructor") &&
			!hasOwn.call(obj.constructor.prototype, "isPrototypeOf") ) {
			return false;
		}

		// Own properties are enumerated firstly, so to speed up,
		// if last one is own, then all properties are own.

		var key;
		for ( key in obj ) {}

		return key === undefined || hasOwn.call( obj, key );
	},

	isEmptyObject: function( obj ) {
		for ( var name in obj ) {
			return false;
		}
		return true;
	},

	error: function( msg ) {
		throw msg;
	},

	parseJSON: function( data ) {
		if ( typeof data !== "string" || !data ) {
			return null;
		}

		// Make sure leading/trailing whitespace is removed (IE can't handle it)
		data = jQuery.trim( data );

		// Attempt to parse using the native JSON parser first
		if ( window.JSON && window.JSON.parse ) {
			return window.JSON.parse( data );
		}

		// Make sure the incoming data is actual JSON
		// Logic borrowed from http://json.org/json2.js
		if ( rvalidchars.test( data.replace( rvalidescape, "@" )
			.replace( rvalidtokens, "]" )
			.replace( rvalidbraces, "")) ) {

			return (new Function( "return " + data ))();

		}
		jQuery.error( "Invalid JSON: " + data );
	},

	// Cross-browser xml parsing
	// (xml & tmp used internally)
	parseXML: function( data , xml , tmp ) {

		if ( window.DOMParser ) { // Standard
			tmp = new DOMParser();
			xml = tmp.parseFromString( data , "text/xml" );
		} else { // IE
			xml = new ActiveXObject( "Microsoft.XMLDOM" );
			xml.async = "false";
			xml.loadXML( data );
		}

		tmp = xml.documentElement;

		if ( ! tmp || ! tmp.nodeName || tmp.nodeName === "parsererror" ) {
			jQuery.error( "Invalid XML: " + data );
		}

		return xml;
	},

	noop: function() {},

	// Evaluates a script in a global context
	// Workarounds based on findings by Jim Driscoll
	// http://weblogs.java.net/blog/driscoll/archive/2009/09/08/eval-javascript-global-context
	globalEval: function( data ) {
		if ( data && rnotwhite.test( data ) ) {
			// We use execScript on Internet Explorer
			// We use an anonymous function so that context is window
			// rather than jQuery in Firefox
			( window.execScript || function( data ) {
				window[ "eval" ].call( window, data );
			} )( data );
		}
	},

	nodeName: function( elem, name ) {
		return elem.nodeName && elem.nodeName.toUpperCase() === name.toUpperCase();
	},

	// args is for internal usage only
	each: function( object, callback, args ) {
		var name, i = 0,
			length = object.length,
			isObj = length === undefined || jQuery.isFunction( object );

		if ( args ) {
			if ( isObj ) {
				for ( name in object ) {
					if ( callback.apply( object[ name ], args ) === false ) {
						break;
					}
				}
			} else {
				for ( ; i < length; ) {
					if ( callback.apply( object[ i++ ], args ) === false ) {
						break;
					}
				}
			}

		// A special, fast, case for the most common use of each
		} else {
			if ( isObj ) {
				for ( name in object ) {
					if ( callback.call( object[ name ], name, object[ name ] ) === false ) {
						break;
					}
				}
			} else {
				for ( ; i < length; ) {
					if ( callback.call( object[ i ], i, object[ i++ ] ) === false ) {
						break;
					}
				}
			}
		}

		return object;
	},

	// Use native String.trim function wherever possible
	trim: trim ?
		function( text ) {
			return text == null ?
				"" :
				trim.call( text );
		} :

		// Otherwise use our own trimming functionality
		function( text ) {
			return text == null ?
				"" :
				text.toString().replace( trimLeft, "" ).replace( trimRight, "" );
		},

	// results is for internal usage only
	makeArray: function( array, results ) {
		var ret = results || [];

		if ( array != null ) {
			// The window, strings (and functions) also have 'length'
			// The extra typeof function check is to prevent crashes
			// in Safari 2 (See: #3039)
			// Tweaked logic slightly to handle Blackberry 4.7 RegExp issues #6930
			var type = jQuery.type( array );

			if ( array.length == null || type === "string" || type === "function" || type === "regexp" || jQuery.isWindow( array ) ) {
				push.call( ret, array );
			} else {
				jQuery.merge( ret, array );
			}
		}

		return ret;
	},

	inArray: function( elem, array ) {

		if ( indexOf ) {
			return indexOf.call( array, elem );
		}

		for ( var i = 0, length = array.length; i < length; i++ ) {
			if ( array[ i ] === elem ) {
				return i;
			}
		}

		return -1;
	},

	merge: function( first, second ) {
		var i = first.length,
			j = 0;

		if ( typeof second.length === "number" ) {
			for ( var l = second.length; j < l; j++ ) {
				first[ i++ ] = second[ j ];
			}

		} else {
			while ( second[j] !== undefined ) {
				first[ i++ ] = second[ j++ ];
			}
		}

		first.length = i;

		return first;
	},

	grep: function( elems, callback, inv ) {
		var ret = [], retVal;
		inv = !!inv;

		// Go through the array, only saving the items
		// that pass the validator function
		for ( var i = 0, length = elems.length; i < length; i++ ) {
			retVal = !!callback( elems[ i ], i );
			if ( inv !== retVal ) {
				ret.push( elems[ i ] );
			}
		}

		return ret;
	},

	// arg is for internal usage only
	map: function( elems, callback, arg ) {
		var value, key, ret = [],
			i = 0,
			length = elems.length,
			// jquery objects are treated as arrays
			isArray = elems instanceof jQuery || length !== undefined && typeof length === "number" && ( ( length > 0 && elems[ 0 ] && elems[ length -1 ] ) || length === 0 || jQuery.isArray( elems ) ) ;

		// Go through the array, translating each of the items to their
		if ( isArray ) {
			for ( ; i < length; i++ ) {
				value = callback( elems[ i ], i, arg );

				if ( value != null ) {
					ret[ ret.length ] = value;
				}
			}

		// Go through every key on the object,
		} else {
			for ( key in elems ) {
				value = callback( elems[ key ], key, arg );

				if ( value != null ) {
					ret[ ret.length ] = value;
				}
			}
		}

		// Flatten any nested arrays
		return ret.concat.apply( [], ret );
	},

	// A global GUID counter for objects
	guid: 1,

	// Bind a function to a context, optionally partially applying any
	// arguments.
	proxy: function( fn, context ) {
		if ( typeof context === "string" ) {
			var tmp = fn[ context ];
			context = fn;
			fn = tmp;
		}

		// Quick check to determine if target is callable, in the spec
		// this throws a TypeError, but we will just return undefined.
		if ( !jQuery.isFunction( fn ) ) {
			return undefined;
		}

		// Simulated bind
		var args = slice.call( arguments, 2 ),
			proxy = function() {
				return fn.apply( context, args.concat( slice.call( arguments ) ) );
			};

		// Set the guid of unique handler to the same of original handler, so it can be removed
		proxy.guid = fn.guid = fn.guid || proxy.guid || jQuery.guid++;

		return proxy;
	},

	// Mutifunctional method to get and set values to a collection
	// The value/s can be optionally by executed if its a function
	access: function( elems, key, value, exec, fn, pass ) {
		var length = elems.length;

		// Setting many attributes
		if ( typeof key === "object" ) {
			for ( var k in key ) {
				jQuery.access( elems, k, key[k], exec, fn, value );
			}
			return elems;
		}

		// Setting one attribute
		if ( value !== undefined ) {
			// Optionally, function values get executed if exec is true
			exec = !pass && exec && jQuery.isFunction(value);

			for ( var i = 0; i < length; i++ ) {
				fn( elems[i], key, exec ? value.call( elems[i], i, fn( elems[i], key ) ) : value, pass );
			}

			return elems;
		}

		// Getting an attribute
		return length ? fn( elems[0], key ) : undefined;
	},

	now: function() {
		return (new Date()).getTime();
	},

	// Use of jQuery.browser is frowned upon.
	// More details: http://docs.jquery.com/Utilities/jQuery.browser
	uaMatch: function( ua ) {
		ua = ua.toLowerCase();

		var match = rwebkit.exec( ua ) ||
			ropera.exec( ua ) ||
			rmsie.exec( ua ) ||
			ua.indexOf("compatible") < 0 && rmozilla.exec( ua ) ||
			[];

		return { browser: match[1] || "", version: match[2] || "0" };
	},

	sub: function() {
		function jQuerySub( selector, context ) {
			return new jQuerySub.fn.init( selector, context );
		}
		jQuery.extend( true, jQuerySub, this );
		jQuerySub.superclass = this;
		jQuerySub.fn = jQuerySub.prototype = this();
		jQuerySub.fn.constructor = jQuerySub;
		jQuerySub.sub = this.sub;
		jQuerySub.fn.init = function init( selector, context ) {
			if ( context && context instanceof jQuery && !(context instanceof jQuerySub) ) {
				context = jQuerySub( context );
			}

			return jQuery.fn.init.call( this, selector, context, rootjQuerySub );
		};
		jQuerySub.fn.init.prototype = jQuerySub.fn;
		var rootjQuerySub = jQuerySub(document);
		return jQuerySub;
	},

	browser: {}
});

// Populate the class2type map
jQuery.each("Boolean Number String Function Array Date RegExp Object".split(" "), function(i, name) {
	class2type[ "[object " + name + "]" ] = name.toLowerCase();
});

browserMatch = jQuery.uaMatch( userAgent );
if ( browserMatch.browser ) {
	jQuery.browser[ browserMatch.browser ] = true;
	jQuery.browser.version = browserMatch.version;
}

// Deprecated, use jQuery.browser.webkit instead
if ( jQuery.browser.webkit ) {
	jQuery.browser.safari = true;
}

// IE doesn't match non-breaking spaces with \s
if ( rnotwhite.test( "\xA0" ) ) {
	trimLeft = /^[\s\xA0]+/;
	trimRight = /[\s\xA0]+$/;
}

// All jQuery objects should point back to these
rootjQuery = jQuery(document);

// Cleanup functions for the document ready method
if ( document.addEventListener ) {
	DOMContentLoaded = function() {
		document.removeEventListener( "DOMContentLoaded", DOMContentLoaded, false );
		jQuery.ready();
	};

} else if ( document.attachEvent ) {
	DOMContentLoaded = function() {
		// Make sure body exists, at least, in case IE gets a little overzealous (ticket #5443).
		if ( document.readyState === "complete" ) {
			document.detachEvent( "onreadystatechange", DOMContentLoaded );
			jQuery.ready();
		}
	};
}

// The DOM ready check for Internet Explorer
function doScrollCheck() {
	if ( jQuery.isReady ) {
		return;
	}

	try {
		// If IE is used, use the trick by Diego Perini
		// http://javascript.nwbox.com/IEContentLoaded/
		document.documentElement.doScroll("left");
	} catch(e) {
		setTimeout( doScrollCheck, 1 );
		return;
	}

	// and execute any waiting functions
	jQuery.ready();
}

// Expose jQuery to the global object
return jQuery;

})();


var // Promise methods
	promiseMethods = "done fail isResolved isRejected promise then always pipe".split( " " ),
	// Static reference to slice
	sliceDeferred = [].slice;

jQuery.extend({
	// Create a simple deferred (one callbacks list)
	_Deferred: function() {
		var // callbacks list
			callbacks = [],
			// stored [ context , args ]
			fired,
			// to avoid firing when already doing so
			firing,
			// flag to know if the deferred has been cancelled
			cancelled,
			// the deferred itself
			deferred  = {

				// done( f1, f2, ...)
				done: function() {
					if ( !cancelled ) {
						var args = arguments,
							i,
							length,
							elem,
							type,
							_fired;
						if ( fired ) {
							_fired = fired;
							fired = 0;
						}
						for ( i = 0, length = args.length; i < length; i++ ) {
							elem = args[ i ];
							type = jQuery.type( elem );
							if ( type === "array" ) {
								deferred.done.apply( deferred, elem );
							} else if ( type === "function" ) {
								callbacks.push( elem );
							}
						}
						if ( _fired ) {
							deferred.resolveWith( _fired[ 0 ], _fired[ 1 ] );
						}
					}
					return this;
				},

				// resolve with given context and args
				resolveWith: function( context, args ) {
					if ( !cancelled && !fired && !firing ) {
						// make sure args are available (#8421)
						args = args || [];
						firing = 1;
						try {
							while( callbacks[ 0 ] ) {
								callbacks.shift().apply( context, args );
							}
						}
						finally {
							fired = [ context, args ];
							firing = 0;
						}
					}
					return this;
				},

				// resolve with this as context and given arguments
				resolve: function() {
					deferred.resolveWith( this, arguments );
					return this;
				},

				// Has this deferred been resolved?
				isResolved: function() {
					return !!( firing || fired );
				},

				// Cancel
				cancel: function() {
					cancelled = 1;
					callbacks = [];
					return this;
				}
			};

		return deferred;
	},

	// Full fledged deferred (two callbacks list)
	Deferred: function( func ) {
		var deferred = jQuery._Deferred(),
			failDeferred = jQuery._Deferred(),
			promise;
		// Add errorDeferred methods, then and promise
		jQuery.extend( deferred, {
			then: function( doneCallbacks, failCallbacks ) {
				deferred.done( doneCallbacks ).fail( failCallbacks );
				return this;
			},
			always: function() {
				return deferred.done.apply( deferred, arguments ).fail.apply( this, arguments );
			},
			fail: failDeferred.done,
			rejectWith: failDeferred.resolveWith,
			reject: failDeferred.resolve,
			isRejected: failDeferred.isResolved,
			pipe: function( fnDone, fnFail ) {
				return jQuery.Deferred(function( newDefer ) {
					jQuery.each( {
						done: [ fnDone, "resolve" ],
						fail: [ fnFail, "reject" ]
					}, function( handler, data ) {
						var fn = data[ 0 ],
							action = data[ 1 ],
							returned;
						if ( jQuery.isFunction( fn ) ) {
							deferred[ handler ](function() {
								returned = fn.apply( this, arguments );
								if ( jQuery.isFunction( returned.promise ) ) {
									returned.promise().then( newDefer.resolve, newDefer.reject );
								} else {
									newDefer[ action ]( returned );
								}
							});
						} else {
							deferred[ handler ]( newDefer[ action ] );
						}
					});
				}).promise();
			},
			// Get a promise for this deferred
			// If obj is provided, the promise aspect is added to the object
			promise: function( obj ) {
				if ( obj == null ) {
					if ( promise ) {
						return promise;
					}
					promise = obj = {};
				}
				var i = promiseMethods.length;
				while( i-- ) {
					obj[ promiseMethods[i] ] = deferred[ promiseMethods[i] ];
				}
				return obj;
			}
		});
		// Make sure only one callback list will be used
		deferred.done( failDeferred.cancel ).fail( deferred.cancel );
		// Unexpose cancel
		delete deferred.cancel;
		// Call given func if any
		if ( func ) {
			func.call( deferred, deferred );
		}
		return deferred;
	},

	// Deferred helper
	when: function( firstParam ) {
		var args = arguments,
			i = 0,
			length = args.length,
			count = length,
			deferred = length <= 1 && firstParam && jQuery.isFunction( firstParam.promise ) ?
				firstParam :
				jQuery.Deferred();
		function resolveFunc( i ) {
			return function( value ) {
				args[ i ] = arguments.length > 1 ? sliceDeferred.call( arguments, 0 ) : value;
				if ( !( --count ) ) {
					// Strange bug in FF4:
					// Values changed onto the arguments object sometimes end up as undefined values
					// outside the $.when method. Cloning the object into a fresh array solves the issue
					deferred.resolveWith( deferred, sliceDeferred.call( args, 0 ) );
				}
			};
		}
		if ( length > 1 ) {
			for( ; i < length; i++ ) {
				if ( args[ i ] && jQuery.isFunction( args[ i ].promise ) ) {
					args[ i ].promise().then( resolveFunc(i), deferred.reject );
				} else {
					--count;
				}
			}
			if ( !count ) {
				deferred.resolveWith( deferred, args );
			}
		} else if ( deferred !== firstParam ) {
			deferred.resolveWith( deferred, length ? [ firstParam ] : [] );
		}
		return deferred.promise();
	}
});



jQuery.support = (function() {

	var div = document.createElement( "div" ),
		all,
		a,
		select,
		opt,
		input,
		marginDiv,
		support,
		fragment,
		body,
		bodyStyle,
		tds,
		events,
		eventName,
		i,
		isSupported;

	// Preliminary tests
	div.setAttribute("className", "t");
	div.innerHTML = "   <link/><table></table><a href='/a' style='top:1px;float:left;opacity:.55;'>a</a><input type='checkbox'/>";

	all = div.getElementsByTagName( "*" );
	a = div.getElementsByTagName( "a" )[ 0 ];

	// Can't get basic test support
	if ( !all || !all.length || !a ) {
		return {};
	}

	// First batch of supports tests
	select = document.createElement( "select" );
	opt = select.appendChild( document.createElement("option") );
	input = div.getElementsByTagName( "input" )[ 0 ];

	support = {
		// IE strips leading whitespace when .innerHTML is used
		leadingWhitespace: ( div.firstChild.nodeType === 3 ),

		// Make sure that tbody elements aren't automatically inserted
		// IE will insert them into empty tables
		tbody: !div.getElementsByTagName( "tbody" ).length,

		// Make sure that link elements get serialized correctly by innerHTML
		// This requires a wrapper element in IE
		htmlSerialize: !!div.getElementsByTagName( "link" ).length,

		// Get the style information from getAttribute
		// (IE uses .cssText instead)
		style: /top/.test( a.getAttribute("style") ),

		// Make sure that URLs aren't manipulated
		// (IE normalizes it by default)
		hrefNormalized: ( a.getAttribute( "href" ) === "/a" ),

		// Make sure that element opacity exists
		// (IE uses filter instead)
		// Use a regex to work around a WebKit issue. See #5145
		opacity: /^0.55$/.test( a.style.opacity ),

		// Verify style float existence
		// (IE uses styleFloat instead of cssFloat)
		cssFloat: !!a.style.cssFloat,

		// Make sure that if no value is specified for a checkbox
		// that it defaults to "on".
		// (WebKit defaults to "" instead)
		checkOn: ( input.value === "on" ),

		// Make sure that a selected-by-default option has a working selected property.
		// (WebKit defaults to false instead of true, IE too, if it's in an optgroup)
		optSelected: opt.selected,

		// Test setAttribute on camelCase class. If it works, we need attrFixes when doing get/setAttribute (ie6/7)
		getSetAttribute: div.className !== "t",

		// Will be defined later
		submitBubbles: true,
		changeBubbles: true,
		focusinBubbles: false,
		deleteExpando: true,
		noCloneEvent: true,
		inlineBlockNeedsLayout: false,
		shrinkWrapBlocks: false,
		reliableMarginRight: true
	};

	// Make sure checked status is properly cloned
	input.checked = true;
	support.noCloneChecked = input.cloneNode( true ).checked;

	// Make sure that the options inside disabled selects aren't marked as disabled
	// (WebKit marks them as disabled)
	select.disabled = true;
	support.optDisabled = !opt.disabled;

	// Test to see if it's possible to delete an expando from an element
	// Fails in Internet Explorer
	try {
		delete div.test;
	} catch( e ) {
		support.deleteExpando = false;
	}

	if ( !div.addEventListener && div.attachEvent && div.fireEvent ) {
		div.attachEvent( "onclick", function click() {
			// Cloning a node shouldn't copy over any
			// bound event handlers (IE does this)
			support.noCloneEvent = false;
			div.detachEvent( "onclick", click );
		});
		div.cloneNode( true ).fireEvent( "onclick" );
	}

	// Check if a radio maintains it's value
	// after being appended to the DOM
	input = document.createElement("input");
	input.value = "t";
	input.setAttribute("type", "radio");
	support.radioValue = input.value === "t";

	input.setAttribute("checked", "checked");
	div.appendChild( input );
	fragment = document.createDocumentFragment();
	fragment.appendChild( div.firstChild );

	// WebKit doesn't clone checked state correctly in fragments
	support.checkClone = fragment.cloneNode( true ).cloneNode( true ).lastChild.checked;

	div.innerHTML = "";

	// Figure out if the W3C box model works as expected
	div.style.width = div.style.paddingLeft = "1px";

	// We use our own, invisible, body
	body = document.createElement( "body" );
	bodyStyle = {
		visibility: "hidden",
		width: 0,
		height: 0,
		border: 0,
		margin: 0,
		// Set background to avoid IE crashes when removing (#9028)
		background: "none"
	};
	for ( i in bodyStyle ) {
		body.style[ i ] = bodyStyle[ i ];
	}
	body.appendChild( div );
	document.documentElement.appendChild( body );

	// Check if a disconnected checkbox will retain its checked
	// value of true after appended to the DOM (IE6/7)
	support.appendChecked = input.checked;

	support.boxModel = div.offsetWidth === 2;

	if ( "zoom" in div.style ) {
		// Check if natively block-level elements act like inline-block
		// elements when setting their display to 'inline' and giving
		// them layout
		// (IE < 8 does this)
		div.style.display = "inline";
		div.style.zoom = 1;
		support.inlineBlockNeedsLayout = ( div.offsetWidth === 2 );

		// Check if elements with layout shrink-wrap their children
		// (IE 6 does this)
		div.style.display = "";
		div.innerHTML = "<div style='width:4px;'></div>";
		support.shrinkWrapBlocks = ( div.offsetWidth !== 2 );
	}

	div.innerHTML = "<table><tr><td style='padding:0;border:0;display:none'></td><td>t</td></tr></table>";
	tds = div.getElementsByTagName( "td" );

	// Check if table cells still have offsetWidth/Height when they are set
	// to display:none and there are still other visible table cells in a
	// table row; if so, offsetWidth/Height are not reliable for use when
	// determining if an element has been hidden directly using
	// display:none (it is still safe to use offsets if a parent element is
	// hidden; don safety goggles and see bug #4512 for more information).
	// (only IE 8 fails this test)
	isSupported = ( tds[ 0 ].offsetHeight === 0 );

	tds[ 0 ].style.display = "";
	tds[ 1 ].style.display = "none";

	// Check if empty table cells still have offsetWidth/Height
	// (IE < 8 fail this test)
	support.reliableHiddenOffsets = isSupported && ( tds[ 0 ].offsetHeight === 0 );
	div.innerHTML = "";

	// Check if div with explicit width and no margin-right incorrectly
	// gets computed margin-right based on width of container. For more
	// info see bug #3333
	// Fails in WebKit before Feb 2011 nightlies
	// WebKit Bug 13343 - getComputedStyle returns wrong value for margin-right
	if ( document.defaultView && document.defaultView.getComputedStyle ) {
		marginDiv = document.createElement( "div" );
		marginDiv.style.width = "0";
		marginDiv.style.marginRight = "0";
		div.appendChild( marginDiv );
		support.reliableMarginRight =
			( parseInt( document.defaultView.getComputedStyle( marginDiv, null ).marginRight, 10 ) || 0 ) === 0;
	}

	// Remove the body element we added
	body.innerHTML = "";
	document.documentElement.removeChild( body );

	// Technique from Juriy Zaytsev
	// http://thinkweb2.com/projects/prototype/detecting-event-support-without-browser-sniffing/
	// We only care about the case where non-standard event systems
	// are used, namely in IE. Short-circuiting here helps us to
	// avoid an eval call (in setAttribute) which can cause CSP
	// to go haywire. See: https://developer.mozilla.org/en/Security/CSP
	if ( div.attachEvent ) {
		for( i in {
			submit: 1,
			change: 1,
			focusin: 1
		} ) {
			eventName = "on" + i;
			isSupported = ( eventName in div );
			if ( !isSupported ) {
				div.setAttribute( eventName, "return;" );
				isSupported = ( typeof div[ eventName ] === "function" );
			}
			support[ i + "Bubbles" ] = isSupported;
		}
	}

	return support;
})();

// Keep track of boxModel
jQuery.boxModel = jQuery.support.boxModel;




var rbrace = /^(?:\{.*\}|\[.*\])$/,
	rmultiDash = /([a-z])([A-Z])/g;

jQuery.extend({
	cache: {},

	// Please use with caution
	uuid: 0,

	// Unique for each copy of jQuery on the page
	// Non-digits removed to match rinlinejQuery
	expando: "jQuery" + ( jQuery.fn.jquery + Math.random() ).replace( /\D/g, "" ),

	// The following elements throw uncatchable exceptions if you
	// attempt to add expando properties to them.
	noData: {
		"embed": true,
		// Ban all objects except for Flash (which handle expandos)
		"object": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
		"applet": true
	},

	hasData: function( elem ) {
		elem = elem.nodeType ? jQuery.cache[ elem[jQuery.expando] ] : elem[ jQuery.expando ];

		return !!elem && !isEmptyDataObject( elem );
	},

	data: function( elem, name, data, pvt /* Internal Use Only */ ) {
		if ( !jQuery.acceptData( elem ) ) {
			return;
		}

		var internalKey = jQuery.expando, getByName = typeof name === "string", thisCache,

			// We have to handle DOM nodes and JS objects differently because IE6-7
			// can't GC object references properly across the DOM-JS boundary
			isNode = elem.nodeType,

			// Only DOM nodes need the global jQuery cache; JS object data is
			// attached directly to the object so GC can occur automatically
			cache = isNode ? jQuery.cache : elem,

			// Only defining an ID for JS objects if its cache already exists allows
			// the code to shortcut on the same path as a DOM node with no cache
			id = isNode ? elem[ jQuery.expando ] : elem[ jQuery.expando ] && jQuery.expando;

		// Avoid doing any more work than we need to when trying to get data on an
		// object that has no data at all
		if ( (!id || (pvt && id && !cache[ id ][ internalKey ])) && getByName && data === undefined ) {
			return;
		}

		if ( !id ) {
			// Only DOM nodes need a new unique ID for each element since their data
			// ends up in the global cache
			if ( isNode ) {
				elem[ jQuery.expando ] = id = ++jQuery.uuid;
			} else {
				id = jQuery.expando;
			}
		}

		if ( !cache[ id ] ) {
			cache[ id ] = {};

			// TODO: This is a hack for 1.5 ONLY. Avoids exposing jQuery
			// metadata on plain JS objects when the object is serialized using
			// JSON.stringify
			if ( !isNode ) {
				cache[ id ].toJSON = jQuery.noop;
			}
		}

		// An object can be passed to jQuery.data instead of a key/value pair; this gets
		// shallow copied over onto the existing cache
		if ( typeof name === "object" || typeof name === "function" ) {
			if ( pvt ) {
				cache[ id ][ internalKey ] = jQuery.extend(cache[ id ][ internalKey ], name);
			} else {
				cache[ id ] = jQuery.extend(cache[ id ], name);
			}
		}

		thisCache = cache[ id ];

		// Internal jQuery data is stored in a separate object inside the object's data
		// cache in order to avoid key collisions between internal data and user-defined
		// data
		if ( pvt ) {
			if ( !thisCache[ internalKey ] ) {
				thisCache[ internalKey ] = {};
			}

			thisCache = thisCache[ internalKey ];
		}

		if ( data !== undefined ) {
			thisCache[ name ] = data;
		}

		// TODO: This is a hack for 1.5 ONLY. It will be removed in 1.6. Users should
		// not attempt to inspect the internal events object using jQuery.data, as this
		// internal data object is undocumented and subject to change.
		if ( name === "events" && !thisCache[name] ) {
			return thisCache[ internalKey ] && thisCache[ internalKey ].events;
		}

		return getByName ? thisCache[ name ] : thisCache;
	},

	removeData: function( elem, name, pvt /* Internal Use Only */ ) {
		if ( !jQuery.acceptData( elem ) ) {
			return;
		}

		var internalKey = jQuery.expando, isNode = elem.nodeType,

			// See jQuery.data for more information
			cache = isNode ? jQuery.cache : elem,

			// See jQuery.data for more information
			id = isNode ? elem[ jQuery.expando ] : jQuery.expando;

		// If there is already no cache entry for this object, there is no
		// purpose in continuing
		if ( !cache[ id ] ) {
			return;
		}

		if ( name ) {
			var thisCache = pvt ? cache[ id ][ internalKey ] : cache[ id ];

			if ( thisCache ) {
				delete thisCache[ name ];

				// If there is no data left in the cache, we want to continue
				// and let the cache object itself get destroyed
				if ( !isEmptyDataObject(thisCache) ) {
					return;
				}
			}
		}

		// See jQuery.data for more information
		if ( pvt ) {
			delete cache[ id ][ internalKey ];

			// Don't destroy the parent cache unless the internal data object
			// had been the only thing left in it
			if ( !isEmptyDataObject(cache[ id ]) ) {
				return;
			}
		}

		var internalCache = cache[ id ][ internalKey ];

		// Browsers that fail expando deletion also refuse to delete expandos on
		// the window, but it will allow it on all other JS objects; other browsers
		// don't care
		if ( jQuery.support.deleteExpando || cache != window ) {
			delete cache[ id ];
		} else {
			cache[ id ] = null;
		}

		// We destroyed the entire user cache at once because it's faster than
		// iterating through each key, but we need to continue to persist internal
		// data if it existed
		if ( internalCache ) {
			cache[ id ] = {};
			// TODO: This is a hack for 1.5 ONLY. Avoids exposing jQuery
			// metadata on plain JS objects when the object is serialized using
			// JSON.stringify
			if ( !isNode ) {
				cache[ id ].toJSON = jQuery.noop;
			}

			cache[ id ][ internalKey ] = internalCache;

		// Otherwise, we need to eliminate the expando on the node to avoid
		// false lookups in the cache for entries that no longer exist
		} else if ( isNode ) {
			// IE does not allow us to delete expando properties from nodes,
			// nor does it have a removeAttribute function on Document nodes;
			// we must handle all of these cases
			if ( jQuery.support.deleteExpando ) {
				delete elem[ jQuery.expando ];
			} else if ( elem.removeAttribute ) {
				elem.removeAttribute( jQuery.expando );
			} else {
				elem[ jQuery.expando ] = null;
			}
		}
	},

	// For internal use only.
	_data: function( elem, name, data ) {
		return jQuery.data( elem, name, data, true );
	},

	// A method for determining if a DOM node can handle the data expando
	acceptData: function( elem ) {
		if ( elem.nodeName ) {
			var match = jQuery.noData[ elem.nodeName.toLowerCase() ];

			if ( match ) {
				return !(match === true || elem.getAttribute("classid") !== match);
			}
		}

		return true;
	}
});

jQuery.fn.extend({
	data: function( key, value ) {
		var data = null;

		if ( typeof key === "undefined" ) {
			if ( this.length ) {
				data = jQuery.data( this[0] );

				if ( this[0].nodeType === 1 ) {
			    var attr = this[0].attributes, name;
					for ( var i = 0, l = attr.length; i < l; i++ ) {
						name = attr[i].name;

						if ( name.indexOf( "data-" ) === 0 ) {
							name = jQuery.camelCase( name.substring(5) );

							dataAttr( this[0], name, data[ name ] );
						}
					}
				}
			}

			return data;

		} else if ( typeof key === "object" ) {
			return this.each(function() {
				jQuery.data( this, key );
			});
		}

		var parts = key.split(".");
		parts[1] = parts[1] ? "." + parts[1] : "";

		if ( value === undefined ) {
			data = this.triggerHandler("getData" + parts[1] + "!", [parts[0]]);

			// Try to fetch any internally stored data first
			if ( data === undefined && this.length ) {
				data = jQuery.data( this[0], key );
				data = dataAttr( this[0], key, data );
			}

			return data === undefined && parts[1] ?
				this.data( parts[0] ) :
				data;

		} else {
			return this.each(function() {
				var $this = jQuery( this ),
					args = [ parts[0], value ];

				$this.triggerHandler( "setData" + parts[1] + "!", args );
				jQuery.data( this, key, value );
				$this.triggerHandler( "changeData" + parts[1] + "!", args );
			});
		}
	},

	removeData: function( key ) {
		return this.each(function() {
			jQuery.removeData( this, key );
		});
	}
});

function dataAttr( elem, key, data ) {
	// If nothing was found internally, try to fetch any
	// data from the HTML5 data-* attribute
	if ( data === undefined && elem.nodeType === 1 ) {
		name = "data-" + key.replace( rmultiDash, "$1-$2" ).toLowerCase();

		data = elem.getAttribute( name );

		if ( typeof data === "string" ) {
			try {
				data = data === "true" ? true :
				data === "false" ? false :
				data === "null" ? null :
				!jQuery.isNaN( data ) ? parseFloat( data ) :
					rbrace.test( data ) ? jQuery.parseJSON( data ) :
					data;
			} catch( e ) {}

			// Make sure we set the data so it isn't changed later
			jQuery.data( elem, key, data );

		} else {
			data = undefined;
		}
	}

	return data;
}

// TODO: This is a hack for 1.5 ONLY to allow objects with a single toJSON
// property to be considered empty objects; this property always exists in
// order to make sure JSON.stringify does not expose internal metadata
function isEmptyDataObject( obj ) {
	for ( var name in obj ) {
		if ( name !== "toJSON" ) {
			return false;
		}
	}

	return true;
}




function handleQueueMarkDefer( elem, type, src ) {
	var deferDataKey = type + "defer",
		queueDataKey = type + "queue",
		markDataKey = type + "mark",
		defer = jQuery.data( elem, deferDataKey, undefined, true );
	if ( defer &&
		( src === "queue" || !jQuery.data( elem, queueDataKey, undefined, true ) ) &&
		( src === "mark" || !jQuery.data( elem, markDataKey, undefined, true ) ) ) {
		// Give room for hard-coded callbacks to fire first
		// and eventually mark/queue something else on the element
		setTimeout( function() {
			if ( !jQuery.data( elem, queueDataKey, undefined, true ) &&
				!jQuery.data( elem, markDataKey, undefined, true ) ) {
				jQuery.removeData( elem, deferDataKey, true );
				defer.resolve();
			}
		}, 0 );
	}
}

jQuery.extend({

	_mark: function( elem, type ) {
		if ( elem ) {
			type = (type || "fx") + "mark";
			jQuery.data( elem, type, (jQuery.data(elem,type,undefined,true) || 0) + 1, true );
		}
	},

	_unmark: function( force, elem, type ) {
		if ( force !== true ) {
			type = elem;
			elem = force;
			force = false;
		}
		if ( elem ) {
			type = type || "fx";
			var key = type + "mark",
				count = force ? 0 : ( (jQuery.data( elem, key, undefined, true) || 1 ) - 1 );
			if ( count ) {
				jQuery.data( elem, key, count, true );
			} else {
				jQuery.removeData( elem, key, true );
				handleQueueMarkDefer( elem, type, "mark" );
			}
		}
	},

	queue: function( elem, type, data ) {
		if ( elem ) {
			type = (type || "fx") + "queue";
			var q = jQuery.data( elem, type, undefined, true );
			// Speed up dequeue by getting out quickly if this is just a lookup
			if ( data ) {
				if ( !q || jQuery.isArray(data) ) {
					q = jQuery.data( elem, type, jQuery.makeArray(data), true );
				} else {
					q.push( data );
				}
			}
			return q || [];
		}
	},

	dequeue: function( elem, type ) {
		type = type || "fx";

		var queue = jQuery.queue( elem, type ),
			fn = queue.shift(),
			defer;

		// If the fx queue is dequeued, always remove the progress sentinel
		if ( fn === "inprogress" ) {
			fn = queue.shift();
		}

		if ( fn ) {
			// Add a progress sentinel to prevent the fx queue from being
			// automatically dequeued
			if ( type === "fx" ) {
				queue.unshift("inprogress");
			}

			fn.call(elem, function() {
				jQuery.dequeue(elem, type);
			});
		}

		if ( !queue.length ) {
			jQuery.removeData( elem, type + "queue", true );
			handleQueueMarkDefer( elem, type, "queue" );
		}
	}
});

jQuery.fn.extend({
	queue: function( type, data ) {
		if ( typeof type !== "string" ) {
			data = type;
			type = "fx";
		}

		if ( data === undefined ) {
			return jQuery.queue( this[0], type );
		}
		return this.each(function() {
			var queue = jQuery.queue( this, type, data );

			if ( type === "fx" && queue[0] !== "inprogress" ) {
				jQuery.dequeue( this, type );
			}
		});
	},
	dequeue: function( type ) {
		return this.each(function() {
			jQuery.dequeue( this, type );
		});
	},
	// Based off of the plugin by Clint Helfers, with permission.
	// http://blindsignals.com/index.php/2009/07/jquery-delay/
	delay: function( time, type ) {
		time = jQuery.fx ? jQuery.fx.speeds[time] || time : time;
		type = type || "fx";

		return this.queue( type, function() {
			var elem = this;
			setTimeout(function() {
				jQuery.dequeue( elem, type );
			}, time );
		});
	},
	clearQueue: function( type ) {
		return this.queue( type || "fx", [] );
	},
	// Get a promise resolved when queues of a certain type
	// are emptied (fx is the type by default)
	promise: function( type, object ) {
		if ( typeof type !== "string" ) {
			object = type;
			type = undefined;
		}
		type = type || "fx";
		var defer = jQuery.Deferred(),
			elements = this,
			i = elements.length,
			count = 1,
			deferDataKey = type + "defer",
			queueDataKey = type + "queue",
			markDataKey = type + "mark";
		function resolve() {
			if ( !( --count ) ) {
				defer.resolveWith( elements, [ elements ] );
			}
		}
		while( i-- ) {
			if (( tmp = jQuery.data( elements[ i ], deferDataKey, undefined, true ) ||
					( jQuery.data( elements[ i ], queueDataKey, undefined, true ) ||
						jQuery.data( elements[ i ], markDataKey, undefined, true ) ) &&
					jQuery.data( elements[ i ], deferDataKey, jQuery._Deferred(), true ) )) {
				count++;
				tmp.done( resolve );
			}
		}
		resolve();
		return defer.promise();
	}
});




var rclass = /[\n\t\r]/g,
	rspace = /\s+/,
	rreturn = /\r/g,
	rtype = /^(?:button|input)$/i,
	rfocusable = /^(?:button|input|object|select|textarea)$/i,
	rclickable = /^a(?:rea)?$/i,
	rspecial = /^(?:data-|aria-)/,
	rinvalidChar = /\:/,
	formHook;

jQuery.fn.extend({
	attr: function( name, value ) {
		return jQuery.access( this, name, value, true, jQuery.attr );
	},

	removeAttr: function( name ) {
		return this.each(function() {
			jQuery.removeAttr( this, name );
		});
	},

	prop: function( name, value ) {
		return jQuery.access( this, name, value, true, jQuery.prop );
	},

	removeProp: function( name ) {
		return this.each(function() {
			// try/catch handles cases where IE balks (such as removing a property on window)
			try {
				this[ name ] = undefined;
				delete this[ name ];
			} catch( e ) {}
		});
	},

	addClass: function( value ) {
		if ( jQuery.isFunction( value ) ) {
			return this.each(function(i) {
				var self = jQuery(this);
				self.addClass( value.call(this, i, self.attr("class") || "") );
			});
		}

		if ( value && typeof value === "string" ) {
			var classNames = (value || "").split( rspace );

			for ( var i = 0, l = this.length; i < l; i++ ) {
				var elem = this[i];

				if ( elem.nodeType === 1 ) {
					if ( !elem.className ) {
						elem.className = value;

					} else {
						var className = " " + elem.className + " ",
							setClass = elem.className;

						for ( var c = 0, cl = classNames.length; c < cl; c++ ) {
							if ( className.indexOf( " " + classNames[c] + " " ) < 0 ) {
								setClass += " " + classNames[c];
							}
						}
						elem.className = jQuery.trim( setClass );
					}
				}
			}
		}

		return this;
	},

	removeClass: function( value ) {
		if ( jQuery.isFunction(value) ) {
			return this.each(function(i) {
				var self = jQuery(this);
				self.removeClass( value.call(this, i, self.attr("class")) );
			});
		}

		if ( (value && typeof value === "string") || value === undefined ) {
			var classNames = (value || "").split( rspace );

			for ( var i = 0, l = this.length; i < l; i++ ) {
				var elem = this[i];

				if ( elem.nodeType === 1 && elem.className ) {
					if ( value ) {
						var className = (" " + elem.className + " ").replace(rclass, " ");
						for ( var c = 0, cl = classNames.length; c < cl; c++ ) {
							className = className.replace(" " + classNames[c] + " ", " ");
						}
						elem.className = jQuery.trim( className );

					} else {
						elem.className = "";
					}
				}
			}
		}

		return this;
	},

	toggleClass: function( value, stateVal ) {
		var type = typeof value,
			isBool = typeof stateVal === "boolean";

		if ( jQuery.isFunction( value ) ) {
			return this.each(function(i) {
				var self = jQuery(this);
				self.toggleClass( value.call(this, i, self.attr("class"), stateVal), stateVal );
			});
		}

		return this.each(function() {
			if ( type === "string" ) {
				// toggle individual class names
				var className,
					i = 0,
					self = jQuery( this ),
					state = stateVal,
					classNames = value.split( rspace );

				while ( (className = classNames[ i++ ]) ) {
					// check each className given, space seperated list
					state = isBool ? state : !self.hasClass( className );
					self[ state ? "addClass" : "removeClass" ]( className );
				}

			} else if ( type === "undefined" || type === "boolean" ) {
				if ( this.className ) {
					// store className if set
					jQuery._data( this, "__className__", this.className );
				}

				// toggle whole className
				this.className = this.className || value === false ? "" : jQuery._data( this, "__className__" ) || "";
			}
		});
	},

	hasClass: function( selector ) {
		var className = " " + selector + " ";
		for ( var i = 0, l = this.length; i < l; i++ ) {
			if ( (" " + this[i].className + " ").replace(rclass, " ").indexOf( className ) > -1 ) {
				return true;
			}
		}

		return false;
	},

	val: function( value ) {
		var hooks, ret,
			elem = this[0];

		if ( !arguments.length ) {
			if ( elem ) {
				hooks = jQuery.valHooks[ elem.nodeName.toLowerCase() ] || jQuery.valHooks[ elem.type ];

				if ( hooks && "get" in hooks && (ret = hooks.get( elem, "value" )) !== undefined ) {
					return ret;
				}

				return (elem.value || "").replace(rreturn, "");
			}

			return undefined;
		}

		var isFunction = jQuery.isFunction( value );

		return this.each(function( i ) {
			var self = jQuery(this), val;

			if ( this.nodeType !== 1 ) {
				return;
			}

			if ( isFunction ) {
				val = value.call( this, i, self.val() );
			} else {
				val = value;
			}

			// Treat null/undefined as ""; convert numbers to string
			if ( val == null ) {
				val = "";
			} else if ( typeof val === "number" ) {
				val += "";
			} else if ( jQuery.isArray( val ) ) {
				val = jQuery.map(val, function ( value ) {
					return value == null ? "" : value + "";
				});
			}

			hooks = jQuery.valHooks[ this.nodeName.toLowerCase() ] || jQuery.valHooks[ this.type ];

			// If set returns undefined, fall back to normal setting
			if ( !hooks || ("set" in hooks && hooks.set( this, val, "value" ) === undefined) ) {
				this.value = val;
			}
		});
	}
});

jQuery.extend({
	valHooks: {
		option: {
			get: function( elem ) {
				// attributes.value is undefined in Blackberry 4.7 but
				// uses .value. See #6932
				var val = elem.attributes.value;
				return !val || val.specified ? elem.value : elem.text;
			}
		},
		select: {
			get: function( elem ) {
				var index = elem.selectedIndex,
					values = [],
					options = elem.options,
					one = elem.type === "select-one";

				// Nothing was selected
				if ( index < 0 ) {
					return null;
				}

				// Loop through all the selected options
				for ( var i = one ? index : 0, max = one ? index + 1 : options.length; i < max; i++ ) {
					var option = options[ i ];

					// Don't return options that are disabled or in a disabled optgroup
					if ( option.selected && (jQuery.support.optDisabled ? !option.disabled : option.getAttribute("disabled") === null) &&
							(!option.parentNode.disabled || !jQuery.nodeName( option.parentNode, "optgroup" )) ) {

						// Get the specific value for the option
						value = jQuery( option ).val();

						// We don't need an array for one selects
						if ( one ) {
							return value;
						}

						// Multi-Selects return an array
						values.push( value );
					}
				}

				// Fixes Bug #2551 -- select.val() broken in IE after form.reset()
				if ( one && !values.length && options.length ) {
					return jQuery( options[ index ] ).val();
				}

				return values;
			},

			set: function( elem, value ) {
				var values = jQuery.makeArray( value );

				jQuery(elem).find("option").each(function() {
					this.selected = jQuery.inArray( jQuery(this).val(), values ) >= 0;
				});

				if ( !values.length ) {
					elem.selectedIndex = -1;
				}
				return values;
			}
		}
	},

	attrFn: {
		val: true,
		css: true,
		html: true,
		text: true,
		data: true,
		width: true,
		height: true,
		offset: true
	},

	attrFix: {
		// Always normalize to ensure hook usage
		tabindex: "tabIndex",
		readonly: "readOnly"
	},

	attr: function( elem, name, value, pass ) {
		var nType = elem.nodeType;

		// don't get/set attributes on text, comment and attribute nodes
		if ( !elem || nType === 3 || nType === 8 || nType === 2 ) {
			return undefined;
		}

		if ( pass && name in jQuery.attrFn ) {
			return jQuery( elem )[ name ]( value );
		}

		var ret, hooks,
			notxml = nType !== 1 || !jQuery.isXMLDoc( elem );

		// Normalize the name if needed
		name = notxml && jQuery.attrFix[ name ] || name;

		// Get the appropriate hook, or the formHook
		// if getSetAttribute is not supported and we have form objects in IE6/7
		hooks = jQuery.attrHooks[ name ] ||
			( formHook && (jQuery.nodeName( elem, "form" ) || rinvalidChar.test( name )) ?
				formHook :
				undefined );

		if ( value !== undefined ) {

			if ( value === null || (value === false && !rspecial.test( name )) ) {
				jQuery.removeAttr( elem, name );
				return undefined;

			} else if ( hooks && "set" in hooks && notxml && (ret = hooks.set( elem, value, name )) !== undefined ) {
				return ret;

			} else {

				// Set boolean attributes to the same name
				if ( value === true && !rspecial.test( name ) ) {
					value = name;
				}

				elem.setAttribute( name, "" + value );
				return value;
			}

		} else {

			if ( hooks && "get" in hooks && notxml ) {
				return hooks.get( elem, name );

			} else {

				ret = elem.getAttribute( name );

				// Non-existent attributes return null, we normalize to undefined
				return ret === null ?
					undefined :
					ret;
			}
		}
	},

	removeAttr: function( elem, name ) {
		if ( elem.nodeType === 1 ) {
			name = jQuery.attrFix[ name ] || name;

			if ( jQuery.support.getSetAttribute ) {
				// Use removeAttribute in browsers that support it
				elem.removeAttribute( name );
			} else {
				jQuery.attr( elem, name, "" );
				elem.removeAttributeNode( elem.getAttributeNode( name ) );
			}
		}
	},

	attrHooks: {
		type: {
			set: function( elem, value ) {
				// We can't allow the type property to be changed (since it causes problems in IE)
				if ( rtype.test( elem.nodeName ) && elem.parentNode ) {
					jQuery.error( "type property can't be changed" );
				} else if ( !jQuery.support.radioValue && value === "radio" && jQuery.nodeName(elem, "input") ) {
					// Setting the type on a radio button after the value resets the value in IE6-9
					// Reset value to it's default in case type is set after value
					// This is for element creation
					var val = elem.getAttribute("value");
					elem.setAttribute( "type", value );
					if ( val ) {
						elem.value = val;
					}
					return value;
				}
			}
		},
		tabIndex: {
			get: function( elem ) {
				// elem.tabIndex doesn't always return the correct value when it hasn't been explicitly set
				// http://fluidproject.org/blog/2008/01/09/getting-setting-and-removing-tabindex-values-with-javascript/
				var attributeNode = elem.getAttributeNode("tabIndex");

				return attributeNode && attributeNode.specified ?
					parseInt( attributeNode.value, 10 ) :
					rfocusable.test( elem.nodeName ) || rclickable.test( elem.nodeName ) && elem.href ?
						0 :
						undefined;
			}
		}
	},

	propFix: {},

	prop: function( elem, name, value ) {
		var nType = elem.nodeType;

		// don't get/set properties on text, comment and attribute nodes
		if ( !elem || nType === 3 || nType === 8 || nType === 2 ) {
			return undefined;
		}

		var ret, hooks,
			notxml = nType !== 1 || !jQuery.isXMLDoc( elem );

		// Try to normalize/fix the name
		name = notxml && jQuery.propFix[ name ] || name;

		hooks = jQuery.propHooks[ name ];

		if ( value !== undefined ) {
			if ( hooks && "set" in hooks && (ret = hooks.set( elem, value, name )) !== undefined ) {
				return ret;

			} else {
				return (elem[ name ] = value);
			}

		} else {
			if ( hooks && "get" in hooks && (ret = hooks.get( elem, name )) !== undefined ) {
				return ret;

			} else {
				return elem[ name ];
			}
		}
	},

	propHooks: {}
});

// IE6/7 do not support getting/setting some attributes with get/setAttribute
if ( !jQuery.support.getSetAttribute ) {
	jQuery.attrFix = jQuery.extend( jQuery.attrFix, {
		"for": "htmlFor",
		"class": "className",
		maxlength: "maxLength",
		cellspacing: "cellSpacing",
		cellpadding: "cellPadding",
		rowspan: "rowSpan",
		colspan: "colSpan",
		usemap: "useMap",
		frameborder: "frameBorder"
	});

	// Use this for any attribute on a form in IE6/7
	formHook = jQuery.attrHooks.name = jQuery.attrHooks.value = jQuery.valHooks.button = {
		get: function( elem, name ) {
			var ret;
			if ( name === "value" && !jQuery.nodeName( elem, "button" ) ) {
				return elem.getAttribute( name );
			}
			ret = elem.getAttributeNode( name );
			// Return undefined if not specified instead of empty string
			return ret && ret.specified ?
				ret.nodeValue :
				undefined;
		},
		set: function( elem, value, name ) {
			// Check form objects in IE (multiple bugs related)
			// Only use nodeValue if the attribute node exists on the form
			var ret = elem.getAttributeNode( name );
			if ( ret ) {
				ret.nodeValue = value;
				return value;
			}
		}
	};

	// Set width and height to auto instead of 0 on empty string( Bug #8150 )
	// This is for removals
	jQuery.each([ "width", "height" ], function( i, name ) {
		jQuery.attrHooks[ name ] = jQuery.extend( jQuery.attrHooks[ name ], {
			set: function( elem, value ) {
				if ( value === "" ) {
					elem.setAttribute( name, "auto" );
					return value;
				}
			}
		});
	});
}


// Some attributes require a special call on IE
if ( !jQuery.support.hrefNormalized ) {
	jQuery.each([ "href", "src", "width", "height" ], function( i, name ) {
		jQuery.attrHooks[ name ] = jQuery.extend( jQuery.attrHooks[ name ], {
			get: function( elem ) {
				var ret = elem.getAttribute( name, 2 );
				return ret === null ? undefined : ret;
			}
		});
	});
}

if ( !jQuery.support.style ) {
	jQuery.attrHooks.style = {
		get: function( elem ) {
			// Return undefined in the case of empty string
			// Normalize to lowercase since IE uppercases css property names
			return elem.style.cssText.toLowerCase() || undefined;
		},
		set: function( elem, value ) {
			return (elem.style.cssText = "" + value);
		}
	};
}

// Safari mis-reports the default selected property of an option
// Accessing the parent's selectedIndex property fixes it
if ( !jQuery.support.optSelected ) {
	jQuery.propHooks.selected = jQuery.extend( jQuery.propHooks.selected, {
		get: function( elem ) {
			var parent = elem.parentNode;

			if ( parent ) {
				parent.selectedIndex;

				// Make sure that it also works with optgroups, see #5701
				if ( parent.parentNode ) {
					parent.parentNode.selectedIndex;
				}
			}
		}
	});
}

// Radios and checkboxes getter/setter
if ( !jQuery.support.checkOn ) {
	jQuery.each([ "radio", "checkbox" ], function() {
		jQuery.valHooks[ this ] = {
			get: function( elem ) {
				// Handle the case where in Webkit "" is returned instead of "on" if a value isn't specified
				return elem.getAttribute("value") === null ? "on" : elem.value;
			}
		};
	});
}
jQuery.each([ "radio", "checkbox" ], function() {
	jQuery.valHooks[ this ] = jQuery.extend( jQuery.valHooks[ this ], {
		set: function( elem, value ) {
			if ( jQuery.isArray( value ) ) {
				return (elem.checked = jQuery.inArray( jQuery(elem).val(), value ) >= 0);
			}
		}
	});
});




var hasOwn = Object.prototype.hasOwnProperty,
	rnamespaces = /\.(.*)$/,
	rformElems = /^(?:textarea|input|select)$/i,
	rperiod = /\./g,
	rspaces = / /g,
	rescape = /[^\w\s.|`]/g,
	fcleanup = function( nm ) {
		return nm.replace(rescape, "\\$&");
	};

/*
 * A number of helper functions used for managing events.
 * Many of the ideas behind this code originated from
 * Dean Edwards' addEvent library.
 */
jQuery.event = {

	// Bind an event to an element
	// Original by Dean Edwards
	add: function( elem, types, handler, data ) {
		if ( elem.nodeType === 3 || elem.nodeType === 8 ) {
			return;
		}

		if ( handler === false ) {
			handler = returnFalse;
		} else if ( !handler ) {
			// Fixes bug #7229. Fix recommended by jdalton
			return;
		}

		var handleObjIn, handleObj;

		if ( handler.handler ) {
			handleObjIn = handler;
			handler = handleObjIn.handler;
		}

		// Make sure that the function being executed has a unique ID
		if ( !handler.guid ) {
			handler.guid = jQuery.guid++;
		}

		// Init the element's event structure
		var elemData = jQuery._data( elem );

		// If no elemData is found then we must be trying to bind to one of the
		// banned noData elements
		if ( !elemData ) {
			return;
		}

		var events = elemData.events,
			eventHandle = elemData.handle;

		if ( !events ) {
			elemData.events = events = {};
		}

		if ( !eventHandle ) {
			elemData.handle = eventHandle = function( e ) {
				// Discard the second event of a jQuery.event.trigger() and
				// when an event is called after a page has unloaded
				return typeof jQuery !== "undefined" && (!e || jQuery.event.triggered !== e.type) ?
					jQuery.event.handle.apply( eventHandle.elem, arguments ) :
					undefined;
			};
		}

		// Add elem as a property of the handle function
		// This is to prevent a memory leak with non-native events in IE.
		eventHandle.elem = elem;

		// Handle multiple events separated by a space
		// jQuery(...).bind("mouseover mouseout", fn);
		types = types.split(" ");

		var type, i = 0, namespaces;

		while ( (type = types[ i++ ]) ) {
			handleObj = handleObjIn ?
				jQuery.extend({}, handleObjIn) :
				{ handler: handler, data: data };

			// Namespaced event handlers
			if ( type.indexOf(".") > -1 ) {
				namespaces = type.split(".");
				type = namespaces.shift();
				handleObj.namespace = namespaces.slice(0).sort().join(".");

			} else {
				namespaces = [];
				handleObj.namespace = "";
			}

			handleObj.type = type;
			if ( !handleObj.guid ) {
				handleObj.guid = handler.guid;
			}

			// Get the current list of functions bound to this event
			var handlers = events[ type ],
				special = jQuery.event.special[ type ] || {};

			// Init the event handler queue
			if ( !handlers ) {
				handlers = events[ type ] = [];

				// Check for a special event handler
				// Only use addEventListener/attachEvent if the special
				// events handler returns false
				if ( !special.setup || special.setup.call( elem, data, namespaces, eventHandle ) === false ) {
					// Bind the global event handler to the element
					if ( elem.addEventListener ) {
						elem.addEventListener( type, eventHandle, false );

					} else if ( elem.attachEvent ) {
						elem.attachEvent( "on" + type, eventHandle );
					}
				}
			}

			if ( special.add ) {
				special.add.call( elem, handleObj );

				if ( !handleObj.handler.guid ) {
					handleObj.handler.guid = handler.guid;
				}
			}

			// Add the function to the element's handler list
			handlers.push( handleObj );

			// Keep track of which events have been used, for event optimization
			jQuery.event.global[ type ] = true;
		}

		// Nullify elem to prevent memory leaks in IE
		elem = null;
	},

	global: {},

	// Detach an event or set of events from an element
	remove: function( elem, types, handler, pos ) {
		// don't do events on text and comment nodes
		if ( elem.nodeType === 3 || elem.nodeType === 8 ) {
			return;
		}

		if ( handler === false ) {
			handler = returnFalse;
		}

		var ret, type, fn, j, i = 0, all, namespaces, namespace, special, eventType, handleObj, origType,
			elemData = jQuery.hasData( elem ) && jQuery._data( elem ),
			events = elemData && elemData.events;

		if ( !elemData || !events ) {
			return;
		}

		// types is actually an event object here
		if ( types && types.type ) {
			handler = types.handler;
			types = types.type;
		}

		// Unbind all events for the element
		if ( !types || typeof types === "string" && types.charAt(0) === "." ) {
			types = types || "";

			for ( type in events ) {
				jQuery.event.remove( elem, type + types );
			}

			return;
		}

		// Handle multiple events separated by a space
		// jQuery(...).unbind("mouseover mouseout", fn);
		types = types.split(" ");

		while ( (type = types[ i++ ]) ) {
			origType = type;
			handleObj = null;
			all = type.indexOf(".") < 0;
			namespaces = [];

			if ( !all ) {
				// Namespaced event handlers
				namespaces = type.split(".");
				type = namespaces.shift();

				namespace = new RegExp("(^|\\.)" +
					jQuery.map( namespaces.slice(0).sort(), fcleanup ).join("\\.(?:.*\\.)?") + "(\\.|$)");
			}

			eventType = events[ type ];

			if ( !eventType ) {
				continue;
			}

			if ( !handler ) {
				for ( j = 0; j < eventType.length; j++ ) {
					handleObj = eventType[ j ];

					if ( all || namespace.test( handleObj.namespace ) ) {
						jQuery.event.remove( elem, origType, handleObj.handler, j );
						eventType.splice( j--, 1 );
					}
				}

				continue;
			}

			special = jQuery.event.special[ type ] || {};

			for ( j = pos || 0; j < eventType.length; j++ ) {
				handleObj = eventType[ j ];

				if ( handler.guid === handleObj.guid ) {
					// remove the given handler for the given type
					if ( all || namespace.test( handleObj.namespace ) ) {
						if ( pos == null ) {
							eventType.splice( j--, 1 );
						}

						if ( special.remove ) {
							special.remove.call( elem, handleObj );
						}
					}

					if ( pos != null ) {
						break;
					}
				}
			}

			// remove generic event handler if no more handlers exist
			if ( eventType.length === 0 || pos != null && eventType.length === 1 ) {
				if ( !special.teardown || special.teardown.call( elem, namespaces ) === false ) {
					jQuery.removeEvent( elem, type, elemData.handle );
				}

				ret = null;
				delete events[ type ];
			}
		}

		// Remove the expando if it's no longer used
		if ( jQuery.isEmptyObject( events ) ) {
			var handle = elemData.handle;
			if ( handle ) {
				handle.elem = null;
			}

			delete elemData.events;
			delete elemData.handle;

			if ( jQuery.isEmptyObject( elemData ) ) {
				jQuery.removeData( elem, undefined, true );
			}
		}
	},

	// Events that are safe to short-circuit if no handlers are attached.
	// Native DOM events should not be added, they may have inline handlers.
	customEvent: {
		"getData": true,
		"setData": true,
		"changeData": true
	},

	trigger: function( event, data, elem, onlyHandlers ) {
		// Event object or event type
		var type = event.type || event,
			namespaces = [],
			exclusive;

		if ( type.indexOf("!") >= 0 ) {
			// Exclusive events trigger only for the exact event (no namespaces)
			type = type.slice(0, -1);
			exclusive = true;
		}

		if ( type.indexOf(".") >= 0 ) {
			// Namespaced trigger; create a regexp to match event type in handle()
			namespaces = type.split(".");
			type = namespaces.shift();
			namespaces.sort();
		}

		if ( (!elem || jQuery.event.customEvent[ type ]) && !jQuery.event.global[ type ] ) {
			// No jQuery handlers for this event type, and it can't have inline handlers
			return;
		}

		// Caller can pass in an Event, Object, or just an event type string
		event = typeof event === "object" ?
			// jQuery.Event object
			event[ jQuery.expando ] ? event :
			// Object literal
			new jQuery.Event( type, event ) :
			// Just the event type (string)
			new jQuery.Event( type );

		event.type = type;
		event.exclusive = exclusive;
		event.namespace = namespaces.join(".");
		event.namespace_re = new RegExp("(^|\\.)" + namespaces.join("\\.(?:.*\\.)?") + "(\\.|$)");

		// triggerHandler() and global events don't bubble or run the default action
		if ( onlyHandlers || !elem ) {
			event.preventDefault();
			event.stopPropagation();
		}

		// Handle a global trigger
		if ( !elem ) {
			// TODO: Stop taunting the data cache; remove global events and always attach to document
			jQuery.each( jQuery.cache, function() {
				// internalKey variable is just used to make it easier to find
				// and potentially change this stuff later; currently it just
				// points to jQuery.expando
				var internalKey = jQuery.expando,
					internalCache = this[ internalKey ];
				if ( internalCache && internalCache.events && internalCache.events[ type ] ) {
					jQuery.event.trigger( event, data, internalCache.handle.elem );
				}
			});
			return;
		}

		// Don't do events on text and comment nodes
		if ( elem.nodeType === 3 || elem.nodeType === 8 ) {
			return;
		}

		// Clean up the event in case it is being reused
		event.result = undefined;
		event.target = elem;

		// Clone any incoming data and prepend the event, creating the handler arg list
		data = data ? jQuery.makeArray( data ) : [];
		data.unshift( event );

		var cur = elem,
			// IE doesn't like method names with a colon (#3533, #8272)
			ontype = type.indexOf(":") < 0 ? "on" + type : "";

		// Fire event on the current element, then bubble up the DOM tree
		do {
			var handle = jQuery._data( cur, "handle" );

			event.currentTarget = cur;
			if ( handle ) {
				handle.apply( cur, data );
			}

			// Trigger an inline bound script
			if ( ontype && jQuery.acceptData( cur ) && cur[ ontype ] && cur[ ontype ].apply( cur, data ) === false ) {
				event.result = false;
				event.preventDefault();
			}

			// Bubble up to document, then to window
			cur = cur.parentNode || cur.ownerDocument || cur === event.target.ownerDocument && window;
		} while ( cur && !event.isPropagationStopped() );

		// If nobody prevented the default action, do it now
		if ( !event.isDefaultPrevented() ) {
			var old,
				special = jQuery.event.special[ type ] || {};

			if ( (!special._default || special._default.call( elem.ownerDocument, event ) === false) &&
				!(type === "click" && jQuery.nodeName( elem, "a" )) && jQuery.acceptData( elem ) ) {

				// Call a native DOM method on the target with the same name name as the event.
				// Can't use an .isFunction)() check here because IE6/7 fails that test.
				// IE<9 dies on focus to hidden element (#1486), may want to revisit a try/catch.
				try {
					if ( ontype && elem[ type ] ) {
						// Don't re-trigger an onFOO event when we call its FOO() method
						old = elem[ ontype ];

						if ( old ) {
							elem[ ontype ] = null;
						}

						jQuery.event.triggered = type;
						elem[ type ]();
					}
				} catch ( ieError ) {}

				if ( old ) {
					elem[ ontype ] = old;
				}

				jQuery.event.triggered = undefined;
			}
		}

		return event.result;
	},

	handle: function( event ) {
		event = jQuery.event.fix( event || window.event );
		// Snapshot the handlers list since a called handler may add/remove events.
		var handlers = ((jQuery._data( this, "events" ) || {})[ event.type ] || []).slice(0),
			run_all = !event.exclusive && !event.namespace,
			args = Array.prototype.slice.call( arguments, 0 );

		// Use the fix-ed Event rather than the (read-only) native event
		args[0] = event;
		event.currentTarget = this;

		for ( var j = 0, l = handlers.length; j < l; j++ ) {
			var handleObj = handlers[ j ];

			// Triggered event must 1) be non-exclusive and have no namespace, or
			// 2) have namespace(s) a subset or equal to those in the bound event.
			if ( run_all || event.namespace_re.test( handleObj.namespace ) ) {
				// Pass in a reference to the handler function itself
				// So that we can later remove it
				event.handler = handleObj.handler;
				event.data = handleObj.data;
				event.handleObj = handleObj;

				var ret = handleObj.handler.apply( this, args );

				if ( ret !== undefined ) {
					event.result = ret;
					if ( ret === false ) {
						event.preventDefault();
						event.stopPropagation();
					}
				}

				if ( event.isImmediatePropagationStopped() ) {
					break;
				}
			}
		}
		return event.result;
	},

	props: "altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "),

	fix: function( event ) {
		if ( event[ jQuery.expando ] ) {
			return event;
		}

		// store a copy of the original event object
		// and "clone" to set read-only properties
		var originalEvent = event;
		event = jQuery.Event( originalEvent );

		for ( var i = this.props.length, prop; i; ) {
			prop = this.props[ --i ];
			event[ prop ] = originalEvent[ prop ];
		}

		// Fix target property, if necessary
		if ( !event.target ) {
			// Fixes #1925 where srcElement might not be defined either
			event.target = event.srcElement || document;
		}

		// check if target is a textnode (safari)
		if ( event.target.nodeType === 3 ) {
			event.target = event.target.parentNode;
		}

		// Add relatedTarget, if necessary
		if ( !event.relatedTarget && event.fromElement ) {
			event.relatedTarget = event.fromElement === event.target ? event.toElement : event.fromElement;
		}

		// Calculate pageX/Y if missing and clientX/Y available
		if ( event.pageX == null && event.clientX != null ) {
			var eventDocument = event.target.ownerDocument || document,
				doc = eventDocument.documentElement,
				body = eventDocument.body;

			event.pageX = event.clientX + (doc && doc.scrollLeft || body && body.scrollLeft || 0) - (doc && doc.clientLeft || body && body.clientLeft || 0);
			event.pageY = event.clientY + (doc && doc.scrollTop  || body && body.scrollTop  || 0) - (doc && doc.clientTop  || body && body.clientTop  || 0);
		}

		// Add which for key events
		if ( event.which == null && (event.charCode != null || event.keyCode != null) ) {
			event.which = event.charCode != null ? event.charCode : event.keyCode;
		}

		// Add metaKey to non-Mac browsers (use ctrl for PC's and Meta for Macs)
		if ( !event.metaKey && event.ctrlKey ) {
			event.metaKey = event.ctrlKey;
		}

		// Add which for click: 1 === left; 2 === middle; 3 === right
		// Note: button is not normalized, so don't use it
		if ( !event.which && event.button !== undefined ) {
			event.which = (event.button & 1 ? 1 : ( event.button & 2 ? 3 : ( event.button & 4 ? 2 : 0 ) ));
		}

		return event;
	},

	// Deprecated, use jQuery.guid instead
	guid: 1E8,

	// Deprecated, use jQuery.proxy instead
	proxy: jQuery.proxy,

	special: {
		ready: {
			// Make sure the ready event is setup
			setup: jQuery.bindReady,
			teardown: jQuery.noop
		},

		live: {
			add: function( handleObj ) {
				jQuery.event.add( this,
					liveConvert( handleObj.origType, handleObj.selector ),
					jQuery.extend({}, handleObj, {handler: liveHandler, guid: handleObj.handler.guid}) );
			},

			remove: function( handleObj ) {
				jQuery.event.remove( this, liveConvert( handleObj.origType, handleObj.selector ), handleObj );
			}
		},

		beforeunload: {
			setup: function( data, namespaces, eventHandle ) {
				// We only want to do this special case on windows
				if ( jQuery.isWindow( this ) ) {
					this.onbeforeunload = eventHandle;
				}
			},

			teardown: function( namespaces, eventHandle ) {
				if ( this.onbeforeunload === eventHandle ) {
					this.onbeforeunload = null;
				}
			}
		}
	}
};

jQuery.removeEvent = document.removeEventListener ?
	function( elem, type, handle ) {
		if ( elem.removeEventListener ) {
			elem.removeEventListener( type, handle, false );
		}
	} :
	function( elem, type, handle ) {
		if ( elem.detachEvent ) {
			elem.detachEvent( "on" + type, handle );
		}
	};

jQuery.Event = function( src, props ) {
	// Allow instantiation without the 'new' keyword
	if ( !this.preventDefault ) {
		return new jQuery.Event( src, props );
	}

	// Event object
	if ( src && src.type ) {
		this.originalEvent = src;
		this.type = src.type;

		// Events bubbling up the document may have been marked as prevented
		// by a handler lower down the tree; reflect the correct value.
		this.isDefaultPrevented = (src.defaultPrevented || src.returnValue === false ||
			src.getPreventDefault && src.getPreventDefault()) ? returnTrue : returnFalse;

	// Event type
	} else {
		this.type = src;
	}

	// Put explicitly provided properties onto the event object
	if ( props ) {
		jQuery.extend( this, props );
	}

	// timeStamp is buggy for some events on Firefox(#3843)
	// So we won't rely on the native value
	this.timeStamp = jQuery.now();

	// Mark it as fixed
	this[ jQuery.expando ] = true;
};

function returnFalse() {
	return false;
}
function returnTrue() {
	return true;
}

// jQuery.Event is based on DOM3 Events as specified by the ECMAScript Language Binding
// http://www.w3.org/TR/2003/WD-DOM-Level-3-Events-20030331/ecma-script-binding.html
jQuery.Event.prototype = {
	preventDefault: function() {
		this.isDefaultPrevented = returnTrue;

		var e = this.originalEvent;
		if ( !e ) {
			return;
		}

		// if preventDefault exists run it on the original event
		if ( e.preventDefault ) {
			e.preventDefault();

		// otherwise set the returnValue property of the original event to false (IE)
		} else {
			e.returnValue = false;
		}
	},
	stopPropagation: function() {
		this.isPropagationStopped = returnTrue;

		var e = this.originalEvent;
		if ( !e ) {
			return;
		}
		// if stopPropagation exists run it on the original event
		if ( e.stopPropagation ) {
			e.stopPropagation();
		}
		// otherwise set the cancelBubble property of the original event to true (IE)
		e.cancelBubble = true;
	},
	stopImmediatePropagation: function() {
		this.isImmediatePropagationStopped = returnTrue;
		this.stopPropagation();
	},
	isDefaultPrevented: returnFalse,
	isPropagationStopped: returnFalse,
	isImmediatePropagationStopped: returnFalse
};

// Checks if an event happened on an element within another element
// Used in jQuery.event.special.mouseenter and mouseleave handlers
var withinElement = function( event ) {
	// Check if mouse(over|out) are still within the same parent element
	var parent = event.relatedTarget;

	// Firefox sometimes assigns relatedTarget a XUL element
	// which we cannot access the parentNode property of
	try {

		// Chrome does something similar, the parentNode property
		// can be accessed but is null.
		if ( parent && parent !== document && !parent.parentNode ) {
			return;
		}
		// Traverse up the tree
		while ( parent && parent !== this ) {
			parent = parent.parentNode;
		}

		if ( parent !== this ) {
			// set the correct event type
			event.type = event.data;

			// handle event if we actually just moused on to a non sub-element
			jQuery.event.handle.apply( this, arguments );
		}

	// assuming we've left the element since we most likely mousedover a xul element
	} catch(e) { }
},

// In case of event delegation, we only need to rename the event.type,
// liveHandler will take care of the rest.
delegate = function( event ) {
	event.type = event.data;
	jQuery.event.handle.apply( this, arguments );
};

// Create mouseenter and mouseleave events
jQuery.each({
	mouseenter: "mouseover",
	mouseleave: "mouseout"
}, function( orig, fix ) {
	jQuery.event.special[ orig ] = {
		setup: function( data ) {
			jQuery.event.add( this, fix, data && data.selector ? delegate : withinElement, orig );
		},
		teardown: function( data ) {
			jQuery.event.remove( this, fix, data && data.selector ? delegate : withinElement );
		}
	};
});

// submit delegation
if ( !jQuery.support.submitBubbles ) {

	jQuery.event.special.submit = {
		setup: function( data, namespaces ) {
			if ( !jQuery.nodeName( this, "form" ) ) {
				jQuery.event.add(this, "click.specialSubmit", function( e ) {
					var elem = e.target,
						type = elem.type;

					if ( (type === "submit" || type === "image") && jQuery( elem ).closest("form").length ) {
						trigger( "submit", this, arguments );
					}
				});

				jQuery.event.add(this, "keypress.specialSubmit", function( e ) {
					var elem = e.target,
						type = elem.type;

					if ( (type === "text" || type === "password") && jQuery( elem ).closest("form").length && e.keyCode === 13 ) {
						trigger( "submit", this, arguments );
					}
				});

			} else {
				return false;
			}
		},

		teardown: function( namespaces ) {
			jQuery.event.remove( this, ".specialSubmit" );
		}
	};

}

// change delegation, happens here so we have bind.
if ( !jQuery.support.changeBubbles ) {

	var changeFilters,

	getVal = function( elem ) {
		var type = elem.type, val = elem.value;

		if ( type === "radio" || type === "checkbox" ) {
			val = elem.checked;

		} else if ( type === "select-multiple" ) {
			val = elem.selectedIndex > -1 ?
				jQuery.map( elem.options, function( elem ) {
					return elem.selected;
				}).join("-") :
				"";

		} else if ( jQuery.nodeName( elem, "select" ) ) {
			val = elem.selectedIndex;
		}

		return val;
	},

	testChange = function testChange( e ) {
		var elem = e.target, data, val;

		if ( !rformElems.test( elem.nodeName ) || elem.readOnly ) {
			return;
		}

		data = jQuery._data( elem, "_change_data" );
		val = getVal(elem);

		// the current data will be also retrieved by beforeactivate
		if ( e.type !== "focusout" || elem.type !== "radio" ) {
			jQuery._data( elem, "_change_data", val );
		}

		if ( data === undefined || val === data ) {
			return;
		}

		if ( data != null || val ) {
			e.type = "change";
			e.liveFired = undefined;
			jQuery.event.trigger( e, arguments[1], elem );
		}
	};

	jQuery.event.special.change = {
		filters: {
			focusout: testChange,

			beforedeactivate: testChange,

			click: function( e ) {
				var elem = e.target, type = jQuery.nodeName( elem, "input" ) ? elem.type : "";

				if ( type === "radio" || type === "checkbox" || jQuery.nodeName( elem, "select" ) ) {
					testChange.call( this, e );
				}
			},

			// Change has to be called before submit
			// Keydown will be called before keypress, which is used in submit-event delegation
			keydown: function( e ) {
				var elem = e.target, type = jQuery.nodeName( elem, "input" ) ? elem.type : "";

				if ( (e.keyCode === 13 && !jQuery.nodeName( elem, "textarea" ) ) ||
					(e.keyCode === 32 && (type === "checkbox" || type === "radio")) ||
					type === "select-multiple" ) {
					testChange.call( this, e );
				}
			},

			// Beforeactivate happens also before the previous element is blurred
			// with this event you can't trigger a change event, but you can store
			// information
			beforeactivate: function( e ) {
				var elem = e.target;
				jQuery._data( elem, "_change_data", getVal(elem) );
			}
		},

		setup: function( data, namespaces ) {
			if ( this.type === "file" ) {
				return false;
			}

			for ( var type in changeFilters ) {
				jQuery.event.add( this, type + ".specialChange", changeFilters[type] );
			}

			return rformElems.test( this.nodeName );
		},

		teardown: function( namespaces ) {
			jQuery.event.remove( this, ".specialChange" );

			return rformElems.test( this.nodeName );
		}
	};

	changeFilters = jQuery.event.special.change.filters;

	// Handle when the input is .focus()'d
	changeFilters.focus = changeFilters.beforeactivate;
}

function trigger( type, elem, args ) {
	// Piggyback on a donor event to simulate a different one.
	// Fake originalEvent to avoid donor's stopPropagation, but if the
	// simulated event prevents default then we do the same on the donor.
	// Don't pass args or remember liveFired; they apply to the donor event.
	var event = jQuery.extend( {}, args[ 0 ] );
	event.type = type;
	event.originalEvent = {};
	event.liveFired = undefined;
	jQuery.event.handle.call( elem, event );
	if ( event.isDefaultPrevented() ) {
		args[ 0 ].preventDefault();
	}
}

// Create "bubbling" focus and blur events
if ( !jQuery.support.focusinBubbles ) {
	jQuery.each({ focus: "focusin", blur: "focusout" }, function( orig, fix ) {

		// Attach a single capturing handler while someone wants focusin/focusout
		var attaches = 0;

		jQuery.event.special[ fix ] = {
			setup: function() {
				if ( attaches++ === 0 ) {
					document.addEventListener( orig, handler, true );
				}
			},
			teardown: function() {
				if ( --attaches === 0 ) {
					document.removeEventListener( orig, handler, true );
				}
			}
		};

		function handler( donor ) {
			// Donor event is always a native one; fix it and switch its type.
			// Let focusin/out handler cancel the donor focus/blur event.
			var e = jQuery.event.fix( donor );
			e.type = fix;
			e.originalEvent = {};
			jQuery.event.trigger( e, null, e.target );
			if ( e.isDefaultPrevented() ) {
				donor.preventDefault();
			}
		}
	});
}

jQuery.each(["bind", "one"], function( i, name ) {
	jQuery.fn[ name ] = function( type, data, fn ) {
		var handler;

		// Handle object literals
		if ( typeof type === "object" ) {
			for ( var key in type ) {
				this[ name ](key, data, type[key], fn);
			}
			return this;
		}

		if ( arguments.length === 2 || data === false ) {
			fn = data;
			data = undefined;
		}

		if ( name === "one" ) {
			handler = function( event ) {
				jQuery( this ).unbind( event, handler );
				return fn.apply( this, arguments );
			};
			handler.guid = fn.guid || jQuery.guid++;
		} else {
			handler = fn;
		}

		if ( type === "unload" && name !== "one" ) {
			this.one( type, data, fn );

		} else {
			for ( var i = 0, l = this.length; i < l; i++ ) {
				jQuery.event.add( this[i], type, handler, data );
			}
		}

		return this;
	};
});

jQuery.fn.extend({
	unbind: function( type, fn ) {
		// Handle object literals
		if ( typeof type === "object" && !type.preventDefault ) {
			for ( var key in type ) {
				this.unbind(key, type[key]);
			}

		} else {
			for ( var i = 0, l = this.length; i < l; i++ ) {
				jQuery.event.remove( this[i], type, fn );
			}
		}

		return this;
	},

	delegate: function( selector, types, data, fn ) {
		return this.live( types, data, fn, selector );
	},

	undelegate: function( selector, types, fn ) {
		if ( arguments.length === 0 ) {
			return this.unbind( "live" );

		} else {
			return this.die( types, null, fn, selector );
		}
	},

	trigger: function( type, data ) {
		return this.each(function() {
			jQuery.event.trigger( type, data, this );
		});
	},

	triggerHandler: function( type, data ) {
		if ( this[0] ) {
			return jQuery.event.trigger( type, data, this[0], true );
		}
	},

	toggle: function( fn ) {
		// Save reference to arguments for access in closure
		var args = arguments,
			guid = fn.guid || jQuery.guid++,
			i = 0,
			toggler = function( event ) {
				// Figure out which function to execute
				var lastToggle = ( jQuery.data( this, "lastToggle" + fn.guid ) || 0 ) % i;
				jQuery.data( this, "lastToggle" + fn.guid, lastToggle + 1 );

				// Make sure that clicks stop
				event.preventDefault();

				// and execute the function
				return args[ lastToggle ].apply( this, arguments ) || false;
			};

		// link all the functions, so any of them can unbind this click handler
		toggler.guid = guid;
		while ( i < args.length ) {
			args[ i++ ].guid = guid;
		}

		return this.click( toggler );
	},

	hover: function( fnOver, fnOut ) {
		return this.mouseenter( fnOver ).mouseleave( fnOut || fnOver );
	}
});

var liveMap = {
	focus: "focusin",
	blur: "focusout",
	mouseenter: "mouseover",
	mouseleave: "mouseout"
};

jQuery.each(["live", "die"], function( i, name ) {
	jQuery.fn[ name ] = function( types, data, fn, origSelector /* Internal Use Only */ ) {
		var type, i = 0, match, namespaces, preType,
			selector = origSelector || this.selector,
			context = origSelector ? this : jQuery( this.context );

		if ( typeof types === "object" && !types.preventDefault ) {
			for ( var key in types ) {
				context[ name ]( key, data, types[key], selector );
			}

			return this;
		}

		if ( name === "die" && !types &&
					origSelector && origSelector.charAt(0) === "." ) {

			context.unbind( origSelector );

			return this;
		}

		if ( data === false || jQuery.isFunction( data ) ) {
			fn = data || returnFalse;
			data = undefined;
		}

		types = (types || "").split(" ");

		while ( (type = types[ i++ ]) != null ) {
			match = rnamespaces.exec( type );
			namespaces = "";

			if ( match )  {
				namespaces = match[0];
				type = type.replace( rnamespaces, "" );
			}

			if ( type === "hover" ) {
				types.push( "mouseenter" + namespaces, "mouseleave" + namespaces );
				continue;
			}

			preType = type;

			if ( liveMap[ type ] ) {
				types.push( liveMap[ type ] + namespaces );
				type = type + namespaces;

			} else {
				type = (liveMap[ type ] || type) + namespaces;
			}

			if ( name === "live" ) {
				// bind live handler
				for ( var j = 0, l = context.length; j < l; j++ ) {
					jQuery.event.add( context[j], "live." + liveConvert( type, selector ),
						{ data: data, selector: selector, handler: fn, origType: type, origHandler: fn, preType: preType } );
				}

			} else {
				// unbind live handler
				context.unbind( "live." + liveConvert( type, selector ), fn );
			}
		}

		return this;
	};
});

function liveHandler( event ) {
	var stop, maxLevel, related, match, handleObj, elem, j, i, l, data, close, namespace, ret,
		elems = [],
		selectors = [],
		events = jQuery._data( this, "events" );

	// Make sure we avoid non-left-click bubbling in Firefox (#3861) and disabled elements in IE (#6911)
	if ( event.liveFired === this || !events || !events.live || event.target.disabled || event.button && event.type === "click" ) {
		return;
	}

	if ( event.namespace ) {
		namespace = new RegExp("(^|\\.)" + event.namespace.split(".").join("\\.(?:.*\\.)?") + "(\\.|$)");
	}

	event.liveFired = this;

	var live = events.live.slice(0);

	for ( j = 0; j < live.length; j++ ) {
		handleObj = live[j];

		if ( handleObj.origType.replace( rnamespaces, "" ) === event.type ) {
			selectors.push( handleObj.selector );

		} else {
			live.splice( j--, 1 );
		}
	}

	match = jQuery( event.target ).closest( selectors, event.currentTarget );

	for ( i = 0, l = match.length; i < l; i++ ) {
		close = match[i];

		for ( j = 0; j < live.length; j++ ) {
			handleObj = live[j];

			if ( close.selector === handleObj.selector && (!namespace || namespace.test( handleObj.namespace )) && !close.elem.disabled ) {
				elem = close.elem;
				related = null;

				// Those two events require additional checking
				if ( handleObj.preType === "mouseenter" || handleObj.preType === "mouseleave" ) {
					event.type = handleObj.preType;
					related = jQuery( event.relatedTarget ).closest( handleObj.selector )[0];

					// Make sure not to accidentally match a child element with the same selector
					if ( related && jQuery.contains( elem, related ) ) {
						related = elem;
					}
				}

				if ( !related || related !== elem ) {
					elems.push({ elem: elem, handleObj: handleObj, level: close.level });
				}
			}
		}
	}

	for ( i = 0, l = elems.length; i < l; i++ ) {
		match = elems[i];

		if ( maxLevel && match.level > maxLevel ) {
			break;
		}

		event.currentTarget = match.elem;
		event.data = match.handleObj.data;
		event.handleObj = match.handleObj;

		ret = match.handleObj.origHandler.apply( match.elem, arguments );

		if ( ret === false || event.isPropagationStopped() ) {
			maxLevel = match.level;

			if ( ret === false ) {
				stop = false;
			}
			if ( event.isImmediatePropagationStopped() ) {
				break;
			}
		}
	}

	return stop;
}

function liveConvert( type, selector ) {
	return (type && type !== "*" ? type + "." : "") + selector.replace(rperiod, "`").replace(rspaces, "&");
}

jQuery.each( ("blur focus focusin focusout load resize scroll unload click dblclick " +
	"mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave " +
	"change select submit keydown keypress keyup error").split(" "), function( i, name ) {

	// Handle event binding
	jQuery.fn[ name ] = function( data, fn ) {
		if ( fn == null ) {
			fn = data;
			data = null;
		}

		return arguments.length > 0 ?
			this.bind( name, data, fn ) :
			this.trigger( name );
	};

	if ( jQuery.attrFn ) {
		jQuery.attrFn[ name ] = true;
	}
});



/*!
 * Sizzle CSS Selector Engine
 *  Copyright 2011, The Dojo Foundation
 *  Released under the MIT, BSD, and GPL Licenses.
 *  More information: http://sizzlejs.com/
 */
(function(){

var chunker = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,
	done = 0,
	toString = Object.prototype.toString,
	hasDuplicate = false,
	baseHasDuplicate = true,
	rBackslash = /\\/g,
	rNonWord = /\W/;

// Here we check if the JavaScript engine is using some sort of
// optimization where it does not always call our comparision
// function. If that is the case, discard the hasDuplicate value.
//   Thus far that includes Google Chrome.
[0, 0].sort(function() {
	baseHasDuplicate = false;
	return 0;
});

var Sizzle = function( selector, context, results, seed ) {
	results = results || [];
	context = context || document;

	var origContext = context;

	if ( context.nodeType !== 1 && context.nodeType !== 9 ) {
		return [];
	}

	if ( !selector || typeof selector !== "string" ) {
		return results;
	}

	var m, set, checkSet, extra, ret, cur, pop, i,
		prune = true,
		contextXML = Sizzle.isXML( context ),
		parts = [],
		soFar = selector;

	// Reset the position of the chunker regexp (start from head)
	do {
		chunker.exec( "" );
		m = chunker.exec( soFar );

		if ( m ) {
			soFar = m[3];

			parts.push( m[1] );

			if ( m[2] ) {
				extra = m[3];
				break;
			}
		}
	} while ( m );

	if ( parts.length > 1 && origPOS.exec( selector ) ) {

		if ( parts.length === 2 && Expr.relative[ parts[0] ] ) {
			set = posProcess( parts[0] + parts[1], context );

		} else {
			set = Expr.relative[ parts[0] ] ?
				[ context ] :
				Sizzle( parts.shift(), context );

			while ( parts.length ) {
				selector = parts.shift();

				if ( Expr.relative[ selector ] ) {
					selector += parts.shift();
				}

				set = posProcess( selector, set );
			}
		}

	} else {
		// Take a shortcut and set the context if the root selector is an ID
		// (but not if it'll be faster if the inner selector is an ID)
		if ( !seed && parts.length > 1 && context.nodeType === 9 && !contextXML &&
				Expr.match.ID.test(parts[0]) && !Expr.match.ID.test(parts[parts.length - 1]) ) {

			ret = Sizzle.find( parts.shift(), context, contextXML );
			context = ret.expr ?
				Sizzle.filter( ret.expr, ret.set )[0] :
				ret.set[0];
		}

		if ( context ) {
			ret = seed ?
				{ expr: parts.pop(), set: makeArray(seed) } :
				Sizzle.find( parts.pop(), parts.length === 1 && (parts[0] === "~" || parts[0] === "+") && context.parentNode ? context.parentNode : context, contextXML );

			set = ret.expr ?
				Sizzle.filter( ret.expr, ret.set ) :
				ret.set;

			if ( parts.length > 0 ) {
				checkSet = makeArray( set );

			} else {
				prune = false;
			}

			while ( parts.length ) {
				cur = parts.pop();
				pop = cur;

				if ( !Expr.relative[ cur ] ) {
					cur = "";
				} else {
					pop = parts.pop();
				}

				if ( pop == null ) {
					pop = context;
				}

				Expr.relative[ cur ]( checkSet, pop, contextXML );
			}

		} else {
			checkSet = parts = [];
		}
	}

	if ( !checkSet ) {
		checkSet = set;
	}

	if ( !checkSet ) {
		Sizzle.error( cur || selector );
	}

	if ( toString.call(checkSet) === "[object Array]" ) {
		if ( !prune ) {
			results.push.apply( results, checkSet );

		} else if ( context && context.nodeType === 1 ) {
			for ( i = 0; checkSet[i] != null; i++ ) {
				if ( checkSet[i] && (checkSet[i] === true || checkSet[i].nodeType === 1 && Sizzle.contains(context, checkSet[i])) ) {
					results.push( set[i] );
				}
			}

		} else {
			for ( i = 0; checkSet[i] != null; i++ ) {
				if ( checkSet[i] && checkSet[i].nodeType === 1 ) {
					results.push( set[i] );
				}
			}
		}

	} else {
		makeArray( checkSet, results );
	}

	if ( extra ) {
		Sizzle( extra, origContext, results, seed );
		Sizzle.uniqueSort( results );
	}

	return results;
};

Sizzle.uniqueSort = function( results ) {
	if ( sortOrder ) {
		hasDuplicate = baseHasDuplicate;
		results.sort( sortOrder );

		if ( hasDuplicate ) {
			for ( var i = 1; i < results.length; i++ ) {
				if ( results[i] === results[ i - 1 ] ) {
					results.splice( i--, 1 );
				}
			}
		}
	}

	return results;
};

Sizzle.matches = function( expr, set ) {
	return Sizzle( expr, null, null, set );
};

Sizzle.matchesSelector = function( node, expr ) {
	return Sizzle( expr, null, null, [node] ).length > 0;
};

Sizzle.find = function( expr, context, isXML ) {
	var set;

	if ( !expr ) {
		return [];
	}

	for ( var i = 0, l = Expr.order.length; i < l; i++ ) {
		var match,
			type = Expr.order[i];

		if ( (match = Expr.leftMatch[ type ].exec( expr )) ) {
			var left = match[1];
			match.splice( 1, 1 );

			if ( left.substr( left.length - 1 ) !== "\\" ) {
				match[1] = (match[1] || "").replace( rBackslash, "" );
				set = Expr.find[ type ]( match, context, isXML );

				if ( set != null ) {
					expr = expr.replace( Expr.match[ type ], "" );
					break;
				}
			}
		}
	}

	if ( !set ) {
		set = typeof context.getElementsByTagName !== "undefined" ?
			context.getElementsByTagName( "*" ) :
			[];
	}

	return { set: set, expr: expr };
};

Sizzle.filter = function( expr, set, inplace, not ) {
	var match, anyFound,
		old = expr,
		result = [],
		curLoop = set,
		isXMLFilter = set && set[0] && Sizzle.isXML( set[0] );

	while ( expr && set.length ) {
		for ( var type in Expr.filter ) {
			if ( (match = Expr.leftMatch[ type ].exec( expr )) != null && match[2] ) {
				var found, item,
					filter = Expr.filter[ type ],
					left = match[1];

				anyFound = false;

				match.splice(1,1);

				if ( left.substr( left.length - 1 ) === "\\" ) {
					continue;
				}

				if ( curLoop === result ) {
					result = [];
				}

				if ( Expr.preFilter[ type ] ) {
					match = Expr.preFilter[ type ]( match, curLoop, inplace, result, not, isXMLFilter );

					if ( !match ) {
						anyFound = found = true;

					} else if ( match === true ) {
						continue;
					}
				}

				if ( match ) {
					for ( var i = 0; (item = curLoop[i]) != null; i++ ) {
						if ( item ) {
							found = filter( item, match, i, curLoop );
							var pass = not ^ !!found;

							if ( inplace && found != null ) {
								if ( pass ) {
									anyFound = true;

								} else {
									curLoop[i] = false;
								}

							} else if ( pass ) {
								result.push( item );
								anyFound = true;
							}
						}
					}
				}

				if ( found !== undefined ) {
					if ( !inplace ) {
						curLoop = result;
					}

					expr = expr.replace( Expr.match[ type ], "" );

					if ( !anyFound ) {
						return [];
					}

					break;
				}
			}
		}

		// Improper expression
		if ( expr === old ) {
			if ( anyFound == null ) {
				Sizzle.error( expr );

			} else {
				break;
			}
		}

		old = expr;
	}

	return curLoop;
};

Sizzle.error = function( msg ) {
	throw "Syntax error, unrecognized expression: " + msg;
};

var Expr = Sizzle.selectors = {
	order: [ "ID", "NAME", "TAG" ],

	match: {
		ID: /#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
		CLASS: /\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
		NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,
		ATTR: /\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,
		TAG: /^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,
		CHILD: /:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,
		POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,
		PSEUDO: /:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/
	},

	leftMatch: {},

	attrMap: {
		"class": "className",
		"for": "htmlFor"
	},

	attrHandle: {
		href: function( elem ) {
			return elem.getAttribute( "href" );
		},
		type: function( elem ) {
			return elem.getAttribute( "type" );
		}
	},

	relative: {
		"+": function(checkSet, part){
			var isPartStr = typeof part === "string",
				isTag = isPartStr && !rNonWord.test( part ),
				isPartStrNotTag = isPartStr && !isTag;

			if ( isTag ) {
				part = part.toLowerCase();
			}

			for ( var i = 0, l = checkSet.length, elem; i < l; i++ ) {
				if ( (elem = checkSet[i]) ) {
					while ( (elem = elem.previousSibling) && elem.nodeType !== 1 ) {}

					checkSet[i] = isPartStrNotTag || elem && elem.nodeName.toLowerCase() === part ?
						elem || false :
						elem === part;
				}
			}

			if ( isPartStrNotTag ) {
				Sizzle.filter( part, checkSet, true );
			}
		},

		">": function( checkSet, part ) {
			var elem,
				isPartStr = typeof part === "string",
				i = 0,
				l = checkSet.length;

			if ( isPartStr && !rNonWord.test( part ) ) {
				part = part.toLowerCase();

				for ( ; i < l; i++ ) {
					elem = checkSet[i];

					if ( elem ) {
						var parent = elem.parentNode;
						checkSet[i] = parent.nodeName.toLowerCase() === part ? parent : false;
					}
				}

			} else {
				for ( ; i < l; i++ ) {
					elem = checkSet[i];

					if ( elem ) {
						checkSet[i] = isPartStr ?
							elem.parentNode :
							elem.parentNode === part;
					}
				}

				if ( isPartStr ) {
					Sizzle.filter( part, checkSet, true );
				}
			}
		},

		"": function(checkSet, part, isXML){
			var nodeCheck,
				doneName = done++,
				checkFn = dirCheck;

			if ( typeof part === "string" && !rNonWord.test( part ) ) {
				part = part.toLowerCase();
				nodeCheck = part;
				checkFn = dirNodeCheck;
			}

			checkFn( "parentNode", part, doneName, checkSet, nodeCheck, isXML );
		},

		"~": function( checkSet, part, isXML ) {
			var nodeCheck,
				doneName = done++,
				checkFn = dirCheck;

			if ( typeof part === "string" && !rNonWord.test( part ) ) {
				part = part.toLowerCase();
				nodeCheck = part;
				checkFn = dirNodeCheck;
			}

			checkFn( "previousSibling", part, doneName, checkSet, nodeCheck, isXML );
		}
	},

	find: {
		ID: function( match, context, isXML ) {
			if ( typeof context.getElementById !== "undefined" && !isXML ) {
				var m = context.getElementById(match[1]);
				// Check parentNode to catch when Blackberry 4.6 returns
				// nodes that are no longer in the document #6963
				return m && m.parentNode ? [m] : [];
			}
		},

		NAME: function( match, context ) {
			if ( typeof context.getElementsByName !== "undefined" ) {
				var ret = [],
					results = context.getElementsByName( match[1] );

				for ( var i = 0, l = results.length; i < l; i++ ) {
					if ( results[i].getAttribute("name") === match[1] ) {
						ret.push( results[i] );
					}
				}

				return ret.length === 0 ? null : ret;
			}
		},

		TAG: function( match, context ) {
			if ( typeof context.getElementsByTagName !== "undefined" ) {
				return context.getElementsByTagName( match[1] );
			}
		}
	},
	preFilter: {
		CLASS: function( match, curLoop, inplace, result, not, isXML ) {
			match = " " + match[1].replace( rBackslash, "" ) + " ";

			if ( isXML ) {
				return match;
			}

			for ( var i = 0, elem; (elem = curLoop[i]) != null; i++ ) {
				if ( elem ) {
					if ( not ^ (elem.className && (" " + elem.className + " ").replace(/[\t\n\r]/g, " ").indexOf(match) >= 0) ) {
						if ( !inplace ) {
							result.push( elem );
						}

					} else if ( inplace ) {
						curLoop[i] = false;
					}
				}
			}

			return false;
		},

		ID: function( match ) {
			return match[1].replace( rBackslash, "" );
		},

		TAG: function( match, curLoop ) {
			return match[1].replace( rBackslash, "" ).toLowerCase();
		},

		CHILD: function( match ) {
			if ( match[1] === "nth" ) {
				if ( !match[2] ) {
					Sizzle.error( match[0] );
				}

				match[2] = match[2].replace(/^\+|\s*/g, '');

				// parse equations like 'even', 'odd', '5', '2n', '3n+2', '4n-1', '-n+6'
				var test = /(-?)(\d*)(?:n([+\-]?\d*))?/.exec(
					match[2] === "even" && "2n" || match[2] === "odd" && "2n+1" ||
					!/\D/.test( match[2] ) && "0n+" + match[2] || match[2]);

				// calculate the numbers (first)n+(last) including if they are negative
				match[2] = (test[1] + (test[2] || 1)) - 0;
				match[3] = test[3] - 0;
			}
			else if ( match[2] ) {
				Sizzle.error( match[0] );
			}

			// TODO: Move to normal caching system
			match[0] = done++;

			return match;
		},

		ATTR: function( match, curLoop, inplace, result, not, isXML ) {
			var name = match[1] = match[1].replace( rBackslash, "" );

			if ( !isXML && Expr.attrMap[name] ) {
				match[1] = Expr.attrMap[name];
			}

			// Handle if an un-quoted value was used
			match[4] = ( match[4] || match[5] || "" ).replace( rBackslash, "" );

			if ( match[2] === "~=" ) {
				match[4] = " " + match[4] + " ";
			}

			return match;
		},

		PSEUDO: function( match, curLoop, inplace, result, not ) {
			if ( match[1] === "not" ) {
				// If we're dealing with a complex expression, or a simple one
				if ( ( chunker.exec(match[3]) || "" ).length > 1 || /^\w/.test(match[3]) ) {
					match[3] = Sizzle(match[3], null, null, curLoop);

				} else {
					var ret = Sizzle.filter(match[3], curLoop, inplace, true ^ not);

					if ( !inplace ) {
						result.push.apply( result, ret );
					}

					return false;
				}

			} else if ( Expr.match.POS.test( match[0] ) || Expr.match.CHILD.test( match[0] ) ) {
				return true;
			}

			return match;
		},

		POS: function( match ) {
			match.unshift( true );

			return match;
		}
	},

	filters: {
		enabled: function( elem ) {
			return elem.disabled === false && elem.type !== "hidden";
		},

		disabled: function( elem ) {
			return elem.disabled === true;
		},

		checked: function( elem ) {
			return elem.checked === true;
		},

		selected: function( elem ) {
			// Accessing this property makes selected-by-default
			// options in Safari work properly
			if ( elem.parentNode ) {
				elem.parentNode.selectedIndex;
			}

			return elem.selected === true;
		},

		parent: function( elem ) {
			return !!elem.firstChild;
		},

		empty: function( elem ) {
			return !elem.firstChild;
		},

		has: function( elem, i, match ) {
			return !!Sizzle( match[3], elem ).length;
		},

		header: function( elem ) {
			return (/h\d/i).test( elem.nodeName );
		},

		text: function( elem ) {
			var attr = elem.getAttribute( "type" ), type = elem.type;
			// IE6 and 7 will map elem.type to 'text' for new HTML5 types (search, etc)
			// use getAttribute instead to test this case
			return elem.nodeName.toLowerCase() === "input" && "text" === type && ( attr === type || attr === null );
		},

		radio: function( elem ) {
			return elem.nodeName.toLowerCase() === "input" && "radio" === elem.type;
		},

		checkbox: function( elem ) {
			return elem.nodeName.toLowerCase() === "input" && "checkbox" === elem.type;
		},

		file: function( elem ) {
			return elem.nodeName.toLowerCase() === "input" && "file" === elem.type;
		},

		password: function( elem ) {
			return elem.nodeName.toLowerCase() === "input" && "password" === elem.type;
		},

		submit: function( elem ) {
			var name = elem.nodeName.toLowerCase();
			return (name === "input" || name === "button") && "submit" === elem.type;
		},

		image: function( elem ) {
			return elem.nodeName.toLowerCase() === "input" && "image" === elem.type;
		},

		reset: function( elem ) {
			return elem.nodeName.toLowerCase() === "input" && "reset" === elem.type;
		},

		button: function( elem ) {
			var name = elem.nodeName.toLowerCase();
			return name === "input" && "button" === elem.type || name === "button";
		},

		input: function( elem ) {
			return (/input|select|textarea|button/i).test( elem.nodeName );
		},

		focus: function( elem ) {
			return elem === elem.ownerDocument.activeElement;
		}
	},
	setFilters: {
		first: function( elem, i ) {
			return i === 0;
		},

		last: function( elem, i, match, array ) {
			return i === array.length - 1;
		},

		even: function( elem, i ) {
			return i % 2 === 0;
		},

		odd: function( elem, i ) {
			return i % 2 === 1;
		},

		lt: function( elem, i, match ) {
			return i < match[3] - 0;
		},

		gt: function( elem, i, match ) {
			return i > match[3] - 0;
		},

		nth: function( elem, i, match ) {
			return match[3] - 0 === i;
		},

		eq: function( elem, i, match ) {
			return match[3] - 0 === i;
		}
	},
	filter: {
		PSEUDO: function( elem, match, i, array ) {
			var name = match[1],
				filter = Expr.filters[ name ];

			if ( filter ) {
				return filter( elem, i, match, array );

			} else if ( name === "contains" ) {
				return (elem.textContent || elem.innerText || Sizzle.getText([ elem ]) || "").indexOf(match[3]) >= 0;

			} else if ( name === "not" ) {
				var not = match[3];

				for ( var j = 0, l = not.length; j < l; j++ ) {
					if ( not[j] === elem ) {
						return false;
					}
				}

				return true;

			} else {
				Sizzle.error( name );
			}
		},

		CHILD: function( elem, match ) {
			var type = match[1],
				node = elem;

			switch ( type ) {
				case "only":
				case "first":
					while ( (node = node.previousSibling) )	 {
						if ( node.nodeType === 1 ) {
							return false;
						}
					}

					if ( type === "first" ) {
						return true;
					}

					node = elem;

				case "last":
					while ( (node = node.nextSibling) )	 {
						if ( node.nodeType === 1 ) {
							return false;
						}
					}

					return true;

				case "nth":
					var first = match[2],
						last = match[3];

					if ( first === 1 && last === 0 ) {
						return true;
					}

					var doneName = match[0],
						parent = elem.parentNode;

					if ( parent && (parent.sizcache !== doneName || !elem.nodeIndex) ) {
						var count = 0;

						for ( node = parent.firstChild; node; node = node.nextSibling ) {
							if ( node.nodeType === 1 ) {
								node.nodeIndex = ++count;
							}
						}

						parent.sizcache = doneName;
					}

					var diff = elem.nodeIndex - last;

					if ( first === 0 ) {
						return diff === 0;

					} else {
						return ( diff % first === 0 && diff / first >= 0 );
					}
			}
		},

		ID: function( elem, match ) {
			return elem.nodeType === 1 && elem.getAttribute("id") === match;
		},

		TAG: function( elem, match ) {
			return (match === "*" && elem.nodeType === 1) || elem.nodeName.toLowerCase() === match;
		},

		CLASS: function( elem, match ) {
			return (" " + (elem.className || elem.getAttribute("class")) + " ")
				.indexOf( match ) > -1;
		},

		ATTR: function( elem, match ) {
			var name = match[1],
				result = Expr.attrHandle[ name ] ?
					Expr.attrHandle[ name ]( elem ) :
					elem[ name ] != null ?
						elem[ name ] :
						elem.getAttribute( name ),
				value = result + "",
				type = match[2],
				check = match[4];

			return result == null ?
				type === "!=" :
				type === "=" ?
				value === check :
				type === "*=" ?
				value.indexOf(check) >= 0 :
				type === "~=" ?
				(" " + value + " ").indexOf(check) >= 0 :
				!check ?
				value && result !== false :
				type === "!=" ?
				value !== check :
				type === "^=" ?
				value.indexOf(check) === 0 :
				type === "$=" ?
				value.substr(value.length - check.length) === check :
				type === "|=" ?
				value === check || value.substr(0, check.length + 1) === check + "-" :
				false;
		},

		POS: function( elem, match, i, array ) {
			var name = match[2],
				filter = Expr.setFilters[ name ];

			if ( filter ) {
				return filter( elem, i, match, array );
			}
		}
	}
};

var origPOS = Expr.match.POS,
	fescape = function(all, num){
		return "\\" + (num - 0 + 1);
	};

for ( var type in Expr.match ) {
	Expr.match[ type ] = new RegExp( Expr.match[ type ].source + (/(?![^\[]*\])(?![^\(]*\))/.source) );
	Expr.leftMatch[ type ] = new RegExp( /(^(?:.|\r|\n)*?)/.source + Expr.match[ type ].source.replace(/\\(\d+)/g, fescape) );
}

var makeArray = function( array, results ) {
	array = Array.prototype.slice.call( array, 0 );

	if ( results ) {
		results.push.apply( results, array );
		return results;
	}

	return array;
};

// Perform a simple check to determine if the browser is capable of
// converting a NodeList to an array using builtin methods.
// Also verifies that the returned array holds DOM nodes
// (which is not the case in the Blackberry browser)
try {
	Array.prototype.slice.call( document.documentElement.childNodes, 0 )[0].nodeType;

// Provide a fallback method if it does not work
} catch( e ) {
	makeArray = function( array, results ) {
		var i = 0,
			ret = results || [];

		if ( toString.call(array) === "[object Array]" ) {
			Array.prototype.push.apply( ret, array );

		} else {
			if ( typeof array.length === "number" ) {
				for ( var l = array.length; i < l; i++ ) {
					ret.push( array[i] );
				}

			} else {
				for ( ; array[i]; i++ ) {
					ret.push( array[i] );
				}
			}
		}

		return ret;
	};
}

var sortOrder, siblingCheck;

if ( document.documentElement.compareDocumentPosition ) {
	sortOrder = function( a, b ) {
		if ( a === b ) {
			hasDuplicate = true;
			return 0;
		}

		if ( !a.compareDocumentPosition || !b.compareDocumentPosition ) {
			return a.compareDocumentPosition ? -1 : 1;
		}

		return a.compareDocumentPosition(b) & 4 ? -1 : 1;
	};

} else {
	sortOrder = function( a, b ) {
		var al, bl,
			ap = [],
			bp = [],
			aup = a.parentNode,
			bup = b.parentNode,
			cur = aup;

		// The nodes are identical, we can exit early
		if ( a === b ) {
			hasDuplicate = true;
			return 0;

		// If the nodes are siblings (or identical) we can do a quick check
		} else if ( aup === bup ) {
			return siblingCheck( a, b );

		// If no parents were found then the nodes are disconnected
		} else if ( !aup ) {
			return -1;

		} else if ( !bup ) {
			return 1;
		}

		// Otherwise they're somewhere else in the tree so we need
		// to build up a full list of the parentNodes for comparison
		while ( cur ) {
			ap.unshift( cur );
			cur = cur.parentNode;
		}

		cur = bup;

		while ( cur ) {
			bp.unshift( cur );
			cur = cur.parentNode;
		}

		al = ap.length;
		bl = bp.length;

		// Start walking down the tree looking for a discrepancy
		for ( var i = 0; i < al && i < bl; i++ ) {
			if ( ap[i] !== bp[i] ) {
				return siblingCheck( ap[i], bp[i] );
			}
		}

		// We ended someplace up the tree so do a sibling check
		return i === al ?
			siblingCheck( a, bp[i], -1 ) :
			siblingCheck( ap[i], b, 1 );
	};

	siblingCheck = function( a, b, ret ) {
		if ( a === b ) {
			return ret;
		}

		var cur = a.nextSibling;

		while ( cur ) {
			if ( cur === b ) {
				return -1;
			}

			cur = cur.nextSibling;
		}

		return 1;
	};
}

// Utility function for retreiving the text value of an array of DOM nodes
Sizzle.getText = function( elems ) {
	var ret = "", elem;

	for ( var i = 0; elems[i]; i++ ) {
		elem = elems[i];

		// Get the text from text nodes and CDATA nodes
		if ( elem.nodeType === 3 || elem.nodeType === 4 ) {
			ret += elem.nodeValue;

		// Traverse everything else, except comment nodes
		} else if ( elem.nodeType !== 8 ) {
			ret += Sizzle.getText( elem.childNodes );
		}
	}

	return ret;
};

// Check to see if the browser returns elements by name when
// querying by getElementById (and provide a workaround)
(function(){
	// We're going to inject a fake input element with a specified name
	var form = document.createElement("div"),
		id = "script" + (new Date()).getTime(),
		root = document.documentElement;

	form.innerHTML = "<a name='" + id + "'/>";

	// Inject it into the root element, check its status, and remove it quickly
	root.insertBefore( form, root.firstChild );

	// The workaround has to do additional checks after a getElementById
	// Which slows things down for other browsers (hence the branching)
	if ( document.getElementById( id ) ) {
		Expr.find.ID = function( match, context, isXML ) {
			if ( typeof context.getElementById !== "undefined" && !isXML ) {
				var m = context.getElementById(match[1]);

				return m ?
					m.id === match[1] || typeof m.getAttributeNode !== "undefined" && m.getAttributeNode("id").nodeValue === match[1] ?
						[m] :
						undefined :
					[];
			}
		};

		Expr.filter.ID = function( elem, match ) {
			var node = typeof elem.getAttributeNode !== "undefined" && elem.getAttributeNode("id");

			return elem.nodeType === 1 && node && node.nodeValue === match;
		};
	}

	root.removeChild( form );

	// release memory in IE
	root = form = null;
})();

(function(){
	// Check to see if the browser returns only elements
	// when doing getElementsByTagName("*")

	// Create a fake element
	var div = document.createElement("div");
	div.appendChild( document.createComment("") );

	// Make sure no comments are found
	if ( div.getElementsByTagName("*").length > 0 ) {
		Expr.find.TAG = function( match, context ) {
			var results = context.getElementsByTagName( match[1] );

			// Filter out possible comments
			if ( match[1] === "*" ) {
				var tmp = [];

				for ( var i = 0; results[i]; i++ ) {
					if ( results[i].nodeType === 1 ) {
						tmp.push( results[i] );
					}
				}

				results = tmp;
			}

			return results;
		};
	}

	// Check to see if an attribute returns normalized href attributes
	div.innerHTML = "<a href='#'></a>";

	if ( div.firstChild && typeof div.firstChild.getAttribute !== "undefined" &&
			div.firstChild.getAttribute("href") !== "#" ) {

		Expr.attrHandle.href = function( elem ) {
			return elem.getAttribute( "href", 2 );
		};
	}

	// release memory in IE
	div = null;
})();

if ( document.querySelectorAll ) {
	(function(){
		var oldSizzle = Sizzle,
			div = document.createElement("div"),
			id = "__sizzle__";

		div.innerHTML = "<p class='TEST'></p>";

		// Safari can't handle uppercase or unicode characters when
		// in quirks mode.
		if ( div.querySelectorAll && div.querySelectorAll(".TEST").length === 0 ) {
			return;
		}

		Sizzle = function( query, context, extra, seed ) {
			context = context || document;

			// Only use querySelectorAll on non-XML documents
			// (ID selectors don't work in non-HTML documents)
			if ( !seed && !Sizzle.isXML(context) ) {
				// See if we find a selector to speed up
				var match = /^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec( query );

				if ( match && (context.nodeType === 1 || context.nodeType === 9) ) {
					// Speed-up: Sizzle("TAG")
					if ( match[1] ) {
						return makeArray( context.getElementsByTagName( query ), extra );

					// Speed-up: Sizzle(".CLASS")
					} else if ( match[2] && Expr.find.CLASS && context.getElementsByClassName ) {
						return makeArray( context.getElementsByClassName( match[2] ), extra );
					}
				}

				if ( context.nodeType === 9 ) {
					// Speed-up: Sizzle("body")
					// The body element only exists once, optimize finding it
					if ( query === "body" && context.body ) {
						return makeArray( [ context.body ], extra );

					// Speed-up: Sizzle("#ID")
					} else if ( match && match[3] ) {
						var elem = context.getElementById( match[3] );

						// Check parentNode to catch when Blackberry 4.6 returns
						// nodes that are no longer in the document #6963
						if ( elem && elem.parentNode ) {
							// Handle the case where IE and Opera return items
							// by name instead of ID
							if ( elem.id === match[3] ) {
								return makeArray( [ elem ], extra );
							}

						} else {
							return makeArray( [], extra );
						}
					}

					try {
						return makeArray( context.querySelectorAll(query), extra );
					} catch(qsaError) {}

				// qSA works strangely on Element-rooted queries
				// We can work around this by specifying an extra ID on the root
				// and working up from there (Thanks to Andrew Dupont for the technique)
				// IE 8 doesn't work on object elements
				} else if ( context.nodeType === 1 && context.nodeName.toLowerCase() !== "object" ) {
					var oldContext = context,
						old = context.getAttribute( "id" ),
						nid = old || id,
						hasParent = context.parentNode,
						relativeHierarchySelector = /^\s*[+~]/.test( query );

					if ( !old ) {
						context.setAttribute( "id", nid );
					} else {
						nid = nid.replace( /'/g, "\\$&" );
					}
					if ( relativeHierarchySelector && hasParent ) {
						context = context.parentNode;
					}

					try {
						if ( !relativeHierarchySelector || hasParent ) {
							return makeArray( context.querySelectorAll( "[id='" + nid + "'] " + query ), extra );
						}

					} catch(pseudoError) {
					} finally {
						if ( !old ) {
							oldContext.removeAttribute( "id" );
						}
					}
				}
			}

			return oldSizzle(query, context, extra, seed);
		};

		for ( var prop in oldSizzle ) {
			Sizzle[ prop ] = oldSizzle[ prop ];
		}

		// release memory in IE
		div = null;
	})();
}

(function(){
	var html = document.documentElement,
		matches = html.matchesSelector || html.mozMatchesSelector || html.webkitMatchesSelector || html.msMatchesSelector;

	if ( matches ) {
		// Check to see if it's possible to do matchesSelector
		// on a disconnected node (IE 9 fails this)
		var disconnectedMatch = !matches.call( document.createElement( "div" ), "div" ),
			pseudoWorks = false;

		try {
			// This should fail with an exception
			// Gecko does not error, returns false instead
			matches.call( document.documentElement, "[test!='']:sizzle" );

		} catch( pseudoError ) {
			pseudoWorks = true;
		}

		Sizzle.matchesSelector = function( node, expr ) {
			// Make sure that attribute selectors are quoted
			expr = expr.replace(/\=\s*([^'"\]]*)\s*\]/g, "='$1']");

			if ( !Sizzle.isXML( node ) ) {
				try {
					if ( pseudoWorks || !Expr.match.PSEUDO.test( expr ) && !/!=/.test( expr ) ) {
						var ret = matches.call( node, expr );

						// IE 9's matchesSelector returns false on disconnected nodes
						if ( ret || !disconnectedMatch ||
								// As well, disconnected nodes are said to be in a document
								// fragment in IE 9, so check for that
								node.document && node.document.nodeType !== 11 ) {
							return ret;
						}
					}
				} catch(e) {}
			}

			return Sizzle(expr, null, null, [node]).length > 0;
		};
	}
})();

(function(){
	var div = document.createElement("div");

	div.innerHTML = "<div class='test e'></div><div class='test'></div>";

	// Opera can't find a second classname (in 9.6)
	// Also, make sure that getElementsByClassName actually exists
	if ( !div.getElementsByClassName || div.getElementsByClassName("e").length === 0 ) {
		return;
	}

	// Safari caches class attributes, doesn't catch changes (in 3.2)
	div.lastChild.className = "e";

	if ( div.getElementsByClassName("e").length === 1 ) {
		return;
	}

	Expr.order.splice(1, 0, "CLASS");
	Expr.find.CLASS = function( match, context, isXML ) {
		if ( typeof context.getElementsByClassName !== "undefined" && !isXML ) {
			return context.getElementsByClassName(match[1]);
		}
	};

	// release memory in IE
	div = null;
})();

function dirNodeCheck( dir, cur, doneName, checkSet, nodeCheck, isXML ) {
	for ( var i = 0, l = checkSet.length; i < l; i++ ) {
		var elem = checkSet[i];

		if ( elem ) {
			var match = false;

			elem = elem[dir];

			while ( elem ) {
				if ( elem.sizcache === doneName ) {
					match = checkSet[elem.sizset];
					break;
				}

				if ( elem.nodeType === 1 && !isXML ){
					elem.sizcache = doneName;
					elem.sizset = i;
				}

				if ( elem.nodeName.toLowerCase() === cur ) {
					match = elem;
					break;
				}

				elem = elem[dir];
			}

			checkSet[i] = match;
		}
	}
}

function dirCheck( dir, cur, doneName, checkSet, nodeCheck, isXML ) {
	for ( var i = 0, l = checkSet.length; i < l; i++ ) {
		var elem = checkSet[i];

		if ( elem ) {
			var match = false;

			elem = elem[dir];

			while ( elem ) {
				if ( elem.sizcache === doneName ) {
					match = checkSet[elem.sizset];
					break;
				}

				if ( elem.nodeType === 1 ) {
					if ( !isXML ) {
						elem.sizcache = doneName;
						elem.sizset = i;
					}

					if ( typeof cur !== "string" ) {
						if ( elem === cur ) {
							match = true;
							break;
						}

					} else if ( Sizzle.filter( cur, [elem] ).length > 0 ) {
						match = elem;
						break;
					}
				}

				elem = elem[dir];
			}

			checkSet[i] = match;
		}
	}
}

if ( document.documentElement.contains ) {
	Sizzle.contains = function( a, b ) {
		return a !== b && (a.contains ? a.contains(b) : true);
	};

} else if ( document.documentElement.compareDocumentPosition ) {
	Sizzle.contains = function( a, b ) {
		return !!(a.compareDocumentPosition(b) & 16);
	};

} else {
	Sizzle.contains = function() {
		return false;
	};
}

Sizzle.isXML = function( elem ) {
	// documentElement is verified for cases where it doesn't yet exist
	// (such as loading iframes in IE - #4833)
	var documentElement = (elem ? elem.ownerDocument || elem : 0).documentElement;

	return documentElement ? documentElement.nodeName !== "HTML" : false;
};

var posProcess = function( selector, context ) {
	var match,
		tmpSet = [],
		later = "",
		root = context.nodeType ? [context] : context;

	// Position selectors must be done after the filter
	// And so must :not(positional) so we move all PSEUDOs to the end
	while ( (match = Expr.match.PSEUDO.exec( selector )) ) {
		later += match[0];
		selector = selector.replace( Expr.match.PSEUDO, "" );
	}

	selector = Expr.relative[selector] ? selector + "*" : selector;

	for ( var i = 0, l = root.length; i < l; i++ ) {
		Sizzle( selector, root[i], tmpSet );
	}

	return Sizzle.filter( later, tmpSet );
};

// EXPOSE
jQuery.find = Sizzle;
jQuery.expr = Sizzle.selectors;
jQuery.expr[":"] = jQuery.expr.filters;
jQuery.unique = Sizzle.uniqueSort;
jQuery.text = Sizzle.getText;
jQuery.isXMLDoc = Sizzle.isXML;
jQuery.contains = Sizzle.contains;


})();


var runtil = /Until$/,
	rparentsprev = /^(?:parents|prevUntil|prevAll)/,
	// Note: This RegExp should be improved, or likely pulled from Sizzle
	rmultiselector = /,/,
	isSimple = /^.[^:#\[\.,]*$/,
	slice = Array.prototype.slice,
	POS = jQuery.expr.match.POS,
	// methods guaranteed to produce a unique set when starting from a unique set
	guaranteedUnique = {
		children: true,
		contents: true,
		next: true,
		prev: true
	};

jQuery.fn.extend({
	find: function( selector ) {
		var self = this,
			i, l;

		if ( typeof selector !== "string" ) {
			return jQuery( selector ).filter(function() {
				for ( i = 0, l = self.length; i < l; i++ ) {
					if ( jQuery.contains( self[ i ], this ) ) {
						return true;
					}
				}
			});
		}

		var ret = this.pushStack( "", "find", selector ),
			length, n, r;

		for ( i = 0, l = this.length; i < l; i++ ) {
			length = ret.length;
			jQuery.find( selector, this[i], ret );

			if ( i > 0 ) {
				// Make sure that the results are unique
				for ( n = length; n < ret.length; n++ ) {
					for ( r = 0; r < length; r++ ) {
						if ( ret[r] === ret[n] ) {
							ret.splice(n--, 1);
							break;
						}
					}
				}
			}
		}

		return ret;
	},

	has: function( target ) {
		var targets = jQuery( target );
		return this.filter(function() {
			for ( var i = 0, l = targets.length; i < l; i++ ) {
				if ( jQuery.contains( this, targets[i] ) ) {
					return true;
				}
			}
		});
	},

	not: function( selector ) {
		return this.pushStack( winnow(this, selector, false), "not", selector);
	},

	filter: function( selector ) {
		return this.pushStack( winnow(this, selector, true), "filter", selector );
	},

	is: function( selector ) {
		return !!selector && ( typeof selector === "string" ?
			jQuery.filter( selector, this ).length > 0 :
			this.filter( selector ).length > 0 );
	},

	closest: function( selectors, context ) {
		var ret = [], i, l, cur = this[0];

		// Array
		if ( jQuery.isArray( selectors ) ) {
			var match, selector,
				matches = {},
				level = 1;

			if ( cur && selectors.length ) {
				for ( i = 0, l = selectors.length; i < l; i++ ) {
					selector = selectors[i];

					if ( !matches[ selector ] ) {
						matches[ selector ] = POS.test( selector ) ?
							jQuery( selector, context || this.context ) :
							selector;
					}
				}

				while ( cur && cur.ownerDocument && cur !== context ) {
					for ( selector in matches ) {
						match = matches[ selector ];

						if ( match.jquery ? match.index( cur ) > -1 : jQuery( cur ).is( match ) ) {
							ret.push({ selector: selector, elem: cur, level: level });
						}
					}

					cur = cur.parentNode;
					level++;
				}
			}

			return ret;
		}

		// String
		var pos = POS.test( selectors ) || typeof selectors !== "string" ?
				jQuery( selectors, context || this.context ) :
				0;

		for ( i = 0, l = this.length; i < l; i++ ) {
			cur = this[i];

			while ( cur ) {
				if ( pos ? pos.index(cur) > -1 : jQuery.find.matchesSelector(cur, selectors) ) {
					ret.push( cur );
					break;

				} else {
					cur = cur.parentNode;
					if ( !cur || !cur.ownerDocument || cur === context || cur.nodeType === 11 ) {
						break;
					}
				}
			}
		}

		ret = ret.length > 1 ? jQuery.unique( ret ) : ret;

		return this.pushStack( ret, "closest", selectors );
	},

	// Determine the position of an element within
	// the matched set of elements
	index: function( elem ) {
		if ( !elem || typeof elem === "string" ) {
			return jQuery.inArray( this[0],
				// If it receives a string, the selector is used
				// If it receives nothing, the siblings are used
				elem ? jQuery( elem ) : this.parent().children() );
		}
		// Locate the position of the desired element
		return jQuery.inArray(
			// If it receives a jQuery object, the first element is used
			elem.jquery ? elem[0] : elem, this );
	},

	add: function( selector, context ) {
		var set = typeof selector === "string" ?
				jQuery( selector, context ) :
				jQuery.makeArray( selector && selector.nodeType ? [ selector ] : selector ),
			all = jQuery.merge( this.get(), set );

		return this.pushStack( isDisconnected( set[0] ) || isDisconnected( all[0] ) ?
			all :
			jQuery.unique( all ) );
	},

	andSelf: function() {
		return this.add( this.prevObject );
	}
});

// A painfully simple check to see if an element is disconnected
// from a document (should be improved, where feasible).
function isDisconnected( node ) {
	return !node || !node.parentNode || node.parentNode.nodeType === 11;
}

jQuery.each({
	parent: function( elem ) {
		var parent = elem.parentNode;
		return parent && parent.nodeType !== 11 ? parent : null;
	},
	parents: function( elem ) {
		return jQuery.dir( elem, "parentNode" );
	},
	parentsUntil: function( elem, i, until ) {
		return jQuery.dir( elem, "parentNode", until );
	},
	next: function( elem ) {
		return jQuery.nth( elem, 2, "nextSibling" );
	},
	prev: function( elem ) {
		return jQuery.nth( elem, 2, "previousSibling" );
	},
	nextAll: function( elem ) {
		return jQuery.dir( elem, "nextSibling" );
	},
	prevAll: function( elem ) {
		return jQuery.dir( elem, "previousSibling" );
	},
	nextUntil: function( elem, i, until ) {
		return jQuery.dir( elem, "nextSibling", until );
	},
	prevUntil: function( elem, i, until ) {
		return jQuery.dir( elem, "previousSibling", until );
	},
	siblings: function( elem ) {
		return jQuery.sibling( elem.parentNode.firstChild, elem );
	},
	children: function( elem ) {
		return jQuery.sibling( elem.firstChild );
	},
	contents: function( elem ) {
		return jQuery.nodeName( elem, "iframe" ) ?
			elem.contentDocument || elem.contentWindow.document :
			jQuery.makeArray( elem.childNodes );
	}
}, function( name, fn ) {
	jQuery.fn[ name ] = function( until, selector ) {
		var ret = jQuery.map( this, fn, until ),
			// The variable 'args' was introduced in
			// https://github.com/jquery/jquery/commit/52a0238
			// to work around a bug in Chrome 10 (Dev) and should be removed when the bug is fixed.
			// http://code.google.com/p/v8/issues/detail?id=1050
			args = slice.call(arguments);

		if ( !runtil.test( name ) ) {
			selector = until;
		}

		if ( selector && typeof selector === "string" ) {
			ret = jQuery.filter( selector, ret );
		}

		ret = this.length > 1 && !guaranteedUnique[ name ] ? jQuery.unique( ret ) : ret;

		if ( (this.length > 1 || rmultiselector.test( selector )) && rparentsprev.test( name ) ) {
			ret = ret.reverse();
		}

		return this.pushStack( ret, name, args.join(",") );
	};
});

jQuery.extend({
	filter: function( expr, elems, not ) {
		if ( not ) {
			expr = ":not(" + expr + ")";
		}

		return elems.length === 1 ?
			jQuery.find.matchesSelector(elems[0], expr) ? [ elems[0] ] : [] :
			jQuery.find.matches(expr, elems);
	},

	dir: function( elem, dir, until ) {
		var matched = [],
			cur = elem[ dir ];

		while ( cur && cur.nodeType !== 9 && (until === undefined || cur.nodeType !== 1 || !jQuery( cur ).is( until )) ) {
			if ( cur.nodeType === 1 ) {
				matched.push( cur );
			}
			cur = cur[dir];
		}
		return matched;
	},

	nth: function( cur, result, dir, elem ) {
		result = result || 1;
		var num = 0;

		for ( ; cur; cur = cur[dir] ) {
			if ( cur.nodeType === 1 && ++num === result ) {
				break;
			}
		}

		return cur;
	},

	sibling: function( n, elem ) {
		var r = [];

		for ( ; n; n = n.nextSibling ) {
			if ( n.nodeType === 1 && n !== elem ) {
				r.push( n );
			}
		}

		return r;
	}
});

// Implement the identical functionality for filter and not
function winnow( elements, qualifier, keep ) {

	// Can't pass null or undefined to indexOf in Firefox 4
	// Set to 0 to skip string check
	qualifier = qualifier || 0;

	if ( jQuery.isFunction( qualifier ) ) {
		return jQuery.grep(elements, function( elem, i ) {
			var retVal = !!qualifier.call( elem, i, elem );
			return retVal === keep;
		});

	} else if ( qualifier.nodeType ) {
		return jQuery.grep(elements, function( elem, i ) {
			return (elem === qualifier) === keep;
		});

	} else if ( typeof qualifier === "string" ) {
		var filtered = jQuery.grep(elements, function( elem ) {
			return elem.nodeType === 1;
		});

		if ( isSimple.test( qualifier ) ) {
			return jQuery.filter(qualifier, filtered, !keep);
		} else {
			qualifier = jQuery.filter( qualifier, filtered );
		}
	}

	return jQuery.grep(elements, function( elem, i ) {
		return (jQuery.inArray( elem, qualifier ) >= 0) === keep;
	});
}




var rinlinejQuery = / jQuery\d+="(?:\d+|null)"/g,
	rleadingWhitespace = /^\s+/,
	rxhtmlTag = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,
	rtagName = /<([\w:]+)/,
	rtbody = /<tbody/i,
	rhtml = /<|&#?\w+;/,
	rnocache = /<(?:script|object|embed|option|style)/i,
	// checked="checked" or checked
	rchecked = /checked\s*(?:[^=]|=\s*.checked.)/i,
	rscriptType = /\/(java|ecma)script/i,
	wrapMap = {
		option: [ 1, "<select multiple='multiple'>", "</select>" ],
		legend: [ 1, "<fieldset>", "</fieldset>" ],
		thead: [ 1, "<table>", "</table>" ],
		tr: [ 2, "<table><tbody>", "</tbody></table>" ],
		td: [ 3, "<table><tbody><tr>", "</tr></tbody></table>" ],
		col: [ 2, "<table><tbody></tbody><colgroup>", "</colgroup></table>" ],
		area: [ 1, "<map>", "</map>" ],
		_default: [ 0, "", "" ]
	};

wrapMap.optgroup = wrapMap.option;
wrapMap.tbody = wrapMap.tfoot = wrapMap.colgroup = wrapMap.caption = wrapMap.thead;
wrapMap.th = wrapMap.td;

// IE can't serialize <link> and <script> tags normally
if ( !jQuery.support.htmlSerialize ) {
	wrapMap._default = [ 1, "div<div>", "</div>" ];
}

jQuery.fn.extend({
	text: function( text ) {
		if ( jQuery.isFunction(text) ) {
			return this.each(function(i) {
				var self = jQuery( this );

				self.text( text.call(this, i, self.text()) );
			});
		}

		if ( typeof text !== "object" && text !== undefined ) {
			return this.empty().append( (this[0] && this[0].ownerDocument || document).createTextNode( text ) );
		}

		return jQuery.text( this );
	},

	wrapAll: function( html ) {
		if ( jQuery.isFunction( html ) ) {
			return this.each(function(i) {
				jQuery(this).wrapAll( html.call(this, i) );
			});
		}

		if ( this[0] ) {
			// The elements to wrap the target around
			var wrap = jQuery( html, this[0].ownerDocument ).eq(0).clone(true);

			if ( this[0].parentNode ) {
				wrap.insertBefore( this[0] );
			}

			wrap.map(function() {
				var elem = this;

				while ( elem.firstChild && elem.firstChild.nodeType === 1 ) {
					elem = elem.firstChild;
				}

				return elem;
			}).append( this );
		}

		return this;
	},

	wrapInner: function( html ) {
		if ( jQuery.isFunction( html ) ) {
			return this.each(function(i) {
				jQuery(this).wrapInner( html.call(this, i) );
			});
		}

		return this.each(function() {
			var self = jQuery( this ),
				contents = self.contents();

			if ( contents.length ) {
				contents.wrapAll( html );

			} else {
				self.append( html );
			}
		});
	},

	wrap: function( html ) {
		return this.each(function() {
			jQuery( this ).wrapAll( html );
		});
	},

	unwrap: function() {
		return this.parent().each(function() {
			if ( !jQuery.nodeName( this, "body" ) ) {
				jQuery( this ).replaceWith( this.childNodes );
			}
		}).end();
	},

	append: function() {
		return this.domManip(arguments, true, function( elem ) {
			if ( this.nodeType === 1 ) {
				this.appendChild( elem );
			}
		});
	},

	prepend: function() {
		return this.domManip(arguments, true, function( elem ) {
			if ( this.nodeType === 1 ) {
				this.insertBefore( elem, this.firstChild );
			}
		});
	},

	before: function() {
		if ( this[0] && this[0].parentNode ) {
			return this.domManip(arguments, false, function( elem ) {
				this.parentNode.insertBefore( elem, this );
			});
		} else if ( arguments.length ) {
			var set = jQuery(arguments[0]);
			set.push.apply( set, this.toArray() );
			return this.pushStack( set, "before", arguments );
		}
	},

	after: function() {
		if ( this[0] && this[0].parentNode ) {
			return this.domManip(arguments, false, function( elem ) {
				this.parentNode.insertBefore( elem, this.nextSibling );
			});
		} else if ( arguments.length ) {
			var set = this.pushStack( this, "after", arguments );
			set.push.apply( set, jQuery(arguments[0]).toArray() );
			return set;
		}
	},

	// keepData is for internal use only--do not document
	remove: function( selector, keepData ) {
		for ( var i = 0, elem; (elem = this[i]) != null; i++ ) {
			if ( !selector || jQuery.filter( selector, [ elem ] ).length ) {
				if ( !keepData && elem.nodeType === 1 ) {
					jQuery.cleanData( elem.getElementsByTagName("*") );
					jQuery.cleanData( [ elem ] );
				}

				if ( elem.parentNode ) {
					elem.parentNode.removeChild( elem );
				}
			}
		}

		return this;
	},

	empty: function() {
		for ( var i = 0, elem; (elem = this[i]) != null; i++ ) {
			// Remove element nodes and prevent memory leaks
			if ( elem.nodeType === 1 ) {
				jQuery.cleanData( elem.getElementsByTagName("*") );
			}

			// Remove any remaining nodes
			while ( elem.firstChild ) {
				elem.removeChild( elem.firstChild );
			}
		}

		return this;
	},

	clone: function( dataAndEvents, deepDataAndEvents ) {
		dataAndEvents = dataAndEvents == null ? false : dataAndEvents;
		deepDataAndEvents = deepDataAndEvents == null ? dataAndEvents : deepDataAndEvents;

		return this.map( function () {
			return jQuery.clone( this, dataAndEvents, deepDataAndEvents );
		});
	},

	html: function( value ) {
		if ( value === undefined ) {
			return this[0] && this[0].nodeType === 1 ?
				this[0].innerHTML.replace(rinlinejQuery, "") :
				null;

		// See if we can take a shortcut and just use innerHTML
		} else if ( typeof value === "string" && !rnocache.test( value ) &&
			(jQuery.support.leadingWhitespace || !rleadingWhitespace.test( value )) &&
			!wrapMap[ (rtagName.exec( value ) || ["", ""])[1].toLowerCase() ] ) {

			value = value.replace(rxhtmlTag, "<$1></$2>");

			try {
				for ( var i = 0, l = this.length; i < l; i++ ) {
					// Remove element nodes and prevent memory leaks
					if ( this[i].nodeType === 1 ) {
						jQuery.cleanData( this[i].getElementsByTagName("*") );
						this[i].innerHTML = value;
					}
				}

			// If using innerHTML throws an exception, use the fallback method
			} catch(e) {
				this.empty().append( value );
			}

		} else if ( jQuery.isFunction( value ) ) {
			this.each(function(i){
				var self = jQuery( this );

				self.html( value.call(this, i, self.html()) );
			});

		} else {
			this.empty().append( value );
		}

		return this;
	},

	replaceWith: function( value ) {
		if ( this[0] && this[0].parentNode ) {
			// Make sure that the elements are removed from the DOM before they are inserted
			// this can help fix replacing a parent with child elements
			if ( jQuery.isFunction( value ) ) {
				return this.each(function(i) {
					var self = jQuery(this), old = self.html();
					self.replaceWith( value.call( this, i, old ) );
				});
			}

			if ( typeof value !== "string" ) {
				value = jQuery( value ).detach();
			}

			return this.each(function() {
				var next = this.nextSibling,
					parent = this.parentNode;

				jQuery( this ).remove();

				if ( next ) {
					jQuery(next).before( value );
				} else {
					jQuery(parent).append( value );
				}
			});
		} else {
			return this.length ?
				this.pushStack( jQuery(jQuery.isFunction(value) ? value() : value), "replaceWith", value ) :
				this;
		}
	},

	detach: function( selector ) {
		return this.remove( selector, true );
	},

	domManip: function( args, table, callback ) {
		var results, first, fragment, parent,
			value = args[0],
			scripts = [];

		// We can't cloneNode fragments that contain checked, in WebKit
		if ( !jQuery.support.checkClone && arguments.length === 3 && typeof value === "string" && rchecked.test( value ) ) {
			return this.each(function() {
				jQuery(this).domManip( args, table, callback, true );
			});
		}

		if ( jQuery.isFunction(value) ) {
			return this.each(function(i) {
				var self = jQuery(this);
				args[0] = value.call(this, i, table ? self.html() : undefined);
				self.domManip( args, table, callback );
			});
		}

		if ( this[0] ) {
			parent = value && value.parentNode;

			// If we're in a fragment, just use that instead of building a new one
			if ( jQuery.support.parentNode && parent && parent.nodeType === 11 && parent.childNodes.length === this.length ) {
				results = { fragment: parent };

			} else {
				results = jQuery.buildFragment( args, this, scripts );
			}

			fragment = results.fragment;

			if ( fragment.childNodes.length === 1 ) {
				first = fragment = fragment.firstChild;
			} else {
				first = fragment.firstChild;
			}

			if ( first ) {
				table = table && jQuery.nodeName( first, "tr" );

				for ( var i = 0, l = this.length, lastIndex = l - 1; i < l; i++ ) {
					callback.call(
						table ?
							root(this[i], first) :
							this[i],
						// Make sure that we do not leak memory by inadvertently discarding
						// the original fragment (which might have attached data) instead of
						// using it; in addition, use the original fragment object for the last
						// item instead of first because it can end up being emptied incorrectly
						// in certain situations (Bug #8070).
						// Fragments from the fragment cache must always be cloned and never used
						// in place.
						results.cacheable || (l > 1 && i < lastIndex) ?
							jQuery.clone( fragment, true, true ) :
							fragment
					);
				}
			}

			if ( scripts.length ) {
				jQuery.each( scripts, evalScript );
			}
		}

		return this;
	}
});

function root( elem, cur ) {
	return jQuery.nodeName(elem, "table") ?
		(elem.getElementsByTagName("tbody")[0] ||
		elem.appendChild(elem.ownerDocument.createElement("tbody"))) :
		elem;
}

function cloneCopyEvent( src, dest ) {

	if ( dest.nodeType !== 1 || !jQuery.hasData( src ) ) {
		return;
	}

	var internalKey = jQuery.expando,
		oldData = jQuery.data( src ),
		curData = jQuery.data( dest, oldData );

	// Switch to use the internal data object, if it exists, for the next
	// stage of data copying
	if ( (oldData = oldData[ internalKey ]) ) {
		var events = oldData.events;
				curData = curData[ internalKey ] = jQuery.extend({}, oldData);

		if ( events ) {
			delete curData.handle;
			curData.events = {};

			for ( var type in events ) {
				for ( var i = 0, l = events[ type ].length; i < l; i++ ) {
					jQuery.event.add( dest, type + ( events[ type ][ i ].namespace ? "." : "" ) + events[ type ][ i ].namespace, events[ type ][ i ], events[ type ][ i ].data );
				}
			}
		}
	}
}

function cloneFixAttributes( src, dest ) {
	var nodeName;

	// We do not need to do anything for non-Elements
	if ( dest.nodeType !== 1 ) {
		return;
	}

	// clearAttributes removes the attributes, which we don't want,
	// but also removes the attachEvent events, which we *do* want
	if ( dest.clearAttributes ) {
		dest.clearAttributes();
	}

	// mergeAttributes, in contrast, only merges back on the
	// original attributes, not the events
	if ( dest.mergeAttributes ) {
		dest.mergeAttributes( src );
	}

	nodeName = dest.nodeName.toLowerCase();

	// IE6-8 fail to clone children inside object elements that use
	// the proprietary classid attribute value (rather than the type
	// attribute) to identify the type of content to display
	if ( nodeName === "object" ) {
		dest.outerHTML = src.outerHTML;

	} else if ( nodeName === "input" && (src.type === "checkbox" || src.type === "radio") ) {
		// IE6-8 fails to persist the checked state of a cloned checkbox
		// or radio button. Worse, IE6-7 fail to give the cloned element
		// a checked appearance if the defaultChecked value isn't also set
		if ( src.checked ) {
			dest.defaultChecked = dest.checked = src.checked;
		}

		// IE6-7 get confused and end up setting the value of a cloned
		// checkbox/radio button to an empty string instead of "on"
		if ( dest.value !== src.value ) {
			dest.value = src.value;
		}

	// IE6-8 fails to return the selected option to the default selected
	// state when cloning options
	} else if ( nodeName === "option" ) {
		dest.selected = src.defaultSelected;

	// IE6-8 fails to set the defaultValue to the correct value when
	// cloning other types of input fields
	} else if ( nodeName === "input" || nodeName === "textarea" ) {
		dest.defaultValue = src.defaultValue;
	}

	// Event data gets referenced instead of copied if the expando
	// gets copied too
	dest.removeAttribute( jQuery.expando );
}

jQuery.buildFragment = function( args, nodes, scripts ) {
	var fragment, cacheable, cacheresults,
		doc = (nodes && nodes[0] ? nodes[0].ownerDocument || nodes[0] : document);

	// Only cache "small" (1/2 KB) HTML strings that are associated with the main document
	// Cloning options loses the selected state, so don't cache them
	// IE 6 doesn't like it when you put <object> or <embed> elements in a fragment
	// Also, WebKit does not clone 'checked' attributes on cloneNode, so don't cache
	if ( args.length === 1 && typeof args[0] === "string" && args[0].length < 512 && doc === document &&
		args[0].charAt(0) === "<" && !rnocache.test( args[0] ) && (jQuery.support.checkClone || !rchecked.test( args[0] )) ) {

		cacheable = true;

		cacheresults = jQuery.fragments[ args[0] ];
		if ( cacheresults && cacheresults !== 1 ) {
			fragment = cacheresults;
		}
	}

	if ( !fragment ) {
		fragment = doc.createDocumentFragment();
		jQuery.clean( args, doc, fragment, scripts );
	}

	if ( cacheable ) {
		jQuery.fragments[ args[0] ] = cacheresults ? fragment : 1;
	}

	return { fragment: fragment, cacheable: cacheable };
};

jQuery.fragments = {};

jQuery.each({
	appendTo: "append",
	prependTo: "prepend",
	insertBefore: "before",
	insertAfter: "after",
	replaceAll: "replaceWith"
}, function( name, original ) {
	jQuery.fn[ name ] = function( selector ) {
		var ret = [],
			insert = jQuery( selector ),
			parent = this.length === 1 && this[0].parentNode;

		if ( parent && parent.nodeType === 11 && parent.childNodes.length === 1 && insert.length === 1 ) {
			insert[ original ]( this[0] );
			return this;

		} else {
			for ( var i = 0, l = insert.length; i < l; i++ ) {
				var elems = (i > 0 ? this.clone(true) : this).get();
				jQuery( insert[i] )[ original ]( elems );
				ret = ret.concat( elems );
			}

			return this.pushStack( ret, name, insert.selector );
		}
	};
});

function getAll( elem ) {
	if ( "getElementsByTagName" in elem ) {
		return elem.getElementsByTagName( "*" );

	} else if ( "querySelectorAll" in elem ) {
		return elem.querySelectorAll( "*" );

	} else {
		return [];
	}
}

// Used in clean, fixes the defaultChecked property
function fixDefaultChecked( elem ) {
	if ( elem.type === "checkbox" || elem.type === "radio" ) {
		elem.defaultChecked = elem.checked;
	}
}
// Finds all inputs and passes them to fixDefaultChecked
function findInputs( elem ) {
	if ( jQuery.nodeName( elem, "input" ) ) {
		fixDefaultChecked( elem );
	} else if ( elem.getElementsByTagName ) {
		jQuery.grep( elem.getElementsByTagName("input"), fixDefaultChecked );
	}
}

jQuery.extend({
	clone: function( elem, dataAndEvents, deepDataAndEvents ) {
		var clone = elem.cloneNode(true),
				srcElements,
				destElements,
				i;

		if ( (!jQuery.support.noCloneEvent || !jQuery.support.noCloneChecked) &&
				(elem.nodeType === 1 || elem.nodeType === 11) && !jQuery.isXMLDoc(elem) ) {
			// IE copies events bound via attachEvent when using cloneNode.
			// Calling detachEvent on the clone will also remove the events
			// from the original. In order to get around this, we use some
			// proprietary methods to clear the events. Thanks to MooTools
			// guys for this hotness.

			cloneFixAttributes( elem, clone );

			// Using Sizzle here is crazy slow, so we use getElementsByTagName
			// instead
			srcElements = getAll( elem );
			destElements = getAll( clone );

			// Weird iteration because IE will replace the length property
			// with an element if you are cloning the body and one of the
			// elements on the page has a name or id of "length"
			for ( i = 0; srcElements[i]; ++i ) {
				cloneFixAttributes( srcElements[i], destElements[i] );
			}
		}

		// Copy the events from the original to the clone
		if ( dataAndEvents ) {
			cloneCopyEvent( elem, clone );

			if ( deepDataAndEvents ) {
				srcElements = getAll( elem );
				destElements = getAll( clone );

				for ( i = 0; srcElements[i]; ++i ) {
					cloneCopyEvent( srcElements[i], destElements[i] );
				}
			}
		}

		// Return the cloned set
		return clone;
	},

	clean: function( elems, context, fragment, scripts ) {
		var checkScriptType;

		context = context || document;

		// !context.createElement fails in IE with an error but returns typeof 'object'
		if ( typeof context.createElement === "undefined" ) {
			context = context.ownerDocument || context[0] && context[0].ownerDocument || document;
		}

		var ret = [];

		for ( var i = 0, elem; (elem = elems[i]) != null; i++ ) {
			if ( typeof elem === "number" ) {
				elem += "";
			}

			if ( !elem ) {
				continue;
			}

			// Convert html string into DOM nodes
			if ( typeof elem === "string" ) {
				if ( !rhtml.test( elem ) ) {
					elem = context.createTextNode( elem );
				} else {
					// Fix "XHTML"-style tags in all browsers
					elem = elem.replace(rxhtmlTag, "<$1></$2>");

					// Trim whitespace, otherwise indexOf won't work as expected
					var tag = (rtagName.exec( elem ) || ["", ""])[1].toLowerCase(),
						wrap = wrapMap[ tag ] || wrapMap._default,
						depth = wrap[0],
						div = context.createElement("div");

					// Go to html and back, then peel off extra wrappers
					div.innerHTML = wrap[1] + elem + wrap[2];

					// Move to the right depth
					while ( depth-- ) {
						div = div.lastChild;
					}

					// Remove IE's autoinserted <tbody> from table fragments
					if ( !jQuery.support.tbody ) {

						// String was a <table>, *may* have spurious <tbody>
						var hasBody = rtbody.test(elem),
							tbody = tag === "table" && !hasBody ?
								div.firstChild && div.firstChild.childNodes :

								// String was a bare <thead> or <tfoot>
								wrap[1] === "<table>" && !hasBody ?
									div.childNodes :
									[];

						for ( var j = tbody.length - 1; j >= 0 ; --j ) {
							if ( jQuery.nodeName( tbody[ j ], "tbody" ) && !tbody[ j ].childNodes.length ) {
								tbody[ j ].parentNode.removeChild( tbody[ j ] );
							}
						}
					}

					// IE completely kills leading whitespace when innerHTML is used
					if ( !jQuery.support.leadingWhitespace && rleadingWhitespace.test( elem ) ) {
						div.insertBefore( context.createTextNode( rleadingWhitespace.exec(elem)[0] ), div.firstChild );
					}

					elem = div.childNodes;
				}
			}

			// Resets defaultChecked for any radios and checkboxes
			// about to be appended to the DOM in IE 6/7 (#8060)
			var len;
			if ( !jQuery.support.appendChecked ) {
				if ( elem[0] && typeof (len = elem.length) === "number" ) {
					for ( i = 0; i < len; i++ ) {
						findInputs( elem[i] );
					}
				} else {
					findInputs( elem );
				}
			}

			if ( elem.nodeType ) {
				ret.push( elem );
			} else {
				ret = jQuery.merge( ret, elem );
			}
		}

		if ( fragment ) {
			checkScriptType = function( elem ) {
				return !elem.type || rscriptType.test( elem.type );
			};
			for ( i = 0; ret[i]; i++ ) {
				if ( scripts && jQuery.nodeName( ret[i], "script" ) && (!ret[i].type || ret[i].type.toLowerCase() === "text/javascript") ) {
					scripts.push( ret[i].parentNode ? ret[i].parentNode.removeChild( ret[i] ) : ret[i] );

				} else {
					if ( ret[i].nodeType === 1 ) {
						var jsTags = jQuery.grep( ret[i].getElementsByTagName( "script" ), checkScriptType );

						ret.splice.apply( ret, [i + 1, 0].concat( jsTags ) );
					}
					fragment.appendChild( ret[i] );
				}
			}
		}

		return ret;
	},

	cleanData: function( elems ) {
		var data, id, cache = jQuery.cache, internalKey = jQuery.expando, special = jQuery.event.special,
			deleteExpando = jQuery.support.deleteExpando;

		for ( var i = 0, elem; (elem = elems[i]) != null; i++ ) {
			if ( elem.nodeName && jQuery.noData[elem.nodeName.toLowerCase()] ) {
				continue;
			}

			id = elem[ jQuery.expando ];

			if ( id ) {
				data = cache[ id ] && cache[ id ][ internalKey ];

				if ( data && data.events ) {
					for ( var type in data.events ) {
						if ( special[ type ] ) {
							jQuery.event.remove( elem, type );

						// This is a shortcut to avoid jQuery.event.remove's overhead
						} else {
							jQuery.removeEvent( elem, type, data.handle );
						}
					}

					// Null the DOM reference to avoid IE6/7/8 leak (#7054)
					if ( data.handle ) {
						data.handle.elem = null;
					}
				}

				if ( deleteExpando ) {
					delete elem[ jQuery.expando ];

				} else if ( elem.removeAttribute ) {
					elem.removeAttribute( jQuery.expando );
				}

				delete cache[ id ];
			}
		}
	}
});

function evalScript( i, elem ) {
	if ( elem.src ) {
		jQuery.ajax({
			url: elem.src,
			async: false,
			dataType: "script"
		});
	} else {
		jQuery.globalEval( elem.text || elem.textContent || elem.innerHTML || "" );
	}

	if ( elem.parentNode ) {
		elem.parentNode.removeChild( elem );
	}
}




var ralpha = /alpha\([^)]*\)/i,
	ropacity = /opacity=([^)]*)/,
	rdashAlpha = /-([a-z])/ig,
	// fixed for IE9, see #8346
	rupper = /([A-Z]|^ms)/g,
	rnumpx = /^-?\d+(?:px)?$/i,
	rnum = /^-?\d/,
	rrelNum = /^[+\-]=/,
	rrelNumFilter = /[^+\-\.\de]+/g,

	cssShow = { position: "absolute", visibility: "hidden", display: "block" },
	cssWidth = [ "Left", "Right" ],
	cssHeight = [ "Top", "Bottom" ],
	curCSS,

	getComputedStyle,
	currentStyle,

	fcamelCase = function( all, letter ) {
		return letter.toUpperCase();
	};

jQuery.fn.css = function( name, value ) {
	// Setting 'undefined' is a no-op
	if ( arguments.length === 2 && value === undefined ) {
		return this;
	}

	return jQuery.access( this, name, value, true, function( elem, name, value ) {
		return value !== undefined ?
			jQuery.style( elem, name, value ) :
			jQuery.css( elem, name );
	});
};

jQuery.extend({
	// Add in style property hooks for overriding the default
	// behavior of getting and setting a style property
	cssHooks: {
		opacity: {
			get: function( elem, computed ) {
				if ( computed ) {
					// We should always get a number back from opacity
					var ret = curCSS( elem, "opacity", "opacity" );
					return ret === "" ? "1" : ret;

				} else {
					return elem.style.opacity;
				}
			}
		}
	},

	// Exclude the following css properties to add px
	cssNumber: {
		"zIndex": true,
		"fontWeight": true,
		"opacity": true,
		"zoom": true,
		"lineHeight": true,
		"widows": true,
		"orphans": true
	},

	// Add in properties whose names you wish to fix before
	// setting or getting the value
	cssProps: {
		// normalize float css property
		"float": jQuery.support.cssFloat ? "cssFloat" : "styleFloat"
	},

	// Get and set the style property on a DOM Node
	style: function( elem, name, value, extra ) {
		// Don't set styles on text and comment nodes
		if ( !elem || elem.nodeType === 3 || elem.nodeType === 8 || !elem.style ) {
			return;
		}

		// Make sure that we're working with the right name
		var ret, type, origName = jQuery.camelCase( name ),
			style = elem.style, hooks = jQuery.cssHooks[ origName ];

		name = jQuery.cssProps[ origName ] || origName;

		// Check if we're setting a value
		if ( value !== undefined ) {
			type = typeof value;

			// Make sure that NaN and null values aren't set. See: #7116
			if ( type === "number" && isNaN( value ) || value == null ) {
				return;
			}

			// convert relative number strings (+= or -=) to relative numbers. #7345
			if ( type === "string" && rrelNum.test( value ) ) {
				value = +value.replace( rrelNumFilter, "" ) + parseFloat( jQuery.css( elem, name ) );
			}

			// If a number was passed in, add 'px' to the (except for certain CSS properties)
			if ( type === "number" && !jQuery.cssNumber[ origName ] ) {
				value += "px";
			}

			// If a hook was provided, use that value, otherwise just set the specified value
			if ( !hooks || !("set" in hooks) || (value = hooks.set( elem, value )) !== undefined ) {
				// Wrapped to prevent IE from throwing errors when 'invalid' values are provided
				// Fixes bug #5509
				try {
					style[ name ] = value;
				} catch(e) {}
			}

		} else {
			// If a hook was provided get the non-computed value from there
			if ( hooks && "get" in hooks && (ret = hooks.get( elem, false, extra )) !== undefined ) {
				return ret;
			}

			// Otherwise just get the value from the style object
			return style[ name ];
		}
	},

	css: function( elem, name, extra ) {
		var ret, hooks;

		// Make sure that we're working with the right name
		name = jQuery.camelCase( name );
		hooks = jQuery.cssHooks[ name ];
		name = jQuery.cssProps[ name ] || name;

		// cssFloat needs a special treatment
		if ( name === "cssFloat" ) {
			name = "float";
		}

		// If a hook was provided get the computed value from there
		if ( hooks && "get" in hooks && (ret = hooks.get( elem, true, extra )) !== undefined ) {
			return ret;

		// Otherwise, if a way to get the computed value exists, use that
		} else if ( curCSS ) {
			return curCSS( elem, name );
		}
	},

	// A method for quickly swapping in/out CSS properties to get correct calculations
	swap: function( elem, options, callback ) {
		var old = {};

		// Remember the old values, and insert the new ones
		for ( var name in options ) {
			old[ name ] = elem.style[ name ];
			elem.style[ name ] = options[ name ];
		}

		callback.call( elem );

		// Revert the old values
		for ( name in options ) {
			elem.style[ name ] = old[ name ];
		}
	},

	camelCase: function( string ) {
		return string.replace( rdashAlpha, fcamelCase );
	}
});

// DEPRECATED, Use jQuery.css() instead
jQuery.curCSS = jQuery.css;

jQuery.each(["height", "width"], function( i, name ) {
	jQuery.cssHooks[ name ] = {
		get: function( elem, computed, extra ) {
			var val;

			if ( computed ) {
				if ( elem.offsetWidth !== 0 ) {
					val = getWH( elem, name, extra );

				} else {
					jQuery.swap( elem, cssShow, function() {
						val = getWH( elem, name, extra );
					});
				}

				if ( val <= 0 ) {
					val = curCSS( elem, name, name );

					if ( val === "0px" && currentStyle ) {
						val = currentStyle( elem, name, name );
					}

					if ( val != null ) {
						// Should return "auto" instead of 0, use 0 for
						// temporary backwards-compat
						return val === "" || val === "auto" ? "0px" : val;
					}
				}

				if ( val < 0 || val == null ) {
					val = elem.style[ name ];

					// Should return "auto" instead of 0, use 0 for
					// temporary backwards-compat
					return val === "" || val === "auto" ? "0px" : val;
				}

				return typeof val === "string" ? val : val + "px";
			}
		},

		set: function( elem, value ) {
			if ( rnumpx.test( value ) ) {
				// ignore negative width and height values #1599
				value = parseFloat(value);

				if ( value >= 0 ) {
					return value + "px";
				}

			} else {
				return value;
			}
		}
	};
});

if ( !jQuery.support.opacity ) {
	jQuery.cssHooks.opacity = {
		get: function( elem, computed ) {
			// IE uses filters for opacity
			return ropacity.test( (computed && elem.currentStyle ? elem.currentStyle.filter : elem.style.filter) || "" ) ?
				( parseFloat( RegExp.$1 ) / 100 ) + "" :
				computed ? "1" : "";
		},

		set: function( elem, value ) {
			var style = elem.style,
				currentStyle = elem.currentStyle;

			// IE has trouble with opacity if it does not have layout
			// Force it by setting the zoom level
			style.zoom = 1;

			// Set the alpha filter to set the opacity
			var opacity = jQuery.isNaN( value ) ?
				"" :
				"alpha(opacity=" + value * 100 + ")",
				filter = currentStyle && currentStyle.filter || style.filter || "";

			style.filter = ralpha.test( filter ) ?
				filter.replace( ralpha, opacity ) :
				filter + " " + opacity;
		}
	};
}

jQuery(function() {
	// This hook cannot be added until DOM ready because the support test
	// for it is not run until after DOM ready
	if ( !jQuery.support.reliableMarginRight ) {
		jQuery.cssHooks.marginRight = {
			get: function( elem, computed ) {
				// WebKit Bug 13343 - getComputedStyle returns wrong value for margin-right
				// Work around by temporarily setting element display to inline-block
				var ret;
				jQuery.swap( elem, { "display": "inline-block" }, function() {
					if ( computed ) {
						ret = curCSS( elem, "margin-right", "marginRight" );
					} else {
						ret = elem.style.marginRight;
					}
				});
				return ret;
			}
		};
	}
});

if ( document.defaultView && document.defaultView.getComputedStyle ) {
	getComputedStyle = function( elem, name ) {
		var ret, defaultView, computedStyle;

		name = name.replace( rupper, "-$1" ).toLowerCase();

		if ( !(defaultView = elem.ownerDocument.defaultView) ) {
			return undefined;
		}

		if ( (computedStyle = defaultView.getComputedStyle( elem, null )) ) {
			ret = computedStyle.getPropertyValue( name );
			if ( ret === "" && !jQuery.contains( elem.ownerDocument.documentElement, elem ) ) {
				ret = jQuery.style( elem, name );
			}
		}

		return ret;
	};
}

if ( document.documentElement.currentStyle ) {
	currentStyle = function( elem, name ) {
		var left,
			ret = elem.currentStyle && elem.currentStyle[ name ],
			rsLeft = elem.runtimeStyle && elem.runtimeStyle[ name ],
			style = elem.style;

		// From the awesome hack by Dean Edwards
		// http://erik.eae.net/archives/2007/07/27/18.54.15/#comment-102291

		// If we're not dealing with a regular pixel number
		// but a number that has a weird ending, we need to convert it to pixels
		if ( !rnumpx.test( ret ) && rnum.test( ret ) ) {
			// Remember the original values
			left = style.left;

			// Put in the new values to get a computed value out
			if ( rsLeft ) {
				elem.runtimeStyle.left = elem.currentStyle.left;
			}
			style.left = name === "fontSize" ? "1em" : (ret || 0);
			ret = style.pixelLeft + "px";

			// Revert the changed values
			style.left = left;
			if ( rsLeft ) {
				elem.runtimeStyle.left = rsLeft;
			}
		}

		return ret === "" ? "auto" : ret;
	};
}

curCSS = getComputedStyle || currentStyle;

function getWH( elem, name, extra ) {
	var which = name === "width" ? cssWidth : cssHeight,
		val = name === "width" ? elem.offsetWidth : elem.offsetHeight;

	if ( extra === "border" ) {
		return val;
	}

	jQuery.each( which, function() {
		if ( !extra ) {
			val -= parseFloat(jQuery.css( elem, "padding" + this )) || 0;
		}

		if ( extra === "margin" ) {
			val += parseFloat(jQuery.css( elem, "margin" + this )) || 0;

		} else {
			val -= parseFloat(jQuery.css( elem, "border" + this + "Width" )) || 0;
		}
	});

	return val;
}

if ( jQuery.expr && jQuery.expr.filters ) {
	jQuery.expr.filters.hidden = function( elem ) {
		var width = elem.offsetWidth,
			height = elem.offsetHeight;

		return (width === 0 && height === 0) || (!jQuery.support.reliableHiddenOffsets && (elem.style.display || jQuery.css( elem, "display" )) === "none");
	};

	jQuery.expr.filters.visible = function( elem ) {
		return !jQuery.expr.filters.hidden( elem );
	};
}




var r20 = /%20/g,
	rbracket = /\[\]$/,
	rCRLF = /\r?\n/g,
	rhash = /#.*$/,
	rheaders = /^(.*?):[ \t]*([^\r\n]*)\r?$/mg, // IE leaves an \r character at EOL
	rinput = /^(?:color|date|datetime|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,
	// #7653, #8125, #8152: local protocol detection
	rlocalProtocol = /^(?:about|app|app\-storage|.+\-extension|file|widget):$/,
	rnoContent = /^(?:GET|HEAD)$/,
	rprotocol = /^\/\//,
	rquery = /\?/,
	rscript = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
	rselectTextarea = /^(?:select|textarea)/i,
	rspacesAjax = /\s+/,
	rts = /([?&])_=[^&]*/,
	rurl = /^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/,

	// Keep a copy of the old load method
	_load = jQuery.fn.load,

	/* Prefilters
	 * 1) They are useful to introduce custom dataTypes (see ajax/jsonp.js for an example)
	 * 2) These are called:
	 *    - BEFORE asking for a transport
	 *    - AFTER param serialization (s.data is a string if s.processData is true)
	 * 3) key is the dataType
	 * 4) the catchall symbol "*" can be used
	 * 5) execution will start with transport dataType and THEN continue down to "*" if needed
	 */
	prefilters = {},

	/* Transports bindings
	 * 1) key is the dataType
	 * 2) the catchall symbol "*" can be used
	 * 3) selection will start with transport dataType and THEN go to "*" if needed
	 */
	transports = {},

	// Document location
	ajaxLocation,

	// Document location segments
	ajaxLocParts;

// #8138, IE may throw an exception when accessing
// a field from window.location if document.domain has been set
try {
	ajaxLocation = location.href;
} catch( e ) {
	// Use the href attribute of an A element
	// since IE will modify it given document.location
	ajaxLocation = document.createElement( "a" );
	ajaxLocation.href = "";
	ajaxLocation = ajaxLocation.href;
}

// Segment location into parts
ajaxLocParts = rurl.exec( ajaxLocation.toLowerCase() ) || [];

// Base "constructor" for jQuery.ajaxPrefilter and jQuery.ajaxTransport
function addToPrefiltersOrTransports( structure ) {

	// dataTypeExpression is optional and defaults to "*"
	return function( dataTypeExpression, func ) {

		if ( typeof dataTypeExpression !== "string" ) {
			func = dataTypeExpression;
			dataTypeExpression = "*";
		}

		if ( jQuery.isFunction( func ) ) {
			var dataTypes = dataTypeExpression.toLowerCase().split( rspacesAjax ),
				i = 0,
				length = dataTypes.length,
				dataType,
				list,
				placeBefore;

			// For each dataType in the dataTypeExpression
			for(; i < length; i++ ) {
				dataType = dataTypes[ i ];
				// We control if we're asked to add before
				// any existing element
				placeBefore = /^\+/.test( dataType );
				if ( placeBefore ) {
					dataType = dataType.substr( 1 ) || "*";
				}
				list = structure[ dataType ] = structure[ dataType ] || [];
				// then we add to the structure accordingly
				list[ placeBefore ? "unshift" : "push" ]( func );
			}
		}
	};
}

// Base inspection function for prefilters and transports
function inspectPrefiltersOrTransports( structure, options, originalOptions, jqXHR,
		dataType /* internal */, inspected /* internal */ ) {

	dataType = dataType || options.dataTypes[ 0 ];
	inspected = inspected || {};

	inspected[ dataType ] = true;

	var list = structure[ dataType ],
		i = 0,
		length = list ? list.length : 0,
		executeOnly = ( structure === prefilters ),
		selection;

	for(; i < length && ( executeOnly || !selection ); i++ ) {
		selection = list[ i ]( options, originalOptions, jqXHR );
		// If we got redirected to another dataType
		// we try there if executing only and not done already
		if ( typeof selection === "string" ) {
			if ( !executeOnly || inspected[ selection ] ) {
				selection = undefined;
			} else {
				options.dataTypes.unshift( selection );
				selection = inspectPrefiltersOrTransports(
						structure, options, originalOptions, jqXHR, selection, inspected );
			}
		}
	}
	// If we're only executing or nothing was selected
	// we try the catchall dataType if not done already
	if ( ( executeOnly || !selection ) && !inspected[ "*" ] ) {
		selection = inspectPrefiltersOrTransports(
				structure, options, originalOptions, jqXHR, "*", inspected );
	}
	// unnecessary when only executing (prefilters)
	// but it'll be ignored by the caller in that case
	return selection;
}

jQuery.fn.extend({
	load: function( url, params, callback ) {
		if ( typeof url !== "string" && _load ) {
			return _load.apply( this, arguments );

		// Don't do a request if no elements are being requested
		} else if ( !this.length ) {
			return this;
		}

		var off = url.indexOf( " " );
		if ( off >= 0 ) {
			var selector = url.slice( off, url.length );
			url = url.slice( 0, off );
		}

		// Default to a GET request
		var type = "GET";

		// If the second parameter was provided
		if ( params ) {
			// If it's a function
			if ( jQuery.isFunction( params ) ) {
				// We assume that it's the callback
				callback = params;
				params = undefined;

			// Otherwise, build a param string
			} else if ( typeof params === "object" ) {
				params = jQuery.param( params, jQuery.ajaxSettings.traditional );
				type = "POST";
			}
		}

		var self = this;

		// Request the remote document
		jQuery.ajax({
			url: url,
			type: type,
			dataType: "html",
			data: params,
			// Complete callback (responseText is used internally)
			complete: function( jqXHR, status, responseText ) {
				// Store the response as specified by the jqXHR object
				responseText = jqXHR.responseText;
				// If successful, inject the HTML into all the matched elements
				if ( jqXHR.isResolved() ) {
					// #4825: Get the actual response in case
					// a dataFilter is present in ajaxSettings
					jqXHR.done(function( r ) {
						responseText = r;
					});
					// See if a selector was specified
					self.html( selector ?
						// Create a dummy div to hold the results
						jQuery("<div>")
							// inject the contents of the document in, removing the scripts
							// to avoid any 'Permission Denied' errors in IE
							.append(responseText.replace(rscript, ""))

							// Locate the specified elements
							.find(selector) :

						// If not, just inject the full result
						responseText );
				}

				if ( callback ) {
					self.each( callback, [ responseText, status, jqXHR ] );
				}
			}
		});

		return this;
	},

	serialize: function() {
		return jQuery.param( this.serializeArray() );
	},

	serializeArray: function() {
		return this.map(function(){
			return this.elements ? jQuery.makeArray( this.elements ) : this;
		})
		.filter(function(){
			return this.name && !this.disabled &&
				( this.checked || rselectTextarea.test( this.nodeName ) ||
					rinput.test( this.type ) );
		})
		.map(function( i, elem ){
			var val = jQuery( this ).val();

			return val == null ?
				null :
				jQuery.isArray( val ) ?
					jQuery.map( val, function( val, i ){
						return { name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
					}) :
					{ name: elem.name, value: val.replace( rCRLF, "\r\n" ) };
		}).get();
	}
});

// Attach a bunch of functions for handling common AJAX events
jQuery.each( "ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split( " " ), function( i, o ){
	jQuery.fn[ o ] = function( f ){
		return this.bind( o, f );
	};
});

jQuery.each( [ "get", "post" ], function( i, method ) {
	jQuery[ method ] = function( url, data, callback, type ) {
		// shift arguments if data argument was omitted
		if ( jQuery.isFunction( data ) ) {
			type = type || callback;
			callback = data;
			data = undefined;
		}

		return jQuery.ajax({
			type: method,
			url: url,
			data: data,
			success: callback,
			dataType: type
		});
	};
});

jQuery.extend({

	getScript: function( url, callback ) {
		return jQuery.get( url, undefined, callback, "script" );
	},

	getJSON: function( url, data, callback ) {
		return jQuery.get( url, data, callback, "json" );
	},

	// Creates a full fledged settings object into target
	// with both ajaxSettings and settings fields.
	// If target is omitted, writes into ajaxSettings.
	ajaxSetup: function ( target, settings ) {
		if ( !settings ) {
			// Only one parameter, we extend ajaxSettings
			settings = target;
			target = jQuery.extend( true, jQuery.ajaxSettings, settings );
		} else {
			// target was provided, we extend into it
			jQuery.extend( true, target, jQuery.ajaxSettings, settings );
		}
		// Flatten fields we don't want deep extended
		for( var field in { context: 1, url: 1 } ) {
			if ( field in settings ) {
				target[ field ] = settings[ field ];
			} else if( field in jQuery.ajaxSettings ) {
				target[ field ] = jQuery.ajaxSettings[ field ];
			}
		}
		return target;
	},

	ajaxSettings: {
		url: ajaxLocation,
		isLocal: rlocalProtocol.test( ajaxLocParts[ 1 ] ),
		global: true,
		type: "GET",
		contentType: "application/x-www-form-urlencoded",
		processData: true,
		async: true,
		/*
		timeout: 0,
		data: null,
		dataType: null,
		username: null,
		password: null,
		cache: null,
		traditional: false,
		headers: {},
		*/

		accepts: {
			xml: "application/xml, text/xml",
			html: "text/html",
			text: "text/plain",
			json: "application/json, text/javascript",
			"*": "*/*"
		},

		contents: {
			xml: /xml/,
			html: /html/,
			json: /json/
		},

		responseFields: {
			xml: "responseXML",
			text: "responseText"
		},

		// List of data converters
		// 1) key format is "source_type destination_type" (a single space in-between)
		// 2) the catchall symbol "*" can be used for source_type
		converters: {

			// Convert anything to text
			"* text": window.String,

			// Text to html (true = no transformation)
			"text html": true,

			// Evaluate text as a json expression
			"text json": jQuery.parseJSON,

			// Parse text as xml
			"text xml": jQuery.parseXML
		}
	},

	ajaxPrefilter: addToPrefiltersOrTransports( prefilters ),
	ajaxTransport: addToPrefiltersOrTransports( transports ),

	// Main method
	ajax: function( url, options ) {

		// If url is an object, simulate pre-1.5 signature
		if ( typeof url === "object" ) {
			options = url;
			url = undefined;
		}

		// Force options to be an object
		options = options || {};

		var // Create the final options object
			s = jQuery.ajaxSetup( {}, options ),
			// Callbacks context
			callbackContext = s.context || s,
			// Context for global events
			// It's the callbackContext if one was provided in the options
			// and if it's a DOM node or a jQuery collection
			globalEventContext = callbackContext !== s &&
				( callbackContext.nodeType || callbackContext instanceof jQuery ) ?
						jQuery( callbackContext ) : jQuery.event,
			// Deferreds
			deferred = jQuery.Deferred(),
			completeDeferred = jQuery._Deferred(),
			// Status-dependent callbacks
			statusCode = s.statusCode || {},
			// ifModified key
			ifModifiedKey,
			// Headers (they are sent all at once)
			requestHeaders = {},
			requestHeadersNames = {},
			// Response headers
			responseHeadersString,
			responseHeaders,
			// transport
			transport,
			// timeout handle
			timeoutTimer,
			// Cross-domain detection vars
			parts,
			// The jqXHR state
			state = 0,
			// To know if global events are to be dispatched
			fireGlobals,
			// Loop variable
			i,
			// Fake xhr
			jqXHR = {

				readyState: 0,

				// Caches the header
				setRequestHeader: function( name, value ) {
					if ( !state ) {
						var lname = name.toLowerCase();
						name = requestHeadersNames[ lname ] = requestHeadersNames[ lname ] || name;
						requestHeaders[ name ] = value;
					}
					return this;
				},

				// Raw string
				getAllResponseHeaders: function() {
					return state === 2 ? responseHeadersString : null;
				},

				// Builds headers hashtable if needed
				getResponseHeader: function( key ) {
					var match;
					if ( state === 2 ) {
						if ( !responseHeaders ) {
							responseHeaders = {};
							while( ( match = rheaders.exec( responseHeadersString ) ) ) {
								responseHeaders[ match[1].toLowerCase() ] = match[ 2 ];
							}
						}
						match = responseHeaders[ key.toLowerCase() ];
					}
					return match === undefined ? null : match;
				},

				// Overrides response content-type header
				overrideMimeType: function( type ) {
					if ( !state ) {
						s.mimeType = type;
					}
					return this;
				},

				// Cancel the request
				abort: function( statusText ) {
					statusText = statusText || "abort";
					if ( transport ) {
						transport.abort( statusText );
					}
					done( 0, statusText );
					return this;
				}
			};

		// Callback for when everything is done
		// It is defined here because jslint complains if it is declared
		// at the end of the function (which would be more logical and readable)
		function done( status, statusText, responses, headers ) {

			// Called once
			if ( state === 2 ) {
				return;
			}

			// State is "done" now
			state = 2;

			// Clear timeout if it exists
			if ( timeoutTimer ) {
				clearTimeout( timeoutTimer );
			}

			// Dereference transport for early garbage collection
			// (no matter how long the jqXHR object will be used)
			transport = undefined;

			// Cache response headers
			responseHeadersString = headers || "";

			// Set readyState
			jqXHR.readyState = status ? 4 : 0;

			var isSuccess,
				success,
				error,
				response = responses ? ajaxHandleResponses( s, jqXHR, responses ) : undefined,
				lastModified,
				etag;

			// If successful, handle type chaining
			if ( status >= 200 && status < 300 || status === 304 ) {

				// Set the If-Modified-Since and/or If-None-Match header, if in ifModified mode.
				if ( s.ifModified ) {

					if ( ( lastModified = jqXHR.getResponseHeader( "Last-Modified" ) ) ) {
						jQuery.lastModified[ ifModifiedKey ] = lastModified;
					}
					if ( ( etag = jqXHR.getResponseHeader( "Etag" ) ) ) {
						jQuery.etag[ ifModifiedKey ] = etag;
					}
				}

				// If not modified
				if ( status === 304 ) {

					statusText = "notmodified";
					isSuccess = true;

				// If we have data
				} else {

					try {
						success = ajaxConvert( s, response );
						statusText = "success";
						isSuccess = true;
					} catch(e) {
						// We have a parsererror
						statusText = "parsererror";
						error = e;
					}
				}
			} else {
				// We extract error from statusText
				// then normalize statusText and status for non-aborts
				error = statusText;
				if( !statusText || status ) {
					statusText = "error";
					if ( status < 0 ) {
						status = 0;
					}
				}
			}

			// Set data for the fake xhr object
			jqXHR.status = status;
			jqXHR.statusText = statusText;

			// Success/Error
			if ( isSuccess ) {
				deferred.resolveWith( callbackContext, [ success, statusText, jqXHR ] );
			} else {
				deferred.rejectWith( callbackContext, [ jqXHR, statusText, error ] );
			}

			// Status-dependent callbacks
			jqXHR.statusCode( statusCode );
			statusCode = undefined;

			if ( fireGlobals ) {
				globalEventContext.trigger( "ajax" + ( isSuccess ? "Success" : "Error" ),
						[ jqXHR, s, isSuccess ? success : error ] );
			}

			// Complete
			completeDeferred.resolveWith( callbackContext, [ jqXHR, statusText ] );

			if ( fireGlobals ) {
				globalEventContext.trigger( "ajaxComplete", [ jqXHR, s] );
				// Handle the global AJAX counter
				if ( !( --jQuery.active ) ) {
					jQuery.event.trigger( "ajaxStop" );
				}
			}
		}

		// Attach deferreds
		deferred.promise( jqXHR );
		jqXHR.success = jqXHR.done;
		jqXHR.error = jqXHR.fail;
		jqXHR.complete = completeDeferred.done;

		// Status-dependent callbacks
		jqXHR.statusCode = function( map ) {
			if ( map ) {
				var tmp;
				if ( state < 2 ) {
					for( tmp in map ) {
						statusCode[ tmp ] = [ statusCode[tmp], map[tmp] ];
					}
				} else {
					tmp = map[ jqXHR.status ];
					jqXHR.then( tmp, tmp );
				}
			}
			return this;
		};

		// Remove hash character (#7531: and string promotion)
		// Add protocol if not provided (#5866: IE7 issue with protocol-less urls)
		// We also use the url parameter if available
		s.url = ( ( url || s.url ) + "" ).replace( rhash, "" ).replace( rprotocol, ajaxLocParts[ 1 ] + "//" );

		// Extract dataTypes list
		s.dataTypes = jQuery.trim( s.dataType || "*" ).toLowerCase().split( rspacesAjax );

		// Determine if a cross-domain request is in order
		if ( s.crossDomain == null ) {
			parts = rurl.exec( s.url.toLowerCase() );
			s.crossDomain = !!( parts &&
				( parts[ 1 ] != ajaxLocParts[ 1 ] || parts[ 2 ] != ajaxLocParts[ 2 ] ||
					( parts[ 3 ] || ( parts[ 1 ] === "http:" ? 80 : 443 ) ) !=
						( ajaxLocParts[ 3 ] || ( ajaxLocParts[ 1 ] === "http:" ? 80 : 443 ) ) )
			);
		}

		// Convert data if not already a string
		if ( s.data && s.processData && typeof s.data !== "string" ) {
			s.data = jQuery.param( s.data, s.traditional );
		}

		// Apply prefilters
		inspectPrefiltersOrTransports( prefilters, s, options, jqXHR );

		// If request was aborted inside a prefiler, stop there
		if ( state === 2 ) {
			return false;
		}

		// We can fire global events as of now if asked to
		fireGlobals = s.global;

		// Uppercase the type
		s.type = s.type.toUpperCase();

		// Determine if request has content
		s.hasContent = !rnoContent.test( s.type );

		// Watch for a new set of requests
		if ( fireGlobals && jQuery.active++ === 0 ) {
			jQuery.event.trigger( "ajaxStart" );
		}

		// More options handling for requests with no content
		if ( !s.hasContent ) {

			// If data is available, append data to url
			if ( s.data ) {
				s.url += ( rquery.test( s.url ) ? "&" : "?" ) + s.data;
			}

			// Get ifModifiedKey before adding the anti-cache parameter
			ifModifiedKey = s.url;

			// Add anti-cache in url if needed
			if ( s.cache === false ) {

				var ts = jQuery.now(),
					// try replacing _= if it is there
					ret = s.url.replace( rts, "$1_=" + ts );

				// if nothing was replaced, add timestamp to the end
				s.url = ret + ( (ret === s.url ) ? ( rquery.test( s.url ) ? "&" : "?" ) + "_=" + ts : "" );
			}
		}

		// Set the correct header, if data is being sent
		if ( s.data && s.hasContent && s.contentType !== false || options.contentType ) {
			jqXHR.setRequestHeader( "Content-Type", s.contentType );
		}

		// Set the If-Modified-Since and/or If-None-Match header, if in ifModified mode.
		if ( s.ifModified ) {
			ifModifiedKey = ifModifiedKey || s.url;
			if ( jQuery.lastModified[ ifModifiedKey ] ) {
				jqXHR.setRequestHeader( "If-Modified-Since", jQuery.lastModified[ ifModifiedKey ] );
			}
			if ( jQuery.etag[ ifModifiedKey ] ) {
				jqXHR.setRequestHeader( "If-None-Match", jQuery.etag[ ifModifiedKey ] );
			}
		}

		// Set the Accepts header for the server, depending on the dataType
		jqXHR.setRequestHeader(
			"Accept",
			s.dataTypes[ 0 ] && s.accepts[ s.dataTypes[0] ] ?
				s.accepts[ s.dataTypes[0] ] + ( s.dataTypes[ 0 ] !== "*" ? ", */*; q=0.01" : "" ) :
				s.accepts[ "*" ]
		);

		// Check for headers option
		for ( i in s.headers ) {
			jqXHR.setRequestHeader( i, s.headers[ i ] );
		}

		// Allow custom headers/mimetypes and early abort
		if ( s.beforeSend && ( s.beforeSend.call( callbackContext, jqXHR, s ) === false || state === 2 ) ) {
				// Abort if not done already
				jqXHR.abort();
				return false;

		}

		// Install callbacks on deferreds
		for ( i in { success: 1, error: 1, complete: 1 } ) {
			jqXHR[ i ]( s[ i ] );
		}

		// Get transport
		transport = inspectPrefiltersOrTransports( transports, s, options, jqXHR );

		// If no transport, we auto-abort
		if ( !transport ) {
			done( -1, "No Transport" );
		} else {
			jqXHR.readyState = 1;
			// Send global event
			if ( fireGlobals ) {
				globalEventContext.trigger( "ajaxSend", [ jqXHR, s ] );
			}
			// Timeout
			if ( s.async && s.timeout > 0 ) {
				timeoutTimer = setTimeout( function(){
					jqXHR.abort( "timeout" );
				}, s.timeout );
			}

			try {
				state = 1;
				transport.send( requestHeaders, done );
			} catch (e) {
				// Propagate exception as error if not done
				if ( status < 2 ) {
					done( -1, e );
				// Simply rethrow otherwise
				} else {
					jQuery.error( e );
				}
			}
		}

		return jqXHR;
	},

	// Serialize an array of form elements or a set of
	// key/values into a query string
	param: function( a, traditional ) {
		var s = [],
			add = function( key, value ) {
				// If value is a function, invoke it and return its value
				value = jQuery.isFunction( value ) ? value() : value;
				s[ s.length ] = encodeURIComponent( key ) + "=" + encodeURIComponent( value );
			};

		// Set traditional to true for jQuery <= 1.3.2 behavior.
		if ( traditional === undefined ) {
			traditional = jQuery.ajaxSettings.traditional;
		}

		// If an array was passed in, assume that it is an array of form elements.
		if ( jQuery.isArray( a ) || ( a.jquery && !jQuery.isPlainObject( a ) ) ) {
			// Serialize the form elements
			jQuery.each( a, function() {
				add( this.name, this.value );
			});

		} else {
			// If traditional, encode the "old" way (the way 1.3.2 or older
			// did it), otherwise encode params recursively.
			for ( var prefix in a ) {
				buildParams( prefix, a[ prefix ], traditional, add );
			}
		}

		// Return the resulting serialization
		return s.join( "&" ).replace( r20, "+" );
	}
});

function buildParams( prefix, obj, traditional, add ) {
	if ( jQuery.isArray( obj ) ) {
		// Serialize array item.
		jQuery.each( obj, function( i, v ) {
			if ( traditional || rbracket.test( prefix ) ) {
				// Treat each array item as a scalar.
				add( prefix, v );

			} else {
				// If array item is non-scalar (array or object), encode its
				// numeric index to resolve deserialization ambiguity issues.
				// Note that rack (as of 1.0.0) can't currently deserialize
				// nested arrays properly, and attempting to do so may cause
				// a server error. Possible fixes are to modify rack's
				// deserialization algorithm or to provide an option or flag
				// to force array serialization to be shallow.
				buildParams( prefix + "[" + ( typeof v === "object" || jQuery.isArray(v) ? i : "" ) + "]", v, traditional, add );
			}
		});

	} else if ( !traditional && obj != null && typeof obj === "object" ) {
		// Serialize object item.
		for ( var name in obj ) {
			buildParams( prefix + "[" + name + "]", obj[ name ], traditional, add );
		}

	} else {
		// Serialize scalar item.
		add( prefix, obj );
	}
}

// This is still on the jQuery object... for now
// Want to move this to jQuery.ajax some day
jQuery.extend({

	// Counter for holding the number of active queries
	active: 0,

	// Last-Modified header cache for next request
	lastModified: {},
	etag: {}

});

/* Handles responses to an ajax request:
 * - sets all responseXXX fields accordingly
 * - finds the right dataType (mediates between content-type and expected dataType)
 * - returns the corresponding response
 */
function ajaxHandleResponses( s, jqXHR, responses ) {

	var contents = s.contents,
		dataTypes = s.dataTypes,
		responseFields = s.responseFields,
		ct,
		type,
		finalDataType,
		firstDataType;

	// Fill responseXXX fields
	for( type in responseFields ) {
		if ( type in responses ) {
			jqXHR[ responseFields[type] ] = responses[ type ];
		}
	}

	// Remove auto dataType and get content-type in the process
	while( dataTypes[ 0 ] === "*" ) {
		dataTypes.shift();
		if ( ct === undefined ) {
			ct = s.mimeType || jqXHR.getResponseHeader( "content-type" );
		}
	}

	// Check if we're dealing with a known content-type
	if ( ct ) {
		for ( type in contents ) {
			if ( contents[ type ] && contents[ type ].test( ct ) ) {
				dataTypes.unshift( type );
				break;
			}
		}
	}

	// Check to see if we have a response for the expected dataType
	if ( dataTypes[ 0 ] in responses ) {
		finalDataType = dataTypes[ 0 ];
	} else {
		// Try convertible dataTypes
		for ( type in responses ) {
			if ( !dataTypes[ 0 ] || s.converters[ type + " " + dataTypes[0] ] ) {
				finalDataType = type;
				break;
			}
			if ( !firstDataType ) {
				firstDataType = type;
			}
		}
		// Or just use first one
		finalDataType = finalDataType || firstDataType;
	}

	// If we found a dataType
	// We add the dataType to the list if needed
	// and return the corresponding response
	if ( finalDataType ) {
		if ( finalDataType !== dataTypes[ 0 ] ) {
			dataTypes.unshift( finalDataType );
		}
		return responses[ finalDataType ];
	}
}

// Chain conversions given the request and the original response
function ajaxConvert( s, response ) {

	// Apply the dataFilter if provided
	if ( s.dataFilter ) {
		response = s.dataFilter( response, s.dataType );
	}

	var dataTypes = s.dataTypes,
		converters = {},
		i,
		key,
		length = dataTypes.length,
		tmp,
		// Current and previous dataTypes
		current = dataTypes[ 0 ],
		prev,
		// Conversion expression
		conversion,
		// Conversion function
		conv,
		// Conversion functions (transitive conversion)
		conv1,
		conv2;

	// For each dataType in the chain
	for( i = 1; i < length; i++ ) {

		// Create converters map
		// with lowercased keys
		if ( i === 1 ) {
			for( key in s.converters ) {
				if( typeof key === "string" ) {
					converters[ key.toLowerCase() ] = s.converters[ key ];
				}
			}
		}

		// Get the dataTypes
		prev = current;
		current = dataTypes[ i ];

		// If current is auto dataType, update it to prev
		if( current === "*" ) {
			current = prev;
		// If no auto and dataTypes are actually different
		} else if ( prev !== "*" && prev !== current ) {

			// Get the converter
			conversion = prev + " " + current;
			conv = converters[ conversion ] || converters[ "* " + current ];

			// If there is no direct converter, search transitively
			if ( !conv ) {
				conv2 = undefined;
				for( conv1 in converters ) {
					tmp = conv1.split( " " );
					if ( tmp[ 0 ] === prev || tmp[ 0 ] === "*" ) {
						conv2 = converters[ tmp[1] + " " + current ];
						if ( conv2 ) {
							conv1 = converters[ conv1 ];
							if ( conv1 === true ) {
								conv = conv2;
							} else if ( conv2 === true ) {
								conv = conv1;
							}
							break;
						}
					}
				}
			}
			// If we found no converter, dispatch an error
			if ( !( conv || conv2 ) ) {
				jQuery.error( "No conversion from " + conversion.replace(" "," to ") );
			}
			// If found converter is not an equivalence
			if ( conv !== true ) {
				// Convert with 1 or 2 converters accordingly
				response = conv ? conv( response ) : conv2( conv1(response) );
			}
		}
	}
	return response;
}




var jsc = jQuery.now(),
	jsre = /(\=)\?(&|$)|\?\?/i;

// Default jsonp settings
jQuery.ajaxSetup({
	jsonp: "callback",
	jsonpCallback: function() {
		return jQuery.expando + "_" + ( jsc++ );
	}
});

// Detect, normalize options and install callbacks for jsonp requests
jQuery.ajaxPrefilter( "json jsonp", function( s, originalSettings, jqXHR ) {

	var inspectData = s.contentType === "application/x-www-form-urlencoded" &&
		( typeof s.data === "string" );

	if ( s.dataTypes[ 0 ] === "jsonp" ||
		s.jsonp !== false && ( jsre.test( s.url ) ||
				inspectData && jsre.test( s.data ) ) ) {

		var responseContainer,
			jsonpCallback = s.jsonpCallback =
				jQuery.isFunction( s.jsonpCallback ) ? s.jsonpCallback() : s.jsonpCallback,
			previous = window[ jsonpCallback ],
			url = s.url,
			data = s.data,
			replace = "$1" + jsonpCallback + "$2";

		if ( s.jsonp !== false ) {
			url = url.replace( jsre, replace );
			if ( s.url === url ) {
				if ( inspectData ) {
					data = data.replace( jsre, replace );
				}
				if ( s.data === data ) {
					// Add callback manually
					url += (/\?/.test( url ) ? "&" : "?") + s.jsonp + "=" + jsonpCallback;
				}
			}
		}

		s.url = url;
		s.data = data;

		// Install callback
		window[ jsonpCallback ] = function( response ) {
			responseContainer = [ response ];
		};

		// Clean-up function
		jqXHR.always(function() {
			// Set callback back to previous value
			window[ jsonpCallback ] = previous;
			// Call if it was a function and we have a response
			if ( responseContainer && jQuery.isFunction( previous ) ) {
				window[ jsonpCallback ]( responseContainer[ 0 ] );
			}
		});

		// Use data converter to retrieve json after script execution
		s.converters["script json"] = function() {
			if ( !responseContainer ) {
				jQuery.error( jsonpCallback + " was not called" );
			}
			return responseContainer[ 0 ];
		};

		// force json dataType
		s.dataTypes[ 0 ] = "json";

		// Delegate to script
		return "script";
	}
});




// Install script dataType
jQuery.ajaxSetup({
	accepts: {
		script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
	},
	contents: {
		script: /javascript|ecmascript/
	},
	converters: {
		"text script": function( text ) {
			jQuery.globalEval( text );
			return text;
		}
	}
});

// Handle cache's special case and global
jQuery.ajaxPrefilter( "script", function( s ) {
	if ( s.cache === undefined ) {
		s.cache = false;
	}
	if ( s.crossDomain ) {
		s.type = "GET";
		s.global = false;
	}
});

// Bind script tag hack transport
jQuery.ajaxTransport( "script", function(s) {

	// This transport only deals with cross domain requests
	if ( s.crossDomain ) {

		var script,
			head = document.head || document.getElementsByTagName( "head" )[0] || document.documentElement;

		return {

			send: function( _, callback ) {

				script = document.createElement( "script" );

				script.async = "async";

				if ( s.scriptCharset ) {
					script.charset = s.scriptCharset;
				}

				script.src = s.url;

				// Attach handlers for all browsers
				script.onload = script.onreadystatechange = function( _, isAbort ) {

					if ( isAbort || !script.readyState || /loaded|complete/.test( script.readyState ) ) {

						// Handle memory leak in IE
						script.onload = script.onreadystatechange = null;

						// Remove the script
						if ( head && script.parentNode ) {
							head.removeChild( script );
						}

						// Dereference the script
						script = undefined;

						// Callback if not abort
						if ( !isAbort ) {
							callback( 200, "success" );
						}
					}
				};
				// Use insertBefore instead of appendChild  to circumvent an IE6 bug.
				// This arises when a base node is used (#2709 and #4378).
				head.insertBefore( script, head.firstChild );
			},

			abort: function() {
				if ( script ) {
					script.onload( 0, 1 );
				}
			}
		};
	}
});




var // #5280: Internet Explorer will keep connections alive if we don't abort on unload
	xhrOnUnloadAbort = window.ActiveXObject ? function() {
		// Abort all pending requests
		for ( var key in xhrCallbacks ) {
			xhrCallbacks[ key ]( 0, 1 );
		}
	} : false,
	xhrId = 0,
	xhrCallbacks;

// Functions to create xhrs
function createStandardXHR() {
	try {
		return new window.XMLHttpRequest();
	} catch( e ) {}
}

function createActiveXHR() {
	try {
		return new window.ActiveXObject( "Microsoft.XMLHTTP" );
	} catch( e ) {}
}

// Create the request object
// (This is still attached to ajaxSettings for backward compatibility)
jQuery.ajaxSettings.xhr = window.ActiveXObject ?
	/* Microsoft failed to properly
	 * implement the XMLHttpRequest in IE7 (can't request local files),
	 * so we use the ActiveXObject when it is available
	 * Additionally XMLHttpRequest can be disabled in IE7/IE8 so
	 * we need a fallback.
	 */
	function() {
		return !this.isLocal && createStandardXHR() || createActiveXHR();
	} :
	// For all other browsers, use the standard XMLHttpRequest object
	createStandardXHR;

// Determine support properties
(function( xhr ) {
	jQuery.extend( jQuery.support, {
		ajax: !!xhr,
		cors: !!xhr && ( "withCredentials" in xhr )
	});
})( jQuery.ajaxSettings.xhr() );

// Create transport if the browser can provide an xhr
if ( jQuery.support.ajax ) {

	jQuery.ajaxTransport(function( s ) {
		// Cross domain only allowed if supported through XMLHttpRequest
		if ( !s.crossDomain || jQuery.support.cors ) {

			var callback;

			return {
				send: function( headers, complete ) {

					// Get a new xhr
					var xhr = s.xhr(),
						handle,
						i;

					// Open the socket
					// Passing null username, generates a login popup on Opera (#2865)
					if ( s.username ) {
						xhr.open( s.type, s.url, s.async, s.username, s.password );
					} else {
						xhr.open( s.type, s.url, s.async );
					}

					// Apply custom fields if provided
					if ( s.xhrFields ) {
						for ( i in s.xhrFields ) {
							xhr[ i ] = s.xhrFields[ i ];
						}
					}

					// Override mime type if needed
					if ( s.mimeType && xhr.overrideMimeType ) {
						xhr.overrideMimeType( s.mimeType );
					}

					// X-Requested-With header
					// For cross-domain requests, seeing as conditions for a preflight are
					// akin to a jigsaw puzzle, we simply never set it to be sure.
					// (it can always be set on a per-request basis or even using ajaxSetup)
					// For same-domain requests, won't change header if already provided.
					if ( !s.crossDomain && !headers["X-Requested-With"] ) {
						headers[ "X-Requested-With" ] = "XMLHttpRequest";
					}

					// Need an extra try/catch for cross domain requests in Firefox 3
					try {
						for ( i in headers ) {
							xhr.setRequestHeader( i, headers[ i ] );
						}
					} catch( _ ) {}

					// Do send the request
					// This may raise an exception which is actually
					// handled in jQuery.ajax (so no try/catch here)
					xhr.send( ( s.hasContent && s.data ) || null );

					// Listener
					callback = function( _, isAbort ) {

						var status,
							statusText,
							responseHeaders,
							responses,
							xml;

						// Firefox throws exceptions when accessing properties
						// of an xhr when a network error occured
						// http://helpful.knobs-dials.com/index.php/Component_returned_failure_code:_0x80040111_(NS_ERROR_NOT_AVAILABLE)
						try {

							// Was never called and is aborted or complete
							if ( callback && ( isAbort || xhr.readyState === 4 ) ) {

								// Only called once
								callback = undefined;

								// Do not keep as active anymore
								if ( handle ) {
									xhr.onreadystatechange = jQuery.noop;
									if ( xhrOnUnloadAbort ) {
										delete xhrCallbacks[ handle ];
									}
								}

								// If it's an abort
								if ( isAbort ) {
									// Abort it manually if needed
									if ( xhr.readyState !== 4 ) {
										xhr.abort();
									}
								} else {
									status = xhr.status;
									responseHeaders = xhr.getAllResponseHeaders();
									responses = {};
									xml = xhr.responseXML;

									// Construct response list
									if ( xml && xml.documentElement /* #4958 */ ) {
										responses.xml = xml;
									}
									responses.text = xhr.responseText;

									// Firefox throws an exception when accessing
									// statusText for faulty cross-domain requests
									try {
										statusText = xhr.statusText;
									} catch( e ) {
										// We normalize with Webkit giving an empty statusText
										statusText = "";
									}

									// Filter status for non standard behaviors

									// If the request is local and we have data: assume a success
									// (success with no data won't get notified, that's the best we
									// can do given current implementations)
									if ( !status && s.isLocal && !s.crossDomain ) {
										status = responses.text ? 200 : 404;
									// IE - #1450: sometimes returns 1223 when it should be 204
									} else if ( status === 1223 ) {
										status = 204;
									}
								}
							}
						} catch( firefoxAccessException ) {
							if ( !isAbort ) {
								complete( -1, firefoxAccessException );
							}
						}

						// Call complete if needed
						if ( responses ) {
							complete( status, statusText, responses, responseHeaders );
						}
					};

					// if we're in sync mode or it's in cache
					// and has been retrieved directly (IE6 & IE7)
					// we need to manually fire the callback
					if ( !s.async || xhr.readyState === 4 ) {
						callback();
					} else {
						handle = ++xhrId;
						if ( xhrOnUnloadAbort ) {
							// Create the active xhrs callbacks list if needed
							// and attach the unload handler
							if ( !xhrCallbacks ) {
								xhrCallbacks = {};
								jQuery( window ).unload( xhrOnUnloadAbort );
							}
							// Add to list of active xhrs callbacks
							xhrCallbacks[ handle ] = callback;
						}
						xhr.onreadystatechange = callback;
					}
				},

				abort: function() {
					if ( callback ) {
						callback(0,1);
					}
				}
			};
		}
	});
}




var elemdisplay = {},
	iframe, iframeDoc,
	rfxtypes = /^(?:toggle|show|hide)$/,
	rfxnum = /^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i,
	timerId,
	fxAttrs = [
		// height animations
		[ "height", "marginTop", "marginBottom", "paddingTop", "paddingBottom" ],
		// width animations
		[ "width", "marginLeft", "marginRight", "paddingLeft", "paddingRight" ],
		// opacity animations
		[ "opacity" ]
	],
	fxNow,
	requestAnimationFrame = window.webkitRequestAnimationFrame ||
	    window.mozRequestAnimationFrame ||
	    window.oRequestAnimationFrame;

jQuery.fn.extend({
	show: function( speed, easing, callback ) {
		var elem, display;

		if ( speed || speed === 0 ) {
			return this.animate( genFx("show", 3), speed, easing, callback);

		} else {
			for ( var i = 0, j = this.length; i < j; i++ ) {
				elem = this[i];

				if ( elem.style ) {
					display = elem.style.display;

					// Reset the inline display of this element to learn if it is
					// being hidden by cascaded rules or not
					if ( !jQuery._data(elem, "olddisplay") && display === "none" ) {
						display = elem.style.display = "";
					}

					// Set elements which have been overridden with display: none
					// in a stylesheet to whatever the default browser style is
					// for such an element
					if ( display === "" && jQuery.css( elem, "display" ) === "none" ) {
						jQuery._data(elem, "olddisplay", defaultDisplay(elem.nodeName));
					}
				}
			}

			// Set the display of most of the elements in a second loop
			// to avoid the constant reflow
			for ( i = 0; i < j; i++ ) {
				elem = this[i];

				if ( elem.style ) {
					display = elem.style.display;

					if ( display === "" || display === "none" ) {
						elem.style.display = jQuery._data(elem, "olddisplay") || "";
					}
				}
			}

			return this;
		}
	},

	hide: function( speed, easing, callback ) {
		if ( speed || speed === 0 ) {
			return this.animate( genFx("hide", 3), speed, easing, callback);

		} else {
			for ( var i = 0, j = this.length; i < j; i++ ) {
				if ( this[i].style ) {
					var display = jQuery.css( this[i], "display" );

					if ( display !== "none" && !jQuery._data( this[i], "olddisplay" ) ) {
						jQuery._data( this[i], "olddisplay", display );
					}
				}
			}

			// Set the display of the elements in a second loop
			// to avoid the constant reflow
			for ( i = 0; i < j; i++ ) {
				if ( this[i].style ) {
					this[i].style.display = "none";
				}
			}

			return this;
		}
	},

	// Save the old toggle function
	_toggle: jQuery.fn.toggle,

	toggle: function( fn, fn2, callback ) {
		var bool = typeof fn === "boolean";

		if ( jQuery.isFunction(fn) && jQuery.isFunction(fn2) ) {
			this._toggle.apply( this, arguments );

		} else if ( fn == null || bool ) {
			this.each(function() {
				var state = bool ? fn : jQuery(this).is(":hidden");
				jQuery(this)[ state ? "show" : "hide" ]();
			});

		} else {
			this.animate(genFx("toggle", 3), fn, fn2, callback);
		}

		return this;
	},

	fadeTo: function( speed, to, easing, callback ) {
		return this.filter(":hidden").css("opacity", 0).show().end()
					.animate({opacity: to}, speed, easing, callback);
	},

	animate: function( prop, speed, easing, callback ) {
		var optall = jQuery.speed(speed, easing, callback);

		if ( jQuery.isEmptyObject( prop ) ) {
			return this.each( optall.complete, [ false ] );
		}

		return this[ optall.queue === false ? "each" : "queue" ](function() {
			// XXX 'this' does not always have a nodeName when running the
			// test suite

			if ( optall.queue === false ) {
				jQuery._mark( this );
			}

			var opt = jQuery.extend({}, optall),
				isElement = this.nodeType === 1,
				hidden = isElement && jQuery(this).is(":hidden"),
				name, val, p,
				display, e,
				parts, start, end, unit;

			// will store per property easing and be used to determine when an animation is complete
			opt.animatedProperties = {};

			for ( p in prop ) {

				// property name normalization
				name = jQuery.camelCase( p );
				if ( p !== name ) {
					prop[ name ] = prop[ p ];
					delete prop[ p ];
				}

				val = prop[name];

				if ( val === "hide" && hidden || val === "show" && !hidden ) {
					return opt.complete.call(this);
				}

				if ( isElement && ( name === "height" || name === "width" ) ) {
					// Make sure that nothing sneaks out
					// Record all 3 overflow attributes because IE does not
					// change the overflow attribute when overflowX and
					// overflowY are set to the same value
					opt.overflow = [ this.style.overflow, this.style.overflowX, this.style.overflowY ];

					// Set display property to inline-block for height/width
					// animations on inline elements that are having width/height
					// animated
					if ( jQuery.css( this, "display" ) === "inline" &&
							jQuery.css( this, "float" ) === "none" ) {
						if ( !jQuery.support.inlineBlockNeedsLayout ) {
							this.style.display = "inline-block";

						} else {
							display = defaultDisplay(this.nodeName);

							// inline-level elements accept inline-block;
							// block-level elements need to be inline with layout
							if ( display === "inline" ) {
								this.style.display = "inline-block";

							} else {
								this.style.display = "inline";
								this.style.zoom = 1;
							}
						}
					}
				}

				// easing resolution: per property > opt.specialEasing > opt.easing > 'swing' (default)
				opt.animatedProperties[name] = jQuery.isArray( val ) ?
					val[1]:
					opt.specialEasing && opt.specialEasing[name] || opt.easing || 'swing';
			}

			if ( opt.overflow != null ) {
				this.style.overflow = "hidden";
			}

			for ( p in prop ) {
				e = new jQuery.fx( this, opt, p );

				val = prop[p];

				if ( rfxtypes.test(val) ) {
					e[ val === "toggle" ? hidden ? "show" : "hide" : val ]();

				} else {
					parts = rfxnum.exec(val);
					start = e.cur();

					if ( parts ) {
						end = parseFloat( parts[2] );
						unit = parts[3] || ( jQuery.cssNumber[ name ] ? "" : "px" );

						// We need to compute starting value
						if ( unit !== "px" ) {
							jQuery.style( this, p, (end || 1) + unit);
							start = ((end || 1) / e.cur()) * start;
							jQuery.style( this, p, start + unit);
						}

						// If a +=/-= token was provided, we're doing a relative animation
						if ( parts[1] ) {
							end = ((parts[1] === "-=" ? -1 : 1) * end) + start;
						}

						e.custom( start, end, unit );

					} else {
						e.custom( start, val, "" );
					}
				}
			}

			// For JS strict compliance
			return true;
		});
	},

	stop: function( clearQueue, gotoEnd ) {
		if ( clearQueue ) {
			this.queue([]);
		}

		this.each(function() {
			var timers = jQuery.timers,
				i = timers.length;
			// clear marker counters if we know they won't be
			if ( !gotoEnd ) {
				jQuery._unmark( true, this );
			}
			// go in reverse order so anything added to the queue during the loop is ignored
			while ( i-- ) {
				if ( timers[i].elem === this ) {
					if (gotoEnd) {
						// force the next step to be the last
						timers[i](true);
					}

					timers.splice(i, 1);
				}
			}
		});

		// start the next in the queue if the last step wasn't forced
		if ( !gotoEnd ) {
			this.dequeue();
		}

		return this;
	}

});

// Animations created synchronously will run synchronously
function createFxNow() {
	setTimeout( clearFxNow, 0 );
	return ( fxNow = jQuery.now() );
}

function clearFxNow() {
	fxNow = undefined;
}

// Generate parameters to create a standard animation
function genFx( type, num ) {
	var obj = {};

	jQuery.each( fxAttrs.concat.apply([], fxAttrs.slice(0,num)), function() {
		obj[ this ] = type;
	});

	return obj;
}

// Generate shortcuts for custom animations
jQuery.each({
	slideDown: genFx("show", 1),
	slideUp: genFx("hide", 1),
	slideToggle: genFx("toggle", 1),
	fadeIn: { opacity: "show" },
	fadeOut: { opacity: "hide" },
	fadeToggle: { opacity: "toggle" }
}, function( name, props ) {
	jQuery.fn[ name ] = function( speed, easing, callback ) {
		return this.animate( props, speed, easing, callback );
	};
});

jQuery.extend({
	speed: function( speed, easing, fn ) {
		var opt = speed && typeof speed === "object" ? jQuery.extend({}, speed) : {
			complete: fn || !fn && easing ||
				jQuery.isFunction( speed ) && speed,
			duration: speed,
			easing: fn && easing || easing && !jQuery.isFunction(easing) && easing
		};

		opt.duration = jQuery.fx.off ? 0 : typeof opt.duration === "number" ? opt.duration :
			opt.duration in jQuery.fx.speeds ? jQuery.fx.speeds[opt.duration] : jQuery.fx.speeds._default;

		// Queueing
		opt.old = opt.complete;
		opt.complete = function( noUnmark ) {
			if ( opt.queue !== false ) {
				jQuery.dequeue( this );
			} else if ( noUnmark !== false ) {
				jQuery._unmark( this );
			}

			if ( jQuery.isFunction( opt.old ) ) {
				opt.old.call( this );
			}
		};

		return opt;
	},

	easing: {
		linear: function( p, n, firstNum, diff ) {
			return firstNum + diff * p;
		},
		swing: function( p, n, firstNum, diff ) {
			return ((-Math.cos(p*Math.PI)/2) + 0.5) * diff + firstNum;
		}
	},

	timers: [],

	fx: function( elem, options, prop ) {
		this.options = options;
		this.elem = elem;
		this.prop = prop;

		options.orig = options.orig || {};
	}

});

jQuery.fx.prototype = {
	// Simple function for setting a style value
	update: function() {
		if ( this.options.step ) {
			this.options.step.call( this.elem, this.now, this );
		}

		(jQuery.fx.step[this.prop] || jQuery.fx.step._default)( this );
	},

	// Get the current size
	cur: function() {
		if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
			return this.elem[ this.prop ];
		}

		var parsed,
			r = jQuery.css( this.elem, this.prop );
		// Empty strings, null, undefined and "auto" are converted to 0,
		// complex values such as "rotate(1rad)" are returned as is,
		// simple values such as "10px" are parsed to Float.
		return isNaN( parsed = parseFloat( r ) ) ? !r || r === "auto" ? 0 : r : parsed;
	},

	// Start an animation from one number to another
	custom: function( from, to, unit ) {
		var self = this,
			fx = jQuery.fx,
			raf;

		this.startTime = fxNow || createFxNow();
		this.start = from;
		this.end = to;
		this.unit = unit || this.unit || ( jQuery.cssNumber[ this.prop ] ? "" : "px" );
		this.now = this.start;
		this.pos = this.state = 0;

		function t( gotoEnd ) {
			return self.step(gotoEnd);
		}

		t.elem = this.elem;

		if ( t() && jQuery.timers.push(t) && !timerId ) {
			// Use requestAnimationFrame instead of setInterval if available
			if ( requestAnimationFrame ) {
				timerId = 1;
				raf = function() {
					// When timerId gets set to null at any point, this stops
					if ( timerId ) {
						requestAnimationFrame( raf );
						fx.tick();
					}
				};
				requestAnimationFrame( raf );
			} else {
				timerId = setInterval( fx.tick, fx.interval );
			}
		}
	},

	// Simple 'show' function
	show: function() {
		// Remember where we started, so that we can go back to it later
		this.options.orig[this.prop] = jQuery.style( this.elem, this.prop );
		this.options.show = true;

		// Begin the animation
		// Make sure that we start at a small width/height to avoid any
		// flash of content
		this.custom(this.prop === "width" || this.prop === "height" ? 1 : 0, this.cur());

		// Start by showing the element
		jQuery( this.elem ).show();
	},

	// Simple 'hide' function
	hide: function() {
		// Remember where we started, so that we can go back to it later
		this.options.orig[this.prop] = jQuery.style( this.elem, this.prop );
		this.options.hide = true;

		// Begin the animation
		this.custom(this.cur(), 0);
	},

	// Each step of an animation
	step: function( gotoEnd ) {
		var t = fxNow || createFxNow(),
			done = true,
			elem = this.elem,
			options = this.options,
			i, n;

		if ( gotoEnd || t >= options.duration + this.startTime ) {
			this.now = this.end;
			this.pos = this.state = 1;
			this.update();

			options.animatedProperties[ this.prop ] = true;

			for ( i in options.animatedProperties ) {
				if ( options.animatedProperties[i] !== true ) {
					done = false;
				}
			}

			if ( done ) {
				// Reset the overflow
				if ( options.overflow != null && !jQuery.support.shrinkWrapBlocks ) {

					jQuery.each( [ "", "X", "Y" ], function (index, value) {
						elem.style[ "overflow" + value ] = options.overflow[index];
					});
				}

				// Hide the element if the "hide" operation was done
				if ( options.hide ) {
					jQuery(elem).hide();
				}

				// Reset the properties, if the item has been hidden or shown
				if ( options.hide || options.show ) {
					for ( var p in options.animatedProperties ) {
						jQuery.style( elem, p, options.orig[p] );
					}
				}

				// Execute the complete function
				options.complete.call( elem );
			}

			return false;

		} else {
			// classical easing cannot be used with an Infinity duration
			if ( options.duration == Infinity ) {
				this.now = t;
			} else {
				n = t - this.startTime;

				this.state = n / options.duration;
				// Perform the easing function, defaults to swing
				this.pos = jQuery.easing[options.animatedProperties[this.prop]](this.state, n, 0, 1, options.duration);
				this.now = this.start + ((this.end - this.start) * this.pos);
			}
			// Perform the next step of the animation
			this.update();
		}

		return true;
	}
};

jQuery.extend( jQuery.fx, {
	tick: function() {
		var timers = jQuery.timers,
			i = timers.length;
		while ( i-- ) {
			if ( !timers[i]() ) {
				timers.splice(i, 1);
			}
		}

		if ( !timers.length ) {
			jQuery.fx.stop();
		}
	},

	interval: 13,

	stop: function() {
		clearInterval( timerId );
		timerId = null;
	},

	speeds: {
		slow: 600,
		fast: 200,
		// Default speed
		_default: 400
	},

	step: {
		opacity: function( fx ) {
			jQuery.style( fx.elem, "opacity", fx.now );
		},

		_default: function( fx ) {
			if ( fx.elem.style && fx.elem.style[ fx.prop ] != null ) {
				fx.elem.style[ fx.prop ] = (fx.prop === "width" || fx.prop === "height" ? Math.max(0, fx.now) : fx.now) + fx.unit;
			} else {
				fx.elem[ fx.prop ] = fx.now;
			}
		}
	}
});

if ( jQuery.expr && jQuery.expr.filters ) {
	jQuery.expr.filters.animated = function( elem ) {
		return jQuery.grep(jQuery.timers, function( fn ) {
			return elem === fn.elem;
		}).length;
	};
}

// Try to restore the default display value of an element
function defaultDisplay( nodeName ) {

	if ( !elemdisplay[ nodeName ] ) {

		var elem = jQuery( "<" + nodeName + ">" ).appendTo( "body" ),
			display = elem.css( "display" );

		elem.remove();

		// If the simple way fails,
		// get element's real default display by attaching it to a temp iframe
		if ( display === "none" || display === "" ) {
			// No iframe to use yet, so create it
			if ( !iframe ) {
				iframe = document.createElement( "iframe" );
				iframe.frameBorder = iframe.width = iframe.height = 0;
			}

			document.body.appendChild( iframe );

			// Create a cacheable copy of the iframe document on first call.
			// IE and Opera will allow us to reuse the iframeDoc without re-writing the fake html
			// document to it, Webkit & Firefox won't allow reusing the iframe document
			if ( !iframeDoc || !iframe.createElement ) {
				iframeDoc = ( iframe.contentWindow || iframe.contentDocument ).document;
				iframeDoc.write( "<!doctype><html><body></body></html>" );
			}

			elem = iframeDoc.createElement( nodeName );

			iframeDoc.body.appendChild( elem );

			display = jQuery.css( elem, "display" );

			document.body.removeChild( iframe );
		}

		// Store the correct default display
		elemdisplay[ nodeName ] = display;
	}

	return elemdisplay[ nodeName ];
}




var rtable = /^t(?:able|d|h)$/i,
	rroot = /^(?:body|html)$/i;

if ( "getBoundingClientRect" in document.documentElement ) {
	jQuery.fn.offset = function( options ) {
		var elem = this[0], box;

		if ( options ) {
			return this.each(function( i ) {
				jQuery.offset.setOffset( this, options, i );
			});
		}

		if ( !elem || !elem.ownerDocument ) {
			return null;
		}

		if ( elem === elem.ownerDocument.body ) {
			return jQuery.offset.bodyOffset( elem );
		}

		try {
			box = elem.getBoundingClientRect();
		} catch(e) {}

		var doc = elem.ownerDocument,
			docElem = doc.documentElement;

		// Make sure we're not dealing with a disconnected DOM node
		if ( !box || !jQuery.contains( docElem, elem ) ) {
			return box ? { top: box.top, left: box.left } : { top: 0, left: 0 };
		}

		var body = doc.body,
			win = getWindow(doc),
			clientTop  = docElem.clientTop  || body.clientTop  || 0,
			clientLeft = docElem.clientLeft || body.clientLeft || 0,
			scrollTop  = win.pageYOffset || jQuery.support.boxModel && docElem.scrollTop  || body.scrollTop,
			scrollLeft = win.pageXOffset || jQuery.support.boxModel && docElem.scrollLeft || body.scrollLeft,
			top  = box.top  + scrollTop  - clientTop,
			left = box.left + scrollLeft - clientLeft;

		return { top: top, left: left };
	};

} else {
	jQuery.fn.offset = function( options ) {
		var elem = this[0];

		if ( options ) {
			return this.each(function( i ) {
				jQuery.offset.setOffset( this, options, i );
			});
		}

		if ( !elem || !elem.ownerDocument ) {
			return null;
		}

		if ( elem === elem.ownerDocument.body ) {
			return jQuery.offset.bodyOffset( elem );
		}

		jQuery.offset.initialize();

		var computedStyle,
			offsetParent = elem.offsetParent,
			prevOffsetParent = elem,
			doc = elem.ownerDocument,
			docElem = doc.documentElement,
			body = doc.body,
			defaultView = doc.defaultView,
			prevComputedStyle = defaultView ? defaultView.getComputedStyle( elem, null ) : elem.currentStyle,
			top = elem.offsetTop,
			left = elem.offsetLeft;

		while ( (elem = elem.parentNode) && elem !== body && elem !== docElem ) {
			if ( jQuery.offset.supportsFixedPosition && prevComputedStyle.position === "fixed" ) {
				break;
			}

			computedStyle = defaultView ? defaultView.getComputedStyle(elem, null) : elem.currentStyle;
			top  -= elem.scrollTop;
			left -= elem.scrollLeft;

			if ( elem === offsetParent ) {
				top  += elem.offsetTop;
				left += elem.offsetLeft;

				if ( jQuery.offset.doesNotAddBorder && !(jQuery.offset.doesAddBorderForTableAndCells && rtable.test(elem.nodeName)) ) {
					top  += parseFloat( computedStyle.borderTopWidth  ) || 0;
					left += parseFloat( computedStyle.borderLeftWidth ) || 0;
				}

				prevOffsetParent = offsetParent;
				offsetParent = elem.offsetParent;
			}

			if ( jQuery.offset.subtractsBorderForOverflowNotVisible && computedStyle.overflow !== "visible" ) {
				top  += parseFloat( computedStyle.borderTopWidth  ) || 0;
				left += parseFloat( computedStyle.borderLeftWidth ) || 0;
			}

			prevComputedStyle = computedStyle;
		}

		if ( prevComputedStyle.position === "relative" || prevComputedStyle.position === "static" ) {
			top  += body.offsetTop;
			left += body.offsetLeft;
		}

		if ( jQuery.offset.supportsFixedPosition && prevComputedStyle.position === "fixed" ) {
			top  += Math.max( docElem.scrollTop, body.scrollTop );
			left += Math.max( docElem.scrollLeft, body.scrollLeft );
		}

		return { top: top, left: left };
	};
}

jQuery.offset = {
	initialize: function() {
		var body = document.body, container = document.createElement("div"), innerDiv, checkDiv, table, td, bodyMarginTop = parseFloat( jQuery.css(body, "marginTop") ) || 0,
			html = "<div style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;'><div></div></div><table style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>";

		jQuery.extend( container.style, { position: "absolute", top: 0, left: 0, margin: 0, border: 0, width: "1px", height: "1px", visibility: "hidden" } );

		container.innerHTML = html;
		body.insertBefore( container, body.firstChild );
		innerDiv = container.firstChild;
		checkDiv = innerDiv.firstChild;
		td = innerDiv.nextSibling.firstChild.firstChild;

		this.doesNotAddBorder = (checkDiv.offsetTop !== 5);
		this.doesAddBorderForTableAndCells = (td.offsetTop === 5);

		checkDiv.style.position = "fixed";
		checkDiv.style.top = "20px";

		// safari subtracts parent border width here which is 5px
		this.supportsFixedPosition = (checkDiv.offsetTop === 20 || checkDiv.offsetTop === 15);
		checkDiv.style.position = checkDiv.style.top = "";

		innerDiv.style.overflow = "hidden";
		innerDiv.style.position = "relative";

		this.subtractsBorderForOverflowNotVisible = (checkDiv.offsetTop === -5);

		this.doesNotIncludeMarginInBodyOffset = (body.offsetTop !== bodyMarginTop);

		body.removeChild( container );
		jQuery.offset.initialize = jQuery.noop;
	},

	bodyOffset: function( body ) {
		var top = body.offsetTop,
			left = body.offsetLeft;

		jQuery.offset.initialize();

		if ( jQuery.offset.doesNotIncludeMarginInBodyOffset ) {
			top  += parseFloat( jQuery.css(body, "marginTop") ) || 0;
			left += parseFloat( jQuery.css(body, "marginLeft") ) || 0;
		}

		return { top: top, left: left };
	},

	setOffset: function( elem, options, i ) {
		var position = jQuery.css( elem, "position" );

		// set position first, in-case top/left are set even on static elem
		if ( position === "static" ) {
			elem.style.position = "relative";
		}

		var curElem = jQuery( elem ),
			curOffset = curElem.offset(),
			curCSSTop = jQuery.css( elem, "top" ),
			curCSSLeft = jQuery.css( elem, "left" ),
			calculatePosition = (position === "absolute" || position === "fixed") && jQuery.inArray("auto", [curCSSTop, curCSSLeft]) > -1,
			props = {}, curPosition = {}, curTop, curLeft;

		// need to be able to calculate position if either top or left is auto and position is either absolute or fixed
		if ( calculatePosition ) {
			curPosition = curElem.position();
			curTop = curPosition.top;
			curLeft = curPosition.left;
		} else {
			curTop = parseFloat( curCSSTop ) || 0;
			curLeft = parseFloat( curCSSLeft ) || 0;
		}

		if ( jQuery.isFunction( options ) ) {
			options = options.call( elem, i, curOffset );
		}

		if (options.top != null) {
			props.top = (options.top - curOffset.top) + curTop;
		}
		if (options.left != null) {
			props.left = (options.left - curOffset.left) + curLeft;
		}

		if ( "using" in options ) {
			options.using.call( elem, props );
		} else {
			curElem.css( props );
		}
	}
};


jQuery.fn.extend({
	position: function() {
		if ( !this[0] ) {
			return null;
		}

		var elem = this[0],

		// Get *real* offsetParent
		offsetParent = this.offsetParent(),

		// Get correct offsets
		offset       = this.offset(),
		parentOffset = rroot.test(offsetParent[0].nodeName) ? { top: 0, left: 0 } : offsetParent.offset();

		// Subtract element margins
		// note: when an element has margin: auto the offsetLeft and marginLeft
		// are the same in Safari causing offset.left to incorrectly be 0
		offset.top  -= parseFloat( jQuery.css(elem, "marginTop") ) || 0;
		offset.left -= parseFloat( jQuery.css(elem, "marginLeft") ) || 0;

		// Add offsetParent borders
		parentOffset.top  += parseFloat( jQuery.css(offsetParent[0], "borderTopWidth") ) || 0;
		parentOffset.left += parseFloat( jQuery.css(offsetParent[0], "borderLeftWidth") ) || 0;

		// Subtract the two offsets
		return {
			top:  offset.top  - parentOffset.top,
			left: offset.left - parentOffset.left
		};
	},

	offsetParent: function() {
		return this.map(function() {
			var offsetParent = this.offsetParent || document.body;
			while ( offsetParent && (!rroot.test(offsetParent.nodeName) && jQuery.css(offsetParent, "position") === "static") ) {
				offsetParent = offsetParent.offsetParent;
			}
			return offsetParent;
		});
	}
});


// Create scrollLeft and scrollTop methods
jQuery.each( ["Left", "Top"], function( i, name ) {
	var method = "scroll" + name;

	jQuery.fn[ method ] = function( val ) {
		var elem, win;

		if ( val === undefined ) {
			elem = this[ 0 ];

			if ( !elem ) {
				return null;
			}

			win = getWindow( elem );

			// Return the scroll offset
			return win ? ("pageXOffset" in win) ? win[ i ? "pageYOffset" : "pageXOffset" ] :
				jQuery.support.boxModel && win.document.documentElement[ method ] ||
					win.document.body[ method ] :
				elem[ method ];
		}

		// Set the scroll offset
		return this.each(function() {
			win = getWindow( this );

			if ( win ) {
				win.scrollTo(
					!i ? val : jQuery( win ).scrollLeft(),
					 i ? val : jQuery( win ).scrollTop()
				);

			} else {
				this[ method ] = val;
			}
		});
	};
});

function getWindow( elem ) {
	return jQuery.isWindow( elem ) ?
		elem :
		elem.nodeType === 9 ?
			elem.defaultView || elem.parentWindow :
			false;
}




// Create innerHeight, innerWidth, outerHeight and outerWidth methods
jQuery.each([ "Height", "Width" ], function( i, name ) {

	var type = name.toLowerCase();

	// innerHeight and innerWidth
	jQuery.fn["inner" + name] = function() {
		return this[0] ?
			parseFloat( jQuery.css( this[0], type, "padding" ) ) :
			null;
	};

	// outerHeight and outerWidth
	jQuery.fn["outer" + name] = function( margin ) {
		return this[0] ?
			parseFloat( jQuery.css( this[0], type, margin ? "margin" : "border" ) ) :
			null;
	};

	jQuery.fn[ type ] = function( size ) {
		// Get window width or height
		var elem = this[0];
		if ( !elem ) {
			return size == null ? null : this;
		}

		if ( jQuery.isFunction( size ) ) {
			return this.each(function( i ) {
				var self = jQuery( this );
				self[ type ]( size.call( this, i, self[ type ]() ) );
			});
		}

		if ( jQuery.isWindow( elem ) ) {
			// Everyone else use document.documentElement or document.body depending on Quirks vs Standards mode
			// 3rd condition allows Nokia support, as it supports the docElem prop but not CSS1Compat
			var docElemProp = elem.document.documentElement[ "client" + name ];
			return elem.document.compatMode === "CSS1Compat" && docElemProp ||
				elem.document.body[ "client" + name ] || docElemProp;

		// Get document width or height
		} else if ( elem.nodeType === 9 ) {
			// Either scroll[Width/Height] or offset[Width/Height], whichever is greater
			return Math.max(
				elem.documentElement["client" + name],
				elem.body["scroll" + name], elem.documentElement["scroll" + name],
				elem.body["offset" + name], elem.documentElement["offset" + name]
			);

		// Get or set width or height on the element
		} else if ( size === undefined ) {
			var orig = jQuery.css( elem, type ),
				ret = parseFloat( orig );

			return jQuery.isNaN( ret ) ? orig : ret;

		// Set the width or height on the element (default to pixels if value is unitless)
		} else {
			return this.css( type, typeof size === "string" ? size : size + "px" );
		}
	};

});


window.jQuery = window.$ = jQuery;
})(window);
/*!
 * jQuery UI 1.8.13
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI
 */

(function(c,j){function k(a,b){var d=a.nodeName.toLowerCase();if("area"===d){b=a.parentNode;d=b.name;if(!a.href||!d||b.nodeName.toLowerCase()!=="map")return false;a=c("img[usemap=#"+d+"]")[0];return!!a&&l(a)}return(/input|select|textarea|button|object/.test(d)?!a.disabled:"a"==d?a.href||b:b)&&l(a)}function l(a){return!c(a).parents().andSelf().filter(function(){return c.curCSS(this,"visibility")==="hidden"||c.expr.filters.hidden(this)}).length}c.ui=c.ui||{};if(!c.ui.version){c.extend(c.ui,{version:"1.8.13",
keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}});c.fn.extend({_focus:c.fn.focus,focus:function(a,b){return typeof a==="number"?this.each(function(){var d=this;setTimeout(function(){c(d).focus();
b&&b.call(d)},a)}):this._focus.apply(this,arguments)},scrollParent:function(){var a;a=c.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(c.curCSS(this,"position",1))&&/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,
"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0);return/fixed/.test(this.css("position"))||!a.length?c(document):a},zIndex:function(a){if(a!==j)return this.css("zIndex",a);if(this.length){a=c(this[0]);for(var b;a.length&&a[0]!==document;){b=a.css("position");if(b==="absolute"||b==="relative"||b==="fixed"){b=parseInt(a.css("zIndex"),10);if(!isNaN(b)&&b!==0)return b}a=a.parent()}}return 0},disableSelection:function(){return this.bind((c.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",
function(a){a.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}});c.each(["Width","Height"],function(a,b){function d(f,g,m,n){c.each(e,function(){g-=parseFloat(c.curCSS(f,"padding"+this,true))||0;if(m)g-=parseFloat(c.curCSS(f,"border"+this+"Width",true))||0;if(n)g-=parseFloat(c.curCSS(f,"margin"+this,true))||0});return g}var e=b==="Width"?["Left","Right"]:["Top","Bottom"],h=b.toLowerCase(),i={innerWidth:c.fn.innerWidth,innerHeight:c.fn.innerHeight,outerWidth:c.fn.outerWidth,
outerHeight:c.fn.outerHeight};c.fn["inner"+b]=function(f){if(f===j)return i["inner"+b].call(this);return this.each(function(){c(this).css(h,d(this,f)+"px")})};c.fn["outer"+b]=function(f,g){if(typeof f!=="number")return i["outer"+b].call(this,f);return this.each(function(){c(this).css(h,d(this,f,true,g)+"px")})}});c.extend(c.expr[":"],{data:function(a,b,d){return!!c.data(a,d[3])},focusable:function(a){return k(a,!isNaN(c.attr(a,"tabindex")))},tabbable:function(a){var b=c.attr(a,"tabindex"),d=isNaN(b);
return(d||b>=0)&&k(a,!d)}});c(function(){var a=document.body,b=a.appendChild(b=document.createElement("div"));c.extend(b.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0});c.support.minHeight=b.offsetHeight===100;c.support.selectstart="onselectstart"in b;a.removeChild(b).style.display="none"});c.extend(c.ui,{plugin:{add:function(a,b,d){a=c.ui[a].prototype;for(var e in d){a.plugins[e]=a.plugins[e]||[];a.plugins[e].push([b,d[e]])}},call:function(a,b,d){if((b=a.plugins[b])&&a.element[0].parentNode)for(var e=
0;e<b.length;e++)a.options[b[e][0]]&&b[e][1].apply(a.element,d)}},contains:function(a,b){return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b)},hasScroll:function(a,b){if(c(a).css("overflow")==="hidden")return false;b=b&&b==="left"?"scrollLeft":"scrollTop";var d=false;if(a[b]>0)return true;a[b]=1;d=a[b]>0;a[b]=0;return d},isOverAxis:function(a,b,d){return a>b&&a<b+d},isOver:function(a,b,d,e,h,i){return c.ui.isOverAxis(a,d,h)&&c.ui.isOverAxis(b,e,i)}})}})(jQuery);
;/*!
 * jQuery UI Widget 1.8.13
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Widget
 */
(function(b,j){if(b.cleanData){var k=b.cleanData;b.cleanData=function(a){for(var c=0,d;(d=a[c])!=null;c++)b(d).triggerHandler("remove");k(a)}}else{var l=b.fn.remove;b.fn.remove=function(a,c){return this.each(function(){if(!c)if(!a||b.filter(a,[this]).length)b("*",this).add([this]).each(function(){b(this).triggerHandler("remove")});return l.call(b(this),a,c)})}}b.widget=function(a,c,d){var e=a.split(".")[0],f;a=a.split(".")[1];f=e+"-"+a;if(!d){d=c;c=b.Widget}b.expr[":"][f]=function(h){return!!b.data(h,
a)};b[e]=b[e]||{};b[e][a]=function(h,g){arguments.length&&this._createWidget(h,g)};c=new c;c.options=b.extend(true,{},c.options);b[e][a].prototype=b.extend(true,c,{namespace:e,widgetName:a,widgetEventPrefix:b[e][a].prototype.widgetEventPrefix||a,widgetBaseClass:f},d);b.widget.bridge(a,b[e][a])};b.widget.bridge=function(a,c){b.fn[a]=function(d){var e=typeof d==="string",f=Array.prototype.slice.call(arguments,1),h=this;d=!e&&f.length?b.extend.apply(null,[true,d].concat(f)):d;if(e&&d.charAt(0)==="_")return h;
e?this.each(function(){var g=b.data(this,a),i=g&&b.isFunction(g[d])?g[d].apply(g,f):g;if(i!==g&&i!==j){h=i;return false}}):this.each(function(){var g=b.data(this,a);g?g.option(d||{})._init():b.data(this,a,new c(d,this))});return h}};b.Widget=function(a,c){arguments.length&&this._createWidget(a,c)};b.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:false},_createWidget:function(a,c){b.data(c,this.widgetName,this);this.element=b(c);this.options=b.extend(true,{},this.options,
this._getCreateOptions(),a);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()});this._create();this._trigger("create");this._init()},_getCreateOptions:function(){return b.metadata&&b.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName);this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+"-disabled ui-state-disabled")},
widget:function(){return this.element},option:function(a,c){var d=a;if(arguments.length===0)return b.extend({},this.options);if(typeof a==="string"){if(c===j)return this.options[a];d={};d[a]=c}this._setOptions(d);return this},_setOptions:function(a){var c=this;b.each(a,function(d,e){c._setOption(d,e)});return this},_setOption:function(a,c){this.options[a]=c;if(a==="disabled")this.widget()[c?"addClass":"removeClass"](this.widgetBaseClass+"-disabled ui-state-disabled").attr("aria-disabled",c);return this},
enable:function(){return this._setOption("disabled",false)},disable:function(){return this._setOption("disabled",true)},_trigger:function(a,c,d){var e=this.options[a];c=b.Event(c);c.type=(a===this.widgetEventPrefix?a:this.widgetEventPrefix+a).toLowerCase();d=d||{};if(c.originalEvent){a=b.event.props.length;for(var f;a;){f=b.event.props[--a];c[f]=c.originalEvent[f]}}this.element.trigger(c,d);return!(b.isFunction(e)&&e.call(this.element[0],c,d)===false||c.isDefaultPrevented())}}})(jQuery);
;

/*!
 * jQuery UI Mouse 1.8.13
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Mouse
 *
 * Depends:
 *	jquery.ui.widget.js
 */
(function( $, undefined ) {

var mouseHandled = false;
$(document).mousedown(function(e) {
	mouseHandled = false;
});

$.widget("ui.mouse", {
	options: {
		cancel: ':input,option',
		distance: 1,
		delay: 0
	},
	_mouseInit: function() {
		var self = this;

		this.element
			.bind('mousedown.'+this.widgetName, function(event) {
				return self._mouseDown(event);
			})
			.bind('click.'+this.widgetName, function(event) {
				if (true === $.data(event.target, self.widgetName + '.preventClickEvent')) {
				    $.removeData(event.target, self.widgetName + '.preventClickEvent');
					event.stopImmediatePropagation();
					return false;
				}
			});

		this.started = false;
	},

	// TODO: make sure destroying one instance of mouse doesn't mess with
	// other instances of mouse
	_mouseDestroy: function() {
		this.element.unbind('.'+this.widgetName);
	},

	_mouseDown: function(event) {
		// don't let more than one widget handle mouseStart
    $(this.document || document).mousedown(function(e) {
      mouseHandled = false;
    });
		if(mouseHandled) {return};

		// we may have missed mouseup (out of window)
		(this._mouseStarted && this._mouseUp(event));

		this._mouseDownEvent = event;

		var self = this,
			btnIsLeft = (event.which == 1),
			elIsCancel = (typeof this.options.cancel == "string" ? $(event.target).parents().add(event.target).filter(this.options.cancel).length : false);
		if (!btnIsLeft || elIsCancel || !this._mouseCapture(event)) {
			return true;
		}

		this.mouseDelayMet = !this.options.delay;
		if (!this.mouseDelayMet) {
			this._mouseDelayTimer = setTimeout(function() {
				self.mouseDelayMet = true;
			}, this.options.delay);
		}

		if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
			this._mouseStarted = (this._mouseStart(event) !== false);
			if (!this._mouseStarted) {
				event.preventDefault();
				return true;
			}
		}

		// Click event may never have fired (Gecko & Opera)
		if (true === $.data(event.target, this.widgetName + '.preventClickEvent')) {
			$.removeData(event.target, this.widgetName + '.preventClickEvent');
		}

		// these delegates are required to keep context
		this._mouseMoveDelegate = function(event) {
			return self._mouseMove(event);
		};
		this._mouseUpDelegate = function(event) {
			return self._mouseUp(event);
		};
		$(this.document || document)
			.bind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
			.bind('mouseup.'+this.widgetName, this._mouseUpDelegate);

		event.preventDefault();

		mouseHandled = true;
		return true;
	},

	_mouseMove: function(event) {
		// IE mouseup check - mouseup happened when mouse was out of window
		if ($.browser.msie && !((this.document || document).documentMode >= 9) && !event.button) {
			return this._mouseUp(event);
		}

		if (this._mouseStarted) {
			this._mouseDrag(event);
			return event.preventDefault();
		}

		if (this._mouseDistanceMet(event) && this._mouseDelayMet(event)) {
			this._mouseStarted =
				(this._mouseStart(this._mouseDownEvent, event) !== false);
			(this._mouseStarted ? this._mouseDrag(event) : this._mouseUp(event));
		}

		return !this._mouseStarted;
	},

	_mouseUp: function(event) {
		$(this.document || document)
			.unbind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
			.unbind('mouseup.'+this.widgetName, this._mouseUpDelegate);

		if (this._mouseStarted) {
			this._mouseStarted = false;

			if (event.target == this._mouseDownEvent.target) {
			    $.data(event.target, this.widgetName + '.preventClickEvent', true);
			}

			this._mouseStop(event);
		}

		return false;
	},

	_mouseDistanceMet: function(event) {
		return (Math.max(
				Math.abs(this._mouseDownEvent.pageX - event.pageX),
				Math.abs(this._mouseDownEvent.pageY - event.pageY)
			) >= this.options.distance
		);
	},

	_mouseDelayMet: function(event) {
		return this.mouseDelayMet;
	},

	// These are placeholder methods, to be overriden by extending plugin
	_mouseStart: function(event) {},
	_mouseDrag: function(event) {},
	_mouseStop: function(event) {},
	_mouseCapture: function(event) { return true; }
});

})(jQuery);

/*
 * jQuery UI Draggable 1.8.13
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Draggables
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.mouse.js
 *	jquery.ui.widget.js
 */
(function(d){d.widget("ui.draggable",d.ui.mouse,{widgetEventPrefix:"drag",options:{addClasses:true,appendTo:"parent",axis:false,connectToSortable:false,containment:false,cursor:"auto",cursorAt:false,grid:false,handle:false,helper:"original",iframeFix:false,opacity:false,refreshPositions:false,revert:false,revertDuration:500,scope:"default",scroll:true,scrollSensitivity:20,scrollSpeed:20,snap:false,snapMode:"both",snapTolerance:20,stack:false,zIndex:false},_create:function(){if(this.options.helper==
"original"&&!/^(?:r|a|f)/.test(this.element.css("position")))this.element[0].style.position="relative";this.options.addClasses&&this.element.addClass("ui-draggable");this.options.disabled&&this.element.addClass("ui-draggable-disabled");this._mouseInit()},destroy:function(){if(this.element.data("draggable")){this.element.removeData("draggable").unbind(".draggable").removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled");this._mouseDestroy();return this}},_mouseCapture:function(a){var b=
this.options;if(this.helper||b.disabled||d(a.target).is(".ui-resizable-handle"))return false;this.handle=this._getHandle(a);if(!this.handle)return false;d(b.iframeFix===true?"iframe":b.iframeFix).each(function(){d('<div class="ui-draggable-iframeFix" style="background: #fff;"></div>').css({width:this.offsetWidth+"px",height:this.offsetHeight+"px",position:"absolute",opacity:"0.001",zIndex:1E3}).css(d(this).offset()).appendTo("body")});return true},_mouseStart:function(a){var b=this.options;this.helper=
this._createHelper(a);this._cacheHelperProportions();if(d.ui.ddmanager)d.ui.ddmanager.current=this;this._cacheMargins();this.cssPosition=this.helper.css("position");this.scrollParent=this.helper.scrollParent();this.offset=this.positionAbs=this.element.offset();this.offset={top:this.offset.top-this.margins.top,left:this.offset.left-this.margins.left};d.extend(this.offset,{click:{left:a.pageX-this.offset.left,top:a.pageY-this.offset.top},parent:this._getParentOffset(),relative:this._getRelativeOffset()});
this.originalPosition=this.position=this._generatePosition(a);this.originalPageX=a.pageX;this.originalPageY=a.pageY;b.cursorAt&&this._adjustOffsetFromHelper(b.cursorAt);b.containment&&this._setContainment();if(this._trigger("start",a)===false){this._clear();return false}this._cacheHelperProportions();d.ui.ddmanager&&!b.dropBehaviour&&d.ui.ddmanager.prepareOffsets(this,a);this.helper.addClass("ui-draggable-dragging");this._mouseDrag(a,true);return true},_mouseDrag:function(a,b){this.position=this._generatePosition(a);
this.positionAbs=this._convertPositionTo("absolute");if(!b){b=this._uiHash();if(this._trigger("drag",a,b)===false){this._mouseUp({});return false}this.position=b.position}if(!this.options.axis||this.options.axis!="y")this.helper[0].style.left=this.position.left+"px";if(!this.options.axis||this.options.axis!="x")this.helper[0].style.top=this.position.top+"px";d.ui.ddmanager&&d.ui.ddmanager.drag(this,a);return false},_mouseStop:function(a){var b=false;if(d.ui.ddmanager&&!this.options.dropBehaviour)b=
d.ui.ddmanager.drop(this,a);if(this.dropped){b=this.dropped;this.dropped=false}if((!this.element[0]||!this.element[0].parentNode)&&this.options.helper=="original")return false;if(this.options.revert=="invalid"&&!b||this.options.revert=="valid"&&b||this.options.revert===true||d.isFunction(this.options.revert)&&this.options.revert.call(this.element,b)){var c=this;d(this.helper).animate(this.originalPosition,parseInt(this.options.revertDuration,10),function(){c._trigger("stop",a)!==false&&c._clear()})}else this._trigger("stop",
a)!==false&&this._clear();return false},_mouseUp:function(a){this.options.iframeFix===true&&d("div.ui-draggable-iframeFix").each(function(){this.parentNode.removeChild(this)});return d.ui.mouse.prototype._mouseUp.call(this,a)},cancel:function(){this.helper.is(".ui-draggable-dragging")?this._mouseUp({}):this._clear();return this},_getHandle:function(a){var b=!this.options.handle||!d(this.options.handle,this.element).length?true:false;d(this.options.handle,this.element).find("*").andSelf().each(function(){if(this==
a.target)b=true});return b},_createHelper:function(a){var b=this.options;a=d.isFunction(b.helper)?d(b.helper.apply(this.element[0],[a])):b.helper=="clone"?this.element.clone().removeAttr("id"):this.element;a.parents("body").length||a.appendTo(b.appendTo=="parent"?this.element[0].parentNode:b.appendTo);a[0]!=this.element[0]&&!/(fixed|absolute)/.test(a.css("position"))&&a.css("position","absolute");return a},_adjustOffsetFromHelper:function(a){if(typeof a=="string")a=a.split(" ");if(d.isArray(a))a=
{left:+a[0],top:+a[1]||0};if("left"in a)this.offset.click.left=a.left+this.margins.left;if("right"in a)this.offset.click.left=this.helperProportions.width-a.right+this.margins.left;if("top"in a)this.offset.click.top=a.top+this.margins.top;if("bottom"in a)this.offset.click.top=this.helperProportions.height-a.bottom+this.margins.top},_getParentOffset:function(){this.offsetParent=this.helper.offsetParent();var a=this.offsetParent.offset();if(this.cssPosition=="absolute"&&this.scrollParent[0]!=document&&
d.ui.contains(this.scrollParent[0],this.offsetParent[0])){a.left+=this.scrollParent.scrollLeft();a.top+=this.scrollParent.scrollTop()}if(this.offsetParent[0]==document.body||this.offsetParent[0].tagName&&this.offsetParent[0].tagName.toLowerCase()=="html"&&d.browser.msie)a={top:0,left:0};return{top:a.top+(parseInt(this.offsetParent.css("borderTopWidth"),10)||0),left:a.left+(parseInt(this.offsetParent.css("borderLeftWidth"),10)||0)}},_getRelativeOffset:function(){if(this.cssPosition=="relative"){var a=
this.element.position();return{top:a.top-(parseInt(this.helper.css("top"),10)||0)+this.scrollParent.scrollTop(),left:a.left-(parseInt(this.helper.css("left"),10)||0)+this.scrollParent.scrollLeft()}}else return{top:0,left:0}},_cacheMargins:function(){this.margins={left:parseInt(this.element.css("marginLeft"),10)||0,top:parseInt(this.element.css("marginTop"),10)||0,right:parseInt(this.element.css("marginRight"),10)||0,bottom:parseInt(this.element.css("marginBottom"),10)||0}},_cacheHelperProportions:function(){this.helperProportions=
{width:this.helper.outerWidth(),height:this.helper.outerHeight()}},_setContainment:function(){var a=this.options;if(a.containment=="parent")a.containment=this.helper[0].parentNode;if(a.containment=="document"||a.containment=="window")this.containment=[(a.containment=="document"?0:d(window).scrollLeft())-this.offset.relative.left-this.offset.parent.left,(a.containment=="document"?0:d(window).scrollTop())-this.offset.relative.top-this.offset.parent.top,(a.containment=="document"?0:d(window).scrollLeft())+
d(a.containment=="document"?document:window).width()-this.helperProportions.width-this.margins.left,(a.containment=="document"?0:d(window).scrollTop())+(d(a.containment=="document"?document:window).height()||document.body.parentNode.scrollHeight)-this.helperProportions.height-this.margins.top];if(!/^(document|window|parent)$/.test(a.containment)&&a.containment.constructor!=Array){a=d(a.containment);var b=a[0];if(b){a.offset();var c=d(b).css("overflow")!="hidden";this.containment=[(parseInt(d(b).css("borderLeftWidth"),
10)||0)+(parseInt(d(b).css("paddingLeft"),10)||0),(parseInt(d(b).css("borderTopWidth"),10)||0)+(parseInt(d(b).css("paddingTop"),10)||0),(c?Math.max(b.scrollWidth,b.offsetWidth):b.offsetWidth)-(parseInt(d(b).css("borderLeftWidth"),10)||0)-(parseInt(d(b).css("paddingRight"),10)||0)-this.helperProportions.width-this.margins.left-this.margins.right,(c?Math.max(b.scrollHeight,b.offsetHeight):b.offsetHeight)-(parseInt(d(b).css("borderTopWidth"),10)||0)-(parseInt(d(b).css("paddingBottom"),10)||0)-this.helperProportions.height-
this.margins.top-this.margins.bottom];this.relative_container=a}}else if(a.containment.constructor==Array)this.containment=a.containment},_convertPositionTo:function(a,b){if(!b)b=this.position;a=a=="absolute"?1:-1;var c=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&d.ui.contains(this.scrollParent[0],this.offsetParent[0]))?this.offsetParent:this.scrollParent,f=/(html|body)/i.test(c[0].tagName);return{top:b.top+this.offset.relative.top*a+this.offset.parent.top*a-(d.browser.safari&&
d.browser.version<526&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollTop():f?0:c.scrollTop())*a),left:b.left+this.offset.relative.left*a+this.offset.parent.left*a-(d.browser.safari&&d.browser.version<526&&this.cssPosition=="fixed"?0:(this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():f?0:c.scrollLeft())*a)}},_generatePosition:function(a){var b=this.options,c=this.cssPosition=="absolute"&&!(this.scrollParent[0]!=document&&d.ui.contains(this.scrollParent[0],
this.offsetParent[0]))?this.offsetParent:this.scrollParent,f=/(html|body)/i.test(c[0].tagName),e=a.pageX,h=a.pageY;if(this.originalPosition){var g;if(this.containment){if(this.relative_container){g=this.relative_container.offset();g=[this.containment[0]+g.left,this.containment[1]+g.top,this.containment[2]+g.left,this.containment[3]+g.top]}else g=this.containment;if(a.pageX-this.offset.click.left<g[0])e=g[0]+this.offset.click.left;if(a.pageY-this.offset.click.top<g[1])h=g[1]+this.offset.click.top;
if(a.pageX-this.offset.click.left>g[2])e=g[2]+this.offset.click.left;if(a.pageY-this.offset.click.top>g[3])h=g[3]+this.offset.click.top}if(b.grid){h=this.originalPageY+Math.round((h-this.originalPageY)/b.grid[1])*b.grid[1];h=g?!(h-this.offset.click.top<g[1]||h-this.offset.click.top>g[3])?h:!(h-this.offset.click.top<g[1])?h-b.grid[1]:h+b.grid[1]:h;e=this.originalPageX+Math.round((e-this.originalPageX)/b.grid[0])*b.grid[0];e=g?!(e-this.offset.click.left<g[0]||e-this.offset.click.left>g[2])?e:!(e-this.offset.click.left<
g[0])?e-b.grid[0]:e+b.grid[0]:e}}return{top:h-this.offset.click.top-this.offset.relative.top-this.offset.parent.top+(d.browser.safari&&d.browser.version<526&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollTop():f?0:c.scrollTop()),left:e-this.offset.click.left-this.offset.relative.left-this.offset.parent.left+(d.browser.safari&&d.browser.version<526&&this.cssPosition=="fixed"?0:this.cssPosition=="fixed"?-this.scrollParent.scrollLeft():f?0:c.scrollLeft())}},_clear:function(){this.helper.removeClass("ui-draggable-dragging");
this.helper[0]!=this.element[0]&&!this.cancelHelperRemoval&&this.helper.remove();this.helper=null;this.cancelHelperRemoval=false},_trigger:function(a,b,c){c=c||this._uiHash();d.ui.plugin.call(this,a,[b,c]);if(a=="drag")this.positionAbs=this._convertPositionTo("absolute");return d.Widget.prototype._trigger.call(this,a,b,c)},plugins:{},_uiHash:function(){return{helper:this.helper,position:this.position,originalPosition:this.originalPosition,offset:this.positionAbs}}});d.extend(d.ui.draggable,{version:"1.8.13"});
d.ui.plugin.add("draggable","connectToSortable",{start:function(a,b){var c=d(this).data("draggable"),f=c.options,e=d.extend({},b,{item:c.element});c.sortables=[];d(f.connectToSortable).each(function(){var h=d.data(this,"sortable");if(h&&!h.options.disabled){c.sortables.push({instance:h,shouldRevert:h.options.revert});h.refreshPositions();h._trigger("activate",a,e)}})},stop:function(a,b){var c=d(this).data("draggable"),f=d.extend({},b,{item:c.element});d.each(c.sortables,function(){if(this.instance.isOver){this.instance.isOver=
0;c.cancelHelperRemoval=true;this.instance.cancelHelperRemoval=false;if(this.shouldRevert)this.instance.options.revert=true;this.instance._mouseStop(a);this.instance.options.helper=this.instance.options._helper;c.options.helper=="original"&&this.instance.currentItem.css({top:"auto",left:"auto"})}else{this.instance.cancelHelperRemoval=false;this.instance._trigger("deactivate",a,f)}})},drag:function(a,b){var c=d(this).data("draggable"),f=this;d.each(c.sortables,function(){this.instance.positionAbs=
c.positionAbs;this.instance.helperProportions=c.helperProportions;this.instance.offset.click=c.offset.click;if(this.instance._intersectsWith(this.instance.containerCache)){if(!this.instance.isOver){this.instance.isOver=1;this.instance.currentItem=d(f).clone().removeAttr("id").appendTo(this.instance.element).data("sortable-item",true);this.instance.options._helper=this.instance.options.helper;this.instance.options.helper=function(){return b.helper[0]};a.target=this.instance.currentItem[0];this.instance._mouseCapture(a,
true);this.instance._mouseStart(a,true,true);this.instance.offset.click.top=c.offset.click.top;this.instance.offset.click.left=c.offset.click.left;this.instance.offset.parent.left-=c.offset.parent.left-this.instance.offset.parent.left;this.instance.offset.parent.top-=c.offset.parent.top-this.instance.offset.parent.top;c._trigger("toSortable",a);c.dropped=this.instance.element;c.currentItem=c.element;this.instance.fromOutside=c}this.instance.currentItem&&this.instance._mouseDrag(a)}else if(this.instance.isOver){this.instance.isOver=
0;this.instance.cancelHelperRemoval=true;this.instance.options.revert=false;this.instance._trigger("out",a,this.instance._uiHash(this.instance));this.instance._mouseStop(a,true);this.instance.options.helper=this.instance.options._helper;this.instance.currentItem.remove();this.instance.placeholder&&this.instance.placeholder.remove();c._trigger("fromSortable",a);c.dropped=false}})}});d.ui.plugin.add("draggable","cursor",{start:function(){var a=d("body"),b=d(this).data("draggable").options;if(a.css("cursor"))b._cursor=
a.css("cursor");a.css("cursor",b.cursor)},stop:function(){var a=d(this).data("draggable").options;a._cursor&&d("body").css("cursor",a._cursor)}});d.ui.plugin.add("draggable","opacity",{start:function(a,b){a=d(b.helper);b=d(this).data("draggable").options;if(a.css("opacity"))b._opacity=a.css("opacity");a.css("opacity",b.opacity)},stop:function(a,b){a=d(this).data("draggable").options;a._opacity&&d(b.helper).css("opacity",a._opacity)}});d.ui.plugin.add("draggable","scroll",{start:function(){var a=d(this).data("draggable");
if(a.scrollParent[0]!=document&&a.scrollParent[0].tagName!="HTML")a.overflowOffset=a.scrollParent.offset()},drag:function(a){var b=d(this).data("draggable"),c=b.options,f=false;if(b.scrollParent[0]!=document&&b.scrollParent[0].tagName!="HTML"){if(!c.axis||c.axis!="x")if(b.overflowOffset.top+b.scrollParent[0].offsetHeight-a.pageY<c.scrollSensitivity)b.scrollParent[0].scrollTop=f=b.scrollParent[0].scrollTop+c.scrollSpeed;else if(a.pageY-b.overflowOffset.top<c.scrollSensitivity)b.scrollParent[0].scrollTop=
f=b.scrollParent[0].scrollTop-c.scrollSpeed;if(!c.axis||c.axis!="y")if(b.overflowOffset.left+b.scrollParent[0].offsetWidth-a.pageX<c.scrollSensitivity)b.scrollParent[0].scrollLeft=f=b.scrollParent[0].scrollLeft+c.scrollSpeed;else if(a.pageX-b.overflowOffset.left<c.scrollSensitivity)b.scrollParent[0].scrollLeft=f=b.scrollParent[0].scrollLeft-c.scrollSpeed}else{if(!c.axis||c.axis!="x")if(a.pageY-d(document).scrollTop()<c.scrollSensitivity)f=d(document).scrollTop(d(document).scrollTop()-c.scrollSpeed);
else if(d(window).height()-(a.pageY-d(document).scrollTop())<c.scrollSensitivity)f=d(document).scrollTop(d(document).scrollTop()+c.scrollSpeed);if(!c.axis||c.axis!="y")if(a.pageX-d(document).scrollLeft()<c.scrollSensitivity)f=d(document).scrollLeft(d(document).scrollLeft()-c.scrollSpeed);else if(d(window).width()-(a.pageX-d(document).scrollLeft())<c.scrollSensitivity)f=d(document).scrollLeft(d(document).scrollLeft()+c.scrollSpeed)}f!==false&&d.ui.ddmanager&&!c.dropBehaviour&&d.ui.ddmanager.prepareOffsets(b,
a)}});d.ui.plugin.add("draggable","snap",{start:function(){var a=d(this).data("draggable"),b=a.options;a.snapElements=[];d(b.snap.constructor!=String?b.snap.items||":data(draggable)":b.snap).each(function(){var c=d(this),f=c.offset();this!=a.element[0]&&a.snapElements.push({item:this,width:c.outerWidth(),height:c.outerHeight(),top:f.top,left:f.left})})},drag:function(a,b){for(var c=d(this).data("draggable"),f=c.options,e=f.snapTolerance,h=b.offset.left,g=h+c.helperProportions.width,n=b.offset.top,
o=n+c.helperProportions.height,i=c.snapElements.length-1;i>=0;i--){var j=c.snapElements[i].left,l=j+c.snapElements[i].width,k=c.snapElements[i].top,m=k+c.snapElements[i].height;if(j-e<h&&h<l+e&&k-e<n&&n<m+e||j-e<h&&h<l+e&&k-e<o&&o<m+e||j-e<g&&g<l+e&&k-e<n&&n<m+e||j-e<g&&g<l+e&&k-e<o&&o<m+e){if(f.snapMode!="inner"){var p=Math.abs(k-o)<=e,q=Math.abs(m-n)<=e,r=Math.abs(j-g)<=e,s=Math.abs(l-h)<=e;if(p)b.position.top=c._convertPositionTo("relative",{top:k-c.helperProportions.height,left:0}).top-c.margins.top;
if(q)b.position.top=c._convertPositionTo("relative",{top:m,left:0}).top-c.margins.top;if(r)b.position.left=c._convertPositionTo("relative",{top:0,left:j-c.helperProportions.width}).left-c.margins.left;if(s)b.position.left=c._convertPositionTo("relative",{top:0,left:l}).left-c.margins.left}var t=p||q||r||s;if(f.snapMode!="outer"){p=Math.abs(k-n)<=e;q=Math.abs(m-o)<=e;r=Math.abs(j-h)<=e;s=Math.abs(l-g)<=e;if(p)b.position.top=c._convertPositionTo("relative",{top:k,left:0}).top-c.margins.top;if(q)b.position.top=
c._convertPositionTo("relative",{top:m-c.helperProportions.height,left:0}).top-c.margins.top;if(r)b.position.left=c._convertPositionTo("relative",{top:0,left:j}).left-c.margins.left;if(s)b.position.left=c._convertPositionTo("relative",{top:0,left:l-c.helperProportions.width}).left-c.margins.left}if(!c.snapElements[i].snapping&&(p||q||r||s||t))c.options.snap.snap&&c.options.snap.snap.call(c.element,a,d.extend(c._uiHash(),{snapItem:c.snapElements[i].item}));c.snapElements[i].snapping=p||q||r||s||t}else{c.snapElements[i].snapping&&
c.options.snap.release&&c.options.snap.release.call(c.element,a,d.extend(c._uiHash(),{snapItem:c.snapElements[i].item}));c.snapElements[i].snapping=false}}}});d.ui.plugin.add("draggable","stack",{start:function(){var a=d(this).data("draggable").options;a=d.makeArray(d(a.stack)).sort(function(c,f){return(parseInt(d(c).css("zIndex"),10)||0)-(parseInt(d(f).css("zIndex"),10)||0)});if(a.length){var b=parseInt(a[0].style.zIndex)||0;d(a).each(function(c){this.style.zIndex=b+c});this[0].style.zIndex=b+a.length}}});
d.ui.plugin.add("draggable","zIndex",{start:function(a,b){a=d(b.helper);b=d(this).data("draggable").options;if(a.css("zIndex"))b._zIndex=a.css("zIndex");a.css("zIndex",b.zIndex)},stop:function(a,b){a=d(this).data("draggable").options;a._zIndex&&d(b.helper).css("zIndex",a._zIndex)}})})(jQuery);
;

/*
 * jQuery UI Sortable 1.8.13
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Sortables
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.mouse.js
 *	jquery.ui.widget.js
 */
(function( $, undefined ) {

$.widget("ui.sortable", $.ui.mouse, {
	widgetEventPrefix: "sort",
	options: {
		appendTo: "parent",
		axis: false,
		connectWith: false,
		containment: false,
		cursor: 'auto',
		cursorAt: false,
		dropOnEmpty: true,
		forcePlaceholderSize: false,
		forceHelperSize: false,
		grid: false,
		handle: false,
		helper: "original",
		items: '> *',
		opacity: false,
		placeholder: false,
		revert: false,
		scroll: true,
		scrollSensitivity: 20,
		scrollSpeed: 20,
		scope: "default",
		tolerance: "intersect",
		zIndex: 1000
	},
	_create: function() {

		var o = this.options;
    this.document = o.document;

		this.containerCache = {};
		this.element.addClass("ui-sortable");

		//Get the items
		this.refresh();

		//Let's determine if the items are being displayed horizontally
		this.floating = this.items.length ? o.axis === 'x' || (/left|right/).test(this.items[0].item.css('float')) || (/inline|table-cell/).test(this.items[0].item.css('display')) : false;

		//Let's determine the parent's offset
		this.offset = this.element.offset();

		//Initialize mouse events for interaction
		this._mouseInit();

	},

	destroy: function() {
		this.element
			.removeClass("ui-sortable ui-sortable-disabled")
			.removeData("sortable")
			.unbind(".sortable");
		this._mouseDestroy();

		for ( var i = this.items.length - 1; i >= 0; i-- )
			this.items[i].item.removeData("sortable-item");

		return this;
	},

	_setOption: function(key, value){
		if ( key === "disabled" ) {
			this.options[ key ] = value;

			this.widget()
				[ value ? "addClass" : "removeClass"]( "ui-sortable-disabled" );
		} else {
			// Don't call widget base _setOption for disable as it adds ui-state-disabled class
			$.Widget.prototype._setOption.apply(this, arguments);
		}
	},

	_mouseCapture: function(event, overrideHandle) {

		if (this.reverting) {
			return false;
		}

		if(this.options.disabled || this.options.type == 'static') return false;

		//We have to refresh the items data once first
		this._refreshItems(event);

		//Find out if the clicked node (or one of its parents) is a actual item in this.items
		var currentItem = null, self = this, nodes = $(event.target).parents().each(function() {
			if($.data(this, 'sortable-item') == self) {
				currentItem = $(this);
				return false;
			}
		});
		if($.data(event.target, 'sortable-item') == self) currentItem = $(event.target);

		if(!currentItem) return false;
		if(this.options.handle && !overrideHandle) {
			var validHandle = false;

			$(this.options.handle, currentItem).find("*").andSelf().each(function() { if(this == event.target) validHandle = true; });
			if(!validHandle) return false;
		}

		this.currentItem = currentItem;
		this._removeCurrentsFromItems();
		return true;

	},

	_mouseStart: function(event, overrideHandle, noActivation) {
		var o = this.options, self = this;
		this.currentContainer = this;

		//We only need to call refreshPositions, because the refreshItems call has been moved to mouseCapture
		this.refreshPositions();

		//Create and append the visible helper
		this.helper = this._createHelper(event);

		//Cache the helper size
		this._cacheHelperProportions();

		/*
		 * - Position generation -
		 * This block generates everything position related - it's the core of draggables.
		 */

		//Cache the margins of the original element
		this._cacheMargins();

		//Get the next scrolling parent
		this.scrollParent = this.helper.scrollParent();

		//The element's absolute position on the page minus margins
		this.offset = this.currentItem.offset();
		this.offset = {
			top: this.offset.top - this.margins.top,
			left: this.offset.left - this.margins.left
		};

		// Only after we got the offset, we can change the helper's position to absolute
		// TODO: Still need to figure out a way to make relative sorting possible
		this.helper.css("position", "absolute");
		this.cssPosition = this.helper.css("position");

		$.extend(this.offset, {
			click: { //Where the click happened, relative to the element
				left: event.pageX - this.offset.left,
				top: event.pageY - this.offset.top
			},
			parent: this._getParentOffset(),
			relative: this._getRelativeOffset() //This is a relative to absolute position minus the actual position calculation - only used for relative positioned helper
		});

		//Generate the original position
		this.originalPosition = this._generatePosition(event);
		this.originalPageX = event.pageX;
		this.originalPageY = event.pageY;

		//Adjust the mouse offset relative to the helper if 'cursorAt' is supplied
		(o.cursorAt && this._adjustOffsetFromHelper(o.cursorAt));

		//Cache the former DOM position
		this.domPosition = { prev: this.currentItem.prev()[0], parent: this.currentItem.parent()[0] };

		//If the helper is not the original, hide the original so it's not playing any role during the drag, won't cause anything bad this way
		if(this.helper[0] != this.currentItem[0]) {
			this.currentItem.hide();
		}

		//Create the placeholder
		this._createPlaceholder();

		//Set a containment if given in the options
		if(o.containment)
			this._setContainment();

		if(o.cursor) { // cursor option
			if ($('body').css("cursor")) this._storedCursor = $('body').css("cursor");
			$('body').css("cursor", o.cursor);
		}

		if(o.opacity) { // opacity option
			if (this.helper.css("opacity")) this._storedOpacity = this.helper.css("opacity");
			this.helper.css("opacity", o.opacity);
		}

		if(o.zIndex) { // zIndex option
			if (this.helper.css("zIndex")) this._storedZIndex = this.helper.css("zIndex");
			this.helper.css("zIndex", o.zIndex);
		}

		//Prepare scrolling
		if(this.scrollParent[0] != (this.document || document) && this.scrollParent[0].tagName != 'HTML')
			this.overflowOffset = this.scrollParent.offset();

		//Call callbacks
		this._trigger("start", event, this._uiHash());

		//Recache the helper size
		if(!this._preserveHelperProportions)
			this._cacheHelperProportions();


		//Post 'activate' events to possible containers
		if(!noActivation) {
			 for (var i = this.containers.length - 1; i >= 0; i--) { this.containers[i]._trigger("activate", event, self._uiHash(this)); }
		}

		//Prepare possible droppables
		if($.ui.ddmanager)
			$.ui.ddmanager.current = this;

		if ($.ui.ddmanager && !o.dropBehaviour)
			$.ui.ddmanager.prepareOffsets(this, event);

		this.dragging = true;

		this.helper.addClass("ui-sortable-helper");
		this._mouseDrag(event); //Execute the drag once - this causes the helper not to be visible before getting its correct position
		return true;

	},

	_mouseDrag: function(event) {

		//Compute the helpers position
		this.position = this._generatePosition(event);
		this.positionAbs = this._convertPositionTo("absolute");

		if (!this.lastPositionAbs) {
			this.lastPositionAbs = this.positionAbs;
		}

		//Do scrolling
		if(this.options.scroll) {
			var o = this.options, scrolled = false;
			if(this.scrollParent[0] != (this.document || document) && this.scrollParent[0].tagName != 'HTML') {

				if((this.overflowOffset.top + this.scrollParent[0].offsetHeight) - event.pageY < o.scrollSensitivity)
					this.scrollParent[0].scrollTop = scrolled = this.scrollParent[0].scrollTop + o.scrollSpeed;
				else if(event.pageY - this.overflowOffset.top < o.scrollSensitivity)
					this.scrollParent[0].scrollTop = scrolled = this.scrollParent[0].scrollTop - o.scrollSpeed;

				if((this.overflowOffset.left + this.scrollParent[0].offsetWidth) - event.pageX < o.scrollSensitivity)
					this.scrollParent[0].scrollLeft = scrolled = this.scrollParent[0].scrollLeft + o.scrollSpeed;
				else if(event.pageX - this.overflowOffset.left < o.scrollSensitivity)
					this.scrollParent[0].scrollLeft = scrolled = this.scrollParent[0].scrollLeft - o.scrollSpeed;

			} else {

				if(event.pageY - $((this.document || document)).scrollTop() < o.scrollSensitivity)
					scrolled = $((this.document || document)).scrollTop($((this.document || document)).scrollTop() - o.scrollSpeed);
				else if($(window).height() - (event.pageY - $((this.document || document)).scrollTop()) < o.scrollSensitivity)
					scrolled = $((this.document || document)).scrollTop($((this.document || document)).scrollTop() + o.scrollSpeed);

				if(event.pageX - $((this.document || document)).scrollLeft() < o.scrollSensitivity)
					scrolled = $((this.document || document)).scrollLeft($((this.document || document)).scrollLeft() - o.scrollSpeed);
				else if($(window).width() - (event.pageX - $((this.document || document)).scrollLeft()) < o.scrollSensitivity)
					scrolled = $((this.document || document)).scrollLeft($((this.document || document)).scrollLeft() + o.scrollSpeed);

			}

			if(scrolled !== false && $.ui.ddmanager && !o.dropBehaviour)
				$.ui.ddmanager.prepareOffsets(this, event);
		}

		//Regenerate the absolute position used for position checks
		this.positionAbs = this._convertPositionTo("absolute");

		//Set the helper position
		if(!this.options.axis || this.options.axis != "y") this.helper[0].style.left = this.position.left+'px';
		if(!this.options.axis || this.options.axis != "x") this.helper[0].style.top = this.position.top+'px';

		//Rearrange
		for (var i = this.items.length - 1; i >= 0; i--) {

			//Cache variables and intersection, continue if no intersection
			var item = this.items[i], itemElement = item.item[0], intersection = this._intersectsWithPointer(item);
			if (!intersection) continue;

			if(itemElement != this.currentItem[0] //cannot intersect with itself
				&&	this.placeholder[intersection == 1 ? "next" : "prev"]()[0] != itemElement //no useless actions that have been done before
				&&	!$.ui.contains(this.placeholder[0], itemElement) //no action if the item moved is the parent of the item checked
				&& (this.options.type == 'semi-dynamic' ? !$.ui.contains(this.element[0], itemElement) : true)
				//&& itemElement.parentNode == this.placeholder[0].parentNode // only rearrange items within the same container
			) {

				this.direction = intersection == 1 ? "down" : "up";

				if (this.options.tolerance == "pointer" || this._intersectsWithSides(item)) {
					this._rearrange(event, item);
				} else {
					break;
				}

				this._trigger("change", event, this._uiHash());
				break;
			}
		}

		//Post events to containers
		this._contactContainers(event);

		//Interconnect with droppables
		if($.ui.ddmanager) $.ui.ddmanager.drag(this, event);

		//Call callbacks
		this._trigger('sort', event, this._uiHash());

		this.lastPositionAbs = this.positionAbs;
		return false;

	},

	_mouseStop: function(event, noPropagation) {

		if(!event) return;

		//If we are using droppables, inform the manager about the drop
		if ($.ui.ddmanager && !this.options.dropBehaviour)
			$.ui.ddmanager.drop(this, event);

		if(this.options.revert) {
			var self = this;
			var cur = self.placeholder.offset();

			self.reverting = true;

			$(this.helper).animate({
				left: cur.left - this.offset.parent.left - self.margins.left + (this.offsetParent[0] == (this.document || document).body ? 0 : this.offsetParent[0].scrollLeft),
				top: cur.top - this.offset.parent.top - self.margins.top + (this.offsetParent[0] == (this.document || document).body ? 0 : this.offsetParent[0].scrollTop)
			}, parseInt(this.options.revert, 10) || 500, function() {
				self._clear(event);
			});
		} else {
			this._clear(event, noPropagation);
		}

		return false;

	},

	cancel: function() {

		var self = this;

		if(this.dragging) {

			this._mouseUp({ target: null });

			if(this.options.helper == "original")
				this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");
			else
				this.currentItem.show();

			//Post deactivating events to containers
			for (var i = this.containers.length - 1; i >= 0; i--){
				this.containers[i]._trigger("deactivate", null, self._uiHash(this));
				if(this.containers[i].containerCache.over) {
					this.containers[i]._trigger("out", null, self._uiHash(this));
					this.containers[i].containerCache.over = 0;
				}
			}

		}

		if (this.placeholder) {
			//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately, it unbinds ALL events from the original node!
			if(this.placeholder[0].parentNode) this.placeholder[0].parentNode.removeChild(this.placeholder[0]);
			if(this.options.helper != "original" && this.helper && this.helper[0].parentNode) this.helper.remove();

			$.extend(this, {
				helper: null,
				dragging: false,
				reverting: false,
				_noFinalSort: null
			});

			if(this.domPosition.prev) {
				$(this.domPosition.prev).after(this.currentItem);
			} else {
				$(this.domPosition.parent).prepend(this.currentItem);
			}
		}

		return this;

	},

	serialize: function(o) {

		var items = this._getItemsAsjQuery(o && o.connected);
		var str = []; o = o || {};

		$(items).each(function() {
			var res = ($(o.item || this).attr(o.attribute || 'id') || '').match(o.expression || (/(.+)[-=_](.+)/));
			if(res) str.push((o.key || res[1]+'[]')+'='+(o.key && o.expression ? res[1] : res[2]));
		});

		if(!str.length && o.key) {
			str.push(o.key + '=');
		}

		return str.join('&');

	},

	toArray: function(o) {

		var items = this._getItemsAsjQuery(o && o.connected);
		var ret = []; o = o || {};

		items.each(function() { ret.push($(o.item || this).attr(o.attribute || 'id') || ''); });
		return ret;

	},

	/* Be careful with the following core functions */
	_intersectsWith: function(item) {

		var x1 = this.positionAbs.left,
			x2 = x1 + this.helperProportions.width,
			y1 = this.positionAbs.top,
			y2 = y1 + this.helperProportions.height;

		var l = item.left,
			r = l + item.width,
			t = item.top,
			b = t + item.height;

		var dyClick = this.offset.click.top,
			dxClick = this.offset.click.left;

		var isOverElement = (y1 + dyClick) > t && (y1 + dyClick) < b && (x1 + dxClick) > l && (x1 + dxClick) < r;

		if(	   this.options.tolerance == "pointer"
			|| this.options.forcePointerForContainers
			|| (this.options.tolerance != "pointer" && this.helperProportions[this.floating ? 'width' : 'height'] > item[this.floating ? 'width' : 'height'])
		) {
			return isOverElement;
		} else {

			return (l < x1 + (this.helperProportions.width / 2) // Right Half
				&& x2 - (this.helperProportions.width / 2) < r // Left Half
				&& t < y1 + (this.helperProportions.height / 2) // Bottom Half
				&& y2 - (this.helperProportions.height / 2) < b ); // Top Half

		}
	},

	_intersectsWithPointer: function(item) {

		var isOverElementHeight = $.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, item.top, item.height),
			isOverElementWidth = $.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, item.left, item.width),
			isOverElement = isOverElementHeight && isOverElementWidth,
			verticalDirection = this._getDragVerticalDirection(),
			horizontalDirection = this._getDragHorizontalDirection();

		if (!isOverElement)
			return false;

		return this.floating ?
			( ((horizontalDirection && horizontalDirection == "right") || verticalDirection == "down") ? 2 : 1 )
			: ( verticalDirection && (verticalDirection == "down" ? 2 : 1) );

	},

	_intersectsWithSides: function(item) {

		var isOverBottomHalf = $.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, item.top + (item.height/2), item.height),
			isOverRightHalf = $.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, item.left + (item.width/2), item.width),
			verticalDirection = this._getDragVerticalDirection(),
			horizontalDirection = this._getDragHorizontalDirection();

		if (this.floating && horizontalDirection) {
			return ((horizontalDirection == "right" && isOverRightHalf) || (horizontalDirection == "left" && !isOverRightHalf));
		} else {
			return verticalDirection && ((verticalDirection == "down" && isOverBottomHalf) || (verticalDirection == "up" && !isOverBottomHalf));
		}

	},

	_getDragVerticalDirection: function() {
		var delta = this.positionAbs.top - this.lastPositionAbs.top;
		return delta != 0 && (delta > 0 ? "down" : "up");
	},

	_getDragHorizontalDirection: function() {
		var delta = this.positionAbs.left - this.lastPositionAbs.left;
		return delta != 0 && (delta > 0 ? "right" : "left");
	},

	refresh: function(event) {
		this._refreshItems(event);
		this.refreshPositions();
		return this;
	},

	_connectWith: function() {
		var options = this.options;
		return options.connectWith.constructor == String
			? [options.connectWith]
			: options.connectWith;
	},

	_getItemsAsjQuery: function(connected) {

		var self = this;
		var items = [];
		var queries = [];
		var connectWith = this._connectWith();

		if(connectWith && connected) {
			for (var i = connectWith.length - 1; i >= 0; i--){
				var cur = $(connectWith[i]);
				for (var j = cur.length - 1; j >= 0; j--){
					var inst = $.data(cur[j], 'sortable');
					if(inst && inst != this && !inst.options.disabled) {
						queries.push([$.isFunction(inst.options.items) ? inst.options.items.call(inst.element) : $(inst.options.items, inst.element).not(".ui-sortable-helper").not('.ui-sortable-placeholder'), inst]);
					}
				};
			};
		}

		queries.push([$.isFunction(this.options.items) ? this.options.items.call(this.element, null, { options: this.options, item: this.currentItem }) : $(this.options.items, this.element).not(".ui-sortable-helper").not('.ui-sortable-placeholder'), this]);

		for (var i = queries.length - 1; i >= 0; i--){
			queries[i][0].each(function() {
				items.push(this);
			});
		};

		return $(items);

	},

	_removeCurrentsFromItems: function() {

		var list = this.currentItem.find(":data(sortable-item)");

		for (var i=0; i < this.items.length; i++) {

			for (var j=0; j < list.length; j++) {
				if(list[j] == this.items[i].item[0])
					this.items.splice(i,1);
			};

		};

	},

	_refreshItems: function(event) {

		this.items = [];
		this.containers = [this];
		var items = this.items;
		var self = this;
		var queries = [[$.isFunction(this.options.items) ? this.options.items.call(this.element[0], event, { item: this.currentItem }) : $(this.options.items, this.element), this]];
		var connectWith = this._connectWith();

		if(connectWith) {
			for (var i = connectWith.length - 1; i >= 0; i--){
				var cur = $(connectWith[i]);
				for (var j = cur.length - 1; j >= 0; j--){
					var inst = $.data(cur[j], 'sortable');
					if(inst && inst != this && !inst.options.disabled) {
						queries.push([$.isFunction(inst.options.items) ? inst.options.items.call(inst.element[0], event, { item: this.currentItem }) : $(inst.options.items, inst.element), inst]);
						this.containers.push(inst);
					}
				};
			};
		}

		for (var i = queries.length - 1; i >= 0; i--) {
			var targetData = queries[i][1];
			var _queries = queries[i][0];

			for (var j=0, queriesLength = _queries.length; j < queriesLength; j++) {
				var item = $(_queries[j]);

				item.data('sortable-item', targetData); // Data for target checking (mouse manager)

				items.push({
					item: item,
					instance: targetData,
					width: 0, height: 0,
					left: 0, top: 0
				});
			};
		};

	},

	refreshPositions: function(fast) {

		//This has to be redone because due to the item being moved out/into the offsetParent, the offsetParent's position will change
		if(this.offsetParent && this.helper) {
			this.offset.parent = this._getParentOffset();
		}

		for (var i = this.items.length - 1; i >= 0; i--){
			var item = this.items[i];

			//We ignore calculating positions of all connected containers when we're not over them
			if(item.instance != this.currentContainer && this.currentContainer && item.item[0] != this.currentItem[0])
				continue;

			var t = this.options.toleranceElement ? $(this.options.toleranceElement, item.item) : item.item;

			if (!fast) {
				item.width = t.outerWidth();
				item.height = t.outerHeight();
			}

			var p = t.offset();
			item.left = p.left;
			item.top = p.top;
		};

		if(this.options.custom && this.options.custom.refreshContainers) {
			this.options.custom.refreshContainers.call(this);
		} else {
			for (var i = this.containers.length - 1; i >= 0; i--){
				var p = this.containers[i].element.offset();
				this.containers[i].containerCache.left = p.left;
				this.containers[i].containerCache.top = p.top;
				this.containers[i].containerCache.width	= this.containers[i].element.outerWidth();
				this.containers[i].containerCache.height = this.containers[i].element.outerHeight();
			};
		}

		return this;
	},

	_createPlaceholder: function(that) {

		var self = that || this, o = self.options;

		if(!o.placeholder || o.placeholder.constructor == String) {
			var className = o.placeholder;
			o.placeholder = {
				element: function() {

					var el = $((this.document || document).createElement(self.currentItem[0].nodeName))
						.addClass(className || self.currentItem[0].className+" ui-sortable-placeholder")
						.removeClass("ui-sortable-helper")[0];

					if(!className)
						el.style.visibility = "hidden";

					return el;
				},
				update: function(container, p) {

					// 1. If a className is set as 'placeholder option, we don't force sizes - the class is responsible for that
					// 2. The option 'forcePlaceholderSize can be enabled to force it even if a class name is specified
					if(className && !o.forcePlaceholderSize) return;

					//If the element doesn't have a actual height by itself (without styles coming from a stylesheet), it receives the inline height from the dragged item
					if(!p.height()) { p.height(self.currentItem.innerHeight() - parseInt(self.currentItem.css('paddingTop')||0, 10) - parseInt(self.currentItem.css('paddingBottom')||0, 10)); };
					if(!p.width()) { p.width(self.currentItem.innerWidth() - parseInt(self.currentItem.css('paddingLeft')||0, 10) - parseInt(self.currentItem.css('paddingRight')||0, 10)); };
				}
			};
		}

		//Create the placeholder
		self.placeholder = $(o.placeholder.element.call(self.element, self.currentItem));

		//Append it after the actual current item
		self.currentItem.after(self.placeholder);

		//Update the size of the placeholder (TODO: Logic to fuzzy, see line 316/317)
		o.placeholder.update(self, self.placeholder);

	},

	_contactContainers: function(event) {

		// get innermost container that intersects with item
		var innermostContainer = null, innermostIndex = null;


		for (var i = this.containers.length - 1; i >= 0; i--){

			// never consider a container that's located within the item itself
			if($.ui.contains(this.currentItem[0], this.containers[i].element[0]))
				continue;

			if(this._intersectsWith(this.containers[i].containerCache)) {

				// if we've already found a container and it's more "inner" than this, then continue
				if(innermostContainer && $.ui.contains(this.containers[i].element[0], innermostContainer.element[0]))
					continue;

				innermostContainer = this.containers[i];
				innermostIndex = i;

			} else {
				// container doesn't intersect. trigger "out" event if necessary
				if(this.containers[i].containerCache.over) {
					this.containers[i]._trigger("out", event, this._uiHash(this));
					this.containers[i].containerCache.over = 0;
				}
			}

		}

		// if no intersecting containers found, return
		if(!innermostContainer) return;

		// move the item into the container if it's not there already
		if(this.containers.length === 1) {
			this.containers[innermostIndex]._trigger("over", event, this._uiHash(this));
			this.containers[innermostIndex].containerCache.over = 1;
		} else if(this.currentContainer != this.containers[innermostIndex]) {

			//When entering a new container, we will find the item with the least distance and append our item near it
			var dist = 10000; var itemWithLeastDistance = null; var base = this.positionAbs[this.containers[innermostIndex].floating ? 'left' : 'top'];
			for (var j = this.items.length - 1; j >= 0; j--) {
				if(!$.ui.contains(this.containers[innermostIndex].element[0], this.items[j].item[0])) continue;
				var cur = this.items[j][this.containers[innermostIndex].floating ? 'left' : 'top'];
				if(Math.abs(cur - base) < dist) {
					dist = Math.abs(cur - base); itemWithLeastDistance = this.items[j];
				}
			}

			if(!itemWithLeastDistance && !this.options.dropOnEmpty) //Check if dropOnEmpty is enabled
				return;

			this.currentContainer = this.containers[innermostIndex];
			itemWithLeastDistance ? this._rearrange(event, itemWithLeastDistance, null, true) : this._rearrange(event, null, this.containers[innermostIndex].element, true);
			this._trigger("change", event, this._uiHash());
			this.containers[innermostIndex]._trigger("change", event, this._uiHash(this));

			//Update the placeholder
			this.options.placeholder.update(this.currentContainer, this.placeholder);

			this.containers[innermostIndex]._trigger("over", event, this._uiHash(this));
			this.containers[innermostIndex].containerCache.over = 1;
		}


	},

	_createHelper: function(event) {

		var o = this.options;
		var helper = $.isFunction(o.helper) ? $(o.helper.apply(this.element[0], [event, this.currentItem])) : (o.helper == 'clone' ? this.currentItem.clone() : this.currentItem);

		if(!helper.parents('body').length) //Add the helper to the DOM if that didn't happen already
			$(o.appendTo != 'parent' ? o.appendTo : this.currentItem[0].parentNode)[0].appendChild(helper[0]);

		if(helper[0] == this.currentItem[0])
			this._storedCSS = { width: this.currentItem[0].style.width, height: this.currentItem[0].style.height, position: this.currentItem.css("position"), top: this.currentItem.css("top"), left: this.currentItem.css("left") };

		if(helper[0].style.width == '' || o.forceHelperSize) helper.width(this.currentItem.width());
		if(helper[0].style.height == '' || o.forceHelperSize) helper.height(this.currentItem.height());

		return helper;

	},

	_adjustOffsetFromHelper: function(obj) {
		if (typeof obj == 'string') {
			obj = obj.split(' ');
		}
		if ($.isArray(obj)) {
			obj = {left: +obj[0], top: +obj[1] || 0};
		}
		if ('left' in obj) {
			this.offset.click.left = obj.left + this.margins.left;
		}
		if ('right' in obj) {
			this.offset.click.left = this.helperProportions.width - obj.right + this.margins.left;
		}
		if ('top' in obj) {
			this.offset.click.top = obj.top + this.margins.top;
		}
		if ('bottom' in obj) {
			this.offset.click.top = this.helperProportions.height - obj.bottom + this.margins.top;
		}
	},

	_getParentOffset: function() {


		//Get the offsetParent and cache its position
		this.offsetParent = this.helper.offsetParent();
		var po = this.offsetParent.offset();

		// This is a special case where we need to modify a offset calculated on start, since the following happened:
		// 1. The position of the helper is absolute, so it's position is calculated based on the next positioned parent
		// 2. The actual offset parent is a child of the scroll parent, and the scroll parent isn't the document, which means that
		//    the scroll is included in the initial calculation of the offset of the parent, and never recalculated upon drag
		if(this.cssPosition == 'absolute' && this.scrollParent[0] != (this.document || document) && $.ui.contains(this.scrollParent[0], this.offsetParent[0])) {
			po.left += this.scrollParent.scrollLeft();
			po.top += this.scrollParent.scrollTop();
		}

		if((this.offsetParent[0] == (this.document || document).body) //This needs to be actually done for all browsers, since pageX/pageY includes this information
		|| (this.offsetParent[0].tagName && this.offsetParent[0].tagName.toLowerCase() == 'html' && $.browser.msie)) //Ugly IE fix
			po = { top: 0, left: 0 };

		return {
			top: po.top + (parseInt(this.offsetParent.css("borderTopWidth"),10) || 0),
			left: po.left + (parseInt(this.offsetParent.css("borderLeftWidth"),10) || 0)
		};

	},

	_getRelativeOffset: function() {

		if(this.cssPosition == "relative") {
			var p = this.currentItem.position();
			return {
				top: p.top - (parseInt(this.helper.css("top"),10) || 0) + this.scrollParent.scrollTop(),
				left: p.left - (parseInt(this.helper.css("left"),10) || 0) + this.scrollParent.scrollLeft()
			};
		} else {
			return { top: 0, left: 0 };
		}

	},

	_cacheMargins: function() {
		this.margins = {
			left: (parseInt(this.currentItem.css("marginLeft"),10) || 0),
			top: (parseInt(this.currentItem.css("marginTop"),10) || 0)
		};
	},

	_cacheHelperProportions: function() {
		this.helperProportions = {
			width: this.helper.outerWidth(),
			height: this.helper.outerHeight()
		};
	},

	_setContainment: function() {

		var o = this.options;
		if(o.containment == 'parent') o.containment = this.helper[0].parentNode;
		if(o.containment == 'document' || o.containment == 'window') this.containment = [
			0 - this.offset.relative.left - this.offset.parent.left,
			0 - this.offset.relative.top - this.offset.parent.top,
			$(o.containment == 'document' ? (this.document || document) : window).width() - this.helperProportions.width - this.margins.left,
			($(o.containment == 'document' ? (this.document || document) : window).height() || (this.document || document).body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top
		];

		if(!(/^(document|window|parent)$/).test(o.containment)) {
			var ce = $(o.containment)[0];
			var co = $(o.containment).offset();
			var over = ($(ce).css("overflow") != 'hidden');

			this.containment = [
				co.left + (parseInt($(ce).css("borderLeftWidth"),10) || 0) + (parseInt($(ce).css("paddingLeft"),10) || 0) - this.margins.left,
				co.top + (parseInt($(ce).css("borderTopWidth"),10) || 0) + (parseInt($(ce).css("paddingTop"),10) || 0) - this.margins.top,
				co.left+(over ? Math.max(ce.scrollWidth,ce.offsetWidth) : ce.offsetWidth) - (parseInt($(ce).css("borderLeftWidth"),10) || 0) - (parseInt($(ce).css("paddingRight"),10) || 0) - this.helperProportions.width - this.margins.left,
				co.top+(over ? Math.max(ce.scrollHeight,ce.offsetHeight) : ce.offsetHeight) - (parseInt($(ce).css("borderTopWidth"),10) || 0) - (parseInt($(ce).css("paddingBottom"),10) || 0) - this.helperProportions.height - this.margins.top
			];
		}

	},

	_convertPositionTo: function(d, pos) {

		if(!pos) pos = this.position;
		var mod = d == "absolute" ? 1 : -1;
		var o = this.options, scroll = this.cssPosition == 'absolute' && !(this.scrollParent[0] != (this.document || document) && $.ui.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent, scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);

		return {
			top: (
				pos.top																	// The absolute mouse position
				+ this.offset.relative.top * mod										// Only for relative positioned nodes: Relative offset from element to offset parent
				+ this.offset.parent.top * mod											// The offsetParent's offset without borders (offset + border)
				- ($.browser.safari && this.cssPosition == 'fixed' ? 0 : ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ) * mod)
			),
			left: (
				pos.left																// The absolute mouse position
				+ this.offset.relative.left * mod										// Only for relative positioned nodes: Relative offset from element to offset parent
				+ this.offset.parent.left * mod											// The offsetParent's offset without borders (offset + border)
				- ($.browser.safari && this.cssPosition == 'fixed' ? 0 : ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ) * mod)
			)
		};

	},

	_generatePosition: function(event) {

		var o = this.options, scroll = this.cssPosition == 'absolute' && !(this.scrollParent[0] != (this.document || document) && $.ui.contains(this.scrollParent[0], this.offsetParent[0])) ? this.offsetParent : this.scrollParent, scrollIsRootNode = (/(html|body)/i).test(scroll[0].tagName);

		// This is another very weird special case that only happens for relative elements:
		// 1. If the css position is relative
		// 2. and the scroll parent is the document or similar to the offset parent
		// we have to refresh the relative offset during the scroll so there are no jumps
		if(this.cssPosition == 'relative' && !(this.scrollParent[0] != (this.document || document) && this.scrollParent[0] != this.offsetParent[0])) {
			this.offset.relative = this._getRelativeOffset();
		}

		var pageX = event.pageX;
		var pageY = event.pageY;

		/*
		 * - Position constraining -
		 * Constrain the position to a mix of grid, containment.
		 */

		if(this.originalPosition) { //If we are not dragging yet, we won't check for options

			if(this.containment) {
				if(event.pageX - this.offset.click.left < this.containment[0]) pageX = this.containment[0] + this.offset.click.left;
				if(event.pageY - this.offset.click.top < this.containment[1]) pageY = this.containment[1] + this.offset.click.top;
				if(event.pageX - this.offset.click.left > this.containment[2]) pageX = this.containment[2] + this.offset.click.left;
				if(event.pageY - this.offset.click.top > this.containment[3]) pageY = this.containment[3] + this.offset.click.top;
			}

			if(o.grid) {
				var top = this.originalPageY + Math.round((pageY - this.originalPageY) / o.grid[1]) * o.grid[1];
				pageY = this.containment ? (!(top - this.offset.click.top < this.containment[1] || top - this.offset.click.top > this.containment[3]) ? top : (!(top - this.offset.click.top < this.containment[1]) ? top - o.grid[1] : top + o.grid[1])) : top;

				var left = this.originalPageX + Math.round((pageX - this.originalPageX) / o.grid[0]) * o.grid[0];
				pageX = this.containment ? (!(left - this.offset.click.left < this.containment[0] || left - this.offset.click.left > this.containment[2]) ? left : (!(left - this.offset.click.left < this.containment[0]) ? left - o.grid[0] : left + o.grid[0])) : left;
			}

		}

		return {
			top: (
				pageY																// The absolute mouse position
				- this.offset.click.top													// Click offset (relative to the element)
				- this.offset.relative.top												// Only for relative positioned nodes: Relative offset from element to offset parent
				- this.offset.parent.top												// The offsetParent's offset without borders (offset + border)
				+ ($.browser.safari && this.cssPosition == 'fixed' ? 0 : ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollTop() : ( scrollIsRootNode ? 0 : scroll.scrollTop() ) ))
			),
			left: (
				pageX																// The absolute mouse position
				- this.offset.click.left												// Click offset (relative to the element)
				- this.offset.relative.left												// Only for relative positioned nodes: Relative offset from element to offset parent
				- this.offset.parent.left												// The offsetParent's offset without borders (offset + border)
				+ ($.browser.safari && this.cssPosition == 'fixed' ? 0 : ( this.cssPosition == 'fixed' ? -this.scrollParent.scrollLeft() : scrollIsRootNode ? 0 : scroll.scrollLeft() ))
			)
		};

	},

	_rearrange: function(event, i, a, hardRefresh) {

		a ? a[0].appendChild(this.placeholder[0]) : i.item[0].parentNode.insertBefore(this.placeholder[0], (this.direction == 'down' ? i.item[0] : i.item[0].nextSibling));

		//Various things done here to improve the performance:
		// 1. we create a setTimeout, that calls refreshPositions
		// 2. on the instance, we have a counter variable, that get's higher after every append
		// 3. on the local scope, we copy the counter variable, and check in the timeout, if it's still the same
		// 4. this lets only the last addition to the timeout stack through
		this.counter = this.counter ? ++this.counter : 1;
		var self = this, counter = this.counter;

		window.setTimeout(function() {
			if(counter == self.counter) self.refreshPositions(!hardRefresh); //Precompute after each DOM insertion, NOT on mousemove
		},0);

	},

	_clear: function(event, noPropagation) {

		this.reverting = false;
		// We delay all events that have to be triggered to after the point where the placeholder has been removed and
		// everything else normalized again
		var delayedTriggers = [], self = this;

		// We first have to update the dom position of the actual currentItem
		// Note: don't do it if the current item is already removed (by a user), or it gets reappended (see #4088)
		if(!this._noFinalSort && this.currentItem[0].parentNode) this.placeholder.before(this.currentItem);
		this._noFinalSort = null;

		if(this.helper[0] == this.currentItem[0]) {
			for(var i in this._storedCSS) {
				if(this._storedCSS[i] == 'auto' || this._storedCSS[i] == 'static') this._storedCSS[i] = '';
			}
			this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper");
		} else {
			this.currentItem.show();
		}

		if(this.fromOutside && !noPropagation) delayedTriggers.push(function(event) { this._trigger("receive", event, this._uiHash(this.fromOutside)); });
		if((this.fromOutside || this.domPosition.prev != this.currentItem.prev().not(".ui-sortable-helper")[0] || this.domPosition.parent != this.currentItem.parent()[0]) && !noPropagation) delayedTriggers.push(function(event) { this._trigger("update", event, this._uiHash()); }); //Trigger update callback if the DOM position has changed
		if(!$.ui.contains(this.element[0], this.currentItem[0])) { //Node was moved out of the current element
			if(!noPropagation) delayedTriggers.push(function(event) { this._trigger("remove", event, this._uiHash()); });
			for (var i = this.containers.length - 1; i >= 0; i--){
				if($.ui.contains(this.containers[i].element[0], this.currentItem[0]) && !noPropagation) {
					delayedTriggers.push((function(c) { return function(event) { c._trigger("receive", event, this._uiHash(this)); };  }).call(this, this.containers[i]));
					delayedTriggers.push((function(c) { return function(event) { c._trigger("update", event, this._uiHash(this));  }; }).call(this, this.containers[i]));
				}
			};
		};

		//Post events to containers
		for (var i = this.containers.length - 1; i >= 0; i--){
			if(!noPropagation) delayedTriggers.push((function(c) { return function(event) { c._trigger("deactivate", event, this._uiHash(this)); };  }).call(this, this.containers[i]));
			if(this.containers[i].containerCache.over) {
				delayedTriggers.push((function(c) { return function(event) { c._trigger("out", event, this._uiHash(this)); };  }).call(this, this.containers[i]));
				this.containers[i].containerCache.over = 0;
			}
		}

		//Do what was originally in plugins
		if(this._storedCursor) $('body').css("cursor", this._storedCursor); //Reset cursor
		if(this._storedOpacity) this.helper.css("opacity", this._storedOpacity); //Reset opacity
		if(this._storedZIndex) this.helper.css("zIndex", this._storedZIndex == 'auto' ? '' : this._storedZIndex); //Reset z-index

		this.dragging = false;
		if(this.cancelHelperRemoval) {
			if(!noPropagation) {
				this._trigger("beforeStop", event, this._uiHash());
				for (var i=0; i < delayedTriggers.length; i++) { delayedTriggers[i].call(this, event); }; //Trigger all delayed events
				this._trigger("stop", event, this._uiHash());
			}
			return false;
		}

		if(!noPropagation) this._trigger("beforeStop", event, this._uiHash());

		//$(this.placeholder[0]).remove(); would have been the jQuery way - unfortunately, it unbinds ALL events from the original node!
		this.placeholder[0].parentNode.removeChild(this.placeholder[0]);

		if(this.helper[0] != this.currentItem[0]) this.helper.remove(); this.helper = null;

		if(!noPropagation) {
			for (var i=0; i < delayedTriggers.length; i++) { delayedTriggers[i].call(this, event); }; //Trigger all delayed events
			this._trigger("stop", event, this._uiHash());
		}

		this.fromOutside = false;
		return true;

	},

	_trigger: function() {
		if ($.Widget.prototype._trigger.apply(this, arguments) === false) {
			this.cancel();
		}
	},

	_uiHash: function(inst) {
		var self = inst || this;
		return {
			helper: self.helper,
			placeholder: self.placeholder || $([]),
			position: self.position,
			originalPosition: self.originalPosition,
			offset: self.positionAbs,
			item: self.currentItem,
			sender: inst ? inst.element : null
		};
	}

});

$.extend($.ui.sortable, {
	version: "1.8.13"
});

})(jQuery);
/*
 * jQuery serializeObject Plugin
 *
 */

(function($) {
  $.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    jQuery.each(a, function() {
      if (o[this.name] !== undefined) {
        if (!o[this.name].push) o[this.name] = [o[this.name]];
        o[this.name].push(this.value || '');
      } else {
        o[this.name] = this.value || '';
      }
    });
    return o;
  };
})(jQuery);

/*
 * jQuery Easing v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/
 *
 * Uses the built in easing capabilities added In jQuery 1.1 to offer multiple easing options
 *
 * TERMS OF USE - jQuery Easing
 *
 * Open source under the BSD License.
 *
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 * disclaimer.  Redistributions in binary form must reproduce the above copyright notice, this list of conditions and
 * the following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 * Neither the name of the author nor the names of contributors may be used to endorse or promote products derived from
 * this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
jQuery.extend(jQuery.easing, {
  easeInSine: function (x, t, b, c, d) {
    return -c * Math.cos(t / d * (Math.PI / 2)) + c + b
  },
  easeOutSine: function (x, t, b, c, d) {
    return c * Math.sin(t / d * (Math.PI / 2)) + b
  },
  easeInOutSine: function (x, t, b, c, d) {
    return -c / 2 * (Math.cos(Math.PI * t / d) - 1) + b
  }
});

/*
 * jQuery JSON Plugin version: 2.1 (2009-08-14)
 *
 * This document is licensed as free software under the terms of the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Brantley Harris wrote this plugin. It is based somewhat on the JSON.org  website's http://www.json.org/json2.js,
 * which proclaims: "NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.", a sentiment that I uphold.
 *
 * It is also influenced heavily by MochiKit's serializeJSON, which is  copyrighted 2005 by Bob Ippolito.
 */
(function($) {
  $.toJSON = function(o) {
    if (typeof(JSON) == 'object' && JSON.stringify) return JSON.stringify(o);

    var type = typeof(o);

    if (o === null) return "null";
    if (type == "undefined") return undefined;
    if (type == "number" || type == "boolean") return o + "";
    if (type == "string") return $.quoteString(o);

    if (type == 'object') {
      if (typeof(o.toJSON) == "function") return $.toJSON(o.toJSON());

      if (o.constructor === Date) {
        var year = o.getUTCFullYear();

        var month = o.getUTCMonth() + 1;
        if (month < 10) month = '0' + month;

        var day = o.getUTCDate();
        if (day < 10) day = '0' + day;

        var hours = o.getUTCHours();
        if (hours < 10) hours = '0' + hours;

        var minutes = o.getUTCMinutes();
        if (minutes < 10) minutes = '0' + minutes;

        var seconds = o.getUTCSeconds();
        if (seconds < 10) seconds = '0' + seconds;

        var milli = o.getUTCMilliseconds();
        if (milli < 100) milli = '0' + milli;
        if (milli < 10) milli = '0' + milli;

        return '"' + year + '-' + month + '-' + day + 'T' + hours + ':' + minutes + ':' + seconds + '.' + milli + 'Z"';
      }

      if (o.constructor === Array) {
        var ret = [];
        for (var i = 0; i < o.length; i++) ret.push($.toJSON(o[i]) || "null");
        return "[" + ret.join(",") + "]";
      }

      var pairs = [];
      for (var k in o) {
        var name;
        type = typeof(k);

        if (type == "number") name = '"' + k + '"';
        else if (type == "string") name = $.quoteString(k);
        else continue;  //skip non-string or number keys

        if (typeof o[k] == "function") continue;  //skip pairs where the value is a function.
        var val = $.toJSON(o[k]);
        pairs.push(name + ":" + val);
      }

      return "{" + pairs.join(", ") + "}";
    }
  };

  $.quoteString = function(string) {
    if (string.match(_escapeable)) {
      return '"' + string.replace(_escapeable, function (a) {
        var c = _meta[a];
        if (typeof c === 'string') return c;
        c = a.charCodeAt();
        return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
      }) + '"';
    }
    return '"' + string + '"';
  };

  var _escapeable = /["\\\x00-\x1f\x7f-\x9f]/g;
  var _meta = {'\b': '\\b', '\t': '\\t', '\n': '\\n', '\f': '\\f', '\r': '\\r', '"' : '\\"', '\\': '\\\\'};
})(jQuery);
/*
 HTML Clean for jQuery
 Anthony Johnston
 http://www.antix.co.uk

 version 1.2.3

 $Revision: 51 $

 requires jQuery http://jquery.com

 Use and distibution http://www.opensource.org/licenses/bsd-license.php

 2010-04-02 allowedTags/removeTags added (white/black list) thanks to David Wartian (Dwartian)
 2010-06-30 replaceStyles added for replacement of bold, italic, super and sub styles on a tag
 2010-07-01 notRenderedTags added, where tags are to be removed but their contents are kept
 */

(function ($) {
  $.fn.htmlClean = function (options) {
    // iterate and html clean each matched element
    return this.each(function () {
      if (this.value) {
        this.value = $.htmlClean(this.value, options);
      } else {
        this.innerHTML = $.htmlClean(this.innerHTML, options);
      }
    });
  };

  // clean the passed html
  $.htmlClean = function (html, options) {
    options = $.extend({}, $.htmlClean.defaults, options);

    var tagsRE = /<(\/)?(\w+:)?([\w]+)([^>]*)>/gi;
    var attrsRE = /(\w+)=(".*?"|'.*?'|[^\s>]*)/gi;

    var tagMatch;
    var root = new Element();
    var stack = [root];
    var container = root;

    if (options.bodyOnly) {
      // check for body tag
      if (tagMatch = /<body[^>]*>((\n|.)*)<\/body>/i.exec(html)) {
        html = tagMatch[1];
      }
    }
    html = html.concat("<xxx>"); // ensure last element/text is found
    var lastIndex;

    while (tagMatch = tagsRE.exec(html)) {
      var tag = new Tag(tagMatch[3], tagMatch[1], tagMatch[4], options);

      // add the text
      var text = html.substring(lastIndex, tagMatch.index);
      if (text.length > 0) {
        var child = container.children[container.children.length - 1];
        if (container.children.length > 0 && isText(child = container.children[container.children.length - 1])) {
          // merge text
          container.children[container.children.length - 1] = child.concat(text);
        } else {
          container.children.push(text);
        }
      }
      lastIndex = tagsRE.lastIndex;

      if (tag.isClosing) {
        // find matching container
        if (pop(stack, [tag.name])) {
          stack.pop();
          container = stack[stack.length - 1];
        }
      } else {
        // create a new element
        var element = new Element(tag);

        // add attributes
        var attrMatch;
        while (attrMatch = attrsRE.exec(tag.rawAttributes)) {
          // check style attribute and do replacements
          if (attrMatch[1].toLowerCase() == "style" && options.replaceStyles) {

            var renderParent = !tag.isInline;
            for (var i = 0; i < options.replaceStyles.length; i++) {
              if (options.replaceStyles[i][0].test(attrMatch[2])) {

                if (!renderParent) {
                  tag.render = false;
                  renderParent = true;
                }
                container.children.push(element); // assumes not replaced
                stack.push(element);
                container = element; // assumes replacement is a container
                // create new tag and element
                tag = new Tag(options.replaceStyles[i][1], "", "", options);
                element = new Element(tag);
              }
            }
          }

          if (tag.allowedAttributes != null
            && (tag.allowedAttributes.length == 0
            || $.inArray(attrMatch[1], tag.allowedAttributes) > -1)) {
            element.attributes.push(new Attribute(attrMatch[1], attrMatch[2]));
          }
        }

        // add required empty ones
        $.each(tag.requiredAttributes, function () {
          var name = this.toString();
          if (!element.hasAttribute(name)) element.attributes.push(new Attribute(name, ""));
        });

        // check for replacements
        for (var repIndex = 0; repIndex < options.replace.length; repIndex++) {
          for (var tagIndex = 0; tagIndex < options.replace[repIndex][0].length; tagIndex++) {
            var byName = typeof (options.replace[repIndex][0][tagIndex]) == "string";
            if ((byName && options.replace[repIndex][0][tagIndex] == tag.name)
              || (!byName && options.replace[repIndex][0][tagIndex].test(tagMatch))) {
              // don't render this tag
              tag.render = false;
              container.children.push(element);
              stack.push(element);
              container = element;

              // render new tag, keep attributes
              tag = new Tag(options.replace[repIndex][1], tagMatch[1], tagMatch[4], options);
              element = new Element(tag);
              element.attributes = container.attributes;

              repIndex = options.replace.length; // break out of both loops
              break;
            }
          }
        }

        // check container rules
        var add = true;
        if (!container.isRoot) {
          if (container.tag.isInline && !tag.isInline) {
            add = false;
          } else if (container.tag.disallowNest && tag.disallowNest
            && !tag.requiredParent) {
            add = false;
          } else if (tag.requiredParent) {
            if (add = pop(stack, tag.requiredParent)) {
              container = stack[stack.length - 1];
            }
          }
        }

        if (add) {
          container.children.push(element);

          if (tag.toProtect) {
            // skip to closing tag
            var tagMatch2 = null;
            while (tagMatch2 = tagsRE.exec(html)) {
              var tag2 = new Tag(tagMatch2[3], tagMatch2[1], tagMatch2[4], options);
              if (tag2.isClosing && tag2.name == tag.name) {
                element.children.push(RegExp.leftContext.substring(lastIndex));
                lastIndex = tagsRE.lastIndex;
                break;
              }
            }
          } else {
            // set as current container element
            if (!tag.isSelfClosing && !tag.isNonClosing) {
              stack.push(element);
              container = element;
            }
          }
        }
      }
    }

    // render doc
    return render(root, options).join("");
  };

  // defaults
  $.htmlClean.defaults = {
    // only clean the body tagbody
    bodyOnly: true,
    // only allow tags in this array, (white list), contents still rendered
    allowedTags: [],
    // remove tags in this array, (black list), contents still rendered
    removeTags: ["basefont", "center", "dir", "font", "frame", "frameset", "iframe", "isindex", "menu", "noframes", "s", "strike", "u"],
    // array of attribute names to remove on all elements in addition to those not in tagAttributes e.g ["width", "height"]
    removeAttrs: [],
    // array of [className], [optional array of allowed on elements] e.g. [["class"], ["anotherClass", ["p", "dl"]]]
    allowedClasses: [],
    // tags not rendered, contents remain
    notRenderedTags: [],
    // format the result
    format: false,
    // format indent to start on
    formatIndent: 0,
    // tags to replace, and what to replace with, tag name or regex to match the tag and attributes
    replace: [
      [
        ["b", "big"],
        "strong"
      ],
      [
        ["i"],
        "em"
      ]
    ],
    // styles to replace with tags, multiple style matches supported, inline tags are replaced by the first match blocks are retained
    replaceStyles: [
      [/font-weight:\s*bold/i, "strong"],
      [/font-style:\s*italic/i, "em"],
      [/vertical-align:\s*super/i, "sup"],
      [/vertical-align:\s*sub/i, "sub"]
    ]
  };

  function applyFormat(element, options, output, indent) {
    if (!element.tag.isInline && output.length > 0) {
      output.push("\n");
      for (var i = 0; i < indent; i++) output.push("\t");
    }
  }

  function render(element, options) {
    var output = [],
        empty = element.attributes.length == 0,
        indent = 0,
        outputChildren = null;

    // don't render if not in allowedTags or in removeTags
    var renderTag
      = element.tag.render
      && (options.allowedTags.length == 0 || $.inArray(element.tag.name, options.allowedTags) > -1)
      && (options.removeTags.length == 0 || $.inArray(element.tag.name, options.removeTags) == -1);

    if (!element.isRoot && renderTag) {
      // render opening tag
      output.push("<");
      output.push(element.tag.name);
      $.each(element.attributes, function () {
        if ($.inArray(this.name, options.removeAttrs) == -1) {
          var m = new RegExp(/^(['"]?)(.*?)['"]?$/).exec(this.value);
          var value = m[2];
          var valueQuote = m[1] || "'";

          // check for classes allowed
          if (this.name == "class") {
            value =
              $.grep(value.split(" "), function (c) {
                return $.grep(options.allowedClasses,
                  function (a) {
                    return a[0] == c && (a.length == 1 || $.inArray(element.tag.name, a[1]) > -1);
                  }).length > 0;
              })
                .join(" ");
            valueQuote = "'";
          }

          if (value != null && (value.length > 0 || $.inArray(this.name, element.tag.requiredAttributes) > -1)) {
            output.push(" ");
            output.push(this.name);
            output.push("=");
            output.push(valueQuote);
            output.push(value);
            output.push(valueQuote);
          }
        }
      });
    }

    if (element.tag.isSelfClosing) {
      // self closing
      if (renderTag) output.push(" />");
      empty = false;
    } else if (element.tag.isNonClosing) {
      empty = false;
    } else {
      if (!element.isRoot && renderTag) {
        // close
        output.push(">");
      }

      indent = options.formatIndent++;

      // render children
      if (element.tag.toProtect) {
        outputChildren = $.htmlClean.trim(element.children.join("")).replace(/<br>/ig, "\n");
        output.push(outputChildren);
        empty = outputChildren.length == 0;
        options.formatIndent--;
      } else {
        outputChildren = [];
        for (var i = 0; i < element.children.length; i++) {
          var child = element.children[i];
          var text = $.htmlClean.trim(textClean(isText(child) ? child : child.childrenToString()));
          if (isInline(child)) {
            if (i > 0 && text.length > 0
              && (startsWithWhitespace(child) || endsWithWhitespace(element.children[i - 1]))) {
              outputChildren.push(" ");
            }
          }
          if (isText(child)) {
            if (text.length > 0) {
              outputChildren.push(text);
            }
          } else {
            // don't allow a break to be the last child
            if (i != element.children.length - 1 || child.tag.name != "br") {
              if (options.format) applyFormat(child, options, outputChildren, indent);
              outputChildren = outputChildren.concat(render(child, options));
            }
          }
        }
        options.formatIndent--;

        if (outputChildren.length > 0) {
          if (options.format && outputChildren[0] != "\n") applyFormat(element, options, output, indent);
          output = output.concat(outputChildren);
          empty = false;
        }
      }

      if (!element.isRoot && renderTag) {
        // render the closing tag
        if (options.format) applyFormat(element, options, output, indent - 1);
        output.push("</");
        output.push(element.tag.name);
        output.push(">");
      }
    }

    // check for empty tags
    if (!element.tag.allowEmpty && empty) {
      return [];
    }

    return output;
  }

  // find a matching tag, and pop to it, if not do nothing
  function pop(stack, tagNameArray, index) {
    index = index || 1;
    if ($.inArray(stack[stack.length - index].tag.name, tagNameArray) > -1) {
      return true;
    } else if (stack.length - (index + 1) > 0
      && pop(stack, tagNameArray, index + 1)) {
      stack.pop();
      return true;
    }
    return false;
  }

  // Element Object
  function Element(tag) {
    if (tag) {
      this.tag = tag;
      this.isRoot = false;
    } else {
      this.tag = new Tag("root");
      this.isRoot = true;
    }
    this.attributes = [];
    this.children = [];

    this.hasAttribute = function (name) {
      for (var i = 0; i < this.attributes.length; i++) {
        if (this.attributes[i].name == name) return true;
      }
      return false;
    };

    this.childrenToString = function () {
      return this.children.join("");
    };

    return this;
  }

  // Attribute Object
  function Attribute(name, value) {
    this.name = name;
    this.value = value;

    return this;
  }

  // Tag object
  function Tag(name, close, rawAttributes, options) {
    this.name = name.toLowerCase();

    this.isSelfClosing = $.inArray(this.name, tagSelfClosing) > -1;
    this.isNonClosing = $.inArray(this.name, tagNonClosing) > -1;
    this.isClosing = (close != undefined && close.length > 0);

    this.isInline = $.inArray(this.name, tagInline) > -1;
    this.disallowNest = $.inArray(this.name, tagDisallowNest) > -1;
    this.requiredParent = tagRequiredParent[$.inArray(this.name, tagRequiredParent) + 1];
    this.allowEmpty = $.inArray(this.name, tagAllowEmpty) > -1;

    this.toProtect = $.inArray(this.name, tagProtect) > -1;

    this.rawAttributes = rawAttributes;
    this.allowedAttributes = tagAttributes[$.inArray(this.name, tagAttributes) + 1];
    this.requiredAttributes = tagAttributesRequired[$.inArray(this.name, tagAttributesRequired) + 1];

    this.render = options && $.inArray(this.name, options.notRenderedTags) == -1;

    return this;
  }

  function startsWithWhitespace(item) {
    while (isElement(item) && item.children.length > 0) {
      item = item.children[0]
    }
    return isText(item) && item.length > 0 && $.htmlClean.isWhitespace(item.charAt(0));
  }

  function endsWithWhitespace(item) {
    while (isElement(item) && item.children.length > 0) {
      item = item.children[item.children.length - 1]
    }
    return isText(item) && item.length > 0 && $.htmlClean.isWhitespace(item.charAt(item.length - 1));
  }

  function isText(item) {
    return item.constructor == String;
  }

  function isInline(item) {
    return isText(item) || item.tag.isInline;
  }

  function isElement(item) {
    return item.constructor == Element;
  }

  function textClean(text) {
    return text.replace(/&nbsp;|\n/g, " ").replace(/\s\s+/g, " ");
  }

  // trim off white space, doesn't use regex
  $.htmlClean.trim = function (text) {
    return $.htmlClean.trimStart($.htmlClean.trimEnd(text));
  };
  $.htmlClean.trimStart = function (text) {
    return text.substring($.htmlClean.trimStartIndex(text));
  };
  $.htmlClean.trimStartIndex = function (text) {
    for (var start = 0; start < text.length - 1 && $.htmlClean.isWhitespace(text.charAt(start)); start++);
    return start;
  };
  $.htmlClean.trimEnd = function (text) {
    return text.substring(0, $.htmlClean.trimEndIndex(text));
  };
  $.htmlClean.trimEndIndex = function (text) {
    for (var end = text.length - 1; end >= 0 && $.htmlClean.isWhitespace(text.charAt(end)); end--);
    return end + 1;
  };
  // checks a char is white space or not
  $.htmlClean.isWhitespace = function (c) {
    return $.inArray(c, whitespace) != -1;
  };

  // tags which are inline
  var tagInline = [
    "a", "abbr", "acronym", "address", "b", "big", "br", "button",
    "caption", "cite", "code", "del", "em", "font",
    "hr", "i", "input", "img", "ins", "label", "legend", "map", "q",
    "samp", "select", "small", "span", "strong", "sub", "sup",
    "tt", "var"];
  var tagDisallowNest = ["h1", "h2", "h3", "h4", "h5", "h6", "p", "th", "td"];
  var tagAllowEmpty = ["th", "td"];
  var tagRequiredParent = [
    null,
    "li", ["ul", "ol"],
    "dt", ["dl"],
    "dd", ["dl"],
    "td", ["tr"],
    "th", ["tr"],
    "tr", ["table", "thead", "tbody", "tfoot"],
    "thead", ["table"],
    "tbody", ["table"],
    "tfoot", ["table"]
  ];
  var tagProtect = ["script", "style", "pre", "code"];
  // tags which self close e.g. <br />
  var tagSelfClosing = ["br", "hr", "img", "link", "meta"];
  // tags which do not close
  var tagNonClosing = ["!doctype", "?xml"];
  // attributes allowed on tags
  var tagAttributes = [
    ["class"], // default, for all tags not mentioned
    "?xml", [],
    "!doctype", [],
    "a", ["accesskey", "class", "href", "name", "title", "rel", "rev", "type", "tabindex"],
    "abbr", ["class", "title"],
    "acronym", ["class", "title"],
    "blockquote", ["cite", "class"],
    "button", ["class", "disabled", "name", "type", "value"],
    "del", ["cite", "class", "datetime"],
    "form", ["accept", "action", "class", "enctype", "method", "name"],
    "input", ["accept", "accesskey", "alt", "checked", "class", "disabled", "ismap", "maxlength", "name", "size", "readonly", "src", "tabindex", "type", "usemap", "value"],
    "img", ["alt", "class", "height", "src", "width"],
    "ins", ["cite", "class", "datetime"],
    "label", ["accesskey", "class", "for"],
    "legend", ["accesskey", "class"],
    "link", ["href", "rel", "type"],
    "meta", ["content", "http-equiv", "name", "scheme"],
    "map", ["name"],
    "optgroup", ["class", "disabled", "label"],
    "option", ["class", "disabled", "label", "selected", "value"],
    "q", ["class", "cite"],
    "td", ["colspan", "rowspan"],
    "th", ["colspan", "rowspan"],
    "script", ["src", "type"],
    "select", ["class", "disabled", "multiple", "name", "size", "tabindex"],
    "style", ["type"],
    "table", ["class", "summary"],
    "textarea", ["accesskey", "class", "cols", "disabled", "name", "readonly", "rows", "tabindex"]
  ];
  var tagAttributesRequired = [[], "img", ["alt"]];
  // white space chars
  var whitespace = [" ", " ", "\t", "\n", "\r", "\f"];

})(jQuery);
/*
 * LiquidMetal, version: 0.1 (2009-02-05)
 *
 * A mimetic poly-alloy of Quicksilver's scoring algorithm, essentially
 * LiquidMetal.
 *
 * For usage and examples, visit:
 * http://github.com/rmm5t/liquidmetal
 *
 * Licensed under the MIT:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright (c) 2009, Ryan McGeary (ryanonjavascript -[at]- mcgeary [*dot*] org)
 */

var LiquidMetal = function() {
  var SCORE_NO_MATCH = 0.0;
  var SCORE_MATCH = 1.0;
  var SCORE_TRAILING = 0.8;
  var SCORE_TRAILING_BUT_STARTED = 0.9;
  var SCORE_BUFFER = 0.85;

  return {
    score: function(string, abbreviation) {
      // Short circuits
      if (abbreviation.length == 0) return SCORE_TRAILING;
      if (abbreviation.length > string.length) return SCORE_NO_MATCH;

      var scores = this.buildScoreArray(string, abbreviation);

      var sum = 0.0;
      for (var i =0; i < scores.length; i++) {
        sum += scores[i];
      }

      return (sum / scores.length);
    },

    buildScoreArray: function(string, abbreviation) {
      var scores = new Array(string.length);
      var lower = string.toLowerCase();
      var chars = abbreviation.toLowerCase().split("");

      var lastIndex = -1;
      var started = false;
      for (var i =0; i < chars.length; i++) {
        var c = chars[i];
        var index = lower.indexOf(c, lastIndex+1);
        if (index < 0) return fillArray(scores, SCORE_NO_MATCH);
        if (index == 0) started = true;

        if (isNewWord(string, index)) {
          scores[index-1] = 1;
          fillArray(scores, SCORE_BUFFER, lastIndex+1, index-1);
        }
        else if (isUpperCase(string, index)) {
          fillArray(scores, SCORE_BUFFER, lastIndex+1, index);
        }
        else {
          fillArray(scores, SCORE_NO_MATCH, lastIndex+1, index);
        }

        scores[index] = SCORE_MATCH;
        lastIndex = index;
      }

      var trailingScore = started ? SCORE_TRAILING_BUT_STARTED : SCORE_TRAILING;
      fillArray(scores, trailingScore, lastIndex+1);
      return scores;
    }
  };

  function isUpperCase(string, index) {
    var c = string.charAt(index);
    return ("A" <= c && c <= "Z");
  }

   function isNewWord(string, index) {
    var c = string.charAt(index-1);
    return (c == " " || c == "\t");
  }

  function fillArray(array, value, from, to) {
    from = Math.max(from || 0, 0);
    to = Math.min(to || array.length, array.length);
    for (var i = from; i < to; i++) { array[i] = value; }
    return array;
  }
}();
//
// showdown.js -- A javascript port of Markdown.
//
// Copyright (c) 2007 John Fraser.
//
// Original Markdown Copyright (c) 2004-2005 John Gruber
//   <http://daringfireball.net/projects/markdown/>
//
// Redistributable under a BSD-style open source license.
// See license.txt for more information.
//
// The full source distribution is at:
//
//				A A L
//				T C A
//				T K B
//
//   <http://www.attacklab.net/>
//

//
// Wherever possible, Showdown is a straight, line-by-line port
// of the Perl version of Markdown.
//
// This is not a normal parser design; it's basically just a
// series of string substitutions.  It's hard to read and
// maintain this way,  but keeping Showdown close to the original
// design makes it easier to port new features.
//
// More importantly, Showdown behaves like markdown.pl in most
// edge cases.  So web applications can do client-side preview
// in Javascript, and then build identical HTML on the server.
//
// This port needs the new RegExp functionality of ECMA 262,
// 3rd Edition (i.e. Javascript 1.5).  Most modern web browsers
// should do fine.  Even with the new regular expression features,
// We do a lot of work to emulate Perl's regex functionality.
// The tricky changes in this file mostly have the "attacklab:"
// label.  Major or self-explanatory changes don't.
//
// Smart diff tools like Araxis Merge will be able to match up
// this file with markdown.pl in a useful way.  A little tweaking
// helps: in a copy of markdown.pl, replace "#" with "//" and
// replace "$text" with "text".  Be sure to ignore whitespace
// and line endings.
//


//
// Showdown usage:
//
//   var text = "Markdown *rocks*.";
//
//   var converter = new Showdown.converter();
//   var html = converter.makeHtml(text);
//
//   alert(html);
//
// Note: move the sample code to the bottom of this
// file before uncommenting it.
//


// **************************************************
// GitHub Flavored Markdown modifications by Tekkub
// http://github.github.com/github-flavored-markdown/
//
// Modifications are tagged with "GFM"
// **************************************************

//
// Showdown namespace
//
var Showdown = {};

//
// converter
//
// Wraps all "globals" so that the only thing
// exposed is makeHtml().
//
Showdown.converter = function() {

  // Global hashes, used by various utility routines
  var g_urls;
  var g_titles;
  var g_html_blocks;

  // Used to track when we're inside an ordered or unordered list
  // (see _ProcessListItems() for details):
  var g_list_level = 0;

  // Main function. The order in which other subs are called here is
  // essential. Link and image substitutions need to happen before
  // _EscapeSpecialCharsWithinTagAttributes(), so that any *'s or _'s in the <a>
  // and <img> tags get encoded.
  this.makeHtml = function(text) {

    // Clear the global hashes. If we don't clear these, you get conflicts
    // from other articles when generating a page which contains more than
    // one article (e.g. an index page that shows the N most recent
    // articles):
    g_urls = new Array();
    g_titles = new Array();
    g_html_blocks = new Array();

    // attacklab: Replace ~ with ~T
    // This lets us use tilde as an escape char to avoid md5 hashes
    // The choice of character is arbitray; anything that isn't
    // magic in Markdown will work.
    text = text.replace(/~/g, "~T");

    // attacklab: Replace $ with ~D
    // RegExp interprets $ as a special character
    // when it's in a replacement string
    text = text.replace(/\$/g, "~D");

    // Standardize line endings
    text = text.replace(/\r\n/g, "\n"); // DOS to Unix
    text = text.replace(/\r/g, "\n"); // Mac to Unix

    // Make sure text begins and ends with a couple of newlines:
    text = "\n\n" + text + "\n\n";

    // Convert all tabs to spaces.
    text = _Detab(text);

    // Strip any lines consisting only of spaces and tabs.
    // This makes subsequent regexen easier to write, because we can
    // match consecutive blank lines with /\n+/ instead of something
    // contorted like /[ \t]*\n+/ .
    text = text.replace(/^[ \t]+$/mg, "");

    // Turn block-level HTML blocks into hash entries
    text = _HashHTMLBlocks(text);

    // Strip link definitions, store in hashes.
    text = _StripLinkDefinitions(text);

    text = _RunBlockGamut(text);

    text = _UnescapeSpecialChars(text);

    // attacklab: Restore dollar signs
    text = text.replace(/~D/g, "$$");

    // attacklab: Restore tildes
    text = text.replace(/~T/g, "~");

    // ** GFM **  Auto-link URLs and emails
    text = text.replace(/https?\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!]/g, function(wholeMatch, matchIndex) {
      var left = text.slice(0, matchIndex), right = text.slice(matchIndex);
      if (left.match(/<[^>]+$/) && right.match(/^[^>]*>/)) {
        return wholeMatch
      }
      var href = wholeMatch.replace(/^http:\/\/github.com\//, "https://github.com/");
      return "<a href='" + href + "'>" + wholeMatch + "</a>";
    });
    text = text.replace(/[a-z0-9_\-+=.]+@[a-z0-9\-]+(\.[a-z0-9-]+)+/ig, function(wholeMatch) {
      return "<a href='mailto:" + wholeMatch + "'>" + wholeMatch + "</a>";
    });

    // ** GFM ** Auto-link sha1 if GitHub.nameWithOwner is defined
    text = text.replace(/[a-f0-9]{40}/ig, function(wholeMatch, matchIndex) {
      if (typeof(GitHub) == "undefined" || typeof(GitHub.nameWithOwner) == "undefined") {
        return wholeMatch;
      }
      var left = text.slice(0, matchIndex), right = text.slice(matchIndex);
      if (left.match(/@$/) || (left.match(/<[^>]+$/) && right.match(/^[^>]*>/))) {
        return wholeMatch;
      }
      return "<a href='http://github.com/" + GitHub.nameWithOwner + "/commit/" + wholeMatch + "'>" + wholeMatch.substring(0, 7) + "</a>";
    });

    // ** GFM ** Auto-link user@sha1 if GitHub.nameWithOwner is defined
    text = text.replace(/([a-z0-9_\-+=.]+)@([a-f0-9]{40})/ig, function(wholeMatch, username, sha, matchIndex) {
      if (typeof(GitHub) == "undefined" || typeof(GitHub.nameWithOwner) == "undefined") {
        return wholeMatch;
      }
      GitHub.repoName = GitHub.repoName || _GetRepoName();
      var left = text.slice(0, matchIndex), right = text.slice(matchIndex);
      if (left.match(/\/$/) || (left.match(/<[^>]+$/) && right.match(/^[^>]*>/))) {
        return wholeMatch;
      }
      return "<a href='http://github.com/" + username + "/" + GitHub.repoName + "/commit/" + sha + "'>" + username + "@" + sha.substring(0, 7) + "</a>";
    });

    // ** GFM ** Auto-link user/repo@sha1
    text = text.replace(/([a-z0-9_\-+=.]+\/[a-z0-9_\-+=.]+)@([a-f0-9]{40})/ig, function(wholeMatch, repo, sha) {
      return "<a href='http://github.com/" + repo + "/commit/" + sha + "'>" + repo + "@" + sha.substring(0, 7) + "</a>";
    });

    // ** GFM ** Auto-link #issue if GitHub.nameWithOwner is defined
    text = text.replace(/#([0-9]+)/ig, function(wholeMatch, issue, matchIndex) {
      if (typeof(GitHub) == "undefined" || typeof(GitHub.nameWithOwner) == "undefined") {
        return wholeMatch;
      }
      var left = text.slice(0, matchIndex), right = text.slice(matchIndex);
      if (left == "" || left.match(/[a-z0-9_\-+=.]$/) || (left.match(/<[^>]+$/) && right.match(/^[^>]*>/))) {
        return wholeMatch;
      }
      return "<a href='http://github.com/" + GitHub.nameWithOwner + "/issues/#issue/" + issue + "'>" + wholeMatch + "</a>";
    });

    // ** GFM ** Auto-link user#issue if GitHub.nameWithOwner is defined
    text = text.replace(/([a-z0-9_\-+=.]+)#([0-9]+)/ig, function(wholeMatch, username, issue, matchIndex) {
      if (typeof(GitHub) == "undefined" || typeof(GitHub.nameWithOwner) == "undefined") {
        return wholeMatch;
      }
      GitHub.repoName = GitHub.repoName || _GetRepoName();
      var left = text.slice(0, matchIndex), right = text.slice(matchIndex);
      if (left.match(/\/$/) || (left.match(/<[^>]+$/) && right.match(/^[^>]*>/))) {
        return wholeMatch;
      }
      return "<a href='http://github.com/" + username + "/" + GitHub.repoName + "/issues/#issue/" + issue + "'>" + wholeMatch + "</a>";
    });

    // ** GFM ** Auto-link user/repo#issue
    text = text.replace(/([a-z0-9_\-+=.]+\/[a-z0-9_\-+=.]+)#([0-9]+)/ig, function(wholeMatch, repo, issue) {
      return "<a href='http://github.com/" + repo + "/issues/#issue/" + issue + "'>" + wholeMatch + "</a>";
    });

    return text;
  };


  var _GetRepoName = function() {
    return GitHub.nameWithOwner.match(/^.+\/(.+)$/)[1]
  };

  //
  // Strips link definitions from text, stores the URLs and titles in
  // hash references.
  //
  var _StripLinkDefinitions = function(text) {

    // Link defs are in the form: ^[id]: url "optional title"

    /*
     var text = text.replace(/
     ^[ ]{0,3}\[(.+)\]:  // id = $1  attacklab: g_tab_width - 1
     [ \t]*
     \n?				// maybe *one* newline
     [ \t]*
     <?(\S+?)>?			// url = $2
     [ \t]*
     \n?				// maybe one newline
     [ \t]*
     (?:
     (\n*)				// any lines skipped = $3 attacklab: lookbehind removed
     ["(]
     (.+?)				// title = $4
     [")]
     [ \t]*
     )?					// title is optional
     (?:\n+|$)
     /gm,
     function(){...});
     */
    var text = text.replace(/^[ ]{0,3}\[(.+)\]:[ \t]*\n?[ \t]*<?(\S+?)>?[ \t]*\n?[ \t]*(?:(\n*)["(](.+?)[")][ \t]*)?(?:\n+|\Z)/gm, function (wholeMatch, m1, m2, m3, m4) {
      m1 = m1.toLowerCase();
      g_urls[m1] = _EncodeAmpsAndAngles(m2);  // Link IDs are case-insensitive
      if (m3) {
        // Oops, found blank lines, so it's not a title.
        // Put back the parenthetical statement we stole.
        return m3 + m4;
      } else if (m4) {
        g_titles[m1] = m4.replace(/"/g, "&quot;");
      }

      // Completely remove the definition from the text
      return "";
    });

    return text;
  };


  var _HashHTMLBlocks = function(text) {
    // attacklab: Double up blank lines to reduce lookaround
    text = text.replace(/\n/g, "\n\n");

    // Hashify HTML blocks:
    // We only want to do this for block-level HTML tags, such as headers,
    // lists, and tables. That's because we still want to wrap <p>s around
    // "paragraphs" that are wrapped in non-block-level tags, such as anchors,
    // phrase emphasis, and spans. The list of tags we're looking for is
    // hard-coded:
    var block_tags_a = "p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math|ins|del";
    var block_tags_b = "p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math";

    // First, look for nested blocks, e.g.:
    //   <div>
    //     <div>
    //     tags for inner block must be indented.
    //     </div>
    //   </div>
    //
    // The outermost tags must start at the left margin for this to match, and
    // the inner nested divs must be indented.
    // We need to do this before the next, more liberal match, because the next
    // match will start at the first `<div>` and stop at the first `</div>`.

    // attacklab: This regex can be expensive when it fails.
    /*
     var text = text.replace(/
     (						// save in $1
     ^					// start of line  (with /m)
     <($block_tags_a)	// start tag = $2
     \b					// word break
     // attacklab: hack around khtml/pcre bug...
     [^\r]*?\n			// any number of lines, minimally matching
     </\2>				// the matching end tag
     [ \t]*				// trailing spaces/tabs
     (?=\n+)				// followed by a newline
     )						// attacklab: there are sentinel newlines at end of document
     /gm,function(){...}};
     */
    text = text.replace(/^(<(p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math|ins|del)\b[^\r]*?\n<\/\2>[ \t]*(?=\n+))/gm, hashElement);

    //
    // Now match more liberally, simply from `\n<tag>` to `</tag>\n`
    //

    /*
     var text = text.replace(/
     (						// save in $1
     ^					// start of line  (with /m)
     <($block_tags_b)	// start tag = $2
     \b					// word break
     // attacklab: hack around khtml/pcre bug...
     [^\r]*?				// any number of lines, minimally matching
     .*</\2>				// the matching end tag
     [ \t]*				// trailing spaces/tabs
     (?=\n+)				// followed by a newline
     )						// attacklab: there are sentinel newlines at end of document
     /gm,function(){...}};
     */
    text = text.replace(/^(<(p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|script|noscript|form|fieldset|iframe|math)\b[^\r]*?.*<\/\2>[ \t]*(?=\n+)\n)/gm, hashElement);

    // Special case just for <hr />. It was easier to make a special case than
    // to make the other regex more complicated.

    /*
     text = text.replace(/
     (						// save in $1
     \n\n				// Starting after a blank line
     [ ]{0,3}
     (<(hr)				// start tag = $2
     \b					// word break
     ([^<>])*?			//
     \/?>)				// the matching end tag
     [ \t]*
     (?=\n{2,})			// followed by a blank line
     )
     /g,hashElement);
     */
    text = text.replace(/(\n[ ]{0,3}(<(hr)\b([^<>])*?\/?>)[ \t]*(?=\n{2,}))/g, hashElement);

    // Special case for standalone HTML comments:

    /*
     text = text.replace(/
     (						// save in $1
     \n\n				// Starting after a blank line
     [ ]{0,3}			// attacklab: g_tab_width - 1
     <!
     (--[^\r]*?--\s*)+
     >
     [ \t]*
     (?=\n{2,})			// followed by a blank line
     )
     /g,hashElement);
     */
    text = text.replace(/(\n\n[ ]{0,3}<!(--[^\r]*?--\s*)+>[ \t]*(?=\n{2,}))/g, hashElement);

    // PHP and ASP-style processor instructions (<?...?> and <%...%>)

    /*
     text = text.replace(/
     (?:
     \n\n				// Starting after a blank line
     )
     (						// save in $1
     [ ]{0,3}			// attacklab: g_tab_width - 1
     (?:
     <([?%])			// $2
     [^\r]*?
     \2>
     )
     [ \t]*
     (?=\n{2,})			// followed by a blank line
     )
     /g,hashElement);
     */
    text = text.replace(/(?:\n\n)([ ]{0,3}(?:<([?%])[^\r]*?\2>)[ \t]*(?=\n{2,}))/g, hashElement);

    // attacklab: Undo double lines (see comment at top of this function)
    text = text.replace(/\n\n/g, "\n");
    return text;
  };

  var hashElement = function(wholeMatch, m1) {
    var blockText = m1;

    // Undo double lines
    blockText = blockText.replace(/\n\n/g, "\n");
    blockText = blockText.replace(/^\n/, "");

    // strip trailing blank lines
    blockText = blockText.replace(/\n+$/g, "");

    // Replace the element text with a marker ("~KxK" where x is its key)
    blockText = "\n\n~K" + (g_html_blocks.push(blockText) - 1) + "K\n\n";

    return blockText;
  };

  //
  // These are all the transformations that form block-level
  // tags like paragraphs, headers, and list items.
  //
  var _RunBlockGamut = function(text) {
    text = _DoHeaders(text);

    // Do Horizontal Rules:
    var key = hashBlock("<hr />");
    text = text.replace(/^[ ]{0,2}([ ]?\*[ ]?){3,}[ \t]*$/gm, key);
    text = text.replace(/^[ ]{0,2}([ ]?\-[ ]?){3,}[ \t]*$/gm, key);
    text = text.replace(/^[ ]{0,2}([ ]?\_[ ]?){3,}[ \t]*$/gm, key);

    text = _DoLists(text);
    text = _DoCodeBlocks(text);
    text = _DoBlockQuotes(text);

    // We already ran _HashHTMLBlocks() before, in Markdown(), but that
    // was to escape raw HTML in the original Markdown source. This time,
    // we're escaping the markup we've just created, so that we don't wrap
    // <p> tags around block-level tags.
    text = _HashHTMLBlocks(text);
    text = _FormParagraphs(text);

    return text;
  };

  //
  // These are all the transformations that occur *within* block-level
  // tags like paragraphs, headers, and list items.
  //
  var _RunSpanGamut = function(text) {

    text = _DoCodeSpans(text);
    text = _EscapeSpecialCharsWithinTagAttributes(text);
    text = _EncodeBackslashEscapes(text);

    // Process anchor and image tags. Images must come first,
    // because ![foo][f] looks like an anchor.
    text = _DoImages(text);
    text = _DoAnchors(text);

    // Make links out of things like `<http://example.com/>`
    // Must come after _DoAnchors(), because you can use < and >
    // delimiters in inline links like [this](<url>).
    text = _DoAutoLinks(text);
    text = _EncodeAmpsAndAngles(text);
    text = _DoItalicsAndBold(text);

    // Do hard breaks:
    text = text.replace(/  +\n/g, " <br />\n");

    return text;
  };

  //
  // Within tags -- meaning between < and > -- encode [\ ` * _] so they
  // don't conflict with their use in Markdown for code, italics and strong.
  //
  var _EscapeSpecialCharsWithinTagAttributes = function(text) {

    // Build a regex to find HTML tags and comments.  See Friedl's
    // "Mastering Regular Expressions", 2nd Ed., pp. 200-201.
    var regex = /(<[a-z\/!$]("[^"]*"|'[^']*'|[^'">])*>|<!(--.*?--\s*)+>)/gi;

    text = text.replace(regex, function(wholeMatch) {
      var tag = wholeMatch.replace(/(.)<\/?code>(?=.)/g, "$1`");
      tag = escapeCharacters(tag, "\\`*_");
      return tag;
    });

    return text;
  };

  //
  // Turn Markdown link shortcuts into XHTML <a> tags.
  //
  var _DoAnchors = function(text) {
    //
    // First, handle reference-style links: [link text] [id]
    //

    /*
     text = text.replace(/
     (							// wrap whole match in $1
     \[
     (
     (?:
     \[[^\]]*\]		// allow brackets nested one level
     |
     [^\[]			// or anything else
     )*
     )
     \]

     [ ]?					// one optional space
     (?:\n[ ]*)?				// one optional newline followed by spaces

     \[
     (.*?)					// id = $3
     \]
     )()()()()					// pad remaining backreferences
     /g,_DoAnchors_callback);
     */
    text = text.replace(/(\[((?:\[[^\]]*\]|[^\[\]])*)\][ ]?(?:\n[ ]*)?\[(.*?)\])()()()()/g, writeAnchorTag);

    //
    // Next, inline-style links: [link text](url "optional title")
    //

    /*
     text = text.replace(/
     (						// wrap whole match in $1
     \[
     (
     (?:
     \[[^\]]*\]	// allow brackets nested one level
     |
     [^\[\]]			// or anything else
     )
     )
     \]
     \(						// literal paren
     [ \t]*
     ()						// no id, so leave $3 empty
     <?(.*?)>?				// href = $4
     [ \t]*
     (						// $5
     (['"])				// quote char = $6
     (.*?)				// Title = $7
     \6					// matching quote
     [ \t]*				// ignore any spaces/tabs between closing quote and )
     )?						// title is optional
     \)
     )
     /g,writeAnchorTag);
     */
    text = text.replace(/(\[((?:\[[^\]]*\]|[^\[\]])*)\]\([ \t]*()<?(.*?)>?[ \t]*((['"])(.*?)\6[ \t]*)?\))/g, writeAnchorTag);

    //
    // Last, handle reference-style shortcuts: [link text]
    // These must come last in case you've also got [link test][1]
    // or [link test](/foo)
    //

    /*
     text = text.replace(/
     (		 					// wrap whole match in $1
     \[
     ([^\[\]]+)				// link text = $2; can't contain '[' or ']'
     \]
     )()()()()()					// pad rest of backreferences
     /g, writeAnchorTag);
     */
    text = text.replace(/(\[([^\[\]]+)\])()()()()()/g, writeAnchorTag);

    return text;
  };

  var writeAnchorTag = function(wholeMatch, m1, m2, m3, m4, m5, m6, m7) {
    if (m7 == undefined) m7 = "";
    var whole_match = m1;
    var link_text = m2;
    var link_id = m3.toLowerCase();
    var url = m4;
    var title = m7;

    if (url == "") {
      if (link_id == "") {
        // lower-case and turn embedded newlines into spaces
        link_id = link_text.toLowerCase().replace(/ ?\n/g, " ");
      }
      url = "#" + link_id;

      if (g_urls[link_id] != undefined) {
        url = g_urls[link_id];
        if (g_titles[link_id] != undefined) {
          title = g_titles[link_id];
        }
      }
      else {
        if (whole_match.search(/\(\s*\)$/m) > -1) {
          // Special case for explicit empty url
          url = "";
        } else {
          return whole_match;
        }
      }
    }

    url = escapeCharacters(url, "*_");
    var result = "<a href=\"" + url + "\"";

    if (title != "") {
      title = title.replace(/"/g, "&quot;");
      title = escapeCharacters(title, "*_");
      result += " title=\"" + title + "\"";
    }

    result += ">" + link_text + "</a>";

    return result;
  };


  //
  // Turn Markdown image shortcuts into <img> tags.
  //
  var _DoImages = function(text) {
    //
    // First, handle reference-style labeled images: ![alt text][id]
    //

    /*
     text = text.replace(/
     (						// wrap whole match in $1
     !\[
     (.*?)				// alt text = $2
     \]

     [ ]?				// one optional space
     (?:\n[ ]*)?			// one optional newline followed by spaces

     \[
     (.*?)				// id = $3
     \]
     )()()()()				// pad rest of backreferences
     /g,writeImageTag);
     */
    text = text.replace(/(!\[(.*?)\][ ]?(?:\n[ ]*)?\[(.*?)\])()()()()/g, writeImageTag);

    //
    // Next, handle inline images:  ![alt text](url "optional title")
    // Don't forget: encode * and _

    /*
     text = text.replace(/
     (						// wrap whole match in $1
     !\[
     (.*?)				// alt text = $2
     \]
     \s?					// One optional whitespace character
     \(					// literal paren
     [ \t]*
     ()					// no id, so leave $3 empty
     <?(\S+?)>?			// src url = $4
     [ \t]*
     (					// $5
     (['"])			// quote char = $6
     (.*?)			// title = $7
     \6				// matching quote
     [ \t]*
     )?					// title is optional
     \)
     )
     /g,writeImageTag);
     */
    text = text.replace(/(!\[(.*?)\]\s?\([ \t]*()<?(\S+?)>?[ \t]*((['"])(.*?)\6[ \t]*)?\))/g, writeImageTag);

    return text;
  };

  var writeImageTag = function(wholeMatch, m1, m2, m3, m4, m5, m6, m7) {
    var whole_match = m1;
    var alt_text = m2;
    var link_id = m3.toLowerCase();
    var url = m4;
    var title = m7;

    if (!title) title = "";

    if (url == "") {
      if (link_id == "") {
        // lower-case and turn embedded newlines into spaces
        link_id = alt_text.toLowerCase().replace(/ ?\n/g, " ");
      }
      url = "#" + link_id;

      if (g_urls[link_id] != undefined) {
        url = g_urls[link_id];
        if (g_titles[link_id] != undefined) {
          title = g_titles[link_id];
        }
      }
      else {
        return whole_match;
      }
    }

    alt_text = alt_text.replace(/"/g, "&quot;");
    url = escapeCharacters(url, "*_");
    var result = "<img src=\"" + url + "\" alt=\"" + alt_text + "\"";

    // attacklab: Markdown.pl adds empty title attributes to images.
    // Replicate this bug.

    //if (title != "") {
    title = title.replace(/"/g, "&quot;");
    title = escapeCharacters(title, "*_");
    result += " title=\"" + title + "\"";
    //}

    result += " />";

    return result;
  };


  var _DoHeaders = function(text) {

    // Setext-style headers:
    //	Header 1
    //	========
    //
    //	Header 2
    //	--------
    //
    text = text.replace(/^(.+)[ \t]*\n=+[ \t]*\n+/gm,
            function(wholeMatch, m1) {
              return hashBlock("<h1>" + _RunSpanGamut(m1) + "</h1>");
            });

    text = text.replace(/^(.+)[ \t]*\n-+[ \t]*\n+/gm,
            function(matchFound, m1) {
              return hashBlock("<h2>" + _RunSpanGamut(m1) + "</h2>");
            });

    // atx-style headers:
    //  # Header 1
    //  ## Header 2
    //  ## Header 2 with closing hashes ##
    //  ...
    //  ###### Header 6
    //

    /*
     text = text.replace(/
     ^(\#{1,6})				// $1 = string of #'s
     [ \t]*
     (.+?)					// $2 = Header text
     [ \t]*
     \#*						// optional closing #'s (not counted)
     \n+
     /gm, function() {...});
     */

    text = text.replace(/^(\#{1,6})[ \t]*(.+?)[ \t]*\#*\n+/gm,
            function(wholeMatch, m1, m2) {
              var h_level = m1.length;
              return hashBlock("<h" + h_level + ">" + _RunSpanGamut(m2) + "</h" + h_level + ">");
            });

    return text;
  };

  // This declaration keeps Dojo compressor from outputting garbage:
  var _ProcessListItems;

  //
  // Form HTML ordered (numbered) and unordered (bulleted) lists.
  //
  var _DoLists = function(text) {

    // attacklab: add sentinel to hack around khtml/safari bug:
    // http://bugs.webkit.org/show_bug.cgi?id=11231
    text += "~0";

    // Re-usable pattern to match any entirel ul or ol list:

    /*
     var whole_list = /
     (									// $1 = whole list
     (								// $2
     [ ]{0,3}					// attacklab: g_tab_width - 1
     ([*+-]|\d+[.])				// $3 = first list item marker
     [ \t]+
     )
     [^\r]+?
     (								// $4
     ~0							// sentinel for workaround; should be $
     |
     \n{2,}
     (?=\S)
     (?!							// Negative lookahead for another list item marker
     [ \t]*
     (?:[*+-]|\d+[.])[ \t]+
     )
     )
     )/g
     */
    var whole_list = /^(([ ]{0,3}([*+-]|\d+[.])[ \t]+)[^\r]+?(~0|\n{2,}(?=\S)(?![ \t]*(?:[*+-]|\d+[.])[ \t]+)))/gm;

    if (g_list_level) {
      text = text.replace(whole_list, function(wholeMatch, m1, m2) {
        var list = m1;
        var list_type = (m2.search(/[*+-]/g) > -1) ? "ul" : "ol";

        // Turn double returns into triple returns, so that we can make a
        // paragraph for the last item in a list, if necessary:
        list = list.replace(/\n{2,}/g, "\n\n\n");
        var result = _ProcessListItems(list);

        // Trim any trailing whitespace, to put the closing `</$list_type>`
        // up on the preceding line, to get it past the current stupid
        // HTML block parser. This is a hack to work around the terrible
        // hack that is the HTML block parser.
        result = result.replace(/\s+$/, "");
        result = "<" + list_type + ">" + result + "</" + list_type + ">\n";
        return result;
      });
    } else {
      whole_list = /(\n\n|^\n?)(([ ]{0,3}([*+-]|\d+[.])[ \t]+)[^\r]+?(~0|\n{2,}(?=\S)(?![ \t]*(?:[*+-]|\d+[.])[ \t]+)))/g;
      text = text.replace(whole_list, function(wholeMatch, m1, m2, m3) {
        var runup = m1;
        var list = m2;

        var list_type = (m3.search(/[*+-]/g) > -1) ? "ul" : "ol";
        // Turn double returns into triple returns, so that we can make a
        // paragraph for the last item in a list, if necessary:
        var list = list.replace(/\n{2,}/g, "\n\n\n");
        var result = _ProcessListItems(list);
        result = runup + "<" + list_type + ">\n" + result + "</" + list_type + ">\n";
        return result;
      });
    }

    // attacklab: strip sentinel
    text = text.replace(/~0/, "");

    return text;
  };

  //
  //  Process the contents of a single ordered or unordered list, splitting it
  //  into individual list items.
  //
  _ProcessListItems = function(list_str) {
    // The $g_list_level global keeps track of when we're inside a list.
    // Each time we enter a list, we increment it; when we leave a list,
    // we decrement. If it's zero, we're not in a list anymore.
    //
    // We do this because when we're not inside a list, we want to treat
    // something like this:
    //
    //    I recommend upgrading to version
    //    8. Oops, now this line is treated
    //    as a sub-list.
    //
    // As a single paragraph, despite the fact that the second line starts
    // with a digit-period-space sequence.
    //
    // Whereas when we're inside a list (or sub-list), that line will be
    // treated as the start of a sub-list. What a kludge, huh? This is
    // an aspect of Markdown's syntax that's hard to parse perfectly
    // without resorting to mind-reading. Perhaps the solution is to
    // change the syntax rules such that sub-lists must start with a
    // starting cardinal number; e.g. "1." or "a.".

    g_list_level++;

    // trim trailing blank lines:
    list_str = list_str.replace(/\n{2,}$/, "\n");

    // attacklab: add sentinel to emulate \z
    list_str += "~0";

    /*
     list_str = list_str.replace(/
     (\n)?							// leading line = $1
     (^[ \t]*)						// leading whitespace = $2
     ([*+-]|\d+[.]) [ \t]+			// list marker = $3
     ([^\r]+?						// list item text   = $4
     (\n{1,2}))
     (?= \n* (~0 | \2 ([*+-]|\d+[.]) [ \t]+))
     /gm, function(){...});
     */
    list_str = list_str.replace(/(\n)?(^[ \t]*)([*+-]|\d+[.])[ \t]+([^\r]+?(\n{1,2}))(?=\n*(~0|\2([*+-]|\d+[.])[ \t]+))/gm,
            function(wholeMatch, m1, m2, m3, m4) {
              var item = m4;
              var leading_line = m1;
              var leading_space = m2;

              if (leading_line || (item.search(/\n{2,}/) > -1)) {
                item = _RunBlockGamut(_Outdent(item));
              }
              else {
                // Recursion for sub-lists:
                item = _DoLists(_Outdent(item));
                item = item.replace(/\n$/, ""); // chomp(item)
                item = _RunSpanGamut(item);
              }

              return  "<li>" + item + "</li>\n";
            }
            );

    // attacklab: strip sentinel
    list_str = list_str.replace(/~0/g, "");

    g_list_level--;
    return list_str;
  };


  //
  //  Process Markdown `<pre><code>` blocks.
  //
  var _DoCodeBlocks = function(text) {

    /*
     text = text.replace(text,
     /(?:\n\n|^)
     (								// $1 = the code block -- one or more lines, starting with a space/tab
     (?:
     (?:[ ]{4}|\t)			// Lines must start with a tab or a tab-width of spaces - attacklab: g_tab_width
     .*\n+
     )+
     )
     (\n*[ ]{0,3}[^ \t\n]|(?=~0))	// attacklab: g_tab_width
     /g,function(){...});
     */

    // attacklab: sentinel workarounds for lack of \A and \Z, safari\khtml bug
    text += "~0";

    text = text.replace(/(?:\n\n|^)((?:(?:[ ]{4}|\t).*\n+)+)(\n*[ ]{0,3}[^ \t\n]|(?=~0))/g, function(wholeMatch, m1, m2) {
      var codeblock = m1;
      var nextChar = m2;

      codeblock = _EncodeCode(_Outdent(codeblock));
      codeblock = _Detab(codeblock);
      codeblock = codeblock.replace(/^\n+/g, ""); // trim leading newlines
      codeblock = codeblock.replace(/\n+$/g, ""); // trim trailing whitespace

      codeblock = "<pre><code>" + codeblock + "\n</code></pre>";

      return hashBlock(codeblock) + nextChar;
    });

    // attacklab: strip sentinel
    text = text.replace(/~0/, "");

    return text;
  };

  var hashBlock = function(text) {
    text = text.replace(/(^\n+|\n+$)/g, "");
    return "\n\n~K" + (g_html_blocks.push(text) - 1) + "K\n\n";
  };


  //
  //   *  Backtick quotes are used for <code></code> spans.
  //
  //   *  You can use multiple backticks as the delimiters if you want to
  //	 include literal backticks in the code span. So, this input:
  //
  //		 Just type ``foo `bar` baz`` at the prompt.
  //
  //	   Will translate to:
  //
  //		 <p>Just type <code>foo `bar` baz</code> at the prompt.</p>
  //
  //	There's no arbitrary limit to the number of backticks you
  //	can use as delimters. If you need three consecutive backticks
  //	in your code, use four for delimiters, etc.
  //
  //  *  You can use spaces to get literal backticks at the edges:
  //
  //		 ... type `` `bar` `` ...
  //
  //	   Turns to:
  //
  //		 ... type <code>`bar`</code> ...
  //
  var _DoCodeSpans = function(text) {
    /*
     text = text.replace(/
     (^|[^\\])					// Character before opening ` can't be a backslash
     (`+)						// $2 = Opening run of `
     (							// $3 = The code block
     [^\r]*?
     [^`]					// attacklab: work around lack of lookbehind
     )
     \2							// Matching closer
     (?!`)
     /gm, function(){...});
     */

    text = text.replace(/(^|[^\\])(`+)([^\r]*?[^`])\2(?!`)/gm,
            function(wholeMatch, m1, m2, m3, m4) {
              var c = m3;
              c = c.replace(/^([ \t]*)/g, "");	// leading whitespace
              c = c.replace(/[ \t]*$/g, "");	// trailing whitespace
              c = _EncodeCode(c);
              return m1 + "<code>" + c + "</code>";
            });

    return text;
  };


  //
  // Encode/escape certain characters inside Markdown code runs.
  // The point is that in code, these characters are literals,
  // and lose their special Markdown meanings.
  //
  var _EncodeCode = function(text) {
    // Encode all ampersands; HTML entities are not
    // entities within a Markdown code span.
    text = text.replace(/&/g, "&amp;");

    // Do the angle bracket song and dance:
    text = text.replace(/</g, "&lt;");
    text = text.replace(/>/g, "&gt;");

    // Now, escape characters that are magic in Markdown:
    text = escapeCharacters(text, "\*_{}[]\\", false);

    return text;
  };


  var _DoItalicsAndBold = function(text) {

    // <strong> must go first:
    text = text.replace(/(\*\*|__)(?=\S)([^\r]*?\S[*_]*)\1/g,
            "<strong>$2</strong>");

    text = text.replace(/(\w)_(\w)/g, "$1~E95E$2"); // ** GFM **  "~E95E" == escaped "_"
    text = text.replace(/(\*|_)(?=\S)([^\r]*?\S)\1/g,
            "<em>$2</em>");

    return text;
  };


  var _DoBlockQuotes = function(text) {

    /*
     text = text.replace(/
     (								// Wrap whole match in $1
     (
     ^[ \t]*>[ \t]?			// '>' at the start of a line
     .+\n					// rest of the first line
     (.+\n)*					// subsequent consecutive lines
     \n*						// blanks
     )+
     )
     /gm, function(){...});
     */

    text = text.replace(/((^[ \t]*>[ \t]?.+\n(.+\n)*\n*)+)/gm,
            function(wholeMatch, m1) {
              var bq = m1;

              // attacklab: hack around Konqueror 3.5.4 bug:
              // "----------bug".replace(/^-/g,"") == "bug"

              bq = bq.replace(/^[ \t]*>[ \t]?/gm, "~0");	// trim one level of quoting

              // attacklab: clean up hack
              bq = bq.replace(/~0/g, "");

              bq = bq.replace(/^[ \t]+$/gm, "");		// trim whitespace-only lines
              bq = _RunBlockGamut(bq);				// recurse

              bq = bq.replace(/(^|\n)/g, "$1  ");
              // These leading spaces screw with <pre> content, so we need to fix that:
              bq = bq.replace(
                      /(\s*<pre>[^\r]+?<\/pre>)/gm,
                      function(wholeMatch, m1) {
                        var pre = m1;
                        // attacklab: hack around Konqueror 3.5.4 bug:
                        pre = pre.replace(/^  /mg, "~0");
                        pre = pre.replace(/~0/g, "");
                        return pre;
                      });

              return hashBlock("<blockquote>\n" + bq + "\n</blockquote>");
            });
    return text;
  };


  //
  //  Params:
  //    $text - string to process with html <p> tags
  //
  var _FormParagraphs = function(text) {

    // Strip leading and trailing lines:
    text = text.replace(/^\n+/g, "");
    text = text.replace(/\n+$/g, "");

    var grafs = text.split(/\n{2,}/g);
    var grafsOut = new Array();

    //
    // Wrap <p> tags.
    //
    var end = grafs.length;
    for (var i = 0; i < end; i++) {
      var str = grafs[i];

      // if this is an HTML marker, copy it
      if (str.search(/~K(\d+)K/g) >= 0) {
        grafsOut.push(str);
      }
      else if (str.search(/\S/) >= 0) {
        str = _RunSpanGamut(str);
        str = str.replace(/\n/g, "<br />");  // ** GFM **
        str = str.replace(/^([ \t]*)/g, "<p>");
        str += "</p>";
        grafsOut.push(str);
      }

    }

    //
    // Unhashify HTML blocks
    //
    end = grafsOut.length;
    for (var i = 0; i < end; i++) {
      // if this is a marker for an html block...
      while (grafsOut[i].search(/~K(\d+)K/) >= 0) {
        var blockText = g_html_blocks[RegExp.$1];
        blockText = blockText.replace(/\$/g, "$$$$"); // Escape any dollar signs
        grafsOut[i] = grafsOut[i].replace(/~K\d+K/, blockText);
      }
    }

    return grafsOut.join("\n\n");
  };

  // Smart processing for ampersands and angle brackets that need to be encoded.
  var _EncodeAmpsAndAngles = function(text) {

    // Ampersand-encoding based entirely on Nat Irons's Amputator MT plugin:
    //   http://bumppo.net/projects/amputator/
    text = text.replace(/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/g, "&amp;");

    // Encode naked <'s
    text = text.replace(/<(?![a-z\/?\$!])/gi, "&lt;");

    return text;
  };

  //
  //   Parameter:  String.
  //   Returns:	The string, with after processing the following backslash
  //			   escape sequences.
  //
  var _EncodeBackslashEscapes = function(text) {
    // attacklab: The polite way to do this is with the new
    // escapeCharacters() function:
    //
    // 	text = escapeCharacters(text,"\\",true);
    // 	text = escapeCharacters(text,"`*_{}[]()>#+-.!",true);
    //
    // ...but we're sidestepping its use of the (slow) RegExp constructor
    // as an optimization for Firefox.  This function gets called a LOT.

    text = text.replace(/\\(\\)/g, escapeCharacters_callback);
    text = text.replace(/\\([`*_{}\[\]()>#+-.!])/g, escapeCharacters_callback);
    return text;
  };

  var _DoAutoLinks = function(text) {

    text = text.replace(/<((https?|ftp|dict):[^'">\s]+)>/gi, "<a href=\"$1\">$1</a>");

    // Email addresses: <address@domain.foo>

    /*
     text = text.replace(/
     <
     (?:mailto:)?
     (
     [-.\w]+
     \@
     [-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]+
     )
     >
     /gi, _DoAutoLinks_callback());
     */
    text = text.replace(/<(?:mailto:)?([-.\w]+\@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]+)>/gi, function(wholeMatch, m1) {
      return _EncodeEmailAddress(_UnescapeSpecialChars(m1));
    });

    return text;
  };

  //
  //  Input: an email address, e.g. "foo@example.com"
  //
  //  Output: the email address as a mailto link, with each character
  //	of the address encoded as either a decimal or hex entity, in
  //	the hopes of foiling most address harvesting spam bots. E.g.:
  //
  //	<a href="&#x6D;&#97;&#105;&#108;&#x74;&#111;:&#102;&#111;&#111;&#64;&#101;
  //	   x&#x61;&#109;&#x70;&#108;&#x65;&#x2E;&#99;&#111;&#109;">&#102;&#111;&#111;
  //	   &#64;&#101;x&#x61;&#109;&#x70;&#108;&#x65;&#x2E;&#99;&#111;&#109;</a>
  //
  //  Based on a filter by Matthew Wickline, posted to the BBEdit-Talk
  //  mailing list: <http://tinyurl.com/yu7ue>
  //
  var _EncodeEmailAddress = function(addr) {

    // attacklab: why can't javascript speak hex?
    function char2hex(ch) {
      var hexDigits = '0123456789ABCDEF';
      var dec = ch.charCodeAt(0);
      return(hexDigits.charAt(dec >> 4) + hexDigits.charAt(dec & 15));
    }

    var encode = [
      function(ch) { return "&#" + ch.charCodeAt(0) + ";" },
      function(ch) { return "&#x" + char2hex(ch) + ";" },
      function(ch) { return ch }
    ];

    addr = "mailto:" + addr;

    addr = addr.replace(/./g, function(ch) {
      if (ch == "@") {
        // this *must* be encoded. I insist.
        ch = encode[Math.floor(Math.random() * 2)](ch);
      } else if (ch != ":") {
        // leave ':' alone (to spot mailto: later)
        var r = Math.random();
        // roughly 10% raw, 45% hex, 45% dec
        ch = (r > .9 ? encode[2](ch) : r > .45 ? encode[1](ch) : encode[0](ch));
      }
      return ch;
    });

    addr = "<a href=\"" + addr + "\">" + addr + "</a>";
    addr = addr.replace(/">.+:/g, "\">"); // strip the mailto: from the visible part

    return addr;
  };

  //
  // Swap back in all the special characters we've hidden.
  //
  var _UnescapeSpecialChars = function(text) {
    text = text.replace(/~E(\d+)E/g, function(wholeMatch, m1) {
      var charCodeToReplace = parseInt(m1);
      return String.fromCharCode(charCodeToReplace);
    });
    return text;
  };

  //
  // Remove one level of line-leading tabs or spaces
  //
  var _Outdent = function(text) {

    // attacklab: hack around Konqueror 3.5.4 bug:
    // "----------bug".replace(/^-/g,"") == "bug"

    text = text.replace(/^(\t|[ ]{1,4})/gm, "~0"); // attacklab: g_tab_width

    // attacklab: clean up hack
    text = text.replace(/~0/g, "");

    return text;
  };

  // attacklab: Detab's completely rewritten for speed.
  // In perl we could fix it by anchoring the regexp with \G.
  // In javascript we're less fortunate.
  var _Detab = function(text) {
    // expand first n-1 tabs
    text = text.replace(/\t(?=\t)/g, "    "); // attacklab: g_tab_width

    // replace the nth with two sentinels
    text = text.replace(/\t/g, "~A~B");

    // use the sentinel to anchor our regex so it doesn't explode
    text = text.replace(/~B(.+?)~A/g, function(wholeMatch, m1, m2) {
      var leadingText = m1;
      var numSpaces = 4 - leadingText.length % 4;  // attacklab: g_tab_width

      // there *must* be a better way to do this:
      for (var i = 0; i < numSpaces; i++) leadingText += " ";

      return leadingText;
    });

    // clean up sentinels
    text = text.replace(/~A/g, "    ");  // attacklab: g_tab_width
    text = text.replace(/~B/g, "");

    return text;
  };


  //  attacklab: Utility functions
  var escapeCharacters = function(text, charsToEscape, afterBackslash) {
    // First we have to escape the escape characters so that
    // we can build a character class out of them
    var regexString = "([" + charsToEscape.replace(/([\[\]\\])/g, "\\$1") + "])";

    if (afterBackslash) {
      regexString = "\\\\" + regexString;
    }

    var regex = new RegExp(regexString, "g");
    text = text.replace(regex, escapeCharacters_callback);

    return text;
  };


  var escapeCharacters_callback = function(wholeMatch, m1) {
    var charCodeToEscape = m1.charCodeAt(0);
    return "~E" + charCodeToEscape + "E";
  };

};
(function() {
  this.Mercury || (this.Mercury = {});
  jQuery.extend(this.Mercury, {
    version: '0.2.3',
    supported: document.getElementById && document.designMode && !jQuery.browser.konqueror && !jQuery.browser.msie,
    Regions: Mercury.Regions || {},
    modalHandlers: Mercury.modalHandlers || {},
    lightviewHandlers: Mercury.lightviewHandlers || {},
    dialogHandlers: Mercury.dialogHandlers || {},
    preloadedViews: Mercury.preloadedViews || {},
    ajaxHeaders: function() {
      var headers;
      headers = {};
      headers[Mercury.config.csrfHeader] = Mercury.csrfToken;
      return headers;
    },
    bind: function(eventName, callback) {
      return jQuery(top).bind("mercury:" + eventName, callback);
    },
    trigger: function(eventName, options) {
      Mercury.log(eventName, options);
      return jQuery(top).trigger("mercury:" + eventName, options);
    },
    log: function() {
      if (Mercury.debug && console) {
        if (arguments[0] === 'hide:toolbar' || arguments[0] === 'show:toolbar') {
          return;
        }
        try {
          return console.debug(arguments);
        } catch (e) {

        }
      }
    }
  });
}).call(this);
(function() {
  String.prototype.titleize = function() {
    return this[0].toUpperCase() + this.slice(1);
  };
  String.prototype.toHex = function() {
    if (this[0] === '#') {
      return this;
    }
    return this.replace(/rgba?\((\d+)[\s|\,]?\s(\d+)[\s|\,]?\s(\d+)\)/gi, function(a, r, g, b) {
      return "#" + (parseInt(r).toHex()) + (parseInt(g).toHex()) + (parseInt(b).toHex());
    });
  };
  String.prototype.regExpEscape = function() {
    var escaped, specials;
    specials = ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'];
    escaped = new RegExp('(\\' + specials.join('|\\') + ')', 'g');
    return this.replace(escaped, '\\$1');
  };
  Number.prototype.toHex = function() {
    var result;
    result = this.toString(16).toUpperCase();
    if (result[1]) {
      return result;
    } else {
      return "0" + result;
    }
  };
  Number.prototype.toBytes = function() {
    var bytes, i;
    bytes = parseInt(this);
    i = 0;
    while (1023 < bytes) {
      bytes /= 1024;
      i += 1;
    }
    if (i) {
      return "" + (bytes.toFixed(2)) + ['', ' kb', ' Mb', ' Gb', ' Tb', ' Pb', ' Eb'][i];
    } else {
      return "" + bytes + " bytes";
    }
  };
  window.originalSetTimeout = window.setTimeout;
  window.setTimeout = function(arg1, arg2) {
    if (typeof arg1 === 'number') {
      return window.originalSetTimeout(arg2, arg1);
    } else {
      return window.originalSetTimeout(arg1, arg2);
    }
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.PageEditor = (function() {
    function PageEditor(saveUrl, options) {
      var token;
      this.saveUrl = saveUrl != null ? saveUrl : null;
      this.options = options != null ? options : {};
      if (!Mercury.supported) {
        throw "Mercury.PageEditor is unsupported in this client. Supported browsers are chrome 10+, firefix 4+, and safari 5+.";
      }
      if (window.mercuryInstance) {
        throw "Mercury.PageEditor can only be instantiated once.";
      }
      if (this.options.visible !== false) {
        this.options.visible = true;
      }
      this.visible = this.options.visible;
      window.mercuryInstance = this;
      this.regions = [];
      this.initializeInterface();
      if (token = jQuery(Mercury.config.csrfSelector).attr('content')) {
        Mercury.csrfToken = token;
      }
    }
    PageEditor.prototype.initializeInterface = function() {
      var _ref, _ref2;
      this.focusableElement = jQuery('<input>', {
        "class": 'mercury-focusable',
        type: 'text'
      }).appendTo((_ref = this.options.appendTo) != null ? _ref : 'body');
      this.iframe = jQuery('<iframe>', {
        id: 'mercury_iframe',
        "class": 'mercury-iframe',
        seamless: 'true',
        frameborder: '0',
        src: 'about:blank'
      });
      this.iframe.appendTo((_ref2 = jQuery(this.options.appendTo).get(0)) != null ? _ref2 : 'body');
      this.iframe.load(__bind(function() {
        return this.initializeFrame();
      }, this));
      this.iframe.get(0).contentWindow.document.location.href = this.iframeSrc();
      this.toolbar = new Mercury.Toolbar(this.options);
      this.statusbar = new Mercury.Statusbar(this.options);
      return this.resize();
    };
    PageEditor.prototype.initializeFrame = function() {
      var iframeWindow, stylesToInject;
      try {
        if (this.iframe.data('loaded')) {
          return;
        }
        this.iframe.data('loaded', true);
        if (jQuery.browser.opera) {
          alert("Opera isn't a fully supported browser, your results may not be optimal.");
        }
        this.document = jQuery(this.iframe.get(0).contentWindow.document);
        stylesToInject = Mercury.config.injectedStyles.replace(/{{regionClass}}/g, Mercury.config.regionClass);
        jQuery("<style mercury-styles=\"true\">").html(stylesToInject).appendTo(this.document.find('head'));
        iframeWindow = this.iframe.get(0).contentWindow;
        jQuery.globalEval = function(data) {
          if (data && /\S/.test(data)) {
            return (iframeWindow.execScript || function(data) {
              return iframeWindow["eval"].call(iframeWindow, data);
            })(data);
          }
        };
        iframeWindow.Mercury = Mercury;
        if (window.History && History.Adapter) {
          iframeWindow.History = History;
        }
        this.bindEvents();
        this.resize();
        this.initializeRegions();
        this.finalizeInterface();
        Mercury.trigger('ready');
        jQuery(iframeWindow).trigger('mercury:ready');
        if (iframeWindow.Event && iframeWindow.Event.fire) {
          iframeWindow.Event.fire(iframeWindow, 'mercury:ready');
        }
        if (iframeWindow.onMercuryReady) {
          iframeWindow.onMercuryReady();
        }
        return this.iframe.css({
          visibility: 'visible'
        });
      } catch (error) {
        return alert("Mercury.PageEditor failed to load: " + error + "\n\nPlease try refreshing.");
      }
    };
    PageEditor.prototype.initializeRegions = function() {
      var region, _i, _j, _len, _len2, _ref, _ref2, _results;
      this.regions = [];
      _ref = jQuery("." + Mercury.config.regionClass, this.document);
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        region = _ref[_i];
        this.buildRegion(jQuery(region));
      }
      if (!this.options.visible) {
        return;
      }
      _ref2 = this.regions;
      _results = [];
      for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
        region = _ref2[_j];
        if (region.focus) {
          region.focus();
          break;
        }
      }
      return _results;
    };
    PageEditor.prototype.buildRegion = function(region) {
      var type;
      try {
        if (region.data('region')) {
          region = region.data('region');
        } else {
          type = region.data('type').titleize();
          region = new Mercury.Regions[type](region, this.iframe.get(0).contentWindow);
          if (this.previewing) {
            region.togglePreview();
          }
        }
        return this.regions.push(region);
      } catch (error) {
        if (Mercury.debug) {
          alert(error);
        }
        return alert("Region type is malformed, no data-type provided, or \"" + type + "\" is unknown for \"" + (region.id || 'unknown') + "\".");
      }
    };
    PageEditor.prototype.finalizeInterface = function() {
      var _ref;
      this.santizerElement = jQuery('<div>', {
        id: 'mercury_sanitizer',
        contenteditable: 'true',
        style: 'position:fixed;width:100px;height:100px;top:0;left:-100px;opacity:0;overflow:hidden'
      });
      this.santizerElement.appendTo((_ref = this.options.appendTo) != null ? _ref : this.document.find('body'));
      this.snippetToolbar = new Mercury.SnippetToolbar(this.document);
      this.hijackLinksAndForms();
      if (!this.options.visible) {
        return Mercury.trigger('mode', {
          mode: 'preview'
        });
      }
    };
    PageEditor.prototype.bindEvents = function() {
      Mercury.bind('initialize:frame', __bind(function() {
        return setTimeout(this.initializeFrame, 1000);
      }, this));
      Mercury.bind('focus:frame', __bind(function() {
        return this.iframe.focus();
      }, this));
      Mercury.bind('focus:window', __bind(function() {
        return setTimeout((__bind(function() {
          return this.focusableElement.focus();
        }, this)), 10);
      }, this));
      Mercury.bind('toggle:interface', __bind(function() {
        return this.toggleInterface();
      }, this));
      Mercury.bind('reinitialize', __bind(function() {
        return this.initializeRegions();
      }, this));
      Mercury.bind('mode', __bind(function(event, options) {
        if (options.mode === 'preview') {
          return this.previewing = !this.previewing;
        }
      }, this));
      Mercury.bind('action', __bind(function(event, options) {
        if (options.action === 'save') {
			
          return this.save();
        }
      }, this));
      this.document.mousedown(function(event) {
        Mercury.trigger('hide:dialogs');
        if (Mercury.region) {
          if (jQuery(event.target).closest("." + Mercury.config.regionClass).get(0) !== Mercury.region.element.get(0)) {
            return Mercury.trigger('unfocus:regions');
          }
        }
      });
      jQuery(window).resize(__bind(function() {
        return this.resize();
      }, this));
      return window.onbeforeunload = this.beforeUnload;
    };
    PageEditor.prototype.toggleInterface = function() {
      if (this.visible) {
        if (this.previewing) {
          Mercury.trigger('mode', {
            mode: 'preview'
          });
        }
        this.visible = false;
        this.toolbar.hide();
        this.statusbar.hide();
      } else {
        this.visible = true;
        this.toolbar.show();
        this.statusbar.show();
      }
      Mercury.trigger('mode', {
        mode: 'preview'
      });
      return this.resize();
    };
    PageEditor.prototype.resize = function() {
      var height, toolbarHeight, width;
      width = jQuery(window).width();
      height = this.statusbar.top();
      toolbarHeight = this.toolbar.height();
      Mercury.displayRect = {
        top: toolbarHeight,
        left: 0,
        width: width,
        height: height - toolbarHeight,
        fullHeight: height
      };
      this.iframe.css({
        top: toolbarHeight,
        left: 0,
        height: height - toolbarHeight
      });
      return Mercury.trigger('resize');
    };
    PageEditor.prototype.iframeSrc = function(url) {
      if (url == null) {
        url = null;
      }
      return (url != null ? url : window.location.href).replace(/([http|https]:\/\/.[^\/]*)\/editor\/?(.*)/i, "$1/$2");
    };
    PageEditor.prototype.hijackLinksAndForms = function() {
      var classname, element, ignored, _i, _j, _len, _len2, _ref, _ref2, _results;
      _ref = jQuery('a, form', this.document);
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        element = _ref[_i];
        ignored = false;
        _ref2 = Mercury.config.nonHijackableClasses || [];
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          classname = _ref2[_j];
          if (jQuery(element).hasClass(classname)) {
            ignored = true;
            continue;
          }
        }
        _results.push(!ignored && (element.target === '' || element.target === '_self') && !jQuery(element).closest("." + Mercury.config.regionClass).length ? jQuery(element).attr('target', '_top') : void 0);
      }
      return _results;
    };
    PageEditor.prototype.beforeUnload = function() {
      if (Mercury.changes && !Mercury.silent) {
        return "You have unsaved changes.  Are you sure you want to leave without saving them first?";
      }
      return null;
    };
    PageEditor.prototype.getRegionByName = function(id) {
      var region, _i, _len, _ref;
      _ref = this.regions;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        region = _ref[_i];
        if (region.name === id) {
          return region;
        }
      }
      return null;
    };
    PageEditor.prototype.save = function(callback) {
		
	  
		
		mw_save_all()
		 
          Mercury.changes = false;
          return Mercury.trigger('saved');
		
		
      var data, method, url, _ref, _ref2;
      url = (_ref = (_ref2 = this.saveUrl) != null ? _ref2 : Mercury.saveURL) != null ? _ref : this.iframeSrc();
      data = this.serialize();
      Mercury.log('saving', data);
      if (this.options.saveStyle !== 'form') {
        data = jQuery.toJSON(data);
      }
      if (this.options.saveMethod === 'PUT') {
        method = 'PUT';
      }
	  
      return jQuery.ajax(url, {
        headers: Mercury.ajaxHeaders(),
        type: method || 'POST',
        dataType: this.options.saveDataType || 'json',
        data: {
          content: data,
          _method: method
        },
        success: __bind(function() {
          if (callback) {
            callback();
          }
          Mercury.changes = false;
          return Mercury.trigger('saved');
        }, this),
        error: __bind(function() {
          return alert("Mercury was unable to save to the url: " + url);
        }, this)
      });
    };
    PageEditor.prototype.serialize = function() {
      var region, serialized, _i, _len, _ref;
      serialized = {};
      _ref = this.regions;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        region = _ref[_i];
        serialized[region.name] = region.serialize();
      }
      return serialized;
    };
    return PageEditor;
  })();
}).call(this);
(function() {
  this.Mercury.HistoryBuffer = (function() {
    function HistoryBuffer(maxLength) {
      this.maxLength = maxLength != null ? maxLength : 200;
      this.index = 0;
      this.stack = [];
      this.markerRegExp = /<em class="mercury-marker"><\/em>/g;
    }
    HistoryBuffer.prototype.push = function(item) {
      if (jQuery.type(item) === 'string') {
        if (this.stack[this.index] && this.stack[this.index].replace(this.markerRegExp, '') === item.replace(this.markerRegExp, '')) {
          return;
        }
      } else if (jQuery.type(item) === 'object' && item.html) {
        if (this.stack[this.index] && this.stack[this.index].html === item.html) {
          return;
        }
      }
      this.stack = this.stack.slice(0, this.index + 1);
      this.stack.push(item);
      if (this.stack.length > this.maxLength) {
        this.stack.shift();
      }
      return this.index = this.stack.length - 1;
    };
    HistoryBuffer.prototype.undo = function() {
      if (this.index < 1) {
        return null;
      }
      this.index -= 1;
      return this.stack[this.index];
    };
    HistoryBuffer.prototype.redo = function() {
      if (this.index >= this.stack.length - 1) {
        return null;
      }
      this.index += 1;
      return this.stack[this.index];
    };
    return HistoryBuffer;
  })();
}).call(this);
(function() {
  this.Mercury.tableEditor = function(table, cell, cellContent) {
    Mercury.tableEditor.load(table, cell, cellContent);
    return Mercury.tableEditor;
  };
  jQuery.extend(Mercury.tableEditor, {
    load: function(table, cell, cellContent) {
      this.table = table;
      this.cell = cell;
      this.cellContent = cellContent != null ? cellContent : '';
      this.row = this.cell.parent('tr');
      this.columnCount = this.getColumnCount();
      return this.rowCount = this.getRowCount();
    },
    addColumn: function(position) {
      var i, intersecting, matchOptions, matching, newCell, row, rowSpan, sig, _len, _ref, _results;
      if (position == null) {
        position = 'after';
      }
      sig = this.cellSignatureFor(this.cell);
      _ref = this.table.find('tr');
      _results = [];
      for (i = 0, _len = _ref.length; i < _len; i++) {
        row = _ref[i];
        rowSpan = 1;
        matchOptions = position === 'after' ? {
          right: sig.right
        } : {
          left: sig.left
        };
        _results.push((matching = this.findCellByOptionsFor(row, matchOptions)) ? (newCell = jQuery("<" + (matching.cell.get(0).tagName) + ">").html(this.cellContent), this.setRowspanFor(newCell, matching.height), position === 'before' ? matching.cell.before(newCell) : matching.cell.after(newCell), i += matching.height - 1) : (intersecting = this.findCellByIntersectionFor(row, sig)) ? this.setColspanFor(intersecting.cell, intersecting.width + 1) : void 0);
      }
      return _results;
    },
    removeColumn: function() {
      var adjusting, cell, i, intersecting, matching, removing, row, sig, _i, _j, _len, _len2, _len3, _ref, _results;
      sig = this.cellSignatureFor(this.cell);
      if (sig.width > 1) {
        return;
      }
      removing = [];
      adjusting = [];
      _ref = this.table.find('tr');
      for (i = 0, _len = _ref.length; i < _len; i++) {
        row = _ref[i];
        if (matching = this.findCellByOptionsFor(row, {
          left: sig.left,
          width: sig.width
        })) {
          removing.push(matching.cell);
          i += matching.height - 1;
        } else if (intersecting = this.findCellByIntersectionFor(row, sig)) {
          adjusting.push(intersecting.cell);
        }
      }
      for (_i = 0, _len2 = removing.length; _i < _len2; _i++) {
        cell = removing[_i];
        jQuery(cell).remove();
      }
      _results = [];
      for (_j = 0, _len3 = adjusting.length; _j < _len3; _j++) {
        cell = adjusting[_j];
        _results.push(this.setColspanFor(cell, this.colspanFor(cell) - 1));
      }
      return _results;
    },
    addRow: function(position) {
      var cell, cellCount, colspan, newCell, newRow, previousRow, rowCount, rowspan, _i, _j, _k, _len, _len2, _len3, _ref, _ref2, _ref3;
      if (position == null) {
        position = 'after';
      }
      newRow = jQuery('<tr>');
      if ((rowspan = this.rowspanFor(this.cell)) > 1 && position === 'after') {
        this.row = jQuery(this.row.nextAll('tr')[rowspan - 2]);
      }
      cellCount = 0;
      _ref = this.row.find('th, td');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        cell = _ref[_i];
        colspan = this.colspanFor(cell);
        newCell = jQuery("<" + cell.tagName + ">").html(this.cellContent);
        this.setColspanFor(newCell, colspan);
        cellCount += colspan;
        if ((rowspan = this.rowspanFor(cell)) > 1 && position === 'after') {
          this.setRowspanFor(cell, rowspan + 1);
          continue;
        }
        newRow.append(newCell);
      }
      if (cellCount < this.columnCount) {
        rowCount = 0;
        _ref2 = this.row.prevAll('tr');
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          previousRow = _ref2[_j];
          rowCount += 1;
          _ref3 = jQuery(previousRow).find('td[rowspan], th[rowspan]');
          for (_k = 0, _len3 = _ref3.length; _k < _len3; _k++) {
            cell = _ref3[_k];
            rowspan = this.rowspanFor(cell);
            if (rowspan - 1 >= rowCount && position === 'before') {
              this.setRowspanFor(cell, rowspan + 1);
            } else if (rowspan - 1 >= rowCount && position === 'after') {
              if (rowspan - 1 === rowCount) {
                newCell = jQuery("<" + cell.tagName + ">").html(this.cellContent);
                this.setColspanFor(newCell, this.colspanFor(cell));
                newRow.append(newCell);
              } else {
                this.setRowspanFor(cell, rowspan + 1);
              }
            }
          }
        }
      }
      if (position === 'before') {
        return this.row.before(newRow);
      } else {
        return this.row.after(newRow);
      }
    },
    removeRow: function() {
      var aboveRow, cell, i, match, minRowspan, prevRowspan, rowsAbove, rowspan, rowspansMatch, sig, _i, _j, _k, _l, _len, _len2, _len3, _len4, _ref, _ref2, _ref3, _ref4, _ref5;
      rowspansMatch = true;
      prevRowspan = 0;
      minRowspan = 0;
      _ref = this.row.find('td, th');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        cell = _ref[_i];
        rowspan = this.rowspanFor(cell);
        if (prevRowspan && rowspan !== prevRowspan) {
          rowspansMatch = false;
        }
        if (rowspan < minRowspan || !minRowspan) {
          minRowspan = rowspan;
        }
        prevRowspan = rowspan;
      }
      if (!rowspansMatch && this.rowspanFor(this.cell) > minRowspan) {
        return;
      }
      if (minRowspan > 1) {
        for (i = 0, _ref2 = minRowspan - 2; 0 <= _ref2 ? i <= _ref2 : i >= _ref2; 0 <= _ref2 ? i++ : i--) {
          jQuery(this.row.nextAll('tr')[i]).remove();
        }
      }
      _ref3 = this.row.find('td[rowspan], th[rowspan]');
      for (_j = 0, _len2 = _ref3.length; _j < _len2; _j++) {
        cell = _ref3[_j];
        sig = this.cellSignatureFor(cell);
        if (sig.height === minRowspan) {
          continue;
        }
        if (match = this.findCellByOptionsFor(this.row.nextAll('tr')[minRowspan - 1], {
          left: sig.left,
          forceAdjacent: true
        })) {
          this.setRowspanFor(cell, this.rowspanFor(cell) - this.rowspanFor(this.cell));
          if (match.direction === 'before') {
            match.cell.before(jQuery(cell).clone());
          } else {
            match.cell.after(jQuery(cell).clone());
          }
        }
      }
      if (this.columnsFor(this.row.find('td, th')) < this.columnCount) {
        rowsAbove = 0;
        _ref4 = this.row.prevAll('tr');
        for (_k = 0, _len3 = _ref4.length; _k < _len3; _k++) {
          aboveRow = _ref4[_k];
          rowsAbove += 1;
          _ref5 = jQuery(aboveRow).find('td[rowspan], th[rowspan]');
          for (_l = 0, _len4 = _ref5.length; _l < _len4; _l++) {
            cell = _ref5[_l];
            rowspan = this.rowspanFor(cell);
            if (rowspan > rowsAbove) {
              this.setRowspanFor(cell, rowspan - this.rowspanFor(this.cell));
            }
          }
        }
      }
      return this.row.remove();
    },
    increaseColspan: function() {
      var cell;
      cell = this.cell.next('td, th');
      if (!cell.length) {
        return;
      }
      if (this.rowspanFor(cell) !== this.rowspanFor(this.cell)) {
        return;
      }
      if (this.cellIndexFor(cell) > this.cellIndexFor(this.cell) + this.colspanFor(this.cell)) {
        return;
      }
      this.setColspanFor(this.cell, this.colspanFor(this.cell) + this.colspanFor(cell));
      return cell.remove();
    },
    decreaseColspan: function() {
      var newCell;
      if (this.colspanFor(this.cell) === 1) {
        return;
      }
      this.setColspanFor(this.cell, this.colspanFor(this.cell) - 1);
      newCell = jQuery("<" + (this.cell.get(0).tagName) + ">").html(this.cellContent);
      this.setRowspanFor(newCell, this.rowspanFor(this.cell));
      return this.cell.after(newCell);
    },
    increaseRowspan: function() {
      var match, nextRow, sig;
      sig = this.cellSignatureFor(this.cell);
      nextRow = this.row.nextAll('tr')[sig.height - 1];
      if (nextRow && (match = this.findCellByOptionsFor(nextRow, {
        left: sig.left,
        width: sig.width
      }))) {
        this.setRowspanFor(this.cell, sig.height + match.height);
        return match.cell.remove();
      }
    },
    decreaseRowspan: function() {
      var match, newCell, nextRow, sig;
      sig = this.cellSignatureFor(this.cell);
      if (sig.height === 1) {
        return;
      }
      nextRow = this.row.nextAll('tr')[sig.height - 2];
      if (match = this.findCellByOptionsFor(nextRow, {
        left: sig.left,
        forceAdjacent: true
      })) {
        newCell = jQuery("<" + (this.cell.get(0).tagName) + ">").html(this.cellContent);
        this.setColspanFor(newCell, this.colspanFor(this.cell));
        this.setRowspanFor(this.cell, sig.height - 1);
        if (match.direction === 'before') {
          return match.cell.before(newCell);
        } else {
          return match.cell.after(newCell);
        }
      }
    },
    getColumnCount: function() {
      return this.columnsFor(this.table.find('thead tr:first-child, tbody tr:first-child, tfoot tr:first-child').first().find('td, th'));
    },
    getRowCount: function() {
      return this.table.find('tr').length;
    },
    cellIndexFor: function(cell) {
      var aboveCell, aboveRow, columns, index, row, rowsAbove, _i, _j, _len, _len2, _ref, _ref2;
      cell = jQuery(cell);
      row = cell.parent('tr');
      columns = this.columnsFor(row.find('td, th'));
      index = this.columnsFor(cell.prevAll('td, th'));
      if (columns < this.columnCount) {
        rowsAbove = 0;
        _ref = row.prevAll('tr');
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          aboveRow = _ref[_i];
          rowsAbove += 1;
          _ref2 = jQuery(aboveRow).find('td[rowspan], th[rowspan]');
          for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
            aboveCell = _ref2[_j];
            if (this.rowspanFor(aboveCell) > rowsAbove && this.cellIndexFor(aboveCell) <= index) {
              index += this.colspanFor(aboveCell);
            }
          }
        }
      }
      return index;
    },
    cellSignatureFor: function(cell) {
      var sig;
      sig = {
        cell: jQuery(cell)
      };
      sig.left = this.cellIndexFor(cell);
      sig.width = this.colspanFor(cell);
      sig.height = this.rowspanFor(cell);
      sig.right = sig.left + sig.width;
      return sig;
    },
    findCellByOptionsFor: function(row, options) {
      var cell, prev, sig, _i, _len, _ref;
      _ref = jQuery(row).find('td, th');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        cell = _ref[_i];
        sig = this.cellSignatureFor(cell);
        if (typeof options.right !== 'undefined') {
          if (sig.right === options.right) {
            return sig;
          }
        }
        if (typeof options.left !== 'undefined') {
          if (options.width) {
            if (sig.left === options.left && sig.width === options.width) {
              return sig;
            }
          } else if (!options.forceAdjacent) {
            if (sig.left === options.left) {
              return sig;
            }
          } else if (options.forceAdjacent) {
            if (sig.left > options.left) {
              prev = jQuery(cell).prev('td, th');
              if (prev.length) {
                sig = this.cellSignatureFor(prev);
                sig.direction = 'after';
              } else {
                sig.direction = 'before';
              }
              return sig;
            }
          }
        }
      }
      if (options.forceAdjacent) {
        sig.direction = 'after';
        return sig;
      }
      return null;
    },
    findCellByIntersectionFor: function(row, signature) {
      var cell, sig, _i, _len, _ref;
      _ref = jQuery(row).find('td, th');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        cell = _ref[_i];
        sig = this.cellSignatureFor(cell);
        if (sig.right - signature.left >= 0 && sig.right > signature.left) {
          return sig;
        }
      }
      return null;
    },
    columnsFor: function(cells) {
      var cell, count, _i, _len;
      count = 0;
      for (_i = 0, _len = cells.length; _i < _len; _i++) {
        cell = cells[_i];
        count += this.colspanFor(cell);
      }
      return count;
    },
    colspanFor: function(cell) {
      return parseInt(jQuery(cell).attr('colspan')) || 1;
    },
    rowspanFor: function(cell) {
      return parseInt(jQuery(cell).attr('rowspan')) || 1;
    },
    setColspanFor: function(cell, value) {
      return jQuery(cell).attr('colspan', value > 1 ? value : null);
    },
    setRowspanFor: function(cell, value) {
      return jQuery(cell).attr('rowspan', value > 1 ? value : null);
    }
  });
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Dialog = (function() {
    function Dialog(url, name, options) {
      this.url = url;
      this.name = name;
      this.options = options != null ? options : {};
      this.button = this.options["for"];
      this.build();
      this.bindEvents();
      this.preload();
    }
    Dialog.prototype.build = function() {
      var _ref;
      this.element = jQuery('<div>', {
        "class": "mercury-dialog mercury-" + this.name + "-dialog loading",
        style: 'display:none'
      });
      return this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
    };
    Dialog.prototype.bindEvents = function() {
      return this.element.mousedown(function(event) {
        return event.stopPropagation();
      });
    };
    Dialog.prototype.preload = function() {
      if (this.options.preload) {
        return this.load();
      }
    };
    Dialog.prototype.toggle = function() {
      if (this.visible) {
        return this.hide();
      } else {
        return this.show();
      }
    };
    Dialog.prototype.resize = function() {
      return this.show();
    };
    Dialog.prototype.show = function() {
      Mercury.trigger('hide:dialogs', this);
      this.visible = true;
      if (this.loaded) {
        this.element.css({
          width: 'auto',
          height: 'auto'
        });
        this.position(this.visible);
      } else {
        this.position();
      }
      return this.appear();
    };
    Dialog.prototype.position = function(keepVisible) {};
    Dialog.prototype.appear = function() {
      this.element.css({
        display: 'block',
        opacity: 0
      });
      return this.element.animate({
        opacity: 0.95
      }, 200, 'easeInOutSine', __bind(function() {
        if (!this.loaded) {
          return this.load(__bind(function() {
            return this.resize();
          }, this));
        }
      }, this));
    };
    Dialog.prototype.hide = function() {
      this.element.hide();
      return this.visible = false;
    };
    Dialog.prototype.load = function(callback) {
      if (!this.url) {
        return;
      }
      if (Mercury.preloadedViews[this.url]) {
        this.loadContent(Mercury.preloadedViews[this.url]);
        if (Mercury.dialogHandlers[this.name]) {
          Mercury.dialogHandlers[this.name].call(this);
        }
        if (callback) {
          return callback();
        }
      } else {
        return jQuery.ajax(this.url, {
          success: __bind(function(data) {
            this.loadContent(data);
            if (Mercury.dialogHandlers[this.name]) {
              Mercury.dialogHandlers[this.name].call(this);
            }
            if (callback) {
              return callback();
            }
          }, this),
          error: __bind(function() {
            this.hide();
            if (this.button) {
              this.button.removeClass('pressed');
            }
            return alert("Mercury was unable to load " + this.url + " for the " + this.name + " dialog.");
          }, this)
        });
      }
    };
    Dialog.prototype.loadContent = function(data) {
      this.loaded = true;
      this.element.removeClass('loading');
      return this.element.html(data);
    };
    return Dialog;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Palette = (function() {
    __extends(Palette, Mercury.Dialog);
    function Palette(url, name, options) {
      this.url = url;
      this.name = name;
      this.options = options != null ? options : {};
      Palette.__super__.constructor.apply(this, arguments);
    }
    Palette.prototype.build = function() {
      var _ref;
      this.element = jQuery('<div>', {
        "class": "mercury-palette mercury-" + this.name + "-palette loading",
        style: 'display:none'
      });
      return this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
    };
    Palette.prototype.bindEvents = function() {
      Mercury.bind('hide:dialogs', __bind(function(event, dialog) {
        if (dialog !== this) {
          return this.hide();
        }
      }, this));
      return Palette.__super__.bindEvents.apply(this, arguments);
    };
    Palette.prototype.position = function(keepVisible) {
      var position, width;
      this.element.css({
        top: 0,
        left: 0,
        display: 'block',
        visibility: 'hidden'
      });
      position = this.button.offset();
      width = this.element.width();
      if (position.left + width > jQuery(window).width()) {
        position.left = position.left - width + this.button.width();
      }
      return this.element.css({
        top: position.top + this.button.height(),
        left: position.left,
        display: keepVisible ? 'block' : 'none',
        visibility: 'visible'
      });
    };
    return Palette;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Select = (function() {
    __extends(Select, Mercury.Dialog);
    function Select(url, name, options) {
      this.url = url;
      this.name = name;
      this.options = options != null ? options : {};
      Select.__super__.constructor.apply(this, arguments);
    }
    Select.prototype.build = function() {
      var _ref;
      this.element = jQuery('<div>', {
        "class": "mercury-select mercury-" + this.name + "-select loading",
        style: 'display:none'
      });
      return this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
    };
    Select.prototype.bindEvents = function() {
      Mercury.bind('hide:dialogs', __bind(function(event, dialog) {
        if (dialog !== this) {
          return this.hide();
        }
      }, this));
      this.element.mousedown(__bind(function(event) {
        return event.preventDefault();
      }, this));
      return Select.__super__.bindEvents.apply(this, arguments);
    };
    Select.prototype.position = function(keepVisible) {
      var documentHeight, elementHeight, elementWidth, height, left, position, top;
      this.element.css({
        top: 0,
        left: 0,
        display: 'block',
        visibility: 'hidden'
      });
      position = this.button.offset();
      elementWidth = this.element.width();
      elementHeight = this.element.height();
      documentHeight = jQuery(document).height();
      top = position.top + (this.button.height() / 2) - (elementHeight / 2);
      if (top < position.top - 100) {
        top = position.top - 100;
      }
      if (top < 20) {
        top = 20;
      }
      height = this.loaded ? 'auto' : elementHeight;
      if (top + elementHeight >= documentHeight - 20) {
        height = documentHeight - top - 20;
      }
      left = position.left;
      if (left + elementWidth > jQuery(window).width()) {
        left = left - elementWidth + this.button.width();
      }
      return this.element.css({
        top: top,
        left: left,
        height: height,
        display: keepVisible ? 'block' : 'none',
        visibility: 'visible'
      });
    };
    return Select;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Panel = (function() {
    __extends(Panel, Mercury.Dialog);
    function Panel(url, name, options) {
      this.url = url;
      this.name = name;
      this.options = options != null ? options : {};
      Panel.__super__.constructor.apply(this, arguments);
    }
    Panel.prototype.build = function() {
      var _ref;
      this.element = jQuery('<div>', {
        "class": 'mercury-panel loading',
        style: 'display:none;'
      });
      this.titleElement = jQuery("<h1>" + this.options.title + "</h1>").appendTo(this.element);
      this.paneElement = jQuery('<div>', {
        "class": 'mercury-panel-pane'
      }).appendTo(this.element);
      return this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
    };
    Panel.prototype.bindEvents = function() {
      Mercury.bind('resize', __bind(function() {
        return this.position(this.visible);
      }, this));
      Mercury.bind('hide:panels', __bind(function(event, panel) {
        if (panel !== this) {
          this.button.removeClass('pressed');
          return this.hide();
        }
      }, this));
      this.element.mousedown(function(event) {
        return event.stopPropagation();
      });
      return Panel.__super__.bindEvents.apply(this, arguments);
    };
    Panel.prototype.show = function() {
      Mercury.trigger('hide:panels', this);
      return Panel.__super__.show.apply(this, arguments);
    };
    Panel.prototype.resize = function() {
      var position, postWidth, preWidth;
      this.paneElement.css({
        display: 'none'
      });
      preWidth = this.element.width();
      this.paneElement.css({
        visibility: 'hidden',
        width: 'auto',
        display: 'block'
      });
      postWidth = this.element.width();
      this.paneElement.css({
        visibility: 'visible',
        display: 'none'
      });
      position = this.element.offset();
      this.element.animate({
        left: position.left - (postWidth - preWidth),
        width: postWidth
      }, 200, 'easeInOutSine', __bind(function() {
        this.paneElement.css({
          display: 'block',
          width: postWidth
        });
        return this.makeDraggable();
      }, this));
      if (!this.visible) {
        return this.hide();
      }
    };
    Panel.prototype.position = function(keepVisible) {
      var elementWidth, height, left, offset, paneHeight;
      this.element.css({
        display: 'block',
        visibility: 'hidden'
      });
      offset = this.element.offset();
      elementWidth = this.element.width();
      height = Mercury.displayRect.height - 16;
      paneHeight = height - this.titleElement.outerHeight();
      this.paneElement.css({
        height: paneHeight,
        overflowY: paneHeight < 30 ? 'hidden' : 'auto'
      });
      if (!this.moved) {
        left = Mercury.displayRect.width - elementWidth - 20;
      }
      if (left <= 8) {
        left = 8;
      }
      if (this.pinned || elementWidth + offset.left > Mercury.displayRect.width - 20) {
        left = Mercury.displayRect.width - elementWidth - 20;
      }
      this.element.css({
        top: Mercury.displayRect.top + 8,
        left: left,
        height: height,
        display: keepVisible ? 'block' : 'none',
        visibility: 'visible'
      });
      this.makeDraggable();
      if (!keepVisible) {
        return this.element.hide();
      }
    };
    Panel.prototype.loadContent = function(data) {
      this.loaded = true;
      this.element.removeClass('loading');
      this.paneElement.css({
        visibility: 'hidden'
      });
      return this.paneElement.html(data);
    };
    Panel.prototype.makeDraggable = function() {
      var elementWidth;
      elementWidth = this.element.width();
      return this.element.draggable({
        handle: 'h1',
        axis: 'x',
        opacity: 0.70,
        scroll: false,
        addClasses: false,
        iframeFix: true,
        containment: [8, 0, Mercury.displayRect.width - elementWidth - 20, 0],
        stop: __bind(function() {
          var left;
          left = this.element.offset().left;
          this.moved = true;
          this.pinned = left > Mercury.displayRect.width - elementWidth - 30 ? true : false;
          return true;
        }, this)
      });
    };
    return Panel;
  })();
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.modal = function(url, options) {
    if (options == null) {
      options = {};
    }
    Mercury.modal.show(url, options);
    return Mercury.modal;
  };
  jQuery.extend(Mercury.modal, {
    minWidth: 400,
    show: function(url, options) {
      this.url = url;
      this.options = options != null ? options : {};
      Mercury.trigger('focus:window');
      this.initialize();
      if (this.visible) {
        return this.update();
      } else {
        return this.appear();
      }
    },
    initialize: function() {
      if (this.initialized) {
        return;
      }
      this.build();
      this.bindEvents();
      return this.initialized = true;
    },
    build: function() {
      var _ref, _ref2;
      this.element = jQuery('<div>', {
        "class": 'mercury-modal loading'
      });
      this.element.html('<h1 class="mercury-modal-title"><span></span><a>&times;</a></h1>');
      this.element.append('<div class="mercury-modal-content-container"><div class="mercury-modal-content"></div></div>');
      this.overlay = jQuery('<div>', {
        "class": 'mercury-modal-overlay'
      });
      this.titleElement = this.element.find('.mercury-modal-title');
      this.contentContainerElement = this.element.find('.mercury-modal-content-container');
      this.contentElement = this.element.find('.mercury-modal-content');
      this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
      this.overlay.appendTo((_ref2 = jQuery(this.options.appendTo).get(0)) != null ? _ref2 : 'body');
      return this.titleElement.find('span').html(this.options.title);
    },
    bindEvents: function() {
      Mercury.bind('refresh', __bind(function() {
        return this.resize(true);
      }, this));
      Mercury.bind('resize', __bind(function() {
        return this.position();
      }, this));
      this.overlay.click(__bind(function() {
        return this.hide();
      }, this));
      this.titleElement.find('a').click(__bind(function() {
        return this.hide();
      }, this));
      return jQuery(document).bind('keydown', __bind(function(event) {
        if (event.keyCode === 27 && this.visible) {
          return this.hide();
        }
      }, this));
    },
    appear: function() {
      this.showing = true;
      this.position();
      this.overlay.show();
      return this.overlay.animate({
        opacity: 1
      }, 200, 'easeInOutSine', __bind(function() {
        this.element.css({
          top: -this.element.height()
        });
        this.setTitle();
        this.element.show();
        return this.element.animate({
          top: 0
        }, 200, 'easeInOutSine', __bind(function() {
          this.visible = true;
          this.showing = false;
          return this.load();
        }, this));
      }, this));
    },
    resize: function(keepVisible) {
      var height, titleHeight, visibility, width;
      visibility = keepVisible ? 'visible' : 'hidden';
      titleHeight = this.titleElement.outerHeight();
      width = this.contentElement.outerWidth();
      if (this.contentPane) {
        this.contentPane.css({
          height: 'auto'
        });
      }
      this.contentElement.css({
        height: 'auto',
        visibility: visibility,
        display: 'block'
      });
      height = this.contentElement.outerHeight() + titleHeight;
      if (width < this.minWidth) {
        width = this.minWidth;
      }
      if (height > Mercury.displayRect.fullHeight - 20 || this.options.fullHeight) {
        height = Mercury.displayRect.fullHeight - 20;
      }
      return this.element.stop().animate({
        left: (Mercury.displayRect.width - width) / 2,
        width: width,
        height: height
      }, 200, 'easeInOutSine', __bind(function() {
        var controlHeight;
        this.contentElement.css({
          visibility: 'visible',
          display: 'block'
        });
        if (this.contentPane.length) {
          this.contentElement.css({
            height: height - titleHeight,
            overflow: 'visible'
          });
          controlHeight = this.contentControl.length ? this.contentControl.outerHeight() : 0;
          this.contentPane.css({
            height: height - titleHeight - controlHeight - 40
          });
          return this.contentPane.find('.mercury-modal-pane').css({
            width: width - 40
          });
        } else {
          return this.contentElement.css({
            height: height - titleHeight,
            overflow: 'auto'
          });
        }
      }, this));
    },
    position: function() {
      var controlHeight, height, titleHeight, viewportWidth, width;
      viewportWidth = Mercury.displayRect.width;
      if (this.contentPane) {
        this.contentPane.css({
          height: 'auto'
        });
      }
      this.contentElement.css({
        height: 'auto'
      });
      this.element.css({
        width: 'auto',
        height: 'auto',
        display: 'block',
        visibility: 'hidden'
      });
      width = this.element.width();
      height = this.element.height();
      if (width < this.minWidth) {
        width = this.minWidth;
      }
      if (height > Mercury.displayRect.fullHeight - 20 || this.options.fullHeight) {
        height = Mercury.displayRect.fullHeight - 20;
      }
      titleHeight = this.titleElement.outerHeight();
      if (this.contentPane && this.contentPane.length) {
        this.contentElement.css({
          height: height - titleHeight,
          overflow: 'visible'
        });
        controlHeight = this.contentControl.length ? this.contentControl.outerHeight() : 0;
        this.contentPane.css({
          height: height - titleHeight - controlHeight - 40
        });
        this.contentPane.find('.mercury-modal-pane').css({
          width: width - 40
        });
      } else {
        this.contentElement.css({
          height: height - titleHeight,
          overflow: 'auto'
        });
      }
      return this.element.css({
        left: (viewportWidth - width) / 2,
        width: width,
        height: height,
        display: this.visible ? 'block' : 'none',
        visibility: 'visible'
      });
    },
    update: function() {
      this.reset();
      this.resize();
      return this.load();
    },
    load: function() {
      this.setTitle();
      if (!this.url) {
        return;
      }
      this.element.addClass('loading');
      if (Mercury.preloadedViews[this.url]) {
        return setTimeout((__bind(function() {
          return this.loadContent(Mercury.preloadedViews[this.url]);
        }, this)), 10);
      } else {
        return jQuery.ajax(this.url, {
          headers: Mercury.ajaxHeaders(),
          type: this.options.loadType || 'GET',
          data: this.options.loadData,
          success: __bind(function(data) {
            return this.loadContent(data);
          }, this),
          error: __bind(function() {
            this.hide();
            return alert("Mercury was unable to load " + this.url + " for the modal.");
          }, this)
        });
      }
    },
    loadContent: function(data, options) {
      if (options == null) {
        options = null;
      }
      this.initialize();
      this.options = options || this.options;
      this.setTitle();
      this.loaded = true;
      this.element.removeClass('loading');
      this.contentElement.html(data);
      this.contentElement.css({
        display: 'none',
        visibility: 'hidden'
      });
      this.contentPane = this.element.find('.mercury-modal-pane-container');
      this.contentControl = this.element.find('.mercury-modal-controls');
      if (this.options.afterLoad) {
        this.options.afterLoad.call(this);
      }
      if (this.options.handler && Mercury.modalHandlers[this.options.handler]) {
        Mercury.modalHandlers[this.options.handler].call(this);
      }
      return this.resize();
    },
    setTitle: function() {
      return this.titleElement.find('span').html(this.options.title);
    },
    reset: function() {
      this.titleElement.find('span').html('');
      return this.contentElement.html('');
    },
    hide: function() {
      if (this.showing) {
        return;
      }
      Mercury.trigger('focus:frame');
      this.element.hide();
      this.overlay.hide();
      this.reset();
      return this.visible = false;
    }
  });
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.lightview = function(url, options) {
    if (options == null) {
      options = {};
    }
    Mercury.lightview.show(url, options);
    return Mercury.lightview;
  };
  jQuery.extend(Mercury.lightview, {
    minWidth: 400,
    show: function(url, options) {
      this.url = url;
      this.options = options != null ? options : {};
      Mercury.trigger('focus:window');
      this.initialize();
      if (this.visible) {
        return this.update();
      } else {
        return this.appear();
      }
    },
    initialize: function() {
      if (this.initialized) {
        return;
      }
      this.build();
      this.bindEvents();
      return this.initialized = true;
    },
    build: function() {
      var _ref, _ref2;
      this.element = jQuery('<div>', {
        "class": 'mercury-lightview loading'
      });
      this.element.html('<h1 class="mercury-lightview-title"><span></span></h1>');
      this.element.append('<div class="mercury-lightview-content"></div>');
      this.overlay = jQuery('<div>', {
        "class": 'mercury-lightview-overlay'
      });
      this.titleElement = this.element.find('.mercury-lightview-title');
      if (this.options.closeButton) {
        this.titleElement.append('<a class="mercury-lightview-close"></a>');
      }
      this.contentElement = this.element.find('.mercury-lightview-content');
      this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
      this.overlay.appendTo((_ref2 = jQuery(this.options.appendTo).get(0)) != null ? _ref2 : 'body');
      return this.titleElement.find('span').html(this.options.title);
    },
    bindEvents: function() {
      Mercury.bind('refresh', __bind(function() {
        return this.resize(true);
      }, this));
      Mercury.bind('resize', __bind(function() {
        if (this.visible) {
          return this.position();
        }
      }, this));
      this.overlay.click(__bind(function() {
        if (!this.options.closeButton) {
          return this.hide();
        }
      }, this));
      this.titleElement.find('.mercury-lightview-close').click(__bind(function() {
        return this.hide();
      }, this));
      return jQuery(document).bind('keydown', __bind(function(event) {
        if (event.keyCode === 27 && this.visible) {
          return this.hide();
        }
      }, this));
    },
    appear: function() {
      this.position();
      this.overlay.show().css({
        opacity: 0
      });
      return this.overlay.animate({
        opacity: 1
      }, 200, 'easeInOutSine', __bind(function() {
        this.setTitle();
        this.element.show().css({
          opacity: 0
        });
        return this.element.stop().animate({
          opacity: 1
        }, 200, 'easeInOutSine', __bind(function() {
          this.visible = true;
          return this.load();
        }, this));
      }, this));
    },
    resize: function(keepVisible) {
      var height, viewportHeight, viewportWidth, width;
      viewportWidth = Mercury.displayRect.width;
      viewportHeight = Mercury.displayRect.fullHeight;
      this.element.css({
        overflow: 'hidden'
      });
      this.contentElement.css({
        visibility: 'hidden',
        display: 'none',
        width: 'auto',
        height: 'auto'
      });
      width = this.contentElement.outerWidth() + 40 + 2;
      if (width > viewportWidth - 40 || this.options.fullSize) {
        width = viewportWidth - 40;
      }
      height = this.contentElement.outerHeight() + this.titleElement.outerHeight() + 30;
      if (height > viewportHeight - 20 || this.options.fullSize) {
        height = viewportHeight - 20;
      }
      if (width < 300) {
        width = 300;
      }
      if (height < 150) {
        height = 150;
      }
      return this.element.stop().animate({
        top: ((viewportHeight - height) / 2) + 10,
        left: (Mercury.displayRect.width - width) / 2,
        width: width,
        height: height
      }, 200, 'easeInOutSine', __bind(function() {
        this.contentElement.css({
          visibility: 'visible',
          display: 'block',
          opacity: 0
        });
        this.contentElement.stop().animate({
          opacity: 1
        }, 200, 'easeInOutSine');
        return this.element.css({
          overflow: 'auto'
        });
      }, this));
    },
    position: function() {
      var height, viewportHeight, viewportWidth, width;
      viewportWidth = Mercury.displayRect.width;
      viewportHeight = Mercury.displayRect.fullHeight;
      this.contentElement.css({
        position: 'absolute',
        width: 'auto',
        height: 'auto'
      });
      width = this.contentElement.width() + 40 + 2;
      if (width > viewportWidth - 40 || this.options.fullSize) {
        width = viewportWidth - 40;
      }
      height = this.contentElement.height() + this.titleElement.outerHeight() + 30;
      if (height > viewportHeight - 20 || this.options.fullSize) {
        height = viewportHeight - 20;
      }
      this.contentElement.css({
        position: 'relative'
      });
      if (width < 300) {
        width = 300;
      }
      if (height < 150) {
        height = 150;
      }
      this.element.css({
        top: ((viewportHeight - height) / 2) + 10,
        left: (viewportWidth - width) / 2,
        width: width,
        height: height,
        overflow: 'auto'
      });
      if (this.visible) {
        return this.contentElement.css({
          width: width - 40,
          height: height - 30 - this.titleElement.outerHeight()
        });
      }
    },
    update: function() {
      this.reset();
      this.resize();
      return this.load();
    },
    load: function() {
      this.setTitle();
      if (!this.url) {
        return;
      }
      this.element.addClass('loading');
      if (Mercury.preloadedViews[this.url]) {
        return setTimeout((__bind(function() {
          return this.loadContent(Mercury.preloadedViews[this.url]);
        }, this)), 10);
      } else {
        return jQuery.ajax(this.url, {
          headers: Mercury.ajaxHeaders(),
          type: this.options.loadType || 'GET',
          data: this.options.loadData,
          success: __bind(function(data) {
            return this.loadContent(data);
          }, this),
          error: __bind(function() {
            this.hide();
            return alert("Mercury was unable to load " + this.url + " for the lightview.");
          }, this)
        });
      }
    },
    loadContent: function(data, options) {
      if (options == null) {
        options = null;
      }
      this.initialize();
      this.options = options || this.options;
      this.setTitle();
      this.loaded = true;
      this.element.removeClass('loading');
      this.contentElement.html(data);
      this.contentElement.css({
        display: 'none',
        visibility: 'hidden'
      });
      if (this.options.afterLoad) {
        this.options.afterLoad.call(this);
      }
      if (this.options.handler && Mercury.lightviewHandlers[this.options.handler]) {
        Mercury.lightviewHandlers[this.options.handler].call(this);
      }
      return this.resize();
    },
    setTitle: function() {
      return this.titleElement.find('span').html(this.options.title);
    },
    reset: function() {
      this.titleElement.find('span').html('');
      return this.contentElement.html('');
    },
    hide: function() {
      Mercury.trigger('focus:frame');
      this.element.hide();
      this.overlay.hide();
      this.reset();
      return this.visible = false;
    }
  });
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Statusbar = (function() {
    function Statusbar(options) {
      this.options = options != null ? options : {};
      this.visible = this.options.visible;
      this.build();
      this.bindEvents();
    }
    Statusbar.prototype.build = function() {
      var _ref;
      this.element = jQuery('<div>', {
        "class": 'mercury-statusbar'
      });
      this.aboutElement = jQuery('<a>', {
        "class": "mercury-statusbar-about"
      }).appendTo(this.element).html("Mercury Editor v" + Mercury.version);
      this.pathElement = jQuery('<div>', {
        "class": 'mercury-statusbar-path'
      }).appendTo(this.element);
      if (!this.visible) {
        this.element.css({
          visibility: 'hidden'
        });
      }
      return this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
    };
    Statusbar.prototype.bindEvents = function() {
      Mercury.bind('region:update', __bind(function(event, options) {
        if (options.region && jQuery.type(options.region.path) === 'function') {
          return this.setPath(options.region.path());
        }
      }, this));
      return this.aboutElement.click(__bind(function() {
        return Mercury.lightview('/mercury/lightviews/about.html', {
          title: "About Mercury Editor v" + Mercury.version,
          closeButton: true
        });
      }, this));
    };
    Statusbar.prototype.height = function() {
      return this.element.outerHeight();
    };
    Statusbar.prototype.top = function() {
      var currentTop, top;
      top = this.element.offset().top;
      currentTop = parseInt(this.element.css('bottom')) < 0 ? top - this.element.outerHeight() : top;
      if (this.visible) {
        return currentTop;
      } else {
        return top + this.element.outerHeight();
      }
    };
    Statusbar.prototype.setPath = function(elements) {
      var element, path, _i, _len;
      path = [];
      for (_i = 0, _len = elements.length; _i < _len; _i++) {
        element = elements[_i];
        path.push("<a>" + (element.tagName.toLowerCase()) + "</a>");
      }
      return this.pathElement.html("<span><strong>Path: </strong>" + (path.reverse().join(' &raquo; ')) + "</span>");
    };
    Statusbar.prototype.show = function() {
      this.visible = true;
      this.element.css({
        opacity: 0,
        visibility: 'visible'
      });
      return this.element.animate({
        opacity: 1
      }, 200, 'easeInOutSine');
    };
    Statusbar.prototype.hide = function() {
      this.visible = false;
      return this.element.css({
        visibility: 'hidden'
      });
    };
    return Statusbar;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Toolbar = (function() {
    function Toolbar(options) {
      this.options = options != null ? options : {};
      this.visible = this.options.visible;
      this.build();
      this.bindEvents();
    }
    Toolbar.prototype.build = function() {
      var button, buttonName, buttons, container, expander, options, toolbar, toolbarName, _ref, _ref2;
      this.element = jQuery('<div>', {
        "class": 'mercury-toolbar-container',
        style: 'width:10000px'
      });
      if (!this.visible) {
        this.element.css({
          display: 'none'
        });
      }
      this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
      _ref2 = Mercury.config.toolbars;
      for (toolbarName in _ref2) {
        if (!__hasProp.call(_ref2, toolbarName)) continue;
        buttons = _ref2[toolbarName];
        if (buttons._custom) {
          continue;
        }
        toolbar = jQuery('<div>', {
          "class": "mercury-toolbar mercury-" + toolbarName + "-toolbar"
        }).appendTo(this.element);
        if (buttons._regions) {
          toolbar.attr('data-regions', buttons._regions);
        }
        container = jQuery('<div>', {
          "class": 'mercury-toolbar-button-container'
        }).appendTo(toolbar);
        for (buttonName in buttons) {
          if (!__hasProp.call(buttons, buttonName)) continue;
          options = buttons[buttonName];
          if (buttonName === '_regions') {
            continue;
          }
          button = this.buildButton(buttonName, options);
          if (button) {
            button.appendTo(container);
          }
        }
        if (container.css('white-space') === 'nowrap') {
          expander = new Mercury.Toolbar.Expander(toolbarName, {
            appendTo: toolbar,
            "for": container
          });
          expander.appendTo(this.element);
        }
        if (Mercury.config.toolbars['primary'] && toolbarName !== 'primary') {
          toolbar.addClass('disabled');
        }
      }
      return this.element.css({
        width: '100%'
      });
    };
    Toolbar.prototype.buildButton = function(name, options) {
      var action, button, group, handled, opts, summary, title;
      if (name[0] === '_') {
        return false;
      }
      switch (jQuery.type(options)) {
        case 'array':
          title = options[0], summary = options[1], handled = options[2];
          return new Mercury.Toolbar.Button(name, title, summary, handled, {
            appendDialogsTo: this.element
          });
        case 'object':
          group = new Mercury.Toolbar.ButtonGroup(name, options);
          for (action in options) {
            if (!__hasProp.call(options, action)) continue;
            opts = options[action];
            button = this.buildButton(action, opts);
            if (button) {
              button.appendTo(group);
            }
          }
          return group;
        case 'string':
          return jQuery('<hr>', {
            "class": "mercury-" + (options === '-' ? 'line-separator' : 'separator')
          });
        default:
          throw "Unknown button structure -- please provide an array, object, or string for " + name + ".";
      }
    };
    Toolbar.prototype.bindEvents = function() {
      Mercury.bind('region:focused', __bind(function(event, options) {
        var regions, toolbar, _i, _len, _ref, _results;
        _ref = this.element.find(".mercury-toolbar");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          toolbar = _ref[_i];
          toolbar = jQuery(toolbar);
          _results.push((regions = toolbar.data('regions')) ? regions.split(',').indexOf(options.region.type) > -1 ? toolbar.removeClass('disabled') : void 0 : void 0);
        }
        return _results;
      }, this));
      Mercury.bind('region:blurred', __bind(function(event, options) {
        var regions, toolbar, _i, _len, _ref, _results;
        _ref = this.element.find(".mercury-toolbar");
        _results = [];
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          toolbar = _ref[_i];
          toolbar = jQuery(toolbar);
          _results.push((regions = toolbar.data('regions')) ? regions.split(',').indexOf(options.region.type) > -1 ? toolbar.addClass('disabled') : void 0 : void 0);
        }
        return _results;
      }, this));
      this.element.click(function() {
        return Mercury.trigger('hide:dialogs');
      });
      return this.element.mousedown(function(event) {
        return event.preventDefault();
      });
    };
    Toolbar.prototype.height = function() {
      if (this.visible) {
        return this.element.outerHeight();
      } else {
        return 0;
      }
    };
    Toolbar.prototype.show = function() {
      this.visible = true;
      this.element.css({
        top: -this.element.outerHeight(),
        display: 'block'
      });
      return this.element.animate({
        top: 0
      }, 200, 'easeInOutSine');
    };
    Toolbar.prototype.hide = function() {
      this.visible = false;
      return this.element.hide();
    };
    return Toolbar;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Toolbar.Button = (function() {
    function Button(name, title, summary, types, options) {
      this.name = name;
      this.title = title;
      this.summary = summary != null ? summary : null;
      this.types = types != null ? types : {};
      this.options = options != null ? options : {};
      this.build();
      this.bindEvents();
      return this.element;
    }
    Button.prototype.build = function() {
      var dialogOptions, mixed, type, url, _ref, _ref2, _results;
      this.element = jQuery('<div>', {
        title: (_ref = this.summary) != null ? _ref : this.title,
        "class": "mercury-button mercury-" + this.name + "-button"
      }).html("<em>" + this.title + "</em>");
      this.element.data('expander', "<div class=\"mercury-expander-button\" data-button=\"" + this.name + "\"><em></em><span>" + this.title + "</span></div>");
      this.handled = {};
      dialogOptions = {
        title: this.summary || this.title,
        preload: this.types.preload,
        appendTo: this.options.appendDialogsTo || 'body',
        "for": this.element
      };
      _ref2 = this.types;
      _results = [];
      for (type in _ref2) {
        if (!__hasProp.call(_ref2, type)) continue;
        mixed = _ref2[type];
        _results.push((function() {
          switch (type) {
            case 'preload':
              return true;
            case 'regions':
              this.element.addClass('disabled');
              return this.handled[type] = jQuery.isFunction(mixed) ? mixed.call(this, this.name) : mixed;
            case 'toggle':
              return this.handled[type] = true;
            case 'mode':
              return this.handled[type] = mixed === true ? this.name : mixed;
            case 'context':
              return this.handled[type] = jQuery.isFunction(mixed) ? mixed : Mercury.Toolbar.Button.contexts[this.name];
            case 'palette':
              this.element.addClass("mercury-button-palette");
              url = jQuery.isFunction(mixed) ? mixed.call(this, this.name) : mixed;
              return this.handled[type] = new Mercury.Palette(url, this.name, dialogOptions);
            case 'select':
              this.element.addClass("mercury-button-select").find('em').html(this.title);
              url = jQuery.isFunction(mixed) ? mixed.call(this, this.name) : mixed;
              return this.handled[type] = new Mercury.Select(url, this.name, dialogOptions);
            case 'panel':
              this.element.addClass('mercury-button-panel');
              url = jQuery.isFunction(mixed) ? mixed.call(this, this.name) : mixed;
              this.handled['toggle'] = true;
              return this.handled[type] = new Mercury.Panel(url, this.name, dialogOptions);
            case 'modal':
              return this.handled[type] = jQuery.isFunction(mixed) ? mixed.apply(this, this.name) : mixed;
            default:
              throw "Unknown button type " + type + " used for the " + this.name + " button.";
          }
        }).call(this));
      }
      return _results;
    };
    Button.prototype.bindEvents = function() {
      Mercury.bind('button', __bind(function(event, options) {
        if (options.action === this.name) {
          return this.element.click();
        }
      }, this));
      Mercury.bind('mode', __bind(function(event, options) {
        if (this.handled.mode === options.mode && this.handled.toggle) {
          return this.togglePressed();
        }
      }, this));
      Mercury.bind('region:update', __bind(function(event, options) {
        var element;
        if (this.handled.context && options.region && jQuery.type(options.region.currentElement) === 'function') {
          element = options.region.currentElement();
          if (element.length && this.handled.context.call(this, element, options.region.element)) {
            return this.element.addClass('active');
          } else {
            return this.element.removeClass('active');
          }
        } else {
          return this.element.removeClass('active');
        }
      }, this));
      Mercury.bind('region:focused', __bind(function(event, options) {
        if (this.handled.regions && options.region && options.region.type) {
          if (this.handled.regions.indexOf(options.region.type) > -1) {
            return this.element.removeClass('disabled');
          } else {
            return this.element.addClass('disabled');
          }
        }
      }, this));
      Mercury.bind('region:blurred', __bind(function(event, options) {
        if (this.handled.regions) {
          return this.element.addClass('disabled');
        }
      }, this));
      this.element.mousedown(__bind(function(event) {
        return this.element.addClass('active');
      }, this));
      this.element.mouseup(__bind(function(event) {
        return this.element.removeClass('active');
      }, this));
      return this.element.click(__bind(function(event) {
        var handled, mixed, type, _ref;
        if (this.element.closest('.disabled').length) {
          return;
        }
        handled = false;
        _ref = this.handled;
        for (type in _ref) {
          if (!__hasProp.call(_ref, type)) continue;
          mixed = _ref[type];
          switch (type) {
            case 'toggle':
              if (!this.handled.mode) {
                this.togglePressed();
              }
              break;
            case 'mode':
              handled = true;
              Mercury.trigger('mode', {
                mode: mixed
              });
              break;
            case 'modal':
              handled = true;
              Mercury.modal(this.handled.modal, {
                title: this.summary || this.title,
                handler: this.name
              });
              break;
            case 'palette':
            case 'select':
            case 'panel':
              event.stopPropagation();
              handled = true;
              this.handled[type].toggle();
          }
        }
        if (!handled) {
          Mercury.trigger('action', {
            action: this.name
          });
        }
        return Mercury.trigger('focus:frame');
      }, this));
    };
    Button.prototype.togglePressed = function() {
      return this.element.toggleClass('pressed');
    };
    return Button;
  })();
  this.Mercury.Toolbar.Button.contexts = {
    backColor: function(node) {
      return this.element.css('background-color', node.css('background-color'));
    },
    foreColor: function(node) {
      return this.element.css('background-color', node.css('color'));
    },
    bold: function(node) {
      var weight;
      weight = node.css('font-weight');
      return weight === 'bold' || weight > 400;
    },
    italic: function(node) {
      return node.css('font-style') === 'italic';
    },
    overline: function(node) {
      return node.css('text-decoration') === 'overline';
    },
    strikethrough: function(node, region) {
      return node.css('text-decoration') === 'line-through' || !!node.closest('strike', region).length;
    },
    underline: function(node, region) {
      return node.css('text-decoration') === 'underline' || !!node.closest('u', region).length;
    },
    subscript: function(node, region) {
      return !!node.closest('sub', region).length;
    },
    superscript: function(node, region) {
      return !!node.closest('sup', region).length;
    },
    justifyLeft: function(node) {
      return node.css('text-align').indexOf('left') > -1;
    },
    justifyCenter: function(node) {
      return node.css('text-align').indexOf('center') > -1;
    },
    justifyRight: function(node) {
      return node.css('text-align').indexOf('right') > -1;
    },
    justifyFull: function(node) {
      return node.css('text-align').indexOf('justify') > -1;
    },
    insertOrderedList: function(node, region) {
      return !!node.closest('ol', region.element).length;
    },
    insertUnorderedList: function(node, region) {
      return !!node.closest('ul', region.element).length;
    }
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Toolbar.ButtonGroup = (function() {
    function ButtonGroup(name, options) {
      this.name = name;
      this.options = options != null ? options : {};
      this.build();
      this.bindEvents();
      this.regions = this.options._regions;
      return this.element;
    }
    ButtonGroup.prototype.build = function() {
      this.element = jQuery('<div>', {
        "class": "mercury-button-group mercury-" + this.name + "-group"
      });
      if (this.options._context || this.options._regions) {
        return this.element.addClass('disabled');
      }
    };
    ButtonGroup.prototype.bindEvents = function() {
      Mercury.bind('region:update', __bind(function(event, options) {
        var context, element;
        context = Mercury.Toolbar.ButtonGroup.contexts[this.name];
        if (context) {
          if (options.region && jQuery.type(options.region.currentElement) === 'function') {
            element = options.region.currentElement();
            if (element.length && context.call(this, element, options.region.element)) {
              return this.element.removeClass('disabled');
            } else {
              return this.element.addClass('disabled');
            }
          }
        }
      }, this));
      Mercury.bind('region:focused', __bind(function(event, options) {
        if (this.regions && options.region && options.region.type) {
          if (this.regions.indexOf(options.region.type) > -1) {
            if (!this.options._context) {
              return this.element.removeClass('disabled');
            }
          } else {
            return this.element.addClass('disabled');
          }
        }
      }, this));
      return Mercury.bind('region:blurred', __bind(function(event, options) {
        if (this.options.regions) {
          return this.element.addClass('disabled');
        }
      }, this));
    };
    return ButtonGroup;
  })();
  this.Mercury.Toolbar.ButtonGroup.contexts = {
    table: function(node, region) {
      return !!node.closest('table', region).length;
    }
  };
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Toolbar.Expander = (function() {
    __extends(Expander, Mercury.Palette);
    function Expander(name, options) {
      this.name = name;
      this.options = options;
      this.container = this.options["for"];
      this.containerWidth = this.container.outerWidth();
      Expander.__super__.constructor.call(this, null, this.name, this.options);
      return this.element;
    }
    Expander.prototype.build = function() {
      var _ref;
      this.container.css({
        whiteSpace: 'normal'
      });
      this.trigger = jQuery('<div>', {
        "class": 'mercury-toolbar-expander'
      }).appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
      this.element = jQuery('<div>', {
        "class": "mercury-palette mercury-expander mercury-" + this.name + "-expander",
        style: 'display:none'
      });
      return this.windowResize();
    };
    Expander.prototype.bindEvents = function() {
      Mercury.bind('hide:dialogs', __bind(function(event, dialog) {
        if (dialog !== this) {
          return this.hide();
        }
      }, this));
      Mercury.bind('resize', __bind(function() {
        return this.windowResize();
      }, this));
      Expander.__super__.bindEvents.apply(this, arguments);
      this.trigger.click(__bind(function(event) {
        var button, hiddenButtons, _i, _len, _ref;
        event.stopPropagation();
        hiddenButtons = [];
        _ref = this.container.find('.mercury-button');
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          button = _ref[_i];
          button = jQuery(button);
          if (button.position().top > 5) {
            hiddenButtons.push(button.data('expander'));
          }
        }
        this.loadContent(hiddenButtons.join(''));
        return this.toggle();
      }, this));
      return this.element.click(__bind(function(event) {
        var button, buttonName;
        buttonName = jQuery(event.target).closest('[data-button]').data('button');
        button = this.container.find(".mercury-" + buttonName + "-button");
        return button.click();
      }, this));
    };
    Expander.prototype.windowResize = function() {
      if (this.containerWidth > jQuery(window).width()) {
        this.trigger.show();
      } else {
        this.trigger.hide();
      }
      return this.hide();
    };
    Expander.prototype.position = function(keepVisible) {
      var position, width;
      this.element.css({
        top: 0,
        left: 0,
        display: 'block',
        visibility: 'hidden'
      });
      position = this.trigger.offset();
      width = this.element.width();
      if (position.left + width > jQuery(window).width()) {
        position.left = position.left - width + this.trigger.width();
      }
      return this.element.css({
        top: position.top + this.trigger.height(),
        left: position.left,
        display: keepVisible ? 'block' : 'none',
        visibility: 'visible'
      });
    };
    return Expander;
  })();
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.tooltip = function(forElement, content, options) {
    if (options == null) {
      options = {};
    }
    Mercury.tooltip.show(forElement, content, options);
    return Mercury.tooltip;
  };
  jQuery.extend(Mercury.tooltip, {
    show: function(forElement, content, options) {
      this.forElement = forElement;
      this.content = content;
      this.options = options != null ? options : {};
      this.document = jQuery(this.forElement.get(0).ownerDocument);
      this.initialize();
      if (this.visible) {
        return this.update();
      } else {
        return this.appear();
      }
    },
    initialize: function() {
      if (this.initialized) {
        return;
      }
      this.build();
      this.bindEvents();
      return this.initialized = true;
    },
    build: function() {
      var _ref;
      this.element = jQuery('<div>', {
        "class": 'mercury-tooltip'
      });
      return this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
    },
    bindEvents: function() {
      var parent, _i, _len, _ref;
      Mercury.bind('resize', __bind(function() {
        if (this.visible) {
          return this.position();
        }
      }, this));
      this.document.scroll(__bind(function() {
        if (this.visible) {
          return this.position();
        }
      }, this));
      _ref = this.forElement.parentsUntil(jQuery('body', this.document));
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        parent = _ref[_i];
        if (parent.scrollHeight > parent.clientHeight) {
          jQuery(parent).scroll(__bind(function() {
            if (this.visible) {
              return this.position();
            }
          }, this));
        }
      }
      return this.element.mousedown(function(event) {
        event.preventDefault();
        return event.stopPropagation();
      });
    },
    appear: function() {
      this.update();
      this.element.show();
      return this.element.animate({
        opacity: 1
      }, 200, 'easeInOutSine', __bind(function() {
        return this.visible = true;
      }, this));
    },
    update: function() {
      this.element.html(this.content);
      return this.position();
    },
    position: function() {
      var left, offset, top, width;
      offset = this.forElement.offset();
      width = this.element.width();
      top = offset.top + (Mercury.displayRect.top - jQuery(this.document).scrollTop()) + this.forElement.outerHeight();
      left = offset.left - jQuery(this.document).scrollLeft();
      if ((left + width + 25) > Mercury.displayRect.width) {
        left = left - (left + width + 25) - Mercury.displayRect.width;
      }
      left = left <= 0 ? 0 : left;
      return this.element.css({
        top: top,
        left: left
      });
    },
    hide: function() {
      if (!this.initialized) {
        return;
      }
      this.element.hide();
      return this.visible = false;
    }
  });
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Snippet = (function() {
    Snippet.all = [];
    Snippet.displayOptionsFor = function(name) {
      Mercury.modal(Mercury.config.snippets.optionsUrl.replace(':name', name), {
        title: 'Snippet Options',
        handler: 'insertSnippet',
        snippetName: name
      });
      return Mercury.snippet = null;
    };
    Snippet.create = function(name, options) {
      var identity, instance;
      identity = "snippet_" + this.all.length;
      instance = new Mercury.Snippet(name, identity, options);
      this.all.push(instance);
      return instance;
    };
    Snippet.find = function(identity) {
      var snippet, _i, _len, _ref;
      _ref = this.all;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        snippet = _ref[_i];
        if (snippet.identity === identity) {
          return snippet;
        }
      }
      return null;
    };
    Snippet.load = function(snippets) {
      var details, identity, instance, _results;
      _results = [];
      for (identity in snippets) {
        if (!__hasProp.call(snippets, identity)) continue;
        details = snippets[identity];
        instance = new Mercury.Snippet(details.name, identity, details.options);
        _results.push(this.all.push(instance));
      }
      return _results;
    };
    function Snippet(name, identity, options) {
      this.name = name;
      this.identity = identity;
      if (options == null) {
        options = {};
      }
      this.version = 0;
      this.data = '';
      this.history = new Mercury.HistoryBuffer();
      this.setOptions(options);
    }
    Snippet.prototype.getHTML = function(context, callback) {
      var element;
      if (callback == null) {
        callback = null;
      }
      element = jQuery('<div class="mercury-snippet" contenteditable="false">', context);
      element.attr({
        'data-snippet': this.identity
      });
      element.attr({
        'data-version': this.version
      });
      element.html("[" + this.identity + "]");
      this.loadPreview(element, callback);
      return element;
    };
    Snippet.prototype.getText = function(callback) {
      return "[--" + this.identity + "--]";
    };
    Snippet.prototype.loadPreview = function(element, callback) {
      if (callback == null) {
        callback = null;
      }
      return jQuery.ajax(Mercury.config.snippets.previewUrl.replace(':name', this.name), {
        headers: Mercury.ajaxHeaders(),
        type: Mercury.config.snippets.method,
        data: this.options,
        success: __bind(function(data) {
          this.data = data;
          element.html(data);
          if (callback) {
            return callback();
          }
        }, this),
        error: __bind(function() {
          return alert("Error loading the preview for the " + this.name + " snippet.");
        }, this)
      });
    };
    Snippet.prototype.displayOptions = function() {
      Mercury.snippet = this;
      return Mercury.modal(Mercury.config.snippets.optionsUrl.replace(':name', this.name), {
        title: 'Snippet Options',
        handler: 'insertSnippet',
        loadType: Mercury.config.snippets.method,
        loadData: this.options
      });
    };
    Snippet.prototype.setOptions = function(options) {
      this.options = options;
      delete this.options['authenticity_token'];
      delete this.options['utf8'];
      this.version += 1;
      return this.history.push(this.options);
    };
    Snippet.prototype.setVersion = function(version) {
      if (version == null) {
        version = null;
      }
      version = parseInt(version);
      if (version && this.history.stack[version - 1]) {
        this.version = version - 1;
        this.options = this.history.stack[this.version];
        return true;
      }
      return false;
    };
    Snippet.prototype.serialize = function() {
      return {
        name: this.name,
        options: this.options
      };
    };
    return Snippet;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.SnippetToolbar = (function() {
    __extends(SnippetToolbar, Mercury.Toolbar);
    function SnippetToolbar(document, options) {
      this.document = document;
      this.options = options != null ? options : {};
      SnippetToolbar.__super__.constructor.call(this, this.options);
    }
    SnippetToolbar.prototype.build = function() {
      var button, buttonName, options, _ref, _ref2, _results;
      this.element = jQuery('<div>', {
        "class": 'mercury-toolbar mercury-snippet-toolbar',
        style: 'display:none'
      });
      this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
      _ref2 = Mercury.config.toolbars.snippetable;
      _results = [];
      for (buttonName in _ref2) {
        if (!__hasProp.call(_ref2, buttonName)) continue;
        options = _ref2[buttonName];
        button = this.buildButton(buttonName, options);
        _results.push(button ? button.appendTo(this.element) : void 0);
      }
      return _results;
    };
    SnippetToolbar.prototype.bindEvents = function() {
      Mercury.bind('show:toolbar', __bind(function(event, options) {
        if (!options.snippet) {
          return;
        }
        options.snippet.mouseout(__bind(function() {
          return this.hide();
        }, this));
        return this.show(options.snippet);
      }, this));
      Mercury.bind('hide:toolbar', __bind(function(event, options) {
        if (!(options.type && options.type === 'snippet')) {
          return;
        }
        return this.hide(options.immediately);
      }, this));
      jQuery(this.document).scroll(__bind(function() {
        if (this.visible) {
          return this.position();
        }
      }, this));
      this.element.mousemove(__bind(function() {
        return clearTimeout(this.hideTimeout);
      }, this));
      return this.element.mouseout(__bind(function() {
        return this.hide();
      }, this));
    };
    SnippetToolbar.prototype.show = function(snippet) {
      this.snippet = snippet;
      Mercury.tooltip.hide();
      this.position();
      return this.appear();
    };
    SnippetToolbar.prototype.position = function() {
      var left, offset, top;
      offset = this.snippet.offset();
      top = offset.top + Mercury.displayRect.top - jQuery(this.document).scrollTop() - this.height() + 10;
      left = offset.left - jQuery(this.document).scrollLeft();
      return this.element.css({
        top: top,
        left: left
      });
    };
    SnippetToolbar.prototype.appear = function() {
      clearTimeout(this.hideTimeout);
      if (this.visible) {
        return;
      }
      this.visible = true;
      this.element.css({
        display: 'block',
        opacity: 0
      });
      return this.element.stop().animate({
        opacity: 1
      }, 200, 'easeInOutSine');
    };
    SnippetToolbar.prototype.hide = function(immediately) {
      if (immediately == null) {
        immediately = false;
      }
      clearTimeout(this.hideTimeout);
      if (immediately) {
        this.element.hide();
        return this.visible = false;
      } else {
        return this.hideTimeout = setTimeout((__bind(function() {
          this.element.stop().animate({
            opacity: 0
          }, 300, 'easeInOutSine', __bind(function() {
            return this.element.hide();
          }, this));
          return this.visible = false;
        }, this)), 500);
      }
    };
    return SnippetToolbar;
  })();
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Region = (function() {
    var type;
    type = 'region';
    function Region(element, window, options) {
      this.element = element;
      this.window = window;
      this.options = options != null ? options : {};
      if (!this.type) {
        this.type = 'region';
      }
      Mercury.log("building " + this.type, this.element, this.options);
      this.document = this.window.document;
      this.name = this.element.attr('id');
      this.history = new Mercury.HistoryBuffer();
      this.build();
      this.bindEvents();
      this.pushHistory();
      this.element.data('region', this);
    }
    Region.prototype.build = function() {};
    Region.prototype.focus = function() {};
    Region.prototype.bindEvents = function() {
      Mercury.bind('mode', __bind(function(event, options) {
        if (options.mode === 'preview') {
          return this.togglePreview();
        }
      }, this));
      Mercury.bind('focus:frame', __bind(function() {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        return this.focus();
      }, this));
      Mercury.bind('action', __bind(function(event, options) {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        if (options.action) {
          return this.execCommand(options.action, options);
        }
      }, this));
      this.element.mousemove(__bind(function(event) {
        var snippet;
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        snippet = jQuery(event.target).closest('.mercury-snippet');
        if (snippet.length) {
          this.snippet = snippet;
          return Mercury.trigger('show:toolbar', {
            type: 'snippet',
            snippet: this.snippet
          });
        }
      }, this));
      return this.element.mouseout(__bind(function() {
        if (this.previewing) {
          return;
        }
        return Mercury.trigger('hide:toolbar', {
          type: 'snippet',
          immediately: false
        });
      }, this));
    };
    Region.prototype.content = function(value, filterSnippets) {
      var container, snippet, _i, _len, _ref;
      if (value == null) {
        value = null;
      }
      if (filterSnippets == null) {
        filterSnippets = false;
      }
      if (value !== null) {
        return this.element.html(value);
      } else {
        container = jQuery('<div>').appendTo(this.document.createDocumentFragment());
        container.html(this.element.html().replace(/^\s+|\s+$/g, ''));
        if (filterSnippets) {
          _ref = container.find('.mercury-snippet');
          for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            snippet = _ref[_i];
            snippet = jQuery(snippet);
            snippet.attr({
              contenteditable: null,
              'data-version': null
            });
            snippet.html("[" + (snippet.data('snippet')) + "]");
          }
        }
        return container.html();
      }
    };
    Region.prototype.togglePreview = function() {
      if (this.previewing) {
        this.previewing = false;
        this.element.addClass(Mercury.config.regionClass).removeClass("" + Mercury.config.regionClass + "-preview");
        if (Mercury.region === this) {
          return this.focus();
        }
      } else {
        this.previewing = true;
        this.element.addClass("" + Mercury.config.regionClass + "-preview").removeClass(Mercury.config.regionClass);
        return Mercury.trigger('region:blurred', {
          region: this
        });
      }
    };
    Region.prototype.execCommand = function(action, options) {
      if (options == null) {
        options = {};
      }
      this.focus();
      if (action !== 'redo') {
        this.pushHistory();
      }
      Mercury.log('execCommand', action, options.value);
      return Mercury.changes = true;
    };
    Region.prototype.pushHistory = function() {
      return this.history.push(this.content());
    };
    Region.prototype.snippets = function() {
      var element, snippet, snippets, _i, _len, _ref;
      snippets = {};
      _ref = this.element.find('[data-snippet]');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        element = _ref[_i];
        snippet = Mercury.Snippet.find(jQuery(element).data('snippet'));
        snippet.setVersion(jQuery(element).data('version'));
        snippets[snippet.identity] = snippet.serialize();
      }
      return snippets;
    };
    Region.prototype.serialize = function() {
      return {
        type: this.type,
        value: this.content(null, true),
        snippets: this.snippets()
      };
    };
    return Region;
  })();
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; }, __hasProp = Object.prototype.hasOwnProperty;
  this.Mercury.uploader = function(file, options) {
    if (Mercury.config.uploading.enabled) {
      Mercury.uploader.show(file, options);
    }
    return Mercury.uploader;
  };
  jQuery.extend(Mercury.uploader, {
    show: function(file, options) {
      this.options = options != null ? options : {};
      this.file = new Mercury.uploader.File(file);
      if (this.file.errors) {
        alert("Error: " + this.file.errors);
        return;
      }
      if (!this.supported()) {
        return;
      }
      Mercury.trigger('focus:window');
      this.initialize();
      return this.appear();
    },
    initialize: function() {
      if (this.initialized) {
        return;
      }
      this.build();
      this.bindEvents();
      return this.initialized = true;
    },
    supported: function() {
      var fileReader, xhr;
      xhr = new XMLHttpRequest;
      fileReader = window.FileReader;
      if (window.Uint8Array && window.ArrayBuffer && !XMLHttpRequest.prototype.sendAsBinary) {
        XMLHttpRequest.prototype.sendAsBinary = function(datastr) {
          var data, index, ui8a, _len;
          ui8a = new Uint8Array(datastr.length);
          for (index = 0, _len = datastr.length; index < _len; index++) {
            data = datastr[index];
            ui8a[index] = datastr.charCodeAt(index) & 0xff;
          }
          return this.send(ui8a.buffer);
        };
      }
      return !!(xhr.upload && xhr.sendAsBinary && fileReader);
    },
    build: function() {
      var _ref, _ref2;
      this.element = jQuery('<div>', {
        "class": 'mercury-uploader',
        style: 'display:none'
      });
      this.element.append('<div class="mercury-uploader-preview"><b><img/></b></div>');
      this.element.append('<div class="mercury-uploader-details"></div>');
      this.element.append('<div class="mercury-uploader-progress"><span>Processing...</span><div class="mercury-uploader-indicator"><div><b>0%</b></div></div></div>');
      this.overlay = jQuery('<div>', {
        "class": 'mercury-uploader-overlay',
        style: 'display:none'
      });
      this.element.appendTo((_ref = jQuery(this.options.appendTo).get(0)) != null ? _ref : 'body');
      return this.overlay.appendTo((_ref2 = jQuery(this.options.appendTo).get(0)) != null ? _ref2 : 'body');
    },
    bindEvents: function() {
      return Mercury.bind('resize', __bind(function() {
        return this.position();
      }, this));
    },
    appear: function() {
      this.fillDisplay();
      this.position();
      this.overlay.show();
      return this.overlay.animate({
        opacity: 1
      }, 200, 'easeInOutSine', __bind(function() {
        this.element.show();
        return this.element.animate({
          opacity: 1
        }, 200, 'easeInOutSine', __bind(function() {
          this.visible = true;
          return this.loadImage();
        }, this));
      }, this));
    },
    position: function() {
      var height, width;
      width = this.element.outerWidth();
      height = this.element.outerHeight();
      return this.element.css({
        top: (Mercury.displayRect.height - height) / 2,
        left: (Mercury.displayRect.width - width) / 2
      });
    },
    fillDisplay: function() {
      var details;
      details = ["Name: " + this.file.name, "Size: " + this.file.readableSize, "Type: " + this.file.type];
      return this.element.find('.mercury-uploader-details').html(details.join('<br/>'));
    },
    loadImage: function() {
      return this.file.readAsDataURL(__bind(function(result) {
        this.element.find('.mercury-uploader-preview b').html(jQuery('<img>', {
          src: result
        }));
        return this.upload();
      }, this));
    },
    upload: function() {
      var xhr;
      xhr = new XMLHttpRequest;
      jQuery.each(['onloadstart', 'onprogress', 'onload', 'onabort', 'onerror'], __bind(function(index, eventName) {
        return xhr.upload[eventName] = __bind(function(event) {
          return this.uploaderEvents[eventName].call(this, event);
        }, this);
      }, this));
      xhr.onload = __bind(function(event) {
        var response;
        if (event.currentTarget.status >= 400) {
          this.updateStatus('Error: Unable to upload the file');
          alert("" + event.currentTarget.status + ": Unable to process response");
          return this.hide();
        } else {
          try {
            response = Mercury.config.uploading.handler ? Mercury.config.uploading.handler(event.target.responseText) : jQuery.parseJSON(event.target.responseText);
            Mercury.trigger('action', {
              action: 'insertImage',
              value: {
                src: response.image.url
              }
            });
            return this.hide();
          } catch (error) {
            this.updateStatus('Error: Unable to upload the file');
            alert("Unable to process response: " + error);
            return this.hide();
          }
        }
      }, this);
      xhr.open('post', Mercury.config.uploading.url, true);
      xhr.setRequestHeader('Accept', 'application/json, text/javascript, text/html, application/xml, text/xml, */*');
      xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
      xhr.setRequestHeader(Mercury.config.csrfHeader, Mercury.csrfToken);
      return this.file.readAsBinaryString(__bind(function(result) {
        var multipart;
        multipart = new Mercury.uploader.MultiPartPost(Mercury.config.uploading.inputName, this.file, result);
        this.file.updateSize(multipart.delta);
        xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + multipart.boundary);
        return xhr.sendAsBinary(multipart.body);
      }, this));
    },
    updateStatus: function(message, loaded) {
      var percent;
      this.element.find('.mercury-uploader-progress span').html(message);
      if (loaded) {
        percent = Math.floor(loaded * 100 / this.file.size) + '%';
        this.element.find('.mercury-uploader-indicator div').css({
          width: percent
        });
        return this.element.find('.mercury-uploader-indicator b').html(percent).show();
      }
    },
    hide: function(delay) {
      if (delay == null) {
        delay = 0;
      }
      return setTimeout(delay * 1000, __bind(function() {
        return this.element.animate({
          opacity: 0
        }, 200, 'easeInOutSine', __bind(function() {
          return this.overlay.animate({
            opacity: 0
          }, 200, 'easeInOutSine', __bind(function() {
            this.overlay.hide();
            this.element.hide();
            this.reset();
            this.visible = false;
            return Mercury.trigger('focus:frame');
          }, this));
        }, this));
      }, this));
    },
    reset: function() {
      this.element.find('.mercury-uploader-preview b').html('');
      this.element.find('.mercury-uploader-indicator div').css({
        width: 0
      });
      this.element.find('.mercury-uploader-indicator b').html('0%').hide();
      return this.updateStatus('Processing...');
    },
    uploaderEvents: {
      onloadstart: function() {
        return this.updateStatus('Uploading...');
      },
      onprogress: function(event) {
        return this.updateStatus('Uploading...', event.loaded);
      },
      onabort: function() {
        this.updateStatus('Aborted');
        return this.hide(1);
      },
      onload: function() {
        return this.updateStatus('Successfully uploaded...', this.file.size);
      },
      onerror: function() {
        this.updateStatus('Error: Unable to upload the file');
        return this.hide(3);
      }
    }
  });
  Mercury.uploader.File = (function() {
    function File(file) {
      var errors;
      this.file = file;
      this.size = this.file.size;
      this.fullSize = this.file.size;
      this.readableSize = this.file.size.toBytes();
      this.name = this.file.fileName;
      this.type = this.file.type;
      errors = [];
      if (this.size >= Mercury.config.uploading.maxFileSize) {
        errors.push('Too large');
      }
      if (!(Mercury.config.uploading.allowedMimeTypes.indexOf(this.type) > -1)) {
        errors.push('Unsupported format');
      }
      if (errors.length) {
        this.errors = errors.join(' / ');
      }
    }
    File.prototype.readAsDataURL = function(callback) {
      var reader;
      if (callback == null) {
        callback = null;
      }
      reader = new FileReader();
      reader.readAsDataURL(this.file);
      return reader.onload = __bind(function() {
        if (callback) {
          return callback(reader.result);
        }
      }, this);
    };
    File.prototype.readAsBinaryString = function(callback) {
      var reader;
      if (callback == null) {
        callback = null;
      }
      reader = new FileReader();
      reader.readAsBinaryString(this.file);
      return reader.onload = __bind(function() {
        if (callback) {
          return callback(reader.result);
        }
      }, this);
    };
    File.prototype.updateSize = function(delta) {
      return this.fullSize = this.size + delta;
    };
    return File;
  })();
  Mercury.uploader.MultiPartPost = (function() {
    function MultiPartPost(inputName, file, contents, formInputs) {
      this.inputName = inputName;
      this.file = file;
      this.contents = contents;
      this.formInputs = formInputs != null ? formInputs : {};
      this.boundary = 'Boundaryx20072377098235644401115438165x';
      this.body = '';
      this.buildBody();
      this.delta = this.body.length - this.file.size;
    }
    MultiPartPost.prototype.buildBody = function() {
      var boundary, name, value, _ref;
      boundary = '--' + this.boundary;
      _ref = this.formInputs;
      for (name in _ref) {
        if (!__hasProp.call(_ref, name)) continue;
        value = _ref[name];
        this.body += "" + boundary + "\r\nContent-Disposition: form-data; name=\"" + name + "\"\r\n\r\n" + (unescape(encodeURIComponent(value))) + "\r\n";
      }
      return this.body += "" + boundary + "\r\nContent-Disposition: form-data; name=\"" + this.inputName + "\"; filename=\"" + this.file.name + "\"\r\nContent-Type: " + this.file.type + "\r\nContent-Transfer-Encoding: binary\r\n\r\n" + this.contents + "\r\n" + boundary + "--";
    };
    return MultiPartPost;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; }, __indexOf = Array.prototype.indexOf || function(item) {
    for (var i = 0, l = this.length; i < l; i++) {
      if (this[i] === item) return i;
    }
    return -1;
  };
  this.Mercury.Regions.Editable = (function() {
    var type;
    __extends(Editable, Mercury.Region);
    type = 'editable';
    function Editable(element, window, options) {
      this.element = element;
      this.window = window;
      this.options = options != null ? options : {};
      this.type = 'editable';
      Editable.__super__.constructor.apply(this, arguments);
    }
    Editable.prototype.build = function() {
      var element, _i, _len, _ref;
      if (jQuery.browser.mozilla && this.content() === '') {
        this.content('&nbsp;');
      }
      this.element.data({
        originalOverflow: this.element.css('overflow')
      });
      this.element.css({
        overflow: 'auto'
      });
      this.specialContainer = jQuery.browser.mozilla && this.element.get(0).tagName !== 'DIV';
      this.element.get(0).contentEditable = true;
      _ref = this.element.find('.mercury-snippet');
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        element = _ref[_i];
        element.contentEditable = false;
        jQuery(element).attr('data-version', '1');
      }
      if (!this.document.mercuryEditing) {
        this.document.mercuryEditing = true;
        try {
          this.document.execCommand('styleWithCSS', false, false);
          this.document.execCommand('insertBROnReturn', false, true);
          this.document.execCommand('enableInlineTableEditing', false, false);
          return this.document.execCommand('enableObjectResizing', false, false);
        } catch (e) {

        }
      }
    };
    Editable.prototype.bindEvents = function() {
      Editable.__super__.bindEvents.apply(this, arguments);
      Mercury.bind('region:update', __bind(function() {
        var anchor, currentElement, table;
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        setTimeout((__bind(function() {
          return this.selection().forceSelection(this.element.get(0));
        }, this)), 1);
        currentElement = this.currentElement();
        if (currentElement.length) {
          table = currentElement.closest('table', this.element);
          if (table.length) {
            Mercury.tableEditor(table, currentElement.closest('tr, td'), '<br/>');
          }
          anchor = currentElement.closest('a', this.element);
          if (anchor.length && anchor.attr('href')) {
            return Mercury.tooltip(anchor, "<a href=\"" + (anchor.attr('href')) + "\" target=\"_blank\">" + (anchor.attr('href')) + "</a>", {
              position: 'below'
            });
          } else {
            return Mercury.tooltip.hide();
          }
        }
      }, this));
      this.element.bind('dragenter', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (!Mercury.snippet) {
          event.preventDefault();
        }
        return event.originalEvent.dataTransfer.dropEffect = 'copy';
      }, this));
      this.element.bind('dragover', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (!Mercury.snippet) {
          event.preventDefault();
        }
        event.originalEvent.dataTransfer.dropEffect = 'copy';
        if (jQuery.browser.webkit) {
          clearTimeout(this.dropTimeout);
          return this.dropTimeout = setTimeout((__bind(function() {
            return this.element.trigger('possible:drop');
          }, this)), 10);
        }
      }, this));
      this.element.bind('drop', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        clearTimeout(this.dropTimeout);
        this.dropTimeout = setTimeout((__bind(function() {
          return this.element.trigger('possible:drop');
        }, this)), 1);
        if (!event.originalEvent.dataTransfer.files.length) {
          return;
        }
        event.preventDefault();
        this.focus();
        return Mercury.uploader(event.originalEvent.dataTransfer.files[0]);
      }, this));
      this.element.bind('possible:drop', __bind(function() {
        var snippet;
        if (this.previewing) {
          return;
        }
        if (snippet = this.element.find('img[data-snippet]').get(0)) {
          this.focus();
          Mercury.Snippet.displayOptionsFor(jQuery(snippet).data('snippet'));
          return this.document.execCommand('undo', false, null);
        }
      }, this));
      this.element.bind('paste', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        if (this.specialContainer) {
          event.preventDefault();
          return;
        }
        if (this.pasting) {
          return;
        }
        Mercury.changes = true;
        return this.handlePaste(event.originalEvent);
      }, this));
      this.element.focus(__bind(function() {
        if (this.previewing) {
          return;
        }
        Mercury.region = this;
        setTimeout((__bind(function() {
          return this.selection().forceSelection(this.element.get(0));
        }, this)), 1);
        return Mercury.trigger('region:focused', {
          region: this
        });
      }, this));
      this.element.blur(__bind(function() {
        if (this.previewing) {
          return;
        }
        Mercury.trigger('region:blurred', {
          region: this
        });
        return Mercury.tooltip.hide();
      }, this));
      this.element.click(__bind(function(event) {
        if (this.previewing) {
          return jQuery(event.target).closest('a').attr('target', '_top');
        }
      }, this));
      this.element.dblclick(__bind(function(event) {
        var image;
        if (this.previewing) {
          return;
        }
        image = jQuery(event.target).closest('img', this.element);
        if (image.length) {
          this.selection().selectNode(image.get(0), true);
          return Mercury.trigger('button', {
            action: 'insertMedia'
          });
        }
      }, this));
      this.element.mouseup(__bind(function() {
        if (this.previewing) {
          return;
        }
        this.pushHistory();
        return Mercury.trigger('region:update', {
          region: this
        });
      }, this));
      this.element.keydown(__bind(function(event) {
        var container;
        if (this.previewing) {
          return;
        }
        switch (event.keyCode) {
          case 90:
            if (!event.metaKey) {
              return;
            }
            event.preventDefault();
            if (event.shiftKey) {
              this.execCommand('redo');
            } else {
              this.execCommand('undo');
            }
            return;
          case 13:
            if (jQuery.browser.webkit && this.selection().commonAncestor().closest('li, ul, ol', this.element).length === 0) {
              event.preventDefault();
              this.document.execCommand('insertParagraph', false, null);
            } else if (this.specialContainer || jQuery.browser.opera) {
              event.preventDefault();
              this.document.execCommand('insertHTML', false, '<br/>');
            }
            break;
          case 9:
            event.preventDefault();
            container = this.selection().commonAncestor();
            if (container.closest('li', this.element).length) {
              if (!event.shiftKey) {
                this.execCommand('indent');
              } else if (container.parents('ul, ol').length > 1) {
                this.execCommand('outdent');
              }
            } else {
              this.execCommand('insertHTML', {
                value: '&nbsp;&nbsp;'
              });
            }
        }
        if (event.metaKey) {
          switch (event.keyCode) {
            case 66:
              this.execCommand('bold');
              event.preventDefault();
              break;
            case 73:
              this.execCommand('italic');
              event.preventDefault();
              break;
            case 85:
              this.execCommand('underline');
              event.preventDefault();
          }
        }
        return this.pushHistory(event.keyCode);
      }, this));
      return this.element.keyup(__bind(function() {
        if (this.previewing) {
          return;
        }
        Mercury.trigger('region:update', {
          region: this
        });
        return Mercury.changes = true;
      }, this));
    };
    Editable.prototype.focus = function() {
      if (Mercury.region !== this) {
        this.element.focus();
        try {
          this.selection().selection.collapseToStart();
        } catch (e) {

        }
      }
      Mercury.trigger('region:focused', {
        region: this
      });
      return Mercury.trigger('region:update', {
        region: this
      });
    };
    Editable.prototype.content = function(value, filterSnippets, includeMarker) {
      var container, content, element, index, selection, snippet, version, _i, _len, _len2, _ref, _ref2;
      if (value == null) {
        value = null;
      }
      if (filterSnippets == null) {
        filterSnippets = true;
      }
      if (includeMarker == null) {
        includeMarker = false;
      }
      if (value !== null) {
        container = jQuery('<div>').appendTo(this.document.createDocumentFragment());
        container.html(value);
        _ref = container.find('[data-snippet]');
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          element = _ref[_i];
          element.contentEditable = false;
          element = jQuery(element);
          if (snippet = Mercury.Snippet.find(element.data('snippet'))) {
            if (!element.data('version')) {
              try {
                version = parseInt(element.html().match(/\/(\d+)\]/)[1]);
                if (version) {
                  snippet.setVersion(version);
                  element.attr({
                    'data-version': version
                  });
                  element.html(snippet.data);
                }
              } catch (error) {

              }
            }
          }
        }
        this.element.html(container.html());
        return this.selection().selectMarker(this.element);
      } else {
        this.element.find('meta').remove();
        if (includeMarker) {
          selection = this.selection();
          selection.placeMarker();
        }
        container = jQuery('<div>').appendTo(this.document.createDocumentFragment());
        container.html(this.element.html().replace(/^\s+|\s+$/g, ''));
        if (filterSnippets) {
          _ref2 = container.find('[data-snippet]');
          for (index = 0, _len2 = _ref2.length; index < _len2; index++) {
            element = _ref2[index];
            element = jQuery(element);
            if (snippet = Mercury.Snippet.find(element.data("snippet"))) {
              snippet.data = element.html();
            }
            element.html("[" + (element.data("snippet")) + "/" + (element.data("version")) + "]");
            element.attr({
              contenteditable: null,
              'data-version': null
            });
          }
        }
        content = container.html();
        if (includeMarker) {
          selection.removeMarker();
        }
        return content;
      }
    };
    Editable.prototype.togglePreview = function() {
      if (this.previewing) {
        this.element.get(0).contentEditable = true;
        this.element.css({
          overflow: 'auto'
        });
      } else {
        this.content(this.content());
        this.element.get(0).contentEditable = false;
        this.element.css({
          overflow: this.element.data('originalOverflow')
        });
        this.element.blur();
      }
      return Editable.__super__.togglePreview.apply(this, arguments);
    };
    Editable.prototype.execCommand = function(action, options) {
      var handler, sibling;
      if (options == null) {
        options = {};
      }
      Editable.__super__.execCommand.apply(this, arguments);
      if (handler = Mercury.config.behaviors[action] || Mercury.Regions.Editable.actions[action]) {
        return handler.call(this, this.selection(), options);
      } else {
        if (action === 'indent') {
          sibling = this.element.get(0).previousSibling;
        }
        if (action === 'insertHTML' && options.value && options.value.get) {
          options.value = jQuery('<div>').html(options.value).html();
        }
        try {
          return this.document.execCommand(action, false, options.value);
        } catch (error) {
          if (action === 'indent' && this.element.prev() !== sibling) {
            return this.element.prev().remove();
          }
        }
      }
    };
    Editable.prototype.pushHistory = function(keyCode) {
      var keyCodes, knownKeyCode, waitTime;
      keyCodes = [13, 46, 8];
      waitTime = 2.5;
      if (keyCode) {
        knownKeyCode = keyCodes.indexOf(keyCode);
      }
      clearTimeout(this.historyTimeout);
      if (knownKeyCode >= 0 && knownKeyCode !== this.lastKnownKeyCode) {
        this.history.push(this.content(null, false, true));
      } else if (keyCode) {
        this.historyTimeout = setTimeout((__bind(function() {
          return this.history.push(this.content(null, false, true));
        }, this)), waitTime * 1000);
      } else {
        this.history.push(this.content(null, false, true));
      }
      return this.lastKnownKeyCode = knownKeyCode;
    };
    Editable.prototype.selection = function() {
      return new Mercury.Regions.Editable.Selection(this.window.getSelection(), this.document);
    };
    Editable.prototype.path = function() {
      var container;
      container = this.selection().commonAncestor();
      if (!container) {
        return [];
      }
      if (container.get(0) === this.element.get(0)) {
        return [];
      } else {
        return container.parentsUntil(this.element);
      }
    };
    Editable.prototype.currentElement = function() {
      var element, selection;
      element = [];
      selection = this.selection();
      if (selection.range) {
        element = selection.commonAncestor();
        if (element.get(0).nodeType === 3) {
          element = element.parent();
        }
      }
      return element;
    };
    Editable.prototype.handlePaste = function(event) {
      var sanitizer, selection;
      if (Mercury.config.pasting.sanitize === 'text' && event.clipboardData) {
        this.execCommand('insertHTML', {
          value: event.clipboardData.getData('text/plain')
        });
        event.preventDefault();
      } else {
        selection = this.selection();
        selection.placeMarker();
        sanitizer = jQuery('#mercury_sanitizer', this.document).focus();
        return setTimeout(1, __bind(function() {
          var content;
          content = this.sanitize(sanitizer);
          selection.selectMarker(this.element);
          selection.removeMarker();
          this.element.focus();
          return this.execCommand('insertHTML', {
            value: content
          });
        }, this));
      }
    };
    Editable.prototype.sanitize = function(sanitizer) {
      var allowed, allowedAttributes, allowedTag, attr, content, element, _i, _j, _len, _len2, _ref, _ref2, _ref3, _ref4;
      sanitizer.find("." + Mercury.config.regionClass).remove();
      if (Mercury.config.pasting.sanitize) {
        switch (Mercury.config.pasting.sanitize) {
          case 'blacklist':
            sanitizer.find('[style]').removeAttr('style');
            sanitizer.find('[class="Apple-style-span"]').removeClass('Apple-style-span');
            content = sanitizer.html();
            break;
          case 'whitelist':
            _ref = sanitizer.find('*');
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
              element = _ref[_i];
              allowed = false;
              _ref2 = Mercury.config.pasting.whitelist;
              for (allowedTag in _ref2) {
                allowedAttributes = _ref2[allowedTag];
                if (element.tagName.toLowerCase() === allowedTag.toLowerCase()) {
                  allowed = true;
                  _ref3 = jQuery(element.attributes);
                  for (_j = 0, _len2 = _ref3.length; _j < _len2; _j++) {
                    attr = _ref3[_j];
                    if (_ref4 = attr.name, __indexOf.call(allowedAttributes, _ref4) < 0) {
                      jQuery(element).removeAttr(attr.name);
                    }
                  }
                  break;
                }
              }
              if (!allowed) {
                jQuery(element).replaceWith(jQuery(element).contents());
              }
            }
            content = sanitizer.html();
            break;
          default:
            content = sanitizer.text();
        }
      } else {
        content = sanitizer.html();
        if (content.indexOf('<!--StartFragment-->') > -1 || content.indexOf('="mso-') > -1 || content.indexOf('<o:') > -1 || content.indexOf('="Mso') > -1) {
          content = sanitizer.text();
        }
      }
      sanitizer.html('');
      return content;
    };
    Editable.actions = {
      insertRowBefore: function() {
        return Mercury.tableEditor.addRow('before');
      },
      insertRowAfter: function() {
        return Mercury.tableEditor.addRow('after');
      },
      insertColumnBefore: function() {
        return Mercury.tableEditor.addColumn('before');
      },
      insertColumnAfter: function() {
        return Mercury.tableEditor.addColumn('after');
      },
      deleteColumn: function() {
        return Mercury.tableEditor.removeColumn();
      },
      deleteRow: function() {
        return Mercury.tableEditor.removeRow();
      },
      increaseColspan: function() {
        return Mercury.tableEditor.increaseColspan();
      },
      decreaseColspan: function() {
        return Mercury.tableEditor.decreaseColspan();
      },
      increaseRowspan: function() {
        return Mercury.tableEditor.increaseRowspan();
      },
      decreaseRowspan: function() {
        return Mercury.tableEditor.decreaseRowspan();
      },
      undo: function() {
        return this.content(this.history.undo());
      },
      redo: function() {
        return this.content(this.history.redo());
      },
      horizontalRule: function() {
        return this.execCommand('insertHorizontalRule');
      },
      removeFormatting: function(selection) {
        return selection.insertTextNode(selection.textContent());
      },
      backColor: function(selection, options) {
        return selection.wrap("<span style=\"background-color:" + (options.value.toHex()) + "\">", true);
      },
      overline: function(selection) {
        return selection.wrap('<span style="text-decoration:overline">', true);
      },
      style: function(selection, options) {
        return selection.wrap("<span class=\"" + options.value + "\">", true);
      },
      replaceHTML: function(selection, options) {
        return this.content(options.value);
      },
      insertImage: function(selection, options) {
        return this.execCommand('insertHTML', {
          value: jQuery('<img/>', options.value)
        });
      },
      insertTable: function(selection, options) {
        return this.execCommand('insertHTML', {
          value: options.value
        });
      },
      insertLink: function(selection, options) {
        var anchor;
        anchor = jQuery("<" + options.value.tagName + ">", this.document).attr(options.value.attrs).html(options.value.content);
        return selection.insertNode(anchor);
      },
      replaceLink: function(selection, options) {
        var anchor, html;
        anchor = jQuery("<" + options.value.tagName + ">", this.document).attr(options.value.attrs).html(options.value.content);
        selection.selectNode(options.node);
        html = jQuery('<div>').html(selection.content()).find('a').html();
        return selection.replace(jQuery(anchor, selection.context).html(html));
      },
      insertSnippet: function(selection, options) {
        var existing, snippet;
        snippet = options.value;
        if ((existing = this.element.find("[data-snippet=" + snippet.identity + "]")).length) {
          selection.selectNode(existing.get(0));
        }
        return selection.insertNode(snippet.getHTML(this.document));
      },
      editSnippet: function() {
        var snippet;
        if (!this.snippet) {
          return;
        }
        snippet = Mercury.Snippet.find(this.snippet.data('snippet'));
        return snippet.displayOptions();
      },
      removeSnippet: function() {
        if (this.snippet) {
          this.snippet.remove();
        }
        return Mercury.trigger('hide:toolbar', {
          type: 'snippet',
          immediately: true
        });
      }
    };
    return Editable;
  })();
  Mercury.Regions.Editable.Selection = (function() {
    function Selection(selection, context) {
      this.selection = selection;
      this.context = context;
      if (!(this.selection.rangeCount >= 1)) {
        return;
      }
      this.range = this.selection.getRangeAt(0);
      this.fragment = this.range.cloneContents();
      this.clone = this.range.cloneRange();
      this.collapsed = this.selection.isCollapsed;
    }
    Selection.prototype.commonAncestor = function(onlyTag) {
      var ancestor;
      if (onlyTag == null) {
        onlyTag = false;
      }
      if (!this.range) {
        return null;
      }
      ancestor = this.range.commonAncestorContainer;
      if (ancestor.nodeType === 3 && onlyTag) {
        ancestor = ancestor.parentNode;
      }
      return jQuery(ancestor);
    };
    Selection.prototype.wrap = function(element, replace) {
      if (replace == null) {
        replace = false;
      }
      element = jQuery(element, this.context).html(this.fragment);
      if (replace) {
        this.replace(element);
      }
      return element;
    };
    Selection.prototype.textContent = function() {
      return this.range.cloneContents().textContent;
    };
    Selection.prototype.content = function() {
      return this.range.cloneContents();
    };
    Selection.prototype.is = function(elementType) {
      var content;
      content = this.content();
      if (jQuery(content).length === 1 && jQuery(content.firstChild).is(elementType)) {
        return jQuery(content.firstChild);
      }
      return false;
    };
    Selection.prototype.forceSelection = function(element) {
      var lastChild, range;
      if (!jQuery.browser.webkit) {
        return;
      }
      range = this.context.createRange();
      if (this.range) {
        if (this.commonAncestor(true).closest('.mercury-snippet').length) {
          lastChild = this.context.createTextNode('\00');
          element.appendChild(lastChild);
        }
      } else {
        if (element.lastChild && element.lastChild.nodeType === 3 && element.lastChild.textContent.replace(/^[\s+|\n+]|[\s+|\n+]$/, '') === '') {
          lastChild = element.lastChild;
          element.lastChild.textContent = '\00';
        } else {
          lastChild = this.context.createTextNode('\00');
          element.appendChild(lastChild);
        }
      }
      if (lastChild) {
        range.setStartBefore(lastChild);
        range.setEndBefore(lastChild);
        return this.selection.addRange(range);
      }
    };
    Selection.prototype.selectMarker = function(context) {
      var markers, range;
      markers = context.find('em.mercury-marker');
      if (!markers.length) {
        return;
      }
      range = this.context.createRange();
      range.setStartBefore(markers.get(0));
      if (markers.length >= 2) {
        range.setEndBefore(markers.get(1));
      }
      markers.remove();
      this.selection.removeAllRanges();
      return this.selection.addRange(range);
    };
    Selection.prototype.placeMarker = function() {
      var rangeEnd, rangeStart;
      if (!this.range) {
        return;
      }
      this.startMarker = jQuery('<em class="mercury-marker"/>', this.context).get(0);
      this.endMarker = jQuery('<em class="mercury-marker"/>', this.context).get(0);
      rangeEnd = this.range.cloneRange();
      rangeEnd.collapse(false);
      rangeEnd.insertNode(this.endMarker);
      if (!this.range.collapsed) {
        rangeStart = this.range.cloneRange();
        rangeStart.collapse(true);
        rangeStart.insertNode(this.startMarker);
      }
      this.selection.removeAllRanges();
      return this.selection.addRange(this.range);
    };
    Selection.prototype.removeMarker = function() {
      jQuery(this.startMarker).remove();
      return jQuery(this.endMarker).remove();
    };
    Selection.prototype.insertTextNode = function(string) {
      var node;
      node = this.context.createTextNode(string);
      this.range.extractContents();
      this.range.insertNode(node);
      this.range.selectNodeContents(node);
      return this.selection.addRange(this.range);
    };
    Selection.prototype.insertNode = function(element) {
      if (element.get) {
        element = element.get(0);
      }
      if (jQuery.type(element) === 'string') {
        element = jQuery(element, this.context).get(0);
      }
      this.range.deleteContents();
      this.range.insertNode(element);
      this.range.selectNodeContents(element);
      return this.selection.addRange(this.range);
    };
    Selection.prototype.selectNode = function(node, removeExisting) {
      if (removeExisting == null) {
        removeExisting = false;
      }
      this.range.selectNode(node);
      if (removeExisting) {
        this.selection.removeAllRanges();
      }
      return this.selection.addRange(this.range);
    };
    Selection.prototype.replace = function(element, collapse) {
      if (element.get) {
        element = element.get(0);
      }
      if (jQuery.type(element) === 'string') {
        element = jQuery(element, this.context).get(0);
      }
      this.range.deleteContents();
      this.range.insertNode(element);
      this.range.selectNodeContents(element);
      this.selection.addRange(this.range);
      if (collapse) {
        return this.range.collapse(false);
      }
    };
    return Selection;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Regions.Markupable = (function() {
    var type;
    __extends(Markupable, Mercury.Region);
    type = 'markupable';
    function Markupable(element, window, options) {
      this.element = element;
      this.window = window;
      this.options = options != null ? options : {};
      this.type = 'markupable';
      Markupable.__super__.constructor.apply(this, arguments);
      this.converter = new Showdown.converter();
    }
    Markupable.prototype.build = function() {
      var height, value, width;
      width = '100%';
      height = this.element.height();
      value = this.element.html().replace(/^\s+|\s+$/g, '').replace('&gt;', '>');
      this.element.removeClass(Mercury.config.regionClass);
      this.textarea = jQuery('<textarea>', this.document).val(value);
      this.textarea.attr('class', this.element.attr('class')).addClass('mercury-textarea');
      this.textarea.css({
        border: 0,
        background: 'transparent',
        display: 'block',
        'overflow-y': 'hidden',
        width: width,
        height: height,
        fontFamily: '"Courier New", Courier, monospace'
      });
      this.element.addClass(Mercury.config.regionClass);
      this.element.empty().append(this.textarea);
      this.previewElement = jQuery('<div>', this.document);
      this.element.append(this.previewElement);
      this.container = this.element;
      this.container.data('region', this);
      this.element = this.textarea;
      return this.resize();
    };
    Markupable.prototype.focus = function() {
      return this.element.focus();
    };
    Markupable.prototype.bindEvents = function() {
      Mercury.bind('mode', __bind(function(event, options) {
        if (options.mode === 'preview') {
          return this.togglePreview();
        }
      }, this));
      Mercury.bind('focus:frame', __bind(function() {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        return this.focus();
      }, this));
      Mercury.bind('action', __bind(function(event, options) {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        if (options.action) {
          return this.execCommand(options.action, options);
        }
      }, this));
      Mercury.bind('unfocus:regions', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        this.element.blur();
        this.container.removeClass('focus');
        return Mercury.trigger('region:blurred', {
          region: this
        });
      }, this));
      this.element.bind('dragenter', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        event.preventDefault();
        return event.originalEvent.dataTransfer.dropEffect = 'copy';
      }, this));
      this.element.bind('dragover', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        event.preventDefault();
        return event.originalEvent.dataTransfer.dropEffect = 'copy';
      }, this));
      this.element.bind('drop', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (Mercury.snippet) {
          event.preventDefault();
          this.focus();
          Mercury.Snippet.displayOptionsFor(Mercury.snippet);
        }
        if (event.originalEvent.dataTransfer.files.length) {
          event.preventDefault();
          this.focus();
          return Mercury.uploader(event.originalEvent.dataTransfer.files[0]);
        }
      }, this));
      this.element.focus(__bind(function() {
        if (this.previewing) {
          return;
        }
        Mercury.region = this;
        this.container.addClass('focus');
        return Mercury.trigger('region:focused', {
          region: this
        });
      }, this));
      this.element.keydown(__bind(function(event) {
        var end, lineText, number, selection, start, text;
        if (this.previewing) {
          return;
        }
        this.resize();
        switch (event.keyCode) {
          case 90:
            if (!event.metaKey) {
              return;
            }
            event.preventDefault();
            if (event.shiftKey) {
              this.execCommand('redo');
            } else {
              this.execCommand('undo');
            }
            return;
          case 13:
            selection = this.selection();
            text = this.element.val();
            start = text.lastIndexOf('\n', selection.start);
            end = text.indexOf('\n', selection.end);
            if (end < start) {
              end = text.length;
            }
            if (text[start] === '\n') {
              start = text.lastIndexOf('\n', selection.start - 1);
            }
            if (text[start + 1] === '-') {
              selection.replace('\n- ', false, true);
              event.preventDefault();
            }
            if (/\d/.test(text[start + 1])) {
              lineText = text.substring(start, end);
              if (/(\d+)\./.test(lineText)) {
                number = parseInt(RegExp.$1);
                selection.replace("\n" + (number += 1) + ". ", false, true);
                event.preventDefault();
              }
            }
            break;
          case 9:
            event.preventDefault();
            this.execCommand('insertHTML', {
              value: '  '
            });
        }
        if (event.metaKey) {
          switch (event.keyCode) {
            case 66:
              this.execCommand('bold');
              event.preventDefault();
              break;
            case 73:
              this.execCommand('italic');
              event.preventDefault();
              break;
            case 85:
              this.execCommand('underline');
              event.preventDefault();
          }
        }
        return this.pushHistory(event.keyCode);
      }, this));
      this.element.keyup(__bind(function() {
        if (this.previewing) {
          return;
        }
        Mercury.changes = true;
        this.resize();
        return Mercury.trigger('region:update', {
          region: this
        });
      }, this));
      this.element.mouseup(__bind(function() {
        if (this.previewing) {
          return;
        }
        this.focus();
        return Mercury.trigger('region:focused', {
          region: this
        });
      }, this));
      return this.previewElement.click(__bind(function(event) {
        if (this.previewing) {
          return $(event.target).closest('a').attr('target', '_top');
        }
      }, this));
    };
    Markupable.prototype.content = function(value, filterSnippets) {
      if (value == null) {
        value = null;
      }
      if (filterSnippets == null) {
        filterSnippets = true;
      }
      if (value !== null) {
        if (jQuery.type(value) === 'string') {
          return this.element.val(value);
        } else {
          this.element.val(value.html);
          return this.selection().select(value.selection.start, value.selection.end);
        }
      } else {
        return this.element.val();
      }
    };
    Markupable.prototype.contentAndSelection = function() {
      return {
        html: this.content(null, false),
        selection: this.selection().serialize()
      };
    };
    Markupable.prototype.togglePreview = function() {
      var value;
      if (this.previewing) {
        this.previewing = false;
        this.container.addClass(Mercury.config.regionClass).removeClass("" + Mercury.config.regionClass + "-preview");
        this.previewElement.hide();
        this.element.show();
        if (Mercury.region === this) {
          return this.focus();
        }
      } else {
        this.previewing = true;
        this.container.addClass("" + Mercury.config.regionClass + "-preview").removeClass(Mercury.config.regionClass);
        value = this.converter.makeHtml(this.element.val());
        this.previewElement.html(value);
        this.previewElement.show();
        this.element.hide();
        return Mercury.trigger('region:blurred', {
          region: this
        });
      }
    };
    Markupable.prototype.execCommand = function(action, options) {
      var handler;
      if (options == null) {
        options = {};
      }
      Markupable.__super__.execCommand.apply(this, arguments);
      if (handler = Mercury.Regions.Markupable.actions[action]) {
        handler.call(this, this.selection(), options);
      }
      return this.resize();
    };
    Markupable.prototype.pushHistory = function(keyCode) {
      var keyCodes, knownKeyCode, waitTime;
      keyCodes = [13, 46, 8];
      waitTime = 2.5;
      if (keyCode) {
        knownKeyCode = keyCodes.indexOf(keyCode);
      }
      clearTimeout(this.historyTimeout);
      if (knownKeyCode >= 0 && knownKeyCode !== this.lastKnownKeyCode) {
        this.history.push(this.contentAndSelection());
      } else if (keyCode) {
        this.historyTimeout = setTimeout((__bind(function() {
          return this.history.push(this.contentAndSelection());
        }, this)), waitTime * 1000);
      } else {
        this.history.push(this.contentAndSelection());
      }
      return this.lastKnownKeyCode = knownKeyCode;
    };
    Markupable.prototype.selection = function() {
      return new Mercury.Regions.Markupable.Selection(this.element);
    };
    Markupable.prototype.resize = function() {
      this.element.css({
        height: this.element.get(0).scrollHeight - 100
      });
      return this.element.css({
        height: this.element.get(0).scrollHeight
      });
    };
    Markupable.prototype.snippets = function() {};
    Markupable.actions = {
      undo: function() {
        return this.content(this.history.undo());
      },
      redo: function() {
        return this.content(this.history.redo());
      },
      insertHTML: function(selection, options) {
        var element;
        if (options.value.get && (element = options.value.get(0))) {
          options.value = jQuery('<div>').html(element).html();
        }
        return selection.replace(options.value, false, true);
      },
      insertImage: function(selection, options) {
        return selection.replace('![add alt text](' + encodeURI(options.value.src) + ')', true);
      },
      insertTable: function(selection, options) {
        return selection.replace(options.value.replace(/<br>|<br\/>/ig, ''), false, true);
      },
      insertLink: function(selection, options) {
        return selection.replace("[" + options.value.content + "](" + options.value.attrs.href + " 'optional title')", true);
      },
      insertUnorderedList: function(selection) {
        return selection.addList('unordered');
      },
      insertOrderedList: function(selection) {
        return selection.addList('ordered');
      },
      style: function(selection, options) {
        return selection.wrap("<span class=\"" + options.value + "\">", '</span>');
      },
      formatblock: function(selection, options) {
        var wrapper, wrapperName, wrappers;
        wrappers = {
          h1: ['# ', ' #'],
          h2: ['## ', ' ##'],
          h3: ['### ', ' ###'],
          h4: ['#### ', ' ####'],
          h5: ['##### ', ' #####'],
          h6: ['###### ', ' ######'],
          pre: ['    ', ''],
          blockquote: ['> ', ''],
          p: ['\n', '\n']
        };
        for (wrapperName in wrappers) {
          wrapper = wrappers[wrapperName];
          selection.unWrapLine("" + wrapper[0], "" + wrapper[1]);
        }
        if (options.value === 'blockquote') {
          Mercury.Regions.Markupable.actions.indent.call(this, selection, options);
          return;
        }
        return selection.wrapLine("" + wrappers[options.value][0], "" + wrappers[options.value][1]);
      },
      bold: function(selection) {
        return selection.wrap('**', '**');
      },
      italic: function(selection) {
        return selection.wrap('_', '_');
      },
      subscript: function(selection) {
        return selection.wrap('<sub>', '</sub>');
      },
      superscript: function(selection) {
        return selection.wrap('<sup>', '</sup>');
      },
      indent: function(selection) {
        return selection.wrapLine('> ', '', false, true);
      },
      outdent: function(selection) {
        return selection.unWrapLine('> ', '', false, true);
      },
      horizontalRule: function(selection) {
        return selection.replace('\n- - -\n');
      },
      insertSnippet: function(selection, options) {
        var snippet;
        snippet = options.value;
        return selection.replace(snippet.getText());
      }
    };
    return Markupable;
  })();
  Mercury.Regions.Markupable.Selection = (function() {
    function Selection(element) {
      this.element = element;
      this.el = this.element.get(0);
      this.getDetails();
    }
    Selection.prototype.serialize = function() {
      return {
        start: this.start,
        end: this.end
      };
    };
    Selection.prototype.getDetails = function() {
      this.length = this.el.selectionEnd - this.el.selectionStart;
      this.start = this.el.selectionStart;
      this.end = this.el.selectionEnd;
      return this.text = this.element.val().substr(this.start, this.length);
    };
    Selection.prototype.replace = function(text, select, placeCursor) {
      var changed, savedVal, val;
      if (select == null) {
        select = false;
      }
      if (placeCursor == null) {
        placeCursor = false;
      }
      this.getDetails();
      val = this.element.val();
      savedVal = this.element.val();
      this.element.val(val.substr(0, this.start) + text + val.substr(this.end, val.length));
      changed = this.element.val() !== savedVal;
      if (select) {
        this.select(this.start, this.start + text.length);
      }
      if (placeCursor) {
        this.select(this.start + text.length, this.start + text.length);
      }
      return changed;
    };
    Selection.prototype.select = function(start, end) {
      this.start = start;
      this.end = end;
      this.element.focus();
      this.el.selectionStart = this.start;
      this.el.selectionEnd = this.end;
      return this.getDetails();
    };
    Selection.prototype.wrap = function(left, right) {
      this.getDetails();
      this.deselectNewLines();
      this.replace(left + this.text + right, this.text !== '');
      if (this.text === '') {
        return this.select(this.start + left.length, this.start + left.length);
      }
    };
    Selection.prototype.wrapLine = function(left, right, selectAfter, reselect) {
      var end, savedSelection, start, text;
      if (selectAfter == null) {
        selectAfter = true;
      }
      if (reselect == null) {
        reselect = false;
      }
      this.getDetails();
      savedSelection = this.serialize();
      text = this.element.val();
      start = text.lastIndexOf('\n', this.start);
      end = text.indexOf('\n', this.end);
      if (end < start) {
        end = text.length;
      }
      if (text[start] === '\n') {
        start = text.lastIndexOf('\n', this.start - 1);
      }
      this.select(start + 1, end);
      this.replace(left + this.text + right, selectAfter);
      if (reselect) {
        return this.select(savedSelection.start + left.length, savedSelection.end + left.length);
      }
    };
    Selection.prototype.unWrapLine = function(left, right, selectAfter, reselect) {
      var changed, end, leftRegExp, rightRegExp, savedSelection, start, text;
      if (selectAfter == null) {
        selectAfter = true;
      }
      if (reselect == null) {
        reselect = false;
      }
      this.getDetails();
      savedSelection = this.serialize();
      text = this.element.val();
      start = text.lastIndexOf('\n', this.start);
      end = text.indexOf('\n', this.end);
      if (end < start) {
        end = text.length;
      }
      if (text[start] === '\n') {
        start = text.lastIndexOf('\n', this.start - 1);
      }
      this.select(start + 1, end);
      window.something = this.text;
      leftRegExp = new RegExp("^" + (left.regExpEscape()));
      rightRegExp = new RegExp("" + (right.regExpEscape()) + "$");
      changed = this.replace(this.text.replace(leftRegExp, '').replace(rightRegExp, ''), selectAfter);
      if (reselect && changed) {
        return this.select(savedSelection.start - left.length, savedSelection.end - left.length);
      }
    };
    Selection.prototype.addList = function(type) {
      var end, index, line, lines, start, text;
      text = this.element.val();
      start = text.lastIndexOf('\n', this.start);
      end = text.indexOf('\n', this.end);
      if (end < start) {
        end = text.length;
      }
      if (text[start] === '\n') {
        start = text.lastIndexOf('\n', this.start - 1);
      }
      this.select(start + 1, end);
      lines = this.text.split('\n');
      if (type === 'unordered') {
        return this.replace("- " + lines.join("\n- "), true);
      } else {
        return this.replace(((function() {
          var _len, _results;
          _results = [];
          for (index = 0, _len = lines.length; index < _len; index++) {
            line = lines[index];
            _results.push("" + (index + 1) + ". " + line);
          }
          return _results;
        })()).join('\n'), true);
      }
    };
    Selection.prototype.deselectNewLines = function() {
      var length, text;
      text = this.text;
      length = text.replace(/\n+$/g, '').length;
      return this.select(this.start, this.start + length);
    };
    Selection.prototype.placeMarker = function() {
      return this.wrap('[mercury-marker]', '[mercury-marker]');
    };
    Selection.prototype.removeMarker = function() {
      var end, start, val;
      val = this.element.val();
      start = val.indexOf('[mercury-marker]');
      if (!(start > -1)) {
        return;
      }
      end = val.indexOf('[mercury-marker]', start + 1) - '[mercury-marker]'.length;
      this.element.val(this.element.val().replace(/\[mercury-marker\]/g, ''));
      return this.select(start, end);
    };
    Selection.prototype.textContent = function() {
      return this.text;
    };
    return Selection;
  })();
}).call(this);
(function() {
  var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
    for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
    function ctor() { this.constructor = child; }
    ctor.prototype = parent.prototype;
    child.prototype = new ctor;
    child.__super__ = parent.prototype;
    return child;
  }, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.Regions.Snippetable = (function() {
    var type;
    __extends(Snippetable, Mercury.Region);
    type = 'snippetable';
    function Snippetable(element, window, options) {
      this.element = element;
      this.window = window;
      this.options = options != null ? options : {};
      this.type = 'snippetable';
      Snippetable.__super__.constructor.apply(this, arguments);
      this.makeSortable();
    }
    Snippetable.prototype.build = function() {
      if (this.element.css('minHeight') === '0px') {
        return this.element.css({
          minHeight: 20
        });
      }
    };
    Snippetable.prototype.bindEvents = function() {
      Snippetable.__super__.bindEvents.apply(this, arguments);
      Mercury.bind('unfocus:regions', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (Mercury.region === this) {
          this.element.removeClass('focus');
          this.element.sortable('destroy');
          return Mercury.trigger('region:blurred', {
            region: this
          });
        }
      }, this));
      Mercury.bind('focus:window', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (Mercury.region === this) {
          this.element.removeClass('focus');
          this.element.sortable('destroy');
          return Mercury.trigger('region:blurred', {
            region: this
          });
        }
      }, this));
      jQuery(this.document).keydown(__bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        switch (event.keyCode) {
          case 90:
            if (!event.metaKey) {
              return;
            }
            event.preventDefault();
            if (event.shiftKey) {
              this.execCommand('redo');
            } else {
              this.execCommand('undo');
            }
        }
      }, this));
      jQuery(this.document).keyup(__bind(function() {
        if (this.previewing) {
          return;
        }
        if (Mercury.region !== this) {
          return;
        }
        return Mercury.changes = true;
      }, this));
      this.element.mouseup(__bind(function() {
        if (this.previewing) {
          return;
        }
        this.focus();
        return Mercury.trigger('region:focused', {
          region: this
        });
      }, this));
      this.element.bind('dragover', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        event.preventDefault();
        return event.originalEvent.dataTransfer.dropEffect = 'copy';
      }, this));
      return this.element.bind('drop', __bind(function(event) {
        if (this.previewing) {
          return;
        }
        if (!Mercury.snippet) {
          return;
        }
        this.focus();
        event.preventDefault();
        return Mercury.Snippet.displayOptionsFor(Mercury.snippet);
      }, this));
    };
    Snippetable.prototype.focus = function() {
      Mercury.region = this;
      this.makeSortable();
      return this.element.addClass('focus');
    };
    Snippetable.prototype.togglePreview = function() {
      if (this.previewing) {
        this.makeSortable();
      } else {
        this.element.sortable('destroy');
        this.element.removeClass('focus');
      }
      return Snippetable.__super__.togglePreview.apply(this, arguments);
    };
    Snippetable.prototype.execCommand = function(action, options) {
      var handler;
      if (options == null) {
        options = {};
      }
      Snippetable.__super__.execCommand.apply(this, arguments);
      if (handler = Mercury.Regions.Snippetable.actions[action]) {
        return handler.call(this, options);
      }
    };
    Snippetable.prototype.makeSortable = function() {
      return this.element.sortable('destroy').sortable({
        document: this.document,
        scroll: false,
        containment: 'parent',
        items: '.mercury-snippet',
        opacity: 0.4,
        revert: 100,
        tolerance: 'pointer',
        beforeStop: __bind(function() {
          Mercury.trigger('hide:toolbar', {
            type: 'snippet',
            immediately: true
          });
          return true;
        }, this),
        stop: __bind(function() {
          setTimeout((__bind(function() {
            return this.pushHistory();
          }, this)), 100);
          return true;
        }, this)
      });
    };
    Snippetable.actions = {
      undo: function() {
        return this.content(this.history.undo());
      },
      redo: function() {
        return this.content(this.history.redo());
      },
      insertSnippet: function(options) {
        var existing, snippet;
        snippet = options.value;
        if ((existing = this.element.find("[data-snippet=" + snippet.identity + "]")).length) {
          return existing.replaceWith(snippet.getHTML(this.document, __bind(function() {
            return this.pushHistory();
          }, this)));
        } else {
          return this.element.append(snippet.getHTML(this.document, __bind(function() {
            return this.pushHistory();
          }, this)));
        }
      },
      editSnippet: function() {
        var snippet;
        if (!this.snippet) {
          return;
        }
        snippet = Mercury.Snippet.find(this.snippet.data('snippet'));
        return snippet.displayOptions();
      },
      removeSnippet: function() {
        if (this.snippet) {
          this.snippet.remove();
        }
        return Mercury.trigger('hide:toolbar', {
          type: 'snippet',
          immediately: true
        });
      }
    };
    return Snippetable;
  })();
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.dialogHandlers.backColor = function() {
    return this.element.find('.picker, .last-picked').click(__bind(function(event) {
      var color;
      color = jQuery(event.target).css('background-color');
      this.element.find('.last-picked').css({
        background: color
      });
      this.button.css({
        backgroundColor: color
      });
      return Mercury.trigger('action', {
        action: 'backColor',
        value: color
      });
    }, this));
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.dialogHandlers.foreColor = function() {
    return this.element.find('.picker, .last-picked').click(__bind(function(event) {
      var color;
      color = jQuery(event.target).css('background-color');
      this.element.find('.last-picked').css({
        background: color
      });
      this.button.css({
        backgroundColor: color
      });
      return Mercury.trigger('action', {
        action: 'foreColor',
        value: color
      });
    }, this));
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.dialogHandlers.formatblock = function() {
    return this.element.find('[data-tag]').click(__bind(function(event) {
      var tag;
      tag = jQuery(event.target).data('tag');
      return Mercury.trigger('action', {
        action: 'formatblock',
        value: tag
      });
    }, this));
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.dialogHandlers.snippetPanel = function() {
    this.element.find('input.filter').keyup(__bind(function() {
      var snippet, value, _i, _len, _ref, _results;
      value = this.element.find('input.filter').val();
      _ref = this.element.find('li[data-filter]');
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        snippet = _ref[_i];
        _results.push(LiquidMetal.score(jQuery(snippet).data('filter'), value) === 0 ? jQuery(snippet).hide() : jQuery(snippet).show());
      }
      return _results;
    }, this));
    return this.element.find('img[data-snippet]').bind('dragstart', function(event) {
      return Mercury.snippet = jQuery(this).data('snippet');
    });
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.dialogHandlers.style = function() {
    return this.element.find('[data-class]').click(__bind(function(event) {
      var className;
      className = jQuery(event.target).data('class');
      return Mercury.trigger('action', {
        action: 'style',
        value: className
      });
    }, this));
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.modalHandlers.htmlEditor = function() {
    var content;
    content = Mercury.region.content(null, true, false);
    this.element.find('textarea').val(content);
    return this.element.find('form').submit(__bind(function(event) {
      var value;
      event.preventDefault();
      value = this.element.find('textarea').val().replace(/\n/g, '');
      Mercury.trigger('action', {
        action: 'replaceHTML',
        value: value
      });
      return this.hide();
    }, this));
  };
}).call(this);
(function() {
  this.Mercury.modalHandlers.insertCharacter = function() {
    return this.element.find('.character').click(function() {
      Mercury.trigger('action', {
        action: 'insertHTML',
        value: "&" + (jQuery(this).attr('data-entity')) + ";"
      });
      return Mercury.modal.hide();
    });
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.modalHandlers.insertLink = function() {
    var bookmarkSelect, container, existingLink, href, link, newBookmarkInput, selection, _i, _len, _ref;
    this.element.find('label input').click(function(event) {
      return jQuery(this).closest('label').next('.selectable').focus();
    });
    this.element.find('.selectable').focus(function() {
      return jQuery(this).prev('label').find('input[type=radio]').prop("checked", true);
    });
    this.element.find('#link_target').change(__bind(function() {
      this.element.find(".link-target-options").hide();
      this.element.find("#" + (this.element.find('#link_target').val()) + "_options").show();
      return this.resize(true);
    }, this));
    bookmarkSelect = this.element.find('#link_existing_bookmark');
    _ref = jQuery('a[name]', window.mercuryInstance.document);
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      link = _ref[_i];
      bookmarkSelect.append(jQuery('<option>', {
        value: jQuery(link).attr('name')
      }).text(jQuery(link).text()));
    }
    if (Mercury.region && Mercury.region.selection) {
      selection = Mercury.region.selection();
      if (selection && selection.commonAncestor) {
        container = selection.commonAncestor(true).closest('a');
      }
      if (container && container.length) {
        existingLink = container;
        this.element.find('#link_text_container').hide();
        if (container.attr('href') && container.attr('href').indexOf('#') === 0) {
          bookmarkSelect.val(container.attr('href').replace(/[^#]*#/, ''));
          bookmarkSelect.prev('label').find('input[type=radio]').prop("checked", true);
        } else {
          this.element.find('#link_external_url').val(container.attr('href'));
        }
        if (container.attr('name')) {
          newBookmarkInput = this.element.find('#link_new_bookmark');
          newBookmarkInput.val(container.attr('name'));
          newBookmarkInput.prev('label').find('input[type=radio]').prop("checked", true);
        }
        if (container.attr('target')) {
          this.element.find('#link_target').val(container.attr('target'));
        }
        if (container.attr('href') && container.attr('href').indexOf('javascript:void') === 0) {
          href = container.attr('href');
          this.element.find('#link_external_url').val(href.match(/window.open\('([^']+)',/)[1]);
          this.element.find('#link_target').val('popup');
          this.element.find('#link_popup_width').val(href.match(/width=(\d+),/)[1]);
          this.element.find('#link_popup_height').val(href.match(/height=(\d+),/)[1]);
          this.element.find('#popup_options').show();
        }
      }
      if (selection.textContent) {
        this.element.find('#link_text').val(selection.textContent());
      }
    }
    return this.element.find('form').submit(__bind(function(event) {
      var args, attrs, content, target, type, value;
      event.preventDefault();
      content = this.element.find('#link_text').val();
      target = this.element.find('#link_target').val();
      type = this.element.find('input[name=link_type]:checked').val();
      switch (type) {
        case 'existing_bookmark':
          attrs = {
            href: "#" + (this.element.find('#link_existing_bookmark').val())
          };
          break;
        case 'new_bookmark':
          attrs = {
            name: "" + (this.element.find('#link_new_bookmark').val())
          };
          break;
        default:
          attrs = {
            href: this.element.find("#link_" + type).val()
          };
      }
      switch (target) {
        case 'popup':
          args = {
            width: parseInt(this.element.find('#link_popup_width').val()) || 500,
            height: parseInt(this.element.find('#link_popup_height').val()) || 500,
            menubar: 'no',
            toolbar: 'no'
          };
          attrs['href'] = "javascript:void(window.open('" + attrs['href'] + "', 'popup_window', '" + (jQuery.param(args).replace(/&/g, ',')) + "'))";
          break;
        default:
          if (target) {
            attrs['target'] = target;
          }
      }
      value = {
        tagName: 'a',
        attrs: attrs,
        content: content
      };
      if (existingLink) {
        Mercury.trigger('action', {
          action: 'replaceLink',
          value: value,
          node: existingLink.get(0)
        });
      } else {
        Mercury.trigger('action', {
          action: 'insertLink',
          value: value
        });
      }
      return this.hide();
    }, this));
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.modalHandlers.insertMedia = function() {
    var iframe, image, selection, src;
    this.element.find('label input').click(function(event) {
      return jQuery(this).closest('label').next('.selectable').focus();
    });
    this.element.find('.selectable').focus(__bind(function(event) {
      var element;
      element = jQuery(event.target);
      element.prev('label').find('input[type=radio]').prop("checked", true);
      this.element.find(".media-options").hide();
      this.element.find("#" + (element.attr('id').replace('media_', ''))).show();
      return this.resize(true);
    }, this));
    if (Mercury.region && Mercury.region.selection) {
      selection = Mercury.region.selection();
      if (selection.is && (image = selection.is('img'))) {
        this.element.find('#media_image_url').val(image.attr('src'));
        this.element.find('#media_image_alignment').val(image.attr('align'));
		
		
		
		element = jQuery(image);
	 	
		$h = element.height();
		$w = element.width();
		if ($h == 0) {
			$h = '';
		}
		
		if ($w == 0) {
			$w = '';
		}
		
		 this.element.find('#media_image_height').val($h);
		 this.element.find('#media_image_width').val($w);
		
		
		
      }
      if (selection.is && (iframe = selection.is('iframe'))) {
        src = iframe.attr('src');
        if (src.indexOf('http://www.youtube.com') > -1) {
          this.element.find('#media_youtube_url').val("http://youtu.be/" + (src.match(/\/embed\/(\w+)/)[1]));
          this.element.find('#media_youtube_width').val(iframe.width());
          this.element.find('#media_youtube_height').val(iframe.height());
          this.element.find('#media_youtube_url').focus();
        } else if (src.indexOf('http://player.vimeo.com') > -1) {
          this.element.find('#media_vimeo_url').val("http://vimeo.com/" + (src.match(/\/video\/(\w+)/)[1]));
          this.element.find('#media_vimeo_width').val(iframe.width());
          this.element.find('#media_vimeo_height').val(iframe.height());
          this.element.find('#media_vimeo_url').focus();
        }
      }
    }
    return this.element.find('form').submit(__bind(function(event) {
      var alignment, attrs, code, type, url, value;
      event.preventDefault();
      type = this.element.find('input[name=media_type]:checked').val();
      switch (type) {
        case 'image_url':
          attrs = {
            src: this.element.find('#media_image_url').val()
          };
          if (alignment = this.element.find('#media_image_alignment').val()) {
            attrs['align'] = alignment;
          }
		  if (alignment = this.element.find('#media_image_height').val()) {
            attrs['height'] = alignment;
          }
		    if (alignment = this.element.find('#media_image_width').val()) {
            attrs['width'] = alignment;
          }
          Mercury.trigger('action', {
            action: 'insertImage',
            value: attrs
          });
          break;
        case 'youtube_url':
          url = this.element.find('#media_youtube_url').val();
          if (!/^http:\/\/youtu.be\//.test(url)) {
            alert('Error: The provided youtube share url was invalid.');
            return;
          }
          code = url.replace('http://youtu.be/', '');
          value = jQuery('<iframe>', {
            width: this.element.find('#media_youtube_width').val() || 560,
            height: this.element.find('#media_youtube_height').val() || 349,
            src: "http://www.youtube.com/embed/" + code + "?wmode=transparent",
            frameborder: 0,
            allowfullscreen: 'true'
          });
          Mercury.trigger('action', {
            action: 'insertHTML',
            value: value
          });
          break;
        case 'vimeo_url':
          url = this.element.find('#media_vimeo_url').val();
          if (!/^http:\/\/vimeo.com\//.test(url)) {
            alert('Error: The provided vimeo url was invalid.');
            return;
          }
          code = url.replace('http://vimeo.com/', '');
          value = jQuery('<iframe>', {
            width: this.element.find('#media_vimeo_width').val() || 400,
            height: this.element.find('#media_vimeo_height').val() || 225,
            src: "http://player.vimeo.com/video/" + code + "?title=1&byline=1&portrait=0&color=ffffff",
            frameborder: 0
          });
          Mercury.trigger('action', {
            action: 'insertHTML',
            value: value
          });
      }
      return this.hide();
    }, this));
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.modalHandlers.insertSnippet = function() {
    return this.element.find('form').submit(__bind(function(event) {
      var serializedForm, snippet;
      event.preventDefault();
      serializedForm = this.element.find('form').serializeObject();
      if (Mercury.snippet) {
        snippet = Mercury.snippet;
        snippet.setOptions(serializedForm);
        Mercury.snippet = null;
      } else {
        snippet = Mercury.Snippet.create(this.options.snippetName, serializedForm);
      }
      Mercury.trigger('action', {
        action: 'insertSnippet',
        value: snippet
      });
      return this.hide();
    }, this));
  };
}).call(this);
(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
  this.Mercury.modalHandlers.insertTable = function() {
    var firstCell, table;
    table = this.element.find('#table_display table');
    table.click(__bind(function(event) {
      var cell;
      cell = jQuery(event.target);
      table = cell.closest('table');
      table.find('.selected').removeAttr('class');
      cell.addClass('selected');
      return Mercury.tableEditor(table, cell, '&nbsp;');
    }, this));
    firstCell = table.find('td, th').first();
    firstCell.addClass('selected');
    Mercury.tableEditor(table, firstCell, '&nbsp;');
    this.element.find('input.action').click(__bind(function(event) {
      var action;
      action = jQuery(event.target).attr('name');
      switch (action) {
        case 'insertRowBefore':
          return Mercury.tableEditor.addRow('before');
        case 'insertRowAfter':
          return Mercury.tableEditor.addRow('after');
        case 'deleteRow':
          return Mercury.tableEditor.removeRow();
        case 'insertColumnBefore':
          return Mercury.tableEditor.addColumn('before');
        case 'insertColumnAfter':
          return Mercury.tableEditor.addColumn('after');
        case 'deleteColumn':
          return Mercury.tableEditor.removeColumn();
        case 'increaseColspan':
          return Mercury.tableEditor.increaseColspan();
        case 'decreaseColspan':
          return Mercury.tableEditor.decreaseColspan();
        case 'increaseRowspan':
          return Mercury.tableEditor.increaseRowspan();
        case 'decreaseRowspan':
          return Mercury.tableEditor.decreaseRowspan();
      }
    }, this));
    this.element.find('#table_alignment').change(__bind(function() {
      return table.attr({
        align: this.element.find('#table_alignment').val()
      });
    }, this));
    this.element.find('#table_border').keyup(__bind(function() {
      return table.attr({
        border: parseInt(this.element.find('#table_border').val())
      });
    }, this));
    this.element.find('#table_spacing').keyup(__bind(function() {
      return table.attr({
        cellspacing: parseInt(this.element.find('#table_spacing').val())
      });
    }, this));
    return this.element.find('form').submit(__bind(function(event) {
      var html, value;
      event.preventDefault();
      table.find('.selected').removeAttr('class');
      table.find('td, th').html('<br/>');
      html = jQuery('<div>').html(table).html();
      value = html.replace(/^\s+|\n/gm, '').replace(/(<\/.*?>|<table.*?>|<tbody>|<tr>)/g, '$1\n');
      Mercury.trigger('action', {
        action: 'insertTable',
        value: value
      });
      return this.hide();
    }, this));
  };
}).call(this);
