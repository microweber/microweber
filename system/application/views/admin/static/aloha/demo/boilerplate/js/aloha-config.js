(function(window, undefined) {

	if (window.Aloha === undefined || window.Aloha === null) {
		var Aloha = window.Aloha = {};		
	}
	
	Aloha.settings = {
		logLevels: {'error': true, 'warn': true, 'info': true, 'debug': false, 'deprecated': true},
		errorhandling : false,
		ribbon: false,
		locale: 'en',
		floatingmenu: {
			width: 630
		},
		repositories: {
			linklist: {
				data: [
					{ name: 'Aloha Developers Wiki', url:'http://www.aloha-editor.com/wiki', type:'website', weight: 0.50 },
					{ name: 'Aloha Editor - The HTML5 Editor', url:'http://aloha-editor.com', type:'website', weight: 0.90  },
					{ name: 'Aloha Demo', url:'http://www.aloha-editor.com/demos.html', type:'website', weight: 0.75  },
					{ name: 'Aloha Wordpress Demo', url:'http://www.aloha-editor.com/demos/wordpress-demo/index.html', type:'website', weight: 0.75  },
					{ name: 'Aloha Logo', url:'http://www.aloha-editor.com/images/aloha-editor-logo.png', type:'image', weight: 0.10  }
				]
			}
		},
		plugins: {
			format: {
				// all elements with no specific configuration get this configuration
				config : [  'b', 'i', 'p', 'sub', 'sup', 'del', 'title', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'pre', 'removeFormat' ],
					editables : {
					// no formatting allowed for title
					'#top-text'	: [  ]
					}
			},
			list: {
				// all elements with no specific configuration get an UL, just for fun :)
				config : [ 'ul', 'ol' ],
					editables : {
					// Even if this is configured it is not set because OL and UL are not allowed in H1.
					'#top-text'	: [  ]
					}
			},
			abbr: {
				// all elements with no specific configuration get an UL, just for fun :)
				config : [ 'abbr' ],
					editables : {
					// Even if this is configured it is not set because OL and UL are not allowed in H1.
					'#top-text'	: [  ]
					}
			},
			link: {
				// all elements with no specific configuration may insert links
				config : [ 'a' ],
					editables : {
					// No links in the title.
					'#top-text'	: [  ]
					},
					// all links that match the targetregex will get set the target
				// e.g. ^(?!.*aloha-editor.com).* matches all href except aloha-editor.com
					targetregex : '^(?!.*aloha-editor.com).*',
					// this target is set when either targetregex matches or not set
					// e.g. _blank opens all links in new window
					target : '_blank',
					// the same for css class as for target
					cssclassregex : '^(?!.*aloha-editor.com).*',
					cssclass : 'aloha',
					// use all resources of type website for autosuggest
					objectTypeFilter: ['website'],
					// handle change of href
					onHrefChange: function( obj, href, item ) {
						var jQuery = Aloha.require('aloha/jquery');
						if ( item ) {
							jQuery(obj).attr('data-name', item.name);
						} else {
							jQuery(obj).removeAttr('data-name');
						}
					}
			},
			table: {
				// all elements with no specific configuration are not allowed to insert tables
				config : [ 'table' ],
					editables : {
					// Don't allow tables in top-text
					'#top-text'	: [ '' ]
					},
					summaryinsidebar : true,
					// [{name:'green', text:'Green', tooltip:'Green is cool', iconClass:'GENTICS_table GENTICS_button_green', cssClass:'green'}]
				tableConfig : [
									{name:'hor-minimalist-a'},
									{name:'box-table-a'},
									{name:'hor-zebra'},
					],
					columnConfig : [
							{name: 'table-style-bigbold',  iconClass: 'aloha-button-col-bigbold'},
							{name: 'table-style-redwhite', iconClass: 'aloha-button-col-redwhite'}
					],
					rowConfig : [
							{name: 'table-style-bigbold',  iconClass: 'aloha-button-row-bigbold'},
							{name: 'table-style-redwhite', iconClass: 'aloha-button-row-redwhite'}
					]

			},
			image: {
				config :{
				'fixedAspectRatio': false,
				'maxWidth': 600,
				'minWidth': 20,
				'maxHeight': 600,
				'minHeight': 20,
				'globalselector': '.global',
				'ui': {
					'oneTab': true
				}
			},
				'fixedAspectRatio': false,
				'maxWidth': 600,
				'minWidth': 20,
				'maxHeight': 600,
				'minHeight': 20,
				'globalselector': '.global',
				'ui': {
					'oneTab': true,
					'align': false,
					'margin': false
				}
			},
			cite: {
				referenceContainer: '#references'
			}
		}
	};
})(window);