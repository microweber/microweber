/*!
* Aloha Editor
* Author & Copyright (c) 2010 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

define(['aloha', 'aloha/plugin', 'aloha/jquery', 'aloha/floatingmenu', 'i18n!link/nls/i18n', 'i18n!aloha/nls/i18n', 'aloha/console',
        'css!link/css/link.css'],
function(Aloha, Plugin, jQuery, FloatingMenu, i18n, i18nCore, console) {
	

	var
		GENTICS = window.GENTICS;
	//namespace prefix for this plugin
	var	linkNamespace = 'aloha-link';

	return Plugin.create('link', {
		
		/**
		 * Configure the available languages
		 */
		languages: ['en', 'de', 'fr', 'ru', 'pl'],
		
		/**
		 * Default configuration allows links everywhere
		 */
		config: ['a'],
		
		/**
		 * all links that match the targetregex will get set the target
		 * e.g. ^(?!.*aloha-editor.com).* matches all href except aloha-editor.com
		 */
		targetregex: '',
		
		/**
		  * this target is set when either targetregex matches or not set
		  * e.g. _blank opens all links in new window
		  */
		target: '',
		
		/**
		 * all links that match the cssclassregex will get set the css class
		 * e.g. ^(?!.*aloha-editor.com).* matches all href except aloha-editor.com
		 */
		cssclassregex: '',
		
		/**
		  * this target is set when either cssclassregex matches or not set
		  */
		cssclass: '',
		
		/**
		 * the defined object types to be used for this instance
		 */
		objectTypeFilter: [],
		
		/**
		 * handle change on href change
		 * called function( obj, href, item );
		 */
		onHrefChange: null,

		
		/**
		 * This variable is used to ignore one selection changed event. We need
		 * to ignore one selectionchanged event when we set our own selection.
		 */
		ignoreNextSelectionChangedEvent: false,
		
		/**
		 * Initialize the plugin
		 */
		init: function () {
			var that = this;
			if ( typeof this.settings.targetregex !== 'undefined') {
				this.targetregex = this.settings.targetregex;
			}
			if ( typeof this.settings.target !== 'undefined') {
				this.target = this.settings.target;
			}
			if ( typeof this.settings.cssclassregex !== 'undefined') {
				this.cssclassregex = this.settings.cssclassregex;
			}
			if ( typeof this.settings.cssclass !== 'undefined') {
				this.cssclass = this.settings.cssclass;
			}
			if ( typeof this.settings.objectTypeFilter !== 'undefined') {
				this.objectTypeFilter = this.settings.objectTypeFilter;
			}
			if ( typeof this.settings.onHrefChange !== 'undefined') {
				this.onHrefChange = this.settings.onHrefChange;
			}

			this.createButtons();
			this.subscribeEvents();
			this.bindInteractions();
	
			Aloha.ready(function () { 
				that.initSidebar(Aloha.Sidebar.right); 
			});
		},

		nsSel: function () {
			var stringBuilder = [], prefix = linkNamespace;
			jQuery.each(arguments, function () { stringBuilder.push('.' + (this == '' ? prefix : prefix + '-' + this)); });
			return stringBuilder.join(' ').trim();
		},

		//Creates string with this component's namepsace prefixed the each classname
		nsClass: function () {
			var stringBuilder = [], prefix = linkNamespace;
			jQuery.each(arguments, function () { stringBuilder.push(this == '' ? prefix : prefix + '-' + this); });
			return stringBuilder.join(' ').trim();
		},

		initSidebar: function(sidebar) {
			var pl = this;
			pl.sidebar = sidebar;
			sidebar.addPanel({
					
					id       : pl.nsClass('sidebar-panel-target'),
					title    : i18n.t('floatingmenu.tab.link'),
					content  : '',
					expanded : true,
					activeOn : 'a, link',
					
					onInit     : function () {
						 var that = this,
							 content = this.setContent(
								'<div class="' + pl.nsClass('target-container') + '"><fieldset><legend>' + i18n.t('link.target.legend') + '</legend><ul><li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_self" /><span>' + i18n.t('link.target.self') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_blank" /><span>' + i18n.t('link.target.blank') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_parent" /><span>' + i18n.t('link.target.parent') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_top" /><span>' + i18n.t('link.target.top') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="framename" /><span>' + i18n.t('link.target.framename') + '</span></li>' + 
								'<li><input type="text" class="' + pl.nsClass('framename') + '" /></li></ul></fieldset></div>' + 
								'<div class="' + pl.nsClass('title-container') + '" ><fieldset><legend>' + i18n.t('link.title.legend') + '</legend><input type="text" class="' + pl.nsClass('linkTitle') + '" /></fieldset></div>').content; 
						 
						 jQuery( pl.nsSel('framename') ).live( 'keyup', function() {
							jQuery( that.effective ).attr( "target", jQuery(this).val().replace("\"", '&quot;').replace("'", "&#39;") );
						 });
						 
						 jQuery( pl.nsSel('radioTarget') ).live( 'change', function() {
							if ( jQuery(this).val() === "framename" ) {
								jQuery( pl.nsSel('framename') ).slideDown();
							}
							else {
								jQuery( pl.nsSel('framename') ).slideUp();
								jQuery( pl.nsSel('framename') ).val("");
								jQuery( that.effective ).attr( "target", jQuery(this).val() );
							}
						 });
						 
						 jQuery( pl.nsSel('linkTitle') ).live( 'keyup', function() {
							jQuery( that.effective ).attr( "title", jQuery(this).val().replace("\"", '&quot;').replace("'", "&#39;") );
						 });
					},
					
					onActivate: function (effective) {
						var that = this;
						that.effective = effective;
						if( jQuery(that.effective).attr('target') != null ) {
							var isFramename = true;
							jQuery( pl.nsSel('framename') ).hide();
							jQuery( pl.nsSel('framename') ).val("");
							jQuery( pl.nsSel('radioTarget') ).each( function () {
								jQuery(this).removeAttr('checked');
								if ( jQuery(this).val() === jQuery(that.effective).attr('target')) {
									isFramename = false;
									jQuery(this).attr('checked', 'checked');
								}
							});
							if ( isFramename ) {
								jQuery( pl.nsSel('radioTarget[value="framename"]') ).attr('checked', 'checked');
								jQuery( pl.nsSel('framename') ).val( jQuery(that.effective).attr('target') );
								jQuery( pl.nsSel('framename') ).show();
							}
						}else {
							jQuery( pl.nsSel('radioTarget') ).first().attr('checked', 'checked');
							jQuery( that.effective ).attr( 'target', jQuery(pl.nsSel('radioTarget')).first().val() );
						}
						
						var that = this;
						that.effective = effective;
						jQuery( pl.nsSel('linkTitle') ).val( jQuery(that.effective).attr('title') );
					}
					
				});
			sidebar.show();
		},
		
		/**
		 * Subscribe for events
		 */
		subscribeEvents: function () {

			var that = this;

			// add the event handler for selection change
			Aloha.bind('aloha-selection-changed', function(event, rangeObject) {
				var config, foundMarkup;
				
				// Check if we need to ignore this selection changed event for
				// now and check whether the selection was placed within a
				// editable area.
				if ( ! that.ignoreNextSelectionChangedEvent && Aloha.Selection.isSelectionEditable() ) {
					
					// show/hide the button according to the configuration
					config = that.getEditableConfig(Aloha.activeEditable.obj);
					if ( jQuery.inArray('a', config) != -1) {
						that.formatLinkButton.show();
						that.insertLinkButton.show();
					} else {
						that.formatLinkButton.hide();
						that.insertLinkButton.hide();
						// leave if a is not allowed
						return;
					}

					foundMarkup = that.findLinkMarkup( rangeObject );

					// link found
					if ( foundMarkup ) {
						that.insertLinkButton.hide();
						that.formatLinkButton.setPressed(true);
						FloatingMenu.setScope('link');
						that.hrefField.setTargetObject(foundMarkup, 'href');
					
						
					} else {
						// no link found
						that.formatLinkButton.setPressed(false);
						that.hrefField.setTargetObject(null);
					}

					// TODO this should not be necessary here!
					FloatingMenu.doLayout();
				}
				that.ignoreNextSelectionChangedEvent = false;


			});

		},
		/**
		 * Initialize the buttons
		 */
		createButtons: function () {
			var that = this;

			// format Link Button - this button behaves like 
			// a formatting button like (bold, italics, etc)
			this.formatLinkButton = new Aloha.ui.Button({
				'name' : 'a',
				'iconClass' : 'aloha-button aloha-button-a',
				'size' : 'small',
				'onclick' : function () { that.formatLink(); },
				'tooltip' : i18n.t('button.addlink.tooltip'),
				'toggle' : true
			});
			FloatingMenu.addButton(
				'Aloha.continuoustext',
				this.formatLinkButton,
				i18nCore.t('floatingmenu.tab.format'),
				1
			);

			// insert Link
			// always inserts a new link
			this.insertLinkButton = new Aloha.ui.Button({
				'name': 'insertLink',
				'iconClass' : 'aloha-button aloha-button-a',
				'size' : 'small',
				'onclick' : function () { that.insertLink( false ); },
				'tooltip' : i18n.t('button.addlink.tooltip'),
				'toggle' : false
			});
			FloatingMenu.addButton(
				'Aloha.continuoustext',
				this.insertLinkButton,
				i18nCore.t('floatingmenu.tab.insert'),
				1
			);

			// add the new scope for links
			FloatingMenu.createScope('link', 'Aloha.continuoustext');

			this.hrefField = new Aloha.ui.AttributeField({
				'name': 'href',
				'width':320,
				'valueField': 'url'
			});
			this.hrefField.setTemplate('<span><b>{name}</b><br/>{url}</span>');
			this.hrefField.setObjectTypeFilter(this.objectTypeFilter);
			// add the input field for links
			FloatingMenu.addButton(
				'link',
				this.hrefField,
				i18n.t('floatingmenu.tab.link'),
				1
			);

			this.removeLinkButton = new Aloha.ui.Button({
				// TODO use another icon here
				'name' : 'removeLink',
				'iconClass' : 'aloha-button aloha-button-a-remove',
				'size' : 'small',
				'onclick' : function () { that.removeLink(); },
				'tooltip' : i18n.t('button.removelink.tooltip')
			});
			// add a button for removing the currently set link
			FloatingMenu.addButton(
				'link',
				this.removeLinkButton,
				i18n.t('floatingmenu.tab.link'),
				1
			);

		},

		/**
		 * Parse a all editables for links and bind an onclick event
		 * Add the link short cut to all edtiables
		 */
		bindInteractions: function () {
			var that = this;

			// update link object when src changes
			this.hrefField.addListener('keyup', function(obj, event) {
				
				// Now show all the ui-attributefield elements
				jQuery('.x-layer x-combo-list').show(); 
				jQuery('.x-combo-list-inner').show();
				jQuery('.x-combo-list').show();
				
				// if the user presses ESC we do a rough check if he has entered a link or searched for something
				if (event.keyCode == 27) {
					var curval = that.hrefField.getQueryValue();
					if (
						curval[0] === '/' || // local link
						curval.match(/^.*\.([a-z]){2,4}$/i) || // local file with extension
						curval[0] === '#' || // inner document link
						curval.match(/^htt.*/i)  // external link
					) {
						// could be a link better leave it as it is
					} else {
						// the user searched for something and aborted restore original value
						// that.hrefField.setValue(that.hrefField.getValue());
					}
				}

				that.hrefChange();
				
				
				// Handle the enter key. Terminate the link scope and show the final link.
				if (event.keyCode == 13) {
					// Update the selection and place the cursor at the end of the link.
					var	range = Aloha.Selection.getRangeObject();
					
					// workaround to keep the found markup otherwise removelink won't work
					var foundMarkup = that.findLinkMarkup( range );
					that.hrefField.setTargetObject(foundMarkup, 'href');
					
					that.ignoreNextSelectionChangedEvent = true;
					range.startContainer = range.endContainer;
					range.startOffset = range.endOffset;
					range.select();
					//that.ignoreNextSelectionChangedEvent = true;
					
					var hrefValue = jQuery(that.hrefField.extButton.el.dom).attr('value');
					if (hrefValue ==="http://" || hrefValue === "") {
						that.removeLink(false);
					}
					
					setTimeout( function() {
						FloatingMenu.setScope('Aloha.continuoustext');
					}, 100);
					
					jQuery('.x-layer').hide();
					jQuery('.x-shadow').hide();
					jQuery('.x-combo-list-inner').hide();
					jQuery('.x-combo-list').hide();
					
					setTimeout( function() {
						jQuery('.x-layer').hide();
						jQuery('.x-shadow').hide();
						jQuery('.x-combo-list-inner').hide();
						jQuery('.x-combo-list').hide();
							
					},200);
					
				}
				
			});

			// on blur check if href is empty. If so remove the a tag
			this.hrefField.addListener('blur', function(obj, event) {

				
				var hrefValue = jQuery(that.hrefField.extButton.el.dom).attr('value');
				
				//checks for either a literal value in the href field
				//(that is not the pre-filled "http://") or a resource
				//(e.g. in the case of a repository link)
				if ( ( ! this.getValue() || this.getValue() === "http://" ) && ! this.getItem() ) {
					that.removeLink(false);
				}
			});

			// add to all editables the Link shortcut
			jQuery.each(Aloha.editables, function(key, editable){

				// CTRL+L
				editable.obj.keydown(function (e) {
					if ( e.metaKey && e.which == 76 ) {
						if ( that.findLinkMarkup() ) {
							FloatingMenu.userActivatedTab = i18n.t('floatingmenu.tab.link');

							// TODO this should not be necessary here!
							FloatingMenu.doLayout();

							that.hrefField.focus();

						} else {
							that.insertLink();
						}
						// prevent from further handling
						// on a MAC Safari cursor would jump to location bar. Use ESC then META+L
						return false;
					}
				});

				editable.obj.find('a').each(function( i ) {

					// show pointer on mouse over
					jQuery(this).mouseenter( function(e) {
						Aloha.Log.debug(that, 'mouse over link.');
						that.mouseOverLink = this;
						that.updateMousePointer();
					});

					// in any case on leave show text cursor
					jQuery(this).mouseleave( function(e) {
						Aloha.Log.debug(that, 'mouse left link.');
						that.mouseOverLink = null;
						that.updateMousePointer();
					});


					// follow link on ctrl or meta + click
					jQuery(this).click( function(e) {
						if (e.metaKey) {

							// blur current editable. user is wating for the link to load
							Aloha.activeEditable.blur();

							// hack to guarantee a browser history entry
							setTimeout( function() {
								  location.href = e.target;
							},0);

							// stop propagation
							e.stopPropagation();
							return false;
						}
					});

				});
			});

			jQuery(document)
				.keydown(function (e) {
					Aloha.Log.debug(that, 'Meta key down.');
					that.metaKey = e.metaKey;
					that.updateMousePointer();
				}).keyup(function (e) {
					Aloha.Log.debug(that, 'Meta key up.');
					that.metaKey = e.metaKey;
					that.updateMousePointer();
				});

		},

		/**
		 * Updates the mouse pointer
		 */
		updateMousePointer: function () {
			if ( this.metaKey && this.mouseOverLink ) {
				Aloha.Log.debug(this, 'set pointer');
				jQuery(this.mouseOverLink).removeClass('aloha-link-text');
				jQuery(this.mouseOverLink).addClass('aloha-link-pointer');
			} else {
				jQuery(this.mouseOverLink).removeClass('aloha-link-pointer');
				jQuery(this.mouseOverLink).addClass('aloha-link-text');
			}
		},

		/**
		 * Check whether inside a link tag
		 * @param {GENTICS.Utils.RangeObject} range range where to insert the object (at start or end)
		 * @return markup
		 * @hide
		 */
		findLinkMarkup: function ( range ) {

			if ( typeof range == 'undefined' ) {
				range = Aloha.Selection.getRangeObject();
			}
			if ( Aloha.activeEditable ) {
				return range.findMarkup(function() {
					return this.nodeName.toLowerCase() == 'a';
				}, Aloha.activeEditable.obj);
			} else {
				return null;
			}
		},

		/**
		 * Format the current selection or if collapsed the current word as link.
		 * If inside a link tag the link is removed.
		 */
		formatLink: function () {

			var range = Aloha.Selection.getRangeObject();

			if (Aloha.activeEditable) {
				if ( this.findLinkMarkup( range ) ) {
					this.removeLink();
				} else {
					this.insertLink();
				}
			}
		},

		/**
		 * Insert a new link at the current selection. When the selection is collapsed,
		 * the link will have a default link text, otherwise the selected text will be
		 * the link text.
		 */
		insertLink: function ( extendToWord ) {
			var range, linkText, newLink;

			// do not insert a link in a link
			if ( this.findLinkMarkup( range ) ) {
				return;
			}

			// activate floating menu tab
			FloatingMenu.userActivatedTab = i18n.t('floatingmenu.tab.link');

			// current selection or cursor position
			range = Aloha.Selection.getRangeObject();

			// if selection is collapsed then extend to the word.
			if (range.isCollapsed() && extendToWord !== false) {
				GENTICS.Utils.Dom.extendToWord(range);
			}
			if ( range.isCollapsed() ) {
				// insert a link with text here
				linkText = i18n.t('newlink.defaulttext');
				newLink = jQuery('<a href="">' + linkText + '</a>');
				GENTICS.Utils.Dom.insertIntoDOM(newLink, range, jQuery(Aloha.activeEditable.obj));
				range.startContainer = range.endContainer = newLink.contents().get(0);
				range.startOffset = 0;
				range.endOffset = linkText.length;
			} else {
				newLink = jQuery('<a href=""></a>');
				GENTICS.Utils.Dom.addMarkup(range, newLink, false);
			}
			range.select();

			// focus has to become before prefilling the attribute, otherwise
			// Chrome and Firefox will not focus the element correctly.
			this.hrefField.focus();
			// prefill and select the new href
			jQuery(this.hrefField.extButton.el.dom).attr('value', 'http://').select();
			this.hrefChange();
		},

		/**
		 * Remove an a tag.
		 */
		removeLink: function (terminateLinkScope) {
			var	range = Aloha.Selection.getRangeObject(),
				foundMarkup = this.findLinkMarkup();
			if ( foundMarkup ) {
				// remove the link
				GENTICS.Utils.Dom.removeFromDOM(foundMarkup, range, true);

				range.startContainer = range.endContainer;
				range.startOffset = range.endOffset;

				// select the (possibly modified) range
				range.select();
				
				if (typeof terminateLinkScope === 'undefined' || terminateLinkScope === true) {
					FloatingMenu.setScope('Aloha.continuoustext');
				}

			}
		},

		/**
		 * Updates the link object depending on the src field
		 */
		hrefChange: function () {
			var that = this;
			// For now hard coded attribute handling with regex.
			// Avoid creating the target attribute, if it's unnecessary, so
			// that XSS scanners (AntiSamy) don't complain.
			if (this.target !== '') {
				this.hrefField.setAttribute('target', this.target, this.targetregex, this.hrefField.getQueryValue());
			}
			this.hrefField.setAttribute('class', this.cssclass, this.cssclassregex, this.hrefField.getQueryValue());
			Aloha.trigger('aloha-link-href-change', {
				 obj: that.hrefField.getTargetObject(),
				 href: that.hrefField.getQueryValue(),
				 item: that.hrefField.getItem()
			});
			if ( typeof this.onHrefChange === 'function' ) {
				this.onHrefChange.call(this, this.hrefField.getTargetObject(),  this.hrefField.getQueryValue(), this.hrefField.getItem() );
			}
		},

		/**
		 * Make the given jQuery object (representing an editable) clean for saving
		 * Find all links and remove editing objects
		 * @param obj jQuery object to make clean
		 * @return void
		 */
		makeClean: function (obj) {
			// find all link tags
			obj.find('a').each(function() {
				jQuery(this).removeClass('aloha-link-pointer');
				jQuery(this).removeClass('aloha-link-text');
			});
		}
	});
});
