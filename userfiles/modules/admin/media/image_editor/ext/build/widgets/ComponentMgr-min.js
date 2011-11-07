/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.ComponentMgr=function(){var B=new Ext.util.MixedCollection();var A={};return{register:function(C){B.add(C)},unregister:function(C){B.remove(C)},get:function(C){return B.get(C)},onAvailable:function(E,D,C){B.on("add",function(F,G){if(G.id==E){D.call(C||G,G);B.un("add",D,C)}})},all:B,registerType:function(D,C){A[D]=C;C.xtype=D},create:function(C,D){return new A[C.xtype||D](C)}}}();Ext.reg=Ext.ComponentMgr.registerType;