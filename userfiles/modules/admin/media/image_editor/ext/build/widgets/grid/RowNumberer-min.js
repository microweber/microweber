/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.grid.RowNumberer=function(A){Ext.apply(this,A);if(this.rowspan){this.renderer=this.renderer.createDelegate(this)}};Ext.grid.RowNumberer.prototype={header:"",width:23,sortable:false,fixed:true,menuDisabled:true,dataIndex:"",id:"numberer",rowspan:undefined,renderer:function(B,C,A,D){if(this.rowspan){C.cellAttr="rowspan=\""+this.rowspan+"\""}return D+1}};