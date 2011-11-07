define( [

	// js
	'aloha',
	'aloha/jquery',
	'aloha/plugin',
	'aloha/pluginmanager',
	'aloha/floatingmenu',
	'link/link-plugin',
	'browser/browser-plugin', 
	// i18n
	'i18n!linkbrowser/nls/i18n',
	'i18n!aloha/nls/i18n'

], function( Aloha,
			 jQuery,
			 Plugin,
			 PluginManager,
			 FloatingMenu,
			 Links,
			 Browser,
			 i18n,
			 i18nCore ) {
	
	'use strict';
	
	var LinkBrowser = Browser.extend( {
		
		init: function ( config ) {
			this._super( config );
			
			var that = this;
			var repositoryButton = new Aloha.ui.Button( {
				iconClass : 'aloha-button-big aloha-button-tree',
				size      : 'large',
				onclick   : function () { that.show(); },
				tooltip   : i18n.t( 'button.addlink.tooltip' ),
				toggle    : false
			} );
			
			FloatingMenu.addButton(
				Links.name,
				repositoryButton,
				i18n.t( 'floatingmenu.tab.link' ),
				1
			);
			
			this.url = Aloha.getAlohaUrl() + '/../plugins/extra/linkbrowser/';
		},
		
		onSelect: function ( item ) {
			Links.hrefField.setItem( item );
			Links.hrefChange();
			this.close();
		},
		
		renderRowCols: function ( item ) {
			var row = {},
			    pluginUrl = this.url,
			    icon = '__page__',
			    idMatch = item.id.match( /(\d+)\./ );
			
			jQuery.each( this.columns, function ( colName, v ) {
				switch ( colName ) {
				case 'icon':
					row.icon = '<div class="aloha-browser-icon\
						aloha-browser-icon-' + icon + '"></div>';
					break;
				case 'translations':
					if ( !item.renditions ) {
						break;
					}
					
					var rends = item.renditions,
					    i = rends.length,
					    strBldr = [],
					    r;
					
					for ( ; i > 0; --i ) {
						r = rends[ i ];
						if ( r.kind == 'translation' ) {
							strBldr.push(
								//'<a href="' + repo_host + r.url + '">' +
								'<img src="'   + pluginUrl  + 'img/flags/' + r.language + '.png"\
									  alt="'   + r.language + '"\
									  title="' + r.filename + '" />'
								//'</a>'
							);
						}
					}
					
					row.translations = strBldr.join( '' );
					break;
				case 'language':
					row.language = item.language;
					break;
				default:
					row[ colName ] = item[ colName ] || '--';
				}
			} );
			
			return row;
		}
	
	} );

	var LinkBrowserPlugin = Plugin.create( 'linkbrowser', {
		dependencies: [ 'link', 'browser' ],

		browser: null,

		init: function () {
			var config = {
				repositoryManager : Aloha.RepositoryManager,
				
				repositoryFilter  : [],
				objectTypeFilter  : [ 'website', 'file', 'image', 'language' /*, '*' */ ],
				renditionFilter	  : [ '*' ],
				filter			  : [ 'language' ],
				
				columns : {
					icon         : { title: '',     width: 30,  sortable: false, resizable: false },
					name         : { title: 'Name', width: 320, sorttype: 'text' },
					language     : { title: '',     width: 30,  sorttype: 'text' },
					translations : { title: '',     width: 350, sorttype: 'text' }
				},
				
				rootPath : Aloha.getPluginUrl( 'browser' ) + '/'
			};
			
			this.browser = new LinkBrowser( config );
		}
	} );

	return LinkBrowserPlugin;
});
