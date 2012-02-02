/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.SplitDragZone=function(A,C,B){this.grid=A;this.view=A.getView();this.proxy=this.view.resizeProxy;Ext.grid.SplitDragZone.superclass.constructor.call(this,C,"gridSplitters"+this.grid.getGridEl().id,{dragElId:Ext.id(this.proxy.dom),resizeFrame:false});this.setHandleElId(Ext.id(C));this.setOuterHandleElId(Ext.id(B));this.scroll=false};Ext.extend(Ext.grid.SplitDragZone,Ext.dd.DDProxy,{fly:Ext.Element.fly,b4StartDrag:function(A,D){this.view.headersDisabled=true;this.proxy.setHeight(this.view.mainWrap.getHeight());var B=this.cm.getColumnWidth(this.cellIndex);var C=Math.max(B-this.grid.minColumnWidth,0);this.resetConstraints();this.setXConstraint(C,1000);this.setYConstraint(0,0);this.minX=A-C;this.maxX=A+1000;this.startPos=A;Ext.dd.DDProxy.prototype.b4StartDrag.call(this,A,D)},handleMouseDown:function(B){ev=Ext.EventObject.setEvent(B);var A=this.fly(ev.getTarget());if(A.hasClass("x-grid-split")){this.cellIndex=this.view.getCellIndex(A.dom);this.split=A.dom;this.cm=this.grid.colModel;if(this.cm.isResizable(this.cellIndex)&&!this.cm.isFixed(this.cellIndex)){Ext.grid.SplitDragZone.superclass.handleMouseDown.apply(this,arguments)}}},endDrag:function(C){this.view.headersDisabled=false;var A=Math.max(this.minX,Ext.lib.Event.getPageX(C));var B=A-this.startPos;this.view.onColumnSplitterMoved(this.cellIndex,this.cm.getColumnWidth(this.cellIndex)+B)},autoOffset:function(){this.setDelta(0,0)}});