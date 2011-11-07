/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.menu.Adapter=function(B,A){Ext.menu.Adapter.superclass.constructor.call(this,A);this.component=B};Ext.extend(Ext.menu.Adapter,Ext.menu.BaseItem,{canActivate:true,onRender:function(B,A){this.component.render(B);this.el=this.component.getEl()},activate:function(){if(this.disabled){return false}this.component.focus();this.fireEvent("activate",this);return true},deactivate:function(){this.fireEvent("deactivate",this)},disable:function(){this.component.disable();Ext.menu.Adapter.superclass.disable.call(this)},enable:function(){this.component.enable();Ext.menu.Adapter.superclass.enable.call(this)}});