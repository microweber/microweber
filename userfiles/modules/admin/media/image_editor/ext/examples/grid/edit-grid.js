/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.onReady(function(){
    Ext.QuickTips.init();

    function formatDate(value){
        return value ? value.dateFormat('M d, Y') : '';
    };
    // shorthand alias
    var fm = Ext.form;

    // custom column plugin example
    var checkColumn = new Ext.grid.CheckColumn({
       header: "Indoor?",
       dataIndex: 'indoor',
       width: 55
    });

    // the column model has information about grid columns
    // dataIndex maps the column to the specific data field in
    // the data store (created below)
    var cm = new Ext.grid.ColumnModel([{
           id:'common',
           header: "Common Name",
           dataIndex: 'common',
           width: 220,
           editor: new fm.TextField({
               allowBlank: false
           })
        },{
           header: "Light",
           dataIndex: 'light',
           width: 130,
           editor: new Ext.form.ComboBox({
               typeAhead: true,
               triggerAction: 'all',
               transform:'light',
               lazyRender:true,
               listClass: 'x-combo-list-small'
            })
        },{
           header: "Price",
           dataIndex: 'price',
           width: 70,
           align: 'right',
           renderer: 'usMoney',
           editor: new fm.NumberField({
               allowBlank: false,
               allowNegative: false,
               maxValue: 100000
           })
        },{
           header: "Available",
           dataIndex: 'availDate',
           width: 95,
           renderer: formatDate,
           editor: new fm.DateField({
                format: 'm/d/y',
                minValue: '01/01/06',
                disabledDays: [0, 6],
                disabledDaysText: 'Plants are not available on the weekends'
            })
        },
        checkColumn
    ]);

    // by default columns are sortable
    cm.defaultSortable = true;

    // this could be inline, but we want to define the Plant record
    // type so we can add records dynamically
    var Plant = Ext.data.Record.create([
           // the "name" below matches the tag name to read, except "availDate"
           // which is mapped to the tag "availability"
           {name: 'common', type: 'string'},
           {name: 'botanical', type: 'string'},
           {name: 'light'},
           {name: 'price', type: 'float'},             // automatic date conversions
           {name: 'availDate', mapping: 'availability', type: 'date', dateFormat: 'm/d/Y'},
           {name: 'indoor', type: 'bool'}
      ]);

    // create the Data Store
    var store = new Ext.data.Store({
        // load using HTTP
        url: 'plants.xml',

        // the return will be XML, so lets set up a reader
        reader: new Ext.data.XmlReader({
               // records will have a "plant" tag
               record: 'plant'
           }, Plant),

        sortInfo:{field:'common', direction:'ASC'}
    });

    // create the editor grid
    var grid = new Ext.grid.EditorGridPanel({
        store: store,
        cm: cm,
        renderTo: 'editor-grid',
        width:600,
        height:300,
        autoExpandColumn:'common',
        title:'Edit Plants?',
        frame:true,
        plugins:checkColumn,
        clicksToEdit:1,

        tbar: [{
            text: 'Add Plant',
            handler : function(){
                var p = new Plant({
                    common: 'New Plant 1',
                    light: 'Mostly Shade',
                    price: 0,
                    availDate: (new Date()).clearTime(),
                    indoor: false
                });
                grid.stopEditing();
                store.insert(0, p);
                grid.startEditing(0, 0);
            }
        }]
    });

    // trigger the data store load
    store.load();
});

Ext.grid.CheckColumn = function(config){
    Ext.apply(this, config);
    if(!this.id){
        this.id = Ext.id();
    }
    this.renderer = this.renderer.createDelegate(this);
};

Ext.grid.CheckColumn.prototype ={
    init : function(grid){
        this.grid = grid;
        this.grid.on('render', function(){
            var view = this.grid.getView();
            view.mainBody.on('mousedown', this.onMouseDown, this);
        }, this);
    },

    onMouseDown : function(e, t){
        if(t.className && t.className.indexOf('x-grid3-cc-'+this.id) != -1){
            e.stopEvent();
            var index = this.grid.getView().findRowIndex(t);
            var record = this.grid.store.getAt(index);
            record.set(this.dataIndex, !record.data[this.dataIndex]);
        }
    },

    renderer : function(v, p, record){
        p.css += ' x-grid3-check-col-td'; 
        return '<div class="x-grid3-check-col'+(v?'-on':'')+' x-grid3-cc-'+this.id+'">&#160;</div>';
    }
};