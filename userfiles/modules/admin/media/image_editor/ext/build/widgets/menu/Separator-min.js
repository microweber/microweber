/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.Separator=function(A){Ext.menu.Separator.superclass.constructor.call(this,A)};Ext.extend(Ext.menu.Separator,Ext.menu.BaseItem,{itemCls:"x-menu-sep",hideOnClick:false,onRender:function(A){var B=document.createElement("span");B.className=this.itemCls;B.innerHTML="&#160;";this.el=B;A.addClass("x-menu-sep-li");Ext.menu.Separator.superclass.onRender.apply(this,arguments)}});