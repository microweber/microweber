/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.tree.TreePanel
 * <p>The TreePanel provides tree-structured UI representation of tree-structured data.</p>
 * <p>{@link Ext.tree.TreeNode TreeNode}s added to the TreePanel may each contain metadata
 * used by your application in their {@link Ext.tree.TreeNode#attributes attributes} property.</p>
 * <p><b>A TreePanel must have a {@link #root} node before it is rendered.</b> This may either be
 * specified using the {@link #root} config option, or using the {@link #setRootNode} method.
 * @extends Ext.Panel

 * @cfg {Ext.tree.TreeNode} root The root node for the tree.
 * @cfg {Boolean} rootVisible false to hide the root node (defaults to true)
 * @cfg {Boolean} lines false to disable tree lines (defaults to true)
 * @cfg {Boolean} enableDD true to enable drag and drop
 * @cfg {Boolean} enableDrag true to enable just drag
 * @cfg {Boolean} enableDrop true to enable just drop
 * @cfg {Object} dragConfig Custom config to pass to the {@link Ext.tree.TreeDragZone} instance
 * @cfg {Object} dropConfig Custom config to pass to the {@link Ext.tree.TreeDropZone} instance
 * @cfg {String} ddGroup The DD group this TreePanel belongs to
 * @cfg {String} ddAppendOnly True if the tree should only allow append drops (use for trees which are sorted)
 * @cfg {Boolean} ddScroll true to enable body scrolling
 * @cfg {Boolean} containerScroll true to register this container with ScrollManager
 * @cfg {Boolean} hlDrop false to disable node highlight on drop (defaults to the value of Ext.enableFx)
 * @cfg {String} hlColor The color of the node highlight (defaults to C3DAF9)
 * @cfg {Boolean} animate true to enable animated expand/collapse (defaults to the value of Ext.enableFx)
 * @cfg {Boolean} singleExpand true if only 1 node per branch may be expanded
 * @cfg {Boolean} selModel A tree selection model to use with this TreePanel (defaults to a {@link Ext.tree.DefaultSelectionModel})
 * @cfg {Boolean} trackMouseOver False to disable mouse over highlighting 
 * @cfg {Ext.tree.TreeLoader} loader A {@link Ext.tree.TreeLoader} for use with this TreePanel
 * @cfg {String} pathSeparator The token used to separate sub-paths in path strings (defaults to '/')
 * @cfg {Boolean} useArrows True to use Vista-style arrows in the tree (defaults to false)
 *
 * @constructor
 * @param {Object} config
 */
Ext.tree.TreePanel = Ext.extend(Ext.Panel, {
    rootVisible : true,
    animate: Ext.enableFx,
    lines : true,
    enableDD : false,
    hlDrop : Ext.enableFx,
    pathSeparator: "/",

    initComponent : function(){
        Ext.tree.TreePanel.superclass.initComponent.call(this);

        if(!this.eventModel){
            this.eventModel = new Ext.tree.TreeEventModel(this);
        }
        
        // initialize the loader
        var l = this.loader;
        if(!l){
            l = new Ext.tree.TreeLoader({
                dataUrl: this.dataUrl
            });
        }else if(typeof l == 'object' && !l.load){
            l = new Ext.tree.TreeLoader(l);
        }
        this.loader = l;
        
        this.nodeHash = {};

        /**
        * The root node of this tree.
        * @type Ext.tree.TreeNode
        * @property root
        */
        if(this.root){
           this.setRootNode(this.root);
        }

        this.addEvents(

            /**
            * @event append
            * Fires when a new child node is appended to a node in this tree.
            * @param {Tree} tree The owner tree
            * @param {Node} parent The parent node
            * @param {Node} node The newly appended node
            * @param {Number} index The index of the newly appended node
            */
           "append",
           /**
            * @event remove
            * Fires when a child node is removed from a node in this tree.
            * @param {Tree} tree The owner tree
            * @param {Node} parent The parent node
            * @param {Node} node The child node removed
            */
           "remove",
           /**
            * @event movenode
            * Fires when a node is moved to a new location in the tree
            * @param {Tree} tree The owner tree
            * @param {Node} node The node moved
            * @param {Node} oldParent The old parent of this node
            * @param {Node} newParent The new parent of this node
            * @param {Number} index The index it was moved to
            */
           "movenode",
           /**
            * @event insert
            * Fires when a new child node is inserted in a node in this tree.
            * @param {Tree} tree The owner tree
            * @param {Node} parent The parent node
            * @param {Node} node The child node inserted
            * @param {Node} refNode The child node the node was inserted before
            */
           "insert",
           /**
            * @event beforeappend
            * Fires before a new child is appended to a node in this tree, return false to cancel the append.
            * @param {Tree} tree The owner tree
            * @param {Node} parent The parent node
            * @param {Node} node The child node to be appended
            */
           "beforeappend",
           /**
            * @event beforeremove
            * Fires before a child is removed from a node in this tree, return false to cancel the remove.
            * @param {Tree} tree The owner tree
            * @param {Node} parent The parent node
            * @param {Node} node The child node to be removed
            */
           "beforeremove",
           /**
            * @event beforemovenode
            * Fires before a node is moved to a new location in the tree. Return false to cancel the move.
            * @param {Tree} tree The owner tree
            * @param {Node} node The node being moved
            * @param {Node} oldParent The parent of the node
            * @param {Node} newParent The new parent the node is moving to
            * @param {Number} index The index it is being moved to
            */
           "beforemovenode",
           /**
            * @event beforeinsert
            * Fires before a new child is inserted in a node in this tree, return false to cancel the insert.
            * @param {Tree} tree The owner tree
            * @param {Node} parent The parent node
            * @param {Node} node The child node to be inserted
            * @param {Node} refNode The child node the node is being inserted before
            */
            "beforeinsert",

            /**
            * @event beforeload
            * Fires before a node is loaded, return false to cancel
            * @param {Node} node The node being loaded
            */
            "beforeload",
            /**
            * @event load
            * Fires when a node is loaded
            * @param {Node} node The node that was loaded
            */
            "load",
            /**
            * @event textchange
            * Fires when the text for a node is changed
            * @param {Node} node The node
            * @param {String} text The new text
            * @param {String} oldText The old text
            */
            "textchange",
            /**
            * @event beforeexpandnode
            * Fires before a node is expanded, return false to cancel.
            * @param {Node} node The node
            * @param {Boolean} deep
            * @param {Boolean} anim
            */
            "beforeexpandnode",
            /**
            * @event beforecollapsenode
            * Fires before a node is collapsed, return false to cancel.
            * @param {Node} node The node
            * @param {Boolean} deep
            * @param {Boolean} anim
            */
            "beforecollapsenode",
            /**
            * @event expandnode
            * Fires when a node is expanded
            * @param {Node} node The node
            */
            "expandnode",
            /**
            * @event disabledchange
            * Fires when the disabled status of a node changes
            * @param {Node} node The node
            * @param {Boolean} disabled
            */
            "disabledchange",
            /**
            * @event collapsenode
            * Fires when a node is collapsed
            * @param {Node} node The node
            */
            "collapsenode",
            /**
            * @event beforeclick
            * Fires before click processing on a node. Return false to cancel the default action.
            * @param {Node} node The node
            * @param {Ext.EventObject} e The event object
            */
            "beforeclick",
            /**
            * @event click
            * Fires when a node is clicked
            * @param {Node} node The node
            * @param {Ext.EventObject} e The event object
            */
            "click",
            /**
            * @event checkchange
            * Fires when a node with a checkbox's checked property changes
            * @param {Node} this This node
            * @param {Boolean} checked
            */
            "checkchange",
            /**
            * @event dblclick
            * Fires when a node is double clicked
            * @param {Node} node The node
            * @param {Ext.EventObject} e The event object
            */
            "dblclick",
            /**
            * @event contextmenu
            * Fires when a node is right clicked. To display a context menu in response to this
            * event, first create a Menu object (see {@link Ext.menu.Menu} for details), then add
            * a handler for this event:<code><pre>
new Ext.tree.TreePanel({
    title: 'My TreePanel',
    root: new Ext.tree.AsyncTreeNode({
        text: 'The Root',
        children: [
            { text: 'Child node 1', leaf: true },
            { text: 'Child node 2', leaf: true }
        ]
    }),
    contextMenu: new Ext.menu.Menu({
        items: [{
            id: 'delete-node',
            text: 'Delete Node'
        }],
        listeners: {
            itemclick: function(item) {
                switch (item.id) {
                    case 'delete-node':
                        var n = item.parentMenu.contextNode;
                        if (n.parentNode) {
                            n.remove();
                        }
                        break;
                }
            }
        }
    }),
    listeners: {
        contextmenu: function(node, e) {
//          Register the context node with the menu so that a Menu Item's handler function can access
//          it via its {@link Ext.menu.BaseItem#parentMenu parentMenu} property.
            node.select();
            var c = node.getOwnerTree().contextMenu;
            c.contextNode = node;
            c.showAt(e.getXY());
        }
    }
});
</pre></code>
            * @param {Node} node The node
            * @param {Ext.EventObject} e The event object
            */
            "contextmenu",
            /**
            * @event beforechildrenrendered
            * Fires right before the child nodes for a node are rendered
            * @param {Node} node The node
            */
            "beforechildrenrendered",
           /**
             * @event startdrag
             * Fires when a node starts being dragged
             * @param {Ext.tree.TreePanel} this
             * @param {Ext.tree.TreeNode} node
             * @param {event} e The raw browser event
             */
            "startdrag",
            /**
             * @event enddrag
             * Fires when a drag operation is complete
             * @param {Ext.tree.TreePanel} this
             * @param {Ext.tree.TreeNode} node
             * @param {event} e The raw browser event
             */
            "enddrag",
            /**
             * @event dragdrop
             * Fires when a dragged node is dropped on a valid DD target
             * @param {Ext.tree.TreePanel} this
             * @param {Ext.tree.TreeNode} node
             * @param {DD} dd The dd it was dropped on
             * @param {event} e The raw browser event
             */
            "dragdrop",
            /**
             * @event beforenodedrop
             * Fires when a DD object is dropped on a node in this tree for preprocessing. Return false to cancel the drop. The dropEvent
             * passed to handlers has the following properties:<br />
             * <ul style="padding:5px;padding-left:16px;">
             * <li>tree - The TreePanel</li>
             * <li>target - The node being targeted for the drop</li>
             * <li>data - The drag data from the drag source</li>
             * <li>point - The point of the drop - append, above or below</li>
             * <li>source - The drag source</li>
             * <li>rawEvent - Raw mouse event</li>
             * <li>dropNode - Drop node(s) provided by the source <b>OR</b> you can supply node(s)
             * to be inserted by setting them on this object.</li>
             * <li>cancel - Set this to true to cancel the drop.</li>
             * <li>dropStatus - If the default drop action is cancelled but the drop is valid, setting this to true
             * will prevent the animated "repair" from appearing.</li>
             * </ul>
             * @param {Object} dropEvent
             */
            "beforenodedrop",
            /**
             * @event nodedrop
             * Fires after a DD object is dropped on a node in this tree. The dropEvent
             * passed to handlers has the following properties:<br />
             * <ul style="padding:5px;padding-left:16px;">
             * <li>tree - The TreePanel</li>
             * <li>target - The node being targeted for the drop</li>
             * <li>data - The drag data from the drag source</li>
             * <li>point - The point of the drop - append, above or below</li>
             * <li>source - The drag source</li>
             * <li>rawEvent - Raw mouse event</li>
             * <li>dropNode - Dropped node(s).</li>
             * </ul>
             * @param {Object} dropEvent
             */
            "nodedrop",
             /**
             * @event nodedragover
             * Fires when a tree node is being targeted for a drag drop, return false to signal drop not allowed. The dragOverEvent
             * passed to handlers has the following properties:<br />
             * <ul style="padding:5px;padding-left:16px;">
             * <li>tree - The TreePanel</li>
             * <li>target - The node being targeted for the drop</li>
             * <li>data - The drag data from the drag source</li>
             * <li>point - The point of the drop - append, above or below</li>
             * <li>source - The drag source</li>
             * <li>rawEvent - Raw mouse event</li>
             * <li>dropNode - Drop node(s) provided by the source.</li>
             * <li>cancel - Set this to true to signal drop not allowed.</li>
             * </ul>
             * @param {Object} dragOverEvent
             */
            "nodedragover"
        );
        if(this.singleExpand){
            this.on("beforeexpandnode", this.restrictExpand, this);
        }
    },

    // private
    proxyNodeEvent : function(ename, a1, a2, a3, a4, a5, a6){
        if(ename == 'collapse' || ename == 'expand' || ename == 'beforecollapse' || ename == 'beforeexpand' || ename == 'move' || ename == 'beforemove'){
            ename = ename+'node';
        }
        // args inline for performance while bubbling events
        return this.fireEvent(ename, a1, a2, a3, a4, a5, a6);
    },


    /**
     * Returns this root node for this tree
     * @return {Node}
     */
    getRootNode : function(){
        return this.root;
    },

    /**
     * Sets the root node for this tree during initialization. 
     * @param {Node} node
     * @return {Node}
     */
    setRootNode : function(node){
        if(!node.render){ // attributes passed
            node = this.loader.createNode(node);
        }
        this.root = node;
        node.ownerTree = this;
        node.isRoot = true;
        this.registerNode(node);
        if(!this.rootVisible){
        	var uiP = node.attributes.uiProvider;
        	node.ui = uiP ? new uiP(node) : new Ext.tree.RootTreeNodeUI(node); 
        }
        return node;
    },

    /**
     * Gets a node in this tree by its id
     * @param {String} id
     * @return {Node}
     */
    getNodeById : function(id){
        return this.nodeHash[id];
    },

    // private
    registerNode : function(node){
        this.nodeHash[node.id] = node;
    },

    // private
    unregisterNode : function(node){
        delete this.nodeHash[node.id];
    },

    // private
    toString : function(){
        return "[Tree"+(this.id?" "+this.id:"")+"]";
    },

    // private
    restrictExpand : function(node){
        var p = node.parentNode;
        if(p){
            if(p.expandedChild && p.expandedChild.parentNode == p){
                p.expandedChild.collapse();
            }
            p.expandedChild = node;
        }
    },

    /**
     * Retrieve an array of checked nodes, or an array of a specific attribute of checked nodes (e.g. "id")
     * @param {String} attribute (optional) Defaults to null (return the actual nodes)
     * @param {TreeNode} startNode (optional) The node to start from, defaults to the root
     * @return {Array}
     */
    getChecked : function(a, startNode){
        startNode = startNode || this.root;
        var r = [];
        var f = function(){
            if(this.attributes.checked){
                r.push(!a ? this : (a == 'id' ? this.id : this.attributes[a]));
            }
        }
        startNode.cascade(f);
        return r;
    },

    /**
     * Returns the container element for this TreePanel.
     * @return {Element} The container element for this TreePanel.
     */
    getEl : function(){
        return this.el;
    },

    /**
     * Returns the default {@link Ext.tree.TreeLoader} for this TreePanel.
     * @return {Ext.tree.TreeLoader} The TreeLoader for this TreePanel.
     */
    getLoader : function(){
        return this.loader;
    },

    /**
     * Expand all nodes
     */
    expandAll : function(){
        this.root.expand(true);
    },

    /**
     * Collapse all nodes
     */
    collapseAll : function(){
        this.root.collapse(true);
    },

    /**
     * Returns the selection model used by this TreePanel.
     * @return {TreeSelectionModel} The selection model used by this TreePanel
     */
    getSelectionModel : function(){
        if(!this.selModel){
            this.selModel = new Ext.tree.DefaultSelectionModel();
        }
        return this.selModel;
    },

    /**
     * Expands a specified path in this TreePanel. A path can be retrieved from a node with {@link Ext.data.Node#getPath}
     * @param {String} path
     * @param {String} attr (optional) The attribute used in the path (see {@link Ext.data.Node#getPath} for more info)
     * @param {Function} callback (optional) The callback to call when the expand is complete. The callback will be called with
     * (bSuccess, oLastNode) where bSuccess is if the expand was successful and oLastNode is the last node that was expanded.
     */
    expandPath : function(path, attr, callback){
        attr = attr || "id";
        var keys = path.split(this.pathSeparator);
        var curNode = this.root;
        if(curNode.attributes[attr] != keys[1]){ // invalid root
            if(callback){
                callback(false, null);
            }
            return;
        }
        var index = 1;
        var f = function(){
            if(++index == keys.length){
                if(callback){
                    callback(true, curNode);
                }
                return;
            }
            var c = curNode.findChild(attr, keys[index]);
            if(!c){
                if(callback){
                    callback(false, curNode);
                }
                return;
            }
            curNode = c;
            c.expand(false, false, f);
        };
        curNode.expand(false, false, f);
    },

    /**
     * Selects the node in this tree at the specified path. A path can be retrieved from a node with {@link Ext.data.Node#getPath}
     * @param {String} path
     * @param {String} attr (optional) The attribute used in the path (see {@link Ext.data.Node#getPath} for more info)
     * @param {Function} callback (optional) The callback to call when the selection is complete. The callback will be called with
     * (bSuccess, oSelNode) where bSuccess is if the selection was successful and oSelNode is the selected node.
     */
    selectPath : function(path, attr, callback){
        attr = attr || "id";
        var keys = path.split(this.pathSeparator);
        var v = keys.pop();
        if(keys.length > 0){
            var f = function(success, node){
                if(success && node){
                    var n = node.findChild(attr, v);
                    if(n){
                        n.select();
                        if(callback){
                            callback(true, n);
                        }
                    }else if(callback){
                        callback(false, n);
                    }
                }else{
                    if(callback){
                        callback(false, n);
                    }
                }
            };
            this.expandPath(keys.join(this.pathSeparator), attr, f);
        }else{
            this.root.select();
            if(callback){
                callback(true, this.root);
            }
        }
    },

    /**
     * Returns the underlying Element for this tree
     * @return {Ext.Element} The Element
     */
    getTreeEl : function(){
        return this.body;
    },

    // private
    onRender : function(ct, position){
        Ext.tree.TreePanel.superclass.onRender.call(this, ct, position);
        this.el.addClass('x-tree');
        this.innerCt = this.body.createChild({tag:"ul",
               cls:"x-tree-root-ct " +
               (this.useArrows ? 'x-tree-arrows' : this.lines ? "x-tree-lines" : "x-tree-no-lines")});
    },

    // private
    initEvents : function(){
        Ext.tree.TreePanel.superclass.initEvents.call(this);

        if(this.containerScroll){
            Ext.dd.ScrollManager.register(this.body);
        }
        if((this.enableDD || this.enableDrop) && !this.dropZone){
           /**
            * The dropZone used by this tree if drop is enabled
            * @type Ext.tree.TreeDropZone
            */
             this.dropZone = new Ext.tree.TreeDropZone(this, this.dropConfig || {
               ddGroup: this.ddGroup || "TreeDD", appendOnly: this.ddAppendOnly === true
           });
        }
        if((this.enableDD || this.enableDrag) && !this.dragZone){
           /**
            * The dragZone used by this tree if drag is enabled
            * @type Ext.tree.TreeDragZone
            */
            this.dragZone = new Ext.tree.TreeDragZone(this, this.dragConfig || {
               ddGroup: this.ddGroup || "TreeDD",
               scroll: this.ddScroll
           });
        }
        this.getSelectionModel().init(this);
    },

    // private
    afterRender : function(){
        Ext.tree.TreePanel.superclass.afterRender.call(this);
        this.root.render();
        if(!this.rootVisible){
            this.root.renderChildren();
        }
    },

    onDestroy : function(){
        if(this.rendered){
            this.body.removeAllListeners();
            Ext.dd.ScrollManager.unregister(this.body);
            if(this.dropZone){
                this.dropZone.unreg();
            }
            if(this.dragZone){
               this.dragZone.unreg();
            }
        }
        this.root.destroy();
        this.nodeHash = null;
        Ext.tree.TreePanel.superclass.onDestroy.call(this);
    }
    
    /** 
     * @cfg {String/Number} activeItem 
     * @hide 
     */
    /** 
     * @cfg {Boolean} autoDestroy 
     * @hide 
     */
    /** 
     * @cfg {Object/String/Function} autoLoad 
     * @hide 
     */
    /** 
     * @cfg {Boolean} autoWidth 
     * @hide 
     */
    /** 
     * @cfg {Boolean/Number} bufferResize 
     * @hide 
     */
    /** 
     * @cfg {String} defaultType 
     * @hide 
     */
    /** 
     * @cfg {Object} defaults 
     * @hide 
     */
    /** 
     * @cfg {Boolean} hideBorders 
     * @hide 
     */
    /** 
     * @cfg {Mixed} items 
     * @hide 
     */
    /** 
     * @cfg {String} layout 
     * @hide 
     */
    /** 
     * @cfg {Object} layoutConfig 
     * @hide 
     */
    /** 
     * @cfg {Boolean} monitorResize 
     * @hide 
     */
    /** 
     * @property items 
     * @hide 
     */
    /** 
     * @method add 
     * @hide 
     */
    /** 
     * @method cascade 
     * @hide 
     */
    /** 
     * @method doLayout 
     * @hide 
     */
    /** 
     * @method find 
     * @hide 
     */
    /** 
     * @method findBy 
     * @hide 
     */
    /** 
     * @method findById 
     * @hide 
     */
    /** 
     * @method findByType 
     * @hide 
     */
    /** 
     * @method getComponent 
     * @hide 
     */
    /** 
     * @method getLayout 
     * @hide 
     */
    /** 
     * @method getUpdater 
     * @hide 
     */
    /** 
     * @method insert 
     * @hide 
     */
    /** 
     * @method load 
     * @hide 
     */
    /** 
     * @method remove 
     * @hide 
     */
    /** 
     * @event add 
     * @hide 
     */
    /** 
     * @event afterLayout 
     * @hide 
     */
    /** 
     * @event beforeadd 
     * @hide 
     */
    /** 
     * @event beforeremove 
     * @hide 
     */
    /** 
     * @event remove 
     * @hide 
     */



    /**
     * @cfg {String} allowDomMove  @hide
     */
    /**
     * @cfg {String} autoEl @hide
     */
    /**
     * @cfg {String} applyTo  @hide
     */
    /**
     * @cfg {String} contentEl  @hide
     */
    /**
     * @cfg {String} disabledClass  @hide
     */
    /**
     * @cfg {String} elements  @hide
     */
    /**
     * @cfg {String} html  @hide
     */
    /**
     * @property disabled
     * @hide
     */
    /**
     * @method applyToMarkup
     * @hide
     */
    /**
     * @method enable
     * @hide
     */
    /**
     * @method disable
     * @hide
     */
    /**
     * @method setDisabled
     * @hide
     */
});

Ext.tree.TreePanel.nodeTypes = {};

Ext.reg('treepanel', Ext.tree.TreePanel);