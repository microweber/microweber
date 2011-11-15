/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/*
 * Note that this control will most likely remain as an example, and not as a core Ext form
 * control.  However, the API will be changing in a future release and so should not yet be
 * treated as a final, stable API at this time.
 */
 
/** 
 * @class Ext.ux.ItemSelector
 * @extends Ext.form.Field
 * A control that allows selection of between two Ext.ux.MultiSelect controls.
 * 
 *  @history
 *    2008-06-19 bpm Original code contributed by Toby Stuart
 * 
 * @constructor
 * Create a new ItemSelector
 * @param {Object} config Configuration options
 */
Ext.ux.ItemSelector = Ext.extend(Ext.form.Field,  {
    msWidth:200,
    msHeight:300,
    hideNavIcons:false,
    imagePath:"",
    iconUp:"up2.gif",
    iconDown:"down2.gif",
    iconLeft:"left2.gif",
    iconRight:"right2.gif",
    iconTop:"top2.gif",
    iconBottom:"bottom2.gif",
    drawUpIcon:true,
    drawDownIcon:true,
    drawLeftIcon:true,
    drawRightIcon:true,
    drawTopIcon:true,
    drawBotIcon:true,
    fromStore:null,
    toStore:null,
    fromData:null, 
    toData:null,
    displayField:0,
    valueField:1,
    switchToFrom:false,
    allowDup:false,
    focusClass:undefined,
    delimiter:',',
    readOnly:false,
    toLegend:null,
    fromLegend:null,
    toSortField:null,
    fromSortField:null,
    toSortDir:'ASC',
    fromSortDir:'ASC',
    toTBar:null,
    fromTBar:null,
    bodyStyle:null,
    border:false,
    defaultAutoCreate:{tag: "div"},
    
    initComponent: function(){
        Ext.ux.ItemSelector.superclass.initComponent.call(this);
        this.addEvents({
            'rowdblclick' : true,
            'change' : true
        });         
    },

    onRender: function(ct, position){
        Ext.ux.ItemSelector.superclass.onRender.call(this, ct, position);

        this.fromMultiselect = new Ext.ux.Multiselect({
            legend: this.fromLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            copy: this.allowDup,
            allowTrash: this.allowDup,
            dragGroup: this.readOnly ? null : "drop2-"+this.el.dom.id,
            dropGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            dataFields: this.dataFields,
            data: this.fromData,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.fromStore,
            isFormField: false,
            tbar: this.fromTBar,
            appendOnly: true,
            sortField: this.fromSortField,
            sortDir: this.fromSortDir
        });
        this.fromMultiselect.on('dblclick', this.onRowDblClick, this);

        if (!this.toStore) {
            this.toStore = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.toData
            });
        }
        this.toStore.on('add', this.valueChanged, this);
        this.toStore.on('remove', this.valueChanged, this);
        this.toStore.on('load', this.valueChanged, this);

        this.toMultiselect = new Ext.ux.Multiselect({
            legend: this.toLegend,
            delimiter: this.delimiter,
            allowDup: this.allowDup,
            dragGroup: this.readOnly ? null : "drop1-"+this.el.dom.id,
            //dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+(this.toSortField ? "" : ",drop1-"+this.el.dom.id),
            dropGroup: this.readOnly ? null : "drop2-"+this.el.dom.id+",drop1-"+this.el.dom.id,
            width: this.msWidth,
            height: this.msHeight,
            displayField: this.displayField,
            valueField: this.valueField,
            store: this.toStore,
            isFormField: false,
            tbar: this.toTBar,
            sortField: this.toSortField,
            sortDir: this.toSortDir
        });
        this.toMultiselect.on('dblclick', this.onRowDblClick, this);
                
        var p = new Ext.Panel({
            bodyStyle:this.bodyStyle,
            border:this.border,
            layout:"table",
            layoutConfig:{columns:3}
        });
        p.add(this.switchToFrom ? this.toMultiselect : this.fromMultiselect);
        var icons = new Ext.Panel({header:false});
        p.add(icons);
        p.add(this.switchToFrom ? this.fromMultiselect : this.toMultiselect);
        p.render(this.el);
        icons.el.down('.'+icons.bwrapCls).remove();

        if (this.imagePath!="" && this.imagePath.charAt(this.imagePath.length-1)!="/")
            this.imagePath+="/";
        this.iconUp = this.imagePath + (this.iconUp || 'up2.gif');
        this.iconDown = this.imagePath + (this.iconDown || 'down2.gif');
        this.iconLeft = this.imagePath + (this.iconLeft || 'left2.gif');
        this.iconRight = this.imagePath + (this.iconRight || 'right2.gif');
        this.iconTop = this.imagePath + (this.iconTop || 'top2.gif');
        this.iconBottom = this.imagePath + (this.iconBottom || 'bottom2.gif');
        var el=icons.getEl();
        if (!this.toSortField) {
            this.toTopIcon = el.createChild({tag:'img', src:this.iconTop, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.upIcon = el.createChild({tag:'img', src:this.iconUp, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
        }
        this.addIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconLeft:this.iconRight, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        this.removeIcon = el.createChild({tag:'img', src:this.switchToFrom?this.iconRight:this.iconLeft, style:{cursor:'pointer', margin:'2px'}});
        el.createChild({tag: 'br'});
        if (!this.toSortField) {
            this.downIcon = el.createChild({tag:'img', src:this.iconDown, style:{cursor:'pointer', margin:'2px'}});
            el.createChild({tag: 'br'});
            this.toBottomIcon = el.createChild({tag:'img', src:this.iconBottom, style:{cursor:'pointer', margin:'2px'}});
        }
        if (!this.readOnly) {
            if (!this.toSortField) {
                this.toTopIcon.on('click', this.toTop, this);
                this.upIcon.on('click', this.up, this);
                this.downIcon.on('click', this.down, this);
                this.toBottomIcon.on('click', this.toBottom, this);
            }
            this.addIcon.on('click', this.fromTo, this);
            this.removeIcon.on('click', this.toFrom, this);
        }
        if (!this.drawUpIcon || this.hideNavIcons) { this.upIcon.dom.style.display='none'; }
        if (!this.drawDownIcon || this.hideNavIcons) { this.downIcon.dom.style.display='none'; }
        if (!this.drawLeftIcon || this.hideNavIcons) { this.addIcon.dom.style.display='none'; }
        if (!this.drawRightIcon || this.hideNavIcons) { this.removeIcon.dom.style.display='none'; }
        if (!this.drawTopIcon || this.hideNavIcons) { this.toTopIcon.dom.style.display='none'; }
        if (!this.drawBotIcon || this.hideNavIcons) { this.toBottomIcon.dom.style.display='none'; }

        var tb = p.body.first();
        this.el.setWidth(p.body.first().getWidth());
        p.body.removeClass();
        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        this.hiddenField = this.el.createChild(hiddenTag);
        this.valueChanged(this.toStore);
    },
    
    initValue:Ext.emptyFn,
    
    toTop : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=records.length-1; i>-1; i--) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.insert(0, record);
                selectionsArray.push(((records.length - 1) - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },

    toBottom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            selectionsArray.sort();
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                this.toMultiselect.view.store.add(record);
                selectionsArray.push((this.toMultiselect.view.store.getCount()) - (records.length - i));
            }
        }
        this.toMultiselect.view.refresh();
        this.toMultiselect.view.select(selectionsArray);
    },
    
    up : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] - 1) >= 0) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] - 1, record);
                    newSelectionsArray.push(selectionsArray[i] - 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },

    down : function() {
        var record = null;
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        selectionsArray.sort();
        selectionsArray.reverse();
        var newSelectionsArray = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                if ((selectionsArray[i] + 1) < this.toMultiselect.view.store.getCount()) {
                    this.toMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.insert(selectionsArray[i] + 1, record);
                    newSelectionsArray.push(selectionsArray[i] + 1);
                }
            }
            this.toMultiselect.view.refresh();
            this.toMultiselect.view.select(newSelectionsArray);
        }
    },
    
    fromTo : function() {
        var selectionsArray = this.fromMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.fromMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            if(!this.allowDup)selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                if(this.allowDup){
                    var x=new Ext.data.Record();
                    record.id=x.id;
                    delete x;   
                    this.toMultiselect.view.store.add(record);
                }else{
                    this.fromMultiselect.view.store.remove(record);
                    this.toMultiselect.view.store.add(record);
                    selectionsArray.push((this.toMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.toMultiselect.view.refresh();
        this.fromMultiselect.view.refresh();
        if(this.toSortField)this.toMultiselect.store.sort(this.toSortField, this.toSortDir);
        if(this.allowDup)this.fromMultiselect.view.select(selectionsArray);
        else this.toMultiselect.view.select(selectionsArray);
    },
    
    toFrom : function() {
        var selectionsArray = this.toMultiselect.view.getSelectedIndexes();
        var records = [];
        if (selectionsArray.length > 0) {
            for (var i=0; i<selectionsArray.length; i++) {
                record = this.toMultiselect.view.store.getAt(selectionsArray[i]);
                records.push(record);
            }
            selectionsArray = [];
            for (var i=0; i<records.length; i++) {
                record = records[i];
                this.toMultiselect.view.store.remove(record);
                if(!this.allowDup){
                    this.fromMultiselect.view.store.add(record);
                    selectionsArray.push((this.fromMultiselect.view.store.getCount() - 1));
                }
            }
        }
        this.fromMultiselect.view.refresh();
        this.toMultiselect.view.refresh();
        if(this.fromSortField)this.fromMultiselect.store.sort(this.fromSortField, this.fromSortDir);
        this.fromMultiselect.view.select(selectionsArray);
    },
    
    valueChanged: function(store) {
        var record = null;
        var values = [];
        for (var i=0; i<store.getCount(); i++) {
            record = store.getAt(i);
            values.push(record.get(this.valueField));
        }
        this.hiddenField.dom.value = values.join(this.delimiter);
        this.fireEvent('change', this, this.getValue(), this.hiddenField.dom.value);
    },
    
    getValue : function() {
        return this.hiddenField.dom.value;
    },
    
    onRowDblClick : function(vw, index, node, e) {
        return this.fireEvent('rowdblclick', vw, index, node, e);
    },
    
    reset: function(){
        range = this.toMultiselect.store.getRange();
        this.toMultiselect.store.removeAll();
        if (!this.allowDup) {
            this.fromMultiselect.store.add(range);
            this.fromMultiselect.store.sort(this.displayField,'ASC');
        }
        this.valueChanged(this.toMultiselect.store);
    }
});

Ext.reg("itemselector", Ext.ux.ItemSelector);