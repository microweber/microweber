/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

Ext.data.GroupingStore=Ext.extend(Ext.data.Store,{remoteGroup:false,groupOnSort:false,clearGrouping:function(){this.groupField=false;if(this.remoteGroup){if(this.baseParams){delete this.baseParams.groupBy}this.reload()}else{this.applySort();this.fireEvent("datachanged",this)}},groupBy:function(C,B){if(this.groupField==C&&!B){return }this.groupField=C;if(this.remoteGroup){if(!this.baseParams){this.baseParams={}}this.baseParams["groupBy"]=C}if(this.groupOnSort){this.sort(C);return }if(this.remoteGroup){this.reload()}else{var A=this.sortInfo||{};if(A.field!=C){this.applySort()}else{this.sortData(C)}this.fireEvent("datachanged",this)}},applySort:function(){Ext.data.GroupingStore.superclass.applySort.call(this);if(!this.groupOnSort&&!this.remoteGroup){var A=this.getGroupState();if(A&&A!=this.sortInfo.field){this.sortData(this.groupField)}}},applyGrouping:function(A){if(this.groupField!==false){this.groupBy(this.groupField,true);return true}else{if(A===true){this.fireEvent("datachanged",this)}return false}},getGroupState:function(){return this.groupOnSort&&this.groupField!==false?(this.sortInfo?this.sortInfo.field:undefined):this.groupField}});