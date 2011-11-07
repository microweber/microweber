/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.Radio=Ext.extend(Ext.form.Checkbox,{inputType:"radio",baseCls:"x-form-radio",getGroupValue:function(){var A=this.getParent().child("input[name="+this.el.dom.name+"]:checked",true);return A?A.value:null},getParent:function(){return this.el.up("form")||Ext.getBody()},toggleValue:function(){if(!this.checked){var A=this.getParent().select("input[name="+this.el.dom.name+"]");A.each(function(B){if(B.dom.id==this.id){this.setValue(true)}else{Ext.getCmp(B.dom.id).setValue(false)}},this)}},setValue:function(A){if(typeof A=="boolean"){Ext.form.Radio.superclass.setValue.call(this,A)}else{var B=this.getParent().child("input[name="+this.el.dom.name+"][value="+A+"]",true);if(B&&!B.checked){Ext.getCmp(B.id).toggleValue()}}},markInvalid:Ext.emptyFn,clearInvalid:Ext.emptyFn});Ext.reg("radio",Ext.form.Radio);