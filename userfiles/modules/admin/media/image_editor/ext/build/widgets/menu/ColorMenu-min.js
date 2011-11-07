/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.ColorMenu=function(A){Ext.menu.ColorMenu.superclass.constructor.call(this,A);this.plain=true;var B=new Ext.menu.ColorItem(A);this.add(B);this.palette=B.palette;this.relayEvents(B,["select"])};Ext.extend(Ext.menu.ColorMenu,Ext.menu.Menu);