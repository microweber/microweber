Ext.namespace('Ext.ux.plugins');

Ext.ux.plugins.EditAreaEditor = function(config){
    Ext.apply(this, {config:config});
};

Ext.extend(Ext.ux.plugins.EditAreaEditor, Ext.util.Observable, {
    config:	{
		start_highlight: true
	},
	onRender:function(o){
        Ext.apply(this.config, { id: o.id});
        editAreaLoader.init(this.config);
        editAreaLoader.start(o.id);
    },
    init: function(textarea) {
        textarea.on('render', this.onRender, this);
        // Extend Textarea
        Ext.apply(textarea, {
            setValue:textarea.setValue.createSequence(function(ct, position) {
                //alert('update:'+textarea.id);
                editAreaLoader.setValue(textarea.id, editAreaLoader.getValue(textarea.id))
            }),
            
            getValue: function() { return editAreaLoader.getValue(textarea.id) },
            
            beforeDestroy:textarea.beforeDestroy.createSequence(function(ct, position) {
            	//TODO
                
             }
            )
        });
        
    }
    
});  

// register xtype
Ext.reg('editarea', Ext.ux.plugins.EditAreaEditor);

Ext.app.SearchField = Ext.extend(Ext.form.TwinTriggerField, {
    initComponent : function(){
        Ext.app.SearchField.superclass.initComponent.call(this);
        this.on('specialkey', function(f, e){
            if(e.getKey() == e.ENTER){
                this.onTrigger2Click();
            }
        }, this);
    },

    validationEvent:false,
    validateOnBlur:false,
    trigger1Class:'x-form-clear-trigger',
    trigger2Class:'x-form-search-trigger',
    hideTrigger1:true,
    width:180,
    hasSearch : false,
    paramName : 'searchitem',

    onTrigger1Click : function(){
        if(this.hasSearch){
            this.el.dom.value = '';
            var o = {start: 0};
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({params:o});
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    },

    onTrigger2Click : function(){
        var v = this.getRawValue();
        if(v.length < 1){
            this.onTrigger1Click();
            return;
        }
        var o = {start: 0};
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = v;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    }
});
