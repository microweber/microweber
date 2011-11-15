/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */


Ext.onReady(function(){
    Ext.QuickTips.init();
    Ext.form.Field.prototype.msgTarget = 'side';
    
    /* Language chooser combobox  */
    var store = new Ext.data.SimpleStore({
        fields: ['code', 'language', 'charset'],
        data : Ext.exampledata.languages // from languages.js
    });
    var combo = new Ext.form.ComboBox({
        store: store,
        displayField:'language',
        typeAhead: true,
        mode: 'local',
        triggerAction: 'all',
        emptyText:'Select a language...',
        selectOnFocus:true,
	onSelect: function(record) {
	    window.location.search = Ext.urlEncode({"lang":record.get("code"),"charset":record.get("charset")});
	}
    });
    combo.render('languages');

    // get the selected language code parameter from url (if exists)
    var params = Ext.urlDecode(window.location.search.substring(1));
    if (params.lang) {
	// check if there's really a language with that language code
	record = store.data.find(function(item, key) {
	    if (item.data.code==params.lang){
		return true;
	    }
	    return false;
	});
	// if language was found in store assign it as current value in combobox
	if (record) {
	    combo.setValue(record.data.language);
	}
    }

    /* Email field */
    var emailfield = new Ext.FormPanel({
        labelWidth: 100, // label settings here cascade unless overridden
        frame:true,
        title: 'Email Field',
        bodyStyle:'padding:5px 5px 0',
        width: 360,
        defaults: {width: 220},
        defaultType: 'textfield',

        items: [{
                fieldLabel: 'Email',
                name: 'email',
                vtype:'email'
            }
        ]
    });
    emailfield.render('emailfield');

    /* Datepicker */
    var datefield = new Ext.FormPanel({
        labelWidth: 100, // label settings here cascade unless overridden
        frame:true,
        title: 'Datepicker',
        bodyStyle:'padding:5px 5px 0',
        width: 360,
        defaults: {width: 220},
        defaultType: 'datefield',

        items: [{
                fieldLabel: 'Date',
                name: 'date'
            }
        ]
    });
    datefield.render('datefield');
    
    // shorthand alias
    var fm = Ext.form, Ed = Ext.grid.GridEditor;
    var monthArray = Date.monthNames.map(function (e) { return [e]; });    
    var ds = new Ext.data.Store({
		proxy: new Ext.data.PagingMemoryProxy(monthArray),
		reader: new Ext.data.ArrayReader({}, [
			{name: 'month'}
		])
    });
    var cm = new Ext.grid.ColumnModel([{
           header: "Months of the year",
           dataIndex: 'month',
           editor: new Ed(new fm.TextField({
               allowBlank: false
           })),
           width: 240
        }]);
    cm.defaultSortable = true;
    var grid = new Ext.grid.GridPanel({
	el:'grid',
	width: 360,
	height: 203,
	title:'Month Browser',
	store: ds,
	cm: cm,
	sm: new Ext.grid.RowSelectionModel({selectRow:Ext.emptyFn}),

	bbar: new Ext.PagingToolbar({
            pageSize: 6,
            store: ds,
            displayInfo: true
        })
    })
    grid.render();

    // trigger the data store load
    ds.load({params:{start:0, limit:6}});    
});
