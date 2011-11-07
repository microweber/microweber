/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

ListLoader = function(config){
	Ext.apply(this, config);
};

Ext.extend(ListLoader, Ext.util.Observable, {
	keyAttribute: 'id',
	keyField: 'parentId',
	
	load: function(node, callback){
		var key = this.keyField;
		var v = node.attributes[this.keyAttribute];
		var rs = this.store.queryBy(function(r){
			return r.data[key] === v;
		});
		node.beginUpdate();
        for (var i = 0, d = rs.items, len = d.length; i < len; i++) {
			var n = this.createNode(d[i]);
			if (n) {
				node.appendChild(n);
			}
		}
		node.endUpdate();
		if(typeof callback == "function"){
            callback(this, node);
        }
	},
	
	createNode : function(record){
		var d = record.data, n;
		if(d.isFolder){
			n = new Ext.tree.AsyncTreeNode({
				loader: this,
				id: record.id,
				text: d.listName,
				leaf: false,
				iconCls: 'icon-folder',
				editable: true,
				expanded: true,
				isFolder: true
			});
		}else{
			n = new Ext.tree.TreeNode({
				id: record.id,
				text: d.listName,
				leaf: true,
				iconCls: 'icon-list',
				editable: true
			});
		}
		return n;
	}
});