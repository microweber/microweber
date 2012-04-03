/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.TextItem=function(A){if(typeof A=="string"){A={text:A}}Ext.menu.TextItem.superclass.constructor.call(this,A)};Ext.extend(Ext.menu.TextItem,Ext.menu.BaseItem,{hideOnClick:false,itemCls:"x-menu-text",onRender:function(){var A=document.createElement("span");A.className=this.itemCls;A.innerHTML=this.text;this.el=A;Ext.menu.TextItem.superclass.onRender.apply(this,arguments)}});