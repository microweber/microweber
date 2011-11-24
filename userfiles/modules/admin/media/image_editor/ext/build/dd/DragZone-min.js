/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.dd.DragZone=function(B,A){Ext.dd.DragZone.superclass.constructor.call(this,B,A);if(this.containerScroll){Ext.dd.ScrollManager.register(this.el)}};Ext.extend(Ext.dd.DragZone,Ext.dd.DragSource,{getDragData:function(A){return Ext.dd.Registry.getHandleFromEvent(A)},onInitDrag:function(A,B){this.proxy.update(this.dragData.ddel.cloneNode(true));this.onStartDrag(A,B);return true},afterRepair:function(){if(Ext.enableFx){Ext.Element.fly(this.dragData.ddel).highlight(this.hlColor||"c3daf9")}this.dragging=false},getRepairXY:function(A){return Ext.Element.fly(this.dragData.ddel).getXY()}});