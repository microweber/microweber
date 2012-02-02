/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.MemoryProxy=function(A){Ext.data.MemoryProxy.superclass.constructor.call(this);this.data=A};Ext.extend(Ext.data.MemoryProxy,Ext.data.DataProxy,{load:function(F,C,G,D,B){F=F||{};var A;try{A=C.readRecords(this.data)}catch(E){this.fireEvent("loadexception",this,B,null,E);G.call(D,null,B,false);return }G.call(D,A,B,true)},update:function(B,A){}});