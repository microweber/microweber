/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

// custom menu item to contain Ext trees
Ext.menu.TreeItem = Ext.extend(Ext.menu.Adapter, {
	constructor : function(config){
        Ext.menu.TreeItem.superclass.constructor.call(this, config.tree, config);
        this.tree = this.component;
        this.addEvents('selectionchange');

        this.tree.on("render", function(tree){
            tree.body.swallowEvent(['click','keydown', 'keypress', 'keyup']);
        });

        this.tree.getSelectionModel().on("selectionchange", this.onSelect, this);
    },

    onSelect : function(tree, sel){
        this.fireEvent("select", this, sel, tree);
    }
});


// custom menu containing a single tree
Ext.menu.TreeMenu = Ext.extend(Ext.menu.Menu, {
    cls:'x-tree-menu',
	keyNav: true,
	hideOnClick:false,
    plain: true,

    constructor : function(config){
        Ext.menu.TreeMenu.superclass.constructor.call(this, config);
        this.treeItem = new Ext.menu.TreeItem(config);
        this.add(this.treeItem);

        this.tree = this.treeItem.tree;
        this.tree.on('click', this.onNodeClick, this);
        this.relayEvents(this.treeItem, ["selectionchange"]);
    },

    // private
    beforeDestroy : function() {
        this.tree.destroy();
    },
	
	onNodeClick : function(node, e){
		if(!node.attributes.isFolder){
			this.treeItem.handleClick(e);
		}
	}
});


// custom form field for displaying a tree, similar to select or combo
Ext.ux.TreeSelector = Ext.extend(Ext.form.TriggerField, {
	initComponent : function(){
		Ext.ux.TreeSelector.superclass.initComponent.call(this);
		this.addEvents('selectionchange');

		this.tree.getSelectionModel().on('selectionchange', this.onSelection, this);
		this.tree.on({
			'expandnode': this.sync,
			'collapsenode' : this.sync,
			'append' : this.sync,
			'remove' : this.sync,
			'insert' : this.sync,
			scope: this
		});
		this.on('focus', this.onTriggerClick, this);
    },

	sync : function(){
		if(this.menu && this.menu.isVisible()){
			if(this.tree.body.getHeight() > this.maxHeight){
				this.tree.body.setHeight(this.maxHeight);
				this.restricted = true;
			}else if(this.restricted && this.tree.body.dom.firstChild.offsetHeight < this.maxHeight){
				this.tree.body.setHeight('');
				this.restricted = false;
			}
			this.menu.el.sync();
		}
	},

	onSelection : function(tree, node){
		if(!node){
			this.setRawValue('');
		}else{
			this.setRawValue(node.text);
		}
	},

	initEvents : function(){
		Ext.ux.TreeSelector.superclass.initEvents.call(this);
		this.el.on('mousedown', this.onTriggerClick, this);
		this.el.on("keydown", this.onKeyDown,  this);
	},

	onKeyDown : function(e){
		if(e.getKey() == e.DOWN){
			this.onTriggerClick();
		}
	},

    validateBlur : function(){
        return !this.menu || !this.menu.isVisible();
    },

    getValue : function(){
		var sm = this.tree.getSelectionModel();
		var s = sm.getSelectedNode();
        return s ? s.id : '';
    },

    setValue : function(id){
		var n = this.tree.getNodeById(id);
		if(n){
			n.select();
		}else{
			this.tree.getSelectionModel().clearSelections();
		}
    },

    // private
    onDestroy : function(){
        if(this.menu) {
            this.menu.destroy();
        }
        if(this.wrap){
            this.wrap.remove();
        }
        Ext.ux.TreeSelector.superclass.onDestroy.call(this);
    },

	// private
    menuListeners : {
        show : function(){ // retain focus styling
            this.onFocus();
        },
        hide : function(){
            this.focus.defer(10, this);
            var ml = this.menuListeners;
            this.menu.un("show", ml.show,  this);
            this.menu.un("hide", ml.hide,  this);
        }
    },

    onTriggerClick : function(){
		if(this.disabled){
            return;
        }
        this.menu.on(Ext.apply({}, this.menuListeners, {
            scope:this
        }));

        this.menu.show(this.el, "tl-bl?");
		this.sync();
		var sm = this.tree.getSelectionModel();
		var selected = sm.getSelectedNode();
		if(selected){
			selected.ensureVisible();
			sm.activate.defer(250, sm, [selected]);
		}
    },

    beforeBlur : function(){
        //
    },

	onRender : function(){
		Ext.ux.TreeSelector.superclass.onRender.apply(this, arguments);
		this.menu = new Ext.menu.TreeMenu(Ext.apply(this.menuConfig || {}, {tree: this.tree}));
		this.menu.render();

		this.tree.body.addClass('x-tree-selector');
	},

	readOnly: true
});

/*
 * Custom tree keyboard navigation that supports node navigation without selection
 */
Ext.tree.ActivationModel = Ext.extend(Ext.tree.DefaultSelectionModel, {
	select : function(node){
        return this.activate(Ext.tree.ActivationModel.superclass.select.call(this, node));
    },
    
    activate : function(node){
		if(!node){
			return;
		}
		if(this.activated != node) {
			if(this.activated){
				this.activated.ui.removeClass('x-tree-activated');
			}
			this.activated = node;
			node.ui.addClass('x-tree-activated');
		}
		node.ui.focus();
		return node;	
	},
	
	activatePrevious : function(){
        var s = this.activated;
        if(!s){
            return null;
        }
        var ps = s.previousSibling;
        if(ps){
            if(!ps.isExpanded() || ps.childNodes.length < 1){
                return this.activate(ps);
            } else{
                var lc = ps.lastChild;
                while(lc && lc.isExpanded() && lc.childNodes.length > 0){
                    lc = lc.lastChild;
                }
                return this.activate(lc);
            }
        } else if(s.parentNode && (this.tree.rootVisible || !s.parentNode.isRoot)){
            return this.activate(s.parentNode);
        }
        return null;
    },

    activateNext : function(){
        var s = this.activated;
        if(!s){
            return null;
        }
        if(s.firstChild && s.isExpanded()){
             return this.activate(s.firstChild);
         }else if(s.nextSibling){
             return this.activate(s.nextSibling);
         }else if(s.parentNode){
            var newS = null;
            s.parentNode.bubble(function(){
                if(this.nextSibling){
                    newS = this.getOwnerTree().selModel.activate(this.nextSibling);
                    return false;
                }
            });
            return newS;
         }
        return null;
    },

    onKeyDown : function(e){
        var s = this.activated;
        // undesirable, but required
        var sm = this;
        if(!s){
            return;
        }
        var k = e.getKey();
        switch(k){
             case e.DOWN:
                 e.stopEvent();
                 this.activateNext();
             break;
             case e.UP:
                 e.stopEvent();
                 this.activatePrevious();
             break;
             case e.RIGHT:
                 e.preventDefault();
                 if(s.hasChildNodes()){
                     if(!s.isExpanded()){
                         s.expand();
                     }else if(s.firstChild){
                         this.activate(s.firstChild, e);
                     }
                 }
             break;
             case e.LEFT:
                 e.preventDefault();
                 if(s.hasChildNodes() && s.isExpanded()){
                     s.collapse();
                 }else if(s.parentNode && (this.tree.rootVisible || s.parentNode != this.tree.getRootNode())){
                     this.activate(s.parentNode, e);
                 }
             break;
        };
    }
});