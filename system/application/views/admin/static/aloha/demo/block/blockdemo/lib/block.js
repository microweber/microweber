/*!
* Aloha Editor
* Author & Copyright (c) 2010 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

define([
   	'aloha/jquery',
	'block/block',
	'block/blockmanager',
	'blockdemo/vendor/underscore'
], function(jQuery, block, BlockManager, vcardTemplate) {
	

	var CompanyBlock = block.AbstractBlock.extend({
		title: 'Company',

		getSchema: function() {
			return {
				symbol: {
					type: 'string',
					label: 'Stock Quote Name'
				}
			};
		},
		render: function($innerElement) {

			// Mapping Stock-Symbol -- Company Name (Fake!)
			switch (this.attr('symbol')) {
				case 'MSFT':
					$innerElement.html('Microsoft');
					break;
				case 'AAPL':
					$innerElement.html('Apple Inc.');
					break;
				default:
					$innerElement.html(this.attr('symbol'));
			}

			var that = this;
			this.element.mouseover(function() {
				that.element.append('<span class="stock-quote-overlay company-' + that.attr('symbol') + '"></span>');
			});
			this.element.mouseout(function() {
				that.element.find('.stock-quote-overlay').remove();
			});
		}
	});

	var EditableProductTeaserBlock = block.AbstractBlock.extend({
		title: 'product teaser',

		getSchema: function() {
			return {
				'price': {
					type: 'number',
					label: 'Price'
				}
			}
		},

		render: function() {
			return '<span class="aloha-editable">' + this.attr('title') + '</span> <strong class="price">(' + this.attr('price') + ')</strong>';
		}
	});

	var ProductTeaserBlock = block.AbstractBlock.extend({
		title: 'product teaser',

		getSchema: function() {
			return {
				'title': {
					type: 'string',
					label: 'Product Title'
				},
				'price': {
					type: 'number',
					label: 'Price'
				}
			}
		},

		render: function() {
			return this.attr('title') + ' <strong class="price">(' + this.attr('price') + ' &euro;)</strong>';
		}
	});

	var VCardBlock;
	
	Aloha.require( ['text!blockdemo/res/vcard.html'] , function ( vcardTemplate ) {

		// Compile the template through underscore
		var template = _.template( vcardTemplate );

		VCardBlock = block.AbstractBlock.extend({
			title: 'vCard',

			getSchema: function() {
				return {
					firstname: {
						type: 'string',
						label: 'First Name'
					},
					lastname: {
						type: 'string',
						label: 'Last Name'
					},
					url: {
						type: 'url',
						label: 'URL'
					},
					org: {
						type: 'string',
						label: 'Organization'
					},
					email: {
						type: 'email',
						label: 'E-Mail'
					}
				};
			},

			render: function() {
				return template(jQuery.extend(
					{
						url: '',
						org: '',
						email: '',
						firstname: '',
						lastname: ''
					}, this.attr()));
			}
		});
	});


	var CustomHandleBlock = block.DefaultBlock.extend({
		renderToolbar: function() {
			var that = this;
			var deleteHandle = jQuery('<span class="block-draghandle-topleft"><a href="#"><span>x</span> delete</a></span>');
			this.element.prepend(deleteHandle);
			deleteHandle.click(function() {
				that.destroy();
				return false;
			});
		}
	});

	var AbstractTwoColumnBlock = block.AbstractBlock.extend({
		containerDefinitions: {
			left: {
				selector: '.column-left'
			},
			right: {
				selector: '.column-right'
			}
		},

		contents: null,

		_initialized: false,

		_registerAsBlockified: function() {
			// do NOT register myself as blockified yet, as we have to wait for our children to be blockified.
		},

		whenAllChildrenBlockified: function(next) {
			var that = this, numberOfNotYetBlockifiedElements = 0, $containersWithDomBlocks = {};

			var template = jQuery('<div />').html(this.$innerElement.html());

			var findBlocksForDomElements = function() {
				var containersWithBlocks = {};
				jQuery.each($containersWithDomBlocks, function(containerName, $domBlocks) {
					containersWithBlocks[containerName] = jQuery.map($domBlocks, function($domBlock) {
						return BlockManager.getBlock($domBlock);
					})
				});
				return containersWithBlocks;
			}

			var decrementAndCheckNumberOfBlockifiedElements = function() {
				numberOfNotYetBlockifiedElements--;
				if (numberOfNotYetBlockifiedElements <= 0) {
					next(findBlocksForDomElements());
				}
			}
			jQuery.each(this.containerDefinitions, function(name, config) {
				template.find(config.selector).empty();

				var $container = that.element.find(config.selector).first();
				var $elements = $container.children(); // TODO: block container only contains other blocks as children, nothing else!
				$elements.each(function() {
					var $element = jQuery(this);
					if (!BlockManager.getBlock($element)) {
						// Not blockified yet
						numberOfNotYetBlockifiedElements++;
						$element.bind('block-initialized', decrementAndCheckNumberOfBlockifiedElements);
					}
				});
				$containersWithDomBlocks[name] = $elements;
			});

			if (!this.attr('template')) {
				this.attr('template', template.html());
			}

			if (numberOfNotYetBlockifiedElements <= 0) {
				next(findBlocksForDomElements());
			}
		},
		init: function() {
			var that = this;
			this.whenAllChildrenBlockified(function(containersWithBlocks) {

				// Serialize child containers / blocks
				jQuery.each(containersWithBlocks, function(containerName, blockList) {
					var serializedBlocks = [];

					jQuery.each(blockList, function() {
						var block = this;
						serializedBlocks.push(block.serialize());
						// TODO for later -- if we have stuff between blocks - block.element.children().remove() might be helpful.
					});
					that.attr(containerName, JSON.stringify(serializedBlocks)); // TODO: use cross-browser version of JSON.stringify.
				});

				that._initialized = true;
				that.element.trigger('block-initialized');
			});
		},

		render: function(innerElement) {
			var that = this;
			if (!this._initialized) {
				// If block is not yet initialized, we will defer rendering internally.
				this.element.bind('block-initialized', function() {
					that.element.unbind('block-initialized');
					that.render(innerElement);
				});

				return;
			}
			innerElement.html(this.attr('template'));
			jQuery.each(this.containerDefinitions, function(name, config) {
				var serializedBlocks = JSON.parse(that.attr(name)); // TODO: use cross-browser version of JSON.parse.
				jQuery.each(serializedBlocks, function() {
					var serializedBlock = this;
					// TODO: copy/paste code from BlockPasteHandler
					var newBlock = jQuery('<' + serializedBlock.tag + '/>');
					newBlock.attr('class', serializedBlock.classes);

					jQuery.each(serializedBlock.attributes, function(k, v) {
						if (k === 'about') {
							newBlock.attr('about', v);
						} else {
							newBlock.attr('data-' + k, v);
						}
					});

					innerElement.find(config.selector).append(newBlock);
					BlockManager._blockify(newBlock);
				});
			});
			this._currentlyRendering = false;
		}
	});

	var TwoColumnBlock = AbstractTwoColumnBlock.extend({
		title: 'Two Column block',
		getSchema: function() {
			return {
				template: {
					type: 'string'
				}
			};
		}
	});

	return {
		CompanyBlock: CompanyBlock,
		EditableProductTeaserBlock: EditableProductTeaserBlock,
		ProductTeaserBlock: ProductTeaserBlock,
		VCardBlock: VCardBlock,
		CustomHandleBlock: CustomHandleBlock,
		TwoColumnBlock: TwoColumnBlock
	};
});