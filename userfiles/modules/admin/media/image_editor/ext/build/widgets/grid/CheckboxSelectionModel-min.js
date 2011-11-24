/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.CheckboxSelectionModel=Ext.extend(Ext.grid.RowSelectionModel,{header:"<div class=\"x-grid3-hd-checker\">&#160;</div>",width:20,sortable:false,menuDisabled:true,fixed:true,dataIndex:"",id:"checker",initEvents:function(){Ext.grid.CheckboxSelectionModel.superclass.initEvents.call(this);this.grid.on("render",function(){var A=this.grid.getView();A.mainBody.on("mousedown",this.onMouseDown,this);Ext.fly(A.innerHd).on("mousedown",this.onHdMouseDown,this)},this)},onMouseDown:function(C,B){if(C.button===0&&B.className=="x-grid3-row-checker"){C.stopEvent();var D=C.getTarget(".x-grid3-row");if(D){var A=D.rowIndex;if(this.isSelected(A)){this.deselectRow(A)}else{this.selectRow(A,true)}}}},onHdMouseDown:function(C,A){if(A.className=="x-grid3-hd-checker"){C.stopEvent();var B=Ext.fly(A.parentNode);var D=B.hasClass("x-grid3-hd-checker-on");if(D){B.removeClass("x-grid3-hd-checker-on");this.clearSelections()}else{B.addClass("x-grid3-hd-checker-on");this.selectAll()}}},renderer:function(B,C,A){return"<div class=\"x-grid3-row-checker\">&#160;</div>"}});