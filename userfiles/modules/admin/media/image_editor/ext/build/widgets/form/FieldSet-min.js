/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.form.FieldSet=Ext.extend(Ext.Panel,{baseCls:"x-fieldset",layout:"form",onRender:function(B,A){if(!this.el){this.el=document.createElement("fieldset");this.el.id=this.id;if(this.title||this.header||this.checkboxToggle){this.el.appendChild(document.createElement("legend")).className="x-fieldset-header"}}Ext.form.FieldSet.superclass.onRender.call(this,B,A);if(this.checkboxToggle){var C=typeof this.checkboxToggle=="object"?this.checkboxToggle:{tag:"input",type:"checkbox",name:this.checkboxName||this.id+"-checkbox"};this.checkbox=this.header.insertFirst(C);this.checkbox.dom.checked=!this.collapsed;this.checkbox.on("click",this.onCheckClick,this)}},onCollapse:function(A,B){if(this.checkbox){this.checkbox.dom.checked=false}this.afterCollapse()},onExpand:function(A,B){if(this.checkbox){this.checkbox.dom.checked=true}this.afterExpand()},onCheckClick:function(){this[this.checkbox.dom.checked?"expand":"collapse"]()}});Ext.reg("fieldset",Ext.form.FieldSet);