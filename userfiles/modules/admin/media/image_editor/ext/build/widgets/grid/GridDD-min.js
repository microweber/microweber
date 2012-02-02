/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.GridDragZone=function(B,A){this.view=B.getView();Ext.grid.GridDragZone.superclass.constructor.call(this,this.view.mainBody.dom,A);if(this.view.lockedBody){this.setHandleElId(Ext.id(this.view.mainBody.dom));this.setOuterHandleElId(Ext.id(this.view.lockedBody.dom))}this.scroll=false;this.grid=B;this.ddel=document.createElement("div");this.ddel.className="x-grid-dd-wrap"};Ext.extend(Ext.grid.GridDragZone,Ext.dd.DragZone,{ddGroup:"GridDD",getDragData:function(B){var A=Ext.lib.Event.getTarget(B);var D=this.view.findRowIndex(A);if(D!==false){var C=this.grid.selModel;if(!C.isSelected(D)||B.hasModifier()){C.handleMouseDown(this.grid,D,B)}return{grid:this.grid,ddel:this.ddel,rowIndex:D,selections:C.getSelections()}}return false},onInitDrag:function(B){var A=this.dragData;this.ddel.innerHTML=this.grid.getDragDropText();this.proxy.update(this.ddel)},afterRepair:function(){this.dragging=false},getRepairXY:function(B,A){return false},onEndDrag:function(A,B){},onValidDrop:function(A,B,C){this.hideProxy()},beforeInvalidDrop:function(A,B){}});