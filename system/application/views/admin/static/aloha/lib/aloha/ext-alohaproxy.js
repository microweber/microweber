/*!
* This file is part of Aloha Editor Project http://aloha-editor.org
* Copyright © 2010-2011 Gentics Software GmbH, aloha@gentics.com
* Contributors http://aloha-editor.org/contribution.php 
* Licensed unter the terms of http://www.aloha-editor.org/license.html
*//*
* Aloha Editor is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.*
*
* Aloha Editor is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

define(
['aloha/jquery', 'aloha/ext', 'aloha/repositorymanager'],
function(jQuery, Ext, RepositoryManager) {
	"use strict";
	
	var
		$ = jQuery;

Ext.data.AlohaProxy = function( ) {
    // Must define a dummy api with "read" action to satisfy Ext.data.Api#prepare *before* calling super
    var api = {};
    api[Ext.data.Api.actions.read] = true;
    Ext.data.AlohaProxy.superclass.constructor.call(this, {
        api: api
    });
    this.params = {
			queryString: null,
			objectTypeFilter: null,
			filter: null,
			inFolderId: null,
			orderBy: null,
			maxItems: null,
			skipCount: null,
			renditionFilter: null,
			repositoryId: null
    };
};

Ext.extend(Ext.data.AlohaProxy, Ext.data.DataProxy, {
	doRequest : function(action, rs, params, reader, cb, scope, arg) {
		var p = this.params;
		jQuery.extend(p, params);
        try {
					RepositoryManager.query( p, function( items ) {
					var result = reader.readRecords( items );
						cb.call(scope, result, arg, true);
					});
        } catch (e) {
            this.fireEvent('loadexception', this, null, arg, e);
            this.fireEvent('exception', this, 'response', action, arg, null, e);
            return false;
        }
	},
	setObjectTypeFilter : function (otFilter) {
		this.params.objectTypeFilter = otFilter;
	},
	getObjectTypeFilter : function () {
		return this.params.objectTypeFilter;
	},
	setParams : function (p) {
		jQuery.extend(this.params, p);
	}
});

});
