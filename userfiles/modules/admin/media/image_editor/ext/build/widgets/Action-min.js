/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.Action=function(A){this.initialConfig=A;this.items=[]};Ext.Action.prototype={isAction:true,setText:function(A){this.initialConfig.text=A;this.callEach("setText",[A])},getText:function(){return this.initialConfig.text},setIconClass:function(A){this.initialConfig.iconCls=A;this.callEach("setIconClass",[A])},getIconClass:function(){return this.initialConfig.iconCls},setDisabled:function(A){this.initialConfig.disabled=A;this.callEach("setDisabled",[A])},enable:function(){this.setDisabled(false)},disable:function(){this.setDisabled(true)},isDisabled:function(){return this.initialConfig.disabled},setHidden:function(A){this.initialConfig.hidden=A;this.callEach("setVisible",[!A])},show:function(){this.setHidden(false)},hide:function(){this.setHidden(true)},isHidden:function(){return this.initialConfig.hidden},setHandler:function(B,A){this.initialConfig.handler=B;this.initialConfig.scope=A;this.callEach("setHandler",[B,A])},each:function(B,A){Ext.each(this.items,B,A)},callEach:function(E,B){var D=this.items;for(var C=0,A=D.length;C<A;C++){D[C][E].apply(D[C],B)}},addComponent:function(A){this.items.push(A);A.on("destroy",this.removeComponent,this)},removeComponent:function(A){this.items.remove(A)},execute:function(){this.initialConfig.handler.apply(this.initialConfig.scope||window,arguments)}};