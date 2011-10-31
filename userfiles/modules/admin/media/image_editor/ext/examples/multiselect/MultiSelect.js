/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/*
 * Note that this control should still be treated as an example and that the API will most likely
 * change once it is ported into the Ext core as a standard form control.  This is still planned
 * for a future release, so this should not yet be treated as a final, stable API at this time.
 */
 
/** 
 * @class Ext.ux.MultiSelect
 * @extends Ext.form.Field
 * A control that allows selection and form submission of multiple list items. The MultiSelect control
 * depends on the Ext.ux.DDView class to provide drag/drop capability both within the list and also 
 * between multiple MultiSelect controls (see the Ext.ux.ItemSelector).
 * 
 *  @history
 *    2008-06-19 bpm Original code contributed by Toby Stuart
 *    2008-06-19 bpm Docs and demo code clean up
 * 
 * @constructor
 * Create a new MultiSelect
 * @param {Object} config Configuration options
 */
Ext.ux.Multiselect = Ext.extend(Ext.form.Field,  {
    /**
     * @cfg {String} legend Wraps the object with a fieldset and specified legend.
     */
    /**
     * @cfg {Store} store The {@link Ext.data.Store} used by the underlying Ext.ux.DDView.
     */
    /**
     * @cfg {Ext.ux.DDView} view The Ext.ux.DDView used to render the multiselect list.
     */
    /**
     * @cfg {String/Array} dragGroup The ddgroup name(s) for the DDView's DragZone (defaults to undefined). 
     */ 
    /**
     * @cfg {String/Array} dropGroup The ddgroup name(s) for the DDView's DropZone (defaults to undefined). 
     */ 
    /**
     * @cfg {Object/Array} tbar The top toolbar of the control. This can be a {@link Ext.Toolbar} object, a 
     * toolbar config, or an array of buttons/button configs to be added to the toolbar.
     */
    /**
     * @cfg {String} fieldName The name of the field to sort by when sorting is enabled.
     */
    /**
     * @cfg {String} appendOnly True if the list should only allow append drops when drag/drop is enabled 
     * (use for lists which are sorted, defaults to false).
     */
    appendOnly:false,
    /**
     * @cfg {Array} dataFields Inline data definition when not using a pre-initialised store. Known to cause problems 
     * in some browswers for very long lists. Use store for large datasets.
     */
    dataFields:[],
    /**
     * @cfg {Array} data Inline data when not using a pre-initialised store. Known to cause problems in some 
     * browswers for very long lists. Use store for large datasets.
     */
    data:[],
    /**
     * @cfg {Number} width Width in pixels of the control (defaults to 100).
     */
    width:100,
    /**
     * @cfg {Number} height Height in pixels of the control (defaults to 100).
     */
    height:100,
    /**
     * @cfg {String/Number} displayField Name/Index of the desired display field in the dataset (defaults to 0).
     */
    displayField:0,
    /**
     * @cfg {String/Number} valueField Name/Index of the desired value field in the dataset (defaults to 1).
     */
    valueField:1,
    /**
     * @cfg {Boolean} allowBlank True to require at least one item in the list to be selected, false to allow no 
     * selection (defaults to true).
     */
    allowBlank:true,
    /**
     * @cfg {Number} minLength Minimum number of selections allowed (defaults to 0).
     */
    minLength:0,
    /**
     * @cfg {Number} maxLength Maximum number of selections allowed (defaults to Number.MAX_VALUE). 
     */
    maxLength:Number.MAX_VALUE,
    /**
     * @cfg {String} blankText Default text displayed when the control contains no items (defaults to the same value as
     * {@link Ext.form.TextField#blankText}.
     */
    blankText:Ext.form.TextField.prototype.blankText,
    /**
     * @cfg {String} minLengthText Validation message displayed when {@link #minLength} is not met (defaults to 'Minimum {0} 
     * item(s) required').  The {0} token will be replaced by the value of {@link #minLength}.
     */
    minLengthText:'Minimum {0} item(s) required',
    /**
     * @cfg {String} maxLengthText Validation message displayed when {@link #maxLength} is not met (defaults to 'Maximum {0} 
     * item(s) allowed').  The {0} token will be replaced by the value of {@link #maxLength}.
     */
    maxLengthText:'Maximum {0} item(s) allowed',
    /**
     * @cfg {String} delimiter The string used to delimit between items when set or returned as a string of values
     * (defaults to ',').
     */
    delimiter:',',
    
    // DDView settings
    copy:false,
    allowDup:false,
    allowTrash:false,
    focusClass:undefined,
    sortDir:'ASC',
    
    // private
    defaultAutoCreate : {tag: "div"},
    
    // private
    initComponent: function(){
        Ext.ux.Multiselect.superclass.initComponent.call(this);
        this.addEvents({
            'dblclick' : true,
            'click' : true,
            'change' : true,
            'drop' : true
        });     
    },
    
    // private
    onRender: function(ct, position){
        Ext.ux.Multiselect.superclass.onRender.call(this, ct, position);
        
        var cls = 'ux-mselect';
        var fs = new Ext.form.FieldSet({
            renderTo:this.el,
            title:this.legend,
            height:this.height,
            width:this.width,
            style:"padding:0;",
            tbar:this.tbar
        });
        //if(!this.legend)fs.el.down('.'+fs.headerCls).remove();
        fs.body.addClass(cls);

        var tpl = '<tpl for="."><div class="' + cls + '-item';
        if(Ext.isIE || Ext.isIE7){
            tpl+='" unselectable=on';
        }else{
            tpl+=' x-unselectable"';
        }
        tpl+='>{' + this.displayField + '}</div></tpl>';

        if(!this.store){
            this.store = new Ext.data.SimpleStore({
                fields: this.dataFields,
                data : this.data
            });
        }

        this.view = new Ext.ux.DDView({
            multiSelect: true, 
            store: this.store, 
            selectedClass: cls+"-selected", 
            tpl:tpl,
            allowDup:this.allowDup, 
            copy: this.copy, 
            allowTrash: this.allowTrash, 
            dragGroup: this.dragGroup, 
            dropGroup: this.dropGroup, 
            itemSelector:"."+cls+"-item",
            isFormField:false, 
            applyTo:fs.body,
            appendOnly:this.appendOnly,
            sortField:this.sortField, 
            sortDir:this.sortDir
        });

        fs.add(this.view);
        
        this.view.on('click', this.onViewClick, this);
        this.view.on('beforeClick', this.onViewBeforeClick, this);
        this.view.on('dblclick', this.onViewDblClick, this);
        this.view.on('drop', function(ddView, n, dd, e, data){
            return this.fireEvent("drop", ddView, n, dd, e, data);
        }, this);
        
        this.hiddenName = this.name;
        var hiddenTag={tag: "input", type: "hidden", value: "", name:this.name};
        if (this.isFormField) { 
            this.hiddenField = this.el.createChild(hiddenTag);
        } else {
            this.hiddenField = Ext.get(document.body).createChild(hiddenTag);
        }
        fs.doLayout();
    },
    
    // private
    initValue:Ext.emptyFn,
    
    // private
    onViewClick: function(vw, index, node, e) {
        var arrayIndex = this.preClickSelections.indexOf(index);
        if (arrayIndex  != -1)
        {
            this.preClickSelections.splice(arrayIndex, 1);
            this.view.clearSelections(true);
            this.view.select(this.preClickSelections);
        }
        this.fireEvent('change', this, this.getValue(), this.hiddenField.dom.value);
        this.hiddenField.dom.value = this.getValue();
        this.fireEvent('click', this, e);
        this.validate();        
    },

    // private
    onViewBeforeClick: function(vw, index, node, e) {
        this.preClickSelections = this.view.getSelectedIndexes();
        if (this.disabled) {return false;}
    },

    // private
    onViewDblClick : function(vw, index, node, e) {
        return this.fireEvent('dblclick', vw, index, node, e);
    },  
    
    /**
     * Returns an array of data values for the selected items in the list. The values will be separated
     * by {@link #delimiter}.
     * @return {Array} value An array of string data values
     */
    getValue: function(valueField){
        var returnArray = [];
        var selectionsArray = this.view.getSelectedIndexes();
        if (selectionsArray.length == 0) {return '';}
        for (var i=0; i<selectionsArray.length; i++) {
            returnArray.push(this.store.getAt(selectionsArray[i]).get(((valueField != null)? valueField : this.valueField)));
        }
        return returnArray.join(this.delimiter);
    },

    /**
     * Sets a delimited string (using {@link #delimiter}) or array of data values into the list.
     * @param {String/Array} values The values to set
     */
    setValue: function(values) {
        var index;
        var selections = [];
        this.view.clearSelections();
        this.hiddenField.dom.value = '';
        
        if (!values || (values == '')) { return; }
        
        if (!(values instanceof Array)) { values = values.split(this.delimiter); }
        for (var i=0; i<values.length; i++) {
            index = this.view.store.indexOf(this.view.store.query(this.valueField, 
                new RegExp('^' + values[i] + '$', "i")).itemAt(0));
            selections.push(index);
        }
        this.view.select(selections);
        this.hiddenField.dom.value = this.getValue();
        this.validate();
    },
    
    // inherit docs
    reset : function() {
        this.setValue('');
    },
    
    // inherit docs
    getRawValue: function(valueField) {
        var tmp = this.getValue(valueField);
        if (tmp.length) {
            tmp = tmp.split(this.delimiter);
        }
        else{
            tmp = [];
        }
        return tmp;
    },

    // inherit docs
    setRawValue: function(values){
        setValue(values);
    },

    // inherit docs
    validateValue : function(value){
        if (value.length < 1) { // if it has no value
             if (this.allowBlank) {
                 this.clearInvalid();
                 return true;
             } else {
                 this.markInvalid(this.blankText);
                 return false;
             }
        }
        if (value.length < this.minLength) {
            this.markInvalid(String.format(this.minLengthText, this.minLength));
            return false;
        }
        if (value.length > this.maxLength) {
            this.markInvalid(String.format(this.maxLengthText, this.maxLength));
            return false;
        }
        return true;
    }
});

Ext.reg("multiselect", Ext.ux.Multiselect);
