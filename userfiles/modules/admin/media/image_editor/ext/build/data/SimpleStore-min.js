/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.SimpleStore=function(A){Ext.data.SimpleStore.superclass.constructor.call(this,Ext.apply(A,{reader:new Ext.data.ArrayReader({id:A.id},Ext.data.Record.create(A.fields))}))};Ext.extend(Ext.data.SimpleStore,Ext.data.Store,{loadData:function(E,B){if(this.expandData===true){var D=[];for(var C=0,A=E.length;C<A;C++){D[D.length]=[E[C]]}E=D}Ext.data.SimpleStore.superclass.loadData.call(this,E,B)}});