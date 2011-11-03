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
						overflow      : 'scroll',
						'white-space' : 'pre'
					} );
					
					// false to deactivate source viewer
					if ( true ) {
						Aloha.bind(
							'aloha-selection-changed',
							function ( event, range ) {
								var sNode = range.startContainer;
								var eNode = range.endContainer;
								
								// FIXME
								if ( !( sNode && eNode ) ) {
									return;
								}
								
								var id = +( new Date );
								var sClass = 'aloha-selection-start-' + id;
								var eClass = 'aloha-selection-end-' + id;
								
								jQuery( sNode.parentNode ).addClass( sClass );
								jQuery( eNode.parentNode ).addClass( eClass );
								
								var clonedContainer = jQuery( jQuery( range.commonAncestorContainer ).clone() );
								
								var clonedStartContainer = clonedContainer.is( '.' + sClass )
									? clonedContainer
									: clonedContainer.find( '.' + sClass );
								
								var clonedEndContainer = clonedContainer.is( '.' + eClass )
									? clonedContainer
									: clonedContainer.find( '.' + eClass );
								
								if ( clonedStartContainer.length == 0 ||
										clonedEndContainer.length == 0 ||
											!clonedStartContainer[0].childNodes ||
												!clonedEndContainer[0].childNodes ||
													clonedStartContainer[0].childNodes.length == 0||
														clonedEndContainer[0].childNodes.length == 0 ) {
									clonedStartContainer = clonedEndContainer = clonedContainer;
									//viewArea.html( '[eh!]' );
									//return;
								}
								
								var fakeRange;
								
								if ( clonedStartContainer &&
										clonedStartContainer.length &&
											clonedStartContainer[ 0 ] &&
												clonedStartContainer[ 0 ].childNodes ) {								
									fakeRange = {
										startContainer : clonedStartContainer[ 0 ].childNodes[ getNodeIndex( sNode ) ],
										endContainer   : clonedEndContainer[ 0 ].childNodes[ getNodeIndex( eNode ) ],
										startOffset    : range.startOffset,
										endOffset      : range.endOffset
									};
									
									// FIXME: index out of bounds
									try {
										TestUtils.addBrackets( fakeRange );
									} catch ( ex ) {
										viewArea.html( '[eh!]' + ex );
										return;
									}
									
									jQuery( sNode.parentNode ).removeClass( sClass );
									jQuery( eNode.parentNode ).removeClass( eClass );
									clonedStartContainer.removeClass( sClass );
									clonedEndContainer.removeClass( eClass );
									
									var source =
										Aloha.jQuery('<div>')
											 .text( clonedContainer.html() )
											 .html()
											 .replace( /\t/g, '  ' )
											 .replace( /([\[\]\{\}])/g,
												'<b style="color:#333">$1</b>' );
									
									viewArea.html( source );
								}
							}
						);
					}
				}
			} );
			
		} );
		
	});
});
