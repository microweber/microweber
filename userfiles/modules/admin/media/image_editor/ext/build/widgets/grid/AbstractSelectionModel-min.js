/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.AbstractSelectionModel=function(){this.locked=false;Ext.grid.AbstractSelectionModel.superclass.constructor.call(this)};Ext.extend(Ext.grid.AbstractSelectionModel,Ext.util.Observable,{init:function(A){this.grid=A;this.initEvents()},lock:function(){this.locked=true},unlock:function(){this.locked=false},isLocked:function(){return this.locked}});