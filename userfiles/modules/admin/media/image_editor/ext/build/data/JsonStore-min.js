/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.JsonStore=function(A){Ext.data.JsonStore.superclass.constructor.call(this,Ext.apply(A,{proxy:A.proxy||(!A.data?new Ext.data.HttpProxy({url:A.url}):undefined),reader:new Ext.data.JsonReader(A,A.fields)}))};Ext.extend(Ext.data.JsonStore,Ext.data.Store);