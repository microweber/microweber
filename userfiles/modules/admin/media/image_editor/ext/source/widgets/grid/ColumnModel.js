/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.grid.ColumnModel
 * @extends Ext.util.Observable
 * This is the default implementation of a ColumnModel used by the Grid. This class is initialized
 * with an Array of column config objects.
 * <br><br>
 * An individual column's config object defines the header string, the {@link Ext.data.Record}
 * field the column draws its data from, an optional rendering function to provide customized
 * data formatting, and the ability to apply a CSS class to all cells in a column through its
 * {@link #id} config option.<br>
 * <br>Usage:<br>
 <pre><code>
 var colModel = new Ext.grid.ColumnModel([
	{header: "Ticker", width: 60, sortable: true},
	{header: "Company Name", width: 150, sortable: true},
	{header: "Market Cap.", width: 100, sortable: true},
	{header: "$ Sales", width: 100, sortable: true, renderer: money},
	{header: "Employees", width: 100, sortable: true, resizable: false}
 ]);
 </code></pre>
 * <p>
 * The config options <b>defined by</b< this class are options which may appear in each
 * individual column definition.
 * @constructor
 * @param {Object} config An Array of column config objects. See this class's
 * config objects for details.
*/
Ext.grid.ColumnModel = function(config){
	/**
     * The width of columns which have no width specified (defaults to 100)
     * @type Number
     */
    this.defaultWidth = 100;

    /**
     * Default sortable of columns which have no sortable specified (defaults to false)
     * @type Boolean
     */
    this.defaultSortable = false;

    /**
     * The config passed into the constructor
     * @property {Array} config
     */
    if(config.columns){
        Ext.apply(this, config);
        this.setConfig(config.columns, true);
    }else{
        this.setConfig(config, true);
    }
    this.addEvents(
        /**
	     * @event widthchange
	     * Fires when the width of a column changes.
	     * @param {ColumnModel} this
	     * @param {Number} columnIndex The column index
	     * @param {Number} newWidth The new width
	     */
	    "widthchange",
        /**
	     * @event headerchange
	     * Fires when the text of a header changes.
	     * @param {ColumnModel} this
	     * @param {Number} columnIndex The column index
	     * @param {String} newText The new header text
	     */
	    "headerchange",
        /**
	     * @event hiddenchange
	     * Fires when a column is hidden or "unhidden".
	     * @param {ColumnModel} this
	     * @param {Number} columnIndex The column index
	     * @param {Boolean} hidden true if hidden, false otherwise
	     */
	    "hiddenchange",
	    /**
         * @event columnmoved
         * Fires when a column is moved.
         * @param {ColumnModel} this
         * @param {Number} oldIndex
         * @param {Number} newIndex
         */
        "columnmoved",
        // deprecated - to be removed
        "columnlockchange",
        /**
         * @event configchange
         * Fires when the configuration is changed
         * @param {ColumnModel} this
         */
        "configchange"
    );
    Ext.grid.ColumnModel.superclass.constructor.call(this);
};
Ext.extend(Ext.grid.ColumnModel, Ext.util.Observable, {
    /**
     * @cfg {String} id (optional) Defaults to the column's initial ordinal position.
     * A name which identifies this column. The id is used to create a CSS class name which
     * is applied to all table cells (including headers) in that column. The class name
     * takes the form of <pre>x-grid3-td-<b>id</b></pre>
     * <br><br>
     * Header cells will also recieve this class name, but will also have the class <pr>x-grid3-hd</pre>,
     * so to target header cells, use CSS selectors such as:<pre>.x-grid3-hd.x-grid3-td-<b>id</b></pre>
     * The {@link Ext.grid.GridPanel#autoExpandColumn} grid config option references the column
     * via this identifier.
     */
    /**
     * @cfg {String} header The header text to display in the Grid view.
     */
    /**
     * @cfg {String} dataIndex (optional) The name of the field in the grid's {@link Ext.data.Store}'s
     * {@link Ext.data.Record} definition from which to draw the column's value. If not
     * specified, the column's index is used as an index into the Record's data Array.
     */
    /**
     * @cfg {Number} width (optional) The initial width in pixels of the column.
     */
    /**
     * @cfg {Boolean} sortable (optional) True if sorting is to be allowed on this column.
     * Defaults to the value of the {@link #defaultSortable} property.
     * Whether local/remote sorting is used is specified in {@link Ext.data.Store#remoteSort}.
     */
    /**
     * @cfg {Boolean} fixed (optional) True if the column width cannot be changed.  Defaults to false.
     */
    /**
     * @cfg {Boolean} resizable (optional) False to disable column resizing. Defaults to true.
     */
    /**
     * @cfg {Boolean} menuDisabled (optional) True to disable the column menu. Defaults to false.
     */
    /**
     * @cfg {Boolean} hidden (optional) True to hide the column. Defaults to false.
     */
    /**
     * @cfg {String} tooltip (optional) A text string to use as the column header's tooltip.  If Quicktips are enabled, this
     * value will be used as the text of the quick tip, otherwise it will be set as the header's HTML title attribute.
     * Defaults to ''.
     */
    /**
     * @cfg {Function} renderer (optional) A function used to generate HTML markup for a cell
     * given the cell's data value. See {@link #setRenderer}. If not specified, the
     * default renderer uses the raw data value.
     */
    /**
     * @cfg {String} align (optional) Set the CSS text-align property of the column.  Defaults to undefined.
     */
    /**
     * @cfg {String} css (optional) Set custom CSS for all table cells in the column (excluding headers).  Defaults to undefined.
     */
    /**
     * @cfg {Boolean} hideable (optional) Specify as <tt>false</tt> to prevent the user from hiding this column
     * (defaults to true).  To disallow column hiding globally for all columns in the grid, use
     * {@link Ext.grid.GridPanel#enableColumnHide} instead.
     */
    /**
     * @cfg {Ext.form.Field} editor (optional) The {@link Ext.form.Field} to use when editing values in this column if
     * editing is supported by the grid.
     */

    /**
     * Returns the id of the column at the specified index.
     * @param {Number} index The column index
     * @return {String} the id
     */
    getColumnId : function(index){
        return this.config[index].id;
    },

    /**
     * Reconfigures this column model
     * @param {Array} config Array of Column configs
     */
    setConfig : function(config, initial){
        if(!initial){ // cleanup
            delete this.totalWidth;
            for(var i = 0, len = this.config.length; i < len; i++){
                var c = this.config[i];
                if(c.editor){
                    c.editor.destroy();
                }
            }
        }
        this.config = config;
        this.lookup = {};
        // if no id, create one
        for(var i = 0, len = config.length; i < len; i++){
            var c = config[i];
            if(typeof c.renderer == "string"){
                c.renderer = Ext.util.Format[c.renderer];
            }
            if(typeof c.id == "undefined"){
                c.id = i;
            }
            if(c.editor && c.editor.isFormField){
                c.editor = new Ext.grid.GridEditor(c.editor);
            }
            this.lookup[c.id] = c;
        }
        if(!initial){
            this.fireEvent('configchange', this);
        }
    },

    /**
     * Returns the column for a specified id.
     * @param {String} id The column id
     * @return {Object} the column
     */
    getColumnById : function(id){
        return this.lookup[id];
    },

    /**
     * Returns the index for a specified column id.
     * @param {String} id The column id
     * @return {Number} the index, or -1 if not found
     */
    getIndexById : function(id){
        for(var i = 0, len = this.config.length; i < len; i++){
            if(this.config[i].id == id){
                return i;
            }
        }
        return -1;
    },

    /**
     * Moves a column from one position to another.
     * @param {Number} oldIndex The index of the column to move.
     * @param {Number} newIndex The position at which to reinsert the coolumn.
     */
    moveColumn : function(oldIndex, newIndex){
        var c = this.config[oldIndex];
        this.config.splice(oldIndex, 1);
        this.config.splice(newIndex, 0, c);
        this.dataMap = null;
        this.fireEvent("columnmoved", this, oldIndex, newIndex);
    },

    // deprecated - to be removed
    isLocked : function(colIndex){
        return this.config[colIndex].locked === true;
    },

    // deprecated - to be removed
    setLocked : function(colIndex, value, suppressEvent){
        if(this.isLocked(colIndex) == value){
            return;
        }
        this.config[colIndex].locked = value;
        if(!suppressEvent){
            this.fireEvent("columnlockchange", this, colIndex, value);
        }
    },

    // deprecated - to be removed
    getTotalLockedWidth : function(){
        var totalWidth = 0;
        for(var i = 0; i < this.config.length; i++){
            if(this.isLocked(i) && !this.isHidden(i)){
                this.totalWidth += this.getColumnWidth(i);
            }
        }
        return totalWidth;
    },

    // deprecated - to be removed
    getLockedCount : function(){
        for(var i = 0, len = this.config.length; i < len; i++){
            if(!this.isLocked(i)){
                return i;
            }
        }
    },

    /**
     * Returns the number of columns.
     * @return {Number}
     */
    getColumnCount : function(visibleOnly){
        if(visibleOnly === true){
            var c = 0;
            for(var i = 0, len = this.config.length; i < len; i++){
                if(!this.isHidden(i)){
                    c++;
                }
            }
            return c;
        }
        return this.config.length;
    },

    /**
     * Returns the column configs that return true by the passed function that is called with (columnConfig, index)
     * @param {Function} fn
     * @param {Object} scope (optional)
     * @return {Array} result
     */
    getColumnsBy : function(fn, scope){
        var r = [];
        for(var i = 0, len = this.config.length; i < len; i++){
            var c = this.config[i];
            if(fn.call(scope||this, c, i) === true){
                r[r.length] = c;
            }
        }
        return r;
    },

    /**
     * Returns true if the specified column is sortable.
     * @param {Number} col The column index
     * @return {Boolean}
     */
    isSortable : function(col){
        if(typeof this.config[col].sortable == "undefined"){
            return this.defaultSortable;
        }
        return this.config[col].sortable;
    },

    /**
     * Returns true if the specified column menu is disabled.
     * @param {Number} col The column index
     * @return {Boolean}
     */
    isMenuDisabled : function(col){
        return !!this.config[col].menuDisabled;
    },

    /**
     * Returns the rendering (formatting) function defined for the column.
     * @param {Number} col The column index.
     * @return {Function} The function used to render the cell. See {@link #setRenderer}.
     */
    getRenderer : function(col){
        if(!this.config[col].renderer){
            return Ext.grid.ColumnModel.defaultRenderer;
        }
        return this.config[col].renderer;
    },

    /**
     * Sets the rendering (formatting) function for a column.  See {@link Ext.util.Format} for some
     * default formatting functions.
     * @param {Number} col The column index
     * @param {Function} fn The function to use to process the cell's raw data
     * to return HTML markup for the grid view. The render function is called with
     * the following parameters:<ul>
     * <li><b>value</b> : Object<p class="sub-desc">The data value for the cell.</p></li>
     * <li><b>metadata</b> : Object<p class="sub-desc">An object in which you may set the following attributes:<ul>
     * <li><b>css</b> : String<p class="sub-desc">A CSS class name to add to the cell's TD element.</p></li>
     * <li><b>attr</b> : String<p class="sub-desc">An HTML attribute definition string to apply to the data container element <i>within</i> the table cell
     * (e.g. 'style="color:red;"').</p></li></ul></p></li>
     * <li><b>record</b> : Ext.data.record<p class="sub-desc">The {@link Ext.data.Record} from which the data was extracted.</p></li>
     * <li><b>rowIndex</b> : Number<p class="sub-desc">Row index</p></li>
     * <li><b>colIndex</b> : Number<p class="sub-desc">Column index</p></li>
     * <li><b>store</b> : Ext.data.Store<p class="sub-desc">The {@link Ext.data.Store} object from which the Record was extracted.</p></li></ul>
     */
    setRenderer : function(col, fn){
        this.config[col].renderer = fn;
    },

    /**
     * Returns the width for the specified column.
     * @param {Number} col The column index
     * @return {Number}
     */
    getColumnWidth : function(col){
        return this.config[col].width || this.defaultWidth;
    },

    /**
     * Sets the width for a column.
     * @param {Number} col The column index
     * @param {Number} width The new width
     */
    setColumnWidth : function(col, width, suppressEvent){
        this.config[col].width = width;
        this.totalWidth = null;
        if(!suppressEvent){
             this.fireEvent("widthchange", this, col, width);
        }
    },

    /**
     * Returns the total width of all columns.
     * @param {Boolean} includeHidden True to include hidden column widths
     * @return {Number}
     */
    getTotalWidth : function(includeHidden){
        if(!this.totalWidth){
            this.totalWidth = 0;
            for(var i = 0, len = this.config.length; i < len; i++){
                if(includeHidden || !this.isHidden(i)){
                    this.totalWidth += this.getColumnWidth(i);
                }
            }
        }
        return this.totalWidth;
    },

    /**
     * Returns the header for the specified column.
     * @param {Number} col The column index
     * @return {String}
     */
    getColumnHeader : function(col){
        return this.config[col].header;
    },

    /**
     * Sets the header for a column.
     * @param {Number} col The column index
     * @param {String} header The new header
     */
    setColumnHeader : function(col, header){
        this.config[col].header = header;
        this.fireEvent("headerchange", this, col, header);
    },

    /**
     * Returns the tooltip for the specified column.
     * @param {Number} col The column index
     * @return {String}
     */
    getColumnTooltip : function(col){
            return this.config[col].tooltip;
    },
    /**
     * Sets the tooltip for a column.
     * @param {Number} col The column index
     * @param {String} tooltip The new tooltip
     */
    setColumnTooltip : function(col, tooltip){
            this.config[col].tooltip = tooltip;
    },

    /**
     * Returns the dataIndex for the specified column.
     * @param {Number} col The column index
     * @return {String} The column's dataIndex
     */
    getDataIndex : function(col){
        return this.config[col].dataIndex;
    },

    /**
     * Sets the dataIndex for a column.
     * @param {Number} col The column index
     * @param {String} dataIndex The new dataIndex
     */
    setDataIndex : function(col, dataIndex){
        this.config[col].dataIndex = dataIndex;
    },

    /**
     * Finds the index of the first matching column for the given dataIndex.
     * @param {String} col The dataIndex to find
     * @return {Number} The column index, or -1 if no match was found
     */
    findColumnIndex : function(dataIndex){
        var c = this.config;
        for(var i = 0, len = c.length; i < len; i++){
            if(c[i].dataIndex == dataIndex){
                return i;
            }
        }
        return -1;
    },

    /**
     * Returns true if the cell is editable.
     * @param {Number} colIndex The column index
     * @param {Number} rowIndex The row index
     * @return {Boolean}
     */
    isCellEditable : function(colIndex, rowIndex){
        return (this.config[colIndex].editable || (typeof this.config[colIndex].editable == "undefined" && this.config[colIndex].editor)) ? true : false;
    },

    /**
     * Returns the editor defined for the cell/column.
     * @param {Number} colIndex The column index
     * @param {Number} rowIndex The row index
     * @return {Object}
     */
    getCellEditor : function(colIndex, rowIndex){
        return this.config[colIndex].editor;
    },

    /**
     * Sets if a column is editable.
     * @param {Number} col The column index
     * @param {Boolean} editable True if the column is editable
     */
    setEditable : function(col, editable){
        this.config[col].editable = editable;
    },


    /**
     * Returns true if the column is hidden.
     * @param {Number} colIndex The column index
     * @return {Boolean}
     */
    isHidden : function(colIndex){
        return this.config[colIndex].hidden;
    },


    /**
     * Returns true if the column width cannot be changed
     */
    isFixed : function(colIndex){
        return this.config[colIndex].fixed;
    },

    /**
     * Returns true if the column can be resized
     * @return {Boolean}
     */
    isResizable : function(colIndex){
        return colIndex >= 0 && this.config[colIndex].resizable !== false && this.config[colIndex].fixed !== true;
    },
    /**
     * Sets if a column is hidden.
     * @param {Number} colIndex The column index
     * @param {Boolean} hidden True if the column is hidden
     */
    setHidden : function(colIndex, hidden){
        var c = this.config[colIndex];
        if(c.hidden !== hidden){
            c.hidden = hidden;
            this.totalWidth = null;
            this.fireEvent("hiddenchange", this, colIndex, hidden);
        }
    },

    /**
     * Sets the editor for a column.
     * @param {Number} col The column index
     * @param {Object} editor The editor object
     */
    setEditor : function(col, editor){
        this.config[col].editor = editor;
    }
});

// private
Ext.grid.ColumnModel.defaultRenderer = function(value){
	if(typeof value == "string" && value.length < 1){
	    return "&#160;";
	}
	return value;
};

// Alias for backwards compatibility
Ext.grid.DefaultColumnModel = Ext.grid.ColumnModel;
