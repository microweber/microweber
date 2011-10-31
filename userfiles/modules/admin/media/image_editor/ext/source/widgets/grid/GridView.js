/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.grid.GridView
 * @extends Ext.util.Observable
 * <p>This class encapsulates the user interface of an {@link Ext.grid.GridPanel}.
 * Methods of this class may be used to access user interface elements to enable
 * special display effects. Do not change the DOM structure of the user interface.</p>
 * <p>This class does not provide ways to manipulate the underlying data. The data
 * model of a Grid is held in an {@link Ext.data.Store}.</p>
 * @constructor
 * @param {Object} config
 */
Ext.grid.GridView = function(config){
    Ext.apply(this, config);
    // These events are only used internally by the grid components
    this.addEvents(
      /**
         * @event beforerowremoved
         * Internal UI Event. Fired before a row is removed.
         * @param {Ext.grid.GridView} view
         * @param {Number} rowIndex The index of the row to be removed.
         * @param {Ext.data.Record} record The Record to be removed
       */
      "beforerowremoved",
      /**
         * @event beforerowsinserted
         * Internal UI Event. Fired before rows are inserted.
         * @param {Ext.grid.GridView} view
         * @param {Number} firstRow The index of the first row to be inserted.
         * @param {Number} lastRow The index of the last row to be inserted.
       */
      "beforerowsinserted",
      /**
         * @event beforerefresh
         * Internal UI Event. Fired before the view is refreshed.
         * @param {Ext.grid.GridView} view
       */
      "beforerefresh",
      /**
         * @event rowremoved
         * Internal UI Event. Fired after a row is removed.
         * @param {Ext.grid.GridView} view
         * @param {Number} rowIndex The index of the row that was removed.
         * @param {Ext.data.Record} record The Record that was removed
       */
      "rowremoved",
      /**
         * @event rowsinserted
         * Internal UI Event. Fired after rows are inserted.
         * @param {Ext.grid.GridView} view
         * @param {Number} firstRow The index of the first inserted.
         * @param {Number} lastRow The index of the last row inserted.
       */
      "rowsinserted",
      /**
         * @event rowupdated
         * Internal UI Event. Fired after a row has been updated.
         * @param {Ext.grid.GridView} view
         * @param {Number} firstRow The index of the row updated.
         * @param {Ext.data.record} record The Record backing the row updated.
       */
      "rowupdated",
      /**
         * @event refresh
         * Internal UI Event. Fired after the GridView's body has been refreshed.
         * @param {Ext.grid.GridView} view
       */
      "refresh"
  );
    Ext.grid.GridView.superclass.constructor.call(this);
};

