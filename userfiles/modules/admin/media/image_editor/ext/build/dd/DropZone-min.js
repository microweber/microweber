/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.DropZone=function(B,A){Ext.dd.DropZone.superclass.constructor.call(this,B,A)};Ext.extend(Ext.dd.DropZone,Ext.dd.DropTarget,{getTargetFromEvent:function(A){return Ext.dd.Registry.getTargetFromEvent(A)},onNodeEnter:function(D,A,C,B){},onNodeOver:function(D,A,C,B){return this.dropAllowed},onNodeOut:function(D,A,C,B){},onNodeDrop:function(D,A,C,B){return false},onContainerOver:function(A,C,B){return this.dropNotAllowed},onContainerDrop:function(A,C,B){return false},notifyEnter:function(A,C,B){return this.dropNotAllowed},notifyOver:function(A,C,B){var D=this.getTargetFromEvent(C);if(!D){if(this.lastOverNode){this.onNodeOut(this.lastOverNode,A,C,B);this.lastOverNode=null}return this.onContainerOver(A,C,B)}if(this.lastOverNode!=D){if(this.lastOverNode){this.onNodeOut(this.lastOverNode,A,C,B)}this.onNodeEnter(D,A,C,B);this.lastOverNode=D}return this.onNodeOver(D,A,C,B)},notifyOut:function(A,C,B){if(this.lastOverNode){this.onNodeOut(this.lastOverNode,A,C,B);this.lastOverNode=null}},notifyDrop:function(A,C,B){if(this.lastOverNode){this.onNodeOut(this.lastOverNode,A,C,B);this.lastOverNode=null}var D=this.getTargetFromEvent(C);return D?this.onNodeDrop(D,A,C,B):this.onContainerDrop(A,C,B)},triggerCacheRefresh:function(){Ext.dd.DDM.refreshCache(this.groups)}});