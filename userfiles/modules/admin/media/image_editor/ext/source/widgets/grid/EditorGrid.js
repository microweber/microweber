/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.grid.EditorGridPanel
 * @extends Ext.grid.GridPanel
 * Class for creating and editable grid.
 
 * @constructor
 * @param {Object} config The config object
 */
Ext.grid.EditorGridPanel = Ext.extend(Ext.grid.GridPanel, {
    /**
     * @cfg {Number} clicksToEdit
     * The number of clicks on a cell required to display the cell's editor (defaults to 2)
     */
    clicksToEdit: 2,

    // private
    isEditor : true,
    // private
    detectEdit: false,

	/**
	 * @cfg {Boolean} autoEncode
	 * True to automatically HTML encode and decode values pre and post edit (defaults to false)
	 */
	autoEncode : false,

	/**
	 * @cfg {Boolean} trackMouseOver @hide
	 */
    // private
    trackMouseOver: false, // causes very odd FF errors
    
    // private
    initComponent : function(){
        Ext.grid.EditorGridPanel.superclass.initComponent.call(this);

        if(!this.selModel){
            /**
             * @cfg {Object} selModel Any subclass of AbstractSelectionModel that will provide the selection model for
             * the grid (defaults to {@link Ext.grid.CellSelectionModel} if not specified).
             */
            this.selModel = new Ext.grid.CellSelectionModel();
        }

        this.activeEditor = null;

	    this.addEvents(
            /**
             * @event beforeedit
             * Fires before cell editing is triggered. The edit event object has the following properties <br />
             * <ul style="padding:5px;padding-left:16px;">
             * <li>grid - This grid</li>
             * <li>record - The record being edited</li>
             * <li>field - The field name being edited</li>
             * <li>value - The value for the field being edited.</li>
             * <li>row - The grid row index</li>
             * <li>column - The grid column index</li>
             * <li>cancel - Set this to true to cancel the edit or return false from your handler.</li>
             * </ul>
             * @param {Object} e An edit event (see above for description)
             */
            "beforeedit",
            /**
             * @event afteredit
             * Fires after a cell is edited. <br />
             * <ul style="padding:5px;padding-left:16px;">
             * <li>grid - This grid</li>
             * <li>record - The record being edited</li>
             * <li>field - The field name being edited</li>
             * <li>value - The value being set</li>
             * <li>originalValue - The original value for the field, before the edit.</li>
             * <li>row - The grid row index</li>
             * <li>column - The grid column index</li>
             * </ul>
             * @param {Object} e An edit event (see above for description)
             */
            "afteredit",
            /**
             * @event validateedit
             * Fires after a cell is edited, but before the value is set in the record. Return false
             * to cancel the change. The edit event object has the following properties <br />
             * <ul style="padding:5px;padding-left:16px;">
             * <li>grid - This grid</li>
             * <li>record - The record being edited</li>
             * <li>field - The field name being edited</li>
             * <li>value - The value being set</li>
             * <li>originalValue - The original value for the field, before the edit.</li>
             * <li>row - The grid row index</li>
             * <li>column - The grid column index</li>
             * <li>cancel - Set this to true to cancel the edit or return false from your handler.</li>
             * </ul>
             * @param {Object} e An edit event (see above for description)
             */
            "validateedit"
        );
    },

    // private
    initEvents : function(){
        Ext.grid.EditorGridPanel.superclass.initEvents.call(this);
        
        this.on("bodyscroll", this.stopEditing, this, [true]);

        if(this.clicksToEdit == 1){
            this.on("cellclick", this.onCellDblClick, this);
        }else {
            if(this.clicksToEdit == 'auto' && this.view.mainBody){
                this.view.mainBody.on("mousedown", this.onAutoEditClick, this);
            }
            this.on("celldblclick", this.onCellDblClick, this);
        }
        this.getGridEl().addClass("xedit-grid");
    },

    // private
    onCellDblClick : function(g, row, col){
        this.startEditing(row, col);
    },

    // private
    onAutoEditClick : function(e, t){
        if(e.button !== 0){
            return;
        }
        var row = this.view.findRowIndex(t);
        var col = this.view.findCellIndex(t);
        if(row !== false && col !== false){
            this.stopEditing();
            if(this.selModel.getSelectedCell){ // cell sm
                var sc = this.selModel.getSelectedCell();
                if(sc && sc.cell[0] === row && sc.cell[1] === col){
                    this.startEditing(row, col);
                }
            }else{
                if(this.selModel.isSelected(row)){
                    this.startEditing(row, col);
                }
            }
        }
    },

    // private
    onEditComplete : function(ed, value, startValue){
        this.editing = false;
        this.activeEditor = null;
        ed.un("specialkey", this.selModel.onEditorKey, this.selModel);
		var r = ed.record;
        var field = this.colModel.getDataIndex(ed.col);
        value = this.postEditValue(value, startValue, r, field);
        if(String(value) !== String(startValue)){
            var e = {
                grid: this,
                record: r,
                field: field,
                originalValue: startValue,
                value: value,
                row: ed.row,
                column: ed.col,
                cancel:false
            };
            if(this.fireEvent("validateedit", e) !== false && !e.cancel){
                r.set(field, e.value);
                delete e.cancel;
                this.fireEvent("afteredit", e);
            }
        }
        this.view.focusCell(ed.row, ed.col);
    },

    /**
     * Starts editing the specified for the specified row/column
     * @param {Number} rowIndex
     * @param {Number} colIndex
     */
    startEditing : function(row, col){
        this.stopEditing();
        if(this.colModel.isCellEditable(col, row)){
            this.view.ensureVisible(row, col, true);
            var r = this.store.getAt(row);
            var field = this.colModel.getDataIndex(col);
            var e = {
                grid: this,
                record: r,
                field: field,
                value: r.data[field],
                row: row,
                column: col,
                cancel:false
            };
            if(this.fireEvent("beforeedit", e) !== false && !e.cancel){
                this.editing = true;
                var ed = this.colModel.getCellEditor(col, row);
                if(!ed.rendered){
                    ed.render(this.view.getEditorParent(ed));
                }
                (function(){ // complex but required for focus issues in safari, ie and opera
                    ed.row = row;
                    ed.col = col;
                    ed.record = r;
                    ed.on("complete", this.onEditComplete, this, {single: true});
                    ed.on("specialkey", this.selModel.onEditorKey, this.selModel);
                    this.activeEditor = ed;
                    var v = this.preEditValue(r, field);
                    ed.startEdit(this.view.getCell(row, col).firstChild, v);
                }).defer(50, this);
            }
        }
    },
    
    // private
	preEditValue : function(r, field){
        var value = r.data[field];
		return this.autoEncode && typeof value == 'string' ? Ext.util.Format.htmlDecode(value) : value;
	},
	
    // private
	postEditValue : function(value, originalValue, r, field){
		return this.autoEncode && typeof value == 'string' ? Ext.util.Format.htmlEncode(value) : value;
	},
	    
    /**
     * Stops any active editing
     * @param {Boolean} cancel (optional) True to cancel any changes
     */
    stopEditing : function(cancel){
        if(this.activeEditor){
            this.activeEditor[cancel === true ? 'cancelEdit' : 'completeEdit']();
        }
        this.activeEditor = null;
    },
    
    // private
    onDestroy: function() {
        if(this.rendered){
            var cols = this.colModel.config;
            for(var i = 0, len = cols.length; i < len; i++){
                var c = cols[i];
                Ext.destroy(c.editor);
            }
        }
        Ext.grid.EditorGridPanel.superclass.onDestroy.call(this);
    }
});
Ext.reg('editorgrid', Ext.grid.EditorGridPanel);