Ext.extend(Ext.grid.GridView, Ext.util.Observable, {
    /**
     * Override this function to apply custom CSS classes to rows during rendering.  You can also supply custom
     * parameters to the row template for the current row to customize how it is rendered using the <b>rowParams</b>
     * parameter.  This function should return the CSS class name (or empty string '' for none) that will be added
     * to the row's wrapping div.  To apply multiple class names, simply return them space-delimited within the string
     * (e.g., 'my-class another-class').
     * @param {Record} record The {@link Ext.data.Record} corresponding to the current row
     * @param {Number} index The row index
     * @param {Object} rowParams A config object that is passed to the row template during rendering that allows
     * customization of various aspects of a body row, if applicable.  Note that this object will only be applied if
     * {@link #enableRowBody} = true, otherwise it will be ignored. The object may contain any of these properties:<ul>
     * <li><code>body</code> : String <div class="sub-desc">An HTML fragment to be rendered as the cell's body content (defaults to '').</div></li>
     * <li><code>bodyStyle</code> : String <div class="sub-desc">A CSS style string that will be applied to the row's TR style attribute (defaults to '').</div></li>
     * <li><code>cols</code> : Number <div class="sub-desc">The column count to apply to the body row's TD colspan attribute (defaults to the current
     * column count of the grid).</div></li>
     * </ul>
     * @param {Store} store The {@link Ext.data.Store} this grid is bound to
     * @method getRowClass
     * @return {String} a CSS class name to add to the row.
     */
    /**
     * @cfg {Boolean} enableRowBody True to add a second TR element per row that can be used to provide a row body
     * that spans beneath the data row.  Use the {@link #getRowClass} method's rowParams config to customize the row body.
     */
    /**
     * @cfg {String} emptyText Default text to display in the grid body when no rows are available (defaults to '').
     */
    /**
     * @property dragZone
     * @type Ext.grid.GridDragZone
     * <p><b>This will only be present if the owning GridPanel was configured with {@link Ext.grid.GridPanel#enableDragDrop enableDragDrop}<b> <tt>true</tt></b>.</p>
     * <p><b>This will only be present after the owning GridPanel has been rendered</b>.</p>
     * <p>A customized implementation of a {@link Ext.dd.DragZone DragZone} which provides default implementations of the
     * template methods of DragZone to enable dragging of the selected rows of a GridPanel. See {@link Ext.grid.GridDragZone} for details.</p>
     */
    /**
     * @cfg {Boolean} deferEmptyText True to defer emptyText being applied until the store's first load
     */
    deferEmptyText: true,
    /**
     * The amount of space to reserve for the scrollbar (defaults to 19 pixels)
     * @type Number
     */
    scrollOffset: 19,
    /**
     * @cfg {Boolean} autoFill True to auto expand the columns to fit the grid <b>when the grid is created</b>.
     */
    autoFill: false,
    /**
     * @cfg {Boolean} forceFit True to auto expand/contract the size of the columns to fit the grid width and prevent horizontal scrolling.
     */
    forceFit: false,
    /**
     * The CSS classes applied to a header when it is sorted. (defaults to ["sort-asc", "sort-desc"])
     * @type Array
     */
    sortClasses : ["sort-asc", "sort-desc"],
    /**
     * The text displayed in the "Sort Ascending" menu item
     * @type String
     */
    sortAscText : "Sort Ascending",
    /**
     * The text displayed in the "Sort Descending" menu item
     * @type String
     */
    sortDescText : "Sort Descending",
    /**
     * The text displayed in the "Columns" menu item
     * @type String
     */
    columnsText : "Columns",

    // private
    borderWidth: 2,
    tdClass: 'x-grid3-cell',
    hdCls: 'x-grid3-hd',

    /**
     * @cfg {Number} cellSelectorDepth The number of levels to search for cells in event delegation (defaults to 4)
     */
    cellSelectorDepth: 4,
    /**
     * @cfg {Number} rowSelectorDepth The number of levels to search for rows in event delegation (defaults to 10)
     */
    rowSelectorDepth: 10,

    /**
     * @cfg {String} cellSelector The selector used to find cells internally
     */
    cellSelector: 'td.x-grid3-cell',
    /**
     * @cfg {String} rowSelector The selector used to find rows internally
     */
    rowSelector: 'div.x-grid3-row',

    /* -------------------------------- UI Specific ----------------------------- */

    // private
    initTemplates : function(){
        var ts = this.templates || {};
        if(!ts.master){
            ts.master = new Ext.Template(
                    '<div class="x-grid3" hidefocus="true">',
                        '<div class="x-grid3-viewport">',
                            '<div class="x-grid3-header"><div class="x-grid3-header-inner"><div class="x-grid3-header-offset">{header}</div></div><div class="x-clear"></div></div>',
                            '<div class="x-grid3-scroller"><div class="x-grid3-body">{body}</div><a href="#" class="x-grid3-focus" tabIndex="-1"></a></div>',
                        "</div>",
                        '<div class="x-grid3-resize-marker">&#160;</div>',
                        '<div class="x-grid3-resize-proxy">&#160;</div>',
                    "</div>"
                    );
        }

        if(!ts.header){
            ts.header = new Ext.Template(
                    '<table border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
                    '<thead><tr class="x-grid3-hd-row">{cells}</tr></thead>',
                    "</table>"
                    );
        }

        if(!ts.hcell){
            ts.hcell = new Ext.Template(
                    '<td class="x-grid3-hd x-grid3-cell x-grid3-td-{id}" style="{style}"><div {tooltip} {attr} class="x-grid3-hd-inner x-grid3-hd-{id}" unselectable="on" style="{istyle}">', this.grid.enableHdMenu ? '<a class="x-grid3-hd-btn" href="#"></a>' : '',
                    '{value}<img class="x-grid3-sort-icon" src="', Ext.BLANK_IMAGE_URL, '" />',
                    "</div></td>"
                    );
        }

        if(!ts.body){
            ts.body = new Ext.Template('{rows}');
        }

        if(!ts.row){
            ts.row = new Ext.Template(
                    '<div class="x-grid3-row {alt}" style="{tstyle}"><table class="x-grid3-row-table" border="0" cellspacing="0" cellpadding="0" style="{tstyle}">',
                    '<tbody><tr>{cells}</tr>',
                    (this.enableRowBody ? '<tr class="x-grid3-row-body-tr" style="{bodyStyle}"><td colspan="{cols}" class="x-grid3-body-cell" tabIndex="0" hidefocus="on"><div class="x-grid3-row-body">{body}</div></td></tr>' : ''),
                    '</tbody></table></div>'
                    );
        }

        if(!ts.cell){
            ts.cell = new Ext.Template(
                    '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
                    '<div class="x-grid3-cell-inner x-grid3-col-{id}" unselectable="on" {attr}>{value}</div>',
                    "</td>"
                    );
        }

        for(var k in ts){
            var t = ts[k];
            if(t && typeof t.compile == 'function' && !t.compiled){
                t.disableFormats = true;
                t.compile();
            }
        }

        this.templates = ts;
        this.colRe = new RegExp("x-grid3-td-([^\\s]+)", "");
    },

    // private
    fly : function(el){
        if(!this._flyweight){
            this._flyweight = new Ext.Element.Flyweight(document.body);
        }
        this._flyweight.dom = el;
        return this._flyweight;
    },

    // private
    getEditorParent : function(ed){
        return this.scroller.dom;
    },

    // private
    initElements : function(){
        var E = Ext.Element;

        var el = this.grid.getGridEl().dom.firstChild;
        var cs = el.childNodes;

        this.el = new E(el);

        this.mainWrap = new E(cs[0]);
        this.mainHd = new E(this.mainWrap.dom.firstChild);

        if(this.grid.hideHeaders){
            this.mainHd.setDisplayed(false);
        }

        this.innerHd = this.mainHd.dom.firstChild;
        this.scroller = new E(this.mainWrap.dom.childNodes[1]);
        if(this.forceFit){
            this.scroller.setStyle('overflow-x', 'hidden');
        }
        this.mainBody = new E(this.scroller.dom.firstChild);

        this.focusEl = new E(this.scroller.dom.childNodes[1]);
        this.focusEl.swallowEvent("click", true);

        this.resizeMarker = new E(cs[1]);
        this.resizeProxy = new E(cs[2]);
    },

    // private
    getRows : function(){
        return this.hasRows() ? this.mainBody.dom.childNodes : [];
    },

    // finder methods, used with delegation

    // private
    findCell : function(el){
        if(!el){
            return false;
        }
        return this.fly(el).findParent(this.cellSelector, this.cellSelectorDepth);
    },

    // private
    findCellIndex : function(el, requiredCls){
        var cell = this.findCell(el);
        if(cell && (!requiredCls || this.fly(cell).hasClass(requiredCls))){
            return this.getCellIndex(cell);
        }
        return false;
    },

    // private
    getCellIndex : function(el){
        if(el){
            var m = el.className.match(this.colRe);
            if(m && m[1]){
                return this.cm.getIndexById(m[1]);
            }
        }
        return false;
    },

    // private
    findHeaderCell : function(el){
        var cell = this.findCell(el);
        return cell && this.fly(cell).hasClass(this.hdCls) ? cell : null;
    },

    // private
    findHeaderIndex : function(el){
        return this.findCellIndex(el, this.hdCls);
    },

    // private
    findRow : function(el){
        if(!el){
            return false;
        }
        return this.fly(el).findParent(this.rowSelector, this.rowSelectorDepth);
    },

    // private
    findRowIndex : function(el){
        var r = this.findRow(el);
        return r ? r.rowIndex : false;
    },

    // getter methods for fetching elements dynamically in the grid

/**
 * Return the &lt;TR> HtmlElement which represents a Grid row for the specified index.
 * @param {Number} index The row index
 * @return {HtmlElement} The &lt;TR> element.
 */
    getRow : function(row){
        return this.getRows()[row];
    },

/**
 * Returns the grid's &lt;TD> HtmlElement at the specified coordinates.
 * @param {Number} row The row index in which to find the cell.
 * @param {Number} col The column index of the cell.
 * @return {HtmlElement} The &lt;TD> at the specified coordinates.
 */
    getCell : function(row, col){
        return this.getRow(row).getElementsByTagName('td')[col];
    },

/**
 * Return the &lt;TD> HtmlElement which represents the Grid's header cell for the specified column index.
 * @param {Number} index The column index
 * @return {HtmlElement} The &lt;TD> element.
 */
    getHeaderCell : function(index){
      return this.mainHd.dom.getElementsByTagName('td')[index];
    },

    // manipulating elements

    // private - use getRowClass to apply custom row classes
    addRowClass : function(row, cls){
        var r = this.getRow(row);
        if(r){
            this.fly(r).addClass(cls);
        }
    },

    // private
    removeRowClass : function(row, cls){
        var r = this.getRow(row);
        if(r){
            this.fly(r).removeClass(cls);
        }
    },

    // private
    removeRow : function(row){
        Ext.removeNode(this.getRow(row));
        this.focusRow(row);
    },
    
    // private
    removeRows : function(firstRow, lastRow){
        var bd = this.mainBody.dom;
        for(var rowIndex = firstRow; rowIndex <= lastRow; rowIndex++){
            Ext.removeNode(bd.childNodes[firstRow]);
        }
        this.focusRow(firstRow);
    },

    // scrolling stuff

    // private
    getScrollState : function(){
        var sb = this.scroller.dom;
        return {left: sb.scrollLeft, top: sb.scrollTop};
    },

    // private
    restoreScroll : function(state){
        var sb = this.scroller.dom;
        sb.scrollLeft = state.left;
        sb.scrollTop = state.top;
    },

    /**
     * Scrolls the grid to the top
     */
    scrollToTop : function(){
        this.scroller.dom.scrollTop = 0;
        this.scroller.dom.scrollLeft = 0;
    },

    // private
    syncScroll : function(){
      this.syncHeaderScroll();
      var mb = this.scroller.dom;
        this.grid.fireEvent("bodyscroll", mb.scrollLeft, mb.scrollTop);
    },

    // private
    syncHeaderScroll : function(){
        var mb = this.scroller.dom;
        this.innerHd.scrollLeft = mb.scrollLeft;
        this.innerHd.scrollLeft = mb.scrollLeft; // second time for IE (1/2 time first fails, other browsers ignore)
    },

    // private
    updateSortIcon : function(col, dir){
        var sc = this.sortClasses;
        var hds = this.mainHd.select('td').removeClass(sc);
        hds.item(col).addClass(sc[dir == "DESC" ? 1 : 0]);
    },

    // private
    updateAllColumnWidths : function(){
        var tw = this.getTotalWidth();
        var clen = this.cm.getColumnCount();
        var ws = [];
        for(var i = 0; i < clen; i++){
            ws[i] = this.getColumnWidth(i);
        }

        this.innerHd.firstChild.firstChild.style.width = tw;

        for(var i = 0; i < clen; i++){
            var hd = this.getHeaderCell(i);
            hd.style.width = ws[i];
        }

        var ns = this.getRows();
        for(var i = 0, len = ns.length; i < len; i++){
            ns[i].style.width = tw;
            ns[i].firstChild.style.width = tw;
            var row = ns[i].firstChild.rows[0];
            for(var j = 0; j < clen; j++){
                row.childNodes[j].style.width = ws[j];
            }
        }

        this.onAllColumnWidthsUpdated(ws, tw);
    },

    // private
    updateColumnWidth : function(col, width){
        var w = this.getColumnWidth(col);
        var tw = this.getTotalWidth();

        this.innerHd.firstChild.firstChild.style.width = tw;
        var hd = this.getHeaderCell(col);
        hd.style.width = w;

        var ns = this.getRows();
        for(var i = 0, len = ns.length; i < len; i++){
            ns[i].style.width = tw;
            ns[i].firstChild.style.width = tw;
            ns[i].firstChild.rows[0].childNodes[col].style.width = w;
        }

        this.onColumnWidthUpdated(col, w, tw);
    },

    // private
    updateColumnHidden : function(col, hidden){
        var tw = this.getTotalWidth();

        this.innerHd.firstChild.firstChild.style.width = tw;

        var display = hidden ? 'none' : '';

        var hd = this.getHeaderCell(col);
        hd.style.display = display;

        var ns = this.getRows();
        for(var i = 0, len = ns.length; i < len; i++){
            ns[i].style.width = tw;
            ns[i].firstChild.style.width = tw;
            ns[i].firstChild.rows[0].childNodes[col].style.display = display;
        }

        this.onColumnHiddenUpdated(col, hidden, tw);

        delete this.lastViewWidth; // force recalc
        this.layout();
    },

    // private
    doRender : function(cs, rs, ds, startRow, colCount, stripe){
        var ts = this.templates, ct = ts.cell, rt = ts.row, last = colCount-1;
        var tstyle = 'width:'+this.getTotalWidth()+';';
        // buffers
        var buf = [], cb, c, p = {}, rp = {tstyle: tstyle}, r;
        for(var j = 0, len = rs.length; j < len; j++){
            r = rs[j]; cb = [];
            var rowIndex = (j+startRow);
            for(var i = 0; i < colCount; i++){
                c = cs[i];
                p.id = c.id;
                p.css = i == 0 ? 'x-grid3-cell-first ' : (i == last ? 'x-grid3-cell-last ' : '');
                p.attr = p.cellAttr = "";
                p.value = c.renderer(r.data[c.name], p, r, rowIndex, i, ds);
                p.style = c.style;
                if(p.value == undefined || p.value === "") p.value = "&#160;";
                if(r.dirty && typeof r.modified[c.name] !== 'undefined'){
                    p.css += ' x-grid3-dirty-cell';
                }
                cb[cb.length] = ct.apply(p);
            }
            var alt = [];
            if(stripe && ((rowIndex+1) % 2 == 0)){
                alt[0] = "x-grid3-row-alt";
            }
            if(r.dirty){
                alt[1] = " x-grid3-dirty-row";
            }
            rp.cols = colCount;
            if(this.getRowClass){
                alt[2] = this.getRowClass(r, rowIndex, rp, ds);
            }
            rp.alt = alt.join(" ");
            rp.cells = cb.join("");
            buf[buf.length] =  rt.apply(rp);
        }
        return buf.join("");
    },

    // private
    processRows : function(startRow, skipStripe){
        if(this.ds.getCount() < 1){
            return;
        }
        skipStripe = skipStripe || !this.grid.stripeRows;
        startRow = startRow || 0;
        var rows = this.getRows();
        var cls = ' x-grid3-row-alt ';
        for(var i = startRow, len = rows.length; i < len; i++){
            var row = rows[i];
            row.rowIndex = i;
            if(!skipStripe){
                var isAlt = ((i+1) % 2 == 0);
                var hasAlt = (' '+row.className + ' ').indexOf(cls) != -1;
                if(isAlt == hasAlt){
                    continue;
                }
                if(isAlt){
                    row.className += " x-grid3-row-alt";
                }else{
                    row.className = row.className.replace("x-grid3-row-alt", "");
                }
            }
        }
    },

    afterRender: function(){
        this.mainBody.dom.innerHTML = this.renderRows();
        this.processRows(0, true);

        if(this.deferEmptyText !== true){
            this.applyEmptyText();
        }
    },

    // private
    renderUI : function(){

        var header = this.renderHeaders();
        var body = this.templates.body.apply({rows:''});


        var html = this.templates.master.apply({
            body: body,
            header: header
        });

        var g = this.grid;

        g.getGridEl().dom.innerHTML = html;

        this.initElements();

        // get mousedowns early
        Ext.fly(this.innerHd).on("click", this.handleHdDown, this);
        this.mainHd.on("mouseover", this.handleHdOver, this);
        this.mainHd.on("mouseout", this.handleHdOut, this);
        this.mainHd.on("mousemove", this.handleHdMove, this);

        this.scroller.on('scroll', this.syncScroll,  this);
        if(g.enableColumnResize !== false){
            this.splitone = new Ext.grid.GridView.SplitDragZone(g, this.mainHd.dom);
        }

        if(g.enableColumnMove){
            this.columnDrag = new Ext.grid.GridView.ColumnDragZone(g, this.innerHd);
            this.columnDrop = new Ext.grid.HeaderDropZone(g, this.mainHd.dom);
        }

        if(g.enableHdMenu !== false){
            if(g.enableColumnHide !== false){
                this.colMenu = new Ext.menu.Menu({id:g.id + "-hcols-menu"});
                this.colMenu.on("beforeshow", this.beforeColMenuShow, this);
                this.colMenu.on("itemclick", this.handleHdMenuClick, this);
            }
            this.hmenu = new Ext.menu.Menu({id: g.id + "-hctx"});
            this.hmenu.add(
                {id:"asc", text: this.sortAscText, cls: "xg-hmenu-sort-asc"},
                {id:"desc", text: this.sortDescText, cls: "xg-hmenu-sort-desc"}
            );
            if(g.enableColumnHide !== false){
                this.hmenu.add('-',
                    {id:"columns", text: this.columnsText, menu: this.colMenu, iconCls: 'x-cols-icon'}
                );
            }
            this.hmenu.on("itemclick", this.handleHdMenuClick, this);

            //g.on("headercontextmenu", this.handleHdCtx, this);
        }

        if(g.enableDragDrop || g.enableDrag){
            this.dragZone = new Ext.grid.GridDragZone(g, {
                ddGroup : g.ddGroup || 'GridDD'
            });
        }

        this.updateHeaderSortState();

    },

    // private
    layout : function(){
        if(!this.mainBody){
            return; // not rendered
        }
        var g = this.grid;
        var c = g.getGridEl();
        var csize = c.getSize(true);
        var vw = csize.width;

        if(vw < 20 || csize.height < 20){ // display: none?
            return;
        }

        if(g.autoHeight){
            this.scroller.dom.style.overflow = 'visible';
        }else{
            this.el.setSize(csize.width, csize.height);

            var hdHeight = this.mainHd.getHeight();
            var vh = csize.height - (hdHeight);

            this.scroller.setSize(vw, vh);
            if(this.innerHd){
                this.innerHd.style.width = (vw)+'px';
            }
        }
        if(this.forceFit){
            if(this.lastViewWidth != vw){
                this.fitColumns(false, false);
                this.lastViewWidth = vw;
            }
        }else {
            this.autoExpand();
            this.syncHeaderScroll();
        }
        this.onLayout(vw, vh);
    },

    // template functions for subclasses and plugins
    // these functions include precalculated values
    onLayout : function(vw, vh){
        // do nothing
    },

    onColumnWidthUpdated : function(col, w, tw){
        // template method
    },

    onAllColumnWidthsUpdated : function(ws, tw){
        // template method
    },

    onColumnHiddenUpdated : function(col, hidden, tw){
        // template method
    },

    updateColumnText : function(col, text){
        // template method
    },

    afterMove : function(colIndex){
        // template method
    },

    /* ----------------------------------- Core Specific -------------------------------------------*/
    // private
    init: function(grid){
        this.grid = grid;

        this.initTemplates();
        this.initData(grid.store, grid.colModel);
        this.initUI(grid);
    },

    // private
    getColumnId : function(index){
      return this.cm.getColumnId(index);
    },

    // private
    renderHeaders : function(){
        var cm = this.cm, ts = this.templates;
        var ct = ts.hcell;

        var cb = [], sb = [], p = {};

        for(var i = 0, len = cm.getColumnCount(); i < len; i++){
            p.id = cm.getColumnId(i);
            p.value = cm.getColumnHeader(i) || "";
            p.style = this.getColumnStyle(i, true);
            p.tooltip = this.getColumnTooltip(i);
            if(cm.config[i].align == 'right'){
                p.istyle = 'padding-right:16px';
            } else {
                delete p.istyle;
            }
            cb[cb.length] = ct.apply(p);
        }
        return ts.header.apply({cells: cb.join(""), tstyle:'width:'+this.getTotalWidth()+';'});
    },

    // private
    getColumnTooltip : function(i){
        var tt = this.cm.getColumnTooltip(i);
        if(tt){
            if(Ext.QuickTips.isEnabled()){
                return 'ext:qtip="'+tt+'"';
            }else{
                return 'title="'+tt+'"';
            }
        }
        return "";
    },

    // private
    beforeUpdate : function(){
        this.grid.stopEditing(true);
    },

    // private
    updateHeaders : function(){
        this.innerHd.firstChild.innerHTML = this.renderHeaders();
    },

    /**
     * Focuses the specified row.
     * @param {Number} row The row index
     */
    focusRow : function(row){
        this.focusCell(row, 0, false);
    },

    /**
     * Focuses the specified cell.
     * @param {Number} row The row index
     * @param {Number} col The column index
     */
    focusCell : function(row, col, hscroll){
        row = Math.min(row, Math.max(0, this.getRows().length-1));
        var xy = this.ensureVisible(row, col, hscroll);
        this.focusEl.setXY(xy||this.scroller.getXY());
        
        if(Ext.isGecko){
            this.focusEl.focus();
        }else{
            this.focusEl.focus.defer(1, this.focusEl);
        }
    },

    // private
    ensureVisible : function(row, col, hscroll){
        if(typeof row != "number"){
            row = row.rowIndex;
        }
        if(!this.ds){
            return;
        }
        if(row < 0 || row >= this.ds.getCount()){
            return;
        }
        col = (col !== undefined ? col : 0);

        var rowEl = this.getRow(row), cellEl;
        if(!(hscroll === false && col === 0)){
            while(this.cm.isHidden(col)){
                col++;
            }
            cellEl = this.getCell(row, col);
        }
        if(!rowEl){
            return;
        }

        var c = this.scroller.dom;

        var ctop = 0;
        var p = rowEl, stop = this.el.dom;
        while(p && p != stop){
            ctop += p.offsetTop;
            p = p.offsetParent;
        }
        ctop -= this.mainHd.dom.offsetHeight;

        var cbot = ctop + rowEl.offsetHeight;

        var ch = c.clientHeight;
        var stop = parseInt(c.scrollTop, 10);
        var sbot = stop + ch;

        if(ctop < stop){
          c.scrollTop = ctop;
        }else if(cbot > sbot){
            c.scrollTop = cbot-ch;
        }

        if(hscroll !== false){
            var cleft = parseInt(cellEl.offsetLeft, 10);
            var cright = cleft + cellEl.offsetWidth;

            var sleft = parseInt(c.scrollLeft, 10);
            var sright = sleft + c.clientWidth;
            if(cleft < sleft){
                c.scrollLeft = cleft;
            }else if(cright > sright){
                c.scrollLeft = cright-c.clientWidth;
            }
        }
        return cellEl ? Ext.fly(cellEl).getXY() : [c.scrollLeft+this.el.getX(), Ext.fly(rowEl).getY()];
    },

    // private
    insertRows : function(dm, firstRow, lastRow, isUpdate){
        if(!isUpdate && firstRow === 0 && lastRow >= dm.getCount()-1){
            this.refresh();
        }else{
            if(!isUpdate){
                this.fireEvent("beforerowsinserted", this, firstRow, lastRow);
            }
            var html = this.renderRows(firstRow, lastRow);
            var before = this.getRow(firstRow);
            if(before){
                Ext.DomHelper.insertHtml('beforeBegin', before, html);
            }else{
                Ext.DomHelper.insertHtml('beforeEnd', this.mainBody.dom, html);
            }
            if(!isUpdate){
                this.fireEvent("rowsinserted", this, firstRow, lastRow);
                this.processRows(firstRow);
            }
        }
        this.focusRow(firstRow);
    },

    // private
    deleteRows : function(dm, firstRow, lastRow){
        if(dm.getRowCount()<1){
            this.refresh();
        }else{
            this.fireEvent("beforerowsdeleted", this, firstRow, lastRow);

            this.removeRows(firstRow, lastRow);

            this.processRows(firstRow);
            this.fireEvent("rowsdeleted", this, firstRow, lastRow);
        }
    },

    // private
    getColumnStyle : function(col, isHeader){
        var style = !isHeader ? (this.cm.config[col].css || '') : '';
        style += 'width:'+this.getColumnWidth(col)+';';
        if(this.cm.isHidden(col)){
            style += 'display:none;';
        }
        var align = this.cm.config[col].align;
        if(align){
            style += 'text-align:'+align+';';
        }
        return style;
    },

    // private
    getColumnWidth : function(col){
        var w = this.cm.getColumnWidth(col);
        if(typeof w == 'number'){
            return (Ext.isBorderBox ? w : (w-this.borderWidth > 0 ? w-this.borderWidth:0)) + 'px';
        }
        return w;
    },

    // private
    getTotalWidth : function(){
        return this.cm.getTotalWidth()+'px';
    },

    // private
    fitColumns : function(preventRefresh, onlyExpand, omitColumn){
        var cm = this.cm, leftOver, dist, i;
        var tw = cm.getTotalWidth(false);
        var aw = this.grid.getGridEl().getWidth(true)-this.scrollOffset;

        if(aw < 20){ // not initialized, so don't screw up the default widths
            return;
        }
        var extra = aw - tw;

        if(extra === 0){
            return false;
        }

        var vc = cm.getColumnCount(true);
        var ac = vc-(typeof omitColumn == 'number' ? 1 : 0);
        if(ac === 0){
            ac = 1;
            omitColumn = undefined;
        }
        var colCount = cm.getColumnCount();
        var cols = [];
        var extraCol = 0;
        var width = 0;
        var w;
        for (i = 0; i < colCount; i++){
            if(!cm.isHidden(i) && !cm.isFixed(i) && i !== omitColumn){
                w = cm.getColumnWidth(i);
                cols.push(i);
                extraCol = i;
                cols.push(w);
                width += w;
            }
        }
        var frac = (aw - cm.getTotalWidth())/width;
        while (cols.length){
            w = cols.pop();
            i = cols.pop();
            cm.setColumnWidth(i, Math.max(this.grid.minColumnWidth, Math.floor(w + w*frac)), true);
        }

        if((tw = cm.getTotalWidth(false)) > aw){
            var adjustCol = ac != vc ? omitColumn : extraCol;
             cm.setColumnWidth(adjustCol, Math.max(1,
                     cm.getColumnWidth(adjustCol)- (tw-aw)), true);
        }

        if(preventRefresh !== true){
            this.updateAllColumnWidths();
        }


        return true;
    },

    // private
    autoExpand : function(preventUpdate){
        var g = this.grid, cm = this.cm;
        if(!this.userResized && g.autoExpandColumn){
            var tw = cm.getTotalWidth(false);
            var aw = this.grid.getGridEl().getWidth(true)-this.scrollOffset;
            if(tw != aw){
                var ci = cm.getIndexById(g.autoExpandColumn);
                var currentWidth = cm.getColumnWidth(ci);
                var cw = Math.min(Math.max(((aw-tw)+currentWidth), g.autoExpandMin), g.autoExpandMax);
                if(cw != currentWidth){
                    cm.setColumnWidth(ci, cw, true);
                    if(preventUpdate !== true){
                        this.updateColumnWidth(ci, cw);
                    }
                }
            }
        }
    },

    // private
    getColumnData : function(){
        // build a map for all the columns
        var cs = [], cm = this.cm, colCount = cm.getColumnCount();
        for(var i = 0; i < colCount; i++){
            var name = cm.getDataIndex(i);
            cs[i] = {
                name : (typeof name == 'undefined' ? this.ds.fields.get(i).name : name),
                renderer : cm.getRenderer(i),
                id : cm.getColumnId(i),
                style : this.getColumnStyle(i)
            };
        }
        return cs;
    },

    // private
    renderRows : function(startRow, endRow){
        // pull in all the crap needed to render rows
        var g = this.grid, cm = g.colModel, ds = g.store, stripe = g.stripeRows;
        var colCount = cm.getColumnCount();

        if(ds.getCount() < 1){
            return "";
        }

        var cs = this.getColumnData();

        startRow = startRow || 0;
        endRow = typeof endRow == "undefined"? ds.getCount()-1 : endRow;

        // records to render
        var rs = ds.getRange(startRow, endRow);

        return this.doRender(cs, rs, ds, startRow, colCount, stripe);
    },

    // private
    renderBody : function(){
        var markup = this.renderRows();
        return this.templates.body.apply({rows: markup});
    },

    // private
    refreshRow : function(record){
        var ds = this.ds, index;
        if(typeof record == 'number'){
            index = record;
            record = ds.getAt(index);
        }else{
            index = ds.indexOf(record);
        }
        var cls = [];
        this.insertRows(ds, index, index, true);
        this.getRow(index).rowIndex = index;
        this.onRemove(ds, record, index+1, true);
        this.fireEvent("rowupdated", this, index, record);
    },

    /**
     * Refreshs the grid UI
     * @param {Boolean} headersToo (optional) True to also refresh the headers
     */
    refresh : function(headersToo){
        this.fireEvent("beforerefresh", this);
        this.grid.stopEditing(true);

        var result = this.renderBody();
        this.mainBody.update(result);

        if(headersToo === true){
            this.updateHeaders();
            this.updateHeaderSortState();
        }
        this.processRows(0, true);
        this.layout();
        this.applyEmptyText();
        this.fireEvent("refresh", this);
    },

    // private
    applyEmptyText : function(){
        if(this.emptyText && !this.hasRows()){
            this.mainBody.update('<div class="x-grid-empty">' + this.emptyText + '</div>');
        }
    },

    // private
    updateHeaderSortState : function(){
        var state = this.ds.getSortState();
        if(!state){
            return;
        }
        if(!this.sortState || (this.sortState.field != state.field || this.sortState.direction != state.direction)){
            this.grid.fireEvent('sortchange', this.grid, state);
        }
        this.sortState = state;
        var sortColumn = this.cm.findColumnIndex(state.field);
        if(sortColumn != -1){
            var sortDir = state.direction;
            this.updateSortIcon(sortColumn, sortDir);
        }
    },

    // private
    destroy : function(){
        if(this.colMenu){
            this.colMenu.removeAll();
            Ext.menu.MenuMgr.unregister(this.colMenu);
            this.colMenu.getEl().remove();
            delete this.colMenu;
        }
        if(this.hmenu){
            this.hmenu.removeAll();
            Ext.menu.MenuMgr.unregister(this.hmenu);
            this.hmenu.getEl().remove();
            delete this.hmenu;
        }
        if(this.grid.enableColumnMove){
            var dds = Ext.dd.DDM.ids['gridHeader' + this.grid.getGridEl().id];
            if(dds){
                for(var dd in dds){
                    if(!dds[dd].config.isTarget && dds[dd].dragElId){
                        var elid = dds[dd].dragElId;
                        dds[dd].unreg();
                        Ext.get(elid).remove();
                    } else if(dds[dd].config.isTarget){
                        dds[dd].proxyTop.remove();
                        dds[dd].proxyBottom.remove();
                        dds[dd].unreg();
                    }
                    if(Ext.dd.DDM.locationCache[dd]){
                        delete Ext.dd.DDM.locationCache[dd];
                    }
                }
                delete Ext.dd.DDM.ids['gridHeader' + this.grid.getGridEl().id];
            }
        }

        Ext.destroy(this.resizeMarker, this.resizeProxy);

        if(this.dragZone){
            this.dragZone.unreg();
        }

        this.initData(null, null);
        Ext.EventManager.removeResizeListener(this.onWindowResize, this);
    },

    // private
    onDenyColumnHide : function(){

    },

    // private
    render : function(){

        if(this.autoFill){
            this.fitColumns(true, true);
        }else if(this.forceFit){
            this.fitColumns(true, false);
        }else if(this.grid.autoExpandColumn){
            this.autoExpand(true);
        }

        this.renderUI();
    },

    /* --------------------------------- Model Events and Handlers --------------------------------*/
    // private
    initData : function(ds, cm){
        if(this.ds){
            this.ds.un("load", this.onLoad, this);
            this.ds.un("datachanged", this.onDataChange, this);
            this.ds.un("add", this.onAdd, this);
            this.ds.un("remove", this.onRemove, this);
            this.ds.un("update", this.onUpdate, this);
            this.ds.un("clear", this.onClear, this);
        }
        if(ds){
            ds.on("load", this.onLoad, this);
            ds.on("datachanged", this.onDataChange, this);
            ds.on("add", this.onAdd, this);
            ds.on("remove", this.onRemove, this);
            ds.on("update", this.onUpdate, this);
            ds.on("clear", this.onClear, this);
        }
        this.ds = ds;

        if(this.cm){
            this.cm.un("configchange", this.onColConfigChange, this);
            this.cm.un("widthchange", this.onColWidthChange, this);
            this.cm.un("headerchange", this.onHeaderChange, this);
            this.cm.un("hiddenchange", this.onHiddenChange, this);
            this.cm.un("columnmoved", this.onColumnMove, this);
            this.cm.un("columnlockchange", this.onColumnLock, this);
        }
        if(cm){
            delete this.lastViewWidth;
            cm.on("configchange", this.onColConfigChange, this);
            cm.on("widthchange", this.onColWidthChange, this);
            cm.on("headerchange", this.onHeaderChange, this);
            cm.on("hiddenchange", this.onHiddenChange, this);
            cm.on("columnmoved", this.onColumnMove, this);
            cm.on("columnlockchange", this.onColumnLock, this);
        }
        this.cm = cm;
    },

    // private
    onDataChange : function(){
        this.refresh();
        this.updateHeaderSortState();
    },

    // private
    onClear : function(){
        this.refresh();
    },

    // private
    onUpdate : function(ds, record){
        this.refreshRow(record);
    },

    // private
    onAdd : function(ds, records, index){
        this.insertRows(ds, index, index + (records.length-1));
    },

    // private
    onRemove : function(ds, record, index, isUpdate){
        if(isUpdate !== true){
            this.fireEvent("beforerowremoved", this, index, record);
        }
        this.removeRow(index);
        if(isUpdate !== true){
            this.processRows(index);
            this.applyEmptyText();
            this.fireEvent("rowremoved", this, index, record);
        }
    },

    // private
    onLoad : function(){
        this.scrollToTop();
    },

    // private
    onColWidthChange : function(cm, col, width){
        this.updateColumnWidth(col, width);
    },

    // private
    onHeaderChange : function(cm, col, text){
        this.updateHeaders();
    },

    // private
    onHiddenChange : function(cm, col, hidden){
        this.updateColumnHidden(col, hidden);
    },

    // private
    onColumnMove : function(cm, oldIndex, newIndex){
        this.indexMap = null;
        var s = this.getScrollState();
        this.refresh(true);
        this.restoreScroll(s);
        this.afterMove(newIndex);
    },

    // private
    onColConfigChange : function(){
        delete this.lastViewWidth;
        this.indexMap = null;
        this.refresh(true);
    },

    /* -------------------- UI Events and Handlers ------------------------------ */
    // private
    initUI : function(grid){
        grid.on("headerclick", this.onHeaderClick, this);

        if(grid.trackMouseOver){
            grid.on("mouseover", this.onRowOver, this);
          grid.on("mouseout", this.onRowOut, this);
      }
    },

    // private
    initEvents : function(){

    },

    // private
    onHeaderClick : function(g, index){
        if(this.headersDisabled || !this.cm.isSortable(index)){
            return;
        }
        g.stopEditing(true);
        g.store.sort(this.cm.getDataIndex(index));
    },

    // private
    onRowOver : function(e, t){
        var row;
        if((row = this.findRowIndex(t)) !== false){
            this.addRowClass(row, "x-grid3-row-over");
        }
    },

    // private
    onRowOut : function(e, t){
        var row;
        if((row = this.findRowIndex(t)) !== false && row !== this.findRowIndex(e.getRelatedTarget())){
            this.removeRowClass(row, "x-grid3-row-over");
        }
    },

    // private
    handleWheel : function(e){
        e.stopPropagation();
    },

    // private
    onRowSelect : function(row){
        this.addRowClass(row, "x-grid3-row-selected");
    },

    // private
    onRowDeselect : function(row){
        this.removeRowClass(row, "x-grid3-row-selected");
    },

    // private
    onCellSelect : function(row, col){
        var cell = this.getCell(row, col);
        if(cell){
            this.fly(cell).addClass("x-grid3-cell-selected");
        }
    },

    // private
    onCellDeselect : function(row, col){
        var cell = this.getCell(row, col);
        if(cell){
            this.fly(cell).removeClass("x-grid3-cell-selected");
        }
    },

    // private
    onColumnSplitterMoved : function(i, w){
        this.userResized = true;
        var cm = this.grid.colModel;
        cm.setColumnWidth(i, w, true);

        if(this.forceFit){
            this.fitColumns(true, false, i);
            this.updateAllColumnWidths();
        }else{
            this.updateColumnWidth(i, w);
        }

        this.grid.fireEvent("columnresize", i, w);
    },

    // private
    handleHdMenuClick : function(item){
        var index = this.hdCtxIndex;
        var cm = this.cm, ds = this.ds;
        switch(item.id){
            case "asc":
                ds.sort(cm.getDataIndex(index), "ASC");
                break;
            case "desc":
                ds.sort(cm.getDataIndex(index), "DESC");
                break;
            default:
                index = cm.getIndexById(item.id.substr(4));
                if(index != -1){
                    if(item.checked && cm.getColumnsBy(this.isHideableColumn, this).length <= 1){
                        this.onDenyColumnHide();
                        return false;
                    }
                    cm.setHidden(index, item.checked);
                }
        }
        return true;
    },

    // private
    isHideableColumn : function(c){
        return !c.hidden && !c.fixed;
    },

    // private
    beforeColMenuShow : function(){
        var cm = this.cm,  colCount = cm.getColumnCount();
        this.colMenu.removeAll();
        for(var i = 0; i < colCount; i++){
            if(cm.config[i].fixed !== true && cm.config[i].hideable !== false){
                this.colMenu.add(new Ext.menu.CheckItem({
                    id: "col-"+cm.getColumnId(i),
                    text: cm.getColumnHeader(i),
                    checked: !cm.isHidden(i),
                    hideOnClick:false,
                    disabled: cm.config[i].hideable === false
                }));
            }
        }
    },

    // private
    handleHdDown : function(e, t){
        if(Ext.fly(t).hasClass('x-grid3-hd-btn')){
            e.stopEvent();
            var hd = this.findHeaderCell(t);
            Ext.fly(hd).addClass('x-grid3-hd-menu-open');
            var index = this.getCellIndex(hd);
            this.hdCtxIndex = index;
            var ms = this.hmenu.items, cm = this.cm;
            ms.get("asc").setDisabled(!cm.isSortable(index));
            ms.get("desc").setDisabled(!cm.isSortable(index));
            this.hmenu.on("hide", function(){
                Ext.fly(hd).removeClass('x-grid3-hd-menu-open');
            }, this, {single:true});
            this.hmenu.show(t, "tl-bl?");
        }
    },

    // private
    handleHdOver : function(e, t){
        var hd = this.findHeaderCell(t);
        if(hd && !this.headersDisabled){
            this.activeHd = hd;
            this.activeHdIndex = this.getCellIndex(hd);
            var fly = this.fly(hd);
            this.activeHdRegion = fly.getRegion();
            if(!this.cm.isMenuDisabled(this.activeHdIndex)){
                fly.addClass("x-grid3-hd-over");
                this.activeHdBtn = fly.child('.x-grid3-hd-btn');
                if(this.activeHdBtn){
                    this.activeHdBtn.dom.style.height = (hd.firstChild.offsetHeight-1)+'px';
                }
            }
        }
    },

    // private
    handleHdMove : function(e, t){
        if(this.activeHd && !this.headersDisabled){
            var hw = this.splitHandleWidth || 5;
            var r = this.activeHdRegion;
            var x = e.getPageX();
            var ss = this.activeHd.style;
            if(x - r.left <= hw && this.cm.isResizable(this.activeHdIndex-1)){
                ss.cursor = Ext.isAir ? 'move' : Ext.isSafari ? 'e-resize' : 'col-resize'; // col-resize not always supported
            }else if(r.right - x <= (!this.activeHdBtn ? hw : 2) && this.cm.isResizable(this.activeHdIndex)){
                ss.cursor = Ext.isAir ? 'move' : Ext.isSafari ? 'w-resize' : 'col-resize';
            }else{
                ss.cursor = '';
            }
        }
    },

    // private
    handleHdOut : function(e, t){
        var hd = this.findHeaderCell(t);
        if(hd && (!Ext.isIE || !e.within(hd, true))){
            this.activeHd = null;
            this.fly(hd).removeClass("x-grid3-hd-over");
            hd.style.cursor = '';
        }
    },

    // private
    hasRows : function(){
        var fc = this.mainBody.dom.firstChild;
        return fc && fc.className != 'x-grid-empty';
    },

    // back compat
    bind : function(d, c){
        this.initData(d, c);
    }
});


