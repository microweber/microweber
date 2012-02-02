/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.grid.GridPanel
 * @extends Ext.Panel
 * This class represents the primary interface of a component based grid control.
 * <br><br>Usage:
 * <pre><code>var grid = new Ext.grid.GridPanel({
    store: new Ext.data.Store({
        reader: reader,
        data: xg.dummyData
    }),
    columns: [
        {id:'company', header: "Company", width: 200, sortable: true, dataIndex: 'company'},
        {header: "Price", width: 120, sortable: true, renderer: Ext.util.Format.usMoney, dataIndex: 'price'},
        {header: "Change", width: 120, sortable: true, dataIndex: 'change'},
        {header: "% Change", width: 120, sortable: true, dataIndex: 'pctChange'},
        {header: "Last Updated", width: 135, sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), dataIndex: 'lastChange'}
    ],
    viewConfig: {
        forceFit: true
    },
    sm: new Ext.grid.RowSelectionModel({singleSelect:true}),
    width:600,
    height:300,
    frame:true,
    title:'Framed with Checkbox Selection and Horizontal Scrolling',
    iconCls:'icon-grid'
});</code></pre>
 * <b>Notes:</b><ul>
 * <li>Although this class inherits many configuration options from base classes, some of them
 * (such as autoScroll, layout, items, etc) are not used by this class, and will have no effect.</li>
 * <li>A grid <b>requires</b> a width in which to scroll its columns, and a height in which to scroll its rows. The dimensions can either
 * be set through the {@link #height} and {@link #width} configuration options or automatically set by using the grid in a {@link Ext.Container Container}
 * who's {@link Ext.Container#layout layout} provides sizing of its child items.</li>
 * <li>To access the data in a Grid, it is necessary to use the data model encapsulated
 * by the {@link #store Store}. See the {@link #cellclick} event.</li>
 * </ul>
 * @constructor
 * @param {Object} config The config object
 */
Ext.grid.GridPanel = Ext.extend(Ext.Panel, {
    /**
     * @cfg {Ext.data.Store} store The {@link Ext.data.Store} the grid should use as its data source (required).
     */
    /**
     * @cfg {Object} cm Shorthand for {@link #colModel}.
     */
    /**
     * @cfg {Object} colModel The {@link Ext.grid.ColumnModel} to use when rendering the grid (required).
     */
    /**
     * @cfg {Object} sm Shorthand for {@link #selModel}.
     */
    /**
     * @cfg {Object} selModel Any subclass of AbstractSelectionModel that will provide the selection model for
     * the grid (defaults to {@link Ext.grid.RowSelectionModel} if not specified).
     */
    /**
     * @cfg {Array} columns An array of columns to auto create a ColumnModel
     */
    /**
    * @cfg {Number} maxHeight Sets the maximum height of the grid - ignored if autoHeight is not on.
    */
    /**
     * @cfg {Boolean} disableSelection True to disable selections in the grid (defaults to false). - ignored if a SelectionModel is specified 
     */
    /**
     * @cfg {Boolean} enableColumnMove False to turn off column reordering via drag drop (defaults to true).
     */
    /**
     * @cfg {Boolean} enableColumnResize False to turn off column resizing for the whole grid (defaults to true).
     */
    /**
     * @cfg {Object} viewConfig A config object that will be applied to the grid's UI view.  Any of
     * the config options available for {@link Ext.grid.GridView} can be specified here.
     */
    /**
     * @cfg {Boolean} hideHeaders True to hide the grid's header (defaults to false).
     */

    /**
     * Configures the text in the drag proxy (defaults to "{0} selected row(s)").
     * {0} is replaced with the number of selected rows.
     * @type String
     */
    ddText : "{0} selected row{1}",
    /**
     * @cfg {Number} minColumnWidth The minimum width a column can be resized to. Defaults to 25.
     */
    minColumnWidth : 25,
    /**
     * @cfg {Boolean} trackMouseOver True to highlight rows when the mouse is over. Default is true.
     */
    trackMouseOver : true,
    /**
     * @cfg {Boolean} <p>enableDragDrop True to enable dragging of the selected rows of the GridPanel.</p>
     * <p>Setting this to <b><tt>true</tt></b> causes this GridPanel's {@link #getView GridView} to create an instance of 
     * {@link Ext.grid.GridDragZone}. This is available <b>(only after the Grid has been rendered)</b> as the
     * GridView's {@link Ext.grid.GridView#dragZone dragZone} property.</p>
     * <p>A cooperating {@link Ext.dd.DropZone DropZone} must be created who's implementations of
     * {@link Ext.dd.DropZone#onNodeEnter onNodeEnter}, {@link Ext.dd.DropZone#onNodeOver onNodeOver},
     * {@link Ext.dd.DropZone#onNodeOut onNodeOut} and {@link Ext.dd.DropZone#onNodeDrop onNodeDrop}</p> are able
     * to process the {@link Ext.grid.GridDragZone#getDragData data} which is provided.
     */
    enableDragDrop : false,
    /**
     * @cfg {Boolean} enableColumnMove True to enable drag and drop reorder of columns.
     */
    enableColumnMove : true,
    /**
     * @cfg {Boolean} enableColumnHide True to enable hiding of columns with the header context menu.
     */
    enableColumnHide : true,
    /**
     * @cfg {Boolean} enableHdMenu True to enable the drop down button for menu in the headers.
     */
    enableHdMenu : true,
    /**
     * @cfg {Boolean} stripeRows True to stripe the rows. Default is false.
     */
    stripeRows : false,
    /**
     * @cfg {String} autoExpandColumn The id of a column in this grid that should expand to fill unused space. This id can not be 0.
     */
    autoExpandColumn : false,
    /**
    * @cfg {Number} autoExpandMin The minimum width the autoExpandColumn can have (if enabled).
    * defaults to 50.
    */
    autoExpandMin : 50,
    /**
    * @cfg {Number} autoExpandMax The maximum width the autoExpandColumn can have (if enabled). Defaults to 1000.
    */
    autoExpandMax : 1000,
    /**
     * @cfg {Object} view The {@link Ext.grid.GridView} used by the grid. This can be set before a call to render().
     */
    view : null,
    /**
     * @cfg {Object} loadMask An {@link Ext.LoadMask} config or true to mask the grid while loading (defaults to false).
     */
    loadMask : false,

    /**
     * @cfg {Boolean} deferRowRender True to enable deferred row rendering. Default is true.
     */
    deferRowRender : true,

    // private
    rendered : false,
    // private
    viewReady: false,
    // private
    stateEvents: ["columnmove", "columnresize", "sortchange"],

    // private
    initComponent : function(){
        Ext.grid.GridPanel.superclass.initComponent.call(this);

        // override any provided value since it isn't valid
        // and is causing too many bug reports ;)
        this.autoScroll = false;
        this.autoWidth = false;

        if(Ext.isArray(this.columns)){
            this.colModel = new Ext.grid.ColumnModel(this.columns);
            delete this.columns;
        }

        // check and correct shorthanded configs
        if(this.ds){
            this.store = this.ds;
            delete this.ds;
        }
        if(this.cm){
            this.colModel = this.cm;
            delete this.cm;
        }
        if(this.sm){
            this.selModel = this.sm;
            delete this.sm;
        }
        this.store = Ext.StoreMgr.lookup(this.store);

        this.addEvents(
            // raw events
            /**
             * @event click
             * The raw click event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "click",
            /**
             * @event dblclick
             * The raw dblclick event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "dblclick",
            /**
             * @event contextmenu
             * The raw contextmenu event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "contextmenu",
            /**
             * @event mousedown
             * The raw mousedown event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "mousedown",
            /**
             * @event mouseup
             * The raw mouseup event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "mouseup",
            /**
             * @event mouseover
             * The raw mouseover event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "mouseover",
            /**
             * @event mouseout
             * The raw mouseout event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "mouseout",
            /**
             * @event keypress
             * The raw keypress event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "keypress",
            /**
             * @event keydown
             * The raw keydown event for the entire grid.
             * @param {Ext.EventObject} e
             */
            "keydown",

            // custom events
            /**
             * @event cellmousedown
             * Fires before a cell is clicked
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Number} columnIndex
             * @param {Ext.EventObject} e
             */
            "cellmousedown",
            /**
             * @event rowmousedown
             * Fires before a row is clicked
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Ext.EventObject} e
             */
            "rowmousedown",
            /**
             * @event headermousedown
             * Fires before a header is clicked
             * @param {Grid} this
             * @param {Number} columnIndex
             * @param {Ext.EventObject} e
             */
            "headermousedown",

            /**
             * @event cellclick
             * Fires when a cell is clicked.
             * The data for the cell is drawn from the {@link Ext.data.Record Record}
             * for this row. To access the data in the listener function use the
             * following technique:
             * <pre><code>
    function(grid, rowIndex, columnIndex, e) {
        var record = grid.getStore().getAt(rowIndex);  // Get the Record
        var fieldName = grid.getColumnModel().getDataIndex(columnIndex); // Get field name
        var data = record.get(fieldName);
    }
</code></pre>
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Number} columnIndex
             * @param {Ext.EventObject} e
             */
            "cellclick",
            /**
             * @event celldblclick
             * Fires when a cell is double clicked
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Number} columnIndex
             * @param {Ext.EventObject} e
             */
            "celldblclick",
            /**
             * @event rowclick
             * Fires when a row is clicked
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Ext.EventObject} e
             */
            "rowclick",
            /**
             * @event rowdblclick
             * Fires when a row is double clicked
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Ext.EventObject} e
             */
            "rowdblclick",
            /**
             * @event headerclick
             * Fires when a header is clicked
             * @param {Grid} this
             * @param {Number} columnIndex
             * @param {Ext.EventObject} e
             */
            "headerclick",
            /**
             * @event headerdblclick
             * Fires when a header cell is double clicked
             * @param {Grid} this
             * @param {Number} columnIndex
             * @param {Ext.EventObject} e
             */
            "headerdblclick",
            /**
             * @event rowcontextmenu
             * Fires when a row is right clicked
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Ext.EventObject} e
             */
            "rowcontextmenu",
            /**
             * @event cellcontextmenu
             * Fires when a cell is right clicked
             * @param {Grid} this
             * @param {Number} rowIndex
             * @param {Number} cellIndex
             * @param {Ext.EventObject} e
             */
            "cellcontextmenu",
            /**
             * @event headercontextmenu
             * Fires when a header is right clicked
             * @param {Grid} this
             * @param {Number} columnIndex
             * @param {Ext.EventObject} e
             */
            "headercontextmenu",
            /**
             * @event bodyscroll
             * Fires when the body element is scrolled
             * @param {Number} scrollLeft
             * @param {Number} scrollTop
             */
            "bodyscroll",
            /**
             * @event columnresize
             * Fires when the user resizes a column
             * @param {Number} columnIndex
             * @param {Number} newSize
             */
            "columnresize",
            /**
             * @event columnmove
             * Fires when the user moves a column
             * @param {Number} oldIndex
             * @param {Number} newIndex
             */
            "columnmove",
            /**
             * @event sortchange
             * Fires when the grid's store sort changes
             * @param {Grid} this
             * @param {Object} sortInfo An object with the keys field and direction
             */
            "sortchange"
        );
    },

    // private
    onRender : function(ct, position){
        Ext.grid.GridPanel.superclass.onRender.apply(this, arguments);

        var c = this.body;

        this.el.addClass('x-grid-panel');

        var view = this.getView();
        view.init(this);

        c.on("mousedown", this.onMouseDown, this);
        c.on("click", this.onClick, this);
        c.on("dblclick", this.onDblClick, this);
        c.on("contextmenu", this.onContextMenu, this);
        c.on("keydown", this.onKeyDown, this);

        this.relayEvents(c, ["mousedown","mouseup","mouseover","mouseout","keypress"]);

        this.getSelectionModel().init(this);
        this.view.render();
    },

    // private
    initEvents : function(){
        Ext.grid.GridPanel.superclass.initEvents.call(this);

        if(this.loadMask){
            this.loadMask = new Ext.LoadMask(this.bwrap,
                    Ext.apply({store:this.store}, this.loadMask));
        }
    },

    initStateEvents : function(){
        Ext.grid.GridPanel.superclass.initStateEvents.call(this);
        this.colModel.on('hiddenchange', this.saveState, this, {delay: 100});
    },

    applyState : function(state){
        var cm = this.colModel;
        var cs = state.columns;
        if(cs){
            for(var i = 0, len = cs.length; i < len; i++){
                var s = cs[i];
                var c = cm.getColumnById(s.id);
                if(c){
                    c.hidden = s.hidden;
                    c.width = s.width;
                    var oldIndex = cm.getIndexById(s.id);
                    if(oldIndex != i){
                        cm.moveColumn(oldIndex, i);
                    }
                }
            }
        }
        if(state.sort){
            this.store[this.store.remoteSort ? 'setDefaultSort' : 'sort'](state.sort.field, state.sort.direction);
        }
    },

    getState : function(){
        var o = {columns: []};
        for(var i = 0, c; c = this.colModel.config[i]; i++){
            o.columns[i] = {
                id: c.id,
                width: c.width
            };
            if(c.hidden){
                o.columns[i].hidden = true;
            }
        }
        var ss = this.store.getSortState();
        if(ss){
            o.sort = ss;
        }
        return o;
    },

    // private
    afterRender : function(){
        Ext.grid.GridPanel.superclass.afterRender.call(this);
        this.view.layout();
        if(this.deferRowRender){
            this.view.afterRender.defer(10, this.view);
        }else{
            this.view.afterRender();
        }
        this.viewReady = true;
    },

    /**
     * Reconfigures the grid to use a different Store and Column Model.
     * The View will be bound to the new objects and refreshed.
     * @param {Ext.data.Store} store The new {@link Ext.data.Store} object
     * @param {Ext.grid.ColumnModel} colModel The new {@link Ext.grid.ColumnModel} object
     */
    reconfigure : function(store, colModel){
        if(this.loadMask){
            this.loadMask.destroy();
            this.loadMask = new Ext.LoadMask(this.bwrap,
                    Ext.apply({store:store}, this.initialConfig.loadMask));
        }
        this.view.bind(store, colModel);
        this.store = store;
        this.colModel = colModel;
        if(this.rendered){
            this.view.refresh(true);
        }
    },

    // private
    onKeyDown : function(e){
        this.fireEvent("keydown", e);
    },

    // private
    onDestroy : function(){
        if(this.rendered){
            if(this.loadMask){
                this.loadMask.destroy();
            }
            var c = this.body;
            c.removeAllListeners();
            this.view.destroy();
            c.update("");
        }
        this.colModel.purgeListeners();
        Ext.grid.GridPanel.superclass.onDestroy.call(this);
    },

    // private
    processEvent : function(name, e){
        this.fireEvent(name, e);
        var t = e.getTarget();
        var v = this.view;
        var header = v.findHeaderIndex(t);
        if(header !== false){
            this.fireEvent("header" + name, this, header, e);
        }else{
            var row = v.findRowIndex(t);
            var cell = v.findCellIndex(t);
            if(row !== false){
                this.fireEvent("row" + name, this, row, e);
                if(cell !== false){
                    this.fireEvent("cell" + name, this, row, cell, e);
                }
            }
        }
    },

    // private
    onClick : function(e){
        this.processEvent("click", e);
    },

    // private
    onMouseDown : function(e){
        this.processEvent("mousedown", e);
    },

    // private
    onContextMenu : function(e, t){
        this.processEvent("contextmenu", e);
    },

    // private
    onDblClick : function(e){
        this.processEvent("dblclick", e);
    },

    // private
    walkCells : function(row, col, step, fn, scope){
        var cm = this.colModel, clen = cm.getColumnCount();
        var ds = this.store, rlen = ds.getCount(), first = true;
        if(step < 0){
            if(col < 0){
                row--;
                first = false;
            }
            while(row >= 0){
                if(!first){
                    col = clen-1;
                }
                first = false;
                while(col >= 0){
                    if(fn.call(scope || this, row, col, cm) === true){
                        return [row, col];
                    }
                    col--;
                }
                row--;
            }
        } else {
            if(col >= clen){
                row++;
                first = false;
            }
            while(row < rlen){
                if(!first){
                    col = 0;
                }
                first = false;
                while(col < clen){
                    if(fn.call(scope || this, row, col, cm) === true){
                        return [row, col];
                    }
                    col++;
                }
                row++;
            }
        }
        return null;
    },

    // private
    getSelections : function(){
        return this.selModel.getSelections();
    },

    // private
    onResize : function(){
        Ext.grid.GridPanel.superclass.onResize.apply(this, arguments);
        if(this.viewReady){
            this.view.layout();
        }
    },

    /**
     * Returns the grid's underlying element.
     * @return {Element} The element
     */
    getGridEl : function(){
        return this.body;
    },

    // private for compatibility, overridden by editor grid
    stopEditing : function(){},

    /**
     * Returns the grid's SelectionModel.
     * @return {SelectionModel} The selection model
     */
    getSelectionModel : function(){
        if(!this.selModel){
            this.selModel = new Ext.grid.RowSelectionModel(
                    this.disableSelection ? {selectRow: Ext.emptyFn} : null);
        }
        return this.selModel;
    },

    /**
     * Returns the grid's data store.
     * @return {DataSource} The store
     */
    getStore : function(){
        return this.store;
    },

    /**
     * Returns the grid's ColumnModel.
     * @return {ColumnModel} The column model
     */
    getColumnModel : function(){
        return this.colModel;
    },

    /**
     * Returns the grid's GridView object.
     * @return {GridView} The grid view
     */
    getView : function(){
        if(!this.view){
            this.view = new Ext.grid.GridView(this.viewConfig);
        }
        return this.view;
    },
    /**
     * Called to get grid's drag proxy text, by default returns this.ddText.
     * @return {String} The text
     */
    getDragDropText : function(){
        var count = this.selModel.getCount();
        return String.format(this.ddText, count, count == 1 ? '' : 's');
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
     * @cfg {String} autoScroll  @hide
     */
    /**
     * @cfg {String} bodyBorder  @hide
     */
    /**
     * @cfg {String} bodyStyle  @hide
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
Ext.reg('grid', Ext.grid.GridPanel);