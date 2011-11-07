/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

if(Ext.dd.DragZone){Ext.tree.TreeDragZone=function(A,B){Ext.tree.TreeDragZone.superclass.constructor.call(this,A.getTreeEl(),B);this.tree=A};Ext.extend(Ext.tree.TreeDragZone,Ext.dd.DragZone,{ddGroup:"TreeDD",onBeforeDrag:function(A,B){var C=A.node;return C&&C.draggable&&!C.disabled},onInitDrag:function(B){var A=this.dragData;this.tree.getSelectionModel().select(A.node);this.tree.eventModel.disable();this.proxy.update("");A.node.ui.appendDDGhost(this.proxy.ghost.dom);this.tree.fireEvent("startdrag",this.tree,A.node,B)},getRepairXY:function(B,A){return A.node.ui.getDDRepairXY()},onEndDrag:function(A,B){this.tree.eventModel.enable.defer(100,this.tree.eventModel);this.tree.fireEvent("enddrag",this.tree,A.node,B)},onValidDrop:function(A,B,C){this.tree.fireEvent("dragdrop",this.tree,this.dragData.node,A,B);this.hideProxy()},beforeInvalidDrop:function(A,C){var B=this.tree.getSelectionModel();B.clearSelections();B.select(this.dragData.node)},afterRepair:function(){if(Ext.enableFx&&this.tree.hlDrop){Ext.Element.fly(this.dragData.ddel).highlight(this.hlColor||"c3daf9")}this.dragging=false}})};