// private
// This is a support class used internally by the Grid components
Ext.grid.GridView.SplitDragZone = function(grid, hd){
    this.grid = grid;
    this.view = grid.getView();
    this.marker = this.view.resizeMarker;
    this.proxy = this.view.resizeProxy;
    Ext.grid.GridView.SplitDragZone.superclass.constructor.call(this, hd,
        "gridSplitters" + this.grid.getGridEl().id, {
        dragElId : Ext.id(this.proxy.dom), resizeFrame:false
    });
    this.scroll = false;
    this.hw = this.view.splitHandleWidth || 5;
};
Ext.extend(Ext.grid.GridView.SplitDragZone, Ext.dd.DDProxy, {

    b4StartDrag : function(x, y){
        this.view.headersDisabled = true;
        var h = this.view.mainWrap.getHeight();
        this.marker.setHeight(h);
        this.marker.show();
        this.marker.alignTo(this.view.getHeaderCell(this.cellIndex), 'tl-tl', [-2, 0]);
        this.proxy.setHeight(h);
        var w = this.cm.getColumnWidth(this.cellIndex);
        var minw = Math.max(w-this.grid.minColumnWidth, 0);
        this.resetConstraints();
        this.setXConstraint(minw, 1000);
        this.setYConstraint(0, 0);
        this.minX = x - minw;
        this.maxX = x + 1000;
        this.startPos = x;
        Ext.dd.DDProxy.prototype.b4StartDrag.call(this, x, y);
    },


    handleMouseDown : function(e){
        var t = this.view.findHeaderCell(e.getTarget());
        if(t){
            var xy = this.view.fly(t).getXY(), x = xy[0], y = xy[1];
            var exy = e.getXY(), ex = exy[0], ey = exy[1];
            var w = t.offsetWidth, adjust = false;
            if((ex - x) <= this.hw){
                adjust = -1;
            }else if((x+w) - ex <= this.hw){
                adjust = 0;
            }
            if(adjust !== false){
                this.cm = this.grid.colModel;
                var ci = this.view.getCellIndex(t);
                if(adjust == -1){
                  if (ci + adjust < 0) {
                    return;
                  }
                    while(this.cm.isHidden(ci+adjust)){
                        --adjust;
                        if(ci+adjust < 0){
                            return;
                        }
                    }
                }
                this.cellIndex = ci+adjust;
                this.split = t.dom;
                if(this.cm.isResizable(this.cellIndex) && !this.cm.isFixed(this.cellIndex)){
                    Ext.grid.GridView.SplitDragZone.superclass.handleMouseDown.apply(this, arguments);
                }
            }else if(this.view.columnDrag){
                this.view.columnDrag.callHandleMouseDown(e);
            }
        }
    },

    endDrag : function(e){
        this.marker.hide();
        var v = this.view;
        var endX = Math.max(this.minX, e.getPageX());
        var diff = endX - this.startPos;
        v.onColumnSplitterMoved(this.cellIndex, this.cm.getColumnWidth(this.cellIndex)+diff);
        setTimeout(function(){
            v.headersDisabled = false;
        }, 50);
    },

    autoOffset : function(){
        this.setDelta(0,0);
    }
});
