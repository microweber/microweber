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
    
    /*
     * Ext.ux.Multiselect Example Code
     */
    var msForm = new Ext.form.FormPanel({
        title: 'MultiSelect Test',
        width:700,
        bodyStyle: 'padding:10px;',
        renderTo: 'multiselect',
        items:[{
            xtype:"multiselect",
            fieldLabel:"Multiselect<br />(Required)",
            name:"multiselect",
            dataFields:["code", "desc"], 
            valueField:"code",
            displayField:"desc",
            width:250,
            height:200,
            allowBlank:false,
            data:[[123,"One Hundred Twenty Three"],
                ["1", "One"], ["2", "Two"], ["3", "Three"], ["4", "Four"], ["5", "Five"],
                ["6", "Six"], ["7", "Seven"], ["8", "Eight"], ["9", "Nine"]],
            tbar:[{
                text:"clear",
                handler:function(){
	                msForm.getForm().findField("multiselect").reset();
	            }
            }]
        }],
        tbar:[{
            text: 'Options',
            menu: [{
	            text:"Set Value (2,3)",
	            handler: function(){
	                msForm.getForm().findField("multiselect").setValue("2,3");
	            }
	        },{
	            text:"Toggle Enabled",
	            handler: function(){
	                var m=msForm.getForm().findField("multiselect");
	                if (!m.disabled)m.disable();
	                else m.enable();
	            }
            }]
        }],
        
        buttons: [{
            text: 'Save',
            handler: function(){
                if(msForm.getForm().isValid()){
	                Ext.Msg.alert('Submitted Values', 'The following will be sent to the server: <br />'+ 
	                    msForm.getForm().getValues(true));
                }
            }
        }]
    });
    
    
    /*
     * Ext.ux.ItemSelector Example Code
     */
    var isForm = new Ext.form.FormPanel({
        title: 'ItemSelector Test',
        width:700,
        bodyStyle: 'padding:10px;',
        renderTo: 'itemselector',
        items:[{
            xtype:"itemselector",
            name:"itemselector",
            fieldLabel:"ItemSelector",
            dataFields:["code", "desc"],
            toData:[["10", "Ten"]],
            msWidth:250,
            msHeight:200,
            valueField:"code",
            displayField:"desc",
            imagePath:"images/",
            toLegend:"Selected",
            fromLegend:"Available",
            fromData:[[123,"One Hundred Twenty Three"],
                ["1", "One"], ["2", "Two"], ["3", "Three"], ["4", "Four"], ["5", "Five"],
                ["6", "Six"], ["7", "Seven"], ["8", "Eight"], ["9", "Nine"]],
            toTBar:[{
                text:"Clear",
                handler:function(){
                    var i=isForm.getForm().findField("itemselector");
                    i.reset.call(i);
                }
            }]
        }],
        
        buttons: [{
            text: 'Save',
            handler: function(){
                if(isForm.getForm().isValid()){
                    Ext.Msg.alert('Submitted Values', 'The following will be sent to the server: <br />'+ 
                        isForm.getForm().getValues(true));
                }
            }
        }]
    });
    
});