/*!
* Aloha Editor
* Author & Copyright (c) 2010 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

define( [

	'aloha',
	'aloha/plugin',
	'aloha/jquery',
	'aloha/floatingmenu',
	'i18n!img/nls/i18n',
	'i18n!aloha/nls/i18n',
	'aloha/console',
	'img/../extra/imglist',
	'css!img/css/img.css'

], function( Aloha, Plugin, jQuery, FloatingMenu, i18n, i18nCore, console ) {
	"use strict";
	
	var
		GENTICS = window.GENTICS;
	//namespace prefix for this plugin
	var	imgNamespace = 'aloha-img';

	return Plugin.create('img', {
		
		/**
		 * Configure the available languages
		 */
		languages: ['en', 'de', 'fr', 'ru', 'pl'],
		
		/**
		 * Default configuration allows imgs everywhere
		 */
		config: ['img'],
		
		/**
		 * all imgs that match the targetregex will get set the target
		 * e.g. ^(?!.*aloha-editor.com).* matches all href except aloha-editor.com
		 */
		targetregex: '',
		
		/**
		  * this target is set when either targetregex matches or not set
		  * e.g. _blank opens all imgs in new window
		  */
		target: '',
		
		/**
		 * all imgs that match the cssclassregex will get set the css class
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
			var stringBuilder = [], prefix = imgNamespace;
			jQuery.each(arguments, function () { stringBuilder.push('.' + (this == '' ? prefix : prefix + '-' + this)); });
			return stringBuilder.join(' ').trim();
		},

		//Creates string with this component's namepsace prefixed the each classname
		nsClass: function () {
			var stringBuilder = [], prefix = imgNamespace;
			jQuery.each(arguments, function () { stringBuilder.push(this == '' ? prefix : prefix + '-' + this); });
			return stringBuilder.join(' ').trim();
		},

		initSidebar: function(sidebar) {
			var pl = this;
			pl.sidebar = sidebar;
			sidebar.addPanel({
					
					id       : pl.nsClass('sidebar-panel-target'),
					title    : i18n.t('floatingmenu.tab.img'),
					content  : '',
					expanded : true,
					activeOn : 'img',
					
					onInit     : function () {
						 var that = this,
							 content = this.setContent(
								'<div class="' + pl.nsClass('target-container') + '"><fieldset><legend>' + i18n.t('img.target.legend') + '</legend><ul><li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_self" /><span>' + i18n.t('img.target.self') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_blank" /><span>' + i18n.t('img.target.blank') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_parent" /><span>' + i18n.t('img.target.parent') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="_top" /><span>' + i18n.t('img.target.top') + '</span></li>' + 
								'<li><input type="radio" name="targetGroup" class="' + pl.nsClass('radioTarget') + '" value="framename" /><span>' + i18n.t('img.target.framename') + '</span></li>' + 
								'<li><input type="text" class="' + pl.nsClass('framename') + '" /></li></ul></fieldset></div>' + 
								'<div class="' + pl.nsClass('title-container') + '" ><fieldset><legend>' + i18n.t('img.title.legend') + '</legend><input type="text" class="' + pl.nsClass('imgTitle') + '" /></fieldset></div>').content; 
						 
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
						 
						 jQuery( pl.nsSel('imgTitle') ).live( 'keyup', function() {
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
						jQuery( pl.nsSel('imgTitle') ).val( jQuery(that.effective).attr('title') );
					}
					
				});
			sidebar.show();
		},
		
		/**
		 * Subscribe for events
		 */
		subscribeEvents: function () {
 

		},
		/**
		 * Initialize the buttons
		 */
		createButtons: function () {
			var that = this;

			// format img Button - this button behaves like 
			// a formatting button like (bold, italics, etc)
//			this.formatimgButton = new Aloha.ui.Button({
//				'name' : 'img',
//				'iconClass' : 'aloha-button aloha-button-a',
//				'size' : 'small',
//				'onclick' : function () { that.formatimg(); },
//				'tooltip' : i18n.t('button.addimg.tooltip'),
//				'toggle' : true
//			});
//			FloatingMenu.addButton(
//				'Aloha.continuoustext',
//				this.formatimgButton,
//				i18nCore.t('floatingmenu.tab.format'),
//				1
//			);

			// insert img
			// always inserts a new img
			this.insertimgButton = new Aloha.ui.Button({
				'name': 'insertimg',
				'iconClass' : 'aloha-button aloha-button-a',
				'size' : 'small',
				'onclick' : function () { that.insertimg( false ); },
				'tooltip' : i18n.t('button.addimg.tooltip'),
				'toggle' : false
			});
			FloatingMenu.addButton(
				'Aloha.continuoustext',
				this.insertimgButton,
				i18nCore.t('floatingmenu.tab.insert'),
				1
			);
			
			// add the new scope for imgs
			FloatingMenu.createScope('img', 'Aloha.continuoustext');

			this.hrefField = new Aloha.ui.AttributeField({
				'name': 'href',
				'width':320,
				'valueField': 'url'
			});
			this.hrefField.setTemplate('<span><b>{name}</b><br/>{url}</span>');
			this.hrefField.setObjectTypeFilter(this.objectTypeFilter);
			// add the input field for imgs
			FloatingMenu.addButton(
				'img',
				this.hrefField,
				i18n.t('floatingmenu.tab.img'),
				1
			);

			this.removeimgButton = new Aloha.ui.Button({
				// TODO use another icon here
				'name' : 'removeimg',
				'iconClass' : 'aloha-button aloha-button-a-remove',
				'size' : 'small',
				'onclick' : function () { that.removeimg(); },
				'tooltip' : i18n.t('button.removeimg.tooltip')
			});
			// add a button for removing the currently set img
			FloatingMenu.addButton(
				'img',
				this.removeimgButton,
				i18n.t('floatingmenu.tab.img'),
				1
			);

		},

		/**
		 * Parse a all editables for imgs and bind an onclick event
		 * Add the img short cut to all edtiables
		 */
		bindInteractions: function () {
			var that = this;

			// update img object when src changes
			this.hrefField.addListener('keyup', function(obj, event) {
				
				// Now show all the ui-attributefield elements
				jQuery('.x-layer x-combo-list').show(); 
				jQuery('.x-combo-list-inner').show();
				jQuery('.x-combo-list').show();
				
				// if the user presses ESC we do a rough check if he has entered a img or searched for something
				if (event.keyCode == 27) {
					var curval = that.hrefField.getQueryValue();
					if (
						curval[0] === '/' || // local img
						curval.match(/^.*\.([a-z]){2,4}$/i) || // local file with extension
						curval[0] === '#' || // inner document img
						curval.match(/^htt.*/i)  // external img
					) {
						// could be a img better leave it as it is
					} else {
						// the user searched for something and aborted restore original value
						// that.hrefField.setValue(that.hrefField.getValue());
					}
				}

				that.hrefChange();
				
				
				// Handle the enter key. Terminate the img scope and show the final img.
				if (event.keyCode == 13) {
					// Update the selection and place the cursor at the end of the img.
					var	range = Aloha.Selection.getRangeObject();
					
					// workaround to keep the found markup otherwise removeimg won't work
					var foundMarkup = that.findimgMarkup( range );
					that.hrefField.setTargetObject(foundMarkup, 'href');
					
					that.ignoreNextSelectionChangedEvent = true;
					range.startContainer = range.endContainer;
					range.startOffset = range.endOffset;
					range.select();
					//that.ignoreNextSelectionChangedEvent = true;
					
					var hrefValue = jQuery(that.hrefField.extButton.el.dom).attr('value');
					if (hrefValue ==="http://" || hrefValue === "") {
						that.removeimg(false);
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
			this.hrefField.addListener('bluasdasdasr', function(obj, event) {

				
				var hrefValue = jQuery(that.hrefField.extButton.el.dom).attr('value');
				
				//checks for either a literal value in the href field
				//(that is not the pre-filled "http://") or a resource
				//(e.g. in the case of a repository img)
				if ( ( ! this.getValue() || this.getValue() === "http://" ) && ! this.getItem() ) {
					that.removeimg(false);
				}
			});

			// add to all editables the img shortcut
			jQuery.each(Aloha.editables, function(key, editable){

				// CTRL+L
				editable.obj.keydown(function (e) {
					if ( e.metaKey && e.which == 76 ) {
						if ( that.findimgMarkup() ) {
							FloatingMenu.userActivatedTab = i18n.t('floatingmenu.tab.img');

							// TODO this should not be necessary here!
							FloatingMenu.doLayout();

							that.hrefField.focus();

						} else {
							that.insertimg();
						}
						// prevent from further handling
						// on a MAC Safari cursor would jump to location bar. Use ESC then META+L
						return false;
					}
				});

				editable.obj.find('img').each(function( i ) {

					// show pointer on mouse over
					jQuery(this).mouseenter( function(e) {
						Aloha.Log.debug(that, 'mouse over img.');
						that.mouseOverimg = this;
						that.updateMousePointer();
					});

					// in any case on leave show text cursor
					jQuery(this).mouseleave( function(e) {
						Aloha.Log.debug(that, 'mouse left img.');
						that.mouseOverimg = null;
						that.updateMousePointer();
					});


					// follow img on ctrl or meta + click
					jQuery(this).click( function(e) {
						if (e.metaKey) {

							// blur current editable. user is wating for the img to load
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
			if ( this.metaKey && this.mouseOverimg ) {
				Aloha.Log.debug(this, 'set pointer');
				jQuery(this.mouseOverimg).removeClass('aloha-img-text');
				jQuery(this.mouseOverimg).addClass('aloha-img-pointer');
			} else {
				jQuery(this.mouseOverimg).removeClass('aloha-img-pointer');
				jQuery(this.mouseOverimg).addClass('aloha-img-text');
			}
		},

		/**
		 * Check whether inside a img tag
		 * @param {GENTICS.Utils.RangeObject} range range where to insert the object (at start or end)
		 * @return markup
		 * @hide
		 */
		findimgMarkup: function ( range ) {

			if ( typeof range == 'undefined' ) {
				range = Aloha.Selection.getRangeObject();
			}
			if ( Aloha.activeEditable ) {
				return range.findMarkup(function() {
					return this.nodeName.toLowerCase() == 'img';
				}, Aloha.activeEditable.obj);
			} else {
				return null;
			}
		},

		/**
		 * Format the current selection or if collapsed the current word as img.
		 * If inside a img tag the img is removed.
		 */
		formatimg: function () {

			var range = Aloha.Selection.getRangeObject();

			if (Aloha.activeEditable) {
				if ( this.findimgMarkup( range ) ) {
					this.removeimg();
				} else {
					this.insertimg();
				}
			}
		},

		/**
		 * Insert a new img at the current selection. When the selection is collapsed,
		 * the img will have a default img text, otherwise the selected text will be
		 * the img text.
		 */
		insertimg: function ( extendToWord ) {
			var range, imgText, newimg;

			// do not insert a img in a img
			if ( this.findimgMarkup( range ) ) {
				return;
			}

			// activate floating menu tab
			FloatingMenu.userActivatedTab = i18n.t('floatingmenu.tab.img');

			// current selection or cursor position
			range = Aloha.Selection.getRangeObject();

			// if selection is collapsed then extend to the word.
			if (range.isCollapsed() && extendToWord !== false) {
				GENTICS.Utils.Dom.extendToWord(range);
			}
			if ( range.isCollapsed() ) {
				// insert a img with text here
				imgText = i18n.t('newimg.defaulttext');
				//newimg = jQuery('<a href="">' + imgText + '</a>');
				newimg = jQuery('<img href="" src="http://lorempixel.com/400/200/" />');
				GENTICS.Utils.Dom.insertIntoDOM(newimg, range, jQuery(Aloha.activeEditable.obj));
				GENTICS.Utils.Dom.addMarkup(range, newimg, false);
				 
			} else {
				newimg = jQuery('<a href=""></a>');
				newimg = jQuery('<img href="" src="http://lorempixel.com/400/200/" />');
				GENTICS.Utils.Dom.addMarkup(range, newimg, false);
			}
			//range.select();

			// focus has to become before prefilling the attribute, otherwise
			// Chrome and Firefox will not focus the element correctly.
			this.hrefField.focus();
			// prefill and select the new href
			//jQuery(this.hrefField.extButton.el.dom).attr('value', 'http://').select();
		//	this.hrefChange();
		},

		/**
		 * Remove an a tag.
		 */
		removeimg: function (terminateimgScope) {
			var	range = Aloha.Selection.getRangeObject(),
				foundMarkup = this.findimgMarkup();
			if ( foundMarkup ) {
				// remove the img
				GENTICS.Utils.Dom.removeFromDOM(foundMarkup, range, true);

				range.startContainer = range.endContainer;
				range.startOffset = range.endOffset;

				// select the (possibly modified) range
				range.select();
				
				if (typeof terminateimgScope === 'undefined' || terminateimgScope === true) {
					FloatingMenu.setScope('Aloha.continuoustext');
				}

			}
		},

		/**
		 * Updates the img object depending on the src field
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
			Aloha.trigger('aloha-img-href-change', {
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
		 * Find all imgs and remove editing objects
		 * @param obj jQuery object to make clean
		 * @return void
		 */
		makeClean: function (obj) {
			// find all img tags
			obj.find('img').each(function() {
				jQuery(this).removeClass('aloha-img-pointer');
				jQuery(this).removeClass('aloha-img-text');
			});
		}
	});
});
