/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/*
 * Software License Agreement (BSD License)
 * Copyright (c) 2008, Nige "Animal" White
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *     * Redistributions of source code must retain the above copyright notice,
 *       this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright notice,
 *       this list of conditions and the following disclaimer in the documentation
 *       and/or other materials provided with the distribution.
 *     * Neither the name of the original author nor the names of its contributors
 *       may be used to endorse or promote products derived from this software
 *       without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */
/**
 * @class Ext.ux.DDView
 * <p>A DnD-enabled version of {@link Ext.DataView}. Drag/drop is implemented by adding
 * {@link Ext.data.Record}s to the target DDView. If copying is not being performed,
 * the original {@link Ext.data.Record} is removed from the source DDView.</p>
 * @constructor
 * Create a new DDView
 * @param {Object} config The configuration properties.
 */
Ext.ux.DDView = function(config) {
    if (!config.itemSelector) {
        var tpl = config.tpl;
        if (this.classRe.test(tpl)) {
            config.tpl = tpl.replace(this.classRe, 'class=$1x-combo-list-item $2$1');
        }
        else {
            config.tpl = tpl.replace(this.tagRe, '$1 class="x-combo-list-item" $2');
        }
        config.itemSelector = ".x-combo-list-item";
    }
    Ext.ux.DDView.superclass.constructor.call(this, Ext.apply(config, {
        border: false
    }));
};

