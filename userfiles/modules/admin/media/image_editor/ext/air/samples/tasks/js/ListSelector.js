/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

// Implementation class for created the tree powered form field
ListSelector = Ext.extend(Ext.ux.TreeSelector, {
	maxHeight:200,
	listenForLoad: false,
    initComponent : function(){
		
		this.tree = new Ext.tree.TreePanel({
			animate:false,
			border:false,
			width: this.treeWidth || 180,
			autoScroll:true,
			useArrows:true,
			selModel: new Ext.tree.ActivationModel(),
			loader : new ListLoader({store: this.store})		
		});
		
		var root = new Ext.tree.AsyncTreeNode({
	        text: 'All Lists',
			id: 'root',
			leaf: false,
			iconCls: 'icon-folder',
			expanded: true,
			isFolder: true
	    });
	    this.tree.setRootNode(root);

        this.tree.on('render', function(){
            this.store.bindTree(this.tree);
        }, this);
		
        ListSelector.superclass.initComponent.call(this);
		
		// selecting folders is not allowed, so filter them
		this.tree.getSelectionModel().on('beforeselect', this.beforeSelection, this);
		
		// if being rendered before the store is loaded, reload when it is loaded
		if(this.listenForLoad) {
			this.store.on('load', function(){
				root.reload();
			}, this, {
				single: true
			});
		}
    },
	
	beforeSelection : function(tree, node){
		if(node && node.attributes.isFolder){
			node.toggle();
			return false;
		}
	}
});