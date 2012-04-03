/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.DateItem=function(A){Ext.menu.DateItem.superclass.constructor.call(this,new Ext.DatePicker(A),A);this.picker=this.component;this.addEvents("select");this.picker.on("render",function(B){B.getEl().swallowEvent("click");B.container.addClass("x-menu-date-item")});this.picker.on("select",this.onSelect,this)};Ext.extend(Ext.menu.DateItem,Ext.menu.Adapter,{onSelect:function(B,A){this.fireEvent("select",this,A,B);Ext.menu.DateItem.superclass.handleClick.call(this)}});