Ext.extend(Ext.ux.DDView, Ext.DataView, {
    /**
     * @cfg {String/Array} dragGroup The ddgroup name(s) for the View's DragZone (defaults to undefined).
     */
    /**
     * @cfg {String/Array} dropGroup The ddgroup name(s) for the View's DropZone (defaults to undefined).
     */
    /**
     * @cfg {Boolean} copy Causes drag operations to copy nodes rather than move (defaults to false).
     */
    /**
     * @cfg {Boolean} allowCopy Causes ctrl/drag operations to copy nodes rather than move (defaults to false).
     */
    /**
     * @cfg {String} sortDir Sort direction for the view, 'ASC' or 'DESC' (defaults to 'ASC').
     */
    sortDir: 'ASC',

    // private
    isFormField: true,
    classRe: /class=(['"])(.*)\1/,
    tagRe: /(<\w*)(.*?>)/,
    reset: Ext.emptyFn,
    clearInvalid: Ext.form.Field.prototype.clearInvalid,

    // private
    afterRender: function() {
        Ext.ux.DDView.superclass.afterRender.call(this);
        if (this.dragGroup) {
            this.setDraggable(this.dragGroup.split(","));
        }
        if (this.dropGroup) {
            this.setDroppable(this.dropGroup.split(","));
        }
        if (this.deletable) {
            this.setDeletable();
        }
        this.isDirtyFlag = false;
        this.addEvents(
            "drop"
        );
    },

    // private
    validate: function() {
        return true;
    },

    // private
    destroy: function() {
        this.purgeListeners();
        this.getEl().removeAllListeners();
        this.getEl().remove();
        if (this.dragZone) {
            if (this.dragZone.destroy) {
                this.dragZone.destroy();
            }
        }
        if (this.dropZone) {
            if (this.dropZone.destroy) {
                this.dropZone.destroy();
            }
        }
    },

	/**
	 * Allows this class to be an Ext.form.Field so it can be found using {@link Ext.form.BasicForm#findField}.
	 */
    getName: function() {
        return this.name;
    },

	/**
	 * Loads the View from a JSON string representing the Records to put into the Store.
     * @param {String} value The JSON string
	 */
    setValue: function(v) {
        if (!this.store) {
            throw "DDView.setValue(). DDView must be constructed with a valid Store";
        }
        var data = {};
        data[this.store.reader.meta.root] = v ? [].concat(v) : [];
        this.store.proxy = new Ext.data.MemoryProxy(data);
        this.store.load();
    },

	/**
	 * Returns the view's data value as a list of ids.
     * @return {String} A parenthesised list of the ids of the Records in the View, e.g. (1,3,8).
	 */
    getValue: function() {
        var result = '(';
        this.store.each(function(rec) {
            result += rec.id + ',';
        });
        return result.substr(0, result.length - 1) + ')';
    },

    getIds: function() {
        var i = 0, result = new Array(this.store.getCount());
        this.store.each(function(rec) {
            result[i++] = rec.id;
        });
        return result;
    },

    /**
     * Returns true if the view's data has changed, else false.
     * @return {Boolean}
     */
    isDirty: function() {
        return this.isDirtyFlag;
    },

	/**
	 * Part of the Ext.dd.DropZone interface. If no target node is found, the
	 * whole Element becomes the target, and this causes the drop gesture to append.
	 */
    getTargetFromEvent : function(e) {
        var target = e.getTarget();
        while ((target !== null) && (target.parentNode != this.el.dom)) {
            target = target.parentNode;
        }
        if (!target) {
            target = this.el.dom.lastChild || this.el.dom;
        }
        return target;
    },

	/**
	 * Create the drag data which consists of an object which has the property "ddel" as
	 * the drag proxy element.
	 */
    getDragData : function(e) {
        var target = this.findItemFromChild(e.getTarget());
        if(target) {
            if (!this.isSelected(target)) {
                delete this.ignoreNextClick;
                this.onItemClick(target, this.indexOf(target), e);
                this.ignoreNextClick = true;
            }
            var dragData = {
                sourceView: this,
                viewNodes: [],
                records: [],
                copy: this.copy || (this.allowCopy && e.ctrlKey)
            };
            if (this.getSelectionCount() == 1) {
                var i = this.getSelectedIndexes()[0];
                var n = this.getNode(i);
                dragData.viewNodes.push(dragData.ddel = n);
                dragData.records.push(this.store.getAt(i));
                dragData.repairXY = Ext.fly(n).getXY();
            } else {
                dragData.ddel = document.createElement('div');
                dragData.ddel.className = 'multi-proxy';
                this.collectSelection(dragData);
            }
            return dragData;
        }
        return false;
    },

    // override the default repairXY.
    getRepairXY : function(e){
        return this.dragData.repairXY;
    },

	// private
    collectSelection: function(data) {
        data.repairXY = Ext.fly(this.getSelectedNodes()[0]).getXY();
        if (this.preserveSelectionOrder === true) {
            Ext.each(this.getSelectedIndexes(), function(i) {
                var n = this.getNode(i);
                var dragNode = n.cloneNode(true);
                dragNode.id = Ext.id();
                data.ddel.appendChild(dragNode);
                data.records.push(this.store.getAt(i));
                data.viewNodes.push(n);
            }, this);
        } else {
            var i = 0;
            this.store.each(function(rec){
                if (this.isSelected(i)) {
                    var n = this.getNode(i);
                    var dragNode = n.cloneNode(true);
                    dragNode.id = Ext.id();
                    data.ddel.appendChild(dragNode);
                    data.records.push(this.store.getAt(i));
                    data.viewNodes.push(n);
                }
                i++;
            }, this);
        }
    },

	/**
	 * Specify to which ddGroup items in this DDView may be dragged.
     * @param {String} ddGroup The DD group name to assign this view to.
	 */
    setDraggable: function(ddGroup) {
        if (ddGroup instanceof Array) {
            Ext.each(ddGroup, this.setDraggable, this);
            return;
        }
        if (this.dragZone) {
            this.dragZone.addToGroup(ddGroup);
        } else {
            this.dragZone = new Ext.dd.DragZone(this.getEl(), {
                containerScroll: true,
                ddGroup: ddGroup
            });
            // Draggability implies selection. DragZone's mousedown selects the element.
            if (!this.multiSelect) { this.singleSelect = true; }

            // Wire the DragZone's handlers up to methods in *this*
            this.dragZone.getDragData = this.getDragData.createDelegate(this);
            this.dragZone.getRepairXY = this.getRepairXY;
            this.dragZone.onEndDrag = this.onEndDrag;
        }
    },

	/**
	 * Specify from which ddGroup this DDView accepts drops.
     * @param {String} ddGroup The DD group name from which to accept drops.
	 */
    setDroppable: function(ddGroup) {
        if (ddGroup instanceof Array) {
            Ext.each(ddGroup, this.setDroppable, this);
            return;
        }
        if (this.dropZone) {
            this.dropZone.addToGroup(ddGroup);
        } else {
            this.dropZone = new Ext.dd.DropZone(this.getEl(), {
                owningView: this,
                containerScroll: true,
                ddGroup: ddGroup
            });

            // Wire the DropZone's handlers up to methods in *this*
            this.dropZone.getTargetFromEvent = this.getTargetFromEvent.createDelegate(this);
            this.dropZone.onNodeEnter = this.onNodeEnter.createDelegate(this);
            this.dropZone.onNodeOver = this.onNodeOver.createDelegate(this);
            this.dropZone.onNodeOut = this.onNodeOut.createDelegate(this);
            this.dropZone.onNodeDrop = this.onNodeDrop.createDelegate(this);
        }
    },

	// private
    getDropPoint : function(e, n, dd){
        if (n == this.el.dom) { return "above"; }
        var t = Ext.lib.Dom.getY(n), b = t + n.offsetHeight;
        var c = t + (b - t) / 2;
        var y = Ext.lib.Event.getPageY(e);
        if(y <= c) {
            return "above";
        }else{
            return "below";
        }
    },

    // private
    isValidDropPoint: function(pt, n, data) {
        if (!data.viewNodes || (data.viewNodes.length != 1)) {
            return true;
        }
        var d = data.viewNodes[0];
        if (d == n) {
            return false;
        }
        if ((pt == "below") && (n.nextSibling == d)) {
            return false;
        }
        if ((pt == "above") && (n.previousSibling == d)) {
            return false;
        }
        return true;
    },

    // private
    onNodeEnter : function(n, dd, e, data){
        if (this.highlightColor && (data.sourceView != this)) {
            this.el.highlight(this.highlightColor);
        }
        return false;
    },

    // private
    onNodeOver : function(n, dd, e, data){
        var dragElClass = this.dropNotAllowed;
        var pt = this.getDropPoint(e, n, dd);
        if (this.isValidDropPoint(pt, n, data)) {
            if (this.appendOnly || this.sortField) {
                return "x-tree-drop-ok-below";
            }

            // set the insert point style on the target node
            if (pt) {
                var targetElClass;
                if (pt == "above"){
                    dragElClass = n.previousSibling ? "x-tree-drop-ok-between" : "x-tree-drop-ok-above";
                    targetElClass = "x-view-drag-insert-above";
                } else {
                    dragElClass = n.nextSibling ? "x-tree-drop-ok-between" : "x-tree-drop-ok-below";
                    targetElClass = "x-view-drag-insert-below";
                }
                if (this.lastInsertClass != targetElClass){
                    Ext.fly(n).replaceClass(this.lastInsertClass, targetElClass);
                    this.lastInsertClass = targetElClass;
                }
            }
        }
        return dragElClass;
    },

    // private
    onNodeOut : function(n, dd, e, data){
        this.removeDropIndicators(n);
    },

    // private
    onNodeDrop : function(n, dd, e, data){
        if (this.fireEvent("drop", this, n, dd, e, data) === false) {
            return false;
        }
        var pt = this.getDropPoint(e, n, dd);
        var insertAt = (this.appendOnly || (n == this.el.dom)) ? this.store.getCount() : n.viewIndex;
        if (pt == "below") {
            insertAt++;
        }

        // Validate if dragging within a DDView
        if (data.sourceView == this) {
            // If the first element to be inserted below is the target node, remove it
            if (pt == "below") {
                if (data.viewNodes[0] == n) {
                    data.viewNodes.shift();
                }
            } else {  // If the last element to be inserted above is the target node, remove it
                if (data.viewNodes[data.viewNodes.length - 1] == n) {
                    data.viewNodes.pop();
                }
            }

            // Nothing to drop...
            if (!data.viewNodes.length) {
                return false;
            }

            // If we are moving DOWN, then because a store.remove() takes place first,
            // the insertAt must be decremented.
            if (insertAt > this.store.indexOf(data.records[0])) {
                insertAt--;
            }
        }

        // Dragging from a Tree. Use the Tree's recordFromNode function.
        if (data.node instanceof Ext.tree.TreeNode) {
            var r = data.node.getOwnerTree().recordFromNode(data.node);
            if (r) {
                data.records = [ r ];
            }
        }

        if (!data.records) {
            alert("Programming problem. Drag data contained no Records");
            return false;
        }

        for (var i = 0; i < data.records.length; i++) {
            var r = data.records[i];
            var dup = this.store.getById(r.id);
            if (dup && (dd != this.dragZone)) {
                if(!this.allowDup && !this.allowTrash){
                    Ext.fly(this.getNode(this.store.indexOf(dup))).frame("red", 1);
                    return true
                }
                var x=new Ext.data.Record();
                r.id=x.id;
                delete x;
            }
            if (data.copy) {
                this.store.insert(insertAt++, r.copy());
            } else {
                if (data.sourceView) {
                    data.sourceView.isDirtyFlag = true;
                    data.sourceView.store.remove(r);
                }
                if(!this.allowTrash)this.store.insert(insertAt++, r);
            }
            if(this.sortField){
                this.store.sort(this.sortField, this.sortDir);
            }
            this.isDirtyFlag = true;
        }
        this.dragZone.cachedTarget = null;
        return true;
    },

    // private
    onEndDrag: function(data, e) {
        var d = Ext.get(this.dragData.ddel);
        if (d && d.hasClass("multi-proxy")) {
            d.remove();
            //delete this.dragData.ddel;
        }
    },

    // private
    removeDropIndicators : function(n){
        if(n){
            Ext.fly(n).removeClass([
                "x-view-drag-insert-above",
                "x-view-drag-insert-left",
                "x-view-drag-insert-right",
                "x-view-drag-insert-below"]);
            this.lastInsertClass = "_noclass";
        }
    },

	/**
	 * Add a delete option to the DDView's context menu.
	 * @param {String} imageUrl The URL of the "delete" icon image.
	 */
    setDeletable: function(imageUrl) {
        if (!this.singleSelect && !this.multiSelect) {
            this.singleSelect = true;
        }
        var c = this.getContextMenu();
        this.contextMenu.on("itemclick", function(item) {
            switch (item.id) {
                case "delete":
                    this.remove(this.getSelectedIndexes());
                    break;
            }
        }, this);
        this.contextMenu.add({
            icon: imageUrl || AU.resolveUrl("/images/delete.gif"),
            id: "delete",
            text: AU.getMessage("deleteItem")
        });
    },

	/**
	 * Return the context menu for this DDView.
     * @return {Ext.menu.Menu} The context menu
	 */
    getContextMenu: function() {
        if (!this.contextMenu) {
            // Create the View's context menu
            this.contextMenu = new Ext.menu.Menu({
                id: this.id + "-contextmenu"
            });
            this.el.on("contextmenu", this.showContextMenu, this);
        }
        return this.contextMenu;
    },

    /**
     * Disables the view's context menu.
     */
    disableContextMenu: function() {
        if (this.contextMenu) {
            this.el.un("contextmenu", this.showContextMenu, this);
        }
    },

    // private
    showContextMenu: function(e, item) {
        item = this.findItemFromChild(e.getTarget());
        if (item) {
            e.stopEvent();
            this.select(this.getNode(item), this.multiSelect && e.ctrlKey, true);
            this.contextMenu.showAt(e.getXY());
        }
    },

	/**
	 * Remove {@link Ext.data.Record}s at the specified indices.
	 * @param {Array/Number} selectedIndices The index (or Array of indices) of Records to remove.
	 */
    remove: function(selectedIndices) {
        selectedIndices = [].concat(selectedIndices);
        for (var i = 0; i < selectedIndices.length; i++) {
            var rec = this.store.getAt(selectedIndices[i]);
            this.store.remove(rec);
        }
    },

	/**
	 * Double click fires the {@link #dblclick} event. Additionally, if this DDView is draggable, and there is only one other
	 * related DropZone that is in another DDView, it drops the selected node on that DDView.
	 */
    onDblClick : function(e){
        var item = this.findItemFromChild(e.getTarget());
        if(item){
            if (this.fireEvent("dblclick", this, this.indexOf(item), item, e) === false) {
                return false;
            }
            if (this.dragGroup) {
                var targets = Ext.dd.DragDropMgr.getRelated(this.dragZone, true);

                // Remove instances of this View's DropZone
                while (targets.indexOf(this.dropZone) !== -1) {
                    targets.remove(this.dropZone);
                }

                // If there's only one other DropZone, and it is owned by a DDView, then drop it in
                if ((targets.length == 1) && (targets[0].owningView)) {
                    this.dragZone.cachedTarget = null;
                    var el = Ext.get(targets[0].getEl());
                    var box = el.getBox(true);
                    targets[0].onNodeDrop(el.dom, {
                        target: el.dom,
                        xy: [box.x, box.y + box.height - 1]
                    }, null, this.getDragData(e));
                }
            }
        }
    },

    // private
    onItemClick : function(item, index, e){
        // The DragZone's mousedown->getDragData already handled selection
        if (this.ignoreNextClick) {
            delete this.ignoreNextClick;
            return;
        }

        if(this.fireEvent("beforeclick", this, index, item, e) === false){
            return false;
        }
        if(this.multiSelect || this.singleSelect){
            if(this.multiSelect && e.shiftKey && this.lastSelection){
                this.select(this.getNodes(this.indexOf(this.lastSelection), index), false);
            } else if (this.isSelected(item) && e.ctrlKey) {
                this.deselect(item);
            }else{
                this.deselect(item);
                this.select(item, this.multiSelect && e.ctrlKey);
                this.lastSelection = item;
            }
            e.preventDefault();
        }
        return true;
    }
});
