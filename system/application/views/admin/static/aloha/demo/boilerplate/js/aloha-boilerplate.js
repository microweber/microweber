Aloha.ready(function() {
	Aloha.require( ['aloha', 'aloha/jquery'], function( Aloha, $) {

		$.fn.alohaStage = function() {
			var switcher = this.find('ul.stage-switcher'),
				me = this,
				current,
				autoplay,
				showNext = function() {
					var nextTab;
					if (!me.currentTab) {
						nextTab = switcher.find('li').first();
					} else {
						nextTab = me.currentTab.next();
						if (nextTab.length == 0) {
							nextTab = switcher.find('li').first();
						}
					}
					nextTab.click();
					clearTimeout( autoplay );
					autoplay = setTimeout( showNext, 6000 );
				};
			switcher.children('li').each(function() {
				var $this = $(this),
					editable = $this.find('.area-content'), // make stage switcher available thru editable
					item = $this.find('.stage-item').detach();
				editable[0].tab = $this;
				item.hide();
				item.appendTo(switcher.parent());
				$this.click(function(event) {
					if (me.currentTab) me.currentTab.removeClass('active');
					me.currentTab = $this;
					$this.addClass('active');
					if (current && current != item ) {
						if (current) current.fadeOut(500);
					}
					item.fadeIn(500);
					current = item;
					clearTimeout( autoplay );
				});
				$this.mouseover(function() {
					$this.addClass('hover');
				});
				$this.mouseout(function() {
					$this.removeClass('hover');
				});
			});
			showNext();
			switcher.animate({right: -150}, {queue: false});
			switcher.mouseenter(function() {
				switcher.animate({right: 0}, {queue: false});
			});
			switcher.mouseleave(function() {
				switcher.animate({right: -150}, {queue: false});
			});
		

			Aloha.bind('aloha-editable-activated', function(e,a){
				if ( a.editable.obj[0].tab ) {
					a.editable.obj[0].tab.click();
				}
				clearTimeout( autoplay );
			});
			Aloha.bind('aloha-editable-deactivated', function(e,a){
				clearTimeout( autoplay );
				autoplay = setTimeout( showNext, 6000 );
			});
		};
		$('.stage-area').alohaStage();
		
		/**
		 * Aloha Source Viewer
		 * Provides a development tool that shows the source around the
		 * selection inside an editable
		 *
		 * @todo support for pretty print
		 */
		
		require( [ '../../test/unit/testutils' ], function ( TestUtils ) {
			
			Aloha.Sidebar.right.addPanel( {
				id       : 'aloha-devtool-source-viewer-panel',
				title    : 'Source Viewer\
							<span style="float:right; padding-right:10px;">\
								<input type="checkbox"\
									   id="aloha-devtool-source-viewer-ckbx"\
									   class="aloha-devtool-source-viewer-ckbx"\
									   style="vertical-align:middle;" />\
								<label for="aloha-devtool-source-viewer-ckbx"\
									   class="aloha-devtool-source-viewer-ckbx"\
									> Widen</label>\
							</span><span style="float:clear"></span>',
				expanded : true,
				activeOn : true,
				content  : '<div id="aloha-devtool-source-viewer-content"></div>',
				onInit   : function () {
					function getNodeIndex ( node ) {
						if ( !node ) {
							return -1;
						}
						
						var kids = node.parentNode.childNodes,
							l = kids.length,
							i = 0;
						
						for ( ; i < l; ++i ) {
							if ( kids[ i ] === node ) {
								return i;
							}
						}
						
						return -1;
					};
				
					var that = this;
					var jQuery = Aloha.jQuery;
					var sidebar = this.sidebar;
					var originalWidth = sidebar.width;
					var viewArea = this.content.find( '#aloha-devtool-source-viewer-content' );
					
					// A hack to make the sidebar wider
					this.title.find( '#aloha-devtool-source-viewer-ckbx' )
						.change( function () {
							sidebar.width = jQuery( this ).attr( 'checked' )
								? 600
								: originalWidth;
							
							sidebar.container.width( sidebar.width )
								.find( '.aloha-sidebar-panels' ).width( sidebar.width );
							sidebar.open( 0 ); //.close( 0 );
						} );
					
					this.title.find( '.aloha-devtool-source-viewer-ckbx' )
						.click( function ( ev ) {
							ev.stopPropagation();
						} );
					
					viewArea.css( {
						background    : '#fff',
						height        : 400,
						margin        : 0,
						padding       : 10,
						border        : 0,
						color		  : '#aaa',
						'line-height' : '1.5em',
						'font-size'   : '12px',
						'font-family' : 'monospace',
						overflow      : 'scroll'
						// 'white-space' : 'pre'
					} );
					
					// false to deactivate source viewer
					if ( true ) {
						Aloha.bind(
							'aloha-selection-changed',
							function ( event, range ) {
								var sNode = range.startContainer;
								var eNode = range.endContainer;
								
								var id = +( new Date );
								var sClass = 'aloha-selection-start-' + id;
								var eClass = 'aloha-selection-end-' + id;
								
								// Add marker classes onto the container nodes,
								// or their parentNodes if the containers are
								// textNodes
								jQuery( sNode.nodeType == 3
											? sNode.parentNode : sNode )
												.addClass( sClass );
								
								jQuery( eNode.nodeType == 3
											? eNode.parentNode : eNode )
												.addClass( eClass );
								
								// We determine which element's source to
								// show. If either the startContainer or the
								// endContainer is a text node, we will want
								// to show more of the source around our
								// selection so we will use the parent node of
								// the commonAncestorContainer
								var common;
								if ( ( sNode.nodeType == 3 ||
											eNode.nodeType == 3 ) &&
												!jQuery( range.commonAncestorContainer )
													.is( '.aloha-editable' ) ) {
									common = range.commonAncestorContainer.parentNode;
								} else {
									common = range.commonAncestorContainer;
								}
								
								var clonedContainer = jQuery( jQuery( common ).clone() );
								
								var clonedStartContainer = clonedContainer.is( '.' + sClass )
										? clonedContainer
										: clonedContainer.find( '.' + sClass );
								
								var clonedEndContainer = clonedContainer.is( '.' + eClass )
										? clonedContainer
										: clonedContainer.find( '.' + eClass );
								
								// Now that we have identified all our
								// containers, we can remove markers anywhere
								// we have placed them
								jQuery( '.' + sClass ).removeClass( sClass );
								jQuery( '.' + eClass ).removeClass( eClass );
								clonedStartContainer.removeClass( sClass );
								clonedEndContainer.removeClass( eClass );
								
								var startNode;
								if ( sNode.nodeType == 3 &&
										clonedStartContainer[ 0 ].childNodes.length  ) {
									startNode = clonedStartContainer[ 0 ]
													.childNodes[ getNodeIndex( sNode ) ];
								} else {
									startNode = clonedStartContainer[ 0 ];
								}
								
								var endNode;
								if ( eNode.nodeType == 3 &&
										clonedEndContainer[ 0 ].childNodes.length ) {
									endNode = clonedEndContainer[ 0 ]
													.childNodes[ getNodeIndex( eNode ) ];
								} else {
									endNode = clonedEndContainer[ 0 ];
								}
								
								var fakeRange = {
									startContainer : startNode,
									endContainer   : endNode,
									startOffset    : range.startOffset,
									endOffset      : range.endOffset
								};
								
								try {
									TestUtils.addBrackets( fakeRange );
								} catch ( ex ) {
									viewArea.html( '[' + ex + ']' );
									return;
								}
								
								var source =
									Aloha.jQuery('<div>')
										 .text( clonedContainer.html() )
										 .html()
										 .replace( /\t/g, '  ' )
										 .replace( /([\[\{])/,
											'<span class="aloha-devtool-source-viewer-marker" style="background:#70a5e2; color:#fff">$1' )
										 .replace( /([\]\}])/, '$1</span>' )
										 .replace( /([\[\]\{\}])/g,
											'<b style="background:#0c53a4; color:#fff;">$1</b>' );
								
								viewArea.html( source );
								
								window.viewArea = viewArea;
								
								var marker = viewArea.find( '.aloha-devtool-source-viewer-marker' );
								
								if ( marker.length ) {
									viewArea
										.scrollTop( 0 )
										.scrollTop( Math.max(
											0, ( marker.offset().top -
											viewArea.offset().top ) - 30
										) );
								}
							}
						);
					}
				}
			} );
			
		} );
		
	});
});
