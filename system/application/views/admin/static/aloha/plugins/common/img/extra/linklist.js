/*!
* Aloha Editor
* Author & Copyright (c) 2011 Gentics Software GmbH
* aloha-sales@gentics.com
* Licensed unter the terms of http://www.aloha-editor.com/license.html
*/

define(['aloha/jquery', 'aloha/repository', 'i18n!aloha/nls/i18n'],
function(jQuery, repository, i18nCore) {
	"use strict";
	
	var 
		GENTICS = window.GENTICS;
	
	/**
	 * Create the repository
	 */
	var imgListRepository = new (repository.extend({
		_constructor: function(){
			this._super('imglist');
		},
	
		/**
		 * configure data as array with following format:
		 *
		 * [
		 * { name: 'Aloha Editor - The HTML5 Editor', url:'http://aloha-editor.com', type:'website' },
		 * { name: 'Aloha Logo', url:'http://www.aloha-editor.com/images/aloha-editor-logo.png', type:'image'  }
		 * ];
		 *
		 * @property
		 * @cfg
		 */
		urlset: [
			{ name: 'Aloha Editor - The HTML5 Editor', url: 'http://aloha-editor.com', type: 'website' },
			{ name: 'Aloha Editor - Wiki', url: 'http://www.aloha-editor.org/wiki/Main_Page', type: 'website' },
			{ name: 'Aloha Editor - GitHub', url: 'http://github.com/alohaeditor/Aloha-Editor', type: 'website' },
			{ name: 'Aloha Logo', url: 'http://www.aloha-editor.com/images/aloha-editor-logo.png', type: 'image'  }
		],
		
		/**
		 * Internal folder structur.
		 * @hide
		 */
		folder: [],
		
		/**
		 * initalize imgList, parse all imgs, build folder structure and add
		 * additional properties to the items
		 */
		init: function() {
		
			// Prepare
			var me = this;
			
			// generate folder structure
		    for (var i = 0; i < this.urlset.length; i++) {
		
		    	var e = this.urlset[i];
		    	e.repositoryId = this.repositoryId;
		    	e.id = e.id ? e.id : e.url;
		    	var u = e.uri = this.parseUri(e.url);
		
		    	// add hostname as root folder
		    	var path = this.addFolder('', u.host);
		
		    	var pathparts = u.path.split('/');
		    	for (var j = 0; j < pathparts.length; j++) {
		    		if (
		    			pathparts[j] &&
		    			// It's a file because it has an extension.
		    			// Could improve this one :)
		    			pathparts[j].lastIndexOf('.') < 0
		    		) {
			    		path = this.addFolder(path, pathparts[j]);
		    		}
		    	}
		    	e.parentId = path;
		    	this.urlset[i] = new Aloha.RepositoryDocument(e);
		    }
		
		    // repository name
		    this.repositoryName = 'imglist';
		},
		
		addFolder: function (path, name) {
		
			var type = path ? 'folder' : 'hostname';
			var p = path ? path + '/' + name : name;
		
			if ( name && !this.folder[p] ) {
				this.folder[p] = new Aloha.RepositoryFolder({
						id: p,
						name: (name)?name:p,
						parentId: path,
						type: 'host',
						repositoryId: this.repositoryId
				});
			}
			return p;
		},
		
		/**
		 * Searches a repository for object items matching query if objectTypeFilter.
		 * If none found it returns null.
		 */
		query: function( p, callback ) {
			// Not supported; filter, orderBy, maxItems, skipcount, renditionFilter
			var r = new RegExp( p.queryString, 'i' );
			var d = this.urlset.filter( function( e, i, a ) {
				return (
					( !p.queryString || e.name.match( r ) || e.url.match( r ) ) &&
					( !p.objectTypeFilter || ( !p.objectTypeFilter.length ) || jQuery.inArray( e.type, p.objectTypeFilter ) > -1 ) &&
					true //( !p.inFolderId || p.inFolderId == e.parentId )
				);
			} );
			callback.call( this, d );
		},
		
		/**
		 * returns the folder structure as parsed at init.
		 */
		getChildren: function ( p, callback ) {
			var d = [],
			    e;
			
			for ( e in this.folder ) {
				var l = this.folder[ e ].parentId;
				if ( typeof this.folder[ e ] != 'function' && ( // extjs prevention
					this.folder[ e ].parentId == p.inFolderId || // all subfolders
					( !this.folder[ e ].parentId && p.inFolderId == this.repositoryId ) // the hostname
				) ) {
					d.push( this.folder[ e ] );
				}
			}

			callback.call( this, d );
		},
		
		//parseUri 1.2.2
		//(c) Steven Levithan <stevenlevithan.com>
		//MIT License
		//http://blog.stevenlevithan.com/archives/parseuri
		parseUri: function(str) {
			var	o = {
					strictMode: false,
					key: ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
					q:   {
						name:   "queryKey",
						parser: /(?:^|&)([^&=]*)=?([^&]*)/g
					},
					parser: {
						strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
						loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
					}
				},
				m   = o.parser[o.strictMode ? "strict" : "loose"].exec(str),
				uri = {},
				i   = 14;
		
			while (i--) uri[o.key[i]] = m[i] || "";
		
			uri[o.q.name] = {};
			uri[o.key[12]].replace(o.q.parser, function ($0, $1, $2) {
				if ($1) uri[o.q.name][$1] = $2;
			});
		
			return uri;
		},
		
		/**
		 * Get the repositoryItem with given id
		 * @param itemId {String} id of the repository item to fetch
		 * @param callback {function} callback function
		 * @return {GENTICS.Aloha.Repository.Object} item with given id
		 */
		getObjectById: function (itemId, callback) {
			var d = [];
		
			for (var i = 0; i < this.urlset.length; i++) {
				if (this.urlset[i].id == itemId) {
					d.push(this.urlset[i]);
				}
			}
		
			callback.call(this, d);
			return true;
		}

}))();

});
