/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.DateMenu=function(A){Ext.menu.DateMenu.superclass.constructor.call(this,A);this.plain=true;var B=new Ext.menu.DateItem(A);this.add(B);this.picker=B.picker;this.relayEvents(B,["select"]);this.on("beforeshow",function(){if(this.picker){this.picker.hideMonthPicker(true)}},this)};Ext.extend(Ext.menu.DateMenu,Ext.menu.Menu,{cls:"x-date-menu",beforeDestroy:function(){this.picker.destroy()}});